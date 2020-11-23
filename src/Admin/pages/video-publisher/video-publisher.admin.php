<?php

use VideoPublisherlite\Form\FormLoader;
use VideoPublisherlite\Form\CategoryList;
use VideoPublisherlite\Form\InputField;

if ( ! defined('ABSPATH') ) exit;

		/**
		 * CSS for the loader
		 */
		FormLoader::css_style(
			array(
				'size' 				=> '20px',
				'padding' 			=> '0px',
				'padding-bottom'	=> '1em',
			)
		);


/**
 * Process the data
 *
 */
if ( isset( $_POST['save_category_settings'] ) ) :

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}
	/**
	 * Make sure this is set if not load empty array
	 * @var [type]
	 */
	if ( ! isset( $_POST['category'] ) ) {

		// update with empty array
		$restricted_category = array();
		update_option( 'evp_restricted_categories' , $restricted_category );

	} else {

		/**
		 * Get category ids
		 */
		$categories = array_keys( $_POST['category'] );
		foreach ( $categories as $catkey => $val ) {
			$val = (int) $val;
			$restricted_category[ $catkey ] = absint( $val );
		}

		// update and provide feedback
	  update_option('evp_restricted_categories', $restricted_category );
	}

endif;

	// section title
	$arg['loader'] = true;
	InputField::section_title('Video Publisher Settings', $arg );

?><div id="category-form">
	<small>
		Restricted for other users that have access to the Youtube Video Publisher.
	</small>
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		//var_dump(get_option('evp_restricted_categories'));
		echo $this->form()->table('open');
		echo '<th><label for="category-list">Restrict Categories</label></th>';
		echo '<td>';
		echo CategoryList::checkbox();
		echo '</td>';
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<br/>';
		echo $this->form()->submit_button('Update Restricted Categories', 'primary large', 'save_category_settings');
	?></form>
</div><!--frmwrap-->
<br/><hr/>
<script type="text/javascript">
	jQuery( document ).ready( function( $ ) {

		// selection
		jQuery('input[type="checkbox"]').on('click', function( event ){
			$(this).parent().css('background-color', '#fff').css('color', '#424242');
				if ($(this).is(":checked")) {
					$(this).parent().css('background-color', '#fff').css('color', '#b9b9b9');
			}
		});

		// loading
		jQuery('input[type="submit"]').on('click', function( event ){
			$(".update-loader").removeClass('hidden');
		 });
	});
</script>
