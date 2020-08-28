<?php

namespace VideoPublisherPro\YouTube;

	use VideoPublisherPro\YouTube\YoutubeVideoInfo;
	use VideoPublisherPro\Post\InsertPost;
	use VideoPublisherPro\Post\GetBlock;
	use VideoPublisherPro\UserFeedback;

/**
 *
 */
class AddNewVideo
{

	/**
	 * publish_video add new video post.
	 *
	 * @param array $form_data brings in the $_POST data from the form submission.
	 */
	public static function publish( array $form_data = array() ){

		/**
		 * video
		 */
		$vid = sanitize_text_field( trim( $form_data['youtube_video_url'] ) );

		# overrides title
		$args = array();
		if ( isset( $form_data['custom_title'] ) && isset( $form_data['video_title'] ) ) {
			$args['title'] 			= sanitize_text_field( trim( $form_data['video_title'] ) );
			$custom_title 			= true;
		}

		// set post type
		if ( current_user_can('manage_options')) {
			$args['post_type'] 		= sanitize_text_field( trim( $form_data['set_post_type'] ) );
		} else {
			$args['post_type'] 		= 'post';
		}

		$args['embed'] 				= GetBlock::youtube( $vid );
		$args['thumbnail'] 		= YoutubeVideoInfo::video_thumbnail( $vid );
		$args['category'] 		= intval( trim( $form_data['select_category'] ) );
		$args['tags'] 				= sanitize_text_field( trim( $form_data['tags'] ) );
		$args['description']	= wp_filter_post_kses( trim( $form_data['post_description'] ) );
		$args['hashtags']			= array( get_term( $args['category'] , 'category' )->name );

		/**
		 * make sure this is a youtube url
		 */
		if ( YoutubeVideoInfo::video_id($vid) ) {

			$id = InsertPost::newpost($vid, $args);

			if ($id) {
				$vidstatus  = UserFeedback::message('Video Has been Posted <strong> '.get_post( $id )->post_title.' </strong> ');
				$vidstatus .= '<div id="new-post-preview">';
				$vidstatus .= '<img width="400" src="'.get_the_post_thumbnail_url( $id ).'">';
				$vidstatus .= '<br>';
				$vidstatus .= '<a href="'.get_permalink( $id ).'" target="_blank">'.get_post( $id )->post_title.'</a>';
				$vidstatus .= '</div>';
				return $vidstatus;
			}
		} else {
			$id = false;
			$vidstatus = UserFeedback::message('Please Use a Valid YouTube url !!!', 'error');
			return $vidstatus;
		}
	}

}
