<?php

namespace VideoPublisherlite\YouTube;

use VideoPublisherlite\UserFeedback;

class AddChannel
{

	/**
	 * Adds New Channel id.
	 *
	 * @param string $channel_id the channel id.
	 */
	public static function new_channel( $channel_id = null ) {

		// make sure we have a valid key.
		if ( ! YouTubeData::api()->has_key() ) {
			echo UserFeedback::message( '<strong> Key is not Valid, Requires A Valid YouTube API Key !! </strong> ', 'error' );  // @codingStandardsIgnoreLine
			return 0;
		}

		// make sure ID is set.
		if ( is_null( $channel_id ) ) {
			$channel_id = false;
		}

		// validate the channel id, try to get 10 videos.
		$get_vidoes = YouTubeData::api()->channel_videos( $channel_id, 10 );
		if ( false === $get_vidoes ) {
			echo UserFeedback::message( '<strong> Channel ID is not Valid ! </strong> ', 'error' );  // @codingStandardsIgnoreLine
			return 0;
		}

		/**
		 * Check the $channel_id.
		 */
		if ( $channel_id ) {

			// set up data.
			$channelname      = YouTubeData::api()->channelby_id( $channel_id )->snippet->title;
			$newchannel       = array( $channel_id => $channelname );
			$update_channels  = array_merge( $newchannel, (array) get_option( 'evp_channels' ) );

			// check if we already have the channel.
			$channel_exists = array_key_exists( $channel_id, (array) get_option( 'evp_channels' ) );

			// if channel_exists, let the user know.
			if ( $channel_exists ) {
				echo UserFeedback::message( '<strong>' . $channelname . '</strong> Channel was already Added !!!', 'error' );  // @codingStandardsIgnoreLine
			} else {
				// add the new channel.
				update_option( 'evp_channels', $update_channels );
				echo UserFeedback::message( '<strong>' . $channelname . '</strong> Channel Added !!!');  // @codingStandardsIgnoreLine
			}
		} else {
			echo UserFeedback::message( '<strong> No Channel ID to Add !! </strong> ', 'error' );  // @codingStandardsIgnoreLine
		}
	}
}
