<?php
namespace tmc\mm\src\Components;

/**
 * Date: 05.04.2018
 * Time: 18:10
 */

class Htaccess {

	/**
	 * @return string|null
	 */
	public function getContent() {

		$homePath = trailingslashit( ABSPATH );
		$filePath = $homePath . '.htaccess';

		if( file_exists( $filePath ) ){

			$fileContents = file_get_contents( $filePath );
			return $fileContents ? $fileContents : null;

		} else {

			return null;

		}

	}

}