<?php

namespace VideoPublisherlite\Data;

if ( ! defined('ABSPATH') ) exit;

/**
 *	TODO DEPRECATED
 *	this will be removed in future version
 */
class ChannelVideo
{

	function __construct() {
		add_action( 'init', array( $this , 'register_channelvideo_postype') );
	}

	/**
	 * Registers the custom post type to be used for channelvideo.
	 *
	 * used to store published videos along with video information
	 * like ID title and Featured Image
	 *
	 * @since 1.0.0
	 * @link https://github.com/ineagu/wpforms/blob/dev/includes/class-form.php
	 */
	public function register_channelvideo_postype() {

		$args = array(
			'label'               => 'ChannelVids',
			'public'              => false,
			'exclude_from_search' => true,
			'show_ui'             => false,
			'show_in_admin_bar'   => false,
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => false,
			'supports'            => array( 'title' ),
			'capability_type'     => 'video_publisher_pro',
			'map_meta_cap'        => false,
		);

		// Register the post type.
		register_post_type( 'channelvideo', $args );
	}
}
