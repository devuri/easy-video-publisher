<?php

namespace EasyVideoPublisher;

/**
 *
 */
class YoutubeVideoPost
{

	/**
	 * get the video id from url
	 * @param  $video_url
	 * @return string
	 */
	public static function video_id( $video_url = null ){
		if ( ! $video_url == null ) {
			if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url , $vid_id)) {
				$the_id = $vid_id[1];
			}
			return $the_id;
		}
	}

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
	 * video_data() using  WP_oEmbed
	 * @param  string $vid_url video url
	 * @return object
	 * @link https://developer.wordpress.org/reference/classes/wp_oembed/
	 */
	public static function video_data( $vid_url = null ){
		$oEmbed = new \WP_oEmbed;
		$vid_data = $oEmbed->get_data($vid_url);
		return $vid_data;
	}

	/**
	 * Download image and set as featured image
	 * @param  [type] $image_url  [description]
	 * @param  [type] $post_id    [description]
	 * @param  [type] $post_title [description]
	 * @return [type]             [description]
	 */
	public static function featured_image( $vid_img_id, $post_id,  $post_title ){

		/**
		 * set up to use the maxresdefault image
		 * example https://img.youtube.com/vi/yXzWfZ4N4xU/maxresdefault.jpg
		 * @link https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api
		 */
		$image_url = 'https://img.youtube.com/vi/'.$vid_img_id.'/maxresdefault.jpg';

		/**
		 * lets make sure all is well
		 * The maxresdefault is not always available
		 * if we cant get the high resolution (maxresdefault) use the (hqdefault)
		 */
		$get_response = wp_remote_get( $image_url );
 		if ( $get_response['response']['code'] == 200 ) {
			// req is ok
 			$image_url = 'https://img.youtube.com/vi/'.$vid_img_id.'/maxresdefault.jpg';
 		} else {
			$image_url = 'https://img.youtube.com/vi/'.$vid_img_id.'/hqdefault.jpg';
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
		$filename = sanitize_title($post_title.'-'.$vid_img_id).'.'.$image_extension;

		// ok lets upload and stuff
		if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
		else
			$file = $upload_dir['basedir'] . '/' . $filename;

			/**
			 * create the post
			 */
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

		return $attach_id;
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
	public static function newpost( $youtube_video = null , $html = false){

		if ( ! $youtube_video == null ) {

			/**
			 * video info
			 */
			$video_id 			= self::video_id($youtube_video);
			$title  				= self::video_data($youtube_video)->title;
			$thumbnail_url	= self::video_data($youtube_video)->thumbnail_url;
			$video_author 	= self::video_data($youtube_video)->author_name;
			$author_url  		= self::video_data($youtube_video)->author_url;
			if ( $html == true ) {
				$video_embed = self::video_data($youtube_video)->html;
			} else {
				$video_embed = self::youtube_block($video_id);
			}

			# post author
			$post_author = self::create_user($video_author);

			/**
			 * Post info
			 */
			$post_info = array(
					'post_title' 		=> $title,
					'post_content' 	=> $video_embed,
					'post_type' 		=> 'post',
					'post_status' 	=> 'publish',
					'post_category'	=> array(2),
					'tags_input' 		=> wp_strip_all_tags($video_author),
					'post_author'   => $post_author,
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
