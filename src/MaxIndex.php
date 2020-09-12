<?php
namespace VideoPublisherPro;

/**
 *
 */
class MaxIndex
{

  /**
   * [max_limit description]
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
   * [max_limit description]
   * @param $option
   * @return bool [description]
   */
	public static function channels( $option ){
		$indexlimit = 49;

		$count = count( $option );
		if ( $count >= $indexlimit ) {
			return true;
		} else {
			return false;
		}
	}


}
