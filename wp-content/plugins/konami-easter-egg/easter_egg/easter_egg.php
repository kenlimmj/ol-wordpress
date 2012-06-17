<?php
/* 
Plugin Name: Konami Easter Egg
Plugin URI: http://www.adrian3.com/
Version: v0.1
Author: <a href="http://adrian3.com/">Adrian3</a>
Description: The Wordpress Konami Easter Egg plugin lets you add a secret YouTube video and a hidden message to your Wordpress powered website. Only people who know the secret code (by default: up up down down left right left right b a enter) can see your message (unless they are really ambitious and are sneaking around in your source code!).

*/


//Original Framework http://theundersigned.net/2006/06/wordpress-how-to-theme-options/ 
//Updated and added additional options by Jeremy Clark http://clarktech.no-ip.com/
//Hacked and Frankensteined into a plugin by Adrian Hanft http://adrian3.com/

$wp_easter_themename = "Easter Egg";
$wp_easter_shortname = "wp_easter";
$wp_easter_options = array (


array(  "name" => "Outer most div tag (IMPORTANT!): <sup>1</sup>",
		            "id" => $wp_easter_shortname."_div_name",
		            "std" => "container",
		            "type" => "text"),

array(  "name" => "Easter Egg Key: <sup>2</sup>",
			"id" => $wp_easter_shortname."_key",
			"std" => "3838404037393739666513",
			"type" => "text"),
				
array(  "name" => "YouTube Code: <sup>3</sup>",
			"id" => $wp_easter_shortname."_youtube_code",
			"std" => "x_Y4H6NSIfQ",
			"type" => "text"),

array(  "name" => "Custom CSS: <sup>4</sup>",
		            "id" => $wp_easter_shortname."_css",
		            "std" => "#easter_egg {			
			background:#fff;
			color:#666;
			text-decoration:none;
			font-size: 24px;
			text-align:center;
			}",
		    "type" => "textarea"),

array(  "name" => "Easter Egg Message:<sup>5</sup>",
		"id" => $wp_easter_shortname."_message",
		"std" => "Nice job! <br />You now have 30 lives.<br /> Use them wisely, my friend.",
		"type" => "textarea")


);

function mywp_easter_add_admin() {

    global $wp_easter_themename, $wp_easter_shortname, $wp_easter_options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($wp_easter_options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($wp_easter_options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: options-general.php?page=easter_egg.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($wp_easter_options as $value) {
                delete_option( $value['id'] ); 
                update_option( $value['id'], $value['std'] );}

            header("Location: options-general.php?page=easter_egg.php&reset=true");
            die;

        }
    }

add_options_page($wp_easter_themename." Options", "Easter Egg", 'edit_themes', basename(__FILE__), 'mywp_easter_admin');

}


function addwp_easter() {
	global $wp_easter_options;
	foreach ($wp_easter_options as $value) {
	    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } 
	      else { $$value['id'] = get_settings( $value['id'] ); } 
	}
echo '<!-- Konami Easter Egg by http://Adrian3.com -->
<link rel="stylesheet" href="'; echo get_bloginfo('wpurl'); echo '/wp-content/plugins/easter_egg/css/easter.css" type="text/css" media="screen" /> 
	<script type="text/javascript" src="http://www.google-analytics.com/ga.js"></script>
	<script type="text/javascript">
	var pageTracker = _gat._getTracker("UA-3989583-7");
	pageTracker._initData();
	pageTracker._trackPageview();
	</script>
<script src="'; echo get_bloginfo('wpurl'); echo'/wp-content/plugins/easter_egg/js/jquery.min.js" type="text/javascript"></script> 
<script type="text/javascript"> var konami = { input:"", clear:setTimeout(\'konami.clear_input()\',2000),
load: function(link) { window.document.onkeyup = function(e) { konami.input+= e ? e.keyCode : event.keyCode         
if (konami.input == "'; echo $wp_easter_key; echo'") { konami.code(link)
clearTimeout(konami.clear) }
clearTimeout(konami.clear)
konami.clear = setTimeout("konami.clear_input()",2000) } }, code: function(link) { window.location=link}, clear_input: function() { konami.input=""; clearTimeout(konami.clear); } } </script> 
<script type="text/javascript"> konami.load() </script>
<script type="text/javascript"> konami.code = function(){ $("#'; echo $wp_easter_div_name; echo '").fadeOut(function(){ $("#easter_egg").fadeIn() }) 
pageTracker._trackEvent("Easter Eggs", "Konami Code") } </script>
<style type="text/css" media="screen">'; echo $wp_easter_css; echo '} </style> '; }



function addwp_easter_content() {
	global $wp_easter_options;
	foreach ($wp_easter_options as $value) {
	    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } 
	      else { $$value['id'] = get_settings( $value['id'] ); } 
	}

	echo '<div id="easter_egg">
		<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/';
		echo $wp_easter_youtube_code;
		echo '&hl=en&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/';
		echo $wp_easter_youtube_code;
		echo '&hl=en&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object><br /> ';


	echo '<br /><p>';
	echo $wp_easter_message;
	echo '<p class="easter_credit"><a href=" http://adrian3.com/2009/05/konami-easter-egg_wordpress-plugin/" title="Konami Easter Egg brought to you by Adrian3.com">Konami Easter Egg by Adrian3.com</a></p></div>';
	
	
}



function mywp_easter_admin() {

    global $wp_easter_themename, $wp_easter_shortname, $wp_easter_options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$wp_easter_themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$wp_easter_themename.' settings reset.</strong></p></div>';
    
?>
<div class="wrap">

<p>This control panel gives you the ability to control how your Easter Egg will be displayed. For more information about this plugin, please visit the <a href="http://adrian3.com/2009/05/konami-easter-egg-wordpress-plugin/" title="Adrian3.com">adrian3.com</a>. Since this is the first version of this plugin, please <a href="http://adrian3.com/contact/" title="contact me">contact me</a> with any bugs, suggestions, or questions. Thanks for using the Konami Easter Egg Plugin, and I hope you enjoy it. <a href="http://adrian3.com/" title="-Adrian 3">-Adrian 3</a></p>
<hr><br /><br />
<h2>Customize Your Easter Egg Settings</h2>
<form method="post">

<table class="optiontable">

<?php foreach ($wp_easter_options as $value) { 
    
if ($value['type'] == "text") { ?>
        
<tr valign="top"> 
    <th scope="row"><?php echo $value['name']; ?></th>
    <td>
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" size="40" />
    </td>
</tr>
<tr><td colspan=2><hr /></td></tr>
<?php } elseif ($value['type'] == "select") { ?>

    <tr valign="middle"> 
        <th scope="top"><?php echo $value['name']; ?></th>
        <td>
            <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                <option<?php if ( get_settings( $value['id'] ) == $option) { echo ' selected="selected"'; }?>><?php echo $option; ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>
<?php } elseif ($value['type'] == "radio") { ?>

    <tr valign="middle"> 
        <th scope="top"><?php echo $value['name']; ?></th>
        <td>
                <?php foreach ($value['options'] as $option) { ?>
      <?php echo $option; ?><input name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php echo $option; ?>" <?php if ( get_settings( $value['id'] ) == $option) { echo 'checked'; } ?>/> &nbsp;&nbsp;&nbsp;
<?php } ?>
        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>
<?php } elseif ($value['type'] == "textarea") { ?>

    <tr valign="middle"> 
        <th scope="top"><?php echo $value['name']; ?></th>
        <td>
            <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="40" rows="16"/><?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>
</textarea>

        </td>
    </tr>
<tr><td colspan=2><hr /></td></tr>
<?php } ?>
<?php 
}
?>
</table>
<p class="submit">
<input name="save" type="submit" value="Save changes" />    
<input type="hidden" name="action" value="save" />
</p>
</form>
<form method="post">
<p class="submit">
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
<h2>Help</h2>
<p><strong>1. Outer Most DIV Tag</strong><br />
	This is specific to your theme. It should be set as the first 
		div id after the opening body tag. This plugin will not work without completing this important step! </p>

<p>2. <strong>Easter Egg Key</strong><br />	
	This is the code for the "key" that unlocks your Easter egg. By default, the key is the infamous Konami code. In other words you have to enter "up up down down left right left right b a enter" to reveal the easter egg.</p>

<p>You can change this to anything you like. Simply replace the numbers with the code representing your message. The Chart below shows what numbers represent the various characters. Do not use commas or spaces between the numbers. This needs to be one long string of numbers in order to work. For example, if you wanted the key for your easter egg to be "go", the corresponding numbers would be 71 (G) and 79 (O). You would enter 7179 in the box.</p>
				<table bgcolor="#CCCCCC">

				  <tr>
				    <td colspan="3"><strong>ASCII Code Chart</strong></td>
				  </tr>
				  <tr>
				    <td width="59">13</td>
				    <td width="97">Enter</td>
				    <td width="97">&nbsp;</td>
				  </tr>

				  <tr>
				      <td>16</td>
				    <td>Shift</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>17</td>
				    <td>Ctrl</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>18</td>
				    <td>Alt</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>19</td>
				    <td>Pause/break</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>20</td>
				    <td>Caps lock</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>27</td>
				    <td>Escape</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>32</td>
				    <td>Spacebar</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>33</td>
				    <td>Page up</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>34</td>
				    <td>Page down</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>35</td>
				    <td>End</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>36</td>
				    <td>Home</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>37</td>
				    <td>Left</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>38</td>
				    <td>Up</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>39</td>
				    <td>Right</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>40</td>
				    <td>Down</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>45</td>
				    <td>Insert</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>46</td>
				    <td>Delete</td>
				    <td>&nbsp;</td>
				  </tr>
				  <tr>
				    <td>48</td>
				    <td>0</td>
				    <td>)</td>
				  </tr>
				  <tr>
				    <td>49</td>
				    <td>1</td>
				    <td>!</td>
				  </tr>
				  <tr>
				    <td>50</td>
				    <td>2</td>
				    <td>@</td>
				  </tr>
				  <tr>
				    <td>51</td>
				    <td>3</td>
				    <td>#</td>
				  </tr>
				  <tr>
				    <td>52</td>
				    <td>4</td>
				    <td>$</td>
				  </tr>
				  <tr>
				    <td>53</td>
				    <td>5</td>
				    <td>%</td>
				  </tr>
				  <tr>
				    <td>54</td>
				    <td>6</td>
				    <td>^</td>
				  </tr>
				  <tr>
				    <td>55</td>
				    <td>7</td>
				    <td>&amp;</td>
				  </tr>
				  <tr>
				    <td>56</td>
				    <td>8</td>
				    <td>*</td>
				  </tr>
				  <tr>
				    <td>57</td>
				    <td>9</td>
				    <td>(</td>
				  </tr>
				  <tr>
				    <td>65</td>
				    <td>A</td>
				    <td>a</td>
				  </tr>
				  <tr>
				    <td>66</td>
				    <td>B</td>
				    <td>b</td>
				  </tr>
				  <tr>
				    <td>67</td>
				    <td>C</td>
				    <td>c</td>
				  </tr>
				  <tr>
				    <td>68</td>
				    <td>D</td>
				    <td>d</td>
				  </tr>
				  <tr>
				    <td>69</td>
				    <td>E</td>
				    <td>e</td>
				  </tr>
				  <tr>
				    <td>70</td>
				    <td>F</td>
				    <td>f</td>
				  </tr>
				  <tr>
				    <td>71</td>
				    <td>G</td>
				    <td>g</td>
				  </tr>
				  <tr>
				    <td>72</td>
				    <td>H</td>
				    <td>h</td>
				  </tr>
				  <tr>
				    <td>73</td>
				    <td>I</td>
				    <td>i</td>
				  </tr>
				  <tr>
				    <td>74</td>
				    <td>J</td>
				    <td>j</td>
				  </tr>
				  <tr>
				    <td>75</td>
				    <td>K</td>
				    <td>k</td>
				  </tr>
				  <tr>
				    <td>76</td>
				    <td>L</td>
				    <td>l</td>
				  </tr>
				  <tr>
				    <td>77</td>
				    <td>M</td>
				    <td>m</td>
				  </tr>
				  <tr>
				    <td>78</td>
				    <td>N</td>
				    <td>n</td>
				  </tr>
				  <tr>
				    <td>79</td>
				    <td>O</td>
				    <td>o</td>
				  </tr>
				  <tr>
				    <td>80</td>
				    <td>P</td>
				    <td>p</td>
				  </tr>
				  <tr>
				    <td>81</td>
				    <td>Q</td>
				    <td>q</td>
				  </tr>
				  <tr>
				    <td>82</td>
				    <td>R</td>
				    <td>r</td>
				  </tr>
				  <tr>
				    <td>83</td>
				    <td>S</td>
				    <td>s</td>
				  </tr>
				  <tr>
				    <td>84</td>
				    <td>T</td>
				    <td>t</td>
				  </tr>
				  <tr>
				    <td>85</td>
				    <td>U</td>
				    <td>u</td>
				  </tr>
				  <tr>
				    <td>86</td>
				    <td>V</td>
				    <td>v</td>
				  </tr>
				  <tr>
				    <td>87</td>
				    <td>W</td>
				    <td>w</td>
				  </tr>
				  <tr>
				    <td>88</td>
				    <td>X</td>
				    <td>x</td>
				  </tr>
				  <tr>
				    <td>89</td>
				    <td>Y</td>
				    <td>y</td>
				  </tr>
				  <tr>
				    <td>90</td>
				    <td>Z</td>
				    <td>z</td>
				  </tr>
				</table>				
<p><strong>3. YouTube Code</strong><br />				
	The YouTube video that gets shown on your Easter Egg is specified with the code you enter in this box. If you are unsure of how to get this code, here are some directions. First, find the video you want to show on YouTube. Below the video you will see a link that says "share." Click on this link and it will show you a URL for the video. The string of text at the end of this URL after the "=" is the code you need to copy and paste into the box above.</p>

<p><strong>4. Custom CSS</strong><br />
	You can customize the CSS of your Easter Egg page by adding css in this box. Use this to redefine the #easter_egg CSS.</p> 
<p><strong>5. Easter Egg Message</strong><br />
	This is the message that will display on your easter egg page. Type anything you want, but be careful about pasting HTML in this box because it will probably cause problems.</p>

<p><strong>How this plugin works</strong><br />
	This plugin uses adds a javascript to the header of your pages. When the correct keys are pressed the javascript will switch the visibility of everything in the tag you specified in step 1 to off. It will then switch the visibility of everything inside the "easter_egg" div to on. The "easter_egg" content is placed in the footer of your page. If you view the source code of your page you will see it, so it isn't completely hidden. In other words, if someone is snooping around in your HTML, they will be able to see the "hidden text" without typing your key code.</p>

<p><strong>Troubleshooting</strong><br />
If the plugin isn't working, here are a couple things to check. First, make sure that you entered the outermost div tag used in your theme. The second thing to check is that your theme uses a "footer.php" file. Open this file and make sure that there is a line that says: &lt;?php do_action('wp_footer'); ?&gt;. If this line is missing add it before the closing body tag. If that doesn't fix your problem it is probably a plugin conflict or some other issue with your theme. Feel free to <a href="http://adrian3.com/contact/" title="contact me">contact me</a> and I will try to help you out.</p>

<p><strong>Credit</strong><br />
This plugin was created by <a href="http://adrian3.com/" title="Adrian Hanft">Adrian Hanft</a> using the Konami-JS code written <a href="http://www.georgemandis.com/" title="George Mandis">George Mandis</a>. Konami-JS is available on <a href="http://code.google.com/p/konami-js/" title="Google Code">Google Code</a> under a <a href="http://dev.perl.org/licenses/" title="Artistic License/GPL">Artistic License/GPL</a>.</p>
</form>

<?php
}
add_action('admin_menu', 'mywp_easter_add_admin');
add_action('wp_head', 'addwp_easter');
add_action('wp_footer', 'addwp_easter_content');

 ?>
