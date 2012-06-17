=== Twitter Pro ===

Contributors: Peter Chester, produced by Shane & Peter, Inc.
Tags: widget, twitter, tweet, social media, shortcode, plugin, sidebar, wordpress
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 1.1

== Description ==

The Twitter Pro plugin allows users on your blog to display their latest tweets either via embedded template tags or via widgets.

= Twitter Pro =

* Widget is ready to use out of the box
* Template Tags available for template customization
* Automatically links Hashtags, twitter users and links
* Caching support, both via wp_cache_set and via database options so as to avoid exceeding API call restrictions on twitter.
* Graceful crashing support incase, heaven forbid, twitter goes down.
* Extremely clean premium quality code

= Upcoming Features =

* Shortcode support
* Live update support so that tweets appear without reloading the page

== Installation ==

= Install =

1. Unzip plugin file. 
1. Upload the the plugin folder (not just the files in it!) to your `wp-content/plugins` folder. If you're using FTP, use 'binary' mode.
1. Activate the plugin in your plugin in your plugins administration panel.
1. Either visit the settings panel or the user administration panels to set the twitter usernames for users on your blog.
1. If you have trouble installing, see the [Codex](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation) for more helpful info.

== Documentation ==

= Shortcode =

[sp-twitter]
[sp-twitter users=1,2 count=10 displayauthor=1 displayphoto=1 displaydate=1 excludereplies=1 displayFormat=li]

= Template Tags =

$userIds is a variable available in many of the template tags.  $userIds can be 
* an integer representing a user ID
* an array of user IDs
* either empty, false or 'all' to represent ALL users on the blog.

ALL parameters are optional in ALL template tags.

**sp_get_tweets( $userIds, $count )**
Returns an array of tweets with associated meta data.

**sp_the_tweets( $userIds, $count, $options )**
Prints a list of tweets. If there $count is set to more than 1 then results are printed in an unordered list.

$options can include:
* displayAuthor (boolean)
* displayFormat ('li' or 'div')
* displayPhoto (boolean)
* displayDate (boolean)
* excludeReplies (boolean)

**sp_get_twitter_username( $userId )**
Return the twitter username for a wordpress user

**sp_the_twitter_username( $userId )**
Print the twitter username for a wordpress user

**sp_get_linked_twitter_username( $userId )**
Return the twitter username linked and prefaced with an @ symbol for a wordpress user

**sp_the_linked_twitter_username( $userId )**
Print the twitter username linked and prefaced with an @ symbol for a wordpress user

**sp_get_twitter_link( $userId )**
Return the twitter link for a wordpress user

**sp_the_twitter_link( $userId )**
Print the twitter link for a wordpress user

**sp_get_twitter_thumbnail( $twitterUser )**
Return the url of the twitter user thumbnail
$twitterUser is the 'user' portion of the array returned in a tweet

**sp_the_twitter_thumbnail( $twitterUser )**
Print the image tag for the twitter user thumbnail
$twitterUser is the 'user' portion of the array returned in a tweet

== Screenshots ==

1. Twitter Pro admin panel
1. Twitter Pro widget admin
1. Twitter Pro widget

== FAQ ==

Q: My Twitter Pro widget is not visible
A: If for any reason your site is unable to connect to Twitter, then the widget will not appear.

== Changelog ==

= 1.0 =

* Initial plugin release.

= 1.0.1 =

* Upgrade caching to support caching through twitter outages

= 1.1 =

* Ability to exclude @replies
* Shortcode support