<?php
namespace VideoPublisherPro;

/**
 *
 */
class UserFeedback
{

	/**
	 * message()
	 *
	 * give the user some feedback
	 *
	 * @param  string $class the css class (success | info | warning | error)
	 * @param  string $message output message
	 * @return string
	 * @link https://developer.wordpress.org/reference/hooks/admin_notices/
	 * @link https://developer.wordpress.org/reference/functions/__/
	 */
	public static function message($message = 'Options updated', $class = 'success'){

		$user_message  = '<div style="font-size: small; text-transform: capitalize;" id="user-feedback" class="notice notice-'.$class.' is-dismissible">';
		$user_message .= '<p>';
		$user_message .= __($message);
		$user_message .= '</p>';
		$user_message .= '</div>';
		return $user_message;
	}
}
