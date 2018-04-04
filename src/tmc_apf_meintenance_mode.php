<?php
use tmc\mm\src\App;

/**
 * Date: 19.03.2018
 * Time: 20:53
 */

class tmc_apf_maintenance_mode extends TMC_v1_0_3_AdminPageFramework  {

	public function setUp() {

		//  ----------------------------------------
		//  Definition
		//  ----------------------------------------

		$this->setRootMenuPage( 'Settings' );

		$this->addSubMenuItems(
			array(
				'title'         =>  'TMC Maintenance',
				'page_slug'     =>  'tmc_mm_settings',
			)
		);

	}

	public function load() {

		//  ----------------------------------------
		//  Styles
		//  ----------------------------------------

		$this->enqueueStyle( App::shell()->getUrl( '/lib/tmc-admin-page-framework/custom-styles/style.css' ), '', '', array( 'version' => App::shell()->getFullPluginVersion() ) );

	}

}