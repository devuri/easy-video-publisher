<?php

	use VideoPublisherPro\YouTube\AddNewVideo;
	use VideoPublisherPro\Form\CategoryList;
	use VideoPublisherPro\Form\FormLoader;
	use VideoPublisherPro\Form\InputField;
	use VideoPublisherPro\PostType;

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
		 * Add New Video Post
		 */
		$new_video = AddNewVideo::publish( $_POST );
		echo $new_video;

endif;

	// section title
	InputField::section_title('Youtube Video Publisher');

	#loading
	FormLoader::loading('update-loader');

 ?><div id="post-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');

		echo '<td><input type="checkbox" id="custom_title" name="custom_title"> <label for="custom_title">Custom Video Title</label><br> <small> Use a custom title for the video</small></td>';

		echo InputField::custom_title('Video Title');

		echo $this->form()->input('YouTube Video url', ' ');

		// categories
		echo $this->form()->select( CategoryList::categories() , 'Select Category' );

		/**
		 * Posts Types.
		 * @var array
		 */
		if ( current_user_can('manage_options')) :
			echo $this->form()->select( PostType::post_types() , 'Set Post Type' );
		endif;

		echo '<td>You can include hashtags and Instagram username like @myusername in the video description</td>';

		echo InputField::get_editor('','post_description');

		echo $this->form()->input('Tags', ' ');

		echo $this->form()->table('close');

		$this->form()->nonce();
		echo '<br/>';

		echo $this->form()->submit_button('Import Video', 'primary large', 'submit_post_import');

	?></form>
</div><!--frmwrap-->
<br><hr/>
<?php
	/**
	 * this should only be seen by admins
	 * @var [type]
	 */
	if ( current_user_can( 'manage_options' ) ) :
	?><strong>Enabling Access using the WordPress capabilities. </strong>
<br>You can give access to the youtube video publisher using the WordPress capabilities.
The <code>EVP_ACCESS</code> constant needs be defined in your <code>wp-config.php</code> file.
<br>For Example to give access to subscribers simply add <code> define( 'EVP_ACCESS', 'read' );</code>
in the <code> wp-config.php</code> file, and subscribers will be able to access the youtube publisher.
<br>The default capability is for Administrators.
read more about <a target="_blank" href="https://wordpress.org/support/article/roles-and-capabilities">WordPress capabilities</a>.
<hr/><?php
	if (defined('EVP_ACCESS')) {
		echo '<span style="color:red;">EVP_ACCESS is defined, the capability is: '. EVP_ACCESS . '</span>';
	}
endif;
?><script type="text/javascript">
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
