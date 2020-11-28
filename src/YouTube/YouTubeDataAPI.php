<?php

namespace VideoPublisherlite\YouTube;

use Madcoda\Youtube\Youtube;
use VideoPublisherlite\UserFeedback;

class YouTubeDataAPI extends Youtube
{

	/**
	 * Class instance.
	 *
	 * @var $instance
	 */
	private static $instance = null;

	/**
	 * New instance.
	 *
	 * @return object ..
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new YouTubeDataAPI();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		try {
			$this->init();
		} catch ( \Exception $error ) {
			return $error;
		}
	}

	/**
	 * Initiate the API
	 * $youtube = new Youtube(array('key' => 'KEY HERE')).
	 *
	 * @param array $params ..
	 * @throws \Exception ..
	 * @link https://github.com/madcoda/php-youtube-api
	 */
	public function init() {
		parent::__construct(
			array( 'key' => $this->apikey() )
		);
	}

	/**
	 * Get API key
	 * uses a random key each time if mutiple keys are available.
	 * if no keys are available returns false.
	 *
	 * @return mixed The API Key.
	 */
	private function apikey() {

		$apikey = $this->get_keys();
		if ( $apikey ) {
			shuffle( $apikey );
		}

		// TODO only return a valid key.
		if ( isset( $apikey[0] ) ) {
			return $apikey[0];
		} else {
			return false;
		}

	}

	/**
	 * Get the keys.
	 *
	 * @return array|false ..
	 */
	public function get_keys() {
		if ( empty( get_option( 'evp_youtube_api', array() ) ) ) {
			return false;
		} else {
			$apikey = (array) get_option( 'evp_youtube_api' );
		}
		// get the keys.
		$apikey = array_keys( $apikey );
		return $apikey;
	}

	/**
	 * API key check
	 * Check if an API key has been set
	 *
	 * @return bool
	 */
	public function has_key() {

		$apikey = get_option( 'evp_youtube_api', false );

			if ( false === $apikey || empty( $apikey ) ) :
				return false;
			endif;

		return true;
	}

	/**
	 * Use to verify specific api key
	 *
	 * @param null $apikey ..
	 * @return bool ..
	 * @throws \Exception ..
	 */
	public function is_key_valid( $apikey = null ) {
		$verify_api_key = new Youtube(
			array( 'key' => $apikey )
		);
		try {
			$verify_api_key->getVideoInfo( 'YXQpgAAeLM4' );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}

	/**
	 * Lets make sure all is well
	 *
	 * @return bool
	 */
	public function is_request_ok() {
		try {
				$this->getVideoInfo( 'YXQpgAAeLM4' );
		} catch ( \Exception $e ) {
			return false;
		}
		return true;
	}

	/**
	 * Exit whatever is goin on here
	 *
	 * @return void
	 */
	public function response_error() {
		try {
				$this->getVideoInfo( 'YXQpgAAeLM4' );
		} catch ( \Exception $e ) {
			echo UserFeedback::message( $e->getMessage() , 'error' ); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Get the latest videos by a channel.
	 *
	 * @param string  $channelId ..
	 * @param integer $limit ..
	 * @return mixed
	 */
	public function channel_videos( $channelId = 'UCWOA1ZGywLbqmigxE4Qlvuw', $limit = 12 ) {

		try {
			$videos = $this->searchChannelVideos( '', $channelId, $limit, 'date' );
		} catch ( \Exception $e ) {
			return 0;
		}

		// make sure we get the data.
		if ( ! $videos ) {
			return 0;
		}

		/**
		 * Return array of video data.
		 */
		foreach ( $videos as $vkey => $vid ) {
			$vidinfo[ $vkey ] = $vid->id->videoId;
		}
		return $vidinfo;
	}

	/**
	 * Get video description
	 *
	 * @param  string $videoId the video ID.
	 * @return string
	 */
	public function video_description( $videoId = '' ) {
		$description = $this->getVideoInfo( $videoId )->snippet->description;
		return $description;
	}

  	/**
  	 * Get video info
  	 *
  	 * @param  string $vid ..
  	 * @return string $info ..
  	 * @throws \Exception ..
  	 */
	public function video_info( $vid = '' ) {
		$info = $this->getVideoInfo( $vid )->snippet;
		return $info;
	}

	/**
	 * Fix error in Parent :: 400 invideoPromotion, remove invideoPromotion from part.
	 *
	 * @param string $id ..
	 * @param string $optional_params ..
	 * @return \StdClass
	 * @throws \Exception ..
	 */
	public function getChannelById( $id, $optional_params = false ) {
			$api_url = $this->getApi( 'channels.list' );
			$params = array(
				'id'   => $id,
				'part' => 'id,snippet,contentDetails,statistics',
			);
			if ( $optional_params ) {
					$params = array_merge( $params, $optional_params );
			}
			$api_data = $this->api_get( $api_url, $params );
			return $this->decodeSingle( $api_data );
	}

	/**
 	 * Channelby_id
 	 *
 	 * @param  string $id channel id.
 	 * @return false|\StdClass channel.
 	 * @throws \Exception Exception.
 	 */
	public function channelby_id( $id = null ) {
		$channel = $this->getChannelById( $id, false );
		return $channel;
	}

  	/**
  	 * Get videos from multiple channels
  	 *
  	 * @param array $channels the channels.
  	 * @return mixed
  	 */
	public function channels_vids( $channels = array() ) {

		foreach ( $channels as $key => $id ) {
			$videos[] = $this->channel_videos( $id, 2 );
		}
		return $videos;
	}

}
