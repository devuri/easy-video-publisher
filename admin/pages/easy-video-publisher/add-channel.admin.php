<?php

/**
 * Process the data
 *
 */
if ( isset( $_POST['add_new_channel'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	if ( isset( $_POST['channel_id'] ) && isset( $_POST['channel_name'] ) ) {
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
	}


endif;

?><h2>
	<?php _e('Video Publisher Settings'); ?>
</h2><hr/>
<div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		_e('Add Channel');
		echo $this->form()->input('Channel ID', ' ');
		echo $this->form()->input('Channel Name', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Add New Channel', 'primary large', 'add_new_channel');
	?></form>
</div><!--frmwrap-->
<br/><hr/><?php
	/**
	 * Channels List
	 * @var [type]
	 */
	if ( get_option('evp_channels') ) {
		_e('Channels');
		echo '<ul>';
		foreach ( get_option( 'evp_channels' ) as $chkey => $channel ) {
			$ch = '<li style="padding-left:2em;">';
			$ch .= $channel;
			$ch .= '</li>';
			echo $ch;
		}
		echo '</ul>';
	}

	 ?><p><br>
		-
	</p>
