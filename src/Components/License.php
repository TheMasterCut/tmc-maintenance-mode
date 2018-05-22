<?php
namespace tmc\mm\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 22.02.2018
 * Time: 11:22
 */

use shellpress\v1_2_2\src\Shared\Components\LicenseManagerSLM;

class License extends LicenseManagerSLM {

    /**
     * Called on object creation.
     *
     * @return void
     */
    protected function onSetUp() {

        $this->registerAPFForm( 'tmc_mm_apf_page', 'tmc_mm_settings', 'tools', 'license' );
        $this->registerNotices();
        $this->registerAutomaticChecker();

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

}