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
	$api_key['has_key'] = true;
	update_option('evp_youtube_api', $api_key );

endif;

?><h2>
	<?php _e('API Key'); ?>
</h2>
<hr/><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->input('YouTube API Key', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Update API Key', 'primary large', 'add_api_key');
	?></form>
</div><!--frmwrap-->
<br/><hr/>
<p>
	<a href="http://code.google.com/apis/console" rel="nofollow">Obtain API key from Google API Console</a>
	<br>
	<a href="https://developers.google.com/youtube/v3/" rel="nofollow">Youtube Data API v3 Doc</a>
</p>
