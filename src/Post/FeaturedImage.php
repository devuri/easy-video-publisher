<?php

namespace VideoPublisherlite\Post;

	use VideoPublisherlite\UserFeedback;
	use VideoPublisherlite\IsError;

/**
 *
 */
class FeaturedImage
{

	/**
	 * Download image and set as featured image
	 * @param  string $thumbnail  	image url
	 * @param  int 		$post_id    	the post id
	 * @param  string $post_title 	title of the post
	 * @return int 		$attach_id		the attachment id
	 */
	public static function setfeatured_image( $thumbnail , $post_id,  $post_title ){

		/**
		 * lets make sure all is well
		 */
		$image_url = $thumbnail;
		$get_image = wp_remote_get( $image_url );
		IsError::error_check( $get_image );

		// if all is not ok give us some feedback
 		if ( ! $get_image['response']['code'] == 200 ) {
			wp_die( UserFeedback::message( 'I cant download the Image to set featured Image' .' !!!', 'error'));
 		}

		/**
		 * ok lets upload and stuff
		 * add uniqid() to avoid replacing the image
		 * @var [type]
		 */
		$filename = sanitize_file_name( strtolower($post_title.'-'.uniqid()) );
		$upload_dir = wp_upload_dir();

		/**
		 * Upload the file and return some info
		 * return image array()
		 */
		$image = UploadImage::upload( $image_url , $upload_dir['path'] , $filename )->save();
		$file = $image['dirname'] . '/' . $image['basename'];

		$wp_filetype = wp_check_filetype( $image['basename'] , null );
		$attachment = array(
			'post_mime_type' 	=> $wp_filetype['type'],
			'post_title' 			=> $post_title,
			'post_content' 		=> '',
			'post_status' 		=> 'inherit'
		);

		// create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file , $post_id );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			$res1= wp_update_attachment_metadata( $attach_id, $attach_data );
			$res2= set_post_thumbnail( $post_id, $attach_id );

		return $attach_id;
	}

}
