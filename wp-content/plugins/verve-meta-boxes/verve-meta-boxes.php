<?php
/*
Plugin Name: Verve Meta Boxes
Plugin URI: http://www.avenueverve.com/verve-meta-boxes/
Description: Provides advanced custom fields interface for Posts and Pages
Version: 1.2.9
Author: AvenueVERVE
Author URI: http://www.avenueverve.com

----------------------------------------------

Copyright 2011 AvenueVERVE, LLC (http://www.avenueverve.com)

This file is part of Verve Meta Boxes.

Verve Meta Boxes is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Verve Meta Boxes is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Verve Meta Boxes.  If not, see <http://www.gnu.org/licenses/>.

*/

require_once(dirname(__FILE__)."/inc/utility_MTUtil.php");
require_once(dirname(__FILE__)."/inc/utility_dirify.php");

define('VERVE_POST_EDIT_NONCE_ACTION',      'verve_save_post');
define('VERVE_POST_EDIT_NONCE_NAME_PREFIX', 'verve_post_edit_');

global $post;
global $wpdb;
global $verve_meta_boxes_version;
global $field_table_name;
global $box_table_name;
global $field_types;


$verve_meta_boxes_version 	= "1.2.9";
$field_table_name 			= $wpdb->prefix."verve_meta_fields";
$box_table_name 			= $wpdb->prefix."verve_meta_boxes";

//$field_types 				= array('text','textarea','radio','checkbox','select','image','file','date','time','datetime');
function verve_init_field_types_filter($types) {
	return array_merge($types, array('text','textarea','radio','checkbox','select','image','file','date','time','datetime'));
}
add_filter('verve_init_field_types', 'verve_init_field_types_filter');

function verve_get_field_types() {
	return apply_filters('verve_init_field_types', array());
}


add_action('wp_ajax_verve_process_sortable', 'verve_process_sortable_callback');

function verve_process_sortable_callback() {
	
	global $wpdb;
	global $field_table_name;
	
	$order = $_POST['order'];
	$order = explode("&",$order);
	$listItems = array();
	foreach($order as $o){
		$o = explode("=",$o);
		$listItems[] = $o[1];
	}

	foreach ($listItems as $position => $item) :
		$query = "UPDATE `".$field_table_name."` SET `verve_meta_field_sequence` = $position WHERE `verve_meta_field_id` = $item";
		$wpdb->query($query);
	endforeach;

	echo '<div class="updated">Sort order updated.</div>';

	die();
}

add_action('wp_ajax_verve_unlink_file', 'verve_unlink_file_callback');

function verve_unlink_file_callback(){
	global $wpdb; 

	if( $_POST['data'] ){

		$data = explode("-",$_POST['data']);
		$key 		= $data[0];
		$post_id 	= $data[1];
		delete_post_meta($post_id, $key); 
	
	}

}


add_action('wp_ajax_verve_delete_field', 'verve_delete_field_callback');

function verve_delete_field_callback(){
	global $wpdb; 
	global $field_table_name;

	if( $_POST['data'] ){

		$data = explode("-",$_POST['data']);
		$key 		= $data[0];
		$field_id 	= $data[1];
		
		$query = "delete from $wpdb->postmeta where meta_key = '$key'";
		$wpdb->query($query);

		$query = "delete from $field_table_name where verve_meta_field_id = $field_id";
		$wpdb->query($query);
		
		echo '<div class="updated">Field deleted.</div>';
	
	}

}


add_action('wp_ajax_verve_editable', 'verve_editable_callback');

function verve_editable_callback(){
	global $wpdb; 
	global $field_table_name;
	global $box_table_name;

	$element 		= explode("-",$_POST['id']);
	$column_name 	= $element[0];
	$row_id 		= $element[1];

	$search 		= array(', ',',','  ');
	$replace 		= array(',',', ',' ');
	$value			= addslashes(str_replace($search,$replace,$_POST['value']));
	$value			= trim($value,' ,');
	
	if(strpos($column_name,'verve_meta_box')>-1){
		$query = "update $box_table_name set ".$column_name."  = '".$value."' where verve_meta_box_id = ".$row_id;
	}else{
		$query = "update $field_table_name set ".$column_name."  = '".$value."' where verve_meta_field_id = ".$row_id;
	}

	$wpdb->query($query);
	echo stripslashes($value);
	
	die();

}


function verve_meta_boxes_install() {

	global $wpdb;
	global $verve_meta_boxes_version;
	global $field_table_name;
	global $box_table_name;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
	
	$field_sql = "CREATE TABLE " . $field_table_name . " (
			verve_meta_field_id bigint(20) NOT NULL auto_increment,
			verve_meta_field_box_id bigint(20) NOT NULL,
			verve_meta_field_key tinytext NOT NULL,
			verve_meta_field_type tinytext NOT NULL,
			verve_meta_field_options text,
			verve_meta_field_description text,
			verve_meta_field_name tinytext NOT NULL,
			verve_meta_field_sequence tinyint default '0',
			UNIQUE KEY  (verve_meta_field_id)
		) DEFAULT CHARSET=utf8;";


	$box_sql = "CREATE TABLE " . $box_table_name . " (
			verve_meta_box_id bigint(20) NOT NULL auto_increment,
			verve_meta_box_key tinytext NOT NULL,
			verve_meta_box_context tinytext,
			verve_meta_box_title text NOT NULL,
			UNIQUE KEY  (verve_meta_box_id)
		) DEFAULT CHARSET=utf8;";



	if($wpdb->get_var("show tables like '$field_table_name'") != $field_table_name) {

	  dbDelta($field_sql);
	  update_option( "verve_meta_boxes_version", $verve_meta_boxes_version );

	}

	if($wpdb->get_var("show tables like '$box_table_name'") != $box_table_name) {

	  dbDelta($box_sql);
	  update_option( "verve_meta_boxes_version", $verve_meta_boxes_version );

	}

	// in case of upgrading
	$installed_ver = get_option( "verve_meta_boxes_version" );
	if( $installed_ver != $verve_meta_boxes_version ) {

	  dbDelta($field_sql);
	  dbDelta($box_sql);
	  update_option( "verve_meta_boxes_version", $verve_meta_boxes_version );

	}

}

register_activation_hook(__FILE__,'verve_meta_boxes_install');

if(!function_exists('verve_get_post_meta')){

	function verve_flatten($array){
		if(is_array($array)){
			$v = array();
			foreach($array as $key => $value){
				//var_dump($value);
				//echo "<br />";
				if(is_array($value) && count($value) > 1){
					$v[$key] = $value;
				}else{
					$v[$key] = $value[0];
				}
			}
			return $v;
		}
		return $array;
	}
	
	function verve_get_post_meta($ID){
		$meta = verve_flatten(get_post_custom($ID));
		return $meta;
	}

}


function verve_create_meta_boxes() { 

	global $wpdb;
	global $box_table_name;

	$query = "select * from $box_table_name";
	$boxes = $wpdb->get_results($query);

	//var_dump($boxes);

	if ( function_exists('add_meta_box') ) { 
		foreach($boxes as $box){
			$args = array("box_id" => $box->verve_meta_box_id);
			//var_dump($args);
			$contexts = unserialize($box->verve_meta_box_context);
			if(count($contexts)>0){
			foreach($contexts as $c){
				add_meta_box( $box->verve_meta_box_key, $box->verve_meta_box_title, 'verve_meta_box_content', $c, 'normal', 'high', $args );
			}
			}
		}
	} 
} 


function verve_meta_box_content($post,$box){


	global $wpdb;
	global $post;
	global $field_table_name;
	
	$box_id = $box['args']['box_id'];

	$query = "select * from $field_table_name where verve_meta_field_box_id = $box_id order by verve_meta_field_sequence asc";
	//echo $query;
	$fields = $wpdb->get_results($query);

	if(!vmb_is_empty($fields)){
	
	echo '<div class="verve_meta_box_content">';

    wp_nonce_field(VERVE_POST_EDIT_NONCE_ACTION, VERVE_POST_EDIT_NONCE_NAME_PREFIX . $post->ID);
    
	foreach($fields as $f){

		$description = '';
		if($f->verve_meta_field_description != ''){ $description = '<img src="'.plugins_url().'/verve-meta-boxes/icons/note.png" alt="" border="0" class="sticky-note" /><span class="sticky-note-content">'.$f->verve_meta_field_description.'</span>'; }
		

		if(
			$f->verve_meta_field_type=='date' ||
			$f->verve_meta_field_type=='time' ||
			$f->verve_meta_field_type=='datetime'
			
		){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
			$date_value 	= ($meta_box_value!='') ? date("Y-m-d",strtotime($meta_box_value)) : '' ;
			$hour 			= ($meta_box_value!='') ? date("g",strtotime($meta_box_value)) : '' ; // hours calculated on 12 hour clock
			$minute 		= ($meta_box_value!='') ? date("i",strtotime($meta_box_value)) : '' ;
			$ampm 			= ($meta_box_value!='') ? date("A",strtotime($meta_box_value)) : '' ;

			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
			//echo $meta_box_value;

			if(
				$f->verve_meta_field_type=='date' ||
				$f->verve_meta_field_type=='datetime'
			){
				echo '<input name="'.$f->verve_meta_field_key.'-datepart" type="text" value="'.$date_value.'" class="date-pick" />';
			}

			if(
				$f->verve_meta_field_type=='time' ||
				$f->verve_meta_field_type=='datetime'
			){
				echo '<select name="'.$f->verve_meta_field_key.'-hourpart">';
				for($h=0;$h<=12;$h++){
					$displayhour = ($h<10) ? '0'.$h : $h ;
					if($h == $hour){
						echo '<option value="'.$displayhour.'" selected="selected">'.$displayhour.'</option>';
					}else{
						echo '<option value="'.$displayhour.'">'.$displayhour.'</option>';
					}
				}
				echo '</select>';
				echo '<select name="'.$f->verve_meta_field_key.'-minutepart">';
				for($m=0;$m<60;$m++){
					$displaymin = ($m<10) ? '0'.$m : $m ;
					if($m == $minute){
						echo '<option value="'.$displaymin.'" selected="selected">'.$displaymin.'</option>';
					}else{
						echo '<option value="'.$displaymin.'">'.$displaymin.'</option>';
					}
				}
				echo '</select>';
				echo '<select name="'.$f->verve_meta_field_key.'-ampmpart">';
				if('AM' == $ampm){
					echo '<option value="AM" selected="selected">AM</option>';
				}else{
					echo '<option value="AM">AM</option>';
				}
				if('PM' == $ampm){
					echo '<option value="PM" selected="selected">PM</option>';
				}else{
					echo '<option value="PM">PM</option>';
				}
				echo '</select>';

			}


			echo '</p>';
		}

		if($f->verve_meta_field_type=='text'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
			echo '<input class="widefat verve-textfield" name="'.$f->verve_meta_field_key.'" type="text" value="'.esc_attr($meta_box_value).'" /></p>';
		}

		if($f->verve_meta_field_type=='textarea'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
			echo '<textarea class="widefat verve-textarea" name="'.$f->verve_meta_field_key.'" rows="10" cols="35">'.esc_attr($meta_box_value).'</textarea></p>';
		}

		if($f->verve_meta_field_type=='select'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
			$options = explode(",",$f->verve_meta_field_options);
			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
			echo '<select name="'.$f->verve_meta_field_key.'">';
			echo '<option value="">select...</option>';
			foreach($options as $o){ $o = trim($o);
				if($meta_box_value == $o){
					echo '<option value="'.$o.'" selected="selected">'.$o.'</option>';
				}else{
					echo '<option value="'.$o.'">'.$o.'</option>';
				}
			}
			echo '</select></p>';
		}

		if($f->verve_meta_field_type=='radio'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
			if($meta_box_value==''){ $meta_box_value = 'no'; }
			$options = array("yes","no");
			echo '<ul id="'.$f->verve_meta_field_key.'" class="verve_meta_radio"><li><p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
			foreach($options as $o){
				if(is_array($meta_box_value)){
					if(in_array($o,$meta_box_value)){
						echo '<span class="radio '.$o.'"><input name="'.$f->verve_meta_field_key.'" type="radio" value="'.$o.'" checked="checked" /> '.$o.'</span>';
					}else{
						echo '<span class="radio '.$o.'"><input name="'.$f->verve_meta_field_key.'" type="radio" value="'.$o.'" /> '.$o.'</span>';
					}
				}else{
					if($o == $meta_box_value){
						echo '<span class="radio '.$o.'"><input name="'.$f->verve_meta_field_key.'" type="radio" value="'.$o.'" checked="checked" /> '.$o.'</span>';
					}else{
						echo '<span class="radio '.$o.'"><input name="'.$f->verve_meta_field_key.'" type="radio" value="'.$o.'" /> '.$o.'</span>';
					}
				}
			}
			echo '</li></ul></p>';
		}

		if($f->verve_meta_field_type=='checkbox'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, false);
			$options = explode(",",$f->verve_meta_field_options);
			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
			echo '<ul id="'.$f->verve_meta_field_key.'" class="verve_meta_checkbox">';
			foreach($options as $o){ $o = trim($o);
				if(is_array($meta_box_value)){
					if(in_array($o,$meta_box_value)){
						echo '<li><input name="'.$f->verve_meta_field_key.'[]" type="checkbox" value="'.$o.'" checked="checked" /> '.$o.'</li>';
					}else{
						echo '<li><input name="'.$f->verve_meta_field_key.'[]" type="checkbox" value="'.$o.'" /> '.$o.'</li>';
					}
				}else{
					if(!$post->ID && $o == $meta_box_value){
						echo '<li><input name="'.$f->verve_meta_field_key.'[]" type="checkbox" value="'.$o.'" checked="checked" /> '.$o.'</li>';
					}elseif($o == $meta_box_value){
						echo '<li><input name="'.$f->verve_meta_field_key.'[]" type="checkbox" value="'.$o.'" checked="checked" /> '.$o.'</li>';
					}else{
						echo '<li><input name="'.$f->verve_meta_field_key.'[]" type="checkbox" value="'.$o.'" /> '.$o.'</li>';
					}
				}
			}
			echo '</ul></p>';
		}

		if($f->verve_meta_field_type=='image'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);

			if(defined('blog_id') && $blog_id !== 1){
				global $blog_id;
				global $current_site;
				$mu_site_url 		= $current_site->domain;
				$verve_mu_site_url 	= get_bloginfo('url');
				$new_meta_box_value = str_replace($verve_mu_site_url.'/files/','http://'.$mu_site_url.'/blogs.dir/'.$blog_id.'/files/',$meta_box_value);
				// If it's an MU setup use the MU upload structure
				$meta_box_value = $new_meta_box_value;
			}

			if( !vmb_is_empty($meta_box_value) ){
				echo '<div><img src="'.plugins_url().'/verve-meta-boxes/tools/timthumb.php?src='.$meta_box_value.'&w=180&zc=1&q=95" alt="" />';
				echo '<img src="'.plugins_url().'/verve-meta-boxes/icons/delete.png" alt="'.$f->verve_meta_field_key.'-'.$post->ID.'" class="deletefile" width="16" height="16" border="0" /></div>';
			}
			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label></p>';
			echo '<input name="'.$f->verve_meta_field_key.'" type="file" />';
		}


		if($f->verve_meta_field_type=='file'){
			$meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
			if( !vmb_is_empty($meta_box_value) ){
				$filename = substr($meta_box_value,strrpos($meta_box_value,"/")+1);
				echo '<div><a target="_blank" href="'.$meta_box_value.'"><img src="'.plugins_url().'/verve-meta-boxes/icons/page_mag_32.png" alt="" /></a> <strong>'.$filename.'</strong>';
				echo '<img src="'.plugins_url().'/verve-meta-boxes/icons/delete.png" alt="'.$f->verve_meta_field_key.'-'.$post->ID.'" class="deletefile" width="16" height="16" border="0" /></div>';
			}
			echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label></p>';
			echo '<input name="'.$f->verve_meta_field_key.'" type="file" />';
		}

		do_action('verve_meta_box_content', $f);		
		
	} //end field loop

	echo '</div>';

	} // end if fields


}





/*
FOR ADVICE ON HOW TO WORK WITH METABOXES
http://andrewferguson.net/2008/09/26/using-add_meta_box/
http://codex.wordpress.org/Function_Reference/add_meta_box

add_meta_box('id', 'title', 'callback', 'page', 'context', 'priority');
string $id String for use in the 'id' attribute of tags.
string $title Title of the meta box
string $callback Function that fills the box with the desired content. The function should echo its output.
string $page The type of edit page on which to show the box (post, page, link)
string $context The context within the page where the boxes should show ('normal', 'advanced')
string $priority The priority within the context where the boxes should show ('high', 'low')
*/

function vmb_is_empty($var = '', $allow_false = false, $allow_ws = false) {

	//This function will allow you to test a variable is empty and considers the following values as empty:
	//There are two optional parameters:
	//$allow_false: setting this to true will make the function consider a boolean value of false as NOT empty. This parameter is false by default.
	//$allow_ws: setting this to true will make the function consider a string with nothing but white space as NOT empty. This parameter is false by default.

	//an unset variable -> empty	
	if( !isset($var) ){ return true; }
	
	//null -> empty
	if( is_null($var) ){ return true; }

	//0 -> NOT empty
	//'string value' -> NOT empty
	//"    " (white space) -> empty
	if( !is_array($var) ){
		if( $allow_ws == false && trim($var) == "" && !is_bool($var) ){ return true; }
	}

	//"0" -> NOT empty
	//false -> empty
	//true -> NOT empty
	if( !is_array($var) ){
		if( $allow_false === false && is_bool($var) && $var === false ){ return true; }
	}

	//array() (empty array) -> empty
	if(is_array($var)){
		if(empty($var)){ return true; }
	}

	return false;	

}



function verve_save_postdata( $post_id, $post ) {
	
	if ( empty($_POST) ) 						{ return; }
	if (@$_REQUEST['bulk_edit'])				{ return; }
	if (@$_REQUEST['action']=='inline-save')	{ return; }
	if ($post->post_type == 'revision') 		{ return; }
    
    $nonce_name = VERVE_POST_EDIT_NONCE_NAME_PREFIX . $post->ID;
    
    // nonce
    if (empty($_POST) || !wp_verify_nonce($_POST[$nonce_name], VERVE_POST_EDIT_NONCE_ACTION))
        return;
    
    // refered from admin (check_admin_referer dies on failure, so not good when other plugins trigger this)
    if (!check_admin_referer(VERVE_POST_EDIT_NONCE_ACTION, $nonce_name))
        return;
    
    
	/* 
	the reason for the post_type check is because save_post is called twice, once for the original post and once for the revision, 
	so if you don't want your code to run twice you need to run a check and stop it for the revision.
	http://alexking.org/blog/2008/09/06/wordpress-26x-duplicate-custom-field-issue
	*/

	/////////////////// RUN SECURITY CHECKS ::: ALSO DECIDE TO CHECK NONCE HERE
	if ( 'page' == @$_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ))
		return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;
	}

	global $wpdb;
	global $field_table_name;
	$query = "select * from $field_table_name";
	$fields = $wpdb->get_results($query);

	foreach($fields as $field){

		
		$key 	= '';
		$value 	= '';

		$key 		= $field->verve_meta_field_key;
		$field_type	= $field->verve_meta_field_type;

		if( $field_type == 'image' || $field_type == 'file' ){


			if( isset($_FILES[$key]) && !vmb_is_empty($_FILES[$key]['name']) ){
				
				/////////////////// HANDLE FILE UPLOAD and POPULATE VALUE VARIABLE
				$overrides 	= array('test_form' => false); //WHY DOES THIS NEED TO BE HERE?
				$image 		= wp_handle_upload($_FILES[$key],$overrides);
				$value 		= $image["url"];
				
				if( vmb_is_empty($value) ){ 
					delete_post_meta($post_id, $key); 
				}else{
					update_post_meta($post_id, $key, $value);
				}
			}
		}

		elseif( $field_type == 'date' ){
			if( !vmb_is_empty($_POST[$key.'-datepart']) ){
				/// parse date to acceptable format, date comes in M/D/Y format or any other user entered format
				$date 	= strtotime($_POST[$key.'-datepart']);
				$value 	= date("Y-m-d H:i:s",$date);
		
			}
			if( vmb_is_empty($value) ){ 
				delete_post_meta($post_id, $key); 
			}else{
				update_post_meta($post_id, $key, $value);
			}
		}

		elseif( $field_type == 'datetime' ){
			if( !vmb_is_empty($_POST[$key.'-datepart']) ){
				/// parse date to acceptable format, date comes in M/D/Y format or any other user entered format
				$datevalue 		= ( !vmb_is_empty($_POST[$key.'-datepart']) ) ? date("Y-m-d",strtotime($_POST[$key.'-datepart'])) : '';
				$hourvalue 		= $_POST[$key.'-hourpart'];
				$minutevalue 	= $_POST[$key.'-minutepart'];
				$ampmvalue 		= $_POST[$key.'-ampmpart'];
				$timestring		= $datevalue.' '.$hourvalue.':'.$minutevalue.' '.$ampmvalue;
				$time			= strtotime($timestring);
				$value 			= date("Y-m-d H:i:s",$time);
		
			}
			if( vmb_is_empty($value) ){ 
				delete_post_meta($post_id, $key); 
			}else{
				update_post_meta($post_id, $key, $value);
			}
		}

		elseif( $field_type == 'time' ){
			if( $_POST[$key.'-hourpart'] != '00' && $_POST[$key.'-minutepart'] ){
				/// parse date to acceptable format, date comes in M/D/Y format or any other user entered format
				$datevalue 		= ( !vmb_is_empty($_POST[$key.'-datepart']) ) ? date("Y-m-d",strtotime($_POST[$key.'-datepart'])) : '';
				$hourvalue 		= $_POST[$key.'-hourpart'];
				$minutevalue 	= $_POST[$key.'-minutepart'];
				$ampmvalue 		= $_POST[$key.'-ampmpart'];
				$timestring		= $datevalue.' '.$hourvalue.':'.$minutevalue.' '.$ampmvalue;
				$time			= strtotime($timestring);
				$value 			= date("Y-m-d H:i:s",$time);
		
			}
			if( vmb_is_empty($value) ){ 
				delete_post_meta($post_id, $key); 
			}else{
				update_post_meta($post_id, $key, $value);
			}
		}

		elseif( $field_type == 'checkbox' ){
			
			delete_post_meta($post_id, $key);

			if( !vmb_is_empty($_POST[$key]) && is_array($_POST[$key]) ){
				foreach($_POST[$key] as $value){
					add_post_meta($post_id, $key, $value, false);
				}
			}
			
		}
		
		else {

			if( !vmb_is_empty($_POST[$key]) ){

				$value = trim($_POST[$key]);

			}
			if( vmb_is_empty($value) ){ 
				delete_post_meta($post_id, $key); 
			}else{
				update_post_meta($post_id, $key, $value);
			}
		}
	}
}


function verve_admin_js(){

	wp_enqueue_script( array("jquery", "jquery-ui-core", "interface", "jquery-ui-sortable", "wp-lists") );

	$date = content_url() . '/plugins/verve-meta-boxes/js/date.js';
	wp_enqueue_script('date-methods',$date);

	$datepicker = content_url() . '/plugins/verve-meta-boxes/js/date-picker.js';
	wp_enqueue_script('date-picker',$datepicker);

	$jeditable = content_url() . '/plugins/verve-meta-boxes/js/jquery.jeditable.mini.js';
	wp_enqueue_script('jeditable',$jeditable,array('jquery'));

	$vervemeta = content_url() . '/plugins/verve-meta-boxes/js/verve-meta.js';
	wp_enqueue_script('verve-meta',$vervemeta,array("jquery"));

}

if(is_admin()){
	add_action('init', 'verve_admin_js');
}


function verve_admin_css(){
     echo '<link type="text/css" rel="stylesheet" href="' . content_url() . '/plugins/verve-meta-boxes/css/verve-meta.css" />' . "\n";
     echo '<link type="text/css" rel="stylesheet" href="' . content_url() . '/plugins/verve-meta-boxes/css/date-picker.css" />' . "\n";
}

add_action('admin_head', 	'verve_admin_css');

add_action('admin_menu', 	'verve_create_meta_boxes');

add_action('save_post', 	'verve_save_postdata', 1, 2); 
// on save_post make sure you add in the final two variables to this function call.
// see more here: http://alexking.org/blog/2008/09/06/wordpress-26x-duplicate-custom-field-issue

function verve_meta_add_pages() {
	add_submenu_page(	'tools.php', 'Verve Meta Boxes', 'Verve Meta Boxes', 8, 'verve-meta-boxes', 'verve_meta_boxes');
}
add_action('admin_menu', 'verve_meta_add_pages');

function verve_meta_boxes() {
	include(dirname( __FILE__ ).'/admin/meta_boxes.php');
}



?>