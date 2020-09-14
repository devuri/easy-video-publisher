<?php

	use VideoPublisherlite\YouTube\YouTubeDataAPI;
	use VideoPublisherlite\YouTube\ImportVideo;
	use VideoPublisherlite\Form\CategoryList;
	use VideoPublisherlite\Form\FormLoader;
	use VideoPublisherlite\Form\InputField;
	use VideoPublisherlite\PostType;

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

	// make sure we have added channels
	if ( ! YouTubeDataAPI::has_key() ) :
		$adminkeylink = admin_url('/admin.php?page=evp-api-setup');
		echo $this->form()->user_feedback('Channel Import requires YouTube API Key <strong><a href="'.$adminkeylink.'">Add YouTube API key</a></strong>', 'error');
	endif;

	// make sure we have added channels
	if ( ! get_option('evp_channels') ) :
	 	echo $this->form()->user_feedback('Please Add at least One Channel', 'warning');
	endif;

/**
 * Process the data
 *
 */
if ( isset( $_POST['get_latest_updates'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * creates the posts
	 */
	$posts = ImportVideo::add_video( $_POST );

	if ( $posts ) {

		// display
		foreach ( $posts as $pkey => $id ) {

			echo $this->form()->user_feedback(
				get_the_post_thumbnail( $id , 'medium' ) .
				' <br>New <strong>  '.
				get_post( $id )->post_status .
				': <a href="'.get_permalink( $id ).'" target="_blank">' .
				get_post( $id )->post_title .
				'</a> </strong> ' );
		}
	}

endif;

	// section title
	InputField::section_title('Youtube Channel Import');

	#loading
	FormLoader::loading('update-loader');

?><div id="channel-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php

		echo $this->form()->table('open');

		// channel
		echo $this->form()->select( (array) get_option('evp_channels') , 'Youtube Channel' );

		// categories
		echo $this->form()->select( CategoryList::categories() , 'Select Category' );

		/**
		 * Number of Posts to Create.
		 * @var array
		 */
		$number_of_posts = array_slice(range(0, 20),1, null, true);
		echo $this->form()->select( $number_of_posts , 'Number of Posts' );

		/**
		 * Posts Types.
		 * @var array
		 */
		if ( current_user_can('manage_options')) :
			echo $this->form()->select( PostType::post_types() , 'Set Post Type' );
		endif;

		/**
		 * Set Post Status.
		 * @var array
		 */
		$post_status = array(
			'draft' 	=> 'Draft',
			'publish' => 'Publish'
		);
		echo $this->form()->select( $post_status , 'Post Status' );

		/**
		 * Set Post Schedule.
		 * @var array
		 */
		$schedule = array(
			0 	=> 'None',
			4 	=> 'Schedule (4h)',
			8 	=> 'Schedule (8h)',
			12 	=> 'Schedule (12h)',
			16 	=> 'Schedule (16h)',
			24 	=> 'Schedule (24h)',
			48 	=> 'Schedule (48h)',
			168 => 'Schedule (7d)',
		);
		echo $this->form()->select( $schedule , 'Post Schedule' );

		/**
		 * Posts Author.
		 * @var array
		 */
		$set_author = array(
			0 	=> 'Current Author',
			1 	=> 'YouTube Author',
		);
		echo $this->form()->select( $set_author , 'Set Author' );


		// close the table
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Import Videos', 'primary large', 'get_latest_updates');


	?></form>
</div><!--frmwrap-->
<br/><hr/>
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {

		jQuery('input[type="submit"]').on('click', function( event ){
			$("#new-post-preview").addClass('hidden');
			$("#channel-importform").addClass('hidden');
			$(".loading").removeClass('hidden');
		 });

	});
</script>
