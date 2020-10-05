<?php
namespace VideoPublisherlite;

/**
 *
 */
class MaxIndex
{

  	/**
  	 * limit api keys
  	 *
  	 * @param $option
  	 * @return bool [description]
  	 */
	public static function api_key( $option ){
		$indexlimit = 2;

		$count = count( $option );
		if ( $count >= $indexlimit ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * limit channels
	 *
	 * @param $option
	 * @return bool
	 */
	public static function channels( $option ){
		$indexlimit = 10;

		$count = count( $option );
		if ( $count >= $indexlimit ) {
			return true;
		} else {
			return false;
		}
	}


}
