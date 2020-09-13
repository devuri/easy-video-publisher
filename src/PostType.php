<?php
namespace VideoPublisherPro;

/**
 *
 */
class PostType
{

	public static function post_types(){
		/**
		 * get the post types
		 * @var array
		 */
		$pt_args = array(
			'public'   => true,
		);
		$ptypes = get_post_types( $pt_args, 'objects' );

		// post type array
		foreach ( $ptypes as $ptkey => $post_type ) {
			$post_types[$ptkey] = $post_type->labels->singular_name;
		}
		return $post_types;
	}

}
