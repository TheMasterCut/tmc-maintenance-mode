<?php
namespace tmc\mm\src\Components;

/**
 * Date: 07.04.2018
 * Time: 19:50
 */

use tmc\mm\src\App;
use WP_Admin_Bar;

class Toolbar {

	public function __construct() {

		add_action( 'admin_bar_menu',   array( $this, '_a_createToolbarMenu' ), 1000 );

		add_action( 'init',             array( $this, '_a_toggleStatusFromUrlCallback' ) );

	}

	/**
	 * @param WP_Admin_Bar $wpAdminBar
	 */
	public function _a_createToolbarMenu( $wpAdminBar ) {

		if( App::i()->htaccess->getStatus() ){
			$title = __( 'Click to unlock website', 'tmc_mm' );
			$label = '<i class="dashicons dashicons-lock" style="font-family: dashicons, serif; color: #C0392B;"></i>';
		} else {
			$title = __( 'Click to lock website', 'tmc_mm' );
			$label = '<i class="dashicons dashicons-unlock" style="font-family: dashicons, serif;"></i>';
		}

		$url = wp_nonce_url( $_SERVER['REQUEST_URI'], 'tmc_mm_toggle', 'tmc_mm_toggle' );

		$wpAdminBar->add_menu(
			array(
				'id'            =>  'tmc_mm_menu',
				'title'         =>  $label,
				'meta'          =>  array(
					'title'         =>  $title
				),
				'href'          =>  $url
			)
		);

	}

	/**
	 * Toggles plugin status with nonce check.
	 * Called on init.
	 *
	 * @return void
	 */
	public function _a_toggleStatusFromUrlCallback() {

		if( array_key_exists( 'tmc_mm_toggle', $_GET ) ){

			$verified   = (bool) wp_verify_nonce( $_GET['tmc_mm_toggle'], 'tmc_mm_toggle' );

			if( $verified ){

				$newStatus = (int) ! App::i()->htaccess->getStatus();

				App::shell()->options->set( 'status', $newStatus . '' );
				App::shell()->options->flush();

			}

			wp_safe_redirect( remove_query_arg( 'tmc_mm_toggle' ) );
			exit;

		}

	}

}