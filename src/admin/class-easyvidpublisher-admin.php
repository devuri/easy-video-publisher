<?php
namespace EasyVideoPublisher;

use WPAdminPage\AdminPage;

final class Video_Publisher_Admin extends AdminPage {
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

  private static function submenu(){
    $menu = array();
    $menu[] = 'YouTube';
    $menu[] = 'Bulk Import';
    $menu[] = 'Automatic Import';
    $menu[] = 'Setup';
    return $menu;
  }


  /**
   * [whitelabeladmin description]
   * @return [type] [description]
   */
  public static function init(){
    return new Video_Publisher_Admin(self::admin_menu(),self::submenu());
  }
}

  // create admin pages
  Video_Publisher_Admin::init();
