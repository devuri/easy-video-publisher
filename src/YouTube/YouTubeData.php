<?php

namespace VideoPublisherlite\YouTube;

class YouTubeData extends YouTubeDataAPI
{

	public static $instance;

	/**
	 * Returns the YouTubeDataAPI instance.
	 *
	 * @return VideoPublisherlite\YouTube\YouTubeDataAPI
   */
	public static function api(){
		self::$instance = new self();
		return self::$instance;
	}

}
