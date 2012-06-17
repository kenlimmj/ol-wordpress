<?php

/*

$userIds is a variable available in many of the template tags.  $userIds can be 
* an integer representing a user ID
* an array of user IDs
* either empty, false or 'all' to represent ALL users on the blog.

ALL parameters are optional in ALL template tags.

*/

function sp_get_tweets( $userIds = false, $count = 1, $excludeReplies = false ) {
	global $TwitterPro;
	$tweets = $TwitterPro->getTweets( $userIds, $count, $excludeReplies );
	if ( isset( $tweets->errors ) && sizeof( $tweets->errors ) > 0 ) {
		return false;
	} else {
		return apply_filters( 'sp_get_tweets', $tweets, $userIds, $count );
	}
}

function sp_the_tweets( $userIds = false, $count = 1, $options = array() ) {
	/* 
	$options can include:
	displayAuthor (boolean)
	displayFormat ('li' or 'div')
	displayPhoto (boolean)
	displayDate (boolean)
	excludeReplies (boolean)
	*/
	
	// set defaults:
	$displayDate = false;
	$displayPhoto = false;
	$displayAuthor = false;
	$excludeReplies = false;

	extract($options);
	
	if ( 
		( $count > 1 && !isset( $displayFormat ) ) || // More than one tweets expected and format not set
		( isset( $displayFormat ) && $displayFormat == 'li' ) // Format set to li explicitely.
	) {
		$multiple = true;
	}
	
	$tweets = sp_get_tweets( $userIds, $count, $excludeReplies );
	
	global $TwitterPro;
	include( $TwitterPro->pluginPath . '/views/tweets.php' );
	
	echo apply_filters( 'sp_the_tweets', $output, $userIds, $count );
}

function sp_get_twitter_username( $userId = false ) {
	global $TwitterPro;
	return apply_filters( 'sp_get_twitter_username', $TwitterPro->getTwitterUserName( $userId ), $userId );	
}

function sp_the_twitter_username( $userId = false ) {
	echo apply_filters( 'sp_the_twitter_username', sp_get_twitter_link( $userId ), $userId );
}

function sp_get_linked_twitter_username( $userId = false ) {
	$twitter_username = sp_get_twitter_username( $userId );
	if ( $twitter_username ) {
		return apply_filters( 'sp_get_linked_twitter_username', '<a href="'.sp_get_twitter_link( $userId ).'" class="twitter-link" target="twitter">@'.$twitter_username.'</a>', $userId );	
	}	
}

function sp_the_linked_twitter_username( $userId = false ) {
	echo apply_filters( 'sp_the_linked_twitter_username', sp_get_linked_twitter_username( $userId ), $userId );
}

function sp_get_twitter_link( $userId = false ) {
	global $TwitterPro;
	return apply_filters( 'sp_get_twitter_link', $TwitterPro->getTwitterUserLink( $userId ), $userId );
}

function sp_the_twitter_link( $userId = false ) {
	echo apply_filters( 'sp_the_twitter_link', sp_get_twitter_link( $userId ), $userId );
}

// $twitterUser is the 'user' portion of the array returned in a tweet
function sp_get_twitter_thumbnail( $twitterUser = false ) {
	global $TwitterPro;
	return apply_filters( 'sp_get_twitter_thumbnail', $TwitterPro->getTwitterThumb( $twitterUser ), $twitterUser );
}

function sp_the_twitter_thumbnail( $twitterUser = false ) {
	echo apply_filters( 'sp_the_twitter_thumbnail', '<img src="'.sp_get_twitter_thumbnail( $twitterUser ).'"/>', $twitterUser );
}
?>