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
    $menu[] = YoutubeVideoPost::access_level('admin');
    $menu[] = 'easy-video-publisher';
    $menu[] = 'sim_publisher_callback';
    $menu[] = 'dashicons-video-alt3';
    $menu[] = null;
    $menu[] = 'evp';
    $menu[] = plugin_dir_path( __FILE__ );
    return $menu;
  }

  /**
   * admin description
   * @return
   */
  public static function init(){
    return new Video_Publisher_Admin(self::admin_menu());
  }
}

  // create admin pages
  Video_Publisher_Admin::init();
