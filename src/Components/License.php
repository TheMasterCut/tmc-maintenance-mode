<?php
namespace tmc\mm\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 22.02.2018
 * Time: 11:22
 */

use shellpress\v1_2_1\src\Shared\Components\LicenseManagerSLM;
use WP_Upgrader;

class License extends LicenseManagerSLM {

    const CHECK_LICENSE_CRON_JOB_NAME = 'rm_tmc_check_license';

    /**
     * Called on object creation.
     *
     * @return void
     */
    protected function onSetUp() {

        //  ----------------------------------------
        //  Actions
        //  ----------------------------------------

        add_action( 'admin_notices',                            array( $this, '_a_displayLicenseNotification' ) );

        $this::s()->event->addOnActivate(                       array( $this, '_a_registerCronJob' ) );
        $this::s()->event->addOnDeactivate(                     array( $this, '_a_unregisterCronJob' ) );

        add_action( $this::CHECK_LICENSE_CRON_JOB_NAME,         array( $this, '_a_doCheckLicenseCronCallback' ) );

        add_action( 'upgrader_process_complete',                array( $this, '_a_handlePluginUpdateCronJobs' ), 10, 2 );

    }

    /**
     * Called when key has been activated.
     *
     * @return void
     */
    protected function onKeyActivationCallback() {
        // TODO: Implement onKeyActivationCallback() method.
    }

    /**
     * Called when key has been deactivated.
     *
     * @return void
     */
    protected function onKeyDeactivationCallback() {
        // TODO: Implement onKeyDeactivationCallback() method.
    }

    //  ================================================================================
    //  FILTERS
    //  ================================================================================

    /**
     * Adds more intervals.
     *
     * @param array $schedules
     *
     * @return array
     */
    public function _f_modifyCronIntervals( $schedules ) {

        $schedules['daily'] = array(
            'interval'  =>  86400,
            'display'   =>  __( 'Once a day' )
        );

        return $schedules;

    }

    //  ================================================================================
    //  Actions
    //  ================================================================================

    /**
     * Display error notice if passed license is inactive.
     * Called on admin_notices hook.
     *
     * @return void
     */
    public function _a_displayLicenseNotification() {

        if( $this->getKey() && ! $this->isActive() ){    //  If key is set and is inactive.

            $adminPageUrl = admin_url( 'options-general.php?page=tmc_mm_settings&tab=tools' );

            printf( '<div class="notice notice-error"><p>%1$s %2$s</p></div>',
                __( 'Your license for The Real Maintenance Mode TMC is inactive.', 'rm_tmc' ),
                sprintf( '<a href="%1$s">%2$s</a>', $adminPageUrl, __( 'Please remove license to dismiss this message.', 'rm_tmc' ) )
            );

        }

    }

    /**
     * If plugin was updated, plugin activation hook is not called.
     * This method fixes it!
     *
     * Called on upgrader_process_complete.
     *
     * @param WP_Upgrader $upgrader
     * @param array $options
     *
     * @return void
     */
    public function _a_handlePluginUpdateCronJobs( $upgrader, $options ) {

        if( $options['action'] == 'update' && $options['type'] == 'plugin' ){   //  Updating plugin.

            $pluginName = plugin_basename( $this::s()->getMainPluginFile() );

            if( is_array( $options['plugins'] ) && in_array( $pluginName, $options['plugins'] ) ){  //  Updating THIS plugin

                add_action( 'init', array( $this, '_a_registerCronJob' ) );

            }

        }

    }

    /**
     * Called on plugin activation.
     *
     * @return void
     */
    public function _a_registerCronJob() {

        if( ! wp_next_scheduled( $this::CHECK_LICENSE_CRON_JOB_NAME ) ){

            wp_schedule_event( time(), 'daily', $this::CHECK_LICENSE_CRON_JOB_NAME );

            $this::s()->log->info( 'Added cron job: ' . $this::CHECK_LICENSE_CRON_JOB_NAME );

        }

    }

    /**
     * Called on plugin deactivation.
     *
     * @return void
     */
    public function _a_unregisterCronJob() {

        wp_clear_scheduled_hook( $this::CHECK_LICENSE_CRON_JOB_NAME );

    }

    /**
     * Does remote requests and checks given license key.
     *
     * @return void
     */
    public function _a_doCheckLicenseCronCallback() {

        $this::s()->log->info( 'START LICENSE CHECK CRON JOB' );

        $key = $this->getKey();

        if( $key ){
            $this->performRemoteKeyUpdate();
        }

        $this::s()->options->flush();

    }

}