<?php
namespace tmc\mm\src\AdminPages\Tabs;

/**
 * @author jakubkuranda@gmail.com
 * Date: 10.05.2018
 * Time: 13:03
 */

use shellpress\v1_2_1\src\Shared\AdminPageFramework\AdminPageTab;

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

    }

}