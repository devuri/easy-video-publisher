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
	$channels 			= get_option('evp_channels');
	$channels[$channelId] = $channelname;

	# add the new channel
	update_option('evp_channels', $channels );

endif;

?><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->input('Channel ID', ' ');
		echo $this->form()->input('Channel Name', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<hr/>';
		echo $this->form()->submit_button('Add New Channel', 'primary large', 'add_new_channel');
	?></form>
</div><!--frmwrap-->
