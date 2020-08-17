<?php

	use EasyVideoPublisher\Post\InsertPost;
	use EasyVideoPublisher\Youtube\YoutubeVideoInfo;
	use EasyVideoPublisher\Form\CategoryList;
	use EasyVideoPublisher\Form\FormLoader;
	use EasyVideoPublisher\Form\InputField;
	use EasyVideoPublisher\GetBlock;

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
if ( isset( $_POST['submit_post_import'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

		/**
		 * video
		 */
		$vid = sanitize_text_field( trim( $_POST['youtube_video_url'] ) );

		# overrides
		$args = array();
		if ( isset($_POST['custom_title']) && isset($_POST['video_title']) ) {
			$args['title'] 			= sanitize_text_field( trim( $_POST['video_title'] ) );
			$custom_title 			= true;
		}
		$args['embed'] 				= GetBlock::youtube( $vid );
		$args['thumbnail'] 		= YoutubeVideoInfo::video_thumbnail( $vid );
		$args['category'] 		= intval( trim( $_POST['select_category'] ) );
		$args['tags'] 				= sanitize_text_field( trim( $_POST['tags'] ) );
		$args['description']	= wp_filter_post_kses( trim( $_POST['post_description'] ) );
		$args['hashtags']			= array( get_term( $args['category'] , 'category' )->name );

		/**
		 * make sure this is a youtube url
		 */
		if ( YoutubeVideoInfo::video_id($vid) ) {

			$id = InsertPost::newpost($vid, $args);

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

endif;

	# section title
	InputField::section_title('Youtube Video Publisher');

	#loading
	FormLoader::loading('update-loader');

 ?><div id="post-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo '<td><input type="checkbox" id="custom_title" name="custom_title"> <label for="custom_title">Custom Video Title</label><br> <small> Use a custom title for the video</small></td>';
		echo InputField::custom_title('Video Title');
		echo $this->form()->input('YouTube Video url', ' ');

		# categories
		echo $this->form()->select( CategoryList::categories() , 'Select Category' );


		echo '<td>You can include hashtags and Instagram username like @myusername in the video description</td>';
		echo InputField::get_editor('','post_description');
		echo $this->form()->input('Tags', ' ');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Import Video', 'primary large', 'submit_post_import');
	?></form>
</div><!--frmwrap-->
<br/><hr/>
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {

		jQuery('#custom_title').on('click', function( event ){
			$(".input-custom-title").fadeOut( "slow");
			$(".input-custom-title").addClass('hidden');
			if ($('#custom_title').is(":checked")) {
				$(".input-custom-title").fadeIn( "slow");
				$(".input-custom-title").removeClass('hidden');
			}
		});

		jQuery('input[type="submit"]').on('click', function( event ){
			$("#new-post-preview").addClass('hidden');
			$("#post-importform").addClass('hidden');
			$(".loading").removeClass('hidden');
		 });
	});
</script>
