<?php
namespace tmc\mm\src\Components;

/**
 * Date: 07.04.2018
 * Time: 19:50
 */

use shellpress\v1_2_4\src\Shared\Components\IComponent;
use tmc\mm\src\App;
use WP_Admin_Bar;

class Toolbar extends IComponent {

    /**
     * Called on creation of component.
     *
     * @return void
     */
    protected function onSetUp() {

        add_action( 'admin_bar_menu',   array( $this, '_a_createToolbarMenu' ), 1000 );

        add_action( 'init',             array( $this, '_a_toggleStatusFromUrlCallback' ) );

    }

	/**
	 * @param WP_Admin_Bar $wpAdminBar
	 */
	public function _a_createToolbarMenu( $wpAdminBar ) {

	    if( ! current_user_can( 'manage_options' ) ) return;

		if( App::i()->settings->getStatus() ){
			$title = __( 'Click to unlock website', 'tmc_mm' );
			$label = '<i class="dashicons dashicons-lock" style="font-family: dashicons, serif; color: #C0392B;"></i>';
		} else {
			$title = __( 'Click to lock website', 'tmc_mm' );
			$label = '<i class="dashicons dashicons-unlock" style="font-family: dashicons, serif;"></i>';
		}

		//  If not in admin area, redirect to settings page

		$url = is_admin() ? $_SERVER['REQUEST_URI'] : admin_url( 'options-general.php?page=tmc_mm_settings' );
		$url = wp_nonce_url( $url, 'tmc_mm_toggle', 'tmc_mm_toggle' );

		$wpAdminBar->add_menu(
			array(
				'id'            =>  'tmc_mm_menu',
				'title'         =>  $label,
				'meta'          =>  array(
					'title'         =>  $title
				),
				'href'          =>  $url
			)
		);

	}

	/**
	 * Toggles plugin status with nonce check.
	 * Called on init.
	 *
	 * @return void
	 */
	public function _a_toggleStatusFromUrlCallback() {

		if( isset( $_GET['tmc_mm_toggle'] ) ){

		    if( ! current_user_can( 'manage_options' ) ){
		        wp_die( __( 'Sorry but you don\'t have permission to toggle maintenance mode.', 'tmc_mm' ) );
            }

            if( ! App::i()->license->isActive() ){
	            wp_die( __( 'It looks like your license is not active.', 'tmc_mm' ) );
            }

			App::s()->log->info( 'Clicked toggle button.' );

			$verified = (bool) wp_verify_nonce( $_GET['tmc_mm_toggle'], 'tmc_mm_toggle' );

			if( $verified ){

				$newStatus = App::i()->settings->getInvertedStatus();

				App::i()->settings->setStatus( $newStatus );
				App::s()->options->flush();

			}

			wp_redirect( remove_query_arg( 'tmc_mm_toggle' ) );
			exit();

		}

	}

}