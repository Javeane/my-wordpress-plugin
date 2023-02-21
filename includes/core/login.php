<?php
/**
 * Login Class
 *
 * This class handles the customization of the WordPress login page.
 *
 * @package My_WordPress_Plugin
 * @subpackage Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class My_WP_Login {
	/**
	 * The plugin options.
	 *
	 * @var array
	 */
	private $options;

	/**
	 * The plugin version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Initialize the class.
	 *
	 * @param array  $options The plugin options.
	 * @param string $version The plugin version.
	 */
	public function __construct( $options, $version ) {
		$this->options = $options;
		$this->version = $version;

		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'authenticate', array( $this, 'authenticate' ), 10, 3 );
		add_action( 'login_head', array( $this, 'custom_login_head' ) );
		add_filter( 'login_headerurl', array( $this, 'custom_login_header_url' ) );
		add_filter( 'login_message', array( $this, 'custom_login_message' ) );
	}

	/**
	 * Initialize the login customization.
	 */
	public function init() {
		$this->add_rewrite_rules();

		if ( ! is_user_logged_in() && strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ) {
			wp_redirect( home_url( '/404/' ) );
			exit();
		}

		if ( ! is_user_logged_in() && strpos( $_SERVER['REQUEST_URI'], 'wp-admin' ) !== false ) {
			wp_redirect( home_url( '/404/' ) );
			exit();
		}
	}

	/**
	 * Add rewrite rules.
	 */
	private function add_rewrite_rules() {
		add_action( 'init', array( $this, 'flush_rewrite_rules' ) );

		add_rewrite_rule(
			'^login$',
			'wp-login.php',
			'top'
		);

		add_rewrite_rule(
			'^login/(.*)',
			'wp-login.php?$1',
			'top'
		);
	}

	/**
	 * Flush the rewrite rules.
	 */
	public function flush_rewrite_rules() {
		$rules = get_option( 'rewrite_rules' );

		if ( ! isset( $rules['login$'] ) ) {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}

		// Check if user account is verified
	if ( ! $this->is_account_verified( $user ) ) {
		$this->redirect_to_verification_page( $redirect_to );
		return;
	}

	// Authenticate the user.
	wp_set_auth_cookie( $user->ID, $credentials['remember'] );
	do_action( 'wp_login', $user->user_login, $user );

	// Redirect to the appropriate page.
	if ( ! empty( $redirect_to ) ) {
		wp_safe_redirect( $redirect_to );
	} else {
		wp_redirect( home_url() );
	}
}

/**
 * Check if the user account is verified.
 *
 * @param WP_User $user The user object.
 * @return bool Whether the account is verified.
 */
protected function is_account_verified( $user ) {
	if ( $user instanceof WP_User ) {
		$verified = get_user_meta( $user->ID, 'is_email_verified', true );
		if ( ! empty( $verified ) && $verified == 'yes' ) {
			return true;
		}
	}
	return false;
}

/**
 * Redirect to the email verification page.
 *
 * @param string $redirect_to The URL to redirect to after verification.
 */
protected function redirect_to_verification_page( $redirect_to = '' ) {
	$verification_page_url = $this->get_verification_page_url();
	if ( ! empty( $verification_page_url ) ) {
		if ( ! empty( $redirect_to ) ) {
			$verification_page_url = add_query_arg( 'redirect_to', $redirect_to, $verification_page_url );
		}
		wp_redirect( $verification_page_url );
	}
}

/**
 * Get the email verification page URL.
 *
 * @return string The URL of the email verification page.
 */
protected function get_verification_page_url() {
	$page_id = get_option( 'my_wp_plugin_verification_page_id', '' );
	if ( ! empty( $page_id ) ) {
		$page_url = get_permalink( $page_id );
		if ( ! empty( $page_url ) ) {
			return $page_url;
		}
	}
	return '';
}
