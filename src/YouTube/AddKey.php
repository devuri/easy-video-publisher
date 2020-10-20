<?php

namespace VideoPublisherlite\YouTube;

use VideoPublisherlite\UserFeedback;

class AddKey
{

	/**
	 * Adds New API key if its valid.
	 *
	 * @param string $youtube_api_key api key.
	 * @return void
	 * @throws \Exception ..
	 */
	public static function new_apikey( $youtube_api_key = null ) {

		if ( is_null( $youtube_api_key ) ) {
			$is_key_valid = false;
		} else {
			// check if the key is valid.
			$is_key_valid = YouTubeData::api()->is_key_valid( $youtube_api_key );
		}

		/**
		 * Check the key
		 *
		 * @var
		 */
		if ( $is_key_valid ) {

			// set the API key with a timestamp.
			$new_key     = array( $youtube_api_key => time() );
			$update_keys = array_merge( $new_key, (array) get_option( 'evp_youtube_api' ) );

			// check if we already have the key in recent updates.
			$api_keys   = (array) get_option( 'evp_youtube_api' );
			$key_exists = array_key_exists( $youtube_api_key, $api_keys );

			// check if we already have that key.
			if ( $key_exists ) {
				echo UserFeedback::message( '<strong> <span style="color:#dc3232">' . $youtube_api_key . '</span></strong> already Exists !!', 'error' );  // @codingStandardsIgnoreLine
			} else {
				// add the new api key.
				update_option( 'evp_youtube_api', $update_keys );
				echo UserFeedback::message( 'New API Key <strong> <span style="color:#037b0e">' . $youtube_api_key . '</span></strong> has been successfully added !!' );  // @codingStandardsIgnoreLine
			}
		} else {
			// the key is not valid.
			echo UserFeedback::message( 'The Key: <span style="color:#dc3232">' . $youtube_api_key . '</span> <strong> is NOT A Valid Key !! </strong> ', 'error' );  // @codingStandardsIgnoreLine
		}
	}
}
