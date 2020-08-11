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
	public static function set_post_image( $url, $post_id,  $post_title ){

		/**
		 * set up to use the maxresdefault image
		 * example https://img.youtube.com/vi/yXzWfZ4N4xU/maxresdefault.jpg
		 * @link https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api
		 */
		$image_url = 'https://img.youtube.com/vi/'.$url.'/maxresdefault.jpg';

		/**
		 * lets make sure all is well
		 * The maxresdefault is not always available
		 * if we cant get the high resolution (maxresdefault) use the (hqdefault)
		 */
		$get_image = wp_remote_get( $image_url );
 		if ( $get_image['response']['code'] == 200 ) {
			// req is ok
 			$image_url = 'https://img.youtube.com/vi/'.$url.'/maxresdefault.jpg';
 		} else {
			$image_url = 'https://img.youtube.com/vi/'.$url.'/hqdefault.jpg';
 		}

		/**
		 * image details
		 */
		$upload_dir = wp_upload_dir();
		$image_data = @file_get_contents($image_url);
		$image_name = basename($image_url);

		/**
		 * split to array
		 * return file name and extension
		 * and rename the image to match the post
		 */
		$get_img_extension = explode(".", $image_name);
		$IMGName = $get_img_extension[0];
		$image_extension = $get_img_extension[1];
		$img_title = sanitize_text_field( $post_title .' - '. $url );
		$filename = sanitize_file_name(strtolower($post_title.'-'.$url)).'.'.$image_extension;

		// ok lets upload and stuff
		if (wp_mkdir_p($upload_dir['path'])) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

			/**
			 * create the post
			 */
			@file_put_contents($file, $image_data);
			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => $img_title,
				'post_content' => '',
				'post_status' => 'inherit'
			);

		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

		require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			$res1= wp_update_attachment_metadata( $attach_id, $attach_data );
			$res2= set_post_thumbnail( $post_id, $attach_id );

		return $attach_id;
	}

}
