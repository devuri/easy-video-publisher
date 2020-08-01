<?php
/**
 * Process the data
 *
 */
if ( isset( $_POST['youtube_video_post'] ) ){

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('error','Verification Failed !!!'));
	}

		$id = SimAutoPost\YoutubePostCreate::newpost('https://www.youtube.com/watch?v=x5Ti4Dv9vFM');
		print_r($id);

}
?><hr/>
<div id="frmwrap" >
		<form action="" method="POST"	enctype="multipart/form-data"><?php

		echo $this->form()->submit_button('Publish New Video', 'primary large', 'youtube_video_post');
	  $this->form()->nonce();
	?></form>
</div><!--frmwrap-->
