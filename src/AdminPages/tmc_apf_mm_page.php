<?php
use tmc\mm\src\App;

/**
 * Date: 19.03.2018
 * Time: 20:53
 */

class tmc_apf_mm_page extends TMC_v1_0_3_AdminPageFramework  {

	public function setUp() {

		//  ----------------------------------------
		//  Definition
		//  ----------------------------------------

		$this->oProp->bShowDebugInfo = false;

		$this->setRootMenuPage( 'Settings' );

		$this->addSubMenuItems(
			array(
				'title'         =>  'The real Maintenance Mode TMC',
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

		$this->enqueueStyle( App::shell()->getUrl( '/lib/ShellPress/assets/css/AdminPage/style.css' ), '', '', array( 'version' => App::shell()->getFullPluginVersion() ) );

		//  ----------------------------------------
		//  Custom field types
		//  ----------------------------------------

		App::shell()->requireFile( 'lib/tmc-admin-page-framework/custom-field-types/toggle-custom-field-type/ToggleCustomFieldType.php' );
		App::shell()->requireFile( 'lib/tmc-admin-page-framework/custom-field-types/ace-custom-field-type/AceCustomFieldType.php' );

		new TMC_v1_0_3_ToggleCustomFieldType();
		new TMC_v1_0_3_AceCustomFieldType();

		//  ----------------------------------------
		//  Sections
		//  ----------------------------------------

		$this->addSettingSections(
			array(
				'section_id'        =>  'basics'
			)
		);

		//  ----------------------------------------
		//  Fields
		//  ----------------------------------------

		$this->addSettingFields(
			'basics',   //  section_id
			array(
				'field_id'          =>  'status',
				'type'              =>  'toggle',
				'title'             =>  __( 'Status', 'tmc_mm' ),
				'theme'             =>  'light'
			),
			array(
				'field_id'          =>  'whitelistedIps',
				'type'              =>  'textarea',
				'title'             =>  __( 'Whitelisted IP\'s', 'tmc_mm' ),
				'before_field'      =>  sprintf( '<p>%1$s <code>%2$s</code></p><br/>',
											__( 'Your current IP is', 'tmc_mm' ),
											$_SERVER['REMOTE_ADDR']
										),
				'attributes'        =>  array(
					'rows'              =>  6
				),
				'description'       =>  __( 'Enter one IP address per line.', 'tmc_mm' )
			),
			array(
				'field_id'          =>  'message',
				'type'              =>  'ace',
				'title'             =>  __( 'Message', 'tmc_mm' ),
				'options'           => array(
					'language'          =>  'html',
					'theme'             =>  'chrome',
					'fontsize'          =>  12,
					'gutter'            =>  false
				)
			),
			array(
				'field_id'          =>  'pageBg',
				'type'              =>  'color',
				'title'             =>  __( 'Background', 'tmc_mm' )
			),
			array(
				'field_id'          =>  'boxBg',
				'type'              =>  'color',
				'title'             =>  __( 'Box', 'tmc_mm' )
			),
			array(
				'field_id'          =>  'textColor',
				'type'              =>  'color',
				'title'             =>  __( 'Text', 'tmc_mm' )
			),
			array(
				'field_id'          =>  'submit',
				'type'              =>  'submit',
				'save'              =>  false,
				'label'             =>  __( 'Update settings', 'tmc_mm' )
			)
		);

	}

	//  ================================================================================
	//  ACTIONS
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

		$newOptions['basics']['_status'] = 'lol';

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

		$html .= '<br/>';
		$html .= __( 'This plugin generates static front-page file for non-whitelisted users.', 'tmc_mm' );
		$html .= '<br/>';
		$html .= __( 'It will work even if your WordPress breaks.', 'tmc_mm' );
		$html .= '<p>';
//		$html .= sprintf( '<i>%1$s</i> %2$s', __( 'Wow! This is pretty fucking cool!', 'tmc_mm' ), __( '- Said little Tommy.', 'tmc_mm' ) );
//		$html .= '<br/>';
//		$html .= __( 'Now you can test your broken shit and nobody will notice!', 'tmc_mm' );
		$html .= '</p>';

		return $html;

	}

}