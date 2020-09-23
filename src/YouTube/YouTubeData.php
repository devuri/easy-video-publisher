<?php

namespace VideoPublisherlite\YouTube;

class YouTubeData
{

  /**
   * Returns the YouTubeDataAPI instance.
   *
   * @return VideoPublisherlite\YouTube\YouTubeDataAPI
   */
  public static function api(){
    if ( self::api_has_key() ) {
      return new YouTubeDataAPI();
    }
    return null;

  }

  /**
   * API key check
   *
   * check if an API key has been set
   * @return bool
   */
  public static function api_has_key(){

    $apikey = (array) get_option('evp_youtube_api');

      if ( ! $apikey ) :
        return false;
      endif;

    return true;
  }

  /**
   * Get a list of the API keys
   *
   * @return string API Keys
   */
  public static function keys(){

    $keylist 	= '<h4>API Keys:</h4>';
    $keylist 	.= '<ul style="list-style: decimal;margin-left: 2em;">';
    foreach( get_option('evp_youtube_api' , array()) as $key => $time ) {
      $key = substr( $key , 0, -20 );
      $keylist 	.= '<li><strong>'.$key.'...</strong> Since '.date_i18n( get_option( 'date_format' ), $time ).'</li>';
    }
    $keylist 	.= '</ul><br>';
    return $keylist;
  }

  /**
   * Get a list of channels
   *
   * @return string list_channels
   */
  public static function list_channels(){

    // get the channels
    $evp_channels =	get_option('evp_channels' , array() );
    asort($evp_channels);

      $chanlist 	= '<h4>Channels:</h4>';
      $chanlist 	.= '<ul style="list-style: decimal;margin-left: 2em;">';
      foreach( $evp_channels as $chkey => $channel ) {
        $chanlist 	.= '<li><strong>'.$channel.'</strong></li>';
      }
      $chanlist 	.= '</ul><br>';
    return $chanlist;
  }



}
