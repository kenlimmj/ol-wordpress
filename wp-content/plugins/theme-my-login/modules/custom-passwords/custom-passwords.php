<?php
/*
Plugin Name: Custom Passwords
Description: Enabling this module will initialize and enable custom passwords. There are no other settings for this module.
*/

if ( !class_exists( 'Theme_My_Login_Custom_Passwords' ) ) :
/**
 * Theme My Login Custom Passwords module class
 *
 * Adds the ability for users to set their own passwords upon registration and password reset.
 *
 * @since 6.0
 */
class Theme_My_Login_Custom_Passwords extends Theme_My_Login_Module {
	/**
	 * Outputs password fields to registration form
	 *
	 * Callback for "tml_register_form" hook in file "register-form.php", included by Theme_My_Login_Template::display()
	 *
	 * @see Theme_My_Login::display()
	 * @since 6.0
	 * @access public
	 *
	 * @param object $template Reference to Theme_My_Login_Template object
	 */
	function password_fields( &$template ) {
	?>
	<p><label for="pass1<?php $template->the_instance(); ?>"><?php _e( 'Password:', 'theme-my-login' );?></label>
	<input autocomplete="off" name="pass1" id="pass1<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" tabindex="30" /></p>
	<p><label for="pass2<?php $template->the_instance(); ?>"><?php _e( 'Confirm Password:', 'theme-my-login' );?></label>
	<input autocomplete="off" name="pass2" id="pass2<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" tabindex="30" /></p>
<?php
	}

	/**
	 * Outputs password fields to multisite signup user form
	 *
	 * Callback for "tml_signup_extra_fields" hook in file "ms-signup-user-form.php", included by Theme_My_Login_Template::display()
	 *
	 * @see Theme_My_Login::display()
	 * @since 6.1
	 * @access public
	 *
	 * @param object $template Reference to Theme_My_Login_Template object
	 */
	function ms_password_fields( &$template ) {
		global $theme_my_login;

		$errors = array();
		foreach ( $theme_my_login->errors->get_error_codes() as $code ) {
			if ( in_array( $code, array( 'empty_password', 'password_mismatch', 'password_length' ) ) )
				$errors[] = $theme_my_login->errors->get_error_message( $code );
		}
	?>
	<label for="pass1<?php $template->the_instance(); ?>"><?php _e( 'Password:', 'theme-my-login' );?></label>
	<?php if ( !empty( $errors ) ) { ?>
		<p class="error"><?php echo implode( '<br />', $errors ); ?></p>
	<?php } ?>
	<input autocomplete="off" name="pass1" id="pass1<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" /><br />
	<span class="hint"><?php echo apply_filters( 'tml_password_hint', __( '(Must be at least 6 characters.)', 'theme-my-login' ) ); ?></span>

	<label for="pass2<?php $template->the_instance(); ?>"><?php _e( 'Confirm Password:', 'theme-my-login' );?></label>
	<input autocomplete="off" name="pass2" id="pass2<?php $template->the_instance(); ?>" class="input" size="20" value="" type="password" /><br />
	<span class="hint"><?php echo apply_filters( 'tml_password_confirm_hint', __( 'Confirm that you\'ve typed your password correctly.', 'theme-my-login' ) ); ?></span>
<?php
	}

	/**
	 * Outputs password field to multisite signup blog form
	 *
	 * Callback for "tml_signup_hidden_fields" hook in file "ms-signup-blog-form.php", included by Theme_My_Login_Template::display()
	 *
	 * @see Theme_My_Login::display()
	 * @since 6.1
	 * @access public
	 *
	 * @param object $template Reference to Theme_My_Login_Template object
	 */
	function ms_hidden_password_field() {
		if ( isset( $_POST['user_pass'] ) )
			echo '<input type="hidden" name="user_pass" value="' . $_POST['user_pass'] . '" />' . "\n";
	}

	/**
	 * Handles password errors for registration form
	 *
	 * Callback for "registration_errors" hook in Theme_My_Login::register_new_user()
	 *
	 * @see Theme_My_Login::register_new_user()
	 * @since 6.0
	 * @access public
	 *
	 * @param WP_Error $errors WP_Error object
	 * @return WP_Error WP_Error object
	 */
	function password_errors( $errors = '' ) {
		// Make sure $errors is a WP_Error object
		if ( empty( $errors ) )
			$errors = new WP_Error();
		// Make sure passwords aren't empty
		if ( empty( $_POST['pass1'] ) || $_POST['pass1'] == '' || empty( $_POST['pass2'] ) || $_POST['pass2'] == '' ) {
			$errors->add( 'empty_password', __( '<strong>ERROR</strong>: Please enter a password.', 'theme-my-login' ) );
		// Make sure passwords match
		} elseif ( $_POST['pass1'] !== $_POST['pass2'] ) {
			$errors->add( 'password_mismatch', __( '<strong>ERROR</strong>: Your passwords do not match.', 'theme-my-login' ) );
		// Make sure password is long enough
		} elseif ( strlen( $_POST['pass1'] ) < 6 ) {
			$errors->add( 'password_length', __( '<strong>ERROR</strong>: Your password must be at least 6 characters in length.', 'theme-my-login' ) );
		// All is good, assign password to a friendlier key
		} else {
			$_POST['user_pass'] = $_POST['pass1'];
		}
		return $errors;
	}

	/**
	 * Handles password errors for multisite signup form
	 *
	 * Callback for "registration_errors" hook in Theme_My_Login::register_new_user()
	 *
	 * @see Theme_My_Login::register_new_user()
	 * @since 6.1
	 * @access public
	 *
	 * @param WP_Error $errors WP_Error object
	 * @return WP_Error WP_Error object
	 */
	function ms_password_errors( $result ) {
		if ( isset( $_POST['stage'] ) && 'validate-user-signup' == $_POST['stage'] ) {
			$errors =& $result['errors'];
			$errors = $this->password_errors( $errors );
			foreach ( $errors->errors as $code => $msg ) {
				$errors->errors[$code] = preg_replace( '/<strong>([^<]+)<\/strong>: /', '', $msg );
			}
		}
		return $result;
	}

	/**
	 * Adds password to signup meta array
	 *
	 * @since 6.1
	 * @access public
	 *
	 * @param array $meta Signup meta
	 * @return array $meta Signup meta
	 */
	function ms_save_password( $meta ) {
		if ( isset( $_POST['user_pass'] ) )
			$meta['user_pass'] = $_POST['user_pass'];
		return $meta;
	}

	/**
	 * Sets the user password
	 *
	 * Callback for "random_password" hook in wp_generate_password()
	 *
	 * @see wp_generate_password()
	 * @since 6.0
	 * @access public
	 *
	 * @param string $password Auto-generated password passed in from filter
	 * @return string Password chosen by user
	 */
	function set_password( $password ) {
		global $wpdb;

		if ( is_multisite() && isset( $_REQUEST['key'] ) ) {
			if ( $meta = $wpdb->get_var( $wpdb->prepare( "SELECT meta FROM $wpdb->signups WHERE activation_key = %s", $_REQUEST['key'] ) ) ) {
				$meta = unserialize( $meta );
				if ( isset( $meta['user_pass'] ) ) {
					$password = $meta['user_pass'];
					unset( $meta['user_pass'] );
					$wpdb->update( $wpdb->signups, array( 'meta' => serialize( $meta ) ), array( 'activation_key' => $_REQUEST['key'] ) );
				}
			}
		} else {
			// Make sure password isn't empty
			if ( isset( $_POST['user_pass'] ) && !empty( $_POST['user_pass'] ) ) {
				$password = $_POST['user_pass'];

				// Remove filter as not to filter User Moderation activation key
				remove_filter( 'random_password', array( &$this, 'set_password' ) );
			}
		}
		return $password;
	}

	/**
	 * Removes the default password nag
	 *
	 * Callback for "tml_new_user_registered" hook in Theme_My_Login::register_new_user()
	 *
	 * @see Theme_My_Login::register_new_user()
	 * @since 6.0
	 * @access public
	 *
	 * @param int $user_id The user's ID
	 */
	function remove_default_password_nag( $user_id ) {
		update_user_meta( $user_id, 'default_password_nag', false );
	}

	/**
	 * Changes the register template message
	 *
	 * Callback for "tml_register_passmail_template_message" hook
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @return string The new register message
	 */
	function register_passmail_template_message() {
		return;
	}
	/**
	 * Handles display of various action/status messages
	 *
	 * @since 6.0
	 * @access public
	 *
	 * @param object $theme_my_login Reference to global $theme_my_login object
	 */
	function action_messages( &$theme_my_login ) {
		// Change "Registration complete. Please check your e-mail." to reflect the fact that they already set a password
		if ( isset( $_GET['registration'] ) && 'complete' == $_GET['registration'] )
			$theme_my_login->errors->add( 'registration_complete', __( 'Registration complete. You may now log in.', 'theme-my-login' ), 'message' );
	}

	/**
	 * Changes where the user is redirected upon successful registration
	 *
	 * Callback for "register_redirect" hook in Theme_My_Login::the_request()
	 *
	 * @see Theme_My_Login::the_request()
	 * @since 6.0
	 * @access public
	 *
	 * @return string $redirect_to Default redirect
	 * @return string URL to redirect to
	 */
	function register_redirect( $redirect_to ) {
		// Redirect to login page with "registration=complete" added to the query
		$redirect_to = site_url( 'wp-login.php?registration=complete' );
		// Add instance to the query if specified
		if ( isset( $_REQUEST['instance'] ) & !empty( $_REQUEST['instance'] ) )
			$redirect_to = add_query_arg( 'instance', $_REQUEST['instance'], $redirect_to );
		return $redirect_to;
	}

	/**
	 * Loads the module
	 *
	 * @since 6.0
	 * @access public
	 */
	function load() {
		// Register password
		add_action( 'tml_register_form', array( &$this, 'password_fields' ) );
		add_action( 'tml_signup_extra_fields', array( &$this, 'ms_password_fields' ) );
		add_action( 'tml_signup_blogform', array( &$this, 'ms_hidden_password_field' ) );
		add_filter( 'registration_errors', array( &$this, 'password_errors' ) );
		add_filter( 'wpmu_validate_user_signup',  array( &$this, 'ms_password_errors' ) );
		add_filter( 'add_signup_meta', array( &$this, 'ms_save_password' ) );
		add_filter( 'random_password', array( &$this, 'set_password' ) );
		add_action( 'tml_new_user_registered', array( &$this, 'remove_default_password_nag' ) );
		add_action( 'approve_user', array( &$this, 'remove_default_password_nag' ) );
		// Template messages
		add_filter( 'tml_register_passmail_template_message', array( &$this, 'register_passmail_template_message' ) );
		add_action( 'tml_request', array( &$this, 'action_messages' ) );
		// Redirection
		add_filter( 'register_redirect', array( &$this, 'register_redirect' ) );
	}
}

/**
 * Holds the reference to Theme_My_Login_Custom_Passwords object
 * @global object $theme_my_login_custom_passwords
 * @since 6.0
 */
$theme_my_login_custom_passwords = new Theme_My_Login_Custom_Passwords();

endif; // Class exists

?>
