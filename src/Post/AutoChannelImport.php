<?php
namespace VideoPublisherPro\Post;

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\YouTube\YoutubeVideoInfo;

/**
 *
 */
class AutoChannelImport
{

	public static function publish( string $channelId = null , array $params = array() ){

		/**
		 * checks to make sure the request is ok
		 * if not show the error message and exit
		 */
		try {
			YouTubeDataAPI::youtube()->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			// TODO create a log message $e->getMessage() and return
			// log message api key failed, $e->getMessage()
			return;
		}

		/**
		 * default args
		 */
		$default = array();
		$default['post_type']				= 'post';
		$default['create_author']		= false;
		$default['youtube_channel'] = $channelId;
		$default['number_of_posts'] = 2;
		$default['setcategory'] 		= array(1);
		$default['post_status'] 		= 'draft';
		$params = wp_parse_args( $params , $default );

		/**
		 * get the channel to post from
		 */
		$channel 					= trim( $params['youtube_channel'] );
		$number_of_posts 	= intval( $params['number_of_posts'] );
		$channel_videos 	= YouTubeDataAPI::channel_videos( $channel , $number_of_posts );

		# check if we already have the channel_videos in recent_updates
		$recent_updates 	= (array) get_option('evp_latest_updates');
		$new_videos 			= array_diff( $channel_videos , $recent_updates);
		$next_update 			= array_merge( $new_videos , $recent_updates );

		# if we cant find any new videos
		if ( ! $new_videos ) {
			return;
		}

		// create posts
		foreach ( $new_videos  as $upkey => $id ) {

			// convert id to full youtube url
			$vid = 'https://youtu.be/'.$id;

			/**
			 * set up some $args
			 */
			$args['tags'] 					= YouTubeDataAPI::video_info( $id )->tags;
			$args['thumbnail'] 			= YoutubeVideoInfo::video_thumbnail( $vid );
			$args['embed'] 					= GetBlock::youtube( $vid );
			$args['post_type'] 			= $params['post_type'];
			$args['category'] 			= $params['setcategory'];
			$args['post_status'] 		= $params['post_status'];
			$args['hashtags'] 			= $params['hashtags'];
			$args['create_author']	= $params['create_author'];

			$post_id = InsertPost::newpost( $vid , $args );
			if ($post_id) {
				# get the $post_id
				$ids[] = $post_id;
			}
		}

		# save updates
		update_option('evp_latest_updates', $next_update );

		/**
		 * ids for each post
		 * @var array list of post ids
		 */
		return $ids;
	}

}
