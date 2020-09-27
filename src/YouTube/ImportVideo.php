<?php

namespace VideoPublisherlite\YouTube;

use VideoPublisherlite\Post\ChannelImport;
use VideoPublisherlite\UserFeedback;

/**
 *
 */
class ImportVideo
{

	/**
	 * add_video import and post channel videos
	 *
	 * @param array $form_data brings in the $_POST data from the form submission.
	 */
	public static function add_video( array $form_data = array() ){

		// make sure we have a valid key.
		if ( ! YouTubeData::api()->has_key() ) {
			echo UserFeedback::message('<strong> Key is not Valid, Requires A Valid YouTube API Key !! </strong> ', 'error');
			return 0;
		}

		// if the channel is not set or empty return
		if ( empty( (array) get_option('evp_channels') ) ) {
            echo UserFeedback::message('Please Add a YouTube Channel!', 'error');
            return 0;
        }

		/**
		 * get the channel to post from
		 */
		$channelId 			= sanitize_text_field( trim( $form_data['youtube_channel'] ) );
		$number_of_posts 	= absint( $form_data['number_of_posts'] );
		$setcategory 		= absint( $form_data['select_category'] );
		$setauthor 			= absint( $form_data['set_author'] );
		$schedule 			= absint( $form_data['post_schedule'] );
		$poststatus 		= sanitize_text_field( trim( $form_data['post_status'] ) );

		/**
		 * set args to override $default
		 * @var array
		 */
		$args = array();

		// set post type
		if ( current_user_can('manage_options')) {
			$args['post_type'] 	= sanitize_text_field( trim( $form_data['set_post_type'] ) );
		} else {
			$args['post_type'] 	= 'post';
		}

		$args['create_author']		= $setauthor;
		$args['youtube_channel'] 	= $channelId;
		$args['number_of_posts'] 	= $number_of_posts;
		$args['setcategory']		= $setcategory;
		$args['post_status']		= $poststatus;
		$args['post_schedule']		= $schedule;
		$args['hashtags']			= array( get_term( $args['setcategory'] , 'category' )->name );

		/**
		 * creates the posts
		 */
		$ids = ChannelImport::publish( $channelId , $args );

		// get the posts ids
		return $ids;
	}

}
