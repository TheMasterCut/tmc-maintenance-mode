<?php
namespace tmc\mm\src\Components;

/**
 * Date: 08.04.2018
 * Time: 02:31
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;
use tmc\mm\src\App;

class Settings extends IComponent {

    /**
     * Called on creation of component.
     *
     * @return void
     */
    protected function onSetUp() {

    	//  ----------------------------------------
    	//  Default settings
    	//  ----------------------------------------

	    App::s()->options->setDefaultOptions( array(
		    'basics'        =>  array(
			    '_status'           =>  '0',
			    'status'            =>  '0',
			    'lockDownType'      =>  'whitelist',
			    'credentials'       =>  array(),
			    'whitelistedIps'    =>  $_SERVER['REMOTE_ADDR'] . PHP_EOL . 'localhost',
			    'message'           =>  '<h2>Site under maintenance.</h2>' . PHP_EOL . '<p>We will be back soon.</p>',
			    'pageBg'            =>  '#ECF0F1',
			    'boxBg'             =>  '#2C3E50',
			    'textColor'         =>  '#ECF0F1',
		    )
	    ) );

	    App::s()->event->addOnActivate( array( $this, '_a_loadDefaultSettings' ) );

    }

	/**
	 * Called on activate.
	 *
	 * @internal
	 *
	 * @return void
	 */
	public function _a_loadDefaultSettings() {

		App::s()->options->fillDifferencies();
		App::s()->options->flush();

	}

	/**
	 * Returns addresses from settings.
	 *
	 * @return array
	 */
	public function getWhitelistedIps() {

		$option = App::s()->options->get( 'basics/whitelistedIps', '' );

		$array = ! empty( $option ) ? explode( PHP_EOL, $option ) : array();

		return $array;

	}

	/**
	 * @return string
	 */
	public function getStatus() {

		return (string) App::s()->options->get( 'basics/status', '0' );

	}

	/**
	 * @param string $status
	 *
	 * @return void
	 */
	public function setStatus( $status ) {

		App::s()->options->set( 'basics/status', $status );

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

		return (string) App::s()->options->get( 'basics/_status', '0' );

	}

	/**
	 * @param string $status
	 *
	 * @return void
	 */
	public function setOldStatus( $status ) {

		App::s()->options->set( 'basics/_status', $status );

	}

	/**
	 * @return string|null
	 */
	public function getMessage() {

		return App::s()->options->get( 'basics/message' );

	}

	/**
	 * @return string|null
	 */
	public function getPageBg() {

		return App::s()->options->get( 'basics/pageBg' );

	}

	/**
	 * @return string|null
	 */
	public function getBoxBg() {

		return App::s()->options->get( 'basics/boxBg' );

	}

	/**
	 * @return string|null
	 */
	public function getTextColor() {

		return App::s()->options->get( 'basics/textColor' );

	}

    /**
     * @return string|null
     */
	public function getTypeOfLockDown() {

	    return App::s()->options->get( 'basics/lockDownType', 'whitelist' );

    }

    /**
     * Returns multiple arrays with keys 'login' and 'password'.
     *
     * @return array
     */
    public function getCredentials() {

	    return App::s()->options->get( 'basics/credentials', array() );

    }

}