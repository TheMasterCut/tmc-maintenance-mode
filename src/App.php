<?php
namespace tmc\mm\src;

use shellpress\v1_1_7\ShellPress;
use tmc_apf_maintenance_mode;

class App extends ShellPress {

	/**
	 * Called automatically after core is ready.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Autoloading
		//  ----------------------------------------

		App::shell()->autoloading->addNamespace( 'tmc\mm', __DIR__ );

		//  ----------------------------------------
		//  Pages
		//  ----------------------------------------

		if( is_admin() && ! wp_doing_ajax() ){

			App::shell()->requireFile( '/lib/tmc-admin-page-framework/admin-page-framework.php', 'TMC_v1_0_3_AdminPageFramework' );
			App::shell()->requireFile( '/src/tmc_apf_maintenance_mode.php' );

			new tmc_apf_maintenance_mode(
				App::shell()->options->getOptionsKey(),
				App::shell()->getMainPluginFile()
			);

		}

	}

}