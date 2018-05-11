<?php
namespace tmc\mm\src\Components;

/**
 * Date: 05.04.2018
 * Time: 18:10
 */

use shellpress\v1_2_1\src\Shared\Components\IComponent;
use tmc\mm\src\App;

class Apache extends IComponent {

    /**
     * Called on creation of component.
     *
     * @return void
     */
    protected function onSetUp() {

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
	protected function getAdditionalWhiteListRules() {

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
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-admin.* [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-content.* [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !/wp-includes.* [NC]';
		$ruleLines[] = 'RewriteCond %{REQUEST_URI} !' . $loginUrl . '.* [NC]';
		$ruleLines[] = 'RewriteRule .* ' . App::i()->front->getEndpointPath() . ' [L,NC]';

		$ruleLines[] = '</IfModule>';
		$ruleLines[] = '# END MAINTENANCE-PAGE';

		return PHP_EOL . implode( PHP_EOL, $ruleLines ) . PHP_EOL;

	}

    /**
     * Prepares whole string of rules for further .htaccess insertion.
     *
     * @return string
     */
    protected function getAdditionalCredentialsRules() {

        //  Beginning of rules

        $ruleLines = array();
        $ruleLines[] = '# BEGIN MAINTENANCE-PAGE';

        $ruleLines[] = 'AuthName "Lock-down enabled"';
        $ruleLines[] = 'AuthType Basic';
        $ruleLines[] = 'AuthUserFile .htpasswd';
        $ruleLines[] = 'Require valid-user';
        $ruleLines[] = 'ErrorDocument 401 ' . App::i()->front->getEndpointPath();

        $ruleLines[] = '# END MAINTENANCE-PAGE';

        return PHP_EOL . implode( PHP_EOL, $ruleLines ) . PHP_EOL;

    }

    /**
     * Creates .htpasswd file and fills with credentials.
     *
     * @return bool
     */
    protected function generatePasswordsFile() {

        $lines = array();

        //  ----------------------------------------
        //  Prepare lines
        //  ----------------------------------------

        foreach( App::i()->settings->getCredentials() as $credentials ){

            $login  = isset( $credentials['login'] ) ? $credentials['login'] : null;
            $pass   = isset( $credentials['password'] ) ? $credentials['password'] : null;

            if( $login && $pass ){

                $lines[] = $credentials['login'] . ':' . crypt( $credentials['password'], base64_encode( $credentials['password'] ) );

            }

        }

        //  ----------------------------------------
        //  Process
        //  ----------------------------------------

        if( empty( $lines ) ){

            //  Lines are empty! Don't create a file.

            App::s()->log->info( 'Did not create .htpasswd file, because lines were empty.' );
            return false;

        } else {

            //  Ok, we got some lines. Create file.

            $result = file_put_contents( ABSPATH . '/.htpasswd', implode( PHP_EOL, $lines ) );

            if( $result ){
                App::s()->log->info( 'Successfully added credentials to .htpasswd.' );
                return true;
            } else {
                App::s()->log->info( 'Could not add cre .htpasswd.' );
                return false;
            }

        }

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

		    switch( App::i()->settings->getTypeOfLockDown() ){

                case 'whitelist':

                    return $this->getAdditionalWhiteListRules() . $string;

                    break;

                case 'password':

                    if( $this->generatePasswordsFile() ){
                        return $this->getAdditionalCredentialsRules() . $string;
                    } else {
                        return $string;
                    }

                    break;

                default:

                    return $string;

                    break;

            }

		}

		//  Nothing changed.

        return $string;

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