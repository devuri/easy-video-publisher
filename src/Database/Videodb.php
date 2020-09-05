<?php

namespace VideoPublisherPro\Database;


/**
 *
 */
final class Videodb extends GetData
{

  /**
   * check the database if the video id already exists.
   *
   * @param null $id
   * @return bool
   */
	public static function video_exists( $id = null ){
		$videos = self::get_result('video_id');
		$vid_exists = array_key_exists( $id , $videos );
		return $vid_exists;
	}

}
