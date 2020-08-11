<?php
namespace EasyVideoPublisher;

/**
 *
 */
class Hashtags
{

	/**
	 * Hashtags for IG
	 * use with https://www.instagram.com/explore/tags/music
	 * @param  string $string [description]
	 * @return [type]         [description]
	 */
	private static function match_tags( $string = null ) {
    $hashtags= FALSE;
    preg_match_all("/(#\w+)/u", $string, $matches);
    if ($matches) {
        $hashtagsArray = array_count_values($matches[0]);
        $hashtags = array_keys($hashtagsArray);
    }
    return $hashtags;
	}

	/**
	 * convert tags to IG links
	 * @param  [type] $content [description]
	 * @return [type]          [description]
	 */
	public static function taglinks( $content = null){
		foreach ( self::match_tags( $content ) as $key => $tagval ) {
			$tag = ltrim( $tagval , '#');
			$link[$key]  = '<a href="https://www.instagram.com/explore/tags/'.$tag.'" target="_blank" ">#';
			$link[$key] .= $tag;
			$link[$key] .= '</a>';
			$link[$key] .= '<br>';
		}
		return $link;
	}

}
