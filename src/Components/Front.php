<?php
namespace tmc\mm\src\Components;

/**
 * Date: 08.04.2018
 * Time: 18:40
 */

use tmc\mm\src\App;

class Front {

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

				/* WordPress Core */

				.alignnone {
					margin: 5px 20px 20px 0;
				}

				.aligncenter,
				div.aligncenter {
					display: block;
					margin: 5px auto 5px auto;
				}

				.alignright {
					float:right;
					margin: 5px 0 20px 20px;
				}

				.alignleft {
					float: left;
					margin: 5px 20px 20px 0;
				}

				a img.alignright {
					float: right;
					margin: 5px 0 20px 20px;
				}

				a img.alignnone {
					margin: 5px 20px 20px 0;
				}

				a img.alignleft {
					float: left;
					margin: 5px 20px 20px 0;
				}

				a img.aligncenter {
					display: block;
					margin-left: auto;
					margin-right: auto;
				}

				.wp-caption {
					background: #fff;
					border: 1px solid #f0f0f0;
					max-width: 96%; /* Image does not overflow the content area */
					padding: 5px 3px 10px;
					text-align: center;
				}

				.wp-caption.alignnone {
					margin: 5px 20px 20px 0;
				}

				.wp-caption.alignleft {
					margin: 5px 20px 20px 0;
				}

				.wp-caption.alignright {
					margin: 5px 0 20px 20px;
				}

				.wp-caption img {
					border: 0 none;
					height: auto;
					margin: 0;
					max-width: 98.5%;
					padding: 0;
					width: auto;
				}

				.wp-caption p.wp-caption-text {
					font-size: 11px;
					line-height: 17px;
					margin: 0;
					padding: 0 4px 5px;
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
	 * Returns absolute path to file which will be included into display.
	 *
	 * @return string
	 */
	public function getTemplatePath() {

		return App::s()->getPath( '/src/Templates/template.html' );

	}

}