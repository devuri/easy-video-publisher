<?php

namespace VideoPublisherPro\YouTube;

use Madcoda\Youtube\Youtube;
use VideoPublisherPro\Admin\VideoPublisherAdmin;

/**
 *
 */
class YouTubeDataAPI
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

			$klist 	= '<h4>API Keys List</h4>';
			$klist 	.= '<ul style="list-style: decimal;margin-left: 2em;">';
			foreach( get_option('evp_youtube_api') as $k => $key ) {
				$klist 	.= '<li>'.$key.'</li>';
			}
		$klist 	.= '</ul><br>';
		return $klist;
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
			echo VideoPublisherAdmin::form()->user_feedback( $e->getMessage() , 'error');
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
	 * channelby_id
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public static function channelby_id( $id = null ){
		$channel = self::youtube()->getChannelById( $id , false );
		return $channel;
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
