=== WP Email Login ===
Contributors: beaulebens, r-a-y, andykillen
Tags: email, login, authentication, users, admin
Requires at least: 2.8
Tested up to: 3.4
Stable tag: trunk

Use your email address instead of a username to log into your WordPress.

== Description ==
Use your email address instead of a username to log into your WordPress.

Since email addresses are required to be unique within WordPress anyway, they also make good identifiers for logging in. For slightly better security, set your username to something random and then just forget it and use your email address instead.

Special thanks to:

* r-a-y for compatibility with older versions of WPMU and XML-RPC
* andykillen for introducing translatable strings and the Dutch translation

Translations available for:

* German
* Polish
* Finnish
* Swedish
* Persian
* French

== Installation ==
1. Unzip and upload `/wp-email-login/` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Log out, log in again using the email address associated with your WordPress account.

== Changelog ==
= 4.3.1 =
* Add Brazilian Portuguese translation, props Alysson http://profiles.wordpress.org/alyssonweb

= 4.3 =
* Check user_status to confirm the user is verified before allowing authentication (used in BuddyPress). Props Steve Holland. If you have a plugin which is using user_status to store some non-zero value, then those users will *not* be able to log in using their email address with this update.

= 4.2.3 =
* Add Lithuanian translation, props Vincent G, from http://www.host1free.com

= 4.2.2 =
* Add Czech translation, props Zaantar

= 4.2.1.1 =
* Syntax error -- SORRY!

= 4.2.1 =
* Switch to get_user_by() to avoid deprecated notice, props benjaminniess

= 4.2 =
* Move translations into /languages/
* Extra check to make sure a variable is defined, props Horacio
* Updated to both Dutch translations to fix a typo, thanks Michael! http://www.concatenate.nl

= 4.1.6 =
* Add German translation, props Florian

= 4.1.5 =
* Add Polish translation, props Piotr

= 4.1.4 =
* Add Finnish translation, props Jarno (http://www.daddyfinland.fi/)
* Tweak JS replacement to look for core translation and replace with plugin-specific one
* Update translation files to have correct project name etc

= 4.1.3.1 =
* Fix syntax errors. SORRY!

= 4.1.3 =
* Swedish translation, props Joel
* Persian translation, props Sushyant (http://www.zavarzadeh.org)
* Better escaping on string output

= 4.1.2 =
* Include French translation, props Sebastien
* Rename translation files to standard format

= 4.1.1 =
* Minor fix for if the username is empty, props Sebastien

= 4.1 =
* Clean up code a bit
* Introduce translatable strings, props andykillen. Packaged with Dutch translation.

= 4.0 =
* Add prompt to login form that you can use Email as well
* Use get_user_by_email(), props Hendry (via email)
* Remove support for versions older than 2.8 -- UPGRADE!

= 3.0 =
* Cut down to use new filters

= 2.0 =
* Now supports XML-RPC authentication using email address thanks to r-a-y!

= 1.0 =
* Initial release