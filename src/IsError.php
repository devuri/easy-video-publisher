<?php
namespace VideoPublisherPro;

	use VideoPublisherPro\Admin\VideoPublisherAdmin;

/**
 *
 */
class IsError
{

	/**
	 * Helps to check if all is well
	 * @param  [type] $task [description]
	 * @return [type]       [description]
	 */
	public static function error_check( $task = null ){
		if( is_wp_error( $task ) ) {
			if ( current_user_can('manage_options') ) {
				var_dump($task);
			}
			wp_die( VideoPublisherAdmin::form()->user_feedback( $task->get_error_message() , 'error') );
		}
	}

}
