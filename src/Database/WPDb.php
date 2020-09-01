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
	 * insert_data()
	 *
	 * insert new video item into the table
	 *
	 * @param  array  $columns  array of data to update
	 * @return  int   the id of the inserted
	 * @link https://developer.wordpress.org/reference/classes/wpdb/insert/
	 */
	public static function insert_data( $tablename = null , $columns = array() ){

		global $wpdb;
		$table = $wpdb->prefix.$tablename;

		if ( is_null($tablename) ) {
			return false;
		}

		/**
		 * build the data array
		 * @var array
		 */
		$defualts = array(
			'post_id' 		=> 0,
			'user_id' 		=> 0,
			'campaign_id' => 0,
			'video_id' 		=> null,
			'channel' 		=> null,
			'created' 		=> current_time( 'mysql' ),
		);
		$data = wp_parse_args( $columns , $defualts );

		/**
		 * insert data into the database
		 * and return the id
		 */
		$wpdb->insert( $table , $data );
		return $wpdb->insert_id;
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
	abstract protected function table();

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
		if ( is_null( $this->table() ) ) {
			return false;
		}

		/**
		 * Create the "evp_videos" Table if it does not exist.
		 *
		 * Creates a table in the database, if it doesn’t already exist.
		 * @link https://developer.wordpress.org/reference/functions/maybe_create_table/
		 */
		$table = maybe_create_table( $this->table_name() , $this->table() );
		return $table;
	}

}
