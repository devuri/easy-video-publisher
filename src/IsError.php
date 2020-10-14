<?php

namespace VideoPublisherlite;

class IsError
{

	/**
	 * Helps to check if all is well
	 *
	 * @param  string $task the task.
	 * @return void
	 */
	public static function error_check( $task = null ) {
		if ( is_wp_error( $task ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				var_dump( $task ); // @codingStandardsIgnoreLine
			}
			wp_die( UserFeedback::message( $task->get_error_message(), 'error' ) ); // @codingStandardsIgnoreLine
		}
	}

}
