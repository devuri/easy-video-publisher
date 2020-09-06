<?php
namespace VideoPublisherPro\Post;

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\YouTube\YoutubeVideoInfo;
	use VideoPublisherPro\Database\VideosTable;
	use VideoPublisherPro\UserFeedback;

/**
 *
 */
class ChannelImport
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
			wp_die( UserFeedback::message( 'Request failed: '. $e->getMessage(), 'error') );
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

		// create posts
		foreach ( $channel_videos  as $upkey => $id ) {

			/**
			 * skip over if video is already posted
			 * and continue to the next item.
			 */
			if( VideosTable::video_exists( $id ) ) continue;

			// convert id to full youtube url
			$vid = 'https://youtu.be/'.$id;

			// check for tags to avoid "Undefined property"
			if ( property_exists( YouTubeDataAPI::video_info( $id ), 'tags') ) {
				$args['tags'] = YouTubeDataAPI::video_info( $id )->tags;
			}

			/**
			 * schedule random time in the future
			 *
			 * will add a scheduled post between a range from one hour to the $shecdule time
			 * based on post_schedule param
			 * @var [type]
			 */
			if ( $params['post_schedule'] ) {
				$schedule = $params['post_schedule'];
				$hrs = mt_rand(1, $schedule);
				$post_date = time() + $hrs*60*60;
				$post_time = date_i18n( 'Y-m-d H:i:s', $post_date );

				// set the schedule
				$args['post_date']	= $post_time;
			}

			/**
			 * set up some $args
			 */
			$args['thumbnail'] 			= YoutubeVideoInfo::video_thumbnail( $vid );
			$args['embed'] 					= GetBlock::youtube( $vid );
			$args['post_type'] 			= $params['post_type'];
			$args['category'] 			= $params['setcategory'];
			$args['post_status'] 		= $params['post_status'];
			$args['hashtags'] 			= $params['hashtags'];
			$args['create_author']	= $params['create_author'];

			$post_id = InsertPost::newpost( $vid , $args );
			if ($post_id) {

				// add to "evp_videos" table
				(new VideosTable())->insert_data(
					array(
						'post_id' 		=> $post_id,
						'user_id' 		=> get_post_field( 'post_author', $post_id ),
						'campaign_id' => 0,
						'video_id' 		=> $id,
						'channel' 		=> $channel,
						'channel_title' => UrlDataAPI::get_data( $vid )->author_name,
					)
				);

				# get the post id
				$posts[] = $post_id;

			}
		}

		/**
		 * empty
		 */
		if ( empty($posts) ) {
			return 0;
		}

		/**
		 * ids for each post
		 * @var array list of post ids
		 */
		return $posts;
	}

}
