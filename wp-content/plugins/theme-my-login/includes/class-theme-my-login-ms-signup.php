<?php
/**
 * Holds the Theme My Login multisite signup class
 *
 * @package Theme My Login
 */

if ( !class_exists( 'Theme_My_Login_MS_Signup' ) ) :
/*
 * Theme My Login multisite signup class
 *
 * This class contains properties and methods common to the multisite signup process.
 *
 * @since 6.1
 */
class Theme_My_Login_MS_Signup {
	/**
	 * Holds reference to global $theme_my_login_template object
	 *
	 * @since 6.1
	 * @access public
	 * @var object
	 */
	var $theme_my_login_template;

	/**
	 * PHP4 style constructor
	 *
	 * @since 6.1
	 * @access public
	 */
	function Theme_My_Login_MS_Signup() {
		$this->__construct();
	}

	/**
	 * PHP5 style constructor
	 *
	 * @since 6.1
	 * @access public
	 */
	function __construct() {
		global $theme_my_login;

		add_action( 'tml_request_register', array( &$this, 'tml_request_register' ) );
		add_action( 'tml_request_activate', array( &$this, 'tml_request_activate' ) );
		add_action( 'tml_display_register', array( &$this, 'tml_display_register' ) );
		add_action( 'tml_display_activate', array( &$this, 'tml_display_activate' ) );
		add_filter( 'tml_title', array( &$this, 'tml_title' ), 10, 2 );

		add_action( 'switch_blog', array( &$theme_my_login, 'init_options' ) );
		add_action( 'wpmu_new_blog', array( &$this, 'wpmu_new_blog' ), 10, 2 );

		add_filter( 'site_url', array( &$this, 'site_url' ), 10, 3 );
		add_filter( 'network_site_url', array( &$this, 'site_url' ), 10, 3 );

		add_filter( 'clean_url', array( &$this, 'clean_url' ), 10, 3 );
	}

	/**
	 * Handles register action
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param object $theme_my_login Theme_My_Login object
	 */
	function tml_request_register( &$theme_my_login ) {
		global $current_site, $wp_version;

		if ( version_compare( $wp_version, '3.3', '<' ) ) {
			add_filter( 'pre_option_blog_public', '__return_zero' );
			add_action( 'wp_head', 'noindex' );
		} else {
			add_action( 'wp_head', 'wp_no_robots' );
		}
		add_action( 'wp_head', array( &$this, 'signup_header' ) );

		if ( is_array( get_site_option( 'illegal_names' )) && isset( $_GET[ 'new' ] ) && in_array( $_GET[ 'new' ], get_site_option( 'illegal_names' ) ) == true ) {
			wp_redirect( network_home_url() );
			exit;
		}

		if ( !is_main_site() ) {
			switch_to_blog( $current_site->blog_id );
			$redirect_to = $theme_my_login->get_login_page_link( array( 'action' => 'register' ) );
			restore_current_blog();
			wp_redirect( $redirect_to );
			exit;
		}
	}

	/**
	 * Displays the registration page
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param object $template Theme_My_Login_Template object
	 */
	function tml_display_register( &$template ) {
		global $theme_my_login, $wpdb, $blogname, $blog_title, $domain, $path, $active_signup;

		$this->theme_my_login_template =& $template;

		do_action( 'before_signup_form' );

		echo '<div class="login mu_register" id="theme-my-login' . esc_attr( $template->instance ) . '">';

		$active_signup = get_site_option( 'registration' );
		if ( !$active_signup )
			$active_signup = 'all';

		$active_signup = apply_filters( 'wpmu_active_signup', $active_signup ); // return "all", "none", "blog" or "user"

		// Make the signup type translatable.
		$i18n_signup['all'] = _x( 'all', 'Multisite active signup type' );
		$i18n_signup['none'] = _x( 'none', 'Multisite active signup type' );
		$i18n_signup['blog'] = _x( 'blog', 'Multisite active signup type' );
		$i18n_signup['user'] = _x( 'user', 'Multisite active signup type' );

		if ( is_super_admin() )
			echo '<p class="message">' . sprintf( __( 'Greetings Site Administrator! You are currently allowing &#8220;%s&#8221; registrations. To change or disable registration go to your <a href="%s">Options page</a>.' ), $i18n_signup[$active_signup], esc_url( network_admin_url( 'ms-options.php' ) ) ) . '</p>';

		$newblogname = isset( $_GET['new'] ) ? strtolower( preg_replace( '/^-|-$|[^-a-zA-Z0-9]/', '', $_GET['new'] ) ) : null;

		$current_user = wp_get_current_user();
		if ( $active_signup == "none" ) {
			_e( 'Registration has been disabled.', 'theme-my-login' );
		} elseif ( $active_signup == 'blog' && !is_user_logged_in() ) {
			printf( __( 'You must first <a href="%s">log in</a>, and then you can create a new site.', 'theme-my-login' ), wp_login_url( Theme_My_Login::get_current_url() ) );
		} else {
			$stage = isset( $_POST['stage'] ) ?  $_POST['stage'] : 'default';
			switch ( $stage ) {
				case 'validate-user-signup' :
					if ( $active_signup == 'all' || $_POST[ 'signup_for' ] == 'blog' && $active_signup == 'blog' || $_POST[ 'signup_for' ] == 'user' && $active_signup == 'user' ) {
						$result = wpmu_validate_user_signup( $_POST['user_name'], $_POST['user_email'] );
						extract( $result );

						$theme_my_login->errors = $errors;

						if ( $errors->get_error_code() ) {
							$this->signup_user( $user_name, $user_email );
							break;
						}

						if ( 'blog' == $_POST['signup_for'] ) {
							$this->signup_blog( $user_name, $user_email );
							break;
						}

						wpmu_signup_user( $user_name, $user_email, apply_filters( 'add_signup_meta', array() ) );

						?>
						<h2><?php printf( __( '%s is your new username', 'theme-my-login' ), $user_name) ?></h2>
						<p><?php _e( 'But, before you can start using your new username, <strong>you must activate it</strong>.', 'theme-my-login' ) ?></p>
						<p><?php printf(__( 'Check your inbox at <strong>%1$s</strong> and click the link given.', 'theme-my-login' ),  $user_email) ?></p>
						<p><?php _e( 'If you do not activate your username within two days, you will have to sign up again.', 'theme-my-login' ); ?></p>
						<?php
						do_action( 'signup_finished' );
					} else {
						_e( 'User registration has been disabled.', 'theme-my-login' );
					}
				break;
				case 'validate-blog-signup':
					if ( $active_signup == 'all' || $active_signup == 'blog' ) {
						// Re-validate user info.
						$result = wpmu_validate_user_signup( $_POST['user_name'], $_POST['user_email'] );
						extract( $result );

						$theme_my_login->errors = $errors;

						if ( $errors->get_error_code() ) {
							$this->signup_user( $user_name, $user_email );
							break;
						}

						$result = wpmu_validate_blog_signup( $_POST['blogname'], $_POST['blog_title'] );
						extract( $result );

						$theme_my_login->errors = $errors;

						if ( $errors->get_error_code() ) {
							$this->signup_blog( $user_name, $user_email, $blogname, $blog_title );
							break;
						}

						$public = (int) $_POST['blog_public'];
						$meta = array ('lang_id' => 1, 'public' => $public);
						$meta = apply_filters( 'add_signup_meta', $meta );

						wpmu_signup_blog( $domain, $path, $blog_title, $user_name, $user_email, $meta );
						?>
						<h2><?php printf( __( 'Congratulations! Your new site, %s, is almost ready.', 'theme-my-login' ), "<a href='http://{$domain}{$path}'>{$blog_title}</a>" ) ?></h2>

						<p><?php _e( 'But, before you can start using your site, <strong>you must activate it</strong>.', 'theme-my-login' ) ?></p>
						<p><?php printf( __( 'Check your inbox at <strong>%s</strong> and click the link given.', 'theme-my-login' ),  $user_email) ?></p>
						<p><?php _e( 'If you do not activate your site within two days, you will have to sign up again.', 'theme-my-login' ); ?></p>
						<h2><?php _e( 'Still waiting for your email?' ); ?></h2>
						<p>
							<?php _e( 'If you haven&#8217;t received your email yet, there are a number of things you can do:', 'theme-my-login' ) ?>
							<ul id="noemail-tips">
								<li><p><strong><?php _e( 'Wait a little longer. Sometimes delivery of email can be delayed by processes outside of our control.', 'theme-my-login' ) ?></strong></p></li>
								<li><p><?php _e( 'Check the junk or spam folder of your email client. Sometime emails wind up there by mistake.', 'theme-my-login' ) ?></p></li>
								<li><?php printf( __( 'Have you entered your email correctly?  You have entered %s, if it&#8217;s incorrect, you will not receive your email.', 'theme-my-login' ), $user_email ) ?></li>
							</ul>
						</p>
						<?php
						do_action( 'signup_finished' );
					} else {
						_e( 'Site registration has been disabled.', 'theme-my-login' );
					}
					break;
				case 'gimmeanotherblog':
					$current_user = wp_get_current_user();
					if ( !is_user_logged_in() )
						die();

					$result = wpmu_validate_blog_signup( $_POST['blogname'], $_POST['blog_title'], $current_user );
					extract( $result );

					$theme_my_login->errors = $errors;

					if ( $errors->get_error_code() ) {
						$this->signup_another_blog( $blogname, $blog_title );
						break;
					}

					$public = (int) $_POST['blog_public'];
					$meta = apply_filters( 'signup_create_blog_meta', array( 'lang_id' => 1, 'public' => $public ) ); // deprecated
					$meta = apply_filters( 'add_signup_meta', $meta );

					wpmu_create_blog( $domain, $path, $blog_title, $current_user->id, $meta, $wpdb->siteid );
					?>
					<h2><?php printf( __( 'The site %s is yours.', 'theme-my-login' ), "<a href='http://{$domain}{$path}'>{$blog_title}</a>" ) ?></h2>
					<p>
						<?php printf( __( '<a href="http://%1$s">http://%2$s</a> is your new site.  <a href="%3$s">Log in</a> as &#8220;%4$s&#8221; using your existing password.', 'theme-my-login' ), $domain.$path, $domain.$path, "http://" . $domain.$path . "wp-login.php", $current_user->user_login ) ?>
					</p>
					<?php
					do_action( 'signup_finished' );
					break;
				case 'default':
				default :
					$user_email = isset( $_POST[ 'user_email' ] ) ? $_POST[ 'user_email' ] : '';

					do_action( 'preprocess_signup_form' ); // populate the form from invites, elsewhere?

					if ( is_user_logged_in() && ( $active_signup == 'all' || $active_signup == 'blog' ) )
						$this->signup_another_blog( $newblogname );
					elseif ( is_user_logged_in() == false && ( $active_signup == 'all' || $active_signup == 'user' ) )
						$this->signup_user( $newblogname, $user_email );
					elseif ( is_user_logged_in() == false && ( $active_signup == 'blog' ) )
						_e( 'Sorry, new registrations are not allowed at this time.', 'theme-my-login' );
					else
						_e( 'You are logged in already. No need to register again!', 'theme-my-login' );

					if ( $newblogname ) {
						$newblog = get_blogaddress_by_name( $newblogname );

						if ( $active_signup == 'blog' || $active_signup == 'all' )
							printf( __( '<p><em>The site you were looking for, <strong>%s</strong> does not exist, but you can create it now!</em></p>', 'theme-my-login' ), $newblog );
						else
							printf( __( '<p><em>The site you were looking for, <strong>%s</strong>, does not exist.</em></p>', 'theme-my-login' ), $newblog );
					}
					break;
			}
		}
		echo '</div>';
		do_action( 'after_signup_form' );
	}

	/**
	 * Fires WP signup hooks
	 *
	 * @since 6.1
	 * @access public
	 */
	function signup_header() {
		do_action( 'signup_header' );
	}

	/**
	 * Processes/displays user signup form
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param string $user_name The posted username
	 * @param string $user_email The posted user e-mail
	 */
	function signup_user( $user_name = '', $user_email = '' ) {
		global $theme_my_login, $current_site, $active_signup;

		$template =& $this->theme_my_login_template;

		// allow definition of default variables
		$filtered_results = apply_filters( 'signup_user_init', array( 'user_name' => $user_name, 'user_email' => $user_email, 'errors' => $theme_my_login->errors ) );
		$user_name = $filtered_results['user_name'];
		$user_email = $filtered_results['user_email'];
		$errors = $filtered_results['errors'];

		if ( !empty( $this->theme_my_login_template->options['ms_signup_user_template'] ) )
			$templates[] = $this->theme_my_login_template->options['ms_signup_user_template'];
		$templates[] = 'ms-signup-user-form.php';

		$template->get_template( $templates, '', true, compact( 'current_site', 'active_signup', 'user_name', 'user_email', 'errors' ) );
	}

	/**
	 * Processes/displays blog signup form
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param string $user_name The posted username
	 * @param string $user_email The posted user e-mail
	 * @param string $blogname The posted blog name
	 * @param string $blog_title The posted blog title
	 */
	function signup_blog( $user_name = '', $user_email = '', $blogname = '', $blog_title = '' ) {
		global $theme_my_login, $current_site;

		$template =& $this->theme_my_login_template;

		// allow definition of default variables
		$filtered_results = apply_filters( 'signup_blog_init', array( 'user_name' => $user_name, 'user_email' => $user_email, 'blogname' => $blogname, 'blog_title' => $blog_title, 'errors' => $theme_my_login->errors ) );
		$user_name = $filtered_results['user_name'];
		$user_email = $filtered_results['user_email'];
		$blogname = $filtered_results['blogname'];
		$blog_title = $filtered_results['blog_title'];
		$errors = $filtered_results['errors'];

		if ( empty( $blogname ) )
			$blogname = $user_name;

		if ( !empty( $this->theme_my_login_template->options['ms_signup_blog_template'] ) )
			$templates[] = $this->theme_my_login_template->options['ms_signup_blog_template'];
		$templates[] = 'ms-signup-blog-form.php';

		$template->get_template( $templates, '', true, compact( 'current_site', 'user_name', 'user_email', 'blogname', 'blog_title', 'errors' ) );
	}

	/**
	 * Processes/displays another blog signup form
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param string $blogname The posted blog name
	 * @param string $blog_title The posted blog title
	 */
	function signup_another_blog( $blogname = '', $blog_title = '' ) {
		global $theme_my_login, $current_site;

		$template =& $this->theme_my_login_template;

		// allow definition of default variables
		$filtered_results = apply_filters( 'signup_another_blog_init', array( 'blogname' => $blogname, 'blog_title' => $blog_title, 'errors' => $theme_my_login->errors ) );
		$blogname = $filtered_results['blogname'];
		$blog_title = $filtered_results['blog_title'];
		$errors = $filtered_results['errors'];

		if ( !empty( $this->theme_my_login_template->options['ms_signup_another_blog_template'] ) )
			$templates[] = $this->theme_my_login_template->options['ms_signup_another_blog_template'];
		$templates[] = 'ms-signup-another-blog-form.php';

		$template->get_template( $templates, '', true, compact( 'current_site', 'blogname', 'blog_title', 'errors' ) );
	}

	/**
	 * Handles activation action
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param object $theme_my_login Theme_My_Login object
	 */
	function tml_request_activate( &$theme_my_login ) {
		global $current_site, $wp_object_cache;

		if ( is_object( $wp_object_cache ) )
			$wp_object_cache->cache_enabled = false;

		add_action( 'wp_head', array( &$this, 'activate_header' ) );
	}

	/**
	 * Outputs the activation page
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param object $template Theme_My_Login_Template object
	 */
	function tml_display_activate( &$template ) {
		global $blog_id;

		echo '<div class="login" id="theme-my-login' . esc_attr( $template->instance ) . '">';

		if ( empty( $_GET['key'] ) && empty( $_POST['key'] ) ) { ?>

			<h2><?php _e( 'Activation Key Required', 'theme-my-login' ) ?></h2>
			<form name="activateform" id="activateform" method="post" action="<?php $template->the_action_url( 'activate' ); ?>">
				<p>
					<label for="key<?php $template->the_instance(); ?>"><?php _e( 'Activation Key:', 'theme-my-login' ) ?></label>
					<br /><input type="text" name="key<?php $template->the_instance(); ?>" id="key" value="" size="50" />
				</p>
				<p class="submit">
					<input id="submit<?php $template->the_instance(); ?>" type="submit" name="Submit" class="submit" value="<?php esc_attr_e( 'Activate', 'theme-my-login' ) ?>" />
				</p>
			</form>

		<?php } else {

			$key = !empty( $_GET['key'] ) ? $_GET['key'] : $_POST['key'];
			$result = wpmu_activate_signup( $key );
			if ( is_wp_error( $result ) ) {
				if ( 'already_active' == $result->get_error_code() || 'blog_taken' == $result->get_error_code() ) {
					$signup = $result->get_error_data();
					?>
					<h2><?php _e( 'Your account is now active!', 'theme-my-login' ); ?></h2>
					<?php
					echo '<p class="lead-in">';
					if ( $signup->domain . $signup->path == '' ) {
						printf( __( 'Your account has been activated. You may now <a href="%1$s">login</a> to the site using your chosen username of &#8220;%2$s&#8221;.  Please check your email inbox at %3$s for your password and login instructions. If you do not receive an email, please check your junk or spam folder. If you still do not receive an email within an hour, you can <a href="%4$s">reset your password</a>.', 'theme-my-login' ), network_site_url( 'wp-login.php', 'login' ), $signup->user_login, $signup->user_email, network_site_url( 'wp-login.php?action=lostpassword', 'login' ) );
					} else {
						printf( __( 'Your site at <a href="%1$s">%2$s</a> is active. You may now log in to your site using your chosen username of &#8220;%3$s&#8221;.  Please check your email inbox at %4$s for your password and login instructions.  If you do not receive an email, please check your junk or spam folder.  If you still do not receive an email within an hour, you can <a href="%5$s">reset your password</a>.', 'theme-my-login' ), 'http://' . $signup->domain, $signup->domain, $signup->user_login, $signup->user_email, network_site_url( 'wp-login.php?action=lostpassword' ) );
					}
					echo '</p>';
				} else {
					?>
					<h2><?php _e( 'An error occurred during the activation', 'theme-my-login' ); ?></h2>
					<?php
					echo '<p>' . $result->get_error_message() . '</p>';
				}
			} else {
				extract( $result );
				$url = get_blogaddress_by_id( (int) $blog_id );
				$user = new WP_User( (int) $user_id );
				?>
				<h2><?php _e( 'Your account is now active!', 'theme-my-login' ); ?></h2>

				<div id="signup-welcome">
					<p><span class="h3"><?php _e( 'Username:', 'theme-my-login' ); ?></span> <?php echo $user->user_login ?></p>
					<p><span class="h3"><?php _e( 'Password:', 'theme-my-login' ); ?></span> <?php echo $password; ?></p>
				</div>

				<?php if ( $url != network_home_url( '', 'http' ) ) : switch_to_blog( (int) $blog_id ); ?>
					<p class="view"><?php printf( __( 'Your account is now activated. <a href="%1$s">View your site</a> or <a href="%2$s">Login</a>', 'theme-my-login' ), $url, wp_login_url() ); ?></p>
				<?php restore_current_blog(); else: ?>
					<p class="view"><?php printf( __( 'Your account is now activated. <a href="%1$s">Login</a> or go back to the <a href="%2$s">homepage</a>.', 'theme-my-login' ), network_site_url( 'wp-login.php', 'login' ), network_home_url() ); ?></p>
				<?php endif;
			}
		}
		echo '</div>';
	}

	/**
	 * Fires WP activation hooks
	 *
	 * @since 6.1
	 * @access public
	 */
	function activate_header() {
		do_action( 'activate_header' );
		do_action( 'activate_wp_head' );
	}

	/**
	 * Changes page title for activation action
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param string $title The page title
	 * @param string $action The requested action
	 * @return string The filtered title
	 */
	function tml_title( $title, $action ) {
		if ( 'activate' == $action )
			$title = __( 'Activate', 'theme-my-login' );
		return $title;
	}

	/**
	 * Activates plugin for new multisite blogs
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param int $blog_id ID of new blog
	 * @param int $user_id ID of blog owner
	 */
	function wpmu_new_blog( $blog_id, $user_id ) {
		global $wpdb;
		require_once ( ABSPATH . '/wp-admin/includes/plugin.php' );
		if ( is_plugin_active_for_network( 'theme-my-login/theme-my-login.php' ) ) {
			require_once( TML_ABSPATH . '/admin/class-theme-my-login-admin.php' );
			switch_to_blog( $blog_id );
			$admin =& new Theme_My_Login_Admin();
			$page_id = $admin->_install();
			$wpdb->update( $wpdb->posts, array( 'post_author' => $user_id ), array( 'ID' => $page_id ) );
			restore_current_blog();
		}
	}

	/**
	 * Rewrites URL's containing wp-login.php created by site_url()
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param string $url The URL
	 * @param string $path The path specified
	 * @param string $orig_scheme The current connection scheme (HTTP/HTTPS)
	 * @return string The modified URL
	 */
	function site_url( $url, $path, $orig_scheme ) {
		global $theme_my_login, $pagenow;

		$actions = array( 'wp-signup.php' => 'register', 'wp-activate.php' => 'activate', 'wp-login.php' => '' );

		foreach ( $actions as $page => $action ) {
			if ( false !== strpos( $url, $page )  && $pagenow != $page ) {
				// Parse the URL
				$parsed_url = parse_url( $url );

				// Set action
				$query = array( 'action' => $action );

				// Extract the query string
				if ( isset( $parsed_url['query'] ) ) {
					wp_parse_str( $parsed_url['query'], $r );
					foreach ( $r as $k => $v ) {
						if ( strpos( $v, ' ' ) !== false )
							$r[$k] = rawurlencode( $v );
					}
				}

				// Merge query args passed in by filter
				if ( isset( $r ) )
					$query = array_merge( $query, (array) $r );

				// Get the login page link along with the query
				$url = $theme_my_login->get_login_page_link( $query );

				// Check if HTTPS is needed
				if ( 'https' == strtolower( $orig_scheme ) )
					$url = preg_replace( '|^http://|', 'https://', $url );

				break;
			}
		}
		return $url;
	}

	/**
	 * Don't clean activate URL
	 *
	 * @since 6.1
	 * @access public
	 */
	function clean_url( $url, $original_url, $context ) {
		if ( strpos( $original_url, 'action=activate' ) !== false )
			return $original_url;
		return $url;
	}
}
endif;

?>
