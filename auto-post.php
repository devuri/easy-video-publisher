<?php
/**
 * Sim AutoPost
 *
 * @package           SimAutoPost
 * @author            Uriel Wilson
 * @copyright         2020 Uriel Wilson
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       AutoPost Youtube
 * Plugin URI:        https://switchwebdev.com/wordpress-plugins/
 * Description:       AutoPost Youtube post video from youtube.
 * Version:           2.1.6
 * Requires at least: 3.4
 * Requires PHP:      5.6
 * Author:            SwitchWebdev.com
 * Author URI:        https://switchwebdev.com
 * Text Domain:       sim-autopost
 * Domain Path:       languages
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

 /**
  * Create posts videos from youtube keywords
  * Create posts from youtube channel
  * Create posts from youtube playlist
  * Schedule posts campaigns with wordpress cron
  * Manually run campaigns to create new posts
  * Select post type include post, page, attachment
  * Set post status like publish, draft, private, pending
  * Set post author for each campaigns
  * Use Mutiple API Keys
  *
  * Use YouTube Tags As WordPress Tags
  * Set Featured Image
  * Exclude Video If It Contains One Of These Words
  *
  *
  * Youtube category:
  * Video License:
  * Video Duration:
  * Fetch Full video description from youtube
  *
  * Add posts with its original time
  * Process videos from bottom to top instead
  * Cache videos for faster posting (uncheck to call youtube each run & check latest 50 videos only)
  *
  * Post Youtube Tags as Tags
  * Set the channel author as the WordPress post author
  * Set the channel author as the YouTube Channel author
  *
  * Add title tag to the embed iframe
  *
  * Limit suggested videos at the end of the embed to the video channel
  *
  * Post Youtube Comments as Comments
  * Limit search to embeddable videos
  *
  * Search results for a specified country
  *
  * Set relevance to a specific language
  *
  * Post Types
  * campaigns = ytapost (private: no ui | store campaigns each associated posts post ID )
  * videos = ytavids (private: no ui | store video Title and IDs only, these will be linked to campaigns id)
  */


  # deny direct access
    if ( ! defined( 'WPINC' ) ) {
      die;
    }

  # plugin directory
	  define("SIMAP_VERSION", '2.1.6');

  # plugin directory
    define("SIMAP_DIR", dirname(__FILE__));

  # plugin url
    define("SIMAP_URL", plugins_url( "/",__FILE__ ));
#  -----------------------------------------------------------------------------

//Activate
register_activation_hook( __FILE__, 'sim_autopost_activation' );
function sim_autopost_activation() {

  // add option

}

// youtube class
require_once plugin_dir_path( __FILE__ ). 'src/class-youtube-post.php';


  /**
	 * Load admin page class via composer
	 */
	require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

  // Menu Item
  require_once plugin_dir_path( __FILE__ ). 'src/admin/class-sim-autopost_admin.php';
