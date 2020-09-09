<?php
namespace VideoPublisherPro\Admin;

use VideoPublisherPro\WPAdminPage\AdminPage;


// TODO change to the new
// use PluginAdminUI\AdminPage;

final class VideoPublisherAdmin extends AdminPage {

  /**
   * The capability() for YouTube
   *
   * control access for the youtube video publisher.
   * uses WordPress capabilities, the EVP_ACCESS needs be defined in your wp-config.php file.
   * Example to give access to subscribers simply add "define( 'EVP_ACCESS', 'read' );"
   * in the wp-config.php file and subscribers will be able to access the youtube publisher.
   * default capability is "manage_options" for Administrators
   *
   * @return string user capability.
   * @link https://wordpress.org/support/article/roles-and-capabilities/
   */
  private static function capability(){
    if (defined('EVP_ACCESS')) {
      return EVP_ACCESS;
    } else {
      return 'manage_options';
    }
  }

  /**
   * check if this is pro
   * @return boolean [description]
   */
  private static function is_pro(){
    if (function_exists('vpro_premium_addons')) {
      return true;
    }
    return false;
  }

  /**
   * get the pro addons
   *
   * @return array if not pro return empy array
   */
  private static function addons(){
    if ( self::is_pro() ) {
      $addons = (array) vpro_premium_addons();
    } else {
      $addons = array();
    }
    return $addons;
  }

  /**
   * admin_menu()
   *
   * Main top level admin menus
   * @return array
   */
  private static function admin_menu(){
    $menu = array();
    $menu['mcolor']       = '#0071A1';
    $menu['page_title']   = 'Easy Video Publisher '. self::pro();
    $menu['menu_title']   = 'Video Publisher';
    $menu['capability']   = 'manage_options';
    $menu['menu_slug']    = 'video-publisher';
    $menu['function']     = 'sim_publisher_callback';
    $menu['icon_url']     = 'dashicons-video-alt3';
    $menu['prefix']       = 'evp';
    $menu['plugin_path']  = plugin_dir_path( __FILE__ );
    return $menu;
  }

  /**
   * pro badge
   * @return [type] [description]
   */
  private static function pro(){
    if ( self::is_pro() ) {
      return ' <span class="dashicons dashicons-awards pro">Pro</span>';
    }
    return '';

  }

  /**
   * submenu()
   * array of submenu items
   * @return array
   */
  public static function submenu(){

    $submenu = array();
    $submenu[] = 'Settings';
    $submenu[] = array(
      'name'        => 'YouTube',
      'capability'  => self::capability()
    );
    $submenu[] = 'Channel Import';
    $submenu[] = 'Add Channel';

    /**
     * add submenu addons
     * @var array
     */
    $submenu = array_merge( $submenu, self::addons() );

    // more submenu items
    $submenu[] = 'API Setup';
    $submenu[] = 'Extensions';
    $submenu[] = 'Getting Started';

    return $submenu;
  }

  /**
   * admin description
   * @return VideoPublisherAdmin
   */
  public static function init(){
    return new VideoPublisherAdmin(self::admin_menu(), self::submenu());
  }
}
