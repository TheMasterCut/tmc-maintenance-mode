<?php
namespace tmc\mm\src\AdminPages\Tabs;

/**
 * @author jakubkuranda@gmail.com
 * Date: 10.05.2018
 * Time: 13:04
 */

use shellpress\v1_2_2\src\Shared\AdminPageFramework\AdminPageTab;

class TabTools extends AdminPageTab {

    /**
     * Declaration of current element.
     */
    public function setUp() {

        $this->pageFactory->addInPageTabs(
            array(
                'title'         =>  __( 'Tools', 'tmc_mm' ),
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
                'section_id'        =>  'multisite',
                'page_slug'         =>  $this->pageSlug,
                'tab_slug'          =>  $this->tabSlug,
                'order'             =>  5,
                'title'             =>  __( 'Multisite', 'tmc_mm' )
            )
        );

        //  ----------------------------------------
        //  Fields
        //  ----------------------------------------

        $this->pageFactory->addSettingFields(
            'multisite',
            array(
                'field_id'          =>  'exportSettings',
                'type'              =>  'export',
                'title'             =>  'Export',
                'file_name'         =>  'tmc-maintenance-mode-settings.txt',
                'label'             =>  __( 'Download settings as file', 'tmc_mm' ),
                'save'              =>  false,
                'attributes'        =>  array(
                    'class'             =>  'button-secondary'
                )
            ),
            array(
                'field_id'          =>  'importSettings',
                'type'              =>  'import',
                'title'             =>  'Import',
                'label'             =>  __( 'Import settings', 'tmc_mm' ),
                'save'              =>  false,
                'attributes'        =>  array(
                    'class'             =>  'button-secondary'
                ),
            )
        );

    }

}