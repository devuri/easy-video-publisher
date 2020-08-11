<?php
namespace EasyVideoPublisher;

/**
 * Instance of the wp_editor
 */
class SimEditor
{

  /**
	 * define user access level for the admin form
	 * who can acces and use the form
	 */
	public static function access_level( $role = 'admin'){

		$access = array();
		$access['admin'] 				= 'manage_options';
		$access['editor'] 			= 'delete_others_pages';
		$access['author'] 			= 'publish_posts';
		$access['contributor'] 	= 'edit_posts';
		$access['subscriber'] 	= 'read';

		return $access[$role];
	}

  /**
	 * allow the user to add a custom Title
	 * Instead of using the title from oEmbed
	 * @return [type] [description]
	 */
	public static function custom_title(){
		$video_title = '<tr class="input-video-title hidden"><th>';
		$video_title .= '<label for="video_title">Video Title</label>';
		$video_title .= '</th>';
		$video_title .= '<td><input type="text" name="video_title" id="video_title" aria-describedby="video-title-description" value=" " class="uk-input">';
		$video_title .= '<p class="description" id="video-title-description">video title<strong>.</strong>';
		$video_title .= '</p></td></tr>';
		return $video_title;
	}
  /**
   * the wp editor
   * @param  string $content   [description]
   * @param  [type] $editor_id [description]
   * @param  array  $options   [description]
   * @return [type]            [description]
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
}
