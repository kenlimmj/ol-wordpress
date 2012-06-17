<?php
/*
Plugin Name: LaTeX for WordPress
Plugin URI: http://wordpress.org/extend/plugins/latex/
Description: Using MathJax and LaTex image service, this plugin provides a general solution to add and display your mathematical fourmula, no matter the visitors are visiting your blog or read from Google Reader. 
Version: 3.42
Author: zhiqiang
Author URI: http://zhiqiang.org
*/

/*  
	1.3 and higher are maintained by Zhiqiang.
    
	 Copyright 2006  Anders Dahnielson (email : anders@dahnielson.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
    Version-History:
    1.1.2:
    Tweaker: Martin Becker
    Email:   mbecker@physik.uni-wuerzburg.de
    Homepage: http://fstyle.de
    -   Edited discription of plugin, so correct tags will be shown
    -   Put inclusion of Snoopy-Class down into the class (mimetex), 
        for in some cases WP will intialize the Snoopy-Class of its own
        after we do here, causing it to not to be able to reinitialize.
        For some reason I don't understand. 
        Note: I think all this workaround using our own extra Snoopy-Class can be
        fixed by the wordpress-team by removing the if statement in their Snoopy-Class.
        That would be the case for ./wp-includes/rss-functions.php in ./wp-admin/index.php
    -   Cleaner code
    
    1.1.1:
    Tweaker: Baris Evrim Demiroz
    Email:   b.evrim /AT/ anlak /DOT/ com
    Homepage: http://www.anlak.com
    -   Switched to Snoopy
    -   Working in comments as well: Thanks to Robert Jones, he gave the code snippet for plugin to work with comments. You may wish to visit him: http://www.jonesieboy.co.uk/blog
    
    1.1.0:
    Anders Dahnielson, URI: http://dahnielson.com
    -   Anders Dahnielson's original version.
*/

function latex_for_wp_activate() {
//	if (get_option("latex_imgcss") == FALSE )
		update_option("latex_imgcss", "vertical-align: middle; border: none;");
//	if (get_option("latex_img_server") == FALSE )
		update_option("latex_img_server", "http://chart.apis.google.com/chart?cht=tx&chl=");
//	if (get_option("mathjax_server") == FALSE )	
		update_option("mathjax_server", "http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=default");
//	if (get_option("latex_cache_path") == FALSE )	
		update_option("latex_cache_path", ABSPATH."wp-content/plugins/latex/cache/");
		update_option("latex_turnoff", true);
		update_option("latex_mathjax_config", '<script type="text/x-mathjax-config"> \n'.
'        MathJax.Hub.Config({ \n' .
'                TeX: {equationNumbers: {autoNumber: ["AMS"], useLabelIds: true}}, \n' .
'                "HTML-CSS": {linebreaks: {automatic: true}}, \n' .
'                SVG: {linebreaks: {automatic: true}}\n' .
'        }); \n' .
'</script>') ;
}

register_activation_hook( __FILE__, 'latex_for_wp_activate' );
	
add_action('admin_menu', 'latex_admin_page');
function latex_admin_page() {
	if (function_exists('add_submenu_page')) {
		add_submenu_page('options-general.php',  'LaTex administrator',  'LaTex', 1, 'latex/latex-admin.php');
	}
}

$iflatexexists = false;

function decode_entities_latex($text) {
    $text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1"); #NOTE: UTF-8 does not work!
    $text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text); #decimal notation
    $text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);  #hex notation
    return $text;
}

function utf8_replaceEntity_latex($result){
	$value = (int)$result[1];
	
	return chr($value);
}

function utf8_html_entity_decode_latex($string){
	return preg_replace_callback(
		'/&#([0-9]+);/',
		'utf8_replaceEntity_latex',
		$string
	);
}

class latex_for_wp {
    // parsing the text to display tex by putting tex-images-tags into the code created by createTex
    function parseTex ($toParse) {
        // tag specification (which tags are to be replaced)
        $regex = '#\$\$(.*?)\$\$#si';
        
		$toParse = str_replace(array("\(", "\)", "\[", "\]", "[latex]", "[tex]", "[/latex]", "[/tex]"), array("$$", "$$", "$$!", "$$", "$$", "$$", "$$", "$$"), $toParse);
		return preg_replace_callback($regex, array(&$this, 'createTex'), $toParse);
    }
    
    // reading the tex-expression and create an image and a image-tag representing that expression
    function createTex($toTex) {
    	$formula_text = $toTex[1];
		$imgtext=false;
		if(substr($formula_text, -1) == "!")	{
if (substr($formula_text, 0, 1) == "!")
			return "<code class='tex2jax_ignore'>\[".substr($formula_text, 1, -1)."\]</code>";
else
			return "<code class='tex2jax_ignore'>\(".substr($formula_text, 0, -1)."\)</code>";
}
		if(substr($formula_text, 0, 1) == "!"){
			$imgtext=true;
			$formula_text=substr($formula_text, 1);
		}
		if (get_option('latex_img_server') != "") {
			$formula_hash = md5($formula_text);
			$formula_filename = 'tex_'.$formula_hash.'.gif';

			$cache_path = ABSPATH . '/wp-content/plugins/latex/cache/';
			$cache_formula_path = $cache_path . $formula_filename;
			$cache_url = get_bloginfo('wpurl') . '/wp-content/plugins/latex/cache/';
			$cache_formula_url = $cache_url . $formula_filename;
		
			if ( !is_file($cache_formula_path) || filesize($cache_formula_path) < 10) {
				if (!class_exists('Snoopy')) 
					require_once (ABSPATH.'/wp-includes/class-snoopy.php');
				
				$snoopy = new Snoopy;
				$formula_text_html = str_replace('%C2%A0', '%20', 
	rawurlencode(html_entity_decode(preg_replace('/\\\\label{.*?}/', '', $formula_text))));
				$snoopy->fetch(get_option('latex_img_server').$formula_text_html);  
				if (strlen($snoopy->results) < 10)
				   $snoopy->fetch('http://www.quantnet.com/cgi-bin/mathtex.cgi?'.rawurlencode(($formula_text)));		   
				$cache_file = fopen($cache_formula_path, 'w');
				fputs($cache_file, $snoopy->results);
				
				fclose($cache_file);
			}
			
			$size = getimagesize($cache_formula_path);
			$height = $size[1];
			$padding = "";
			if ($height <= 10) $padding = "padding-bottom:2px;";
			else if ($height <= 14) $padding = "padding-bottom:1px;";
		} else {
		
		}


		global $iflatexexists;
		$iflatexexists = true;
		$formula_text = decode_entities_latex(utf8_decode(html_entity_decode($formula_text)));
		$formula_text = utf8_html_entity_decode_latex(rawurldecode(html_entity_decode($formula_text )));
		
		// returning the image-tag, referring to the image in your cache folder 
		if($imgtext) return "<p style='text-align:center;'><span class='MathJax_Preview'>".(get_option('latex_img_server')==""?"\[".($formula_text)."\]":"<img src='$cache_formula_url' style='".get_option('latex_imgcss')."' class='tex' alt=\"".($formula_text)."\" />")."</span>".(get_option("mathjax_server") != ""?"<script type='math/tex;  mode=display'>".($formula_text)."</script>":"")."</p>";
		
		else return "<span class='MathJax_Preview'>".(get_option('latex_img_server')==""?"\(".($formula_text)."\)":"<img src='$cache_formula_url' style='".get_option('latex_imgcss')." $padding' class='tex' alt=\"".($formula_text)."\" />")."</span>".(get_option("mathjax_server") != ""?"<script type='math/tex'>".($formula_text)."</script>":"");
    }
}

$latex_object = new latex_for_wp;
// this specifies where parsing should be done. one can look up further information on wordpress.org
add_filter('the_title', array($latex_object, 'parseTex'), 10001);
add_filter('the_content', array($latex_object, 'parseTex'), 10001);
add_filter('the_excerpt', array($latex_object, 'parseTex'), 10001);
add_filter('comment_text', array($latex_object, 'parseTex'), 10001);


function add_latex_mathjax_code(){
global $iflatexexists;

	if($iflatexexists && get_option("mathjax_server") !=""){
echo stripcslashes(get_option('latex_mathjax_config'));

echo '<script type="text/javascript" src="'.get_option("mathjax_server").'"></script>';
}
}

if (get_option("mathjax_server") != "")
	add_action('wp_footer','add_latex_mathjax_code');
	
$latex_qmr_work_tags = array(
	'the_title',
	'the_content',
	'the_excerpt',
	'comment_text',
	);

foreach ( $latex_qmr_work_tags as $latex_qmr_work_tag ) {
	remove_filter ($latex_qmr_work_tag, 'wptexturize');
}
?>