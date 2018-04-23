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

		foreach( App::i()->settings->getWhitelistedIps() as $address ){

		    if( ! empty( $address ) ){
                $ruleLines[] = 'RewriteCond %{REMOTE_ADDR} !^' . str_replace( '.', '\.', $address );
            }

		}

		//  Ending

        $loginUrl = str_replace( home_url(), '', wp_login_url() );
		$loginUrl = rtrim( $loginUrl, '/' );
		$loginUrl = '/' . ltrim( $loginUrl, '/' );

		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !\.(jpe?g?|png|gif) [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-cron.php$ [NC] ';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-admin/ [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-content/ [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-includes/ [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !' . $loginUrl . ' [NC]';
		$ruleLines[] = 'RewriteRule .* ' . App::i()->front->getEndpointPath() . ' [L,NC]';
		$ruleLines[] = '</IfModule>';
		$ruleLines[] = '# END MAINTENANCE-PAGE';

		return PHP_EOL . implode( PHP_EOL, $ruleLines ) . PHP_EOL;

	}


	/**
	 * Called on mod_rewrite_rules.
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public function _f_addRules( $string ) {

		if( App::i()->settings->getStatus() ){

			App::s()->log->info( 'Adding own rules to htaccess.' );

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

		$status     = App::i()->settings->getStatus();
		$_status    = App::i()->settings->getOldStatus();

		//  Apparently flush_rewrite_rules() works only on admin.

		if( $status !== $_status && is_admin() && did_action( 'wp_loaded' ) ){

			App::i()->settings->setOldStatus( $status );
			App::s()->options->flush();

			App::s()->log->info( 'Flushing mod rewrite rules.' );

			flush_rewrite_rules();

			App::i()->front->updateTemplateFile();

		}

	}

}