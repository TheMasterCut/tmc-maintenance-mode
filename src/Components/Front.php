<?php
namespace tmc\mm\src\Components;

/**
 * Date: 08.04.2018
 * Time: 18:40
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;
use tmc\mm\src\App;

class Front extends IComponent {

    /**
     * Called on creation of component.
     *
     * @return void
     */
    protected function onSetUp() {}

	/**
	 * @return string
	 */
	public function getHtml() {

		//  Prepare some data

		$data = array(
			'page'      =>  array(
				'bgColor'   =>  App::i()->settings->getPageBg(),
			),
			'box'       =>  array(
				'bgColor'   =>  App::i()->settings->getBoxBg(),
				'textColor' =>  App::i()->settings->getTextColor(),
				'message'   =>  apply_filters( 'the_content', App::i()->settings->getMessage() )
			)
		);

		//  Render data into template

		return $this::s()->mustache->render( 'src/Templates/template.mustache', $data );

	}

	/**
	 * @return void
	 */
	public function updateTemplateFile() {

		$filePath = $this->getEndpointPath();

		file_put_contents( $filePath, $this->getHtml() );

	}

	/**
	 * Returns absolute path to file which will be displayed.
	 *
	 * @return string
	 */
	public function getEndpointPath() {

		return trailingslashit( ABSPATH ) . '401.php';

	}

	/**
	 * Returns relative path to file which will be displayed.
     * Returned string does not have slash in the beginning.
	 *
	 * @return string
	 */
	public function getRelativeEndpointPath() {

		$absPath = $this->getEndpointPath();
		$relPath = str_replace( ABSPATH, '', $absPath );

		return ltrim( $relPath, '/' );

	}

}