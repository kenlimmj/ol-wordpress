<?php

// Creates JS loading function called in header.php
function call_scripts() {

// LOAD ON ALL PAGES
wp_enqueue_script('jquery'); // WP default jQuery library
wp_enqueue_script('respond', '/wp-content/themes/olresponsive/js/respond.min.js', array(), null, true); // Polyfill for browsers that don't support @media-query
wp_enqueue_script('bootstrap', '/wp-content/themes/olresponsive/js/bootstrap.min.js', array('jquery'), '2.0.4', true); // Accompanying JS for Twitter Bootstrap

// CONDITIONAL LOADING
// if (is_category() || is_page('about-us') || is_page('docs')) {
// wp_enqueue_script('list', '/wp-content/themes/olresponsive/js/list.min.js', array(), null, true); // Script for realtime list search
// wp_enqueue_script('subnav', '/wp-content/themes/olresponsive/js/subnav.js', array('jquery'), null, true);  } // jQuery script to collapse and make persistent subnav bar
if (is_page('linking') || is_page('docs')) {
wp_enqueue_script('prettify', '/wp-content/themes/olresponsive/js/prettify.js', array('jquery'), null, true); // Google Code Prettifier
echo '<link rel="stylesheet" href="/wp-content/themes/olresponsive/prettify.css">'; }
if (is_single() || is_home() || is_page('standards') || is_author() || is_page('ol2')) {
wp_enqueue_script('jwplayer', '/wp-content/themes/olresponsive/jwplayer/jwplayer.js', array('jquery'), null, false); } // JWPlayer core JS
if (is_single()) {
wp_enqueue_script('lecture', '/wp-content/themes/olresponsive/js/lecture.js', array('jquery'), null, true); } // JWEmbedder configuration file for lecture pages
if (is_home()) {
echo '<link rel="stylesheet" href="/wp-content/themes/olresponsive/all.css">'; }
}

// Calls YouTuBe API to get Video Title and Description based on ID
function get_youtube($link_ID) {
  $url = "http://gdata.youtube.com/feeds/api/videos/$link_ID?v=2&alt=json";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  return $foo=objectToArray(json_decode($output));
}

// Prevent WP from automatically encapsulating line-breaks and <img> tags with <p>
remove_filter( 'the_content', 'wpautop' );

// Edit names in administration page menus so they make sense
function edit_admin_menus() {
global $menu;
global $submenu;

// $menu[5][0] = 'Content';
// $submenu['edit.php'][5][0] = 'All Lectures/Blogposts';
// $submenu['edit.php'][10][0] = 'Add a Lecture/Blogpost';
// $submenu['edit.php'][15][0] = 'Subjects and Topics';
remove_submenu_page('edit.php','edit-tags.php?taxonomy=post_tag');

$menu[70][0] = 'Staff and Lecturers'; // Change Posts to Recipes
$submenu['users.php'][5][0] = 'All Staff/Lecturers';
$submenu['user-new.php'][10][0] = 'Add Staff/Lecturer';
}
add_action( 'admin_menu', 'edit_admin_menus' );

// Remove useless menus from the administration page
function remove_menus () {
global $menu;
    $restricted = array(__('Dashboard'), __('Links'), __('Comments'));
    end ($menu);
    while (prev($menu)){
        $value = explode(' ',$menu[key($menu)][0]);
        if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
    }
}
add_action('admin_menu', 'remove_menus');

// Finds top level category parent by searching iteratively until there is no higher parent
function pa_category_top_parent_id ($catid) {
 while ($catid) {
  $cat = get_category($catid); // get the object for the catid
  $catid = $cat->category_parent; // assign parent ID (if exists) to $catid
  // the while loop will continue whilst there is a $catid
  // when there is no longer a parent $catid will be NULL so we can assign our $catParent
  $catParent = $cat->cat_ID;
 }
return $catParent;
}

// Convert stdclassObject to multidimensional array
function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        }
        else {
            // Return array
            return $d;
        }
        }

// Remove meta junk and translation JS from head
function remove_l10n_js(){
  if (!is_admin()){
    wp_deregister_script('l10n');
  }
}
add_action('wp_print_scripts', 'remove_l10n_js');

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

?>