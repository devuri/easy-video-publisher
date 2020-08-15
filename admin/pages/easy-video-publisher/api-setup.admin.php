<?php

	use EasyVideoPublisher\YouTubeAPI;

/**
 * Add API keys
 *
 */
if ( isset( $_POST['add_api_key'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * Adds new API Keys
	 */
	$api_key			= get_option( 'evp_youtube_api' );
	$new_key 			= array(trim( $_POST['youtube_api_key'] ));
	$update_keys 	= array_merge( $api_key , $new_key );

	# update the key
	update_option('evp_youtube_api', $update_keys );

endif;

/**
 * Delete the API keys
 *
 */
if ( isset( $_POST['delete_api_keys'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	$delete_keys 	= array();
	# update the key
	update_option('evp_youtube_api', $delete_keys );

endif;

?><h2>
	<?php _e('API Key'); ?>
</h2>
<hr/><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');

		# add key input
		echo $this->form()->input('YouTube API Key', ' ');

		echo $this->form()->table('close');

		$this->form()->nonce();
		echo '<br>';
		echo $this->form()->submit_button('Add API Key', 'primary large', 'add_api_key');
		echo '<br/>';
		echo '<br/><hr/>';

		echo YouTubeAPI::keys();
		echo '<input name="delete_api_keys" id="delete_api_keys" type="submit" class="button" value="Delete API Keys ">';
		echo '<br/>';
	?></form>
</div><!--frmwrap-->
<br/><hr/>
<p>
	<a href="http://code.google.com/apis/console" rel="nofollow">Obtain API key from Google API Console</a>
	<br>
	<a href="https://developers.google.com/youtube/v3/" rel="nofollow">Youtube Data API v3 Doc</a>
</p>
