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
		if ( empty( get_option('evp_youtube_api', array() ) ) ) {
			return false;
		} else {
			$apikey = get_option('evp_youtube_api');
		}
		// get the keys
		$apikey = array_keys($apikey);
		shuffle( $apikey );
		if ( isset( $apikey[0] ) ) {
			return $apikey[0];
		}

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
	 * Adds New API key if its valid.
	 * @param  [type] $youtube_api_key [description]
	 * @return [type]                  [description]
	 */
	public static function addnew_api_key( $youtube_api_key = null ){

		if ( is_null($youtube_api_key) ) {
			$is_key_valid = false;
		} else {
			// check if the key is valid
			$is_key_valid = self::is_key_valid($youtube_api_key);
		}


		/**
		 * check the key
		 * @var [type]
		 */
		if ( $is_key_valid ) {

			// set the API key with a timestamp
			$new_key			= array( $youtube_api_key => time() );
			$update_keys	= array_merge( $new_key , (array) get_option( 'evp_youtube_api' ) );

			# check if we already have the key in recent updates
			$api_keys 		= (array) get_option( 'evp_youtube_api' );
			$key_exists 	= array_key_exists( $youtube_api_key , $api_keys );

			# check if we already have that key
			if ( $key_exists ) {
				echo UserFeedback::message('<strong> <span style="color:#dc3232">'.$youtube_api_key.'</span></strong> already Exists !!', 'error');
			} else {
				# add the new api key
				update_option('evp_youtube_api', $update_keys );
				echo UserFeedback::message( 'New API Key <strong> <span style="color:#037b0e">'.$youtube_api_key.'</span></strong> has been successfully added !!');
			}

		} else {
			echo UserFeedback::message('The Key: <span style="color:#dc3232">'.$youtube_api_key.'</span> <strong> is NOT A Valid Key !! </strong> ', 'error');
		}
	}

	/**
	 * use to verify specific api key
	 * @return bool [description]
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
		$keys = (array) get_option('evp_youtube_api');

			$keylist 	= '<h4>API Keys:</h4>';
			$keylist 	.= '<ul style="list-style: decimal;margin-left: 2em;">';
			foreach( get_option('evp_youtube_api') as $key => $time ) {
				$key = substr( $key , 0, -20 );
				$keylist 	.= '<li><strong>'.$key.'...</strong> Since '.date_i18n( get_option( 'date_format' ), $time ).'</li>';
			}
		$keylist 	.= '</ul><br>';
		return $keylist;
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
			echo UserFeedback::message( $e->getMessage() , 'error');
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
