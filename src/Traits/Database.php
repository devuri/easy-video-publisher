<?php

namespace VideoPublisherlite\Traits;

trait Database {

	/**
	 * Setup WordPress database.
	 *
	 * @link https://developer.wordpress.org/reference/classes/wpdb/
	 * @return object
	 */
	public function db() {
		global $wpdb;
		return $wpdb;
	}

}
