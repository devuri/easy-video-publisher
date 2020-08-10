<?php

/**
 * Process the data
 *
 */
if ( isset( $_POST['add_api_key'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * Adds new channel
	 */
	$api_key 						= array();
	$api_key['apikey'] 	= trim( $_POST['youtube_api_key'] );
	update_option('evp_youtube_api', $api_key );

endif;

?><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->input('YouTube API Key', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<hr/>';
		echo $this->form()->submit_button('Update API Key', 'primary large', 'add_api_key');
	?></form>
	<p>
		<a href="http://code.google.com/apis/console" rel="nofollow">Obtain API key from Google API Console</a>
		<br>
		<a href="https://developers.google.com/youtube/v3/" rel="nofollow">Youtube Data API v3 Doc</a>
	</p>
</div><!--frmwrap-->
