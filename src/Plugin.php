<?php

namespace VideoPublisherlite;

use VideoPublisherlite\Traits\Singleton;
use VideoPublisherlite\Admin\VideoPublisherAdmin;
use VideoPublisherlite\Database\VideosTable;

class Plugin
{

	use Singleton;

	/**
	 * Retrying Failed Jobs
	 *
	 * Specify the number of times a job should be attempted
	 * before being marked as a failure.
	 *
	 * @var int
	 */
	protected $retry;

	/**
	 * Start here
	 */
	public function __construct() {

		$this->retry = 3;

		add_action( 'plugins_loaded', array( $this, 'admin_pages' ) );
		add_action( 'plugins_loaded', array( $this, 'queue' ) );

	}

  	/**
  	 * Load The Admin Pages
  	 *
  	 * @return void
  	 */
	public function admin_pages() : void {
		if ( is_admin() ) {
			VideoPublisherAdmin::init();
		}
	}

	/**
	 * Setup the WP_Queue\Job
	 *
	 * Setup queue worker. start cron worker, which piggy backs onto WP cron
	 * and specify the number of times a job should be attempted before
	 * being marked as a failure.
	 *
	 * @return void
	 * @link https://github.com/deliciousbrains/wp-queue
	 */
	public function queue() : void {
		wp_queue()->cron( $this->retry );
	}

}
