<?php

/**
 * Process the data
 *
 */
if ( isset( $_POST['add_new_channel'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * Adds new channel
	 */
	$channelId 			= trim( $_POST['channel_id'] );
	$channelname 		= trim( $_POST['channel_name'] );

	$channels 			= array();
	$channels 			= get_option( 'evp_channels' );
	$channels[$channelId] = $channelname;

	# add the new channel
	update_option('evp_channels', $channels );

endif;

?><h2>
	<?php _e('Video Publisher Settings'); ?>
</h2><hr/>
<div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->input('Channel ID', ' ');
		echo $this->form()->input('Channel Name', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Add New Channel', 'primary large', 'add_new_channel');
	?></form>
</div><!--frmwrap-->
<br/><hr/>
<p>
	<a href="http://code.google.com/apis/console" rel="nofollow">Obtain API key from Google API Console</a>
	<br>
	<a href="https://developers.google.com/youtube/v3/" rel="nofollow">Youtube Data API v3 Doc</a>
</p>
