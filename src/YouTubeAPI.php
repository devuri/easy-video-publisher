<?php

namespace EasyVideoPublisher;

use Madcoda\Youtube\Youtube;

/**
 *
 */
class YouTubeAPI
{

	/**
	 * Get API key
	 * uses a random key each time if mutiple keys ara available.
	 * @return string The API Key
	 */
	private static function apikey(){
		$apikey = get_option('evp_youtube_api');
		shuffle( $apikey );
		return $apikey[0];
	}

	/**
	 * API key check
	 * check if an API key has been set
	 * @return boolean
	 */
	public static function has_key(){

		$apikey = get_option('evp_youtube_api');

			if ( ! $apikey ) :
				return false;
			endif;

		return true;
	}

	/**
	 * youtube object
	 * @return object
	 * @link https://github.com/madcoda/php-youtube-api
	 */
	public static function youtube(){
		return new Youtube(
			array('key' => self::apikey() )
		);
	}

	/**
	 * Get a list of the API keys
	 * @return string API Keys
	 */
	public static function keys(){
		$keys = get_option('evp_youtube_api');
		?><h4>API Keys List</h4><ul>
				<?php foreach( get_option('evp_youtube_api') as $k => $key ) { ?>
          <li><?php echo $key; ?></li>
				<?php } ?>
			</ul><?
	}

	/**
	 * lets make sure all is well
	 * @return boolean
	 */
	public static function is_request_ok(){
		try {
				self::youtube()->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			return false;
		}
		return true;
	}

	/**
	 * exit whatever is goin on here
	 * @return [type] [description]
	 */
	public static function response_error(){
		try {
				self::youtube()->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			wp_die(VideoPublisherAdmin::form()->user_feedback( $e->getMessage(), 'error'));;
		}
	}

	/**
	 * get the latest videos by a channel.
	 * @param  string  $channelId [description]
	 * @param  integer $limit     [description]
	 * @return [type]             [description]
	 */
	public static function channel_videos( $channelId = 'UCWOA1ZGywLbqmigxE4Qlvuw', $limit = 12 ){

		# get the channel videos
		$videos = self::youtube()->searchChannelVideos('', $channelId , $limit,'date');

		/**
		 * return array of video data
		 * @var [type]
		 */
		foreach ( $videos as $vkey => $vid ) {
			$vidinfo[$vkey]		= $vid->id->videoId;
		}
		return $vidinfo;
	}

	/**
	 * get video description
	 * @param  string $videoId [description]
	 * @return string
	 */
	public static function video_description( $videoId = '' ){
		$description 	= self::youtube()->getVideoInfo($videoId)->snippet->description;
		return $description ;
	}

	/**
	 * get video info
	 * @param  string $videoId [description]
	 * @return string
	 */
	public static function video_info( $vid = '' ){
		$info = self::youtube()->getVideoInfo( $vid )->snippet;
		return $info;
	}

	/**
	 * get videos from multiple channels
	 * @param  array  $channels [description]
	 * @return [type]           [description]
	 */
	public static function channels_vids( $channels = array() ){
		/**
		 * process channel ids
		 */
		foreach ($channels as $key => $id) {
			$videos[] = self::channel_videos($id, 2);
		}
		return $videos;
	}

}
