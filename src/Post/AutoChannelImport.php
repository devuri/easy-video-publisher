<?php
namespace VideoPublisherlite\Post;

use VideoPublisherlite\Database\VideosTable;
use VideoPublisherlite\YouTube\YouTubeData;
use VideoPublisherlite\YouTube\YoutubeVideoInfo;

class AutoChannelImport
{

    /**
     * Publish
     *
     * @param string|null $channel_id .
     * @param array       $params .
     */
    public static function publish( $channel_id = null, array $params = array() ) {

		/**
		 * Checks to make sure the request is ok
		 * if not show the error message and exit
		 */
		try {
			YouTubeData::api()->getVideoInfo( 'YXQpgAAeLM4' );
		} catch ( \Exception $e ) {
			// TODO create a log message $e->getMessage() and return.
			// TODO log message api key failed, $e->getMessage().
			return 0;
		}

		// Default args.
		$default = array();
		$default['post_type']       = 'post';
		$default['create_author']   = false;
		$default['youtube_channel'] = $channel_id;
		$default['number_of_posts'] = 2;
		$default['setcategory']     = array( 1 );
		$default['post_status']     = 'draft';
		$params = wp_parse_args( $params, $default );

		// Get the channel to post from.
		$channel         = trim( $params['youtube_channel'] );
		$number_of_posts = (int) $params['number_of_posts'];
		$channel_videos  = YouTubeData::api()->channel_videos( $channel, $number_of_posts );

		// create posts.
		foreach ( $new_videos  as $upkey => $id ) {

			/**
			 * Skip over if video is already posted
			 * and continue to the next item.
			 */
			if ( VideosTable::video_exists( $id ) ) continue;

			// convert id to full youtube url.
			$vid = 'https://youtu.be/' . $id;

			// Set up some $args.
			$args['tags']          = YouTubeData::api()->video_info( $id )->tags;
			$args['thumbnail']     = YoutubeVideoInfo::video_thumbnail( $vid );
			$args['embed']         = GetBlock::youtube( $vid );
			$args['post_type']     = $params['post_type'];
			$args['category']      = $params['setcategory'];
			$args['post_status']   = $params['post_status'];
			$args['hashtags']      = $params['hashtags'];
			$args['create_author'] = $params['create_author'];

			$post_id = InsertPost::newpost( $vid, $args );

			if ( $post_id ) {

				// Add to "evp_videos" table.
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

				// Get the $post_id .
				$posts[] = $post_id;
			}
		}

		if ( empty( $posts ) ) {
			return 0;
		}

		// IDs for each post.
		return $posts;
	}

}
