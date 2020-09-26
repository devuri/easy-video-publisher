<?php

namespace VideoPublisherlite\YouTube;

use Madcoda\Youtube\Youtube;
use VideoPublisherlite\UserFeedback;

/**
 *
 */
class YouTubeDataAPI extends Youtube
{

	/**
	 * class instance
	 */
	private static $instance = null;

	/**
	 * Constructor
	 */
	private function __construct() {
		try {
			$this->init();
		} catch (\Exception $error ) {
			return $error;
		}
	}

	/**
	 * initiate the API
	 * $youtube = new Youtube(array('key' => 'KEY HERE'))
	 *
	 * @param array $params
	 *
	 * @return Object
	 * @throws \Exception
	 * @link https://github.com/madcoda/php-youtube-api
	 */
	private function init(){
		parent::__construct(
			array('key' => $this->apikey() )
		);
	}

	/**
	 * new instance.
	 * @return object
	 */
	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new YouTubeDataAPI();
		}
		return self::$instance;
	}

	/**
	 * Get API key
	 *
	 * uses a random key each time if mutiple keys are available.
	 * if no keys are available returns false.
	 * @return mixed The API Key.
	 */
	private function apikey(){
		// get the keys
		$apikey = $this->get_keys();

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
	public function get_keys(){
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
	 *
	 * check if an API key has been set
	 * @return bool
	 */
	public function has_key(){

		$apikey = (array) get_option('evp_youtube_api');

			if ( ! $apikey ) :
				return false;
			endif;

		return true;
	}

	/**
	 * use to verify specific api key
	 *
	 * @param null $apikey
	 * @return bool [description]
	 * @throws \Exception
	 */
	public function is_key_valid( $apikey = null ){
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
	 * lets make sure all is well
	 *
	 * @return bool
	 */
	public function is_request_ok(){
		try {
				$this->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			return false;
		}
		return true;
	}

  /**
   * exit whatever is goin on here
   *
   * @return void [type] [description]
   */
	public function response_error(){
		try {
				$this->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			echo UserFeedback::message( $e->getMessage() , 'error');
		}
	}

  /**
   * get the latest videos by a channel.
   *
   * @param string $channelId [description]
   * @param integer $limit [description]
   * @return mixed [type]             [description]
   */
	public function channel_videos( $channelId = 'UCWOA1ZGywLbqmigxE4Qlvuw', $limit = 12 ){

		// get the channel videos
		try {
			$videos = $this->searchChannelVideos('', $channelId , $limit,'date');
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
	 *
	 * @param  string $videoId [description]
	 * @return string
	 */
	public function video_description( $videoId = '' ){
		$description 	= $this->getVideoInfo($videoId)->snippet->description;
		return $description;
	}

  /**
   * get video info
   * @param string $vid
   * @return string
   * @throws \Exception
   */
	public function video_info( $vid = '' ){
		$info = $this->getVideoInfo( $vid )->snippet;
		return $info;
	}

	/**
	 * Fix error in Parent :: 400 invideoPromotion, remove invideoPromotion from part.
	 *
	 * @param $id
	 * @return \StdClass
	 * @throws \Exception
	 */
	public function getChannelById($id, $optionalParams = false)
	{
			$API_URL = $this->getApi('channels.list');
			$params = array(
					'id' => $id,
					'part' => 'id,snippet,contentDetails,statistics'
			);
			if ($optionalParams) {
					$params = array_merge($params, $optionalParams);
			}
			$apiData = $this->api_get($API_URL, $params);
			return $this->decodeSingle($apiData);
	}

	/**
 	 * channelby_id
 	 *
 	 * @param  [type] $id [description]
 	 * @return false|\StdClass [type]     [description]
 	 * @throws \Exception
 	 */
	public function channelby_id( $id = null ){
		$channel = $this->getChannelById( $id , false );
		return $channel;
	}


  /**
   * get videos from multiple channels
   *
   * @param array $channels [description]
   * @return mixed [type]           [description]
   */
	public function channels_vids( $channels = array() ){
		/**
		 * process channel ids
		 */
		foreach ($channels as $key => $id) {
			$videos[] = $this->channel_videos($id, 2);
		}
		return $videos;
	}

}
