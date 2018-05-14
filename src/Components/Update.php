<?php
namespace tmc\mm\src\Components;

/**
 * Date: 13.05.2018
 * Time: 21:49
 */

use shellpress\v1_2_1\src\Shared\Components\CustomUpdater;
use tmc\mm\src\App;

class Update extends CustomUpdater {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		$this->setUpdateSource( 'https://themastercut.co/packages/tmc-maintenance-mode/version.json' );

		if( ! App::i()->license->isActive() ){
            $this->disableUpdatePackage();
        }

	}

}