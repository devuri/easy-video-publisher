<?php
/**
 *  Uninstall stuff.
 *  do some cleanup after user uninstalls the plugin
 *  ----------------------------------------------------------------------------
 *  -remove stuff
 * ----------------------------------------------------------------------------
 * @category  	Plugin
 * @copyright 	Copyright © 2020 Uriel Wilson.
 * @package   	VideoPublisherPro
 * @author    	Uriel Wilson
 * @link      	https://switchwebdev.com
 *  ----------------------------------------------------------------------------
 */

	// Deny direct access
  if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	  die;
  }

  /**
   * Delete the plugin options
   */
  delete_option( 'evp_youtube_api' );
  delete_option( 'evp_channels' );
  delete_option( 'evp_restricted_categories' );

  /**
   * Delete "evp_videos" Table.
   *
   * TODO maybe ask the user 
   */
  global $wpdb;
  $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'evp_videos' );


  // Finally clear the cache
  wp_cache_flush();
