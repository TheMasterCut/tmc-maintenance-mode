<?php
namespace tmc\mm\src;

use shellpress\v1_1_7\ShellPress;
use tmc\mm\src\Components\Front;
use tmc\mm\src\Components\Htaccess;
use tmc\mm\src\Components\Settings;
use tmc\mm\src\Components\Toolbar;
use tmc_apf_maintenance_mode;

class App extends ShellPress {

	/** @var Settings */
	public $settings;

	/** @var Htaccess */
	public $htaccess;

	/** @var Toolbar */
	public $toolbar;

	/** @var Front */
	public $front;

	/**
	 * Called automatically after core is ready.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Default settings
		//  ----------------------------------------

		App::shell()->options->setDefaultOptions( array(
			'_status'       =>  '0',
			'status'        =>  '0',
			'addresses'     =>  array( $_SERVER['REMOTE_ADDR'] ),
			'message'       =>  '',
			'pageBg'        =>  '#ECF0F1',
			'boxBg'         =>  '#2C3E50',
			'textColor'     =>  '#ECF0F1'
		) );

		App::shell()->event->addOnActivate( array( $this, '_a_loadDefaultSettings' ) );

		//  ----------------------------------------
		//  Autoloading
		//  ----------------------------------------

		App::shell()->autoloading->addNamespace( 'tmc\mm', dirname( App::shell()->getMainPluginFile() ) );

		//  ----------------------------------------
		//  Components
		//  ----------------------------------------

		$this->settings = new Settings();
		$this->htaccess = new Htaccess();
		$this->toolbar = new Toolbar();
		$this->front = new Front();

		//  ----------------------------------------
		//  Pages
		//  ----------------------------------------

		if( is_admin() && ! wp_doing_ajax() && ! defined( 'DOING_CRON' ) ){

			App::shell()->requireFile( '/lib/tmc-admin-page-framework/admin-page-framework.php', 'TMC_v1_0_3_AdminPageFramework' );
			App::shell()->requireFile( '/src/AdminPages/tmc_apf_maintenance_mode.php' );

			new tmc_apf_maintenance_mode(
				App::shell()->options->getOptionsKey(),
				App::shell()->getMainPluginFile()
			);

		}

	}

	/**
	 * Called on activate.
	 *
	 * @return void
	 */
	public function _a_loadDefaultSettings() {

		App::shell()->options->fillDifferencies();
		App::shell()->options->flush();

	}

}