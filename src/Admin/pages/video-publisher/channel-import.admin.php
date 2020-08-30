<?php

	use VideoPublisherPro\YouTube\YouTubeDataAPI;
	use VideoPublisherPro\YouTube\ImportVideo;
	use VideoPublisherPro\Form\CategoryList;
	use VideoPublisherPro\Form\FormLoader;
	use VideoPublisherPro\Form\InputField;
	use VideoPublisherPro\LatestUpdates;
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


	# make sure we have added channels
	if ( ! YouTubeDataAPI::has_key() ) :
		$adminkeylink = admin_url('/admin.php?page=evpro-api-setup');
		echo $this->form()->user_feedback('Channel Import requires YouTube API Key <strong><a href="'.$adminkeylink.'">Add YouTube API key</a></strong>', 'error');
	endif;

	# make sure we have added channels
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
				' Video Has been Added: <br>' .
				get_the_post_thumbnail( $id , 'medium' ) .
				' <br>New <strong>  '.
				get_post( $id )->post_status .
				': <a href="'.get_permalink( $id ).'" target="_blank">' .
				get_post( $id )->post_title .
				'</a> </strong> ' );
		}
	}

endif;

/**
 * Delete All Videos
 *
 */
if ( isset( $_POST['delete_recent_updates'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	$delete_videos	= array();
	// TODO delete posts along with video ids wp_delete_post(1);

	# delete the videos
	update_option('evp_latest_updates', $delete_videos );

endif;

	// section title
	InputField::section_title('Youtube Channel Import');

	#loading
	FormLoader::loading('update-loader');

?><div id="channel-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php

		echo $this->form()->table('open');

		# channel
		echo $this->form()->select( (array) get_option('evp_channels') , 'Youtube Channel' );

		# categories
		echo $this->form()->select( CategoryList::categories() , 'Select Category' );

		/**
		 * Number of Posts to Create.
		 * @var array
		 */
		$number_of_posts = array(
			1 	=> '1',
			2 	=> '2',
			3 	=> '3',
			4 	=> '4',
			5 	=> '5',
			6 	=> '6',
			7 	=> '7',
			8 	=> '8',
			9 	=> '9',
			10 	=> '10',
		);
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
		 * Posts Author.
		 * @var array
		 */
		$set_author = array(
			0 	=> 'Current Author',
			1 	=> 'YouTube Author',
		);
		echo $this->form()->select( $set_author , 'Set Author' );


		# close the table
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Import Videos', 'primary large', 'get_latest_updates');

		echo '<br><hr/><h4>';
		_e('Recent Updates [ '.LatestUpdates::count_updates().' ]');
		echo '</h4>';

		// delete videos
		echo '<input name="delete_recent_updates" id="delete_recent_updates" type="submit" class="button" value="Delete All Recent Updates">';
		echo '<span style="color: red; font-size: small; display: block;"> This will only delete the video ids for recent updates </span>';
		echo '<br>';

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
