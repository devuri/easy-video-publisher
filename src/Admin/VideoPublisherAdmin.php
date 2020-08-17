<?php
namespace EasyVideoPublisher\Admin;

use EasyVideoPublisher\WPAdminPage\AdminPage;

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
    $menu['id']           = 634890;
    $menu['pro']          = true;
    $menu['mcolor']       = '#0071A1';
    $menu['page_title']   = 'Easy Video Publisher Pro';
    $menu['menu_title']   = 'Video Publisher';
    $menu['capability']   = 'manage_options';
    $menu['menu_slug']    = 'easy-video-publisher';
    $menu['function']     = 'sim_publisher_callback';
    $menu['icon_url']     = 'dashicons-video-alt3';
    $menu['prefix']       = 'evpro';
    $menu['plugin_path']  = plugin_dir_path( __FILE__ );
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
      'capability'  => 'read'
    );
    $submenu[] = 'Instagram';
    $submenu[] = 'Channel Import';
    // $submenu[] = 'Search Import';
    // $submenu[] = 'Playlist Import';
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
