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
 * Plugin Name:       Easy Video Publisher
 * Plugin URI:        https://switchwebdev.com/wordpress-plugins/
 * Description:       Video Publisher is a easy to use Video import plugin, use to Import Youtube videos and Import Youtube channel videos.
 * Version:           3.1.6
 * Requires at least: 3.4
 * Requires PHP:      5.6
 * Author:            SwitchWebdev.com
 * Author URI:        https://switchwebdev.com
 * Text Domain:       easy-video-publisher
 * Domain Path:       languages
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


if ( ! defined('ABSPATH') ) exit;

	// plugin directory
	define("EVP_VERSION", '3.1.6');

	// plugin directory
	define("EVP_DIR", dirname(__FILE__));

	// plugin url
	define("EVP_URL", plugins_url( "/",__FILE__ ));

	// Load composer
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

//  -----------------------------------------------------------------------------

	/**
	 * Setup on activation
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_activation_hook/
	 */
	register_activation_hook( __FILE__, function(){

	 	VideoPublisherlite\Activate::setup();

	});

  	/**
  	 * add after the plugins have fully loaded
  	 * Loads The Admin Pages
  	 */
	add_action( 'plugins_loaded', function () {

		if ( is_admin() ) {
			VideoPublisherlite\Admin\VideoPublisherAdmin::init();
		}
	});
