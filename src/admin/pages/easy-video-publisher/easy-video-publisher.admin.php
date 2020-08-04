<?php

	/**
	 * CSS for the loader
	 */
	EasyVideoPublisher\FormLoader::css_style();

/**
 * Process the data
 *
 */
if ( isset( $_POST['youtube_video_import'] ) ){

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

		/**
		 * video
		 */
		$vid = sanitize_text_field( trim( $_POST['youtube_video_url'] ) );

		$args = array();
		$args['category'] = intval( trim( $_POST['categoryset_category'] ) );
		$args['tags'] = sanitize_text_field( trim( $_POST['video_tags'] ) );

		/**
		 * make sure this is a youtube url
		 */
		if ( EasyVideoPublisher\YoutubeVideoPost::video_id($vid) ) {

			$id = EasyVideoPublisher\YoutubeVideoPost::newpost($vid, $args);
			if ($id) {
				echo $this->form()->user_feedback('Video Has been Posted <strong> '.get_post( $id )->post_title.' </strong> ');
				echo '<img width="400" src="'.get_the_post_thumbnail_url( $id ).'">';
				echo '<br>';
				echo '<a href="'.get_permalink( $id ).'" target="_blank">'.get_post( $id )->post_title.'<a>';
			}
		} else {
			echo $this->form()->user_feedback('Please Use a Valid YouTube url !!!', 'error');
		}
}
?><div id="loading-div" class="hidden">
	<?php EasyVideoPublisher\FormLoader::loading(); ?>
</div><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->input('YouTube Video url', ' ');
		echo $this->form()->categorylist('Category', ' ');
		echo $this->form()->input('Video Tags', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo $this->form()->submit_button('Import Video', 'primary large', 'youtube_video_import');
	?></form>
</div><!--frmwrap-->

<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		event.preventDefault();
		jQuery('input[type="submit"]').on('click', function( event ){
			$('input[type="submit"]').attr('disabled','disabled');
			$("#loading-div").removeClass('hidden');

			// disable the button
			// add loading to a div
			// send post request
			// get response
			// remove loadinf div on success

		});
	});
</script>
