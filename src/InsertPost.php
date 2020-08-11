<?php

namespace EasyVideoPublisher;


/**
 *
 */
class InsertPost
{

	/**
	 * create_user
	 * @param  string $username
	 * @return boolean
	 */
	public static function create_user( $username = null ){
			$user_id = username_exists( $username );
			 	if ( ! $user_id ) {
					 $userdata = array(
							 'user_login' 	=> $username,
							 'display_name' => $username,
							 'user_pass'  	=> wp_generate_password( 10, true),
					 );
					 $user_id = wp_insert_user( $userdata );
			 	}
	 		return $user_id;
	}

	/**
	 * Create the Post
	 * @param  string $geturl youtube url
	 * @param  array  $args   other options
	 * @return int
	 */
	public static function newpost( $geturl = null , $args = array()){

		/**
		 * default args
		 */
		$default = array();
		$default['title'] 				= UrlDataAPI::get_data($geturl)->title;
		$default['category'] 			= array(1);
		$default['post_type'] 		= 'post';
		$default['post_status'] 	= 'publish';
		$default['html'] 					= false;
		$default['create_author'] = false;
		$default['author'] 				= get_current_user_id();
		$default['tags'] 					= array();
		$default['description'] 	= '';
		$args = wp_parse_args( $args , $default );


		if ( ! $geturl == null ) {

			/**
			 * info
			 */
			$video_id 	= YoutubeVideo::video_id($geturl);
			$thumbnail	= UrlDataAPI::get_data($geturl)->thumbnail_url;
			$author 		= UrlDataAPI::get_data($geturl)->author_name;
			$author_url	= UrlDataAPI::get_data($geturl)->author_url;


			if ( $args['html'] == true ) {
				$embed = GetBlock::html($video_id);
			} else {
				$embed = GetBlock::youtube($video_id);
			}

			/**
			 * create a new author
			 */
			if ( $args['create_author'] ) {
				$post_author = self::create_user($author);
			} else {
				$post_author = $args['author'];
			}

			/**
			 * Post info
			 */
			$postInfo = array(
					'post_title' 		=> $args['title'],
					'post_content' 	=> $embed.'<p>'.$args['description'].'</p>',
					'post_type' 		=> $args['post_type'],
					'post_status' 	=> $args['post_status'],
					'post_category'	=> array($args['category']),
					'tags_input' 		=> wp_strip_all_tags($args['tags']),
					'post_author'   => $post_author,
			);

			/**
			 * create the post
			 */
			$post_Id = wp_insert_post( $postInfo );

			/**
			 * set featured image
			 */
			YoutubeVideo::featured_image( $video_id, $post_Id, $args['title']  );
		}

		return $post_Id;
	}

}
