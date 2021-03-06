<?php

use tmc\mm\src\AdminPages\Tabs\TabBasics;
use tmc\mm\src\AdminPages\Tabs\TabTools;
use tmc\mm\src\App;

/**
 * Date: 19.03.2018
 * Time: 20:53
 */

class tmc_mm_apf_page extends TMC_v1_0_3_AdminPageFramework  {

	public function setUp() {

		//  ----------------------------------------
		//  Definition
		//  ----------------------------------------

		$this->oProp->bShowDebugInfo = false;

		$this->setRootMenuPage( 'Settings' );
		$this->setPageTitleVisibility( false );

		$this->addSubMenuItems(
			array(
				'title'         =>  'The Real Maintenance Mode TMC',
				'page_slug'     =>  'tmc_mm_settings',
			)
		);

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		add_filter( 'validation_' . $this->oProp->sClassName,       array( $this, '_f_toggleStatusDifference' ) );
		add_filter( 'content_top_' . $this->oProp->sClassName,      array( $this, '_f_modifyContentTop' ) );

	}

	public function load() {

		//  ----------------------------------------
		//  Styles
		//  ----------------------------------------

		$this->enqueueStyle( App::s()->getUrl( '/lib/ShellPress/assets/css/AdminPage/style.css' ), '', '', array( 'version' => App::s()->getFullPluginVersion() ) );

		//  ----------------------------------------
		//  Custom field types
		//  ----------------------------------------

		App::s()->requireFile( 'lib/tmc-admin-page-framework/custom-field-types/toggle-custom-field-type/ToggleCustomFieldType.php' );
		App::s()->requireFile( 'lib/tmc-admin-page-framework/custom-field-types/ace-custom-field-type/AceCustomFieldType.php' );
		App::s()->requireFile( 'lib/tmc-admin-page-framework/custom-field-types/radio-reveal-field-type/RadioRevealFieldType.php' );

		new TMC_v1_0_3_ToggleCustomFieldType();
		new TMC_v1_0_3_AceCustomFieldType();
		new TMC_v1_0_3_RadioRevealFieldType();

		//  ----------------------------------------
		//  Tabs
		//  ----------------------------------------

        new TabBasics( $this, 'tmc_mm_settings', 'basics' );
        new TabTools( $this, 'tmc_mm_settings', 'tools' );

	}

	//  ================================================================================
	//  FILTERS
	//  ================================================================================

	/**
	 * Called on form validation.
	 *
	 * @param array $newOptions
	 * @param array $oldOptions
	 *
	 * @return array
	 */
	public function _f_toggleStatusDifference( $newOptions ) {

		$newOptions['basics']['_status'] = '';

		return $newOptions;

	}

	/**
	 * Called on content_top_*.
	 *
	 * @param string $html
	 *
	 * @return string
	 */
	public function _f_modifyContentTop( $html ) {

	    $headHtml = '';
	    $headHtml .= sprintf( '<h1>%1$s</h1>', __( 'The Real Maintenance Mode TMC', 'tmc_mm' ) );
		$headHtml .= '<br/>';
		$headHtml .= __( 'This plugin generates static front-page file for non-whitelisted users.', 'tmc_mm' );
		$headHtml .= '<br/>';
		$headHtml .= __( 'It will work even if your WordPress breaks.', 'tmc_mm' );
		$headHtml .= '<p>';
		$headHtml .= sprintf( '<i>%1$s</i> %2$s', __( 'Wow! This is pretty fucking cool!', 'tmc_mm' ), __( '- Said little Tommy.', 'tmc_mm' ) );
		$headHtml .= '<br/>';
		$headHtml .= __( 'Now you can test your broken shit and nobody will notice!', 'tmc_mm' );
		$headHtml .= '</p>';

		return $headHtml . $html;

	}

}