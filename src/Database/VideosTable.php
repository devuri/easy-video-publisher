<?php

namespace VideoPublisherPro\Database;


/**
 *
 */
class VideosTable extends WPDb
{

	/**
	 * set the table name
	 * @return string
	 */
	private static function table_name(){
		return self::database()->prefix . "evp_videos";
	}

	/**
	 * table
	 *
	 * define the table schema
	 * @return
	 */
	private static function table(){

		$table_name 			= self::table_name();
		$charset_collate 	= self::database()->get_charset_collate();

		// schema
		return "CREATE TABLE {$table_name} (
		  id bigint(20) unsigned NOT NULL auto_increment,
		  post_id bigint(20) unsigned NOT NULL default '0',
			user_id bigint(20) unsigned NOT NULL DEFAULT '0',
		  video_id varchar(255),
		  channel varchar(255),
		  created datetime NOT NULL default '0000-00-00 00:00:00',
		  PRIMARY KEY (id),
			KEY post_id (post_id),
			KEY user_id (user_id),
			KEY video_id (video_id),
			KEY created (created)
		) $charset_collate";
	}

	/**
	 * create table
	 *
	 * @return bool True on success, if the table already exists, False on failure.
	 */
	public static function create_table() {

		// be sure to include the file upgrade.php
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		/**
		 * Create the "evp_videos" Table if it does not exist.
		 *
		 * Creates a table in the database, if it doesn’t already exist.
		 * @link https://developer.wordpress.org/reference/functions/maybe_create_table/
		 */
		$table = maybe_create_table( self::table_name() , self::table() );
		return $table;
	}

}
