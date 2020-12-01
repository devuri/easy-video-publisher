<?php

namespace VideoPublisherlite\Database;

class GetData
{

	/**
	 * Setup WordPress database abstraction.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/
	 * @return object
	 */
	protected static function db() {
		global $wpdb;
		return $wpdb;
	}

	/**
	 * Set the table name
	 *
	 * @return string
	 */
	protected static function table_name() {
		return self::db()->prefix . 'evp_videos';
	}

	/**
  	 * Get a list of results
  	 *
  	 * @param string $data the column.
  	 * @return false returns results as a keyed array
  	 */
	public static function distinct_results( $data = 'channel_title' ) {

		$tablename = self::table_name();

		$results = self::db()->get_results( "SELECT DISTINCT $data FROM $tablename", 'ARRAY_A' );

		if ( ! empty( $results ) && is_array( $results ) ) {
			foreach ( $results as $entry ) {
				$key = $entry[ $data ];
				$result_list[ $key ] = $key;
			}
		} else {
			return false;
		}
		return $result_list;
	}

  	/**
  	 * Get a list of results
  	 *
  	 * @param string $data the column.
  	 * @return false returns results as a keyed array
  	 */
	public static function results( $data = 'channel_title' ) {

		$tablename = self::table_name();

		$results = self::db()->get_results( "SELECT $data FROM $tablename", 'ARRAY_A' );

		if ( ! empty( $results ) && is_array( $results ) ) {
			foreach ( $results as $entry ) {
				$key = $entry[ $data ];
				$result_list[ $key ] = $key;
			}
		} else {
			return false;
		}
		return $result_list;
	}

  	/**
  	 * Get data from the database table
  	 *
  	 * @param string $channel .
  	 * @param  array  $args .
  	 * @return mixed
  	 */
	public static function by_channel( $channel = null, $args = array() ) {

		if ( is_null( $channel ) ) {
			return false;
		}

		$default = array();
		$default['start']  = 0;
		$default['limit']  = 10;
		$default['output'] = 'ARRAY_N'; // ARRAY_A | ARRAY_N | OBJECT | OBJECT_K.
		$args = wp_parse_args( $args, $default );

		// Set the tabel name.
		$table = self::table_name();

		/**
		 * Get the tabe data.
		 *
		 * The query parameter for prepare accepts sprintf()-like placeholders:
		 * %s (string)
		 * %d (integer)
		 * %f (float)
		 *
		 * @link https://developer.wordpress.org/reference/classes/wpdb/#placeholders
		 */
		$data = self::db()->get_results(
			self::db()->prepare( "SELECT * from $table WHERE channel =  %s LIMIT %d OFFSET %d", $channel, $args['limit'], $args['start'] ),
			$args['output']
		);
		return $data;
	}

}
