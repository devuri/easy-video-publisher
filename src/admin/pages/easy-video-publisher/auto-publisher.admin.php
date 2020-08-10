<?php

	use EasyVideoPublisher\YoutubeVideoPost;
	use EasyVideoPublisher\YouTube_API;


	/**
	 * checks to make sure the request is ok
	 * if not show the erro message and exit
	 */
	YouTube_API::response_error();


/**
 * Process the data
 *
 */
if ( isset( $_POST['get_latest_updates'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	$get_channel_vids = YouTube_API::channel_videos('UCWOA1ZGywLbqmigxE4Qlvuw', 12 );

		# create posts
		foreach ( $get_channel_vids  as $upkey => $vid ) {

			/**
			 * check if we posted this already
			 */
			if ( ! in_array( $vid , get_option('evp_latest_updates') ) ) {
				$args['tags'] = null;
				$id = YoutubeVideoPost::newpost('https://www.youtube.com/watch?v='.$vid , $args );
				if ($id) {
					echo $this->form()->user_feedback('Video Has been Posted <strong> <a href="'.get_permalink( $id ).'" target="_blank">'.get_post( $id )->post_title.'</a> </strong> ');
				}
			}
		}

		// update and provide feedback
		update_option('evp_latest_updates', $get_channel_vids );



endif;

?><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<hr/>';
		echo $this->form()->submit_button('Publish Videos', 'primary large', 'get_latest_updates');
	?></form>
</div><!--frmwrap-->
