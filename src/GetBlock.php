<?php

namespace EasyVideoPublisher;

/**
 *
 */
class GetBlock
{

	/**
	 * Youtube Block
	 * @param string $vid the video ID
	 * @return string
	 */
	public static function youtube( $vid = null ){
		$yt_block = '<!-- wp:core-embed/youtube {"url":"'.$vid.'","type":"video","providerNameSlug":"youtube","className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
		<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio">
		<div class="wp-block-embed__wrapper">
		'.$vid.'
		</div></figure>
		<!-- /wp:core-embed/youtube -->';
		return $yt_block;
	}

	public static function instagram( $embed_url = null ){
		$ig_block  = '<figure class="wp-block-embed-instagram wp-block-embed is-type-rich is-provider-instagram">';
		$ig_block .= '<div class="wp-block-embed__wrapper">';
		$ig_block .= $embed_url;
		$ig_block .= '</div></figure>';
		return $ig_block;
	}

	/**
	 * Html Block
	 * @param string $vid the video ID
	 * @return string
	 */
	public static function html( $vid = null ){
		$html_block  = '<!-- wp:html -->';
		$html_block .= '<iframe src="https://www.youtube.com/embed/'.$vid.'?feature=oembed"';
		$html_block .= 'width="780" height="439" frameborder="0"';
		$html_block .= 'allowfullscreen="allowfullscreen"></iframe>';
		$html_block .= '<!-- /wp:html -->';
		return $html_block;
	}

}
