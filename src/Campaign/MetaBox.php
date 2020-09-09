<?php
namespace VideoPublisherPro\Campaign;

	use VideoPublisherPro\WPAdminPage\AdminPage;
	use VideoPublisherPro\Form\CategoryList;
	use VideoPublisherPro\Form\FormLoader;
	use VideoPublisherPro\PostType;

/**
 * Video_Meta
 * the main meta class for the video post type
 */
final class MetaBox extends AdminPage {

	function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'evp_metabox' ) );
		add_action( 'save_post', array( $this, 'save_meta_data' ) ); // fix errors
	}

	/**
	 * Register meta.
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function evp_metabox() {
	    add_meta_box( 'evp-import-campaign', __( 'Video Import Campaign Settings', 'textdomain' ), array( $this, 'evp_meta_view' ), 'evp-campaign' );
	}


	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function evp_meta_view( $post ) {

		//var_dump($post);
		//var_dump( get_post_meta( $post->ID ) );

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

			#loading
			FormLoader::loading('update-loader');

		?><div id="channel-importform">
				<form action="" method="POST"	enctype="multipart/form-data"><?php

				echo $this->form()->table('open');

				# channel
				echo $this->form()->select( (array) get_option('evp_channels') , 'Youtube Channel' );

				# categories
				$categories = array();
				$categories = CategoryList::categories();
				$categories['selected'] = get_cat_name( absint($this->evpa_meta( $post->ID , 'evpa_select_category')) );
				echo $this->form()->select( $categories , 'Select Category' );

				/**
				 * Posts Types.
				 * @var array
				 */
				if ( current_user_can('manage_options')) :
					$post_types = array();
					$post_types = PostType::post_types();
					$post_types['selected'] = $this->evpa_meta( $post->ID , 'evpa_set_post_type');
					echo $this->form()->select( $post_types , 'Set Post Type' );
				endif;

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
				</script><?php
		}

    /**
     * @param $id
     * @param $evp_meta_field
     * @return
     */
	private function evpa_meta( $id ,$evp_meta_field ){
		$post_meta = get_post_meta( $id , $evp_meta_field , true );
		return $post_meta;
	}

	/**
	 * Save meta info.
	 *
	 * @param int $post_id Post ID
	 */
	public function save_meta_data( $post_id ) {

		if ( ! isset( $_POST['_swa_page_wpnonce'] ) ) {
      return $post_id;
    }

		// Automation settings
		update_post_meta( $post_id, 'evpa_youtube_channel', $this->input_val('youtube_channel') );
		update_post_meta( $post_id, 'evpa_select_category', absint('select_category') );
		update_post_meta( $post_id, 'evpa_set_post_type', $this->input_val('set_post_type') );
		update_post_meta( $post_id, 'evpa_set_author', absint('set_author') );
	}

	/**
	 * input_val
	 *
	 * Get the input field $_POST data
	 * @param  string $input_field input name
	 * @return string
	 */
	private function input_val( $input_field = null ){

		if ( is_null($input_field) ) {
			return false;
		}

		$input = sanitize_text_field($_POST[$input_field]);
		if ( isset( $input )) {
			return $input;
		}

	}

}
