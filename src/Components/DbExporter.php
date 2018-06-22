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
	 * @return string
	 */
	public function getTableDataDumpSql( $tableName ) {

		global $wpdb;   /** @var wpdb $wpdb */

//		$data = $wpdb->( "SELECT * FROM `$tableName`");
//
//		while( $row = $this->db->fetch_row($data) ){
//			$row_values = array();
//			foreach ($row as $value) {
//				$row_values[] = $this->db->escape($value);
//			}
//			$insert->add_row( $row_values );
//
//			if ($insert->get_length() > self::INSERT_THRESHOLD) {
//				// The insert got too big: write the SQL and create
//				// new insert statement
//				$this->dump_file->write($insert->get_sql() . $eol);
//				$insert->reset();
//			}
//		}
//
//		$sql = $insert->get_sql();
//		if ($sql) {
//			$this->dump_file->write($insert->get_sql() . $eol);
//		}
//		$this->dump_file->write($eol . $eol);

	}

}