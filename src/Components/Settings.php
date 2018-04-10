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
	public function getWhitelistedIps() {

		$option = App::shell()->options->get( 'basics/whitelistedIps', '' );

		$array = ! empty( $option ) ? explode( PHP_EOL, $option ) : array();

		return $array;

	}

	/**
	 * @return string
	 */
	public function getStatus() {

		return (string) App::shell()->options->get( 'basics/status', '0' );

	}

	/**
	 * @param string $status
	 *
	 * @return void
	 */
	public function setStatus( $status ) {

		App::shell()->options->set( 'basics/status', $status );

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

		return (string) App::shell()->options->get( 'basics/_status', '0' );

	}

	/**
	 * @param string $status
	 *
	 * @return void
	 */
	public function setOldStatus( $status ) {

		App::shell()->options->set( 'basics/_status', $status );

	}

	/**
	 * @return string|null
	 */
	public function getMessage() {

		return App::shell()->options->get( 'basics/message' );

	}

	/**
	 * @return string|null
	 */
	public function getPageBg() {

		return App::shell()->options->get( 'basics/pageBg' );

	}

	/**
	 * @return string|null
	 */
	public function getBoxBg() {

		return App::shell()->options->get( 'basics/boxBg' );

	}

	/**
	 * @return string|null
	 */
	public function getTextColor() {

		return App::shell()->options->get( 'basics/textColor' );

	}

}