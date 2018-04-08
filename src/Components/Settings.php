<?php
namespace tmc\mm\src\Components;

/**
 * Date: 08.04.2018
 * Time: 02:31
 */

use tmc\mm\src\App;

class Settings {

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
	 * @param string $status
	 *
	 * @return void
	 */
	public function setStatus( $status ) {

		App::shell()->options->set( 'status', $status );

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
	 * @param string $status
	 *
	 * @return void
	 */
	public function setOldStatus( $status ) {

		App::shell()->options->set( '_status', $status );

	}

}