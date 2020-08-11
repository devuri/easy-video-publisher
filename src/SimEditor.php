<?php
namespace EasyVideoPublisher;

/**
 * Instance of the wp_editor
 */
class SimEditor
{

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
