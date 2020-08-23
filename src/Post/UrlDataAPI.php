<?php

namespace VideoPublisherPro\Post;


/**
 * get url data
 * uses oEmbed via Core class WP_oEmbed used to get oEmbed data.
 * @link https://oembed.com/
 * @link https://developer.wordpress.org/reference/classes/wp_oembed/
 */
class UrlDataAPI
{

	/**
	 * get_data() using  WP_oEmbed
	 * @param  string $url video url
	 * @return object
	 * @link https://developer.wordpress.org/reference/classes/wp_oembed/
	 */
	public static function get_data( $url = null ){
		$oEmbed = new \WP_oEmbed;
		$data = $oEmbed->get_data($url);
		return $data;
	}

	/**
	 * [provider description]
	 * @param $geturl the url 
	 * @return object
	 */
	public static function provider( $geturl = null ){
		$provider = [];
		$provider['name'] = self::get_data($geturl)->provider_name;
		$provider['url'] 	= self::get_data($geturl)->provider_url;
		$obprovider = (object) $provider;
		return $obprovider;
	}

}
