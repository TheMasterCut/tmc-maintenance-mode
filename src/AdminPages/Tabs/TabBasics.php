<?php
namespace tmc\mm\src\AdminPages\Tabs;

/**
 * @author jakubkuranda@gmail.com
 * Date: 10.05.2018
 * Time: 13:03
 */

use shellpress\v1_2_3\src\Shared\AdminPageFramework\AdminPageTab;
use tmc\mm\src\App;

class TabBasics extends AdminPageTab {

    /**
     * Declaration of current element.
     */
    public function setUp() {

        $this->pageFactory->addInPageTabs(
            array(
                'title'         =>  __( 'Basics', 'tmc_mm' ),
                'page_slug'     =>  $this->pageSlug,
                'tab_slug'      =>  $this->tabSlug
            )
        );

    }

    /**
     * Called while current component is loaded.
     */
    public function load() {

        //  ----------------------------------------
        //  Sections
        //  ----------------------------------------

        $this->pageFactory->addSettingSections(
            array(
                'section_id'        =>  'basics'
            )
        );

        //  ----------------------------------------
        //  Fields
        //  ----------------------------------------

        $this->pageFactory->addSettingFields(
            'basics',   //  section_id
            array(
                'field_id'          =>  'status',
                'type'              =>  'toggle',
                'title'             =>  __( 'Status', 'tmc_mm' ),
                'theme'             =>  'light'
            ),
            array(
                'field_id'          =>  'lockDownType',
                'type'              =>  'radioreveal',
                'title'             =>  __( 'Type of lock-down', 'tmc_mm' ),
                'label'             =>  array(
                    'whitelist'         =>  sprintf( '%1$s <br/><small style="color: gray;">%2$s</small>',
	                                            __( 'Whitelist', 'tmc_mm' ),
	                                            __( 'Locks only front-end', 'tmc_mm' )
                                            ),
                    'password'          =>  sprintf( '%1$s <br/><small style="color: gray;">%2$s</small>',
							                    __( 'Password', 'tmc_mm' ),
							                    __( 'Locks whole site', 'tmc_mm' )
						                    ),
                ),
                'reveals'           =>  array(
                    'whitelist'         =>  '#fieldrow-basics_whitelistedIps',
                    'password'          =>  '#fieldrow-basics_credentials'
                ),
                'default'           =>  'whitelist'
            ),
            array(
                'field_id'          =>  'whitelistedIps',
                'type'              =>  'textarea',
                'title'             =>  __( 'Whitelisted IP\'s', 'tmc_mm' ),
                'before_field'      =>  sprintf( '<p>%1$s <code>%2$s</code></p><p>%3$s</p><br/>',
                    __( 'Your current IP is', 'tmc_mm' ),
                    $_SERVER['REMOTE_ADDR'],
                    __( 'Enter one IP address per line.', 'tmc_mm' )
                ),
                'attributes'        =>  array(
                    'rows'              =>  6
                )
            ),
            array(
                'field_id'          =>  'credentials',
                'type'              =>  'inline_mixed',
                'title'             =>  __( 'Credentials', 'tmc_mm' ),
                'tip'               =>  __( 'You can insert multiple credentials.', 'tmc_mm' ),
                'repeatable'        =>  true,
                'attributes'        =>  array(
                    'fieldset'          =>  array(
                        'style'             =>  'max-width: 400px;'
                    )
                ),
                'content'           =>  array(
                    array(
                        'field_id'      =>  'login',
                        'type'          =>  'text',
                        'attributes'    =>  array(
                            'placeholder'   =>  __( 'login', 'tmc_mm' )
                        )
                    ),
                    array(
                        'field_id'      =>  'password',
                        'type'          =>  'text',
                        'attributes'    =>  array(
                            'placeholder'   =>  __( 'password', 'tmc_mm' )
                        )
                    )
                )
            ),
            array(
                'field_id'          =>  'message',
                'type'              =>  'ace',
                'title'             =>  __( 'Message', 'tmc_mm' ),
                'attributes'        =>  array(
                    'rows'          =>  15,
                    'cols'          =>  100
                ),
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

	    //  ----------------------------------------
	    //  Filters
	    //  ----------------------------------------

	    add_filter( 'fields_' . $this->pageFactoryClassName,    array( $this, '_f_modifyFields' ) );

    }

    //  ================================================================================
    //  FILTERS
    //  ================================================================================

	/**
	 * Called on fields_{factoryClassName}.
	 * Modifies fields based on licensing.
	 *
	 * @param array $fields
	 *
	 * @return mixed
	 */
	public function _f_modifyFields( $fields ) {

		if( ! App::i()->license->isActive() ){

			$fields['basics']['status']['attributes']['disabled'] = 'disabled';
			$fields['basics']['status']['before_field'] =
				sprintf( '<a href="%1$s" style="color: red;">%2$s</a><br/>',
					admin_url( 'options-general.php?page=tmc_mm_settings&tab=tools' ),
					__( 'You need to enter license in order to activate or deactivate lock-down.', 'tmc_mm' )
				);

		}

    	return $fields;

	}

}