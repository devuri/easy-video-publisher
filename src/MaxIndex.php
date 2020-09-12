<?php
namespace VideoPublisherPro;

/**
 *
 */
class MaxIndex
{

	/**
	 * [max_limit description]
	 * @return [type] [description]
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
	 * @return [type] [description]
	 */
	public static function channels( $option ){
		$indexlimit = 24;

		$count = count( $option );
		if ( $count >= $indexlimit ) {
			return true;
		} else {
			return false;
		}
	}


}
