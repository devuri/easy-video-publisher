<?php
namespace VideoPublisherlite;

class MaxIndex
{

	/**
	 * Limits the number of API KEYS, can override in wp-config
	 * using the "EVP_APIKEYS_LIMIT = 10"
	 *
	 * @return int
	 */
	private static function api_keys_limit() {
		if ( defined( 'EVP_APIKEYS_LIMIT' ) ) {
			return (int) EVP_APIKEYS_LIMIT;
		} else {
			return 2;
		}
	}

	/**
	 * Limits the number of channels, can override in wp-config
	 * using the "EVP_CHANNEL_LIMIT = 20"
	 *
	 * @return int
	 */
	private static function channels_limit() {
	    if ( defined( 'EVP_CHANNEL_LIMIT' ) ) {
	    	return (int) EVP_CHANNEL_LIMIT;
	    } else {
	    	return 10;
	    }
	}

  	/**
  	 * Limit api keys
  	 *
  	 * @param string $option the option.
  	 * @return bool
  	 */
	public static function api_key( $option ) {
		$count = count( $option );
		if ( $count >= self::api_keys_limit() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Limit channels.
	 *
	 * @param string $option the option.
	 * @return bool
	 */
	public static function channels( $option ) {
		$count = count( $option );
		if ( $count >= self::channels_limit() ) {
			return true;
		} else {
			return false;
		}
	}

}
