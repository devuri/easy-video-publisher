<?php

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\YouTube\AddKey;
	use VideoPublisherPro\Form\FormLoader;
	use VideoPublisherPro\Form\InputField;
	use VideoPublisherPro\MaxIndex;

/**
 * Add API keys
 *
 */
if ( isset( $_POST['add_api_key'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * new API Key
	 */
	$youtube_api_key = sanitize_text_field($_POST['youtube_api_key']);
	$youtube_api_key = trim($youtube_api_key);

	// if the key is not set or empty return null
	if ( ! isset( $youtube_api_key ) || empty( $youtube_api_key ) ) {
		$youtube_api_key = null;
	}

	// check the key limit
	if ( MaxIndex::api_key( (array) get_option('evp_youtube_api', array() ) ) ) {
		// maximum number of channels
		echo $this->form()->user_feedback('You have reached the maximum Index allowed, Looks like you cannot add any more keys !', 'error');
		echo $this->form()->user_feedback('You can increase your limit by upgrading to Pro to unlock more.', 'warning');

	} else {
		
		/**
		 * Process and add the key
		 */
		AddKey::new_apikey($youtube_api_key);
	}

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


// section title
InputField::section_title('Add API Keys');

?><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');

		# add key input
		echo $this->form()->input('YouTube API Key', ' ');

		echo $this->form()->table('close');

		$this->form()->nonce();
		echo '<br>';
		echo $this->form()->submit_button('Add API Key', 'primary large', 'add_api_key');
		echo '<br>';
		echo '<br><hr/>';

		echo YouTubeDataAPI::keys();
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
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		// loading
		jQuery('input[type="submit"]').on('click', function( event ){
			$(".update-loader").removeClass('hidden');
		 });
	});
</script>
