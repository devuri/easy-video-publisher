<?php

use WPAdminPage\AdminPage;

final class Sim_AutoPost_Admin extends AdminPage {
  /**
   * admin_menu()
   *
   * Main top level admin menus
   * @return [type] [description]
   */
  private static function admin_menu(){
    $menu = array();
    $menu[] = 'Sim AutoPost Settings';
    $menu[] = 'Sim AutoPost';
    $menu[] = 'manage_options';
    $menu[] = 'sim-autopost';
    $menu[] = 'sim_autopost_callback';
    $menu[] = 'dashicons-video-alt3';
    $menu[] = null;
    $menu[] = 'mls';
    $menu[] = plugin_dir_path( __FILE__ );
    return $menu;
  }

  /**
   * [whitelabeladmin description]
   * @return [type] [description]
   */
  public static function init(){
    return new Sim_AutoPost_Admin(self::admin_menu());
  }
}

  // create admin pages
  Sim_AutoPost_Admin::init();
