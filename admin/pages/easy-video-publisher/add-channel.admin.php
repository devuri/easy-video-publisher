<?php

	use EasyVideoPublisher\YouTubeAPI;
	use EasyVideoPublisher\SimEditor;

	# make sure we have added channels
	if ( ! YouTubeAPI::has_key() ) :
		$adminkeylink = admin_url('/admin.php?page=evp-api-setup');
		echo $this->form()->user_feedback('Channel Import requires YouTube API Key <strong><a href="'.$adminkeylink.'">Add YouTube API key</a></strong>', 'error');
	endif;

/**
 * Process the data
 *
 */
if ( isset( $_POST['add_new_channel'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	if ( isset( $_POST['channel_id'] ) ) {

		/**
		 * checks to make sure the request is ok
		 * if not show the erro message and exit
		 */
		if ( ! YouTubeAPI::is_request_ok()  ) {
			wp_die($this->form()->user_feedback( YouTubeAPI::response_error() .' !!!', 'error'));
		}

		# Adds new channel
		$channelId 			= trim( $_POST['channel_id'] );
		$channelname 		= YouTubeAPI::channelby_id( $channelId )->snippet->title;
		$newchannel 		= array( $channelId => $channelname );

		# check if we already have the channels
		$channels 				= get_option( 'evp_channels' );
		$is_new						= array_diff( $newchannel , $channels );
		$update_channels	= array_merge( $newchannel , $channels );

		# if we cant find any new videos
		if ( $is_new ) {
			# add the new channel
			update_option('evp_channels', $update_channels );
			echo $this->form()->user_feedback( '<strong>'.$channelname.'</strong> Channel Added !!!');
		} else {
			echo $this->form()->user_feedback('<strong>'.$channelname.'</strong> Channel was already Added !!!', 'error');
		}

	}

endif;

	# section title
	SimEditor::section_title('Add YouTube Channels');

?><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		_e('Add Channel');

		#channel ID
		echo $this->form()->input('Channel ID', ' ');

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
		$evp_channels = get_option( 'evp_channels');
		sort( $evp_channels );
		foreach ( $evp_channels as $chkey => $channel ) {
			$ch = '<li style="padding-left:2em;">';
			$ch .= $channel;
			$ch .= '</li>';
			echo $ch;
		}
		echo '</ul>';
	}

	 ?><p><br>
	 </p>
