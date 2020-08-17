<?php

namespace EasyVideoPublisher\Post;


/**
 * get url data
 * uses oEmbed
 * @link https://oembed.com/
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

	public static function provider( $geturl = null ){
		$provider = [];
		$provider['name'] = self::get_data($geturl)->provider_name;
		$provider['url'] 	= self::get_data($geturl)->provider_url;
		$obprovider = (object) $provider;
		return $obprovider;
	}

}
