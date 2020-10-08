<?php

namespace VideoPublisherlite\Post;

if ( ! defined('ABSPATH') ) exit;

class CreateUser
{

	/**
	 * setup a default author
	 * @return int the author id
	 */
	public static function evp_author(){

		$evp_author = get_option( 'evp_author', false );

			if ( $evp_author === false ) {
				$evp_author = self::new_user( 'evp-author' );
				update_option( 'evp_author' , $evp_author );
				return get_option('evp_author');
			}

		return get_option('evp_author');
	}

	/**
	 * new_user
	 * @param  string $username
	 * @return bool
	 * @link https://developer.wordpress.org/reference/functions/wp_insert_user/
	 */
	private static function new_user( $username = null ){

		/**
		 * lets clean this up
		 * @link https://developer.wordpress.org/reference/functions/sanitize_title/
		 */
		$display_name = sanitize_text_field( $username );
		$username 		= sanitize_title( $username );

		$user_id = username_exists( $username );
			if ( ! $user_id ) {
				$userdata = array(
					'user_login' 	 => $username,
					'display_name' => $display_name,
					'user_pass'		 => wp_generate_password( 10, true),
			);
				$user_id = wp_insert_user( $userdata );
			}
		// make sure we get an author here
		if ( is_int( $user_id ) ) {
			return $user_id;
		}
		// fall back to defualt author
		return self::evp_author();
	}

	/**
	 * maybe create a new author
	 * @param  string  $medialink     	the embed url
	 * @param  string  $author        	author name
	 * @param  bool $create_author 	if we should creat a new wp user
	 * @return string                 	the author
	 */
	public static function author( $medialink = null , $author = null , $create_author = false ){

		/**
		 * make sure all is well with our data
		 */
		if ( ! property_exists( UrlDataAPI::get_data( $medialink ), 'title') ) {
			return 0;
		}

		// get medialink
		$author_name	= UrlDataAPI::get_data( $medialink )->author_name;
		$author_url		= UrlDataAPI::get_data( $medialink )->author_url;

		// maybe create author
		if ( $create_author ) {
			$post_author = self::new_user( $author_name );
		} else {
			$post_author = $author;
		}
		return $post_author;
	}
}
