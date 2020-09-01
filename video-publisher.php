<?php
/**
 * Video Publisher Lite
 *
 * @package           VideoPublisherLite
 * @author            Uriel Wilson
 * @copyright         2020 Uriel Wilson
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       Video Publisher Lite
 * Plugin URI:        https://switchwebdev.com/wordpress-plugins/
 * Description:       Video Publisher is a easy to use Video import plugin, use to Import Youtube videos from youtube channel playlist or search.
 * Version:           2.4.3
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
	  define("EVP_VERSION", '2.4.3');

  # plugin directory
    define("EVP_DIR", dirname(__FILE__));

  # plugin url
    define("EVP_URL", plugins_url( "/",__FILE__ ));

#  -----------------------------------------------------------------------------

  //Activate
  register_activation_hook( __FILE__, 'sim_vidpublisher_activation' );
  function sim_vidpublisher_activation() {

    # api key
    $api_key = array();
    update_option('evp_youtube_api', $api_key );

    # channels
    $channels = array();
    update_option('evp_channels', $channels);

    # latest updates
    $latest_updates = array();
    update_option('evp_latest_updates', $latest_updates );

    # restrict categpries
    $restricted_categories = array();
    update_option('evp_restricted_categories', $restricted_categories );

  }

  /**
	 * Load composer
	 */
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

  /**
   * add after the plugins have fully loaded
   * since we will have addons for this.
   *
   */
  function evp_lite_admin_pages() {
    /**
     * Load Admin Pages
     */
    if ( is_admin() ) {
      VideoPublisherPro\Admin\VideoPublisherAdmin::init();
    }
  }
  add_action( 'plugins_loaded', 'evp_lite_admin_pages' );
