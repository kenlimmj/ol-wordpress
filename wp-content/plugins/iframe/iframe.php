<?php
/*
Plugin Name: [iframe]
Plugin URI: http://web-profile.com.ua/wordpress/plugins/iframe/
Description: [iframe src="http://player.vimeo.com/video/3261363" width="100%" height="480"] shortcode
Version: 2.2
Author: webvitaly
Author Email: webvitaly(at)gmail.com
Author URI: http://web-profile.com.ua/wordpress/
*/

if ( !function_exists( 'iframe_embed_shortcode' ) ) :

	function iframe_enqueue_script() {
		wp_enqueue_script( 'jquery' );
	}
	add_action('wp_enqueue_scripts', 'iframe_enqueue_script');
	
	function iframe_embed_shortcode($atts, $content = null) {
		$defaults = array(
			'width' => '100%',
			'height' => '480',
			'scrolling' => 'no',
			'class' => 'iframe-class',
			'frameborder' => '0'
		);
		// add defaults
		foreach ($defaults as $default => $value) {
			if (!array_key_exists($default, $atts)) {
				$atts[$default] = $value;
			}
		}
		// special case maps
		$src_cut = substr($atts["src"], 0, 35);
		if(strpos($src_cut, 'maps.google' )){
			$atts["src"] .= '&output=embed';
		}
		$html = '';
		if( isset( $atts["same_height_as"] ) ){
			$same_height_as = $atts["same_height_as"];
		}else{
			$same_height_as = '';
		}
		
		if( $same_height_as != '' ){
			$atts["same_height_as"] = '';
			if( $same_height_as != 'content' ){ // we are setting the height of the iframe like as target element
				if( $same_height_as == 'document' || $same_height_as == 'window' ){ // remove quotes for window or document selectors
					$target_selector = $same_height_as;
				}else{
					$target_selector = '"' . $same_height_as . '"';
				}
				$html .= '
					<script>
					jQuery(document).ready(function($) {
						var target_height = $(' . $target_selector . ').height();
						$("iframe.' . $atts["class"] . '").height(target_height);
						//alert(target_height);
					});
					</script>
				';
			}else{ // set the actual height of the iframe (show all content of the iframe without scroll)
				$html .= '
					<script>
					jQuery(document).ready(function($) {
						$("iframe.' . $atts["class"] . '").bind("load", function() {
							var embed_height = $(this).contents().find("body").height();
							$(this).height(embed_height);
						});
					});
					</script>
				';
			}
		}
        $html .= "\n".'<!-- Iframe plugin v.2.2 (wordpress.org/extend/plugins/iframe/) -->'."\n";
		$html .= '<iframe';
        foreach ($atts as $attr => $value) {
			if( $attr != 'same_height_as' ){ // remove some attributes
				if( $value != '' ) { // adding all attributes
					$html .= ' ' . $attr . '="' . $value . '"';
				} else { // adding empty attributes
					$html .= ' ' . $attr;
				}
			}
		}
		$html .= '></iframe>';
		return $html;
	}
	add_shortcode('iframe', 'iframe_embed_shortcode');
	
endif;
