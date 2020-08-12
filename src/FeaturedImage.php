<?php

namespace EasyVideoPublisher;


/**
 *
 */
class FeaturedImage
{

	/**
	 * Download image and set as featured image
	 * @param  string $url  	youtube video id
	 * @param  int 		$post_id    	the post id
	 * @param  string $post_title 	title of the post
	 * TODO use oEmbed to get the Image
	 * @return int 		$attach_id		the attachment id
	 */
	public static function setfeatured_image( $thumbnail , $post_id,  $post_title ){

		$image_url = $thumbnail;

		/**
		 * lets make sure all is well
		 * The maxresdefault is not always available
		 * if we cant get the high resolution (maxresdefault) use the (hqdefault)
		 */
		$get_image = wp_remote_get( $image_url );
		IsError::error_check( $get_image );

 		if ( ! $get_image['response']['code'] == 200 ) {
			wp_die( VideoPublisherAdmin::form()->user_feedback( 'I cant download the Image to set featured Image' .' !!!', 'error'));
 		}

		// ok lets upload and stuff
		$filename = sanitize_file_name(strtolower($post_title.'-'.uniqid()));
		$upload_dir = wp_upload_dir();

		/**
		 * Upload the file and return some info
		 */
		$image = UploadImage::upload( $image_url , $upload_dir['path'] , $filename )->save();
		$file = $image['dirname'] . '/' . $image['basename'];

		$wp_filetype = wp_check_filetype($image['basename'], null );
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => $img_title,
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, $file , $post_id );

		require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			$res1= wp_update_attachment_metadata( $attach_id, $attach_data );
			$res2= set_post_thumbnail( $post_id, $attach_id );

		return $attach_id;
	}

}
