<?php

namespace VideoPublisherlite;

use VideoPublisherlite\Admin\VideoPublisherAdmin;

class Plugin
{

	/**
	 * Start here
	 */
	public function __construct() {
		$this->init();
	}

	/**
  	 * Init
  	 *
  	 * @return void
  	 */
	public function init() {
		add_action( 'plugins_loaded', array( $this, 'admin_pages' ) );
		add_action( 'plugins_loaded', array( $this, 'queue_cron' ) );
	}

  	/**
  	 * Load The Admin Pages
  	 *
  	 * @return void
  	 */
	public function admin_pages() {
		if ( is_admin() ) {
			VideoPublisherAdmin::init();
		}
	}

	/**
	 * Setup the WP_Queue\Job
	 *
	 * @return void
	 * @link https://github.com/deliciousbrains/wp-queue
	 */
	public function queue_cron() {
		wp_queue()->cron();
	}

}
