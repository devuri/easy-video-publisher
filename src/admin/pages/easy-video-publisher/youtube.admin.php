<?php

	use EasyVideoPublisher\YoutubeVideoPost;
	use EasyVideoPublisher\Category_List;
	use EasyVideoPublisher\Category_Select;
	use EasyVideoPublisher\FormLoader;
	use EasyVideoPublisher\Sim_Editor;

	/**
	 * CSS for the loader
	 */
	FormLoader::css_style(
		array(
			'size' 						=> '200px',
			'padding' 				=> '1em',
			'padding-bottom' 	=> '0',
		)
	);

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
		if ( isset($_POST['custom_title']) && isset($_POST['video_title']) ) {
			$args['title'] = sanitize_text_field( trim( $_POST['video_title'] ) );
			$custom_title = true;
		}
		$args['category'] = intval( trim( $_POST['categoryset_category'] ) );
		$args['tags'] = sanitize_text_field( trim( $_POST['video_tags'] ) );
		$args['description'] = wp_filter_post_kses( trim( $_POST['video_description'] ) );


		/**
		 * make sure this is a youtube url
		 */
		if ( YoutubeVideoPost::video_id($vid) ) {

			$id = YoutubeVideoPost::newpost($vid, $args);

			if ($id) {
				echo $this->form()->user_feedback('Video Has been Posted <strong> '.get_post( $id )->post_title.' </strong> ');
				echo '<div id="new-post-preview">';
				echo '<img width="400" src="'.get_the_post_thumbnail_url( $id ).'">';
				echo '<br>';
				echo '<a href="'.get_permalink( $id ).'" target="_blank">'.get_post( $id )->post_title.'</a>';
				echo '</div>';
			}
		} else {
			echo $this->form()->user_feedback('Please Use a Valid YouTube url !!!', 'error');
		}
}
?><h2>
	<?php _e('Youtube Video Publisher'); ?>
</h2><hr/>
	<?php FormLoader::loading('update-loader');; ?>
<div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo '<td><input type="checkbox" id="custom_title" name="custom_title"> <label for="custom_title">Custom Video Title</label><br> <small> Use a custom title for the video</small></td>';
		echo YoutubeVideoPost::custom_title();
		echo $this->form()->input('YouTube Video url', ' ');
		echo Category_Select::categories('category');
		echo '<td>You can include hashtags and Instagram username like @myusername in the video description</td>';
		echo Sim_Editor::get_editor('','video_description');
		echo $this->form()->input('Video Tags', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<hr/>';
		echo $this->form()->submit_button('Import Video', 'primary large', 'youtube_video_import');
	?></form>
</div><!--frmwrap-->
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {

		jQuery('#custom_title').on('click', function( event ){
			$(".input-video-title").fadeOut( "slow");
			$(".input-video-title").addClass('hidden');
			if ($('#custom_title').is(":checked")) {
				$(".input-video-title").fadeIn( "slow");
				$(".input-video-title").removeClass('hidden');
			}
		});

		jQuery('input[type="submit"]').on('click', function( event ){
			$("#new-post-preview").addClass('hidden');
			$("#yt-importform").addClass('hidden');
			$(".loading").removeClass('hidden');
		 });
	});
</script>
