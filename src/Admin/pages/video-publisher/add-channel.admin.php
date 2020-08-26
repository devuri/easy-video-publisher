<?php

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\Form\InputField;

	# make sure we have added channels
	if ( ! YouTubeDataAPI::has_key() ) :
		$adminkeylink = admin_url('/admin.php?page=evpro-api-setup');
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
		 * if not show the error message and exit
		 */
		try {
			YouTubeDataAPI::youtube()->getVideoInfo('YXQpgAAeLM4');
		} catch (\Exception $e ) {
			echo $this->form()->user_feedback( 'Request failed: '. $e->getMessage(), 'error');
			$adminkeylink = admin_url('/admin.php?page=evpro-api-setup');
			echo $this->form()->user_feedback('Channel Import requires YouTube API Key <strong><a class="button" href="'.$adminkeylink.'">Add YouTube API key</a></strong>');
		 	return;
		}

		// sanitize new channel
		$channel_id 			= sanitize_text_field( $_POST['channel_id'] );
		$channelId 				= trim( $channel_id );

		// set up data
		$channelname 			= YouTubeDataAPI::channelby_id( $channelId )->snippet->title;
		$newchannel 			= array( $channelId => $channelname );
		$update_channels	= array_merge( $newchannel , (array) get_option( 'evp_channels' ) );

		// check if we already have the channel
		$channel_exists = array_key_exists( $channelId , (array) get_option( 'evp_channels' ) );

		// if channel_exists, let the user know
		if ( $channel_exists ) {
			echo $this->form()->user_feedback('<strong>'.$channelname.'</strong> Channel was already Added !!!', 'error');
		} else {
			// add the new channel
			update_option('evp_channels', $update_channels );
			echo $this->form()->user_feedback( '<strong>'.$channelname.'</strong> Channel Added !!!');
		}

	}

endif;

	# section title
	InputField::section_title('Add YouTube Channels');

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
		$evp_channels = (array) get_option( 'evp_channels');
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
