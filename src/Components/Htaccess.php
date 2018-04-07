<?php
namespace tmc\mm\src\Components;

/**
 * Date: 05.04.2018
 * Time: 18:10
 */

use tmc\mm\src\App;

class Htaccess {

	public function __construct() {

		//  ----------------------------------------
		//  Actions
		//  ----------------------------------------

		//  Add rewrite rules only, if maintenance mode is enabled

		add_filter( 'mod_rewrite_rules',        array( $this, '_f_addRules' ), 100 );

		add_action( 'shutdown',                 array( $this, '_a_rewriteWatcher' ) );

	}

	/**
	 * Prepares whole string of rules for further .htaccess insertion.
	 *
	 * @return string
	 */
	protected function getAdditionalAccessRules() {

		//  Beginning of rules

		$ruleLines = array();
		$ruleLines[] = '# BEGIN MAINTENANCE-PAGE';
		$ruleLines[] = '<IfModule mod_rewrite.c>';
		$ruleLines[] = 'RewriteEngine on';

		//  IP Restrictions

		foreach( $this->getWhitelistedAddresses() as $address ){

			$ruleLines[] = 'RewriteCond %{REMOTE_ADDR} !^' . str_replace( '.', '\.', $address );

		}

		//  Ending

		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !\.(jpe?g?|png|gif) [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-cron.php$ [NC] ';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-admin/ [NC]';
		$ruleLines[] = sprintf( 'RewriteRule .* %1$s [L,NC]', ABSPATH . 'wip.php' );
		$ruleLines[] = '</IfModule>';
		$ruleLines[] = '# END MAINTENANCE-PAGE';

		return PHP_EOL . implode( PHP_EOL, $ruleLines ) . PHP_EOL;

	}

	/**
	 * Returns addresses from settings.
	 *
	 * @return array
	 */
	public function getWhitelistedAddresses() {

		return (array) App::shell()->options->get( 'addresses', array() );

	}

	/**
	 * Called on mod_rewrite_rules.
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public function _f_addRules( $string ) {

		if( App::shell()->options->get( 'status' ) ){

			return $this->getAdditionalAccessRules() . $string;

		} else {

			return $string;

		}

	}

	/**
	 * Checks if mechanism should flush htaccess.
	 * Called on init.
	 */
	public function _a_rewriteWatcher() {

		$status     = App::shell()->options->get( 'status' );
		$_status    = App::shell()->options->get( '_status' );

		if( $status != $_status ){

			App::shell()->options->set( '_status', $status );
			App::shell()->options->flush();

			flush_rewrite_rules();

		}

	}

}