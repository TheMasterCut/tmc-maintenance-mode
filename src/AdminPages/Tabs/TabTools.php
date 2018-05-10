<?php
namespace tmc\mm\src\AdminPages\Tabs;

/**
 * @author jakubkuranda@gmail.com
 * Date: 10.05.2018
 * Time: 13:04
 */

use shellpress\v1_2_1\src\Shared\AdminPageFramework\AdminPageTab;
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
                'tab_slug'      =>  $this->tabSlug
            )
        );

        //  ----------------------------------------
        //  Filters
        //  ----------------------------------------

        add_filter( 'validation_' . $this->pageSlug . '_' . $this->tabSlug,         array( $this, '_f_updateKeyButtonCallback' ), 10, 2 );

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
            ),
            array(
                'section_id'            =>  'license',
                'page_slug'             =>  $this->pageSlug,
                'tab_slug'              =>  $this->tabSlug,
                'order'                 =>  10,
                'title'                 =>  __( 'License', 'rm_tmc' ),
                'description'           =>  array(
                    __( 'You can use premium features and extended support.', 'rm_tmc' ),
                    __( 'Some features require external server to provide seamless functionality.', 'rm_tmc' ),
                )
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

        $this->pageFactory->addSettingFields(
            'license',  //  section_id
            array(
                'field_id'          =>  'key',
                'type'              =>  'text',
                'title'             =>  __( 'Key', 'tmc_fbleads' ),
                'attributes'            =>  array(
                    'class'                 =>  'regular-text',
                ),
                'after_input'       =>  sprintf( ' <input type="submit" class="button" name="updateKeySubmit" value="%1$s">', __( 'Update key', 'tmc_fbleads' ) ),
                'after_field'       =>  $this->getLicenseStatusHtml()
            ),
            array(
                'field_id'          =>  'keyExpiryDatetime',
                'type'              =>  'text',
                'title'             =>  'keyExpiryDatetime',
                'hidden'            =>  true,
                'attributes'        =>  array(
                    'disabled'          =>  'disabled'
                )
            ),
            array(
                'field_id'          =>  'lastCheckDatetime',
                'type'              =>  'text',
                'title'             =>  'lastCheckDatetime',
                'hidden'            =>  true,
                'attributes'        =>  array(
                    'disabled'          =>  'disabled'
                )
            ),
            array(
                'field_id'          =>  'keyStatus',
                'type'              =>  'text',
                'title'             =>  'keyStatus',
                'hidden'            =>  true,
                'attributes'        =>  array(
                    'disabled'          =>  'disabled'
                )
            ),
            array(
                'field_id'          =>  'isKeyCorrect',
                'type'              =>  'radio',
                'title'             =>  'isKeyCorrect',
                'label'             =>  array(
                    '1'                 =>  'yes',
                    '0'                 =>  'no'
                ),
                'hidden'            =>  true
            )
        );

    }

    //  ================================================================================
    //  FUNCTIONALITY
    //  ================================================================================

    /**
     * Returns HTML of license status.
     *
     * @return string
     */
    protected function getLicenseStatusHtml() {

        $html = '';

        if( App::i()->license->getKey() ){

            $keyStatus          = App::i()->license->getKeyStatus();
            $keyExpiryDatetime  = App::i()->license->getKeyExpiryDatetime();
            $keyIsActive        = App::i()->license->isActive();

            if( $keyStatus ){
                if( $keyIsActive ){
                    $html .= sprintf( '<div style="clear: both; color: #16a085;">%1$s</div>', $keyStatus );
                } else {
                    $html .= sprintf( '<div style="clear: both; color: #e74c3c;">%1$s</div>', $keyStatus );
                }
            }

            if( $keyExpiryDatetime && $keyIsActive ){
                $html .= sprintf( '<div style="clear: both; color: #16a085;">%1$s %2$s</div>', __( 'Valid until:', 'tmc_fbleads' ), $keyExpiryDatetime );
            }

        }

        return $html;

    }

    //  ================================================================================
    //  FILTERS
    //  ================================================================================

    /**
     * Called on form validation.
     * Performs key update if there is change in key value.
     *
     * @param array $newOptions
     * @param array $oldOptions
     *
     * @return array
     */
    public function _f_updateKeyButtonCallback( $newOptions, $oldOptions ) {

        if(
            $newOptions['license']['key'] != $oldOptions['license']['key']
            || isset( $_POST['updateKeySubmit'] ) && ! empty( $_POST['updateKeySubmit'] )
        ){

            $key = $newOptions['license']['key'];

            App::i()->license->setKey( $key );
            App::i()->license->performRemoteKeyUpdate();

            $this->pageFactory->setSettingNotice( __( 'License key has been updated.', 'tmc_fbleads' ), 'updated' );

            //  ShellPress options are older than this fresh submitted data.
            //  Actions are performed on ShellPress internal storage, so we return it here for update.

            return App::s()->options->get();

        }

        return $newOptions;

    }

}