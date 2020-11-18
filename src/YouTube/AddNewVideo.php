<?php

namespace VideoPublisherlite\YouTube;

use VideoPublisherlite\Post\InsertPost;
use VideoPublisherlite\Post\GetBlock;
use VideoPublisherlite\UserFeedback;
use VideoPublisherlite\Database\VideosTable;
use VideoPublisherlite\Post\UrlDataAPI;

/**
 * Youtube Video Publisher
 */
class AddNewVideo
{

	/**
	 * Data we might need .
	 *
	 * @var array .
	 */
	public $form_data;

	/**
	 * AddNewVideo constructor.
	 *
	 * @param array $data .
	 */
	public function __construct( $data = array() ) {
		$this->form_data = $data;
	}

	/**
	 * Publish the video add new video post.
	 */
	public function publish() {

		/**
		 * Video url.
		 */
		$vid = sanitize_text_field( trim( $this->form_data['youtube_video_url'] ) );

		// overrides title.
		$args = array();
		if ( isset( $this->form_data['custom_title'] ) && isset( $this->form_data['video_title'] ) ) {
			$args['title'] = sanitize_text_field( trim( $this->form_data['video_title'] ) );
			$custom_title  = true;
		}

		// set post type.
		if ( current_user_can( 'manage_options' ) ) {
			$args['post_type'] = sanitize_text_field( trim( $this->form_data['set_post_type'] ) );
		} else {
			$args['post_type'] = 'post';
		}

		$args['embed']       = GetBlock::youtube( $vid );
		$args['thumbnail']   = YoutubeVideoInfo::video_thumbnail( $vid );
		$args['category']    = intval( trim( $this->form_data['select_category'] ) );
		$args['tags']        = sanitize_text_field( trim( $this->form_data['tags'] ) );
		$args['description'] = wp_filter_post_kses( trim( $this->form_data['post_description'] ) );
		$args['hashtags']    = array( get_term( $args['category'], 'category' )->name );

		/**
		 * Make sure this is a youtube url.
		 */
		if ( YoutubeVideoInfo::video_id( $vid ) ) {

			$video_id = YoutubeVideoInfo::video_id( $vid );

			if ( VideosTable::video_exists( $video_id ) ) {
				// TODO link to the specific post.
				return UserFeedback::message( 'This Video was already Published', 'warning' );
			}

			$id = InsertPost::newpost( $vid, $args );

			if ( $id ) {

				// add to "evp_videos" table.
				( new VideosTable() )->insert_data(
					array(
						'post_id'       => $id,
						'user_id'       => get_post_field( 'post_author', $id ),
						'campaign_id'   => 0,
						'video_id'      => $video_id,
						'channel_title' => UrlDataAPI::get_data( $vid )->author_name,
					)
				);

				// user feedback.
				$vidstatus  = UserFeedback::message( 'Video Has been Published: ' . get_post( $id )->post_title );
				$vidstatus .= '<div id="new-post-preview">';
				$vidstatus .= '<img width="400" src="' . get_the_post_thumbnail_url( $id ) . '">';
				$vidstatus .= '<br>';
				$vidstatus .= '<a href="' . get_permalink( $id ) . '" target="_blank">' . get_post( $id )->post_title . '</a>';
				$vidstatus .= '</div>';
				return $vidstatus;
			}
		} else {
			$id = false;
			$vidstatus = UserFeedback::message( 'Please Use a Valid YouTube url !!!', 'error' ); // @codingStandardsIgnoreLine
			return $vidstatus;
		}
	}

}
