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

		# set UrlDataAPI provider


		/**
		 * default args
		 */
		$default = array();
		$default['ig'] 						= false;
		$default['title'] 				= UrlDataAPI::get_data( $geturl )->title;
		$default['embed'] 				= $geturl;
		$default['category'] 			= array(1);
		$default['post_type'] 		= 'post';
		$default['post_status'] 	= 'publish';
		$default['html'] 					= false;
		$default['create_author'] = false;
		$default['author'] 				= get_current_user_id();
		$default['tags'] 					= array();
		$default['description'] 	= '';
		$default['thumbnail'] 		= UrlDataAPI::get_data($geturl)->thumbnail_url;
		$args = wp_parse_args( $args , $default );

		/**
		 * title is too long
		 */
		if ( strlen( $args['title'] ) > 180 ) {
			$args['title'] = substr( $args['title'].'...' , 0, 180 );
		}

		if ( $args['ig'] ) {
			$tag 	= ' - #' . $args['ig'];
			$ig 	= ',  @' . $args['ig'];
		}


		if ( ! $geturl == null ) {

			/**
			 * info
			 */
			$thumbnail	= $args['thumbnail'];
			$author 		= UrlDataAPI::get_data($geturl)->author_name;
			$author_url	= UrlDataAPI::get_data($geturl)->author_url;


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
					'post_title' 		=> $args['title'] . $tag . $ig,
					'post_content' 	=> $args['embed'].'<p>'.$args['description'].'</p>',
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
			FeaturedImage::setfeatured_image( $thumbnail , $post_Id, $args['title']  );
		}

		return $post_Id;
	}

}
