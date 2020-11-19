<?php

namespace VideoPublisherlite;

use VideoPublisherlite\Traits\Singleton;
use VideoPublisherlite\Admin\VideoPublisherAdmin;

class Plugin
{

	use Singleton;

	/**
	 * Start here
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'admin_pages' ) );
		add_action( 'plugins_loaded', array( $this, 'queue' ) );
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
	public function queue() {
		wp_queue()->cron();
	}

}
