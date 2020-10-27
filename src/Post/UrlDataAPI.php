<?php

namespace VideoPublisherlite\Post;

/**
 * Get url data
 * uses oEmbed via Core class WP_oEmbed used to get oEmbed data.
 *
 * @link https://oembed.com/
 * @link https://developer.wordpress.org/reference/classes/wp_oembed/
 */
class UrlDataAPI
{

	/**
	 * Get_data() using  WP_oEmbed
	 *
	 * @param  string $url video url.
	 * @return object
	 * @link https://developer.wordpress.org/reference/classes/wp_oembed/
	 */
	public static function get_data( $url = null ) {

		if ( is_null( $url ) ) {
			return array();
		}

		$o_embed = new \WP_oEmbed();
		$data = $o_embed->get_data( $url );
		return $data;
	}

	/**
	 * Provider description
	 *
	 * @param  string $geturl the url.
	 * @return object
	 */
	public static function provider( $geturl = null ) {
		$provider = array();
		$provider['name'] = self::get_data( $geturl )->provider_name;
		$provider['url']  = self::get_data( $geturl )->provider_url;
		$obprovider       = (object) $provider;
		return $obprovider;
	}

}
