<?php
/*
Plugin Name: Cimy User Manager
Plugin URI: http://www.marcocimmino.net/cimy-wordpress-plugins/cimy-user-manager/
Description: Import and export users from/to CSV files, supports all WordPress profile data also Cimy User Extra Fields plug-in
Version: 1.3.1
Author: Marco Cimmino
Author URI: mailto:cimmino.marco@gmail.com
*/

/*

Cimy User Manager - Import and export users from/to CSV files
Copyright (c) 2007-2012 Marco Cimmino

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.


The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

// pre 2.6 compatibility or if not defined
if (!defined("WP_CONTENT_DIR"))
	define("WP_CONTENT_DIR", ABSPATH."/wp_content");

$cum_plugin_name = basename(__FILE__);
$cum_plugin_path = plugin_basename(dirname(__FILE__))."/";
$cum_plugin_dir = WP_CONTENT_DIR."/plugins/".$cum_plugin_path;
$cum_upload_path = WP_CONTENT_DIR."/cimy-user-manager/";

$cimy_um_domain = 'cimy_um';
$cimy_um_i18n_is_setup = false;
cimy_um_i18n_setup();

// function that add the submenu under 'Users'
if (is_network_admin())
	add_action('network_admin_menu', 'cimy_um_admin_menu_custom');
else
	add_action('admin_menu', 'cimy_um_admin_menu_custom');

$userid_code = '% USERID %';
$useremail_code = '% EMAIL %';
$username_code = '% USERNAME %';
$firstname_code = '% FIRSTNAME %';
$lastname_code = '% LASTNAME %';
$nickname_code = '% NICKNAME %';
$website_code = '% WEBSITE %';
$aim_code = '% AIM %';
$yahoo_code = '% YAHOO %';
$jabber_code = '% JABBER %';
$password_code = '% PASSWORD %';
$role_code = "% ROLE %";
$desc_code = "% DESCRIPTION %";
$registered_code = "% REGISTERED %";
$displayname_code = "% DISPLAYNAME %";

add_action('init', 'cimy_um_download_database');

function cimy_um_download_database() {
	if (!empty($_POST["cimy_um_filename"])) {
		if (strpos($_SERVER['HTTP_REFERER'], admin_url('users.php?page=cimy_user_manager')) !== false) {
			$cimy_um_filename = $_POST["cimy_um_filename"];
			// protect from site traversing
			$cimy_um_filename = str_replace('../', '', $cimy_um_filename);
			if (!is_file($cimy_um_filename))
				return;

			header("Pragma: "); // Leave blank for issues with IE
			header("Expires: 0");
			header('Vary: User-Agent');
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: text/csv");
			header("Content-Type: application/force-download");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"".esc_html(basename($cimy_um_filename))."\";"); // cannot use esc_url any more because prepends 'http' (doh)
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($cimy_um_filename));
			readfile($cimy_um_filename);
			exit();
		}
	}
}

function cimy_um_i18n_setup() {
	global $cimy_um_domain, $cimy_um_i18n_is_setup, $cum_plugin_path;

	if ($cimy_um_i18n_is_setup)
		return;

	load_plugin_textdomain($cimy_um_domain, false, $cum_plugin_path.'langs');
	$cimy_um_i18n_is_setup = true;
}


function cimy_um_admin_menu_custom() {
	global $cimy_um_domain;

	add_submenu_page('users.php', __('Cimy User Manager', $cimy_um_domain), __('Cimy User Manager', $cimy_um_domain), "list_users", "cimy_user_manager", 'cimy_um_import_export_page');
}

function cimy_um_import_export_page() {
	global $cimy_um_domain, $cimy_uef_name, $cum_upload_path;

	if (!current_user_can('list_users'))
		return;

	if (!empty($_POST))
		if (!check_admin_referer('cimy_um_importexport', 'cimy_um_importexportnonce'))
			return;

	$allowed_to_import = false;
	if (is_network_admin() && current_user_can('edit_users') && current_user_can('manage_network_users'))
		$allowed_to_import = true;
	else if (!is_network_admin() && current_user_can('edit_users'))
		$allowed_to_import = true;

	$results_import = array();
	$results_export = array();
	
	if (isset($_POST['cimy_um_import']))
		$results_import = cimy_um_import_data();
	
	if (isset($_POST['cimy_um_export']))
		$results_export = cimy_um_export_data();
	
	if (isset($_POST['db_date_format']))
		$db_date_format = esc_attr($_POST['db_date_format']);
	else
		$db_date_format = "%d %B %Y @%H:%M";
	
	if (!isset($cimy_uef_name)) {
		$db_extra_fields_warning = "<br /><strong>".__("You must activate Cimy User Extra Fields to export data from that plug-in", $cimy_um_domain)."</strong>";
	}
	else
		$db_extra_fields_warning = "";
	
	if (isset($_POST["db_field_separator"]))
		$field_separator = esc_attr(stripslashes($_POST["db_field_separator"]));
	else
		$field_separator = esc_attr(",");

	if (isset($_POST["db_text_separator"]))
		$text_separator = esc_attr(stripslashes($_POST["db_text_separator"]));
	else
		$text_separator = esc_attr("\"");
	?>
	<div class="wrap" id="options">
	<?php
	if ($allowed_to_import) {
		if (function_exists("screen_icon"))
			screen_icon("users");
	?>
	<h2><?php _e("Import Users", $cimy_um_domain); ?></h2><?php
	
	// print successes/errors if there are some
	if (count($results_import) > 0) {
	?>
		<br /><div class="updated">
	<?php
		if (isset($results_import["error"])) {
			echo "<h3>".__("ERRORS", $cimy_um_domain)." (".count($results_import["error"]).")</h3>";
	
			foreach ($results_import["error"] as $result)
				echo $result."<br />";
		}
		
		if (isset($results_import["added"])) {
			echo "<h3>".__("USERS SUCCESSFULLY ADDED", $cimy_um_domain)." (".count($results_import["added"]).")</h3>";
	
			foreach ($results_import["added"] as $result)
				echo $result."<br />";
		}
		
		if (isset($results_import["modified"])) {
			echo "<h3>".__("USERS SUCCESSFULLY MODIFIED", $cimy_um_domain)." (".count($results_import["modified"]).")</h3>";
	
			foreach ($results_import["modified"] as $result)
				echo $result."<br />";
		}
		?><br /></div><?php
	}
	?>
	
	<p>
	</p>
	
	<script type="text/javascript" language="javascript">
		function changeEnc(form_id) {
			var browser = navigator.appName;
		
			if (browser == "Microsoft Internet Explorer")
				document.cimy_um_import.encoding = "multipart/form-data";
			else
				document.cimy_um_import.enctype = "multipart/form-data";
		}
	</script>
	
	<form name="cimy_um_import" id="cimy_um_import" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field('cimy_um_importexport', 'cimy_um_importexportnonce', false); ?>
	<table class="form-table">
		<tr>
			<th scope="row" width="40%"><?php _e("Select the CSV file", $cimy_um_domain); ?></th>
			<td width="60%">
				<input type="file" name="db_import" onchange="changeEnc('cimy_um_import');" />
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Select field delimiter", $cimy_um_domain); ?></th>
			<td>
				<input type="text" name="db_field_separator" value="<?php echo $field_separator; ?>" />  <?php _e('If your CSV file is like: "field1","field2" then you need to use comma', $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Select text delimiter", $cimy_um_domain); ?></th>
			<td>
				<input type="text" name="db_text_separator" value="<?php echo $text_separator; ?>" />  <?php _e('If your CSV file is like: "field1","field2" then you need to use double quote', $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Create users", $cimy_um_domain); ?></th>
			<td>
				<input type="checkbox" name="db_add_users" value="1"<?php checked(true, isset($_POST['db_add_users']), true); ?> />  <?php _e("Select this option to let the plug-in create users.", $cimy_um_domain); ?><br /><?php _e("<strong>REMEMBER</strong>: for new users <strong>do not</strong> enter any USERID, but <strong>specify</strong> an unique USERNAME and EMAIL", $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Email notifications", $cimy_um_domain); ?></th>
			<td>
				<input type="checkbox" name="db_send_email_new_users" value="1"<?php checked(true, isset($_POST["db_send_email_new_users"]), true); ?> />  <?php _e("Send an email to the new users created.", $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"></th>
			<td>
				<input type="checkbox" name="db_send_email_password_changed" value="1"<?php checked(true, isset($_POST["db_send_email_password_changed"]), true); ?> />  <?php _e("Send an email to the existing users with the new password.", $cimy_um_domain); ?>
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="cimy_um_import" value="1" />
	<p class="submit"><input class="button-primary" type="submit" name="Import" value="<?php _e('Import') ?>" /></p>
	</form>

	<br />
	<?php
	} // if ($allowed_to_import)
		if (function_exists("screen_icon"))
			screen_icon("users");
	?>
	<h2><?php _e("Export Users", $cimy_um_domain); ?></h2><?php
	
	// print successes/errors if there are some
	if (count($results_export) > 0) {
	?>
		<br /><div class="updated">
	<?php
		if (isset($results_export["tmp_file"])) {
			echo "<h3>".__("FILE GENERATED", $cimy_um_domain)." (".count($results_export["tmp_file"]).")</h3>";
			echo $results_export["tmp_file"];
			echo "<form name=\"cimy_um_download\" id=\"cimy_um_download\" method=\"post\"><input type=\"hidden\" name=\"cimy_um_filename\" value=\"".$results_export["tmp_file"]."\" /><input type=\"submit\" value=\"".__("Download Export File")."\" /></form>";
			echo "<br />";
		}
		
		if (isset($results_export["error"])) {
			echo "<h3>".__("ERRORS", $cimy_um_domain)." (".count($results_export["error"]).")</h3>";
	
			foreach ($results_export["error"] as $result)
				echo $result."<br />";
		}
		
		if (isset($results_export["exported"])) {
			echo "<h3>".__("USERS SUCCESSFULLY EXPORTED", $cimy_um_domain)." (".count($results_export["exported"]).")</h3>";
	
			foreach ($results_export["exported"] as $result)
				echo $result."<br />";
		}
		?><br /></div><?php
	}
	if ((!is_dir($cum_upload_path)) && (is_writable(WP_CONTENT_DIR))) {
		if (defined("FS_CHMOD_DIR"))
			@mkdir($cum_upload_path, FS_CHMOD_DIR);
		else
			@mkdir($cum_upload_path, 0777);
	}
	if (is_writable($cum_upload_path) && (!file_exists($cum_upload_path.".htaccess")))
		cimy_um_create_htaccess();
	?>
	
	<p>
	</p>
	
	<form name="cimy_um_export" id="cimy_um_export" method="post">
	<?php wp_nonce_field('cimy_um_importexport', 'cimy_um_importexportnonce', false); ?>
	<table class="form-table">
		<tr>
			<th scope="row" width="40%"><?php _e("Upload path", $cimy_um_domain); ?></th>
			<td width="60%">
			<?php
				if (is_writable($cum_upload_path))
					echo "<em>".$cum_upload_path."</em><br />".__("is created and writable", $cimy_um_domain);
				else
					echo "<em>".$cum_upload_path."</em><br />".__("is NOT created or webserver does NOT have permission to write on it", $cimy_um_domain);
			?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Select field delimiter", $cimy_um_domain); ?></th>
			<td>
				<input type="text" name="db_field_separator" value="<?php echo $field_separator; ?>" />  <?php _e('If your CSV file is like: "field1","field2" then you need to use comma', $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Select text delimiter", $cimy_um_domain); ?></th>
			<td>
				<input type="text" name="db_text_separator" value="<?php echo $text_separator; ?>" />  <?php _e('If your CSV file is like: "field1","field2" then you need to use double quote', $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Sort by", $cimy_um_domain); ?></th>
			<td>
				<select name="db_sort_by">
					<option value='registered'<?php selected('registered', $_POST['db_sort_by'], true); ?>><?php _e('Registered date', $cimy_um_domain); ?></option>
					<option value='login'<?php selected('login', $_POST['db_sort_by'], true); ?>><?php _e('Login Name', $cimy_um_domain); ?></option>
					<option value='email'<?php selected('email', $_POST['db_sort_by'], true); ?>><?php _e('Email'); ?></option>
					<option value='display_name'<?php selected('display_name', $_POST['db_sort_by'], true); ?>><?php _e('Display Name', $cimy_um_domain); ?></option>
					<option value='url'<?php selected('url', $_POST['db_sort_by'], true); ?>><?php _e('Website'); ?></option>
					<option value='post_count'<?php selected('post_count', $_POST['db_sort_by'], true); ?>><?php _e('Post Count', $cimy_um_domain); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Use UTF-16LE encoding", $cimy_um_domain); ?></th>
			<td>
				<input type="checkbox" name="db_use_utf-16le" value="1"<?php checked(true, isset($_POST['db_use_utf-16le']), true); ?> />  <?php _e("Select this option if you want more Microsoft Excel compatibility", $cimy_um_domain); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e("Add also Cimy User Extra Fields data", $cimy_um_domain); ?></th>
			<td>
				<input type="checkbox" name="db_extra_fields" value="1"<?php checked(true, isset($_POST['db_extra_fields']), true); disabled(false, isset($cimy_uef_name), true); ?> />  <?php _e("Select this option to let the plug-in export also data present into Cimy User Extra Fields", $cimy_um_domain); echo $db_extra_fields_warning; ?>
			</td>
		</tr>
<!--
		<tr>
			<th><?php //_e("Select date format", $cimy_um_domain); ?></th>
			<td>
				<input type="text" name="db_date_format" value="<?php //echo $db_date_format; ?>" />  <?php //_e("Select the date/time format to represent registration dates (if any)", $cimy_um_domain); ?><br />
				<?php //_e("More info about date/time format are on this", $cimy_um_domain); ?> <a href="http://www.php.net/manual/en/function.strftime.php"><?php //_e("LINK", $cimy_um_domain); ?></a>
			</td>
		</tr>
-->
	</table>
	
	<input type="hidden" name="cimy_um_export" value="1" />
	<p class="submit"><input class="button-primary" type="submit" name="Export" value="<?php _e('Export') ?>" /></p>
	</form>
	</div>
	<br />
	<?php
}

function cimy_um_import_data() {
	global $wpdb, $wpdb_data_table, $wpdb_fields_table, $cimy_um_domain, $wp_roles;
	global $userid_code, $useremail_code, $username_code, $firstname_code, $lastname_code, $nickname_code, $website_code, $aim_code, $yahoo_code, $jabber_code, $password_code, $role_code, $desc_code, $displayname_code, $registered_code;
	
	$results = array();

	$allowed_to_import = false;
	if (is_network_admin() && current_user_can('edit_users') && current_user_can('manage_network_users'))
		$allowed_to_import = true;
	else if (!is_network_admin() && current_user_can('edit_users'))
		$allowed_to_import = true;

	if (!$allowed_to_import)
		return;

	// try to not timeout
	set_time_limit(0);
	$all_roles = $wp_roles->role_names;

	// needed for silly Windows files
	ini_set('auto_detect_line_endings', true);

	$file_type = $_FILES["db_import"]['type'];
	$file_tmp_name = $_FILES["db_import"]['tmp_name'];
	$file_error = $_FILES["db_import"]['error'];

	if (!is_readable($file_tmp_name)) {
		$results["error"][] = __("Cannot open the file", $cimy_um_domain);
		return $results;
	}
	else if (($fh = fopen($file_tmp_name, 'r')) === false) {
		$results["error"][] = __("Cannot open the file", $cimy_um_domain);
		return $results;
	}

	$field_separator = stripslashes($_POST["db_field_separator"]);
	$text_separator = stripslashes($_POST["db_text_separator"]);

	$separator = $text_separator.$field_separator.$text_separator;

	// fgetcsv is nice, but works good only after PHP v5.0.4 and when delimiters are 1 character long
	$use_fget_csv = ((strlen($field_separator) == 1) && (strlen($text_separator) == 1) && version_compare(PHP_VERSION, "5.0.4", '>=')) ? true : false;

	// name of the fields in the file imported
	if ($use_fget_csv)
		$fields = fgetcsv($fh, 0, $field_separator, $text_separator);
	else
		$fields = explode($separator, fgets($fh));

	// position of special things in $all_data array
	$specials = array();
	
	// position of extra_fields data in $all_data array
	$extra_fields = array();
	
	// ID of the fields in the DB
	$db_extra_fields = array();
	
	$missing_cimy_uef_error = false;

	$i = 0;
	$next_field = reset($fields);
	while ($next_field !== false) {
		$field = $next_field;
		$next_field = next($fields);

		$field = strtoupper(trim($field, "\n\r"));

		if ($i == 0) {
			if (!empty($text_separator) && !$use_fget_csv)
				$field = substr($field, strlen($text_separator));
			$fields[$i] = $field;
		}

		if ($next_field === false) {
			$last_field = $i;
			if (!empty($text_separator) && !$use_fget_csv)
				$field = substr($field, 0, (-1)*strlen($text_separator));
			$fields[$i] = $field;
		}

		switch ($field) {
			case $userid_code:
				$specials["ID"] = $i;
				break;
				
			case $useremail_code:
				$specials["email"] = $i;
				break;
			
			case $username_code:
				$specials["username"] = $i;
				break;
				
			case $firstname_code:
				$specials["firstname"] = $i;
				break;
				
			case $lastname_code:
				$specials["lastname"] = $i;
				break;
				
			case $nickname_code:
				$specials["nickname"] = $i;
				break;

			case $displayname_code:
				$specials["displayname"] = $i;
				break;

			case $website_code:
				$specials["website"] = $i;
				break;
				
			case $aim_code:
				$specials["aim"] = $i;
				break;
				
			case $yahoo_code:
				$specials["yahoo"] = $i;
				break;
				
			case $jabber_code:
				$specials["jabber"] = $i;
				break;
				
			case $password_code:
				$specials["password"] = $i;
				break;
				
			case $role_code:
				$specials["role"] = $i;
				break;
				
			case $desc_code:
				$specials["description"] = $i;
				break;
				
			// so it won't go in the default:
			case $registered_code:
				$specials["registered"] = $i;
				break;

			default:
				$extra_fields[strtoupper($field)] = $i;
				break;
		}
		$i++;
	}

	// first line already read, so will be immediately increased to 2
	$line = 1;

	// looping through rows
	while (!feof($fh)) {
		$line++;
		$line_tr = " ".sprintf(__("(line %s)", $cimy_um_domain), $line);

		if ($use_fget_csv) {
			$all_data = fgetcsv($fh, 0, $field_separator, $text_separator);
			if (empty($all_data))
				continue;
		}
		else {
			$row = fgets($fh);
			// remove definitly all new lines and carriage returns
			$row = trim($row, "\n\r");
			
			// remove also space, but not definitly from the row
			if (trim($row) == "")
				continue;
			$all_data = explode($separator, $row);

			if (!empty($text_separator)) {
				$all_data[0] = substr($all_data[0], strlen($text_separator));
				$all_data[$last_field] = substr($all_data[$last_field], 0, (-1)*strlen($text_separator));
			}
		}

		$email = "";
		$username = "";
		$passw = "";
		$wp_user = false;
		$wp_new_user = array();
		$wp_userid = false;
		$new_password_email_subject = "";
		$new_password_email_body = "";

		if ((isset($specials["username"])) && (empty($username))) {
			$username = sanitize_user($all_data[$specials["username"]], true);

			if ($username != $all_data[$specials["username"]])
				$results["error"][] = sprintf(__("username '%s' has some invalid characters, used this username instead: '%s'", $cimy_um_domain), esc_attr($all_data[$specials["username"]]), esc_attr($username)).$line_tr;

			if (!is_object($wp_user)) {
				$wp_user = new WP_User($username);
				$wp_userid = intval($wp_user->ID);
		
				if ($wp_user->ID != 0)
					$results["modified"][] = "'".esc_attr($username)."'".$line_tr;
				else
					$wp_user = false; // yea let's give it a chance with username then if available
			}
		}

		// ID is less important than username, if importing back the CSV created username comes first
		// use ID only if username failed earlier
		if ((isset($specials["ID"])) && (trim($all_data[$specials["ID"]]) != "") && !is_object($wp_user)) {
			$wp_userid = intval(trim($all_data[$specials["ID"]]));
			$wp_user = new WP_User($wp_userid);
			
			if ($wp_user->ID != 0) {
				$username = $wp_user->user_login;
				$results["modified"][] = "'".esc_attr($username)."'".$line_tr;
			}
		}

		// user object not found, means no username, no ID or wrongly formatted CSV
		if (!is_object($wp_user)) {
			$results["error"][] = sprintf(__("no username provided neither ID, whatever you are trying to do this will be skipped", $cimy_um_domain)).$line_tr;
			continue;
		}

		// check if user doesn't exist, if not insert!
		if (($wp_user->ID != $wp_userid) || ($wp_user->ID == 0)) {
			// just check what was the error and drop the row as we are not allowed to create new users by the admin
			if (!isset($_POST["db_add_users"])) {
				$new_user_error = "";
				
				if (isset($specials["ID"])) {
					if ($wp_userid == 0)
						$new_user_error = __("userid is missing", $cimy_um_domain);
					else
						$new_user_error = sprintf(__("userid '%s' is not present in the DB", $cimy_um_domain), esc_attr($wp_userid));
				}
				
				if (isset($specials["username"])) {
					if (!empty($new_user_error))
						$new_user_error .= " ".__("and", $cimy_um_domain)." ";
				
					if (empty($username))
						$new_user_error .= __("username is missing", $cimy_um_domain);
					else
						$new_user_error .= sprintf(__("the username '%s' is not present in the DB", $cimy_um_domain), esc_attr($username));
				}
				
				if (!empty($new_user_error))
					$results["error"][] = $new_user_error.$line_tr;
				
				// drop as user is not valid and we cannot create it
				continue;
			}
		
			// check for username: if missing cannot add a new user
			if (empty($username)) {
				$results["error"][] = __("username missing cannot add an user without it", $cimy_um_domain).$line_tr;
				continue;
			}
			
			// check for username: if already existing cannot add a new user
			if (username_exists($username) != null) {
				$results["error"][] = sprintf(__("username '%s' already present in the DB (line %s)", $cimy_um_domain), esc_attr($username), $line);
				continue;
			}
			
			// check for e-mail: if missing or already present cannot add a new user
			if (isset($specials["email"])) {
				$email = sanitize_email($all_data[$specials["email"]]);

				if ($email != $all_data[$specials["email"]])
					$results["error"][] = sprintf(__("e-mail '%s' has some invalid characters, used this address instead: '%s'", $cimy_um_domain), esc_attr($all_data[$specials["email"]]), esc_attr($email)).$line_tr;
			
				if (!email_exists($email)) {
					$wp_new_user["user_email"] = $email;
				}
				else {
					$results["error"][] = sprintf(__("e-mail '%s' already present in the DB, dropped this new user: '%s'", $cimy_um_domain), esc_attr($email), esc_attr($username)).$line_tr;
					continue;
				}
			}
			else {
				$results["error"][] = sprintf(__("e-mail field empty, dropped this new user: '%s'", $cimy_um_domain), esc_attr($username)).$line_tr;
				continue;
			}
			
			// check for e-mail: if empty cannot add a new user
			if (empty($email)) {
				$results["error"][] = sprintf(__("e-mail field empty, dropped this new user: '%s'", $cimy_um_domain), esc_attr($username)).$line_tr;
				continue;
			}
			
			$passw = $all_data[$specials["password"]];
			if (empty($passw))
				$passw = wp_generate_password(12, false);
			
			$wp_new_user["user_pass"] = $passw;
			$wp_new_user["user_login"] = $username;
			
			// dropping ID as WordPress/MySQL will assign a correct/new one
			//unset($wp_new_user["ID"]);
			//$results["error"][] = $wp_new_user["ID"];

			if (!current_user_can('create_users')) {
				$results["error"][] = __("seems you do not have 'create_users' capability", $cimy_um_domain).$line_tr;
				continue;
			}

			$wp_userid = wp_insert_user($wp_new_user);
			$wp_user = new WP_User($wp_userid);

			if (isset($_POST["db_send_email_new_users"]))
				if ($_POST["db_send_email_new_users"] == "1")
					wp_new_user_notification($wp_userid, $passw);

			$results["added"][] = "'".esc_attr($username)."'".$line_tr;
		}
		// updating an existing user
		else {
			// no man you don't have enough power!
			if (!current_user_can('edit_user', $wp_userid))
				continue;

			if (isset($specials["password"])) {
				$value = $all_data[$specials["password"]];
				if (empty($value))
					$value = wp_generate_password(12, false);

				//$wp_new_user["ID"] = $wp_userid;
				$wp_new_user["user_pass"] = $value;

				if (!empty($_POST["db_send_email_password_changed"])) {
					$new_password_email_body  = sprintf(__('Username: %s'), $username) . "\r\n";
					$new_password_email_body .= sprintf(__('Password: %s'), $value) . "\r\n";
					$new_password_email_body .= site_url('wp-login.php', 'login') . "\r\n";

					// The blogname option is escaped with esc_html on the way into the database in sanitize_option
					// we want to reverse this for the plain text arena of emails.
					$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
					$new_password_email_subject = sprintf(__('[%s] Your new password'), $blogname);
					$new_password_email_subject = apply_filters('password_reset_title', $new_password_email_subject);
					$new_password_email_body = apply_filters('password_reset_message', $new_password_email_body, $value);
				}
			}
		}

		if (isset($specials["role"])) {
			// if the role exists let's do it
			if (!empty($all_roles[$all_data[$specials["role"]]]))
				$wp_user->set_role($all_data[$specials["role"]]);
		}

		if (isset($specials["firstname"])) {
			$value = $all_data[$specials["firstname"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["first_name"] = $value;
		}
		
		if (isset($specials["lastname"])) {
			$value = $all_data[$specials["lastname"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["last_name"] = $value;
		}
		
		if (isset($specials["nickname"])) {
			$value = $all_data[$specials["nickname"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["nickname"] = $value;
		}

		if (isset($specials["displayname"])) {
			$value = $all_data[$specials["displayname"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["display_name"] = $value;
		}

		// $email == "" means is not a new user
		if (empty($email)) {
			if (isset($specials["email"])) {
				$value = sanitize_email($all_data[$specials["email"]]);

				if ($value != $all_data[$specials["email"]])
					$results["error"][] = sprintf(__("e-mail '%s' has some invalid characters, used this address instead: '%s'", $cimy_um_domain), esc_attr($all_data[$specials["email"]]), esc_attr($value)).$line_tr;

				if (!email_exists($value)) {
					//$wp_new_user["ID"] = $wp_userid;
					$wp_new_user["user_email"] = $value;
				}
				else {
					$results["error"][] = sprintf(__("e-mail '%s' already present in the DB, dropped this modification", $cimy_um_domain), esc_attr($value)).$line_tr;
				}
				if (!empty($new_password_email_body))
					wp_mail($value, $new_password_email_subject, $new_password_email_body);
			}
			else if (!empty($new_password_email_body))
				wp_mail($wp_user->user_email, $new_password_email_subject, $new_password_email_body);
		}

		if (isset($specials["website"])) {
			$value = $all_data[$specials["website"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["user_url"] = $value;
		}
		
		if (isset($specials["aim"])) {
			$value = $all_data[$specials["aim"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["aim"] = $value;
		}
		
		if (isset($specials["yahoo"])) {
			$value = $all_data[$specials["yahoo"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["yim"] = $value;
		}
		
		if (isset($specials["jabber"])) {
			$value = $all_data[$specials["jabber"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["jabber"] = $value;
		}
		
		if (isset($specials["description"])) {
			$value = $all_data[$specials["description"]];

			//$wp_new_user["ID"] = $wp_userid;
			$wp_new_user["description"] = $value;
		}

		if (!empty($wp_new_user)) {
			$wp_new_user["ID"] = $wp_userid;
			wp_update_user($wp_new_user);
		}

		// no extra fields no party
		if (empty($extra_fields))
			continue;

		// looping through array that contains extra_fields position in CSV rows
		
		// $fields is the first row
		// every $e_field is the column position in the first row
		// $fields[$e_field] are extra fields' names
		// $db_extra_fields stores all DB ids, key of the array is fields' names
		foreach ($extra_fields as $e_field) {
			if (!isset($db_extra_fields[$fields[$e_field]])) {
				$field_name = trim($fields[$e_field]);
				
				$sql = "SELECT ID,TYPE,LABEL FROM $wpdb_fields_table WHERE NAME=\"".$wpdb->escape(strtoupper($field_name))."\"";
				$result = $wpdb->get_results($sql, ARRAY_A);
				
				if (count($result) > 0)
					$db_extra_fields[$fields[$e_field]] = $result;
				else {
					$db_extra_fields[$fields[$e_field]] = -1;
					
					if (!isset($wpdb_fields_table)) {
						if (!$missing_cimy_uef_error)
							$results["error"][] = __("Cimy User Extra Fields is not active, impossible to import any extra fields data", $cimy_um_domain).$line_tr;
						
						$missing_cimy_uef_error = true;
					} else
						$results["error"][] = sprintf(__("'%s' field doesn't exist", $cimy_um_domain), esc_attr($field_name)).$line_tr;
				}
			}
			
			if ($db_extra_fields[$fields[$e_field]] != -1) {
				foreach ($db_extra_fields[$fields[$e_field]] as $ef_details) {
					$sql = "SELECT ID FROM $wpdb_data_table WHERE FIELD_ID=".$ef_details["ID"]." AND USER_ID=$wp_userid";
				
					unset($present);
					$present = $wpdb->get_var($sql);
		
					$all_data[$e_field] = $wpdb->escape($all_data[$e_field]);
					$value_to_store = trim($all_data[$e_field]);
					
					if ($ef_details["TYPE"] == "radio") {
						if ($ef_details["LABEL"] == $value_to_store)
							$value_to_store = "selected";
						else
							$value_to_store = "";
					}
					
					if ($ef_details["TYPE"] == "checkbox") {
						if ((strtoupper($value_to_store) == "YES") || ($value_to_store == "1"))
							$value_to_store = "YES";
						else
							$value_to_store = "NO";
					}
				
					$value_to_store = "\"".$value_to_store."\"";
		
					if (isset($present))
						$sql = "UPDATE $wpdb_data_table SET VALUE=".$value_to_store." WHERE USER_ID=$wp_userid AND FIELD_ID=".$ef_details["ID"];
					else
						$sql = "INSERT INTO $wpdb_data_table (USER_ID, FIELD_ID, VALUE) VALUES ($wp_userid, ".$ef_details["ID"].", ".$value_to_store.")";
	
					$wpdb->query($sql);
				}
			}
		}
	}
	
	fclose($fh);
	
	return $results;
}

function cimy_um_create_htaccess() {
	global $cum_upload_path;

	$file = $cum_upload_path.".htaccess";
	$fd_file = fopen($file, "w");

	$line = '<Files ~ ".*\..*">
order allow,deny
deny from all
</Files>';
	fwrite($fd_file, $line);
	fclose($fd_file);
}

function cimy_um_export_data() {
	global $wpdb, $wpdb_data_table, $wpdb_fields_table, $cimy_um_domain, $cum_upload_path;
	global $userid_code, $useremail_code, $username_code, $firstname_code, $lastname_code, $nickname_code, $website_code, $aim_code, $yahoo_code, $jabber_code, $password_code, $role_code, $desc_code, $registered_code, $displayname_code, $cimy_uef_name;
	
	$results = array();
	
	if (!current_user_can('list_users'))
		return;
	
	set_time_limit(0);

	$field_separator = stripslashes($_POST["db_field_separator"]);
	$text_separator = stripslashes($_POST["db_text_separator"]);
	
	if ((isset($_POST["db_extra_fields"])) && (isset($cimy_uef_name))) {
		global $wpdb_data_table;
		$extra_fields = get_cimyFields();
		$all_radio_fields = array();
	}
	else {
		$extra_fields = false;
	}
	
	if (isset($_POST['db_date_format']))
		$db_date_format = $_POST['db_date_format'];
	else
		$db_date_format = "";

	$tmpfile = $cum_upload_path."cimy_um_exported_users-".date("Ymd-His").".csv";
	$fd_tmp_file = fopen($tmpfile, "w");

	$line = $text_separator.$userid_code.$text_separator.
		$field_separator.$text_separator.$username_code.$text_separator.
		$field_separator.$text_separator.$role_code.$text_separator.
		$field_separator.$text_separator.$firstname_code.$text_separator.
		$field_separator.$text_separator.$lastname_code.$text_separator.
		$field_separator.$text_separator.$nickname_code.$text_separator.
		$field_separator.$text_separator.$displayname_code.$text_separator.
		$field_separator.$text_separator.$useremail_code.$text_separator.
		$field_separator.$text_separator.$website_code.$text_separator.
		$field_separator.$text_separator.$aim_code.$text_separator.
		$field_separator.$text_separator.$yahoo_code.$text_separator.
		$field_separator.$text_separator.$jabber_code.$text_separator.
		$field_separator.$text_separator.$desc_code.$text_separator.
		$field_separator.$text_separator.$registered_code.$text_separator;
	
	if ($extra_fields) {
		foreach ($extra_fields as $field) {
			// avoid radio fields duplicates
			if ($field["TYPE"] == "radio") {
				if (in_array($field["NAME"], $all_radio_fields))
					continue;
				else
					$all_radio_fields[] = $field["NAME"];
			}
			else if ($field["TYPE"] == "registration-date")
				continue;
			
			$line .= $field_separator.$text_separator.$field["NAME"].$text_separator;
		}
	}

	$line = str_replace(array("\r\n\r\n", "\r\n", "\n\r", "\r", "\n" ), " ", $line);
	$line .= "\r";

	// UTF-16LE is needed to open and use the csv-file in Excel (thanks to Jean-Pierre)
	// http://www.php.net/manual/en/function.iconv.php#104287
	// http://www.php.net/manual/en/function.fwrite.php#69566
	if (!empty($_POST['db_use_utf-16le']))
		$line = mb_convert_encoding($line, 'UTF-16LE', 'UTF-8');

	fwrite($fd_tmp_file, $line);
	
	$results["exported"] = array();

	$offset = 0;
	// max number of users to be retrieved at once
	// bigger number increases speed, but also memory usage, be reasonable
	$limit = 250;

	$args = array(
		'blog_id' => $GLOBALS['blog_id'],
		'role' => '',
		'meta_key' => '',
		'meta_value' => '',
		'meta_compare' => '',
		'include' => array(),
		'exclude' => array(),
		'orderby' => $_POST['db_sort_by'],
		'order' => 'ASC',
		'offset' => $offset,
		'search' => '',
		'number' => $limit,
		'count_total' => true,
		'fields' => 'all_with_meta',
		'who' => ''
	);

	// needed otherwise does not export everything
	if (is_network_admin())
		$args['blog_id'] = 0;

	$all_users = get_users($args);
	$tot_users = count($all_users);

	while ($tot_users > 0) {
		foreach ($all_users as $current_user) {
			$results["exported"][] = $current_user->user_login;

			$line = $text_separator.$current_user->ID.$text_separator.
					$field_separator.$text_separator.$current_user->user_login.$text_separator.
					$field_separator.$text_separator.$current_user->roles[0].$text_separator.
					$field_separator.$text_separator.$current_user->first_name.$text_separator.
					$field_separator.$text_separator.$current_user->last_name.$text_separator.
					$field_separator.$text_separator.$current_user->nickname.$text_separator.
					$field_separator.$text_separator.$current_user->display_name.$text_separator.
					$field_separator.$text_separator.$current_user->user_email.$text_separator.
					$field_separator.$text_separator.$current_user->user_url.$text_separator.
					$field_separator.$text_separator.$current_user->aim.$text_separator.
					$field_separator.$text_separator.$current_user->yim.$text_separator.
					$field_separator.$text_separator.$current_user->jabber.$text_separator.
					$field_separator.$text_separator.$current_user->user_description.$text_separator.
					$field_separator.$text_separator.$current_user->user_registered.$text_separator;

			if ($extra_fields) {
				$all_radio_fields = array();
				$ef_db = get_cimyFieldValue($current_user->ID, false);
				$i = 0;

				foreach ($extra_fields as $field) {
					$db_name = "";
					$db_value = "";

					// avoid radio fields duplicates
					if ($field["TYPE"] == "radio") {
						if (in_array($field["NAME"], $all_radio_fields))
							continue;
						else
							$all_radio_fields[] = $field["NAME"];
					}

					if (isset($ef_db[$i]))
						$db_name = $ef_db[$i]['NAME'];

					// can happen if the field's data has not been written in the DB yet
					// this issue has been introduced with the get_cimyFieldValue calls optimization in v1.1.0
					if ($field["NAME"] == $db_name) {
						if (isset($ef_db[$i]))
							$db_value = $ef_db[$i]['VALUE'];

						$i++;
						if ($field["TYPE"] == "registration-date")
							continue;
// 							$db_value = cimy_get_formatted_date($db_value, $db_date_format);
					}

					$line .= $field_separator.$text_separator.$db_value.$text_separator;
				}
			}

			$line = str_replace(array("\r\n\r\n", "\r\n", "\n\r", "\r", "\n" ), " ", $line);
			$line .= "\r";

			// UTF-16LE is needed to open and use the csv-file in Excel (thanks to Jean-Pierre)
			// http://www.php.net/manual/en/function.iconv.php#104287
			// http://www.php.net/manual/en/function.fwrite.php#69566
			if (!empty($_POST['db_use_utf-16le']))
				$line = mb_convert_encoding($line, 'UTF-16LE', 'UTF-8');

			fwrite($fd_tmp_file, $line);
		}
		// get next round of users (if any)
		$offset += $tot_users;
		$args["offset"] = $offset;
		$all_users = get_users($args);
		$tot_users = count($all_users);
	}
	
	fclose($fd_tmp_file);

	$results["tmp_file"] = $tmpfile;

	return $results;
}

function cimy_um_get_temp_dir() {
	$temp_dir = "";
	if (function_exists("sys_get_temp_dir"))
		$temp_dir = sys_get_temp_dir();

	if (!empty($temp_dir) && (is_writable($temp_dir)))
		return $temp_dir;

	// Try to get from environment variable
	else if ((!empty($_ENV['TMP'])) && (is_writable(realpath($_ENV['TMP']))))
	{
		return realpath($_ENV['TMP']);
	}
	else if ((!empty($_ENV['TMPDIR'])) && (is_writable(realpath($_ENV['TMPDIR']))))
	{
		return realpath($_ENV['TMPDIR']);
	}
	else if ((!empty($_ENV['TEMP'])) && (is_writable(realpath($_ENV['TEMP']))))
	{
		return realpath($_ENV['TEMP']);
	}

        // Detect by creating a temporary file
	else
	{
		// Try to use system's temporary directory
		// as random name shouldn't exist
		$temp_file = tempnam(md5(uniqid(rand(), true)), '');
		if ($temp_file)
		{
			$temp_dir = realpath(dirname($temp_file));
			unlink($temp_file);
			return $temp_dir;
		}
		else
		{
			return false;
		}
	}
}

?>
