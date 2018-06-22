<?php
namespace tmc\mm\src\Components;

/**
 * Date: 13.05.2018
 * Time: 21:49
 */

use shellpress\v1_2_4\src\Shared\Components\IComponent;
use tmc\mm\src\App;

class Update extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {

		$this::s()->update->setFeedSource( 'https://raw.githubusercontent.com/TheMasterCut/tmc-maintenance-mode/master/updates.json' );

		if( ! App::i()->license->isActive() ){
			$this::s()->update->disableUpdateOfPackage();
		}

	}

}