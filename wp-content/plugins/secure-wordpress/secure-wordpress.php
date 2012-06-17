<?php
/**
 * Plugin Name: Secure WordPress
 * Plugin URI: http://www.websitedefender.com/secure-wordpress-plugin/
 * Text Domain: secure_wp
 * Domain Path: /languages
 * Description: Basic security checks for securing your WordPress installation
 * Author: WebsiteDefender
 * Version: 2.0.7
 * Author URI: http://www.websitedefender.com/
 * License: GPL
 */
/*
 * rev #1: 07/16/2011 {c}
 * rev #2: 07/26/2011 {c}
 * rev #3: 08/26/2011 {c}
 * rev #4: 09/12/2011 {c}
 * rev #5: 09/20/2011 {c}
 * rev #6: 09/30/2011 {c}
 * rev #7: 11/15/2011 {c}
 */

global $wp_version;
if ( !function_exists ('add_action') || version_compare($wp_version, "3.0", "<") ) {
	if (function_exists ('add_action')) {
		$exit_msg = 'The plugin <em><a href="http://wordpress.org/extend/plugins/secure-wordpress/" target="_blank">Secure WordPress</a></em> requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress" target="_blank">Please update WordPress</a> or delete the plugin.';
    }
	else {$exit_msg = '';}
    if (!headers_sent()) {
        header('Status: 403 Forbidden');
        header('HTTP/1.1 403 Forbidden');
    }
	exit($exit_msg);
}
/**
 * Displayed for the menu item in te admin menu
 * Images/ Icons in base64-encoding
 * @uses function wpag_get_resource_url() for display
 */
if ( isset($_GET['resource']) && !empty($_GET['resource']) ) {
	# base64 encoding performed by base64img.php from http://php.holtsmark.no
	$resources = array(
		'secure_wp.gif' =>
		'R0lGODlhCwALAKIHALOzs8PDw9fX16GhoZSUlPz8/Orq6v///yH5BAEAAAcALAAAAAALAAsAAAMveLrMFtCwEoi1oZwCrgeFcA3BcAkdAQgBagEmIZiDSAzxfNtDajy/10PQYEkajQQAOw==',
		'wp.png' =>
		'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAFfKj/FAAAAB3RJTUUH1wYQEiwG0'.
		'0adjQAAAAlwSFlzAAALEgAACxIB0t1+/AAAAARnQU1BAACxjwv8YQUAAABOUExURZ'.
		'wMDN7n93ut1kKExjFjnHul1tbn75S93jFrnP///1qUxnOl1sbe71KMxjFrpWOUzjl'.
		'7tYy13q3G5+fv95y93muczu/39zl7vff3//f//9Se9dEAAAABdFJOUwBA5thmAAAA'.
		's0lEQVR42iWPUZLDIAxDRZFNTMCllJD0/hddktWPRp6x5QcQmyIA1qG1GuBUIArwj'.
		'SRITkiylXNxHjtweqfRFHJ86MIBrBuW0nIIo96+H/SSAb5Zm14KnZTm7cQVc1XSMT'.
		'jr7IdAVPm+G5GS6YZHaUv6M132RBF1PopTXiuPYplcmxzWk2C72CfZTNaU09GCM3T'.
		'Ww9porieUwZt9yP6tHm5K5L2Uun6xsuf/WoTXwo7yQPwBXo8H/8TEoKYAAAAASUVO'.
		'RK5CYII='.
		'');

	if (array_key_exists($_GET['resource'], $resources))
    {
		$content = base64_decode($resources[ $_GET['resource'] ]);
		$lastMod = filemtime(__FILE__);
		$client = ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false );
		// Checking if the client is validating his cache and if it is current.
		if ( isset($client) && (strtotime($client) == $lastMod) ) {
            if (!headers_sent()) {
                // Client's cache IS current, so we just respond '304 Not Modified'.
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 304);
            }
			exit;
		}
        else {
            if (!headers_sent()) {
                // Image not cached or cache outdated, we respond '200 OK' and output the image.
                header('Last-Modified: '.gmdate('D, d M Y H:i:s', $lastMod).' GMT', true, 200);
                header('Content-Length: '.strlen($content));
                header('Content-Type: image/' . substr(strrchr($_GET['resource'], '.'), 1) );
            }
			echo $content;
			exit;
		}
	}
}


/* $rev #1, #2 {c} */
if (!function_exists('json_encode') || !class_exists('Services_JSON'))
{
    @require_once('inc/json.php');
}
if (!defined('WSD_RECAPTCHA_API_SERVER'))
{
    @require_once('inc/recaptchalib.php');
}
if ( !class_exists('WPlize') ) {
	@require_once('inc/WPlize.php');
}


// Check to see whether or not we should display the dashboard widget
//@ $rev3
$plugin1 = 'websitedefender-wordpress-security';
$plugin2 = 'wp-security-scan';
if (! in_array($plugin1.'/'.$plugin1.'.php', apply_filters('active_plugins', get_option('active_plugins')))
        || ! in_array($plugin2.'/securityscan.php', apply_filters('active_plugins', get_option('active_plugins'))))
{
    define('SWP_WSD_BLOG_FEED', 'http://www.websitedefender.com/feed/');
    @require_once('inc/swUtil.php');
    //@@ Hook into the 'wp_dashboard_setup' action to create the dashboard widget
    add_action('wp_dashboard_setup', "swUtil::addDashboardWidget");
}
unset($plugin1,$plugin2);
//@===



/*
 * Instantiate the swWSD class
 */
@require 'inc/swWSD.php';
$swwsd = new swWSD();


if ( !class_exists('SecureWP') ){

	if (function_exists ('add_action'))
    {
		// Pre-2.6 compatibility
		if ( !defined( 'WP_CONTENT_URL' ) ) {
			define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
        }
		if ( !defined( 'WP_PLUGIN_URL' ) ) {
			define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
        }
		if ( !defined( 'WP_PLUGIN_DIR' ) ) {
			define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
        }

		// plugin definitions
		define( 'FB_SWP_BASENAME', plugin_basename(__FILE__) );
		define( 'FB_SWP_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
		define( 'FB_SWP_FILENAME', str_replace( FB_SWP_BASEFOLDER.'/', '', plugin_basename(__FILE__) ) );
		define( 'FB_SWP_TEXTDOMAIN', 'secure_wp' );
	}


    /**
     * @package Secure WordPress
     * @author WebsiteDefender
     * @desc Secure WordPress beefs up the security of your WordPress installation
     * by removing error information on login pages, adds index.html to plugin directories,
     * hides the WordPress version and much more.
     */
    class SecureWP
    {
        var $wpversion;

        public function __construct()
        {
            global $wp_version;

            $this->wpversion = $wp_version;

            $this->activate();

            add_action( 'init', array(&$this, 'textdomain') );
            /**
             * remove WP version
             */
            if ( $GLOBALS['WPlize']->get_option('secure_wp_version') == '1' ) {
                add_action( 'init', array(&$this, 'replace_wp_version'), 1 );
            }

            /**
             * remove core update for non admins
             * @link: rights: http://codex.wordpress.org/Roles_and_Capabilities
             */
            if ( is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rcu') == '1') ) {
                add_action( 'init', array(&$this, 'remove_core_update'), 1 );
            }

            /**
             * remove plugin update for non admins
             * @link: rights: http://codex.wordpress.org/Roles_and_Capabilities
             */
            if ( is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rpu') == '1') ) {
                add_action( 'init', array(&$this, 'remove_plugin_update'), 1 );
            }

            /**
             * remove theme update for non admins
             * @link: rights: http://codex.wordpress.org/Roles_and_Capabilities
             */
            if ( is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rtu') == '1') && ( version_compare($wp_version, "2.8alpha", ">") ) ) {
                add_action( 'init', array(&$this, 'remove_theme_update'), 1 );
            }

            /**
             * remove WP version on backend
             */
            if ( $GLOBALS['WPlize']->get_option('secure_wp_admin_version') == '1' ) {
                add_action( 'init', array(&$this, 'remove_wp_version_on_admin'), 1 );
            }

            add_action( 'init', array(&$this, 'on_init'), 1 );
        }

        /**
         * active for multilanguage
         *
         * @package Secure WordPress
         */
        public function textdomain()
        {
            if ( function_exists('load_plugin_textdomain') ) {
                if ( !defined('WP_PLUGIN_DIR') ) {
                    load_plugin_textdomain(FB_SWP_TEXTDOMAIN, str_replace( ABSPATH, '', dirname(__FILE__) ) . '/languages');
                }
                else { load_plugin_textdomain(FB_SWP_TEXTDOMAIN, false, dirname( plugin_basename(__FILE__) ) . '/languages'); }
            }
        }

        // public function for WP < 2.8
        public function get_plugins_url($path = '', $plugin = '')
        {
            if ( function_exists('plugin_url') ) {
                return plugins_url($path, $plugin);
            }

            if ( function_exists('is_ssl') ) {
                $scheme = ( is_ssl() ? 'https' : 'http' );
            }
            else {$scheme = 'http';}

            if ( function_exists('plugins_url') )
                $url = plugins_url();
            else
                $url = WP_PLUGIN_URL;
            if ( 0 === strpos($url, 'http') ) {
                if ( function_exists('is_ssl') && is_ssl() ) {
                    $url = str_replace( 'http://', "{$scheme}://", $url );
                }
            }

            if ( !empty($plugin) && is_string($plugin) )
            {
                $folder = dirname(plugin_basename($plugin));
                if ('.' != $folder) {
                    $url .= '/' . ltrim($folder, '/');
                }
            }

            if ( !empty($path) && is_string($path) && strpos($path, '..') === false ) {
                $url .= '/' . ltrim($path, '/');
            }

            return apply_filters('plugins_url', $url, $path, $plugin);
        }

        /**
         * init functions; check rights and options; load external resources
         *
         * @package Secure WordPress
         */
        public function on_init()
        {
            global $wp_version;

            if ( is_admin() )
            {
                // update options
                add_action('admin_post_swp_update', array(&$this, 'swp_update') );
                // deinstall options
                add_action('admin_post_swp_uninstall', array(&$this, 'swp_uninstall') );

                // init default options when activate
                if ( function_exists('register_activation_hook') ) {
                    register_activation_hook(__FILE__, array($this, 'activate') );
                }
                // uninstall options when deactivate
                if ( function_exists('register_deactivation_hook') ) {
                    register_deactivation_hook(__FILE__, array($this, 'deactivate') );
                }

                // add options page
                add_action( 'admin_menu', array(&$this, 'admin_menu') );
                // hint in the footer of the options page
                add_action( 'in_admin_footer', array(&$this, 'admin_footer') );

                add_action( 'wp_ajax_set_toggle_status', array($this, 'set_toggle_status') );

                // Only load in the plug-in page
                /*$rev #2 07/27/2011 {c}$*/
                $url = $_SERVER['REQUEST_URI'];
                if (stristr($url, 'secure-wordpress'))
                {
                    /* $rev #1 07/15/2011 {c}$ */
                    $h1 = 'wsd_sw-styles'; $h2 = 'acx-json'; $h3 = 'acx-md5'; $h4 = 'wsd_sw_wsd'; $h5 = 'wsd_sw_scripts';
                        wp_register_style($h1, $this->get_plugins_url('css/wsd_sw_styles.css', __FILE__));
                        wp_register_script($h2, $this->get_plugins_url('js/json.js', __FILE__));
                        wp_register_script($h3, $this->get_plugins_url('js/md5.js', __FILE__));
                        wp_register_script($h4, $this->get_plugins_url('js/sw_wsd.js', __FILE__),array('jquery'));
                        wp_register_script($h5, $this->get_plugins_url('js/sw_wsd_scripts.js', __FILE__),array('jquery'));
                    wp_enqueue_style($h1);
                    wp_enqueue_script($h2);
                    wp_enqueue_script($h3);
                    wp_enqueue_script($h4);
                    wp_enqueue_script($h5);
                }
                /* $rev #2 09/12/2011 {c}$ */
                $h6 = 'swp-dashboard';
                wp_register_style($h6, $this->get_plugins_url('css/acx-wp-dashboard.css', __FILE__));
                wp_enqueue_style($h6);

            }
            /* End if admin*/


            /**
             * remove Error-information
             */
            if ( !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_error') == '1') ) {
                add_action( 'login_head', array(&$this, 'remove_error_div') );
                add_filter( 'login_errors', create_function( '$a', "return null;" ) );
            }


            /**
             * add index.html in plugin-folder
             */
            if ( $GLOBALS['WPlize']->get_option('secure_wp_index') == '1' ) {
                $this->add_index( WP_PLUGIN_DIR, true );
                $this->add_index( WP_CONTENT_URL . '/themes', true );
            }


            /**
             * remove rdf
             */
            if ( function_exists('rsd_link') && !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_rsd') == '1') ) {
                remove_action('wp_head', 'rsd_link');
            }


            /**
             * remove wlf
             */
            if ( function_exists('wlwmanifest_link') && !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_wlw') == '1') ) {
                remove_action('wp_head', 'wlwmanifest_link');
            }

            /**
             * add wp-scanner
             * @link http://blogsecurity.net/wordpress/tools/wp-scanner
             */

            if ( !is_admin() && ($GLOBALS['WPlize']->get_option('secure_wp_wps') == '1') ) {
                add_filter( 'script_loader_src', array(&$this, 'filter_script_loader') );
                add_filter( 'style_loader_src', array(&$this, 'filter_script_loader') );
            }

            /**
             * block bad queries
             * @link http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/
             */
            if ( !is_admin() && $GLOBALS['WPlize']->get_option('secure_wp_amurlr') == '1' ) {
                add_action( 'init', array(&$this, 'wp_against_malicious_url_request') );
            }
        }

        /**
         * install options
         *
         * @package Secure WordPress
         */
        public function activate()
        {
            // set default options
            $this->options_array = array('secure_wp_error' => '',
                                         'secure_wp_version' => '1',
                                         'secure_wp_admin_version' => '1',
                                         'secure_wp_index' => '1',
                                         'secure_wp_rsd' => '1',
                                         'secure_wp_wlw' => '',
                                         'secure_wp_rcu' => '1',
                                         'secure_wp_rpu' => '1',
                                         'secure_wp_rtu' => '1',
                                         'secure_wp_wps' => '',
                                         'secure_wp_amurlr' => '1'
                                        );

            // add class WPlize for options in WP
            $GLOBALS['WPlize'] = new WPlize('secure-wp',$this->options_array);
        }

        /**
         * update options
         *
         * @package Secure WordPress
         */
        public function update()
        {
            // init value
            $update_options = array();

            // set value
            foreach ($this->options_array as $key => $value) {
                $update_options[$key] = stripslashes_deep( trim($_POST[$key]) );
            }

            // save value
            if ($update_options) {
                $GLOBALS['WPlize']->update_option($update_options);
            }

            // update 10/04/2011
            if($_POST['show_rss_widget'] == 'on'){
                update_option('WSD-RSS-WGT-DISPLAY', 'yes');
            }
            else {
                update_option('WSD-RSS-WGT-DISPLAY', 'no');
            }
        }

        /**
         * uninstall options
         *
         * @package Secure WordPress
         */
        public function deactivate() { $GLOBALS['WPlize']->delete_option(); }

        /**
         * Add option for tabboxes via ajax
         *
         * @package Secure WordPress
         */
        public function set_toggle_status()
        {
            if ( current_user_can('manage_options') && $_POST['set_toggle_id'] )
            {
                $id     = $_POST['set_toggle_id'];
                $status = $_POST['set_toggle_status'];

                $GLOBALS['WPlize']->update_option($id, $status);
            }
        }

        /**
         * @version WP 2.8
         * Add action link(s) to plugins page
         *
         * @package Secure WordPress
         *
         * @param $links, $file
         * @return $links
         */
        public function filter_plugin_meta($links, $file)
        {
            /* create link */
            if ( $file == FB_SWP_BASENAME ) {
                array_unshift(
                    $links,
                    sprintf( '<a href="options-general.php?page=%s">%s</a>', FB_SWP_FILENAME, __('Settings') )
                );
            }

            return $links;
        }

        /**
         * Display Images/ Icons in base64-encoding
         *
         * @package Secure WordPress
         *
         * @param $resourceID
         * @return $resourceID
         */
        public function get_resource_url($resourceID) { return trailingslashit( get_bloginfo('url') ) . '?resource=' . $resourceID; }

        /**
         * content of help
         *
         * @package Secure WordPress
         */
        public function contextual_help()
        {
            $content = __('<a href="http://wordpress.org/extend/plugins/secure-wordpress/" target="_blank">Documentation</a>', FB_SWP_TEXTDOMAIN);
            return $content;
        }

        /**
         * settings in plugin-admin-page
         *
         * @package Secure WordPress
         */
        public function admin_menu()
        {
            global $wp_version;

            if ( function_exists('add_management_page') && current_user_can('manage_options') )
            {
                if ( !isset($_GET['update']) ) {
                    $_GET['update'] = 'false';
                }

                if ( !isset($_GET['uninstall']) ) {
                    $_GET['uninstall'] = 'false';
                }

                // update, uninstall message
                if ( strpos($_SERVER['REQUEST_URI'], 'secure-wordpress.php') && $_GET['update'] == 'true' ) {
                    $return_message = __('Options updated.', FB_SWP_TEXTDOMAIN);
                }
                elseif ( $_GET['uninstall'] == 'true' ) {
                    $return_message = __('All entries from the database have been deleted. You can now deactivate this plugin.', FB_SWP_TEXTDOMAIN);
                }
                else { $return_message = ''; }

                $message = '<div class="updated fade"><p>' . $return_message . '</p></div>';

                $menutitle = '';
                if ( version_compare( $wp_version, '2.7alpha', '>' ) )
                {
                    if ( $return_message !== '' ) {
                        add_action('admin_notices', create_function( '', "echo '$message';" ) );
                    }

                    $menutitle = '<img src="' . $this->get_resource_url('secure_wp.gif') . '" alt="" />' . ' ';
                }
                $menutitle .= __('Secure WP', FB_SWP_TEXTDOMAIN);

                // added check for SSL login and to adjust url for logo accordingly
                if ( force_ssl_login() || force_ssl_admin() ) {
                    $menutitle = str_replace( 'http://', 'https://', $menutitle );
                }

                if ( version_compare( $wp_version, '2.7alpha', '>' ) && function_exists('add_contextual_help') ) {
                    $hook = add_submenu_page( 'options-general.php', __('Secure WordPress', FB_SWP_TEXTDOMAIN), $menutitle, 'manage_options', basename(__FILE__), array(&$this, 'display_page') );
                    add_contextual_help( $hook, __('<a href="http://wordpress.org/extend/plugins/secure-wordpress/" target="_blank">Documentation</a>', FB_SWP_TEXTDOMAIN) );
                    //add_filter( 'contextual_help', array(&$this, 'contextual_help') );
                }
                else { add_submenu_page( 'options-general.php', __('Secure WP', FB_SWP_TEXTDOMAIN), $menutitle, 9, basename(__FILE__), array(&$this, 'display_page') ); }

                $plugin = plugin_basename(__FILE__);
                add_filter( 'plugin_action_links_' . $plugin, array(&$this, 'filter_plugin_meta'), 10, 2 );
                if ( version_compare( $wp_version, '2.8alpha', '>' ) ) {
                    add_filter( 'plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2 );
                }
            }
        }

        /**
         * credit in wp-footer
         *
         * @package Secure WordPress
         */
        public function admin_footer()
        {
            if( basename($_SERVER['QUERY_STRING']) == 'page=secure-wordpress.php') {
                $plugin_data = get_plugin_data( __FILE__ );
                printf('%1$s plugin | ' . __('Version', FB_SWP_TEXTDOMAIN) . ' <a href="http://wordpress.org/extend/plugins/secure-wordpress/changelog/" target="_blank" title="' . __('History', FB_SWP_TEXTDOMAIN) . '">%2$s</a> | ' . __('Author', FB_SWP_TEXTDOMAIN) . ' %3$s<br />', $plugin_data['Title'], $plugin_data['Version'], $plugin_data['Author']);
            }
        }

        /**
         * add index.php to plugin-derectory
         */
        public function add_index($path, $enable)
        {
            $file = trailingslashit($path) . 'index.php';
            if ($enable) {
                if (!file_exists($file)) {
                    $fh = @fopen($file, 'w');
                    if ($fh) {fclose($fh);}
                }
            }
            else {
                if (file_exists($file) && filesize($file) === 0) {
                    @unlink($file);
                }
            }
        }

        /**
         * Replace the WP-version with a random string &lt; WP 2.4
         * and eliminate WP-version &gt; WP 2.4
         * @link http://bueltge.de/wordpress-version-verschleiern-plugin/602/
         *
         * @package Secure WordPress
         */
        public function replace_wp_version()
        {
            if ( !is_admin() )
            {
                global $wp_version;

                // random values
                $v = intval( rand(0, 9999) );
                $d = intval( rand(9999, 99999) );
                $m = intval( rand(99999, 999999) );
                $t = intval( rand(999999, 9999999) );

                if ( function_exists('the_generator') )
                {
                    // eliminate version for wordpress >= 2.4
                    remove_filter( 'wp_head', 'wp_generator' );
                    $actions = array( 'rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head' );
                    foreach ( $actions as $action ) {
                        remove_action( $action, 'the_generator' );
                    }

                    // for vars
                    $wp_version = $v;
                    $wp_db_version = $d;
                    $manifest_version = $m;
                    $tinymce_version = $t;
                }
                else {
                    // for wordpress < 2.4
                    add_filter( "bloginfo_rss('version')", create_function('$a', "return $v;") );

                    // for rdf and rss v0.92
                    $wp_version = $v;
                    $wp_db_version = $d;
                    $manifest_version = $m;
                    $tinymce_version = $t;
                }
            }
        }

        /**
         * remove WP Version-Information on Dashboard
         *
         * @package Secure WordPress
         */
        public function remove_wp_version_on_admin()
        {
            if ( !current_user_can('update_plugins') && is_admin() ) {
                wp_enqueue_script( 'remove-wp-version',  $this->get_plugins_url( 'js/remove_wp_version.js', __FILE__ ), array('jquery') );
                remove_action( 'update_footer', 'core_update_footer' );
            }
        }

        /**
         * remove core-Update-Information
         *
         * @package Secure WordPress
         */
        public function remove_core_update()
        {
            if ( !current_user_can('update_plugins') )
            {
                add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_notices', 'maintenance_nag' );" ) );
                add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_notices', 'update_nag', 3 );" ) );
                add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', '_maybe_update_core' );" ) );
                add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ) );
                add_filter( 'pre_option_update_core', create_function( '$a', "return null;" ) );
                remove_action( 'wp_version_check', 'wp_version_check' );
                remove_action( 'admin_init', '_maybe_update_core' );
                add_filter( 'pre_transient_update_core', create_function( '$a', "return null;" ) );
                // 3.0
                add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
                //wp_clear_scheduled_hook( 'wp_version_check' );
            }
        }

        /**
         * remove plugin-Update-Information
         *
         * @package Secure WordPress
         */
        public function remove_plugin_update()
        {
            if ( !current_user_can('update_plugins') )
            {
                wp_enqueue_style( 'remove-update-plugins', $this->get_plugins_url( 'css/remove_update_plugins.css', __FILE__ ) );
                add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_plugin_update_rows' );" ), 2 );
                add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', '_maybe_update_plugins' );" ), 2 );
                add_action( 'admin_menu', create_function( '$a', "remove_action( 'load-plugins.php', 'wp_update_plugins' );" ) );
                add_action( 'admin_init', create_function( '$a', "remove_action( 'admin_init', 'wp_update_plugins' );" ), 2 );
                add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_update_plugins' );" ), 2 );
                add_filter( 'pre_option_update_plugins', create_function( '$a', "return null;" ) );
                remove_action( 'load-plugins.php', 'wp_update_plugins' );
                remove_action( 'load-update.php', 'wp_update_plugins' );
                remove_action( 'admin_init', '_maybe_update_plugins' );
                remove_action( 'wp_update_plugins', 'wp_update_plugins' );
                // 3.0
                remove_action( 'load-update-core.php', 'wp_update_plugins' );
                add_filter( 'pre_transient_update_plugins', create_function( '$a', "return null;" ) );
                //wp_clear_scheduled_hook( 'wp_update_plugins' );
            }
        }

        /**
         * remove theme-Update-Information
         *
         * @package Secure WordPress
         */
        public function remove_theme_update()
        {
            if ( !current_user_can('edit_themes') )
            {
                remove_action( 'load-themes.php', 'wp_update_themes' );
                remove_action( 'load-update.php', 'wp_update_themes' );
                remove_action( 'admin_init', '_maybe_update_themes' );
                remove_action( 'wp_update_themes', 'wp_update_themes' );
                // 3.0
                remove_action( 'load-update-core.php', 'wp_update_themes' );
                //wp_clear_scheduled_hook( 'wp_update_themes' );
                add_filter( 'pre_transient_update_themes', create_function( '$a', "return null;" ) );
            }
        }

        /**
         * remove error-div
         *
         * @package Secure WordPress
         */
        public function remove_error_div()
        {
            global $wp_version;

            echo "\n";
            echo '<link rel="stylesheet" type="text/css" href="';
            echo $this->get_plugins_url( 'css/remove_login.css', __FILE__ );
            echo '" />';
            echo "\n";
        }

        /**
         * add string in blog for WP scanner
         *
         * @package Secure WordPress
         */
        public function wp_scanner() { echo '<!-- wpscanner -->' . "\n"; }

        /**
         * Removes the version parameter from urls
         *
         * @param  string $src Original script URI
         * @return string
         */
        public function filter_script_loader($src)
        {
            if ( is_admin() ) { return $src; }

            // Separate the version parameter.
            $src = explode('?ver=' . $this->wpversion, $src);

            // Just the URI without the query string.
            return $src[0];
        }

        /**
         * block bad queries
         *
         * @package Secure WordPress
         * @see http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/
         * @author Jeff Starr
         */
        public function wp_against_malicious_url_request()
        {
            global $user_ID;

            if ($user_ID)
            {
                if ( !current_user_can('manage_options') ) {
                    if (strlen($_SERVER['REQUEST_URI']) > 255 ||
                        stripos($_SERVER['REQUEST_URI'], "eval(") ||
                        stripos($_SERVER['REQUEST_URI'], "CONCAT") ||
                        stripos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
                        stripos($_SERVER['REQUEST_URI'], "base64"))
                        {
                            if (!headers_sent()) {
                                header("HTTP/1.1 414 Request-URI Too Long");
                                header("Status: 414 Request-URI Too Long");
                                header("Connection: Close");
                            }
                            exit;
                    }
                }
            }
        }

        /**
         * update options
         *
         * @package Secure WordPress
         */
        public function swp_update()
        {
            if ( !current_user_can('manage_options') ) {
                wp_die( __('Options not updated - you don&lsquo;t have the privileges to do this!', FB_SWP_TEXTDOMAIN) );
            }

            //cross check the given referer
            check_admin_referer('secure_wp_settings_form');

            $this->update();

            $referer = str_replace('&update=true&update=true', '', $_POST['_wp_http_referer'] );
            wp_redirect($referer . '&update=true' );
        }

        /**
         * uninstall options
         *
         * @package Secure WordPress
         */
        public function swp_uninstall()
        {
            if ( !current_user_can('manage_options') ) {
                wp_die( __('Entries were not deleted - you don&lsquo;t have the privileges to do this!', FB_SWP_TEXTDOMAIN) );
            }

            //cross check the given referer
            check_admin_referer('secure_wp_uninstall_form');

            if ( isset($_POST['deinstall_yes']) ) {
                $this->deactivate();
            }
            else { wp_die( __('Entries were not deleted - check the checkbox!', FB_SWP_TEXTDOMAIN) ); }

            wp_redirect( 'plugins.php' );
        }

        /**
         * display options page in backende
         *
         * @package Secure WordPress
         */
        public function display_page()
        {
            global $wp_version;

            if ( isset($_POST['action']) && 'deinstall' == $_POST['action'] ) {
                check_admin_referer('secure_wp_deinstall_form');
                if ( current_user_can('manage_options') && isset($_POST['deinstall_yes']) )
                {
                    $this->deactivate();
                    ?>
                    <div id="message" class="updated fade"><p><?php _e('All entries in the database were cleared.', FB_SWP_TEXTDOMAIN); ?></p></div>
                    <?php
                }
                else {
                    ?>
                    <div id="message" class="error"><p><?php _e('Entries were not deleted - check the checkbox or you don&lsquo;t have the privileges to do this!', FB_SWP_TEXTDOMAIN); ?></p></div>
                    <?php
                }
            }

            $secure_wp_error         = $GLOBALS['WPlize']->get_option('secure_wp_error');
            $secure_wp_version       = $GLOBALS['WPlize']->get_option('secure_wp_version');
            $secure_wp_admin_version = $GLOBALS['WPlize']->get_option('secure_wp_admin_version');
            $secure_wp_index         = $GLOBALS['WPlize']->get_option('secure_wp_index');
            $secure_wp_rsd           = $GLOBALS['WPlize']->get_option('secure_wp_rsd');
            $secure_wp_wlw           = $GLOBALS['WPlize']->get_option('secure_wp_wlw');
            $secure_wp_rcu           = $GLOBALS['WPlize']->get_option('secure_wp_rcu');
            $secure_wp_rpu           = $GLOBALS['WPlize']->get_option('secure_wp_rpu');
            $secure_wp_rtu           = $GLOBALS['WPlize']->get_option('secure_wp_rtu');
            $secure_wp_wps           = $GLOBALS['WPlize']->get_option('secure_wp_wps');
            $secure_wp_amurlr        = $GLOBALS['WPlize']->get_option('secure_wp_amurlr');

            $secure_wp_win_settings  = $GLOBALS['WPlize']->get_option('secure_wp_win_settings');
            $secure_wp_win_about     = $GLOBALS['WPlize']->get_option('secure_wp_win_about');
            $secure_wp_win_opt       = $GLOBALS['WPlize']->get_option('secure_wp_win_opt');
        ?>
        <div class="wrap">
            <div id="icon-acunetix" class="icon32" style="background: url('<?php echo $this->get_plugins_url( 'img/acunetix.png', __FILE__ ); ?>') no-repeat;"><br /></div>
            <h2><?php _e('Secure WordPress by WebsiteDefender', FB_SWP_TEXTDOMAIN); ?></h2>
            <br class="clear" />

            <div id="poststuff" class="ui-sortable meta-box-sortables poststuff poststuff_left">
                <div id="secure_wp_win_settings" class="postbox <?php echo $secure_wp_win_settings ?>" >
                    <div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
                    <h3><?php _e('Configuration', FB_SWP_TEXTDOMAIN); ?></h3>
                    <div class="inside">

                        <form name="secure_wp_config-update" method="post" action="admin-post.php">
                            <?php if (function_exists('wp_nonce_field') === true) {wp_nonce_field('secure_wp_settings_form');} ?>

                            <table class="form-table">

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_error"><?php _e('Error-Messages', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_error" id="secure_wp_error" value="1" <?php if ( $secure_wp_error == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Deactivates tooltip and error message at login of WordPress', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_version"><?php _e('WordPress Version', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_version" id="secure_wp_version" value="1" <?php if ( $secure_wp_version == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Removes version of WordPress in all areas, including feed, not in admin', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_admin_version"><?php _e('WordPress Version in Backend', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_admin_version" id="secure_wp_admin_version" value="1" <?php if ( $secure_wp_admin_version == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Removes version of WordPress on admin-area for non-admins. Show WordPress version of your blog only to users with the rights to edit plugins.', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_index"><?php _e('index.php', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_index" id="secure_wp_index" value="1" <?php if ( $secure_wp_index == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('creates an <code>index.php</code> file in <code>/plugins/</code> and <code>/themes/</code> to keep it from showing your directory listing', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_rsd"><?php _e('Really Simple Discovery', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_rsd" id="secure_wp_rsd" value="1" <?php if ( $secure_wp_rsd == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Remove Really Simple Discovery link in <code>wp_head</code> of the frontend', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_wlw"><?php _e('Windows Live Writer', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_wlw" id="secure_wp_wlw" value="1" <?php if ( $secure_wp_wlw == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Remove Windows Live Writer link in <code>wp_head</code> of the frontend', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_rcu"><?php _e('Core Update', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_rcu" id="secure_wp_rcu" value="1" <?php if ( $secure_wp_rcu == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Remove WordPress Core update for non-admins. Show message of a new WordPress version only to users with the right to update.', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_rpu"><?php _e('Plugin Update', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_rpu" id="secure_wp_rpu" value="1" <?php if ( $secure_wp_rpu == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Remove the plugin update for non-admins. Show message for a new version of a plugin in the install of your blog only to users with the rights to edit plugins.', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <?php if ( version_compare($wp_version, "2.8alpha", ">=") ) { ?>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_rtu"><?php _e('Theme Update', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_rtu" id="secure_wp_rtu" value="1" <?php if ( $secure_wp_rtu == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Remove the theme update for non-admins. Show message for a new version of a theme in the install of your blog only to users with the rights to edit themes.', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>
                            <?php } ?>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_wps"><?php _e('WP Version on Scripts/Styles', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_wps" id="secure_wp_wps" value="1" <?php if ( $secure_wp_wps == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Removes version of WordPress on the url form scripts and stylesheets only on frontend.', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

                                <tr valign="top">
                                    <th scope="row">
                                        <label for="secure_wp_amurlr"><?php _e('Block bad queries', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="secure_wp_amurlr" id="secure_wp_amurlr" value="1" <?php if ( $secure_wp_amurlr == '1') { echo "checked='checked'"; } ?> />
                                        <?php _e('Protect WordPress against malicious URL requests', FB_SWP_TEXTDOMAIN); ?>
                                    </td>
                                </tr>

<?php
//# 10/04/2011
$_checked = false;
$wsdRssWidgetVisible = get_option('WSD-RSS-WGT-DISPLAY');
if (empty($wsdRssWidgetVisible) || $wsdRssWidgetVisible=='yes') {
    add_option('WSD-RSS-WGT-DISPLAY', 'yes');
    $_checked = true;
}
else {
    if (strtolower($wsdRssWidgetVisible) == 'no') {
        $_checked = false;
    }
}
?>
                                <tr valign="top">
                                    <th scope="row">
                                        <label for="show_rss_widget"><?php _e('Dashboard RSS widget', FB_SWP_TEXTDOMAIN); ?></label>
                                    </th>
                                    <td>
                                        <input type="checkbox" name="show_rss_widget" id="show_rss_widget" <?php echo ($_checked ? 'checked="checked"' : '');?> />
                                        <label for="show_rss_widget"><?php _e("Show the WebsiteDefender News dashboard widget", FB_SWP_TEXTDOMAIN);?></label>
                                    </td>
                                </tr>
                            </table>

                            <p class="submit">
                                <input type="hidden" name="action" value="swp_update" />
                                <input type="submit" name="Submit" value="<?php _e('Save Changes', FB_SWP_TEXTDOMAIN); ?> &raquo;" class="button-primary" />
                            </p>
                        </form>

                    </div>
                </div>
            </div>

            <div id="poststuff" class="ui-sortable meta-box-sortables poststuff">
                <div id="secure_wp_win_opt" class="postbox <?php echo $secure_wp_win_opt ?>" >
                    <div class="handlediv" title="<?php _e('Click to toggle', FB_SWP_TEXTDOMAIN); ?>"><br/></div>
                    <h3><?php _e("About WebsiteDefender.com", FB_SWP_TEXTDOMAIN);?></h3>
                    <div class="inside">
                    <?php
                    /*
                     * $rev #1 07/15/2011 k$
                     * >> Display the WSD form
                     */
                     global $swwsd;
                     $swwsd->wsd_render_main();
                    ?>
                    </div>
                </div>
            </div>

            <div id="poststuff" class="ui-sortable meta-box-sortables poststuff  poststuff_left poststuff_clear">
                <div id="secure_wp_win_opt" class="postbox <?php echo $secure_wp_win_opt ?>" >
                    <div class="handlediv" title="<?php _e('Click to toggle'); ?>"><br/></div>
                    <h3 id="uninstall"><?php _e('Clear Options', FB_SWP_TEXTDOMAIN) ?></h3>
                    <div class="inside">

                        <p><?php _e('Click this button to delete the settings of this plugin. Deactivating Secure WordPress plugin removes any data that may have been created.', FB_SWP_TEXTDOMAIN); ?></p>
                        <form name="deinstall_options" method="post" action="admin-post.php">
                            <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('secure_wp_uninstall_form'); ?>
                            <p id="submitbutton">
                                <input type="hidden" name="action" value="swp_uninstall" />
                                <input type="submit" value="<?php _e('Delete Options', FB_SWP_TEXTDOMAIN); ?> &raquo;" class="button-secondary" />
                                <input type="checkbox" name="deinstall_yes" />
                            </p>
                        </form>

                    </div>
                </div>
            </div>

            <script type="text/javascript">var wordpress_site_name = "<?php echo htmlentities(get_bloginfo('url'));?>"</script>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('.postbox h3').click(function() { $($(this).parent().get(0)).toggleClass('closed'); } );
                    $('.postbox .handlediv').click(function() { $($(this).parent().get(0)).toggleClass('closed'); } );
                    $('.postbox.close-me').each(function() {
                        $(this).addClass("closed");
                    });
                });
            </script>
            <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
            <script type="text/javascript" src="https://dashboard.websitedefender.com/swpuser.php?FIELDS"></script>
            <script type="text/javascript" src="<?php echo $this->get_plugins_url( 'js/prepare_new_user_form.js', __FILE__ )?>"></script>
            <script type="text/javascript" src="<?php echo $this->get_plugins_url( 'js/verify_form.js', __FILE__ )?>"></script>

        </div>
        <?php
        }
    }
    /* End class: SecureWP.php */

}
/* End if (!class_exists('SecureWP')) */


if ( class_exists('WPlize') && function_exists('is_admin') ) {
	$SecureWP = new SecureWP();
}

?>