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

		$this->oProp->bShowDebugInfo = false;

		$this->setRootMenuPage( 'Settings' );

		$this->addSubMenuItems(
			array(
				'title'         =>  'TMC Maintenance mode',
				'page_slug'     =>  'tmc_mm_settings',
			)
		);

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		add_filter( 'validation_' . $this->oProp->sClassName,       array( $this, '_f_toggleStatusDifference' ) );

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

		new TMC_v1_0_3_ToggleCustomFieldType();

		//  ----------------------------------------
		//  Sections
		//  ----------------------------------------

		$this->addSettingSections(
			array(
				'section_id'        =>  '_default',
				'class'             =>  'section-grid'
			)
		);

		//  ----------------------------------------
		//  Fields
		//  ----------------------------------------

		$this->addSettingFields(
			array(
				'field_id'          =>  'status',
				'type'              =>  'toggle',
				'title'             =>  __( 'Status', 'tmc_mm' ),
				'theme'             =>  'light'
			),
			array(
				'field_id'          =>  'addresses',
				'type'              =>  'text',
				'title'             =>  __( 'Addresses', 'tmc_mm' ),
				'repeatable'        =>  true,
				'description'       =>  sprintf( '%1$s <code>%2$s</code>',
											__( 'Your current IP is', 'tmc_mm' ),
											$_SERVER['REMOTE_ADDR']
										)
			),
			array(
				'field_id'          =>  'message',
				'type'              =>  'textarea',
				'title'             =>  __( 'Message', 'tmc_mm' ),
				'rich'              =>  array(
					'media_buttons'     =>  false,
					'teeny'             =>  true,
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

		$newOptions['_status'] = 'lol';

		return $newOptions;

	}

}