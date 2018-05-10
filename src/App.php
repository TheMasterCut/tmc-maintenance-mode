<?php
namespace tmc\mm\src;

use shellpress\v1_2_1\ShellPress;
use tmc\mm\src\Components\Front;
use tmc\mm\src\Components\Htaccess;
use tmc\mm\src\Components\License;
use tmc\mm\src\Components\Settings;
use tmc\mm\src\Components\Toolbar;
use tmc_mm_apf_page;

class App extends ShellPress {

	/** @var Settings */
	public $settings;

	/** @var Htaccess */
	public $htaccess;

	/** @var Toolbar */
	public $toolbar;

	/** @var Front */
	public $front;

	/** @var License */
	public $license;

	/**
	 * Called automatically after core is ready.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Default settings
		//  ----------------------------------------

		App::s()->options->setDefaultOptions( array(
			'basics'        =>  array(
				'_status'           =>  '0',
				'status'            =>  '0',
				'whitelistedIps'    =>  $_SERVER['REMOTE_ADDR'] . PHP_EOL . 'localhost',
				'message'           =>  '<h2>Site under maintenance.</h2>' . PHP_EOL . '<p>We will be back soon.</p>',
				'pageBg'            =>  '#ECF0F1',
				'boxBg'             =>  '#2C3E50',
				'textColor'         =>  '#ECF0F1'
			)
		) );

		App::s()->options->load();

		App::s()->event->addOnActivate( array( $this, '_a_loadDefaultSettings' ) );

		//  ----------------------------------------
		//  Autoloading
		//  ----------------------------------------

		App::s()->autoloading->addNamespace( 'tmc\mm', dirname( App::s()->getMainPluginFile() ) );

		//  ----------------------------------------
		//  Components
		//  ----------------------------------------

		$this->settings = new Settings( $this );
		$this->license = new License( $this );
		$this->htaccess = new Htaccess( $this );
		$this->toolbar = new Toolbar( $this );
		$this->front = new Front( $this );

		//  ----------------------------------------
		//  Pages
		//  ----------------------------------------

		if( is_admin() && ! wp_doing_ajax() && ! defined( 'DOING_CRON' ) ){

			App::s()->requireFile( '/lib/tmc-admin-page-framework/admin-page-framework.php', 'TMC_v1_0_3_AdminPageFramework' );
			App::s()->requireFile( '/src/AdminPages/tmc_mm_apf_page.php' );

			new tmc_mm_apf_page(
				App::s()->options->getOptionsKey(),
				App::s()->getMainPluginFile()
			);

		}

	}

	/**
	 * Called on activate.
	 *
	 * @return void
	 */
	public function _a_loadDefaultSettings() {

		App::s()->options->fillDifferencies();
		App::s()->options->flush();

	}

}