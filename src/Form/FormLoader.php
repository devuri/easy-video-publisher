<?php

namespace VideoPublisherlite\Form;

if ( ! defined('ABSPATH') ) exit;

class FormLoader
{

  	/**
  	 * Loading div
  	 *
  	 * @param string $name
  	 * @param array $css
  	 * @return void
  	 */
	public static function loading($name = 'update-loader', $css = array()){

		/**
		 * make the prongs (12)
		 */
		$prong = '<div class="prong"><div class="inner"></div></div>';
		echo '<div class="loading '.$name.' hidden">';
		echo '<div class="loader">';
			for ($i=0; $i <= 12 ; $i++) {
				echo $prong;
			}
		echo '</div>';
		echo '</div>';
	}

  	/**
  	 * css for the loader
  	 *
  	 * @param  array  $css
  	 * @return void
  	 */
	public static function css_style( $css = array() ){	?><style media="screen">
		.loading {
			padding: <?php echo $css['padding']; ?>;
			padding-bottom: <?php echo $css['padding-bottom']; ?>;
		}
		.loader {
		width:<?php echo $css['size']; ?>;
		height: <?php echo $css['size']; ?>;
		border-radius: 200px;
		position: relative;
		animation: rotate 0.8s steps(12, end) infinite;
		}
		.loader .prong {
		position: absolute;
		height: 50%;
		width: 16px;
		left: calc(50% - 8px);
		transform-origin: bottom;
		}
		.loader .prong .inner {
		background: #34657f;
		border-radius: 12px;
		position: absolute;
		width: 100%;
		top: 0;
		height: 50%;
		}
		.loader .prong:nth-of-type(1) {
		opacity: 0.08;
		transform: rotate(30deg);
		}
		.loader .prong:nth-of-type(2) {
		opacity: 0.16;
		transform: rotate(60deg);
		}
		.loader .prong:nth-of-type(3) {
		opacity: 0.24;
		transform: rotate(90deg);
		}
		.loader .prong:nth-of-type(4) {
		opacity: 0.32;
		transform: rotate(120deg);
		}
		.loader .prong:nth-of-type(5) {
		opacity: 0.4;
		transform: rotate(150deg);
		}
		.loader .prong:nth-of-type(6) {
		opacity: 0.48;
		transform: rotate(180deg);
		}
		.loader .prong:nth-of-type(7) {
		opacity: 0.56;
		transform: rotate(210deg);
		}
		.loader .prong:nth-of-type(8) {
		opacity: 0.64;
		transform: rotate(240deg);
		}
		.loader .prong:nth-of-type(9) {
		opacity: 0.72;
		transform: rotate(270deg);
		}
		.loader .prong:nth-of-type(10) {
		opacity: 0.8;
		transform: rotate(300deg);
		}
		.loader .prong:nth-of-type(11) {
		opacity: 0.88;
		transform: rotate(330deg);
		}
		.loader .prong:nth-of-type(12) {
		opacity: 0.96;
		transform: rotate(360deg);
		}

		@keyframes rotate {
		from {
			transform: rotate(0deg);
		}
		to {
			transform: rotate(360deg);
		}
		}
		</style><?php
	}
}
