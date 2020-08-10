<?php
namespace EasyVideoPublisher;

/**
 *
 */
class Latest_Updates
{

	/**
	 * get the most recent updates array
	 * @return [type] [description]
	 */
	private static function current_updates(){
		return get_option('evp_latest_updates');
	}

	public static function display(){
		foreach ( self::current_updates() as $vkey => $videoId ) {

			$vid_data = YoutubeVideoPost::video_data('https://www.youtube.com/watch?v='.$videoId);
			$title 			= $vid_data->title;
			$author 		= $vid_data->author_name;
			$thumbnail 	= $vid_data->thumbnail_url;

			echo $title.'<hr/>';
		}
	}

}