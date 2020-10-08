<?php
namespace VideoPublisherlite;

use VideoPublisherlite\Database\VideosTable;

class Activate
{
	/**
	 * the external class library to get images uses curl,
	 * so lets prepare incase its not avalable.
	 *
	 * This uses curl VideoPublisherlite\Post\ImageUploadFromUrl
	 * TODO fallback to our own alternative if no curl.
	 *
	 * @return boolean
	 */
	public static function is_curl_enabled(){
		if( function_exists('curl_init') === false ){
			return false;
		}
		return true;
	}

	/**
	 * create options on activation
	 *
	 * only create options if they dont already exist
	 * in case the user is just debuging stuff.
	 *
	 * @param string  the option name
	 * @return false|void
	 */
	public static function option( $option = null ){

		if ( is_null($option) ) {
			return 0;
		}

		if ( get_option( $option , false ) === false ) {
			update_option( $option , array() );
		}
	}

	/**
	 * setup the options and create "evp_videos" Database Table
	 *
	 * @return void
	 */
	public static function setup(){

		self::option( 'evp_youtube_api' );

		self::option( 'evp_channels' );

		self::option( 'evp_restricted_categories' );

		VideosTable::create();

		do_action( 'evp_loaded' );
	}

}
