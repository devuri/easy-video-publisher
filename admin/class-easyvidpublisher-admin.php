<?php
namespace EasyVideoPublisher;

use WPAdminPage\AdminPage;

// TODO change to the new
// use PluginAdminUI\AdminPage;

final class VideoPublisherAdmin extends AdminPage {
  /**
   * admin_menu()
   *
   * Main top level admin menus
   * @return [type] [description]
   */
  private static function admin_menu(){
    $menu = array();
    $menu[] = 'Easy Video Publisher';
    $menu[] = 'Video Publisher';
    $menu[] = 'manage_options';
    $menu[] = 'easy-video-publisher';
    $menu[] = 'sim_publisher_callback';
    $menu[] = 'dashicons-video-alt3';
    $menu[] = null;
    $menu[] = 'evp';
    $menu[] = plugin_dir_path( __FILE__ );
    return $menu;
  }

  /**
   * submenu()
   * array of submenu items
   * @return array
   */
  private static function submenu(){
    $submenu = array();
    $submenu[] = 'Settings';
    $submenu[] = array(
      'name'    => 'YouTube',
      'access'  => 'read'
    );
    $submenu[] = 'Instagram';
    $submenu[] = 'Channel Import';
    $submenu[] = 'Add Channel';
    $submenu[] = 'API Setup';
    return $submenu;
  }

  /**
   * admin description
   * @return
   */
  public static function init(){
    return new VideoPublisherAdmin(self::admin_menu(),self::submenu());
  }
}

  // create admin pages
  VideoPublisherAdmin::init();
