<?php

	use EasyVideoPublisher\FormLoader;
	use EasyVideoPublisher\Category_List;


		/**
		 * CSS for the loader
		 */
		FormLoader::css_style();


/**
 * Process the data
 *
 */
if ( isset( $_POST['save_category_settings'] ) ){

	if ( ! $this->form()->verify_nonce()  ) {
		wp_die($this->form()->user_feedback('Verification Failed !!!', 'error'));
	}

	/**
	 * get category ids
	 */
	$categories = array_keys($_POST['category']);
	var_dump($categories);


}
?><h2>
	<?php _e('Publisher Settings'); ?>
</h2><hr/>
<div id="loading-div" class="hidden" style="padding: 3em;">
	<?php FormLoader::loading(); ?>
</div><div id="yt-importform">
		<form action="" method="POST"	enctype="multipart/form-data"><?php
		echo $this->form()->table('open');
		echo '<th><label for="category-list">Restrict Categories</label></th>';
		echo '<td>';
		echo Category_List::checkbox();
		echo '</td>';
		echo $this->form()->table('close');
		$this->form()->nonce();
		echo '<hr/>';
		echo $this->form()->submit_button('Save', 'primary large', 'save_category_settings');
	?></form>
</div><!--frmwrap-->
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
			$("#new-post-preview").addClass('hidden');
			$("#yt-importform").addClass('hidden');
			$("#loading-div").removeClass('hidden');
		 });
	});
</script>
