<?php
namespace tmc\mm\src;

use shellpress\v1_2_3\ShellPress;
use tmc\mm\src\Components\DbExporter;
use tmc\mm\src\Components\Front;
use tmc\mm\src\Components\Apache;
use tmc\mm\src\Components\License;
use tmc\mm\src\Components\Settings;
use tmc\mm\src\Components\Toolbar;
use tmc\mm\src\Components\Update;
use tmc_mm_apf_page;

class App extends ShellPress {

	/** @var Settings */
	public $settings;

	/** @var Apache */
	public $apache;

	/** @var Toolbar */
	public $toolbar;

	/** @var Front */
	public $front;

	/** @var License */
	public $license;

	/** @var DbExporter */
	public $dbExporter;

	/**
	 * Called automatically after core is ready.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		//  ----------------------------------------
		//  Autoloading
		//  ----------------------------------------

		App::s()->autoloading->addNamespace( 'tmc\mm', dirname( App::s()->getMainPluginFile() ) );

		//  ----------------------------------------
		//  Components
		//  ----------------------------------------

		$this->settings   = new Settings( $this );
		$this->license    = new License( $this );
		$this->apache     = new Apache( $this );
		$this->toolbar    = new Toolbar( $this );
		$this->front      = new Front( $this );
		$this->update     = new Update( $this );
		$this->dbExporter = new DbExporter( $this );

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

}