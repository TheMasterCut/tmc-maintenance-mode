<?php
namespace tmc\mm\src\Components;

/**
 * Date: 08.04.2018
 * Time: 18:40
 */

use shellpress\v1_2_2\src\Shared\Components\IComponent;
use tmc\mm\src\App;

class Front extends IComponent {

    /**
     * Called on creation of component.
     *
     * @return void
     */
    protected function onSetUp() {
        // TODO: Implement onSetUp() method.
    }

	/**
	 * @return string
	 */
	public function getHtml() {

		ob_start();
		?>

		<!DOCTYPE html>
		<html lang="pl">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">

			<style>

				body {
					display: flex;
					flex-direction: column;
					justify-content: center;
					min-height: 100vh;
					letter-spacing: 0.1em;
					background-color: <?php echo App::i()->settings->getPageBg(); ?>;
				}

				.box {
					max-width: 500px;
					width: 100%;
					background: <?php echo App::i()->settings->getBoxBg(); ?>;
					color: <?php echo App::i()->settings->getTextColor(); ?>;
					padding: 40px;
					margin: auto;
				}

			</style>
		</head>
		<body>

		<div class="wrapper">
			<div class="box">

				<?php echo apply_filters( 'the_content', App::i()->settings->getMessage() ); ?>

			</div>
		</div>

		</body>
		</html>

		<?php

		return ob_get_clean();

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

		return App::s()->getPath( '/src/Templates/index.php' );

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

	/**
	 * Returns absolute path to file which will be included into display.
	 *
	 * @return string
	 */
	public function getTemplatePath() {

		return App::s()->getPath( '/src/Templates/template.html' );

	}

}