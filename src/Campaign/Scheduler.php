<?php

/**
 *
 * @link https://actionscheduler.org/usage/#scheduling-an-action
 * @var [type]
 */

require_once( plugin_dir_path( __FILE__ ) . '/libraries/action-scheduler/action-scheduler.php' );

/**
 * Schedule an action with the hook 'eg_midnight_log' to run at midnight each day
 * so that our callback is run then.
 */
function eg_schedule_midnight_log() {
	if ( false === as_next_scheduled_action( 'eg_midnight_log' ) ) {
		as_schedule_recurring_action( strtotime( 'tomorrow' ), DAY_IN_SECONDS, 'eg_midnight_log' );
	}
}
add_action( 'init', 'eg_schedule_midnight_log' );

/**
 * A callback to run when the 'eg_midnight_log' scheduled action is run.
 */
function eg_log_action_data() {
	error_log( 'It is just after midnight on ' . date( 'Y-m-d' ) );
}
add_action( 'eg_midnight_log', 'eg_log_action_data' );
