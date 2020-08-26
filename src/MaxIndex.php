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
	public static function max_limit(){
		$maxlimit = 2;
		return $maxlimit;
	}

	/**
	 * limit check
	 * @return [type] [description]
	 */
	public static function check( $option ){
		$limit = self::max_limit();

		$count = count( $option );
		if ( $count >= $limit ) {
			return true;
		} else {
			return false;
		}
	}

}
