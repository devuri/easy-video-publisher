<?php
/**
 * Process the data
 *
 */
if ( isset( $_POST['youtube_video_import'] ) ){

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('error','Verification Failed !!!'));
	}

		/**
		 * video
		 */
		$vid = sanitize_text_field($_POST['youtube_video_url']);
		$id = EasyVideoPublisher\YoutubeVideoPost::newpost($vid, true);
		print_r($id);

}
?><hr/>
<div id="frmwrap" >
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->input('YouTube Video url', 'video url');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo $this->form()->submit_button('Import Video', 'primary large', 'youtube_video_import');
	?></form>
</div><!--frmwrap-->
