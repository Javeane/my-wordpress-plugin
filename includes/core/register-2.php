<?php
/**
 * Plugin registration functionality.
 *
 * @package My_WordPress_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class My_Plugin_Register
 */
class My_Plugin_Register {
	/**
	 * My_Plugin_Register constructor.
	 */
	public function __construct() {
		add_filter( 'login_url', array( $this, 'my_plugin_custom_login_url' ) );
		add_filter( 'registration_errors', array( $this, 'my_plugin_registration_errors' ), 10, 3 );
		add_filter( 'wp_authenticate_user', array( $this, 'my_plugin_wp_authenticate_user' ), 10, 2 );
	}

	/**
	 * Set custom login URL.
	 *
	 * @param string $login_url The default login URL.
	 *
	 * @return string The custom login URL.
	 */
	public function my_plugin_custom_login_url( $login_url ) {
		return home_url( 'my-custom-login-url' );
	}

	/**
	 * Add password and confirm password fields to registration form and check if they match.
	 *
	 * @param array  $errors    Registration errors.
	 * @param string $sanitized_user_login Sanitized user login name.
	 * @param string $user_email User email.
	 *
	 * @return array The modified registration errors.
	 */
	public function my_plugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {
		if ( ! empty( $_POST['password'] ) && ! empty( $_POST['confirm_password'] ) ) {
			if ( $_POST['password'] !== $_POST['confirm_password'] ) {
				$errors->add( 'password_mismatch', __( 'Passwords do not match', 'my-wordpress-plugin' ) );
			}
		} else {
			$errors->add( 'password_empty', __( 'Please enter a password', 'my-wordpress-plugin' ) );
		}

		return $errors;
	}

	/**
	 * Add captcha validation to login form.
	 *
	 * @param null|WP_Admin_Bar $wp_admin_bar
*/
function add_captcha_to_login() {
// Add captcha field to login form.
add_filter( 'login_form', array( $this, 'add_captcha_field' ) );
// Validate captcha on login.
add_action( 'wp_authenticate_user', array( $this, 'validate_captcha' ), 10, 2 );
}
/**
 * Add captcha field to login form.
 *
 * @param string $form HTML form string.
 *
 * @return string HTML form string with added captcha field.
 */
function add_captcha_field( $form ) {
	$form .= '<p class="form-row form-row-wide"><label for="captcha">' . __( 'Captcha', 'my-wordpress-plugin' ) . '<span class="required">*</span></label><input type="text" name="captcha" id="captcha" class="input-text" autocomplete="off" required></p>';
	return $form;
}

/**
 * Validate captcha on login.
 *
 * @param WP_User|WP_Error $user WP_User object if login successful, WP_Error object otherwise.
 * @param string           $password User password.
 */
function validate_captcha( $user, $password ) {
	if ( ! isset( $_POST['captcha'] ) || ! $this->is_valid_captcha( $_POST['captcha'] ) ) {
		remove_action( 'wp_authenticate_user', 'wp_authenticate_username_password', 20 );
		$user = new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Invalid captcha.' ) );
	}
}

/**
 * Check if captcha is valid.
 *
 * @param string $captcha Captcha string to check.
 *
 * @return bool True if captcha is valid, false otherwise.
 */
function is_valid_captcha( $captcha ) {
	// Implement captcha validation logic.
	// This is just a placeholder.
	return true;
}

}

new My_Wordpress_Plugin_Login();
?>	 
