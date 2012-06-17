=== Verve Meta Boxes ===
Contributors: Komra Moriko, Vaughn Draughon
Donate link: http://www.avenueverve.com/
Tags: custom fields, admin, meta boxes, post image, page image, date, datetime, time, checkbox, textarea, select, radio, file upload
Requires at least: 2.8.0
Tested up to: 3.2
Stable tag: trunk

A robust, intuitive, polished custom fields plugin provides text, textarea, image, file, date, time, datetime, select, radio, and checkbox fields.

== Description ==

Verve Meta Boxes is a robust custom fields plugin with an intuitive, polished interface that allows for creation of text, textarea, image, file, date, time, datetime, select, radio, and checkbox custom fields for posts and/or pages.

Values for custom fields are stored in wp_postmeta and can be accessed in templates through standard Wordpress functions such as: the_meta, get_post_meta, get_post_custom, get_post_custom_values, etc.

Once installed, you will find a configuration screen located under Tools in the left navigation. There you can create multiple meta boxes each with a set of user defined drag and drop sortable custom fields. See the screenshots for more detail.

For custom post types, we recommend installing [Custom Post Type UI](http://wordpress.org/extend/plugins/custom-post-type-ui/) or [CMS Press](http://wordpress.org/extend/plugins/cms-press/). 

== Installation ==

Installing Verve Meta Boxes.

1. Upload `verve-meta-boxes` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php get_post_custom_values('your_custom_field_key_here'); ?>` inside the Loop in your templates

Note - Verve Meta Boxes supports custom post types. There are a number of plugins that allow you to set up custom post types. We recommend [Custom Post Type UI](http://wordpress.org/extend/plugins/custom-post-type-ui/). You must check "supports: custom-fields" for Verve Meta Boxes to recognize the custom post type.

== Frequently Asked Questions ==

= I can't get the values to save when I am creating checkbox or select fields. =

When entering comma separated lists into the checkbox or select fields, you must hit enter or return for the information to be saved to the database.

= Upload feature doesnt seem to work. When I browse and select I have no option to upload =

Just hit the Save Draft or Publish button, that will do it.

= I created custom post types but they don't show up in Verve Meta Boxes =

There are a number of plugins that allow you to set up custom post types. We recommend [Custom Post Type UI](http://wordpress.org/extend/plugins/custom-post-type-ui/). You must check __Supports__ *Custom Fields* for Verve Meta Boxes to recognize the custom post type.

== Screenshots ==

1. You will find a link to Verve Meta Boxes admin panel in the Tools navigation section. Create a meta box, then add custom fields to it. You can drag and drop to order fields within meta boxes. You can click to edit-in-place field labels and add/edit comma separated values for checkbox and select fields. Remember to hit return or enter to save your edit-in-place changes.
2. This screenshot shows a post page with multiple types of fields in two different meta boxes ("My Meta Box" and "For the Press"). You can drag and drop them to position them conveniently on editing screens. 
3. On edit screens, you may also collapse or hide meta boxes.

== Changelog ==

= 1.2.9 =
* patch by Hiroki integrated. see http://wordpress.org/support/topic/plugin-cms-tree-page-view-custom-field-values-can-get-lost
* update timthumb.php to version 2.8

= 1.2.8 =
* update to work with Wordpress 3.2.
* update timthumb.php
* update date-picker.js
* fix to location of content directory, props to Nate Mackey
* fix issue with single checkbox (author recommends using radio buttons instead of single checkboxes)
* removed qtip (unused)

= 1.2.6.1 =
* bug fix on losing custom fields values when trashing post.

= 1.2.6 =
* bug fix on thumbnails not showing up for single blog sites.
* bug fix if no context was specified for box

= 1.2.5 =
* Bug fix on fields with values of zero being considered empty. 
* Modified to work with multi-sites.
* Fixed bug in time field.

= 1.2.4 =
* Bug fix on time fields.

= 1.2.3 =
* Changed the image cache directory to be contained within verve-meta-boxes/tools directory to avoid permissions problems causing images to not show up on edit screen.
* Fixed bug on checkboxes, where is was impossible to deselect all checkboxes.

= 1.2.2 =
* Bug fixes to handle PHP strict environment and compatibility with Custom Post Types UI.

= 1.2.1 =
* Modified code to work in environments that utilize PHP strict.

= 1.2.0 =
* Added time and datetime fields.

= 1.1.2 =
* Fixed bug to maintain backward compatiblity with WP 2.8.x - 2.9.x.

= 1.1.1 =
* Code optimization to filter post types by whether or not they support custom-fields
* Fixed bug where file uploads did not work in IE.

= 1.1.0 =
* Made compatible with custom post types in Wordpress 3.0

= 1.0.1 =
* Fixed bug where users could not delete content from textarea and text fields
* Added instructions when adding comma separated values to checkbox and radio fields, you must hit enter for your changes to take effect.

= 1.0 =
* Initial public release

== Upgrade Notice ==

= 1.2.5 =
* Bug fix on fields with values of zero being considered empty. 
