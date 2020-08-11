<?php

	use EasyVideoPublisher\InsertPost;
	use EasyVideoPublisher\CategoryList;
	use EasyVideoPublisher\LatestUpdates;
	use EasyVideoPublisher\YouTubeAPI;
	use EasyVideoPublisher\FormLoader;


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
	if ( ! get_option('evp_channels') ) {
		wp_die($this->form()->user_feedback('Please Add at least One Channel', 'warning'));
	}


/**
 * Process the data
 *
 */
if ( isset( $_POST['get_latest_updates'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * checks to make sure the request is ok
	 * if not show the erro message and exit
	 */
	 if ( ! YouTubeAPI::is_request_ok()  ) {
		 wp_die($this->form()->user_feedback( YouTubeAPI::response_error() .' !!!', 'error'));
	 }


	/**
	 * get the channel to post from
	 */
	$channelId 				= trim( $_POST['youtube_channel'] );
	$number_of_posts 	= intval( $_POST['number_of_posts'] );
	$channel_videos 	= YouTubeAPI::channel_videos( $channelId , $number_of_posts );

	# check if we already have the channel_videos in recent_updates
	$recent_updates 	= get_option('evp_latest_updates');
	$new_videos 			= array_diff( $channel_videos , $recent_updates);
	$next_update 			= array_merge( $new_videos , $recent_updates );

	# if we cant find any new videos
	if ( ! $new_videos ) {
		echo $this->form()->user_feedback(' No New Videos to Import', 'warning');
	}

	# create posts
	foreach ( $new_videos  as $upkey => $vid ) {
		/**
		 * check if we posted this already
		 */
		$args['tags'] = null;
		$args['category'] = intval( trim( $_POST['select_category'] ) );
		$id = InsertPost::newpost('https://youtu.be/'.$vid , $args );
		if ($id) {
			echo $this->form()->user_feedback('Video Has been Posted <strong> <a href="'.get_permalink( $id ).'" target="_blank">'.get_post( $id )->post_title.'</a> </strong> ');
		}
	}

	# save updates
	update_option('evp_latest_updates', $next_update );

endif;

?><h2>
	<?php _e('Youtube Channel Import'); ?>
</h2><hr/>
<?php FormLoader::loading('update-loader');; ?>
<div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php

		echo $this->form()->table('open');

		# channel
		echo $this->form()->select( get_option('evp_channels') , 'Youtube Channel' );

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

		# close the table
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Import Videos', 'primary large', 'get_latest_updates');

	?></form>
</div><!--frmwrap-->
<br><hr/><h4>
	<?php _e('Recent Updates [ '.LatestUpdates::count_updates().' ]'); ?>
</h4>
<?php //Latest_Updates::display(); ?>
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {
		jQuery('input[type="submit"]').on('click', function( event ){
			$("#yt-importform").addClass('hidden');
			$(".loading").removeClass('hidden');
		 });
	});
</script>
