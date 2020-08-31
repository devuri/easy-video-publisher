<?php

namespace VideoPublisherPro\Database;


/**
 *
 */
class WPDb
{

	/**
	 * setup WordPress database abstraction.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/
	 * @return object
	 */
	public static function database(){
		global $wpdb;
		return $wpdb;
	}

}
