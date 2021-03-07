<?php

namespace VideoPublisherlite\Database;

final class VideosTable extends WPDb
{

	/**
	 * DB Table version
	 *
	 * @var $version
	 */
	private $version;

	/**
	 * [__construct description]
	 */
	public function __construct() {

		if ( false === get_option( 'evp_version', false ) ) {
			update_option( 'evp_version', '3.5.6' );
		}

		$this->version = get_option( 'evp_version', false );

	}

	/**
	 * Set the table name
	 *
	 * @return string
	 */
	protected function table_name() {
		return $this->database()->prefix . 'evp_videos';
	}

  	/**
  	 * Define the table schema
  	 *
  	 * @return string
  	 */
	protected function schema() {

		$table_name = $this->table_name();
		$charset_collate = $this->database()->get_charset_collate();

		// schema.
		return "CREATE TABLE {$table_name} (
			ID bigint(20) unsigned NOT NULL auto_increment,
			post_id bigint(20) unsigned NOT NULL default '0',
			user_id bigint(20) unsigned NOT NULL DEFAULT '0',
			campaign_id bigint(20) unsigned NOT NULL DEFAULT '0',
			video_id varchar(30),
			video_views bigint(20) unsigned NOT NULL DEFAULT '0',
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
	 * Migration update.
	 */
	public function maybe_migrate() {

		if ( ! is_admin() ) {
			return false;
		}

		$this->do_migrate();
	}

	/**
	 * Checks table version
	 *
	 * @return bool
	 */
	public function update_available() {
		if ( version_compare( $this->version, '3.5.4', '<' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Add new views
	 *
	 * @return void
	 */
	public function do_migrate() {

		if ( $this->update_available() ) {
			$this->v354_upgrade();
			update_option( 'evp_version', '3.5.4' );
		}

	}

	/**
	 * Add new views
	 *
	 * @return bool $col true if column added successfully
	 */
	public function v354_upgrade() {

 		// be sure to include the file upgrade.php.
 		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

 		$table_name  = $this->table_name();
 		$column_name = 'video_views';
 		$create_ddl  = "ALTER TABLE {$table_name} ADD {$column_name} bigint(20) unsigned NOT NULL DEFAULT '0' AFTER video_id";

 		$col = maybe_add_column( $table_name, $column_name, $create_ddl );
 		return $col;
 	}

  	/**
  	 * Create the table
  	 *
  	 * @return void
  	 */
	public static function create() {
		/**
		 * Inititate and create the table
		 */
		( new VideosTable() )->new_table();
	}

	/**
	 * Insert Data
	 *
	 * Insert new video item into the table
	 *
	 * @param array $columns array of data to update.
	 * @return int the id of the inserted
	 * @link https://developer.wordpress.org/reference/classes/wpdb/insert/
	 */
	public function insert_data( $columns = array() ) {

		// Set the Table Name.
		$table = $this->table_name();

		// Build the data array.
		$defualts = array(
			'post_id'       => 0,
			'user_id'       => 0,
			'campaign_id'   => 0,
			'video_id'      => null,
			'channel'       => null,
			'channel_title' => null,
			'created'       => current_time( 'mysql' ),
		);
		$data = wp_parse_args( $columns, $defualts );

		/**
		 * Insert data into the database
		 * and return the id
		 */
		$this->database()->insert( $table, $data );
		return $this->database()->insert_id;
	}

	/**
	 * Check the database if the video id already exists.
	 *
	 * @param null $id .
	 * @return bool
	 */
	public static function video_exists( $id = null ) {

		$videos = ( new GetData() )->distinct_results( 'video_id' );

		if ( ! is_array( $videos ) ) {
			return false;
		}

		$vid_exists = array_key_exists( $id, $videos );
		return $vid_exists;
	}

}
