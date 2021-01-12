<?php

namespace VideoPublisherlite\Database;

use  VideoPublisherlite\Traits\Database;

class GetData
{

	use Database;

	/**
	 * Set the table name
	 *
	 * @return string
	 */
	protected function table_name() {
		return $this->db()->prefix . 'evp_videos';
	}

	/**
  	 * Get a list of results
  	 *
  	 * @param string $data the column.
  	 * @return false returns results as a keyed array
  	 */
	public function distinct_results( $data = 'channel_title' ) {

		$tablename = $this->table_name();

		$results = $this->db()->get_results( "SELECT DISTINCT $data FROM $tablename", 'ARRAY_A' );

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
	public function results( $data = 'channel_title' ) {

		$tablename = $this->table_name();

		$results = $this->db()->get_results( "SELECT $data FROM $tablename", 'ARRAY_A' );

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
	public function by_channel( $channel = null, $args = array() ) {

		if ( is_null( $channel ) ) {
			return false;
		}

		$default = array();
		$default['start']  = 0;
		$default['limit']  = 10;
		$default['output'] = 'ARRAY_N'; // ARRAY_A | ARRAY_N | OBJECT | OBJECT_K.
		$args = wp_parse_args( $args, $default );

		// Set the tabel name.
		$table = $this->table_name();

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
		$data = $this->db()->get_results(
			$this->db()->prepare( "SELECT * from $table WHERE channel =  %s LIMIT %d OFFSET %d", $channel, $args['limit'], $args['start'] ),
			$args['output']
		);
		return $data;
	}

}
