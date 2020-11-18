<?php

use VideoPublisherlite\YouTube\YouTubeData;
use VideoPublisherlite\YouTube\ImportVideo;
use VideoPublisherlite\Form\CategoryList;
use VideoPublisherlite\Form\FormLoader;
use VideoPublisherlite\Form\InputField;
use VideoPublisherlite\PostType;

if ( ! defined( 'ABSPATH' ) ) exit;

	/**
	 * CSS for the loader
	 */
	FormLoader::css_style(
		array(
			'size'           => '200px',
			'padding'        => '1em',
			'padding-bottom' => '0',
		)
	);

	// make sure we have added channels .
	if ( ! YouTubeData::api()->has_key() ) :
		$adminkeylink = admin_url( '/admin.php?page=evp-api-setup' );
		echo $this->form()->user_feedback( 'Channel Import requires YouTube API Key <strong><a href="' . $adminkeylink . '">Add YouTube API key</a></strong>', 'error' ); // @codingStandardsIgnoreLine
	endif;

	// make sure we have added channels .
	if ( ! get_option( 'evp_channels' ) ) :
	 	echo $this->form()->user_feedback( 'Please Add at least One Channel', 'warning' ); // @codingStandardsIgnoreLine
	endif;

/**
 * Process the data
 */
if ( isset( $_POST['get_latest_updates'] ) ) : // @codingStandardsIgnoreLine

	if ( ! $this->form()->verify_nonce() ) {
		wp_die( $this->form()->user_feedback( 'Verification Failed !!!', 'error' ) ); // @codingStandardsIgnoreLine
	}

	/**
	 * Creates the posts
	 */
	$create = new ImportVideo( $_POST ); // @codingStandardsIgnoreLine
	$create->add_video();

endif;

	// section title .
	InputField::section_title( 'Youtube Channel Import' );

	// loading .
	FormLoader::loading( 'update-loader' );

?>
<div id="channel-importform">
		<form action="" method="POST"	enctype="multipart/form-data">
			<?php

		echo $this->form()->table( esc_attr( 'open' ) ); // @codingStandardsIgnoreLine

		// channel .
		$evp_channels = (array) get_option( 'evp_channels' );
		asort( $evp_channels );
		echo $this->form()->select( $evp_channels, 'Youtube Channel' ); // @codingStandardsIgnoreLine

		// categories .
		echo $this->form()->select( CategoryList::categories(), 'Select Category' ); // @codingStandardsIgnoreLine

		/**
		 * Number of Posts to Create.
		 */
		$number_of_posts = array_slice( range( 0, 20 ), 1, null, true );
		echo $this->form()->select( $number_of_posts , 'Number of Posts' ); // @codingStandardsIgnoreLine

		/**
		 * Posts Types.
		 */
		if ( current_user_can( 'manage_options' ) ) :
			echo $this->form()->select( PostType::post_types(), 'Set Post Type' ); // @codingStandardsIgnoreLine
		endif;

		/**
		 * Set Post Status.
		 */
		$post_status = array(
			'draft'   => 'Draft',
			'publish' => 'Publish',
		);
		echo $this->form()->select( $post_status, 'Post Status' ); // @codingStandardsIgnoreLine

		/**
		 * Set Post Schedule.
		 */
		$schedule = array(
			0   => 'Now',
			4   => 'Schedule (4h)',
			8   => 'Schedule (8h)',
			12  => 'Schedule (12h)',
			16  => 'Schedule (16h)',
			24  => 'Schedule (24h)',
			48  => 'Schedule (48h)',
			168 => 'Schedule (7d)',
		);
		echo $this->form()->select( $schedule, 'Post Schedule' ); // @codingStandardsIgnoreLine

		/**
		 * Posts Author.
		 */
		$video_description = array(
			1 => 'Yes, Include Video Description',
			0 => 'No, Exclude Video Description',
		);
		echo $this->form()->select( $video_description, 'Import with Video Description' ); // @codingStandardsIgnoreLine

		/**
		 * Posts Author.
		 */
		$set_author = array(
			1 => 'YouTube Author',
			0 => 'Current Author',
		);
		echo $this->form()->select( $set_author, 'Set Author' ); // @codingStandardsIgnoreLine


		// close the table .
		echo $this->form()->table( 'close' ); // @codingStandardsIgnoreLine
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button( 'Import Videos', 'primary large', 'get_latest_updates' ); // @codingStandardsIgnoreLine

	?>
</form>
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
