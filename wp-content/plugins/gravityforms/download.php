<?php
for ( $i = 0; $i < $depth = 10; $i++ ) {
    $wp_root_path = str_repeat( '../', $i );

    if ( file_exists("{$wp_root_path}wp-load.php" ) ) {
        require_once("{$wp_root_path}wp-load.php");
        require_once("{$wp_root_path}wp-admin/includes/admin.php");
        break;
    }
}

//redirect to the login page if user is not authenticated
auth_redirect();

if(!IS_ADMINISTRATOR)
    die(__("You don't have permission to download a file", "gravityforms"));

$file_path = RGFormsModel::get_upload_path($_GET["form_id"]) . "/" . $_GET["f"];
$info = pathinfo($file_path);
if(strtolower($info["extension"]) == "csv"){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=export.csv');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    ob_clean();
    flush();
    readfile($file_path);
}
exit;
?>