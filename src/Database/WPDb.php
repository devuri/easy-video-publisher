<?php

namespace VideoPublisherPro\Database;


/**
 *
 */
abstract class WPDb
{

	/**
	 * setup WordPress database abstraction.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/
	 * @return object
	 */
	protected function database(){
		global $wpdb;
		return $wpdb;
	}

	/**
	 * set the table name
	 * @return string
	 */
	abstract protected function table_name();

  /**
   * table
   *
   * define the table schema
   * @return
   */
	abstract protected function schema();

	/**
	 * create table
	 *
	 * @return bool True on success, if the table already exists, False on failure.
	 */
	public function new_table() {

		// be sure to include the file upgrade.php
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		/**
		 * there is no table to create
		 */
		if ( is_null( $this->table_name() ) ) {
			return false;
		}

		/**
		 * there is no table to create
		 */
		if ( is_null( $this->schema() ) ) {
			return false;
		}

		/**
		 * Create the "evp_videos" Table if it does not exist.
		 *
		 * Creates a table in the database, if it doesn’t already exist.
		 * @link https://developer.wordpress.org/reference/functions/maybe_create_table/
		 */
		$table = maybe_create_table( $this->table_name() , $this->schema() );
		return $table;
	}

}
