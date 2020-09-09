<?php

namespace VideoPublisherPro\YouTube;

use VideoPublisherPro\YouTube\YouTubeDataAPI;
use VideoPublisherPro\UserFeedback;

/**
 *
 */
class AddChannel
{

	/**
	 * Adds New Channel id.
	 * @param string $channelId the channel id.
	 */
	public static function new_channel( $channelId = null ){

		// make sure we have a valid key.
		if ( ! YouTubeDataAPI::has_key() ) {
			echo UserFeedback::message('<strong> Key is not Valid, Requires A Valid YouTube API Key !! </strong> ', 'error');
			return 0;
		}

		// make sure ID is set
		if ( is_null( $channelId ) ) {
			$channelId = false;
		}

		// validate the channel id, try to get 10 videos
		$get_vidoes = YouTubeDataAPI::channel_videos( $channelId , 10 );
		if ( $get_vidoes == false ) {
			echo UserFeedback::message('<strong> Channel ID is not Valid ! </strong> ', 'error');
			return 0;
		}

		/**
		 * check the $channelId
		 */
		if ( $channelId ) {

			// set up data
			$channelname 			= YouTubeDataAPI::channelby_id( $channelId )->snippet->title;
			$newchannel 			= array( $channelId => $channelname );
			$update_channels	= array_merge( $newchannel , (array) get_option( 'evp_channels' ) );

			// check if we already have the channel
			$channel_exists = array_key_exists( $channelId , (array) get_option( 'evp_channels' ) );

			// if channel_exists, let the user know
			if ( $channel_exists ) {
				echo UserFeedback::message('<strong>'.$channelname.'</strong> Channel was already Added !!!', 'error');
			} else {
				// add the new channel
				update_option('evp_channels', $update_channels );
				echo UserFeedback::message( '<strong>'.$channelname.'</strong> Channel Added !!!');
			}

		} else {
			echo UserFeedback::message('<strong> No Channel ID to Add !! </strong> ', 'error');
		}
	}

}
