<?php
if ( !class_exists( 'TwitterProWidget' ) ) {
	class TwitterProWidget extends WP_Widget {
		
		function TwitterProWidget( ) {
			$widget_ops = array('classname' => 'twitter-pro-widget', 'description' => __( 'List Tweets from any or all users on the blog with display options for date, photo and author info.' ) );
			$this->WP_Widget('twitter-pro-widget', __('Twitter Pro Widget'), $widget_ops);
		}

		function widget( $args, $instance ) {
			if ( function_exists( 'sp_get_tweets' ) ) {
				extract( $args, EXTR_SKIP );
				$tweets = sp_get_tweets( $instance['users'], (int)$instance['count'],  $instance['excludeReplies'] );
				if ( $tweets ) {
					echo $before_widget;
					$title = apply_filters('widget_title', $instance['title'] );
					if ( $title ) {
						echo $before_title . $title . $after_title;
					}
					sp_the_tweets( $instance['users'], (int)$instance['count'], array(
						'displayDate' => $instance['displayDate'],
						'displayPhoto' => $instance['displayPhoto'],
						'displayAuthor' => $instance['displayAuthor'],
						'excludeReplies' => $instance['excludeReplies'],
						'displayFormat' => 'li'						
					) );					
					
					echo $after_widget;
				}
			}
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['count'] = strip_tags( $new_instance['count'] );
			$instance['users'] = $new_instance['users'];
			$instance['displayAuthor'] = ( $new_instance['displayAuthor'] == 'true' ) ? true : false;
			$instance['displayPhoto'] = ( $new_instance['displayPhoto'] == 'true' ) ? true : false;
			$instance['displayDate'] = ( $new_instance['displayDate'] == 'true' ) ? true : false;
			$instance['excludeReplies'] = ( $new_instance['excludeReplies'] == 'true' ) ? true : false;
			return $instance;
		}

		function form( $instance ) {
			global $TwitterPro;
			$users_with_twitter_accounts = $TwitterPro->getTwitterUsers();
			if ( count($users_with_twitter_accounts) > 0 ) {
				$instance = wp_parse_args( (array) $instance, array( 
					'title' => __( 'Latest Tweets', $TwitterPro->pluginDomain ), 
					'count' => 5, 
					'users' => $users_with_twitter_accounts,
					'displayAuthor' => true,
					'displayPhoto' => true,
					'displayDate' => true, 
					'excludeReplies' => true, 
				) );
				$title = esc_attr( $instance['title'] );
				$count = abs( $instance['count'] );
				$users = (array) $instance['users'];
				$displayAuthor = (boolean) $instance['displayAuthor'];
				$displayPhoto = (boolean) $instance['displayPhoto'];
				$displayDate = (boolean) $instance['displayDate'];
				$excludeReplies = (boolean) $instance['excludeReplies'];
			}

			include( $TwitterPro->pluginPath . '/views/widget-admin.php' );			

		}

	}

	if( !function_exists( 'sp_twitter_pro_widget' ) && function_exists('register_widget') ) {
		function sp_twitter_pro_widget() {
			register_widget( 'TwitterProWidget' );
		}
		add_action( 'widgets_init', 'sp_twitter_pro_widget' );
	}
}
?>