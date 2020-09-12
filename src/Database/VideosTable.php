<?php

namespace VideoPublisherPro\Database;


/**
 *
 */
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
	 * table
	 *
	 * define the table schema
	 * @return
	 */
	protected function schema(){

		$table_name 			= $this->table_name();
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
	 * @return
	 */
	public static function create(){
		$evp_videos = new VideosTable();
		$evp_videos->new_table();
	}

}
