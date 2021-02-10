<?php
namespace VideoPublisherlite;

use VideoPublisherlite\Database\VideosTable;

class Activate
{

	/**
	 * Create options on activation
	 *
	 * Only create options if they dont already exist
	 * in case the user is just debuging stuff.
	 *
	 * @param string $option the option name.
	 */
	public static function make_option( $option = null ) : bool {

		if ( is_null( $option ) ) {
			return false;
		}

		if ( get_option( $option, false ) === false ) {
			$update = update_option( $option, array() );
			return $update;
		}
	}

	/**
	 * Create options on activation
	 * Install db tables for WP_Queue\Job
	 *
	 * @return false|void
	 * @link https://github.com/deliciousbrains/wp-queue
	 */
	public static function create_queue_tables() : void {
		wp_queue_install_tables();
	}

	/**
	 * Setup the options and create "evp_videos" Database Table
	 *
	 * @return void
	 */
	public static function setup() : void {

		self::make_option( 'evp_youtube_api' );

		self::make_option( 'evp_channels' );

		self::make_option( 'evp_restricted_categories' );

		VideosTable::create();

		self::create_queue_tables();

		do_action( 'evp_loaded' );
	}
}
