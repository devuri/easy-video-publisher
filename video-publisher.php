<?php
/**
 * Sim AutoPost
 *
 * @package           EasyVideoPublisher
 * @author            Uriel Wilson
 * @copyright         2020 Uriel Wilson
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Video Publisher
 * Plugin URI:        https://switchwebdev.com/wordpress-plugins/
 * Description:       Easy Video Publisher use to Import Youtube videos from youtube channel playlist or search.
 * Version:           0.1.5
 * Requires at least: 3.4
 * Requires PHP:      5.6
 * Author:            SwitchWebdev.com
 * Author URI:        https://switchwebdev.com
 * Text Domain:       easy-video-publisher
 * Domain Path:       languages
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


  # deny direct access
    if ( ! defined( 'WPINC' ) ) {
      die;
    }

  # plugin directory
	  define("EVP_VERSION", '0.1.5');

  # plugin directory
    define("EVP_DIR", dirname(__FILE__));

  # plugin url
    define("EVP_URL", plugins_url( "/",__FILE__ ));
#  -----------------------------------------------------------------------------

//Activate
register_activation_hook( __FILE__, 'sim_easyvidpublisher_activation' );
function sim_easyvidpublisher_activation() {

  // add option

}

// open graph data
require_once plugin_dir_path( __FILE__ ). 'src/class-open-graph-data.php';

// youtube class
require_once plugin_dir_path( __FILE__ ). 'src/class-youtube-post.php';


  /**
	 * Load admin page class via composer
	 */
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

  // Menu Item
  require_once plugin_dir_path( __FILE__ ). 'src/admin/class-easyvidpublisher-admin.php';
