<?php

namespace VideoPublisherlite\Database;

final class VideosTable extends WPDb
{

	/**
	 * set the table name
	 * @return string
	 */
	protected function table_name(){
		return $this->database()->prefix . "evp_videos";
	}

  	/**
  	 * define the table schema
  	 *
  	 * @return string
  	 */
	protected function schema(){

		$table_name 		= $this->table_name();
		$charset_collate 	= $this->database()->get_charset_collate();

		// schema
		return "CREATE TABLE {$table_name} (
			ID bigint(20) unsigned NOT NULL auto_increment,
			post_id bigint(20) unsigned NOT NULL default '0',
			user_id bigint(20) unsigned NOT NULL DEFAULT '0',
			campaign_id bigint(20) unsigned NOT NULL DEFAULT '0',
			video_id varchar(30),
			channel varchar(50),
			channel_title varchar(200),
			created datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY (ID),
			KEY post_id (post_id),
			KEY user_id (user_id),
			KEY campaign_id (campaign_id),
			KEY video_id (video_id),
			KEY channel (channel),
			KEY created (created)
		) $charset_collate";
	}

  	/**
  	 * create the table
  	 *
  	 * @return void
  	 */
	public static function create(){
		/**
		 * inititate and create the table
		 */
		(new VideosTable)->new_table();
	}

	/**
	 * insert_data()
	 *
	 * insert new video item into the table
	 *
	 * @param null $tablename
	 * @param array $columns array of data to update
	 * @return  int   the id of the inserted
	 * @link https://developer.wordpress.org/reference/classes/wpdb/insert/
	 */
	public function insert_data( $columns = array() ){

		// Table Name
		$table = $this->table_name();

		/**
		 * build the data array
		 * @var array
		 */
		$defualts = array(
			'post_id' 		=> 0,
			'user_id' 		=> 0,
			'campaign_id' 	=> 0,
			'video_id' 		=> null,
			'channel' 		=> null,
			'channel_title' => null,
			'created' 		=> current_time( 'mysql' ),
		);
		$data = wp_parse_args( $columns , $defualts );

		/**
		 * insert data into the database
		 * and return the id
		 */
		$this->database()->insert( $table , $data );
		return $this->database()->insert_id;
	}

	/**
	 * check the database if the video id already exists.
	 *
	 * @param null $id
	 * @return bool
	 */
	public static function video_exists( $id = null ){
		$videos = GetData::get_result('video_id');

		if ( ! is_array( $videos ) ) {
			return false;
		}

		$vid_exists = array_key_exists( $id , $videos );
		return $vid_exists;
	}

}
