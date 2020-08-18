<?php

namespace VideoPublisherPro\YouTube;

	use VideoPublisherPro\Post\UrlDataAPI;


/**
 *
 */
class YoutubeVideoInfo
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

	public static function video_thumbnail( $video_url = null ){

		# get the video id
		$vid_id = self::video_id( $video_url );

		/**
		 * set up to use the maxresdefault image
		 * example https://img.youtube.com/vi/yXzWfZ4N4xU/maxresdefault.jpg
		 * @link https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api
		 */
		$image_url = 'https://img.youtube.com/vi/'.$vid_id.'/maxresdefault.jpg';

		/**
		 * lets make sure all is well
		 * The maxresdefault is not always available
		 * if we cant get the high resolution (maxresdefault) use the (hqdefault)
		 */
		$get_image = wp_remote_get( $image_url );
		if ( $get_image['response']['code'] == 200 ) {
			// req is ok
			$thumbnail = 'https://img.youtube.com/vi/'.$vid_id.'/maxresdefault.jpg';
		} else {
			$thumbnail = 'https://img.youtube.com/vi/'.$vid_id.'/hqdefault.jpg';
		}
		return $thumbnail;
	}

	/**
	 * Get youtube video info using WP_oEmbed
	 * @param  mixed  	$v    	video id, or array of video ids
	 * @param  integer 	$limit 	how many videos
	 * @return array         		video data
	 */
	public static function video_info( $v = null , $limit = 1 ) {
		/**
		 * get vid info for array
		 */
		if ( is_array($v) ) {

			$i = 0;
			foreach ( $v as $key => $v) {
				$vid[$key] = array(
					'id' 					=> $v,
					'title' 			=> UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->title,
					'thumbnail' 	=> UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->thumbnail_url,
					'author_name' => UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->author_name,
					'author_url' 	=> UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->author_url,
				);

				# stop if we reach the limit
				if(++$i == $limit) break;
			}
			return $vid;

		} else {
			$vid = array(
				'id' 					=> $v,
				'title' 			=> UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->title,
				'thumbnail' 	=> UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->thumbnail_url,
				'author_name' => UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->author_name,
				'author_url' 	=> UrlDataAPI::get_data( 'https://www.youtube.com/watch?v='.$v )->author_url,
			);
			return $vid;
		}
	}

}
