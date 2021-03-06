<?php

namespace VideoPublisherlite\Post;

class GetBlock
{

	/**
	 * Youtube Block
	 *
	 * @param string $vid the video ID.
	 * @return string
	 */
	public static function youtube( $vid = null ) : string {
		$yt_block = '<!-- wp:core-embed/youtube {"url":"' . esc_attr( $vid ) . '","type":"video","providerNameSlug":"youtube","className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
		<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio">
		<div class="wp-block-embed__wrapper">
		' . esc_attr( $vid ) . '
		</div></figure>
		<!-- /wp:core-embed/youtube -->';
		return $yt_block;
	}

	/**
	 * Instagram block
	 *
	 * @param string $embed_url .
	 * @return string
	 */
	public static function instagram( $embed_url = null ) : string {
		$ig_block  = '<figure class="wp-block-embed-instagram wp-block-embed is-type-rich is-provider-instagram">';
		$ig_block .= '<div class="wp-block-embed__wrapper">';
		$ig_block .= esc_attr( $embed_url );
		$ig_block .= '</div></figure>';
		return $ig_block;
	}

	/**
	 * Html Block
	 *
	 * @param string $vid the video ID.
	 * @return string
	 */
	public static function html( $vid = null ) : string {
		$html_block  = '<!-- wp:html -->';
		$html_block .= '<iframe src="https://www.youtube.com/embed/' . esc_attr( $vid ) . '?feature=oembed"';
		$html_block .= 'width="780" height="439" frameborder="0"';
		$html_block .= 'allowfullscreen="allowfullscreen"></iframe>';
		$html_block .= '<!-- /wp:html -->';
		return $html_block;
	}

}
