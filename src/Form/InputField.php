<?php
namespace VideoPublisherlite\Form;

if ( ! defined('ABSPATH') ) exit;

/**
 * Instance of the wp_editor
 */
class InputField
{

  	/**
  	 * define user access level for the admin form, who can acces and use the form
  	 *
  	 * @param string $role
  	 * @return mixed|string
  	 */
	public static function access_level( $role = 'admin'){

		$access = array();
		$access['admin']		= 'manage_options';
		$access['editor']		= 'delete_others_pages';
		$access['author']		= 'publish_posts';
		$access['contributor']	= 'edit_posts';
		$access['subscriber']	= 'read';

		return $access[$role];
	}

  	/**
  	 * allow the user to add a custom Title, Instead of using the title from oEmbed
  	 *
  	 * @param  string $fieldname
  	 * @return string
  	 */
	public static function custom_title( $fieldname = 'Title'){
		$fieldname = strtolower($fieldname);
		$get_title = '<tr class="input-custom-title hidden"><th>';
		$get_title .= '<label for="'.str_replace(" ", "_", $fieldname).'">'.ucwords(str_replace("_", " ", $fieldname )).'</label>';
		$get_title .= '</th>';
		$get_title .= '<td><input type="text" name="'.str_replace(" ", "_", $fieldname).'" id="'.str_replace(" ", "_", $fieldname).'" aria-describedby="'.str_replace(" ", "-", $fieldname).'-description" value=" " class="uk-input">';
		$get_title .= '<p class="description" id="'.str_replace(" ", "-", $fieldname).'-description">'.$fieldname.'<strong>.</strong>';
		$get_title .= '</p></td></tr>';
		return $get_title;
	}

  	/**
  	 * the wp editor
  	 *
  	 * @param  string $content
  	 * @param  string $editor_id
  	 * @param  array  $options
  	 * @return false|string
  	 * @link https://developer.wordpress.org/reference/functions/wp_editor/
  	 * @link https://developer.wordpress.org/reference/classes/_wp_editors/parse_settings/
  	 */
	public static function editor( $content = '', $editor_id = 'new_editor', $options = array() ) {
	    ob_start();
	    $args = array(
	        'media_buttons' => false,
	        'quicktags' => false,
	        'tinymce'       => array(
	            'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,bullist,numlist,outdent,indent,blockquote,link,unlink,undo,redo',
	            'toolbar2'      => '',
	            'toolbar3'      => '',
	        ),
	    );
	    wp_editor( $content, $editor_id, $args );
	    $editor = ob_get_clean();

	    return $editor;
	}

	/**
	 * the wp editor
	 *
	 * @param  string $content
	 * @param  string $editor_id
	 * @param  array  $options
	 * @return string
	 */
	public static function get_editor($content = '', $editor_id = 'new_editor', $options = array()){
	    $name = strtolower($editor_id);
	    $editor = '<tr class="input">
	      <th><label for="'.str_replace(" ", "_", $name).'">
	      '.ucwords(str_replace("_", " ", $name)).'
	      </label></th>
	    <td>
	      '.self::editor($content = '', $editor_id , $options = array()).'
	      <p class="description" id="'.str_replace(" ", "_", $name).'">'.str_replace("_", " ", $name).'.</p>
	      </td>
	    </tr>';
		return $editor;
	}


	public static function section_title( $title = 'Page Title', $args = array() ){

		$default = array();
		$default['loader'] 			= false;
		$args = wp_parse_args( $args , $default );

		?>
		<table class="form-table" role="presentation">
		  <tbody>
		    <!-- page-title -->
		    <tr class="input-tags">
		      <th style="width: 240px; padding:unset; padding-bottom: 1em">
						<label for="page-title">
							<h2 style="margin:0">
								<?php _e($title); ?>
							</h2>
						</label>
					</th>
		      <td style="padding: 0;"><?php
					// if true use the loader
					if ( $args['loader'] ) {
						FormLoader::loading('update-loader');
					}
					?></td>
		    </tr>
		    <!-- page-title-->
		  </tbody>
		</table><hr/>
	<?php }
}
