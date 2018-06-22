<?php
namespace tmc\mm\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 21.06.2018
 * Time: 15:21
 */

use shellpress\v1_2_4\src\Shared\Components\IComponent;
use wpdb;

class DbExporter extends IComponent {

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {
		// TODO: Implement onSetUp() method.
	}

	/**
	 * Returns all tables names inside connected database.
	 *
	 * @return string[]
	 */
	public function getAllTablesNames() {

		global $wpdb;   /** @var wpdb $wpdb */

		$results = (array) $wpdb->get_results( 'SHOW TABLES LIKE \'%\'' );

		$tablesNames = array();

		foreach( $results as $result ){
			foreach( $result as $name ){
				$tablesNames[] = (string) $name;
			}
		}

		return $tablesNames;

	}

}