=== Secure WordPress ===
Contributors: WebsiteDefender
Author: WebsiteDefender
Tags: secure, notice, hack, hacked, protection, version, security
Requires at least: 3.0
Tested up to: 3.3
Stable tag: trunk

WordPress Security Plugin

== Description ==
Secure WordPress beefs up the security of your WordPress installation by removing error information on login pages, adds index.html to plugin directories, hides the WordPress version and much more.

1. Removes error-information on login-page
1. Adds index.php plugin-directory (virtual)
1. Removes the wp-version, except in admin-area
1. Removes Really Simple Discovery
1. Removes Windows Live Writer
1. Removes core update information for non-admins
1. Removes plugin-update information for non-admins
1. Removes theme-update information for non-admins (only WP 2.8 and higher)
1. Hides wp-version in backend-dashboard for non-admins
1. Removes version on URLs from scripts and stylesheets only on frontend
1. Blocks any bad queries that could be harmful to your WordPress website

= Requirements =
* WordPress version 3.0 and higher (tested with 3.2.1, 3.3)
* PHP5 (tested with PHP Interpreter >= 5.2.9)

= Localizations =
Idea, first version and german translation by [Frank B&uuml;ltge](http://bueltge.de "bueltge.de"), Italian translation by [Gianni Diurno](http://gidibao.net/ "gidibao.net"), Polish translation by Michal Maciejewski, Belorussian file by [Fat Cow](http://www.fatcow.com/ "www.fatcow.com"), Ukrainian translation by [AzzePis](http://wordpress.co.ua/plugins/ "wordpress.co.ua/plugins/"), Russian language by [Dmitriy Donchenko](http://blogproblog.com/ "blogproblog.com"), Hungarian language files by [K&ouml;rmendi P&eacute;ter](http://www.seo-hungary.com/ "www.seo-hungary.com"), Danish language files by [GeorgWP](http://wordpress.blogos.dk/s%C3%B8g-efter-downloads/?did=175 "S&oslash;g efter downloads")m Spanish language files by [Pablo Jim&eacute;nez](http://www.ministeriosccc.org "www.ministeriosccc.org"), Chinese language (zh_CN) by [tanghaiwei](http://dd54.net), French translation files by [Jez007](http://forum.gmstemple.com/ "forum.gmstemple.com"), Japanese translation by [Fumito Mizuno](http://ounziw.com/ "Standing on the Shoulder of Linus"), Dutch translation by [Rene](http://wpwebshop.com "wpwebshop.com"), Persian language files by [ALiRezaCH](http://alirezach.co.cc), Romanian translation by [ Selco Resita]( http://www.selco-computers.ro "selco-computers.ro") and Arabic language files by [مدونة](http://www.r-sn.com/wp), Turkish translation by [Nightmare17] (http://sanalespri.com). WebsiteDefender would like to thank everyone that worked on making Secure WordPress a success.

== Installation ==
1. Make a backup of your current installation
1. Unpack the download-package
1. Upload the extracted files to the /wp-content/plugins/ directory
1. Configure the desired options and activate the plugin from the 'Plugins' menu in WordPress

If you do encounter any bugs, or have comments or suggestions, please contact the WebsiteDefender team on support@websitedefender.com

== Screenshots ==
1. options-area (WordPress 3.1)


== Other Notes ==
= License =
Good news, this plugin is free for everyone! Since it's released under the GPL, you can use it free of charge on your personal or commercial blog.

= Translations =
The plugin comes with various translations, please refer to the [WordPress Codex](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") for more information about activating the translation. If you want to help to translate the plugin to your language, please have a look at the .pot file which contains all defintions and may be used with a [gettext](http://www.gnu.org/software/gettext/) editor like [Poedit](http://www.poedit.net/) or the very fine plugin [CodeStyling Localization](http://www.code-styling.de/english/development/wordpress-plugin-codestyling-localization-en "Codestyling Localization") for WordPresss.


== Changelog ==

= v2.0.7 =
* Update: Updated the deprecated function call get_bloginfo('siteurl') to get_bloginfo('url')
* Update: Updated validation for plug-in form fields (email address, user name, target id, etc.)

= v2.0.6 =
* New setting: Option to open / close WebsiteDefender dashboard widget
* Update: Internal code updates

= v2.0.5 =
* BugFix: The bug reported about ALTER rights retrieval has been addressed
* Update: Code cleanup
* Update: Minor internal updates

= v2.0.4 =
* Feature: The WebsiteDefender RSS widget added to the admin dashboard
* Update: The plug-in has been made compatible with WP Security Scan and WebsiteDefender WordPress Security
* Feature: Turkish language files added.

= v2.0.3 (07/21/2011) =
* Bugfix: The import of external resources has been fixed.

= v2.0.2 (07/20/2011) =
* Bugfix: Updated the links to websitedefender.com

= v2.0.1 (07/20/2011) =
* Update: Major code cleanup
* Update: Updated the class that handles the authentication/registration with WebsiteDefender.com in order to avoid code collision when both plug-ins are active.
* New: Dependent files (.css/.js/.php) have been added

= v2.0.0 (03/22/2011) =
* Feature: Release new stable version
* Feature: Support for WordPress 3.1
* Feature: Change owner of the plugin to WebsiteDefender
* Feature: Re-branding of the plugin
* Feature: Integrated WebsiteDefender registration in Settings

= v1.0.6 (11/15/2010) =
* Bugfix: change from `public` to `var` for variables to use the plugin on PHP5.2 and smaller

= v1.0.5 (11/10/2010) =
* Feature: Remove WordPress version on urls form scripts and stylesheets
* Maintenance: rescan and update german language file
* Remove: exclude to add string fpr wp-scanner-service; Wish of the community users

= v1.0.4 (10/09/2010 =
* Bugfix: update options

= v1.0.3 (10/06/2010) =
* Bugfix: include JS for remove version in backend for Non-Admins
* Bugfix: change for php-warning at update options
* Maintenance: update italien language files
* Maintenance: update german language files
* Maintenance: update pot file

= v1.0.2 (09/10/2010) =
* add persian language file
* change the backend; remove WP Scanner function
* change the include of javascript for metaboxes

= v1.0.1 (08/06/2010) =
* add more hooks to remove WordPress Version; was change with WP3.0

= v1.0 (07/09/2010) =
* relese stable version
* small changes on the source
* change owner of the plugin

= v0.8.6 (06/18/2010) =
* fix a problem with https://; see [Ticket #13941](http://core.trac.wordpress.org/ticket/13941)

= v0.8.5 (05/16/2010) =
* small code changes for WP coding standards
* add free malware and vulnerabilities scan for test this; the scan has most interested informations and scan all of the server

= v0.8.4 (05/05/2010) =
* add methode for use the plugin also on ssl-installs
* change uninstall method

= v0.8.3 (04/14/2010) =
* bugfix fox secure block bad queries on string for case-insensitive

= v0.8.2 (03/21/2010) =
* fix syntax error on ask for rights to block bad queries
* add french language files

= v0.8.1 (03/08/2010) =
* remove versions-informations on backend with javascript
* small changes

= v0.8 (03/04/2010) =
* Protect WordPress against malicious URL requests, use the idea and script from Jeff Star, [see post](http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/ "Protect WordPress Against Malicious URL Requests")

= v0.7 (03/01/2010) =
* add updates for WP 3.0

= v0.6 (01/11/2010) =
* fix for core update under WP 2.9
* fix language file de_DE

= v0.5 (12/22/2009) =
* small fix for use WP and the plugin with SSL `https`

= v0.4 (12/02/2009) =
* add new feature: hide version for smaller right as admin

= v0.3.9 (09/07/2009) =
* change index.html in index.php for better works

= v0.3.8 (06/22/2009) =
* add function to remove theme-update information for non-admins
* rescan language file; edit de_DE