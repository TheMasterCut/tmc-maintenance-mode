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
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-content/ [NC]';
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
	 * @return string
	 */
	public function getStatus() {

		return (string) App::shell()->options->get( 'status', '0' );

	}

	/**
	 * @return string
	 */
	public function getInvertedStatus() {

		return (string) (int) ! (bool) $this->getStatus();

	}

	/**
	 * @return string
	 */
	public function getOldStatus() {

		return (string) App::shell()->options->get( '_status', '0' );

	}

	/**
	 * Called on mod_rewrite_rules.
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public function _f_addRules( $string ) {

		if( $this->getStatus() ){

			App::shell()->log->info( 'Adding own rules to htaccess.' );

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

		$status     = $this->getStatus();
		$_status    = $this->getOldStatus();

		//  Appearantly flush_rewrite_rules() works only on admin.

		if( $status !== $_status && is_admin() && did_action( 'wp_loaded' ) ){

			App::shell()->options->set( '_status', $status );
			App::shell()->options->flush();

			App::shell()->log->info( 'Flushing mod rewrite rules.' );

			flush_rewrite_rules();

		}

	}

}