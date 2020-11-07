<?php

namespace VideoPublisherlite\YouTube;

use VideoPublisherlite\Post\ChannelImport;
use VideoPublisherlite\UserFeedback;

/**
 * Youtube Channel Import
 */
final class ImportVideo
{
	/**
	 * Data we need .
	 *
	 * @var array .
	 */
	private $form_data;

	/**
	 * AddNewVideo constructor.
	 *
	 * @param array $data .
	 */
	public function __construct( $data = array() ) {

		// make sure we have a valid key.
		if ( ! YouTubeData::api()->has_key() ) {
			echo UserFeedback::message( 'Key is not Valid, Requires A Valid YouTube API Key ! ', 'error' ); // @codingStandardsIgnoreLine
			return 0;
		}

		// if the channel is not set or empty return.
		if ( empty( (array) get_option( 'evp_channels' ) ) ) {
			echo UserFeedback::message( 'Please Add a YouTube Channel ! ', 'error' ); // @codingStandardsIgnoreLine
			return 0;
		}

		// Get the form data.
		$this->form_data = $data;

	}

	/**
	 * Clean the data
	 *
	 * @return array items .
	 */
	private function get_data() {

		/**
		 * Get the channel to post from
		 */
		$get_data = array();
		$get_data['channel_id']      = sanitize_text_field( trim( $this->form_data['youtube_channel'] ) );
		$get_data['number_of_posts'] = absint( $this->form_data['number_of_posts'] );
		$get_data['setcategory']     = absint( $this->form_data['select_category'] );
		$get_data['setauthor']       = absint( $this->form_data['set_author'] );
		$get_data['schedule']        = absint( $this->form_data['post_schedule'] );
		$get_data['poststatus']      = sanitize_text_field( trim( $this->form_data['post_status'] ) );
		$get_data['post_type']       = sanitize_text_field( trim( $this->form_data['set_post_type'] ) );

		return $get_data;
	}

	/**
	 * Add_video import and post channel videos
	 */
	private function video_args() {

		$video_args = $this->get_data();

		/**
		 * Set args to override $default
		 *
		 * @var
		 */
		$args = array();

		$args['post_type']       = $video_args['post_type'];
		$args['create_author']   = $video_args['setauthor'];
		$args['youtube_channel'] = $video_args['channel_id'];
		$args['number_of_posts'] = $video_args['number_of_posts'];
		$args['setcategory']     = $video_args['setcategory'];
		$args['post_status']     = $video_args['poststatus'];
		$args['post_schedule']   = $video_args['schedule'];
		$args['hashtags']        = array( get_term( $args['setcategory'], 'category' )->name );

		return $args;

	}

	/**
	 * Creates the posts
	 */
	public function add_video() {

		// get video args .
		$args = $this->video_args();
		$the_channel = $args['youtube_channel'];

		// do the import .
		wp_queue()->push( new ChannelImport( $the_channel, $args ) );

	}

}
