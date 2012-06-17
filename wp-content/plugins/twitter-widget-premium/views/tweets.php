<?php

$output = "<!-- Twitter Pro Widget: http://codecanyon.net/item/twitter-widget-pro-wordpress-premium-plugin/109372?ref=Widget -->\n";

if ( $tweets ) {
	
	if ( $multiple ) { 
		$output .= "<ul class=\"tweets\">\n"; 
		$displayFormat = 'li';
	} else {
		$displayFormat = 'div';
	}

	foreach ( $tweets as $k => $v ) {
		$output .= "<!-- Cached: " . $v['cached_at'] . " -->\n";
		$output .= "<$displayFormat class=\"tweet\">\n";
		
		// TODO: offer wordpress thumb / twitter thumb option
		if ( $displayPhoto ) {	
			$output .= '<a href="'.sp_get_twitter_link($v['user']['wordpress_user_id']).'" target="twitter" class="twitter-link">';
			$output .= '<div class="tweet-thumb"><img src="' . sp_get_twitter_thumbnail( $v['user'] ) . '"></div>';
			$output .= '</a>'."\n";
		}
		
		$output .= '<div class="tweet-text">'.$v['text'].'</div>'."\n";
		
		// TODO: update format to include fuzzy date logic
		if ( $displayDate ) {	
			$output .= '<div class="tweet-date">';
			$output .= date_i18n( get_option('date_format'),$v['created_at'] ); // date
			$output .= ' '.__('at').' ';
			$output .= date_i18n( get_option('time_format'),$v['created_at'] ); // time
			$output .= '</div>'."\n";
		}
		
		if ( $displayAuthor ) {
			$output .= '<div class="tweet-user">'.sp_get_linked_twitter_username($v['user']['wordpress_user_id']).'</div>'."\n";
		}
		
		$output .= "</$displayFormat>\n";
	}

	if ( $multiple ) { $output .= "</ul>"; }

}
?>