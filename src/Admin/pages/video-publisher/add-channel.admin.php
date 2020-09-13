<?php

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\YouTube\AddChannel;
	use VideoPublisherPro\Form\InputField;
	use VideoPublisherPro\MaxIndex;

	// make sure we have added channels
	if ( ! YouTubeDataAPI::has_key() ) :
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
		 * get and sanitize new channel id
		 */
		$channel_id = sanitize_text_field( $_POST['channel_id'] );
		$channelId 	= trim( $channel_id );

		// if the channel id is not set or empty return null
		if ( ! isset( $channel_id ) || empty( $channel_id ) ) {
			$channel_id = null;
		}

		// check the channel index limit
		if ( MaxIndex::channels( (array) get_option( 'evp_channels' , array() ) ) ) {

			// maximum number of channels
			echo $this->form()->user_feedback('You have reached the maximum Index allowed, Looks like you cannot add any more channels !', 'error');
		} else {

			/**
			 * Process and add the channel
			 */
			AddChannel::new_channel( $channelId );
		}

	}

endif;

	/**
	 * Delete the Channels
	 *
	 */
	if ( isset( $_POST['delete_channels'] ) ) :

		if ( ! $this->form()->verify_nonce()  ) {
			wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
		}

		// delete channels
		$delete_channels 	= array();
		update_option('evp_channels', $delete_channels );
		echo $this->form()->user_feedback('Channels have been deleted.');

	endif;

	// section title
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
		echo '<br>';
		echo '<br><hr/>';

		echo YouTubeDataAPI::list_channels();
		echo '<input name="delete_channels" id="delete_channels" type="submit" class="button" value="Delete Channels ">';

	?></form>
</div><!--frmwrap-->
<br/><hr/>
