<?php

namespace VideoPublisherlite\YouTube;

/**
 * Youtube Automatic Import
 *
 * Run by calling ( new AutoImport() )->do_import();
 */
class AutoImport
{
	/**
	 * Campaign ID
	 *
	 * @var array
	 */
	private $campaign;

	/**
	 * Import Data
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Create the Auto Publisher Job
	 *
	 * @param array $campaign the $campaign ID for import.
	 * @param array $data the data for import.
	 */
	public function __construct( $campaign = '', $data = array() ) {
		/**
		 * Get import data
		 */
		$this->data = $data;
		$this->campaign = (int) $campaign;

	}


	/**
	 * Setup the import
	 */
	private function import_args() {

		/**
		 * Set args to override $default
		 *
		 * @var
		 */
		$args = array();

		$args['campaign']        = absint( $this->campaign );
		$args['type']            = $this->data['channel'];
		$args['youtube_channel'] = sanitize_text_field( trim( $this->data['youtube_channel'] ) );
		$args['post_type']       = sanitize_text_field( trim( $this->data['set_post_type'] ) );
		$args['create_author']   = absint( $this->data['set_author'] );
		$args['number_of_posts'] = absint( $this->data['number_of_posts'] );
		$args['setcategory']     = absint( $this->data['select_category'] );
		$args['post_status']     = sanitize_text_field( trim( $this->data['post_status'] ) );
		$args['post_schedule']   = absint( $this->data['post_schedule'] );
		$args['set_description'] = absint( $this->data['import_with_video_description'] );
		$args['author']          = absint( $this->data['user_id'] );
		$args['hashtags']        = array( get_term( $args['setcategory'], 'category' )->name );

		return $args;

	}

	/**
	 * Run the Import.
	 *
	 * @return void
	 */
	public function do_import() {
		/**
		 * Creates the posts
		 */
		$import = new ImportVideo( $this->import_args() ); // @codingStandardsIgnoreLine
		$import->add_video();
	}

}
