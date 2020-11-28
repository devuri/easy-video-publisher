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
			return false;
		}

		// Get the form data.
		$this->form_data = $data;

	}

	/**
	 * Add_video import and post channel videos
	 */
	private function video_args() {

		/**
		 * Set args to override $default
		 *
		 * @var
		 */
		$args = array();

		$args['post_type']       = sanitize_text_field( trim( $this->form_data['set_post_type'] ) );
		$args['create_author']   = absint( $this->form_data['set_author'] );
		$args['youtube_channel'] = sanitize_text_field( trim( $this->form_data['youtube_channel'] ) );
		$args['number_of_posts'] = absint( $this->form_data['number_of_posts'] );
		$args['setcategory']     = absint( $this->form_data['select_category'] );
		$args['post_status']     = sanitize_text_field( trim( $this->form_data['post_status'] ) );
		$args['post_schedule']   = absint( $this->form_data['post_schedule'] );
		$args['set_description'] = absint( $this->form_data['import_with_video_description'] );
		$args['author']          = get_current_user_id();
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

		// do the import delayed by 5 minutes in seconds 600.
		wp_queue()->push( new ChannelImport( $the_channel, $args ), 600 );
		echo UserFeedback::message( 'Import Has been added to the Queue ! ' ); // @codingStandardsIgnoreLine

	}

}
