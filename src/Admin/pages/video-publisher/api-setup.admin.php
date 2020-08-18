<?php

	use EasyVideoPublisher\YouTube\YouTubeDataAPI;
	use EasyVideoPublisher\Form\FormLoader;
	use EasyVideoPublisher\Form\InputField;

/**
 * Add API keys
 *
 */
if ( isset( $_POST['add_api_key'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * Adds new API Keys
	 */
	$new_key 			= array(trim( $_POST['youtube_api_key'] ));

	# check if we already have the key in recent updates
	$api_keys 		= get_option( 'evp_youtube_api' );
	$iskey_new		= array_diff( $new_key , $api_keys);
	$update_keys	= array_merge( $new_key , $api_keys );

	# if we cant find any new videos
	if ( $iskey_new ) {
		# add the new channel
		update_option('evp_youtube_api', $update_keys );
		echo $this->form()->user_feedback( 'New API Key <strong>'.$new_key[0].'</strong> Added !!!');
	} else {
		echo $this->form()->user_feedback('<strong>'.$new_key[0].'</strong> already Exists !!!', 'error');
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
