<?php
namespace VideoPublisherlite\Post;

use VideoPublisherlite\YouTube\YouTubeData;
use VideoPublisherlite\YouTube\YoutubeVideoInfo;
use VideoPublisherlite\Database\VideosTable;
use VideoPublisherlite\UserFeedback;
use WP_Queue\Job;

class ChannelImport extends Job
{

	/**
	 * Get the youtube channel id
	 *
	 * @var string .
	 */
	public $the_channel;


	/**
	 * Data we might need .
	 *
	 * @var array .
	 */
	public $params;

	/**
	 * Publisher
	 *
	 * @param string $the_channel .
	 * @param array  $params .
	 */
	public function __construct( $the_channel = null, $params = array() ) {
		$this->the_channel = $the_channel;
		$this->params      = $params;
	}


	/**
	 * Handle job.
	 */
	public function handle() {
		$this->publish();
		// TODO notify user via email.
	}

	/**
	 * Creates each Post
	 *
	 * @param array $channel_videos .
	 * @return void
	 */
	public function create_post( $channel_videos = array() ) {

		// create posts .
		foreach ( $channel_videos  as $upkey => $id ) {

			/**
			 * Skip over if video is already posted
			 * and continue to the next item.
			 */
			if ( VideosTable::video_exists( $id ) ) continue;

			// convert id to full youtube url.
			$vid = 'https://youtu.be/' . $id;

			// check for tags to avoid "Undefined property".
			if ( property_exists( YouTubeData::api()->video_info( $id ), 'tags' ) ) {
				$args['tags'] = YouTubeData::api()->video_info( $id )->tags;
			}

			/**
			 * Schedule random time in the future
			 *
			 * Will add a scheduled post between a range from one hour to the $shecdule time
			 * based on post_schedule param
			 */
			if ( $this->params['post_schedule'] ) {

				$schedule = $this->params['post_schedule'];

				$hrs       = wp_rand( 1, $schedule );
				$post_date = time() + $hrs * 60 * 60;

				// set the schedule.
				$scheduled = date_i18n( 'Y-m-d H:i:s', $post_date );
				$args['post_date'] = $scheduled;
			}

			/**
			 * Set up some $args .
			 */
			$args['thumbnail']     = YoutubeVideoInfo::video_thumbnail( $vid );
			$args['embed']         = GetBlock::youtube( $vid );
			$args['post_type']     = $this->params['post_type'];
			$args['category']      = $this->params['setcategory'];
			$args['post_status']   = $this->params['post_status'];
			$args['hashtags']      = $this->params['hashtags'];
			$args['create_author'] = $this->params['create_author'];

			$post_id = InsertPost::newpost( $vid, $args );
			if ( $post_id ) {

				// add to "evp_videos" table.
				( new VideosTable() )->insert_data(
					array(
						'post_id'       => $post_id,
						'user_id'       => get_post_field( 'post_author', $post_id ),
						'campaign_id'   => 0,
						'video_id'      => $id,
						'channel'       => $channel,
						'channel_title' => UrlDataAPI::get_data( $vid )->author_name,
					)
				);
			}
		}
	}

	/**
	 * Publich the video
	 */
	public function publish() {

		// checks to make sure the request is ok.
		try {
			YouTubeData::api()->getVideoInfo( 'YXQpgAAeLM4' );
		} catch ( \Exception $e ) {
			// TODO create a log message $e->getMessage() and return.
			wp_die( UserFeedback::message( 'Request failed: ' . $e->getMessage(), 'error' ) ); // @codingStandardsIgnoreLine.
		}

		/**
		 * Default args
		 */
		$default = array();
		$default['post_type']       = 'post';
		$default['create_author']   = false;
		$default['youtube_channel'] = $this->the_channel;
		$default['number_of_posts'] = 2;
		$default['setcategory']     = array( 1 );
		$default['post_status']     = 'draft';
		$this->params = wp_parse_args( $this->params, $default );

		/**
		 * Get the channel to post from
		 */
		$channel          = trim( $this->params['youtube_channel'] );
		$number_of_posts  = intval( $this->params['number_of_posts'] );
		$channel_videos   = YouTubeData::api()->channel_videos( $channel, $number_of_posts );

		// no videos to import.
		if ( ! $channel_videos ) {
			// TODO log some info here.
			return 0;
		}

		// create posts.
		$posts = $this->create_post( $channel_videos );

		/**
		 * Ids for each post
		 *
		 * @var array list of post ids
		 */
		return $posts;
	}

}
