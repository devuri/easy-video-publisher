<?php

namespace VideoPublisherlite\Database;

use  VideoPublisherlite\Traits\Database;

abstract class WPDb
{

	use Database;

	/**
	 * Setup WordPress database abstraction.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/
	 * @return object
	 */
	protected function database() {
		return $this->db();
	}

	/**
	 * Set the table name
	 */
	abstract protected function table_name();

  	/**
  	 * The table define the table schema
  	 */
	abstract protected function schema();

	/**
	 * Create table
	 *
	 * @return bool True on success, if the table already exists, False on failure.
	 */
	public function new_table() {

		// be sure to include the file upgrade.php.
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		/**
		 * There is no table to create
		 */
		if ( is_null( $this->table_name() ) ) {
			return false;
		}

		/**
		 * There is no table to create
		 */
		if ( is_null( $this->schema() ) ) {
			return false;
		}

		/**
		 * Create the "evp_videos" Table if it does not exist.
		 *
		 * Creates a table in the database, if it doesn’t already exist.
		 *
		 * @link https://developer.wordpress.org/reference/functions/maybe_create_table/
		 */
		$table = maybe_create_table( $this->table_name(), $this->schema() );
		return $table;
	}

}
