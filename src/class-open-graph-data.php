<?php

namespace EasyVideoPublisher;

/**
 *
 */
class OpenGraph {

	/**
	 * get the date
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	public static function data( $url = null ){

		if ( ! $url == null ) {

			/**
			 * setup
			 * @var [type]
			 */
			libxml_use_internal_errors(true);
			$c =  @file_get_contents($url);
			$d =  new \DomDocument();
			@$d->loadHTML($c);
			$xp = new \domxpath($d);

			/**
			 * basic open graph data
			 */
			foreach ($xp->query("//meta[@property='og:url']") as $el) {
				$da_url = $el->getAttribute("content");
				$ogurl = wp_strip_all_tags($da_url);
			}

			# Site
			foreach ($xp->query("//meta[@property='og:site_name']") as $el) {
				$da_site_name = $el->getAttribute("content");
				$ogsite= wp_strip_all_tags($da_site_name);
			}

			# Title
			foreach ($xp->query("//meta[@property='og:title']") as $el) {
				$da_title = $el->getAttribute("content");
				$ogtitle = wp_strip_all_tags($da_title);

				// image for alt
				$imagetitle_sanitized = sanitize_title($da_title);
			}

			# Image
			foreach ($xp->query("//meta[@property='og:image']") as $el) {
				$ogimage = $el->getAttribute("content");


			}

			# Description
			foreach ($xp->query("//meta[@property='og:description']") as $el) {
				$da_description = $el->getAttribute("content");
				$ogdescription = wp_strip_all_tags($da_description);
			}

			# Type
			foreach ($xp->query("//meta[@property='og:type']") as $el) {
				$da_type = $el->getAttribute("content");
				$ogtype = wp_strip_all_tags($da_type);
			}

			# Video
			foreach ($xp->query("//meta[@property='og:video:secure_url']") as $el) {
				$da_video_url = $el->getAttribute("content");
				$ogvideourl = wp_strip_all_tags($da_video_url);
			}

			/**
			 * the ogdata array
			 */
			$ogdata = array (
				'url' 		=> $ogurl,
				'site' 		=> $ogsite,
				'title' 	=> $ogtitle,
				'image' 	=> $ogimage,
				'imagetitle' 	=> $imagetitle_sanitized,
				'description' => $ogdescription,
				'type' 	=> $ogtype,
				'video' 	=> $ogvideourl,
			);
			return $ogdata;
		} else {
			return false;
		}
	}

}
