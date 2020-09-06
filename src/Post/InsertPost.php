<?php

namespace VideoPublisherPro\Post;


/**
 *
 */
class InsertPost
{

	/**
	 * create_user
	 * @param  string $username
	 * @return boolean
	 * @link https://developer.wordpress.org/reference/functions/wp_insert_user/
	 */
	private static function create_user( $username = null ){
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
	 * maybe create a new author
	 * @param  string  $medialink     	the embed url
	 * @param  string  $author        	author name
	 * @param  boolean $create_author 	if we should creat a new wp user
	 * @return string                 	the author
	 */
	private static function author( $medialink = null , $author = null , $create_author = false ){

		# get medialink
		$author_name	= UrlDataAPI::get_data( $medialink )->author_name;
		$author_url		= UrlDataAPI::get_data( $medialink )->author_url;

		# maybe create author
		if ( $create_author ) {
			$post_author = self::create_user( $author_name );
		} else {
			$post_author = $author;
		}
		return $post_author;
	}

  /**
   * creates a simple hashtag from the username
   * @param $medialink
   * @param $username
   * @return string [type]            [description]
   */
	private static function username( $medialink, $username ){
		$username 	= UrlDataAPI::get_data( $medialink )->author_name;
		$username 	= strtolower(sanitize_file_name( $username, true));
		$username 	= '  #' . $username;
		return $username;
	}

  /**
   * set hashtags
   * @param mixed $hashtags [description]
   * @return false|string [type]           [description]
   */
	private static function hashtags( $hashtags ){

		# if we did not get an array return false
		if ( ! is_array( $hashtags ) ) {
			return false;
		}

		#  get hashtag
		if ( $hashtags ) {
			foreach ( $hashtags as $hstkey => $hashtag ) {
				$htags 				= strtolower(sanitize_file_name($hashtag));
				if ( strpos($htags, '-') ) {
					$onewordtags 	= ' #'.str_replace( "-", "", $htags );
				} else {
					$onewordtags = '';
				}
				$htags 				= str_replace( "-", " #", $htags );
				$htags 				= ' #'.$htags;
			}
			return $htags.$onewordtags;
		}

	}

  /**
   * Create the Post
   * @param string|null $medialink youtube url
   * @param array $args other options
   * @return int
   */
	public static function newpost( string $medialink = null , array $args = array() ){

		/**
		 * default args
		 * @link https://developer.wordpress.org/reference/functions/wp_parse_args/
		 */
		$default = array();
		$default['username'] 			= false;
		$default['hashtags'] 			= false;
		$default['title'] 				= UrlDataAPI::get_data( $medialink )->title;
		$default['embed'] 				= $medialink;
		$default['category'] 			= array(1);
		$default['post_type'] 		= 'post';
		$default['post_status'] 	= 'publish';
		$default['post_date'] 		= current_time( 'mysql' );
		$default['html'] 					= false;
		$default['create_author'] = false;
		$default['author'] 				= get_current_user_id();
		$default['tags'] 					= array();
		$default['description'] 	= '';
		$default['thumbnail'] 		= UrlDataAPI::get_data( $medialink )->thumbnail_url;
		$args = wp_parse_args( $args , $default );

		# if the title is too long
		if ( strlen( $args['title'] ) > 180 ) {
			$args['title'] = substr( $args['title'].'...' , 0, 180 );
		}

		#  add username
		if ( $args['username'] ) {
			$username 	= '  @' . $args['username'];
		} else {
			$username 	= '';
		}

		if ( ! $medialink == null ) {

			/**
			 * setup info
			 */
			$title					= $args['title'];
			$hashtags				= self::hashtags( $args['hashtags'] );
			$thumbnail			= $args['thumbnail'];
			$embed					= $args['embed'];
			$description		= $args['description'];
			$post_type			= $args['post_type'];
			$post_status		= $args['post_status'];
			$category				= $args['category'];
			$tags						= $args['tags'];
			$create_author	= $args['create_author'];
			$author					= $args['author'];
			$post_date			= $args['post_date'];
			$post_author 		= self::author( $medialink , $author , $create_author );

			/**
			 * Post info
			 * @link https://developer.wordpress.org/reference/functions/wp_insert_post/
			 */
			$postInfo = array(
					'post_title' 		=> esc_html($title . $username . $hashtags),
					'post_content' 	=> $embed.'<p>'.$description.'</p>',
					'post_type' 		=> $post_type,
					'post_status' 	=> $post_status,
					'post_category'	=> array($category),
					'tags_input' 		=> $tags,
					'post_author'   => $post_author,
					'post_date' 		=> $post_date,
			);
			# create the post
			$postId = wp_insert_post( $postInfo );

			# set featured image
			FeaturedImage::setfeatured_image( $thumbnail , $postId, $title  );
		}

		return $postId;
	}

}
