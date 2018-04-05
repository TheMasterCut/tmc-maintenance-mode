<?php
namespace tmc\mm\src;

use shellpress\v1_1_7\ShellPress;
use tmc\mm\src\Components\Htaccess;
use tmc_apf_maintenance_mode;

class App extends ShellPress {

	/** @var Htaccess */
	public $htaccess;

	/**
	 * Called automatically after core is ready.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Autoloading
		//  ----------------------------------------

		App::shell()->autoloading->addNamespace( 'tmc\mm', dirname( App::shell()->getMainPluginFile() ) );

		//  ----------------------------------------
		//  Components
		//  ----------------------------------------

		$this->htaccess = new Htaccess();
		wp_die( $this->htaccess->getContent() );

		//  ----------------------------------------
		//  Pages
		//  ----------------------------------------

		if( is_admin() && ! wp_doing_ajax() && ! defined( 'DOING_CRON' ) ){

			App::shell()->requireFile( '/lib/tmc-admin-page-framework/admin-page-framework.php', 'TMC_v1_0_3_AdminPageFramework' );
			App::shell()->requireFile( '/src/tmc_apf_maintenance_mode.php' );

			new tmc_apf_maintenance_mode(
				App::shell()->options->getOptionsKey(),
				App::shell()->getMainPluginFile()
			);

		}

	}

}