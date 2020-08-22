<?php

	use VideoPublisherPro\Post\InsertPost;
	use VideoPublisherPro\Post\UrlDataAPI;
	use VideoPublisherPro\YouTube\YoutubeVideoInfo;
	use VideoPublisherPro\Form\CategoryList;
	use VideoPublisherPro\Form\FormLoader;
	use VideoPublisherPro\Form\InputField;


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
		$medialink = sanitize_text_field( trim( $_POST['instagram_url'] ) );

		# overrides
		$args = array();
		if ( isset($_POST['custom_title']) && isset($_POST['title']) ) {
			$args['title'] 			= sanitize_text_field( trim( $_POST['title'] ) );
			$custom_title 			= true;
		}


		$args['username'] 		= UrlDataAPI::get_data( $medialink )->author_name;
		$args['embed'] 				= wp_oembed_get( $medialink );
		$args['category'] 		= intval( trim( $_POST['select_category'] ) );
		$args['tags'] 				= sanitize_text_field( trim( $_POST['tags'] ) );
		$args['description']	= wp_filter_post_kses( trim( $_POST['post_description'] ) );
		$args['hashtags'] 		= array( get_term( $args['category'] , 'category' )->name );


		/**
		 * make sure this is a youtube url
		 */
		$id = InsertPost::newpost($medialink, $args);

			if ($id) {
				echo $this->form()->user_feedback('Video Has been Posted <strong> '.get_post( $id )->post_title.' </strong> ');
				echo '<div id="new-post-preview">';
				echo '<img width="400" src="'.get_the_post_thumbnail_url( $id ).'">';
				echo '<br>';
				echo '<a href="'.get_permalink( $id ).'" target="_blank">'.get_post( $id )->post_title.'</a>';
				echo '</div>';
			}
endif;

	# section title
	InputField::section_title('Instagram Publisher');

	#loading
	FormLoader::loading('update-loader');

?><div id="post-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo '<td><input type="checkbox" id="custom_title" name="custom_title"> <label for="custom_title">Custom Title</label><br> <small> Use a custom title for the video</small></td>';
		echo InputField::custom_title('Title');
		echo $this->form()->input('Instagram Url', ' ');

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
