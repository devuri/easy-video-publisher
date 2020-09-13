<?php

namespace VideoPublisherPro\YouTube;

use Madcoda\Youtube\Youtube;
use VideoPublisherPro\UserFeedback;

/**
 *
 */
class YouTubeDataAPI
{

	/**
	 * Get API key
	 * uses a random key each time if mutiple keys are available.
	 * if no keys are available returns false.
	 * @return mixed The API Key.
	 */
	private static function apikey(){
		// get the keys
		$apikey = self::get_keys();

		// key shuffle
		if ( $apikey ) {
			shuffle( $apikey );
		}

		// get key
		// TODO only return a valid key
		if ( isset( $apikey[0] ) ) {
			return $apikey[0];
		} else {
			return false;
		}

	}

  /**
   * [get_keys description]
   * @return array|false [type] [description]
	 */
	public static function get_keys(){
		if ( empty( get_option('evp_youtube_api', array() ) ) ) {
			return false;
		} else {
			$apikey = (array) get_option('evp_youtube_api');
		}
		// get the keys
		$apikey = array_keys($apikey);
		return $apikey;
	}

	/**
	 * API key check
	 * check if an API key has been set
	 * @return boolean
	 */
	public static function has_key(){

		$apikey = (array) get_option('evp_youtube_api');

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
	 * use to verify specific api key
	 * @param null $apikey
	 * @return bool [description]
	 * @throws \Exception
	 */
	public static function is_key_valid( $apikey = null ){
		$verify_api_key = new Youtube(
			array('key' => $apikey )
		);
		try {
			$verify_api_key->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			return false;
		}
		return true;
	}

	/**
	 * Get a list of the API keys
	 * @return string API Keys
	 */
	public static function keys(){

		$keylist 	= '<h4>API Keys:</h4>';
		$keylist 	.= '<ul style="list-style: decimal;margin-left: 2em;">';
		foreach( get_option('evp_youtube_api' , array()) as $key => $time ) {
			$key = substr( $key , 0, -20 );
			$keylist 	.= '<li><strong>'.$key.'...</strong> Since '.date_i18n( get_option( 'date_format' ), $time ).'</li>';
		}
		$keylist 	.= '</ul><br>';
		return $keylist;
	}

	/**
	 * Get a list of channels
	 * @return string list_channels
	 */
	public static function list_channels(){

			$chanlist 	= '<h4>Channels:</h4>';
			$chanlist 	.= '<ul style="list-style: decimal;margin-left: 2em;">';
			foreach( get_option('evp_channels' , array() ) as $chkey => $channel ) {
				$chanlist 	.= '<li><strong>'.$channel.'</strong></li>';
			}
			$chanlist 	.= '</ul><br>';
		return $chanlist;
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
   * @return void [type] [description]
   */
	public static function response_error(){
		try {
				self::youtube()->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			echo UserFeedback::message( $e->getMessage() , 'error');
		}
	}

  /**
   * get the latest videos by a channel.
   * @param string $channelId [description]
   * @param integer $limit [description]
   * @return mixed [type]             [description]
   */
	public static function channel_videos( $channelId = 'UCWOA1ZGywLbqmigxE4Qlvuw', $limit = 12 ){

		// get the channel videos
		try {
			$videos = self::youtube()->searchChannelVideos('', $channelId , $limit,'date');
		} catch (\Exception $e) {
			return 0;
		}

		// make sure we get the data,
		// this will return false if we dont get any videos
		if ( ! $videos ) {
			return 0;
		}


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
		return $description;
	}

  /**
   * get video info
   * @param string $vid
   * @return string
   * @throws \Exception
   */
	public static function video_info( $vid = '' ){
		$info = self::youtube()->getVideoInfo( $vid )->snippet;
		return $info;
	}

	/**
 	 * channelby_id
 	 * @param  [type] $id [description]
 	 * @return false|\StdClass [type]     [description]
 	 * @throws \Exception
 	 */
	public static function channelby_id( $id = null ){
		$channel = self::youtube()->getChannelById( $id , false );
		return $channel;
	}


  /**
   * get videos from multiple channels
   * @param array $channels [description]
   * @return mixed [type]           [description]
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
