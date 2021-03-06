<?php
namespace tmc\mm\src\AdminPages\Tabs;

/**
 * @author jakubkuranda@gmail.com
 * Date: 10.05.2018
 * Time: 13:04
 */

use shellpress\v1_2_5\src\Shared\AdminPageFramework\AdminPageTab;
use tmc\mm\src\App;

class TabTools extends AdminPageTab {

    /**
     * Declaration of current element.
     */
    public function setUp() {

        $this->pageFactory->addInPageTabs(
            array(
                'title'         =>  __( 'Tools', 'tmc_mm' ),
                'page_slug'     =>  $this->pageSlug,
                'tab_slug'      =>  $this->tabSlug,
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
//            array(
//            	'section_id'        =>  'dbExport',
//	            'page_slug'         =>  $this->pageSlug,
//	            'tab_slug'          =>  $this->tabSlug,
//	            'order'             =>  5,
//	            'title'             =>  __( 'Database export', 'tmc_mm' )
//            ),
	        array(
		        'section_id'        =>  'multisite',
		        'page_slug'         =>  $this->pageSlug,
		        'tab_slug'          =>  $this->tabSlug,
		        'order'             =>  10,
		        'title'             =>  __( 'Multisite', 'tmc_mm' )
	        )
        );

        //  ----------------------------------------
        //  Fields
        //  ----------------------------------------

//	    $this->pageFactory->addSettingFields(
//	    	'dbExport',
//		    array(
//		    	'field_id'              =>  'tablesNames',
//			    'type'                  =>  'checkbox',
//			    'title'                 =>  __( 'Tables to export', 'tmc_mm' ),
//			    'label'                 =>  $this->getTablesNamesAsLabelForField(),
//			    'select_all_button'     =>  __( 'Select all', 'tmc_mm' ),
//			    'select_none_button'    =>  __( 'Select none', 'tmc_mm' ),
//			    'attributes'            =>  array(
//			    	'field'                 =>  array(
//			    		'style'                 =>  'max-height: 200px; overflow: auto;'
//				    )
//			    )
//		    ),
//		    array(
//				'field_id'              =>  'exportDatabase',
//			    'type'                  =>  'submit',
//			    'value'                 =>  __( 'Download tables', 'tmc_mm' ),
//			    'attributes'            =>  array(
//			    	'class'                 =>  'button-secondary'
//			    )
//		    )
//	    );

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

	/**
	 * Returns array of name => name.
	 *
	 * @return string[]
	 */
    private function getTablesNamesAsLabelForField() {

	    $names = App::i()->dbExporter->getAllTablesNames();

	    $results = array();
	    foreach( $names as $name ){
		    $results[$name] = $name;
	    }

	    return $results;

    }

}