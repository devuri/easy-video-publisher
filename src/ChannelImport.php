<?php
namespace EasyVideoPublisher;

/**
 *
 */
class ChannelImport
{

	public static function publish( string $channelId = null , array $params = array() ){

		/**
		 * default args
		 */
		$default = array();
		$default['youtube_channel'] = $channelId;
		$default['number_of_posts'] = 2;
		$default['setcategory'] 		= array(1);
		$params = wp_parse_args( $params , $default );

		/**
		 * get the channel to post from
		 */
		$channel 					= trim( $params['youtube_channel'] );
		$number_of_posts 	= intval( $params['number_of_posts'] );
		$channel_videos 	= YouTubeAPI::channel_videos( $channel , $number_of_posts );

		# check if we already have the channel_videos in recent_updates
		$recent_updates 	= get_option('evp_latest_updates');
		$new_videos 			= array_diff( $channel_videos , $recent_updates);
		$next_update 			= array_merge( $new_videos , $recent_updates );

		# if we cant find any new videos
		if ( ! $new_videos ) {
			return;
		}

		# create posts
		foreach ( $new_videos  as $upkey => $id ) {

			$vid = 'https://youtu.be/'.$id;

			/**
			 * check if we posted this already
			 */
			$args['tags'] 					= YouTubeAPI::video_info( $id )->tags;
			$args['thumbnail'] 			= YoutubeVideo::video_thumbnail( $vid );
			$args['embed'] 					= GetBlock::youtube( $vid );
			$args['category'] 			= $params['setcategory'];
			$args['hashtags'] 			= $params['hashtags'];
			$args['create_author']	= false;
			$id = InsertPost::newpost( $vid , $args );
			if ($id) {
				# get the post id
				$get_id[] = $id;
			}
		}

		# save updates
		update_option('evp_latest_updates', $next_update );

		/**
		 * ids for each post
		 * @var array list of post ids
		 */
		return $get_id;
	}

}
