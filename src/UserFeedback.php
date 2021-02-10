<?php

namespace VideoPublisherlite;

class UserFeedback
{

	/**
	 * Message()
	 *
	 * Give the user some feedback.
	 *
	 * @param  string $message the css class (success | info | warning | error).
	 * @param  string $class output message.
	 * @return string
	 * @link https://developer.wordpress.org/reference/hooks/admin_notices/
	 * @link https://developer.wordpress.org/reference/functions/__/
	 */
	public static function message( $message = 'Options updated', $class = 'success' ) : string {

		$user_message  = '<div style="font-size: small; text-transform: capitalize;" id="user-feedback" class="notice notice-' . $class . ' is-dismissible">';
		$user_message .= '<p>';
		$user_message .= esc_attr( $message );
		$user_message .= '</p>';
		$user_message .= '</div>';
		return $user_message;
	}
}
