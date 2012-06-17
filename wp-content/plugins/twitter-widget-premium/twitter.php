<?php
/**
 * @package Twitter Pro
 * @version 1.1
 */
/*
Plugin Name: Twitter Pro
Plugin URI: http://plugins.shaneandpeter.com/twitter-pro
Description: The Twitter Pro plugin allows users on your blog to display their latest tweets either via embedded template tags or via widgets.
Author: Shane & Peter, Inc.
Version: 1.1
Author URI: http://shaneandpeter.com
*/

if ( !class_exists( 'TwitterPro' ) ) {
	class TwitterPro {
		
		private $_name = 'twitter-pro';
		private $_key_user = 'twitterprouser';
		private $_cache_timeout = 30; // seconds (Twitter allows 150 hits per hour = minimum caching of 24 seconds)
		private $_nonce = 'twitter-pro-nonce';
		private $_options = 'TwitterProOptions';
		private $_options_css = 'css-options';
		private $_options_widget = 'widget-options';
		public $pluginDir;
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		public $pluginSlug = 'twitter-pro';
		public $pluginDomain = 'twitter-pro';
		public $supportUrl = 'http://plugins.shaneandpeter.com/twitter-pro';

		public function __construct() {
			$this->addActions();
			$this->addShortcodes();
			$this->pluginName = __( 'Twitter Pro', $this->pluginDomain );
			$this->pluginDir = basename( dirname(__FILE__) );
			$this->pluginPath = WP_PLUGIN_DIR . '/' . $this->pluginDir;
			$this->pluginUrl = WP_PLUGIN_URL . '/' . $this->pluginDir;
		}

		private function addActions() {
			add_action( 'init', array( $this, 'enqueueResources' ) );
			add_action( 'show_user_profile', array( $this, 'showProfile' ) );
			add_action( 'edit_user_profile', array( $this, 'showProfile' ) );
			add_action( 'profile_update', array( $this, 'updateProfile' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'addAdminScriptsAndStyles' ) );
			add_action( 'admin_menu', array( $this, 'addOptionsPage' ) );
			add_action( 'admin_init', array( $this, 'saveSettingsForm' ) );
		}
		
		public function addAdminScriptsAndStyles() {
			wp_enqueue_style( 'twitter-pro-admin', plugins_url( $this->pluginDir . '/resources/admin.css' ) );
		}

		public function enqueueResources() {
			if ( $this->getOptions( $this->_options_css ) ) {
				wp_enqueue_style( 'twitter-pro', plugins_url( $this->pluginDir . '/resources/style.css' ) );
			}
		}

		public function addOptionsPage() {
    		add_options_page( $this->pluginName, $this->pluginName, 'manage_options', $this->pluginSlug, array($this,'optionsPageView') );		
		}

		public function optionsPageView() {
			include( $this->pluginPath . '/views/settings.php' );
		}
		
		public function saveSettingsForm() {
			if ( isset($_POST[$this->pluginDomain.'-save']) && check_admin_referer( $this->_nonce ) ) {

                $options = $this->getOptions();
				$options[$this->_options_css] = ( isset( $_POST[$this->_options_css] ) ) ? true : false;
				$options[$this->_options_widget] = ( isset( $_POST[$this->_options_widget] ) ) ? true : false;
				$this->saveOptions( $options );

				foreach( get_users_of_blog() as $k => $user ) {
					$this->updateProfile( $user->ID );
				}
			}
		}

		public function getOptions( $option = false ) {
			$options = get_option( $this->_options, array(
				$this->_options_css => true,
				$this->_options_widget => true
			) );
			if ( $option ) {
				return $options[ $option ];
			} else {
				return $options;
			}
		}

		private function saveOptions( $options ) {
			if ( is_array( $options ) ) {
				update_option( $this->_options, $options );
			}
		}
	
		/**
		 * Get all users with a twitter account
		 *
		 * @return array() users
		 */
		public function getTwitterUsers( $userIds = false ) {
			global $wpdb;
			
			if ( is_numeric( $userIds ) && $userIds > 0 ) {
				return array( $userIds );
			}
			
			if ( is_array( $userIds ) ) {
				$requestid = md5(join( '-', $userIds ));
 				$cache = (array) wp_cache_get( $this->_options, 'userlist'.$requestid );
				if ( count( $cache ) > 1 && $cache[0] > 1) {
					$twitter_user_ids = $cache;
				} else {
					$twitter_user_ids = array();
					foreach ( (array) $userIds as $userId ) {
						if ( $this->getTwitterUserName( $userId ) ) {
							$twitter_user_ids[] = $userId;
						}
					}
					wp_cache_set( $this->_options, $twitter_user_ids, 'userlist'.$requestid );
				}
				return $twitter_user_ids;
				//return $wpdb->get_col( "SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE meta_key = '$this->_key_user' AND user_id IN (".join(',',$userIds).")" );
			}

			// if no user ids are specified then return all users with twitter usernames within the current blog
			$users = get_users_of_blog();
			$cache = (array) wp_cache_get( $this->_options, 'userlist' );
			if ( count( $cache ) > 1 && $cache[0] > 1) {
				$twitter_user_ids = $cache;
			} else {
				$twitter_user_ids = array();
				foreach ( (array) $users as $user ) {
					if ( $this->getTwitterUserName( $user->ID ) ) {
						$twitter_user_ids[] = $user->ID;
					}
				}
				wp_cache_set( $this->_options, $twitter_user_ids, 'userlist' );
			}			
			return $twitter_user_ids;
		}
		
		public function showProfile() {
		    global $profileuser;
			$fieldname = $this->_key_user . '-' . $profileuser->ID;
			$fieldvalue = esc_attr($profileuser->{$this->_key_user});
			include( $this->pluginPath . '/views/user-account.php' );
		}
		
		public function updateProfile( $userId ) {
			$fieldname = $this->_key_user . '-' . $userId;
			if ( isset( $_POST[ $fieldname ] ) && !empty( $_POST[ $fieldname ] ) ) {
				update_usermeta( $userId, $this->_key_user, stripslashes_deep( $_POST[ $fieldname ] ) );
			} else {
				delete_usermeta( $userId, $this->_key_user );				
			}
		}
		
		public function getTweets( $userIds = false, $count = 1, $excludeReplies = false ) {
			$userIds = $this->getTwitterUsers( $userIds );
			$results = array();
			foreach ( $userIds as $k => $userId ) {
				$twitteruser = $this->getTwitterUserName( $userId );
				$tweetcache = $this->getCache( $userId );
				if ( !$tweetcache || $tweetcache['cachecount'] < $count ) {
					$url = 'http://twitter.com/statuses/user_timeline.xml?screen_name='.urlencode( $twitteruser ).'&count='.$count;
					$response = wp_remote_get( $url );
					if (!is_wp_error($response)) {			
						$xml = simplexml_load_string($response['body']);
						if( empty( $xml->error ) ) {
							if ( count($xml->status) > 0 ) {
								$tweetcache = array();
								$tweetcache['statuses'] = array();
								foreach ($xml->status as $s) {
									$tweet = (string) $s->text;
									if ( $excludeReplies && '@' == substr( $tweet, 0, 1 ) ) {
										// don't add this tweet because it is a reply and replies have been excluded.
									} else {
										$tweetcache['statuses'][strtotime( $s->created_at )] = array(
											'id' => (int)$s->id,
											'text' => $this->tweetFilter( $tweet ),
											'created_at' => strtotime( $s->created_at ),
											'cached_at' => mktime(),
											'user' => array(
												'id' => (int)$s->user->id,
												'name' => (string)$s->user->name,
												'screen_name' => (string)$s->user->screen_name,
												'profile_image_url' => (string)$s->user->profile_image_url,
												'wordpress_user_id' => $userId,
											),
										);
									}
								}
							}
						}
					}
					$tweetcache['cachecount'] = $count;
					$tweetcache['lastupdated'] = mktime();
					$this->setCache( $userId, $tweetcache );
				}
				if ( isset( $tweetcache['statuses'] ) && is_array( $tweetcache['statuses'] ) ) {
					$results += $tweetcache['statuses'];
				}
			}

			krsort($results); // sort by date in reverse to mix multiple feeds.

			if (isset($results) && is_array($results) && count($results) > 0) {
				return array_slice( $results, 0, $count );
			} else {
				return array(); // Connection to twitter failed and there is no cache.
			}
		}
		
		private function addShortcodes() {
			add_shortcode('sp-twitter', array($this, 'doShortcode'));
		}
		
		public function doShortcode( $atts = array(), $content = null ) {
			$atts = shortcode_atts(array(
				'users' => false,
				'count' => 1,
				'displayauthor' => true,
				'displayphoto' => true,
				'displaydate' => true, 
				'excludereplies' => true,
				'displayformat' => 'li',
			), $atts);
			if ( $atts['displayphoto'] == 'false' ) { $atts['displayphoto'] = false; }
			if ( $atts['displayauthor'] == 'false' ) { $atts['displayauthor'] = false; }
			if ( $atts['displaydate'] == 'false' ) { $atts['displaydate'] = false; }
			if ( $atts['excludereplies'] == 'false' ) { $atts['excludereplies'] = false; }
			if ( $atts['users'] ) {
				$atts['users'] = explode(',', $atts['users']);
			}
			ob_start();
			sp_the_tweets( $atts['users'], (int) $atts['count'], array(
				'displayDate' => (boolean) $atts['displaydate'],
				'displayPhoto' => (boolean) $atts['displayphoto'],
				'displayAuthor' => (boolean) $atts['displayauthor'],
				'excludeReplies' => (boolean) $atts['excludereplies'],
				'displayFormat' => $atts['displayformat'],						
			) );
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
		
		public function getTwitterUserName( $userId = false ) {
			return get_user_meta( $userId, $this->_key_user, true );
		}
		
		// TODO: test with users with no twitter account
		public function getTwitterUserLink( $userId = false ) {
			$username = $this->getTwitterUserName( $userId );
			if ( $username ) {
				return 'http://twitter.com/'.$username;
			} else {
				return false;
			}
		}
		
		public function getTwitterThumb( $twitterUser = false ) {
			if ( $twitterUser ) {
				$twitter_thumb = $twitterUser['profile_image_url'];
				return $twitter_thumb;
			}
		}
		
		public function setCache( $userId, $cache ) {
			$expiration = mktime() + $this->_cache_timeout;
			update_option( $this->_options.'-cache-'.$userId, array( 'cache'=>$cache,'expires'=>$expiration) );
			return wp_cache_set( $this->_options, $cache, $userId, $this->_cache_timeout );
		}

		public function getCache( $userId ) {
			if ( $cache = wp_cache_get( $this->_options, $userId ) ) {
				return $cache;
			} else {
				$tweetcache = get_option($this->_options.'-cache-'.$userId);
				if ( $tweetcache['expires'] > mktime() ) {
					return $tweetcache['cache'];
				}
			}
		}
		
		/**
		 * Filter tweet text to link hashtags and handles correctly.
		 *
		 * @param string $tweet 
		 * @return string $tweet
		 */
		public function tweetFilter( $tweet ) {
			$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $tweet);
			$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $tweet);
			$tweet = preg_replace("/@(\w+)/", "<a target=\"_blank\" href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $tweet);
			$tweet = preg_replace("/#(\w+)/", "<a target=\"_blank\" href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $tweet);
			return $tweet;
		}
		
		public function enableWidget() {
			return $this->getOptions( $this->_options_widget );
		}

	}

	global $TwitterPro;
	$TwitterPro = new TwitterPro();
	include ( $TwitterPro->pluginPath . '/library/template-tags.php' );
	if ( $TwitterPro->enableWidget() ) {
		include ( $TwitterPro->pluginPath . '/twitter-widget.php' );
	}
}
?>