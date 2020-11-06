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
	 * @return false|void
	 */
	public static function make_option( $option = null ) {

		if ( is_null( $option ) ) {
			return 0;
		}

		if ( get_option( $option, false ) === false ) {
			update_option( $option, array() );
		}
	}

	/**
	 * Create options on activation
	 * Install db tables for WP_Queue\Job
	 *
	 * @return false|void
	 * @link https://github.com/deliciousbrains/wp-queue
	 */
	public static function queue_install() {
		wp_queue_install_tables();
	}

	/**
	 * Setup the options and create "evp_videos" Database Table
	 *
	 * @return void
	 */
	public static function setup() {

		self::make_option( 'evp_youtube_api' );

		self::make_option( 'evp_channels' );

		self::make_option( 'evp_restricted_categories' );

		VideosTable::create();

		self::queue_install();

		do_action( 'evp_loaded' );
	}
}
