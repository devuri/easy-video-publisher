<?php
/**
 * Process the data
 *
 */
if ( isset( $_POST['youtube_video_import'] ) ){

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('error','Verification Failed !!!'));
	}
		$id = EasyVideoPublisher\YoutubeVideoPost::newpost('https://www.youtube.com/watch?v=Wji4b2jjYOk', true);
		print_r($id);

}
?><hr/>
<div id="frmwrap" >
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->submit_button('Import Video', 'primary large', 'youtube_video_import');
	  $this->form()->nonce();
	?></form>
</div><!--frmwrap-->
