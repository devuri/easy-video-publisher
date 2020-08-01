<?php

namespace SimAutoPost;

/**
 *
 */
class YoutubePostCreate
{

	/**
	 * get the video id from url
	 * @param  $video_url
	 * @return string
	 */
	public static function get_video_id( $video_url = null ){
		if ( ! $video_url == null ) {
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url , $vid_id)) {
				$the_id = $vid_id[1];
			}
			return $the_id;
		}
	}

	/**
	 * Download image and set as featured image
	 * @param  [type] $image_url  [description]
	 * @param  [type] $post_id    [description]
	 * @param  [type] $post_title [description]
	 * @return [type]             [description]
	 */
	public static function featured_image( $vid_img_id, $post_id,  $post_title ){

		// @link https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api
		// example https://img.youtube.com/vi/gu07qmagjSg/maxresdefault.jpg

		$image_url = 'https://img.youtube.com/vi/'.$vid_img_id.'/maxresdefault.jpg';
		//$image_url = 'https://img.youtube.com/vi/'.$vid_img_id.'/0.jpg';

		$upload_dir = wp_upload_dir();

		// use @ to suppress error incase
		$image_data = @file_get_contents($image_url);
		$image_name = basename($image_url);

		//split to array return file name and extension
		$IMGgetExtension = explode(".", $image_name);

		$IMGName = $IMGgetExtension[0];
		$IMGExtension = $IMGgetExtension[1];

		// set new IMAGE Name here (myfile.jpg)
		$filename = sanitize_title($post_title.'-'.$vid_img_id).'.'.$IMGExtension;

		// ok lets upload and stuff
		if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
		else
			$file = $upload_dir['basedir'] . '/' . $filename;

			// use @ to suppress error incase
			@file_put_contents($file, $image_data);

			$wp_filetype = wp_check_filetype($filename, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($filename),
				'post_content' => '',
				'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

		require_once(ABSPATH . 'wp-admin/includes/image.php');

			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			$res1= wp_update_attachment_metadata( $attach_id, $attach_data );
			$res2= set_post_thumbnail( $post_id, $attach_id );

	}
	/**
	 * Youtube Block for wordpress
	 * @param string $vid the video ID
	 * @return string
	 */
	public static function youtube_block( $vid = null ){
		$yt_block = '<!-- wp:core-embed/youtube {"url":"https://www.youtube.com/watch?v='.$vid.'","type":"video","providerNameSlug":"youtube","className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
		<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
		https://www.youtube.com/watch?v='.$vid.'
		</div></figure>
		<!-- /wp:core-embed/youtube -->';
		return $yt_block;
	}


	/**
	 * Create the Post
	 * @param  [type] $youtube_video_id [description]
	 * @return
	 */
	public static function newpost( $youtube_video = null){

		if ( ! $youtube_video == null ) {

			/**
			 * video id
			 */
			$video_id = self::get_video_id($youtube_video);

			$title  = 'The Video Title';

			$post_info = array(
					'post_title' 		=> $title,
					'post_content' 	=> self::youtube_block($video_id),
					'post_type' 		=> 'post',
					'post_status' 	=> 'publish',
					'post_category'	=> array(2),
					'tags_input' 		=> wp_strip_all_tags('tag 1,tag2'),
					'post_author'   => 1,
			);

			/**
			 * create the post
			 */
			$post_id = wp_insert_post( $post_info );

			/**
			 *
			 */
			self::featured_image( $video_id, $post_id, $title  );
		}

		return $post_id;
	}

}
