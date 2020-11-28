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
			echo UserFeedback::message( 'Key is not Valid, Requires A Valid YouTube API Key ! ', 'error' );  // @codingStandardsIgnoreLine
			return false;
		}

		// channel ID.
		$channel_id = trim( $channel_id );

		// validate that the channel works.
		if ( ! YouTubeData::api()->channel_videos( $channel_id ) ) {
			echo UserFeedback::message( 'Channel ID is Not Valid! ', 'error' );  // @codingStandardsIgnoreLine
			return false;
		}

		// make sure we have array option.
		if ( false === get_option( 'evp_channels', false ) ) {
			update_option( 'evp_channels', array() );
		}

		// make sure ID is set.
		if ( is_null( $channel_id ) ) {
			$channel_id = false;
			return false;
		}

		// validate the channel id, try to get 10 videos.
		$get_vidoes = YouTubeData::api()->channel_videos( $channel_id, 10 );
		if ( false === $get_vidoes ) {
			echo UserFeedback::message( ' Channel ID is not Valid ! ', 'error' );  // @codingStandardsIgnoreLine
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
				echo UserFeedback::message( $channelname . ' Channel was already Added ! ', 'error' );  // @codingStandardsIgnoreLine
			} else {
				// add the new channel.
				update_option( 'evp_channels', $update_channels );
				echo UserFeedback::message( $channelname . ' Channel Added ! ');  // @codingStandardsIgnoreLine
			}
		} else {
			echo UserFeedback::message( 'No Channel ID to Add ! ', 'error' );  // @codingStandardsIgnoreLine
		}
	}
}
