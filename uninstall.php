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

	# deny direct access
  if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	  die;
  }


  delete_option( 'evp_youtube_api' );
  delete_option( 'evp_channels' );
  delete_option( 'evp_latest_updates' );
  delete_option( 'evp_restricted_categories' );


  //finally clear the cache
  wp_cache_flush();
