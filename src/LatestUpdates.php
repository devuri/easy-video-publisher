<?php
namespace VideoPublisherPro;

/**
 *
 */
class LatestUpdates
{

	/**
	 * get the most recent updates array
	 * @return [type] [description]
	 */
	private static function current_updates(){
		return get_option('evp_latest_updates');
	}

	/**
	 * recent_count
	 * @return [type] [description]
	 */
	public static function count_updates(){
		if ( get_option('evp_latest_updates') ) {
			return count( get_option('evp_latest_updates') );
		}
		$update_count = 0;
		return $update_count;
	}

}
