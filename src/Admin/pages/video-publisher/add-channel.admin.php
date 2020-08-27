<?php

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\Form\InputField;
	use VideoPublisherPro\MaxIndex;

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

		/**
		 * get and sanitize new  channel id
		 */
		$channel_id = sanitize_text_field( $_POST['channel_id'] );
		$channelId 	= trim( $channel_id );

		if ( ! isset( $channel_id ) || empty( $channel_id ) ) {
			$channel_id = null;
		}

		// check the channel index limit
		if ( MaxIndex::channels( (array) get_option( 'evp_channels' , array() ) ) ) {

			// maximum number of channels
			echo $this->form()->user_feedback('You have reached the maximum Index allowed, Looks like you cannot add any more channels !', 'error');
			echo $this->form()->user_feedback('You can increase your limit by upgrading to Pro to unlock more.', 'warning');

		} else {

			/**
			 * Process and add the channel
			 */
			YouTubeDataAPI::add_channel( $channelId );
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
		$evp_channels = (array) get_option( 'evp_channels' );
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
