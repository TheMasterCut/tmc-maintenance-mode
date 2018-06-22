<?php
namespace tmc\mm\src\Components;

/**
 * @author jakubkuranda@gmail.com
 * Date: 21.06.2018
 * Time: 15:21
 */

use shellpress\v1_2_5\src\Shared\Components\IComponent;
use wpdb;

class DbExporter extends IComponent {

	/**
	 * Maximum length of single insert statement
	 */
	const INSERT_THRESHOLD = 838860;

	/**
	 * Called on creation of component.
	 *
	 * @return void
	 */
	protected function onSetUp() {



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

	/**
	 * Returns SQL for table creation.
	 *
	 * @param string $tableName
	 *
	 * @return string
	 */
	public function getCreateTableSql( $tableName ) {

		global $wpdb;   /** @var wpdb $wpdb */

		$sql = $wpdb->get_var( 'SHOW CREATE TABLE ' . $tableName, 1 );    // We want second column from MySQL response.

		return empty( $sql ) ? '' : $sql . ';';

	}

	/**
	 * Returns SQL for data creation.
	 *
	 * @param string $tableName
	 *
	 * @return array
	 */
	public function getTableDataDumpSql( $tableName ) {

		global $wpdb;   /** @var wpdb $wpdb */

		$sqlStatements = array();

		$results = $wpdb->get_results( "SELECT * FROM $tableName LIMIT 5", 'ARRAY_A' );

		//  ----------------------------------------
		//  Build INSERT statements
		//  ----------------------------------------

		$sqlDataRows = array();

		foreach( $results as $row ){

			$sqlDataRows[] = sprintf( '(%1$s)', implode( ',', $row ) );

		}

		$sqlStatements[] = "INSERT INTO $tableName VALUES " . implode( ',' . PHP_EOL, $sqlDataRows ) . ";";

		return $sqlStatements;

	}

}