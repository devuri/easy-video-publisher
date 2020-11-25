<?php

namespace VideoPublisherlite\YouTube;

/**
 * Automatic Update for Video Views.
 *
 * Run by calling ( new UpdateViews() )->update_view_count();
 */
class UpdateViews
{
	/**
	 * List of Video IDs
	 *
	 * @var array
	 */
	private $videos;

	/**
	 * Limit for $videos
	 *
	 * @var array
	 */
	private $limit;

	/**
	 * Create the  $videos Update Job
	 *
	 * @param array $videos the $campaign ID for import.
	 * @param array $limit the $limit for videos.
	 */
	public function __construct( $videos = array(), $limit = 10 ) {
		/**
		 * Get import data
		 */
		$this->videos = $videos;
		$this->limit = (int) $limit;

	}

	/**
	 * Run the Update.
	 *
	 * @return void
	 */
	public function update_view_count() {

		// create update job for each video.
		foreach ( $this->videos as $vidkey => $video ) {

			// 24 hour delay for each video.
			$schedule = 24;

			// check table to see when was the last update.
			$latest_update = $this->latest_update( $video );

			// if the latest update is 24 hours or less skip.
			if ( $latest_update >= 24 ) continue;

			$views = new UpdateViewCount( $video, $schedule ); // @codingStandardsIgnoreLine

			// update views and set 'updated_at' time now().
			$views->update_views();
		}

	}

}
