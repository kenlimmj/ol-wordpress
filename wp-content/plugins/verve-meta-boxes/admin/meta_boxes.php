<?php
//error_reporting(E_STRICT);
/*
Copyright 2010 AvenueVERVE, LLC (http://www.avenueverve.com)

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

global $wpdb;
global $field_table_name;
global $box_table_name;
global $field_types;
global $post_types;

$field_types 	= verve_get_field_types();
$box_id 		= @$_REQUEST['box_id'];
$field_id 		= @$_REQUEST['field_id'];

function my_verve_init_field_types($types) {
  $types[] = 'html';
  return $types;
}
add_filter('verve_init_field_types', 'my_verve_init_field_types');

function my_verve_meta_box_content($f) {
  if($f->verve_meta_field_type=='html'){
    $meta_box_value = get_post_meta($post->ID, $f->verve_meta_field_key, true);
    echo '<p><label for="'.$f->verve_meta_field_key.'">'.$f->verve_meta_field_name.$description.'</label>';
    echo '<textarea name="'.$f->verve_meta_field_key.'" rows="3" cols="35" class="theEditor">'.$meta_box_value.'</textarea></p>';
  }
}
add_action('verve_meta_box_content', 'my_verve_meta_box_content');

if(function_exists('post_type_supports')){
	$post_types = get_post_types();
	foreach($post_types as $key => $value){
		if ( !post_type_supports($value, 'custom-fields') ){
			unset($post_types[$key]);
		}
	}
}else{
	$post_types = array('post','page');
}

function fieldkey_unique($key){
	global $wpdb;
	global $field_table_name;
	
	$query = "select * from $field_table_name where verve_meta_field_key = '".$key."'";
	$keys = $wpdb->get_results($query);
	if(count($keys)>0){
		return false;
	}
	return true;
}

if($_POST){
	//check_admin_referer('verve_meta_boxes');
	//var_dump($_POST);
	
	if($_POST['do']=='create_box'){

		$box_key 		= dirify_plus_for_php($_POST['verve_meta_box_title'],'plu');
		$box_title 		= addslashes($_POST['verve_meta_box_title']);
		$box_context	= serialize($_POST['verve_meta_box_context']);
	
		$query = (empty($box_id)) ? "insert into $box_table_name set " : "update $box_table_name set ";
		$query.= "verve_meta_box_key = '$box_key',
				  verve_meta_box_context = '$box_context',
				  verve_meta_box_title = '$box_title'
				  ";
		if(!empty($box_id)){ $query.=" where verve_meta_box_id = $box_id"; }

		$wpdb->query($query);
	
	}


	if($_POST['do']=='update_box' && $_POST['verve_meta_box_id'] !=''){

		$box_id 		= $_POST['verve_meta_box_id'];
		$box_context	= serialize($_POST['verve_meta_box_context']);
	
		$query = "update $box_table_name set 
				  verve_meta_box_context = '$box_context'
				  where verve_meta_box_id = $box_id
				  ";

		$wpdb->query($query);
	
	}

	if($_POST['do']=='delete_box' && $_POST['verve_meta_box_id'] !=''){

		$box_id 		= $_POST['verve_meta_box_id'];
		$box_context	= serialize($_POST['verve_meta_box_context']);
	
		//get all the field keys
		$query = "select verve_meta_field_key from $field_table_name where verve_meta_field_box_id = ".$box_id;
		$meta_keys = $wpdb->get_col($query);
		
		$query = "delete from $wpdb->postmeta where meta_key in ('".implode("','",$meta_keys)."')";
		$wpdb->query($query);
		
		$query = "delete from $field_table_name where verve_meta_field_box_id = ".$box_id;
		$wpdb->query($query);
		
		$query = "delete from $box_table_name where verve_meta_box_id = ".$box_id;
		$wpdb->query($query);
			
	}
	
	

	if($_POST['do']=='create_field' && !empty($box_id)){

		$i = 0;
		do {
			$suffix = ($i>0) ? "_".$i: "";
			$field_key 	= dirify_plus_for_php($_POST['verve_meta_field_name'],'plu').$suffix;
			$i++;
		} while (!fieldkey_unique($field_key));
		
		$field_name 		= addslashes($_POST['verve_meta_field_name']);
		$field_description 	= addslashes($_POST['verve_meta_field_description']);
		$field_type 		= $_POST['verve_meta_field_type'];
		$field_options 		= serialize($_POST['verve_meta_field_options']);
	
		$query = (empty($field_id)) ? "insert into $field_table_name set " : "update $field_table_name set ";
		$query.= "verve_meta_field_key = '$field_key',
				  verve_meta_field_name = '$field_name',
				  verve_meta_field_type = '$field_type',
				  verve_meta_field_box_id = '$box_id',
				  verve_meta_field_description = '$field_description'
				  ";
		if(!empty($_POST['verve_meta_field_options']))	{ $query.=" verve_meta_field_options = '$field_options' "; }
		if(!empty($field_id))							{ $query.=" where verve_meta_field_id = $field_id"; }

		$wpdb->query($query);
	
	}



}

$query 	= "select * from $box_table_name order by verve_meta_box_id desc";
$boxes 	= $wpdb->get_results($query);

?>
<div class="wrap verve-meta-boxes">
<h2>Verve Meta Boxes</h2>

<div id="info"></div>

<table class="widefat">
<thead>
<tr>
<th scope="col" id="cb" class="manage-column column-cb check-column" style="">&nbsp;</th>
<th scope="col" id="product_image_value" class="manage-column column-product_image_value">Box Title</th>
<th scope="col">Context</th>
<th scope="col">&nbsp;</th>
</tr>
</thead>
<tfoot>
<tr>
<th scope="col"  class="manage-column column-cb check-column" style="">&nbsp;</th>
<th scope="col" id="product_image_value" class="manage-column column-product_image_value">Box Title</th>
<th scope="col">Context</th>
<th scope="col">&nbsp;</th>
</tr>
</tfoot>
<tbody id="the-list">

	<form method="post" action="tools.php?page=verve-meta-boxes">
	<input name="do" type="hidden" value="create_box" />
	<input name="box_id" type="hidden" value="<?php echo $box->verve_meta_box_id?>" />
	<?php settings_fields('verve_meta_boxes'); ?>
	<?php $class = ('alternate' == $class) ? '' : 'alternate'; ?>
	<tr id='box-new' class='<?php echo $class; ?>'>
		<th scope="row" class="check-column">&nbsp;</th>
		<td valign="bottom">
			<strong>Create New Meta Box</strong><br />
			<input type="text" name="verve_meta_box_title" value="<?php echo $box->verve_meta_box_title; ?>" />
		</td>
		<td valign="bottom">
			<?php $context = (!empty($box->verve_meta_box_context)) ? unserialize($box->verve_meta_box_context) : array();  ?>
			<?php foreach ($post_types as $type) {?>
			<input name="verve_meta_box_context[]" type="checkbox" value="<?php echo $type?>"<?php if( in_array($type,$context) ){?> checked="checked"<?php }?> /> <?php echo $type?>
			<?php }?>
		</td>
		<td valign="bottom">
			<?php if(!empty($box->verve_meta_box_id)){?>
			<input type="submit" class="button-primary" value="<?php _e('Update Box') ?>" />
			<?php }else{?>
			<input type="submit" class="button-primary" value="<?php _e('Create Box') ?>" />
			<?php }?>
		</td>
	</tr>
	</form>
	<?php 
	if(!empty($boxes)){
	foreach($boxes as $b) { 
	$class = ('alternate' == $class) ? '' : 'alternate';
	$custom = get_post_custom($b->verve_meta_box_id);
	?>
	<tr class='box-def <?php echo $class; ?>'>
		<th scope="row" class="check-column">
			<span class="box_deleter">
			<form method="post" action="tools.php?page=verve-meta-boxes" onsubmit="return confirm('Are you sure? Box and stored values will be deleted. This cannot be undone.')">
			<input name="do" type="hidden" value="delete_box" />
			<input name="verve_meta_box_id" type="hidden" value="<?php echo $b->verve_meta_box_id?>" />
			<input name="deletebox" type="image" src="<?php echo plugins_url()?>/verve-meta-boxes/icons/cog_delete.png" />
			</form>
			</span>
		</th>
		<td>
			<strong>BOX: <span class="jeditable box_name" id="verve_meta_box_title-<?php echo $b->verve_meta_box_id?>"><?php echo $b->verve_meta_box_title?></span></strong>
		</td>
		<td>
			<form method="post" action="tools.php?page=verve-meta-boxes">
			<input name="do" type="hidden" value="update_box" />
			<input name="verve_meta_box_id" type="hidden" value="<?php echo $b->verve_meta_box_id?>" />
			<?php $context = (!empty($b->verve_meta_box_context)) ? unserialize($b->verve_meta_box_context) : array();  ?>
			<?php foreach ($post_types as $type) {?>
			<input name="verve_meta_box_context[]" type="checkbox" value="<?php echo $type?>"<?php if( in_array($type,$context) ){?> checked="checked"<?php }?> /> <?php echo $type?>
			<?php }?>
		</td>
		<td>
			<input type="submit" class="button-secondary" value="<?php _e('Update Box') ?>" />
			</form>
		</td>
	</tr>
	<?php
	$query = "select * from $field_table_name where verve_meta_field_box_id = ".$b->verve_meta_box_id." order by verve_meta_field_sequence asc";
	//echo $query;
	$fields = $wpdb->get_results($query); //var_dump($fields);
	if(!empty($fields)){ $i=0;
	?>
	<tr class='existing-fields <?php echo $class; ?>'>
		<th scope="row" class="check-column">&nbsp;</th>
		<td colspan="3">
			<strong>Existing Field(s)</strong>
			<ul class="field_list">
			<?php foreach ($fields as $field) { $i++;?>
				<li id="listItem_<?php echo $field->verve_meta_field_id?>" class="field_item" alt="<?php echo $field->verve_meta_field_id?>">
				<div class="field_specs">
				<span class="field_mover"><img src="<?php echo plugins_url()?>/verve-meta-boxes/icons/arrow.png" alt="move" width="16" height="16" class="handle" /></span>
				<span class="field_name jeditable" id="verve_meta_field_name-<?php echo $field->verve_meta_field_id?>"><?php echo $field->verve_meta_field_name?></span>
				<span class="field_type"><?php echo $field->verve_meta_field_type?></span>
				<span class="field_key"><?php echo $field->verve_meta_field_key?></span>
				</div>
				<?php if($field->verve_meta_field_type=='checkbox' || $field->verve_meta_field_type=='select'){
					if(!empty($field->verve_meta_field_options) ){
						if(is_serialized($field->verve_meta_field_options)){ $values = implode(", ",unserialize($field->verve_meta_field_options)); }else{ $values = $field->verve_meta_field_options; }
					}else{
						$values = 'click here to add a comma separated list of values... (hit enter to save)';
					}?>
					<div class="field_options"><b>values:</b> <span class="jeditable" id="verve_meta_field_options-<?php echo $field->verve_meta_field_id?>"><?php echo $values?></span></div>
					<?php unset($values);?>
				<?php }?>
				<?php if($field->verve_meta_field_type=='radio'){?>
					<div class="field_options"><b>values:</b> yes / no</div>
				<?php }?>
				<?php if(!empty($field->verve_meta_field_description)){?>
				<div class="field_description">description: <?php echo $field->verve_meta_field_description?></div>
				<?php }?>
				<div id="field_editor">
					<span class="field_deleter"><img src="<?php echo plugins_url()?>/verve-meta-boxes/icons/delete.png" alt="<?php echo $field->verve_meta_field_key?>-<?php echo $field->verve_meta_field_id?>" width="16" height="16" class="deletefield" /></span>
				</div>
				</li>
			<?php }?>
			</ul>
		</td>
	</tr>
	<?php }?>
	<tr class='add-field <?php echo $class; ?>'>
		<th scope="row" class="check-column">&nbsp;</th>
		<td colspan="3">
			<strong>Add Field</strong>
			<form method="post" action="tools.php?page=verve-meta-boxes">
			<input name="do" type="hidden" value="create_field" />
			<input name="box_id" type="hidden" value="<?php echo $b->verve_meta_box_id?>" />

			<label for="verve_meta_field_type">Field type</label>
			<select id="verve_meta_field_type" name="verve_meta_field_type">
			<?php foreach ($field_types as $type) {?>
				<option value="<?php echo $type?>"<?php if(@$f->type==$type){?> selected="selected"<?php }?>><?php echo $type?></option>
			<?php }?>
			</select>

			<label for="verve_meta_field_name">Field Name</label>
			<input name="verve_meta_field_name" type="text" value="<?php echo $f->verve_meta_field_name?>" />

			<label for="field_type">Field Description</label>
			<input name="verve_meta_field_description" type="text" value="<?php echo $f->verve_meta_field_description?>" />
			<input type="submit" class="button-secondary" value="<?php _e('Add Field') ?>" />
			</form>
		</td>
	</tr>
	<?php }?>
	<?php }?>

</tbody>
</table>



</div>

