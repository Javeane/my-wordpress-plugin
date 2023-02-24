<?php
/**
 * Register form rendering and submission
 *
 * @package my-wordpress-plugin
 * @subpackage Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register form class
 */
class My_WP_Register_Form {
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_user' ) );
		add_action( 'register_form', array( $this, 'render_register_form' ) );
		add_filter( 'registration_errors', array( $this, 'validate_user_registration' ), 10, 3 );
	}

	/**
	 * Register new user
	 */
	public function register_user() {
		if ( ! isset( $_POST['my_plugin_register'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['my_plugin_register'], 'my_plugin_register' ) ) {
			wp_die( esc_html__( 'Nonce verification failed.', 'my-wordpress-plugin' ) );
		}

		$username = sanitize_user( wp_unslash( $_POST['username'] ) );
		$email = sanitize_email( wp_unslash( $_POST['email'] ) );
		$password = wp_unslash( $_POST['password'] );
		$confirm_password = wp_unslash( $_POST['confirm_password'] );

		if ( ! $username || ! $email || ! $password || ! $confirm_password ) {
			wp_die( esc_html__( 'All fields are required.', 'my-wordpress-plugin' ) );
		}

		if ( $password !== $confirm_password ) {
			wp_die( esc_html__( 'Passwords do not match.', 'my-wordpress-plugin' ) );
		}

		// Check if the username is already taken.
		if ( username_exists( $username ) ) {
			wp_die( esc_html__( 'Username already exists.', 'my-wordpress-plugin' ) );
		}

		// Check if the email is already in use.
		if ( email_exists( $email ) ) {
			wp_die( esc_html__( 'Email address already in use.', 'my-wordpress-plugin' ) );
		}

		// Create user account.
		$user_id = wp_create_user( $username, $password, $email );

		if ( is_wp_error( $user_id ) ) {
			wp_die( $user_id->get_error_message() );
		}

		// Log the user in.
		wp_set_current_user( $user_id, $username );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $username );

		// Redirect to home page.
		wp_redirect( home_url() );
		exit;
	}

	/**
     * Render register form
     */
    public function render_register_form() {
    ?>
    <!-- Register form markup -->
    <form name="registerform" id="registerform" action="<?php echo esc_url( wp_registration_url() ); ?>" method="post">
        <p>
            <label for="username"><?php esc_html_e( 'Username' ); ?><br>
            <input type="text" name="username" id="username" class="input" value="<?php echo esc_attr( wp_unslash( $_POST['username'] ?? '' ) ); ?>" size="20"></label>
        </p>
        <p>
            <label for="email"><?php esc_html_e( 'Email' ); ?><br>
            <input type="email" name="email" id="email" class="input" value="<?php echo esc_attr( wp_unslash( $_POST['email'] ?? '' ) ); ?>" size="25"></label>
        </p>
        <p>
            <label for="password"><?php esc_html_e( 'Password' ); ?><br>
            <input type="password" name="password" id="password" class="input" value="" size="20"></label>
        </p>
        <p>
            <label for="confirm_password"><?php esc_html_e( 'Confirm Password' ); ?><br>
            <input type="password" name="confirm_password" id="confirm_password" class="input" value="" size="20"></label>
        </p>
        <?php wp_nonce_field( 'my_plugin_register', 'my_plugin_register' ); ?>
        <p>
            <input type="submit" value="<?php esc_attr_e( 'Register' ); ?>" class="button">
        </p>
    </form>
    <?php
    }

    /**
     * Validate user registration
     *
     * @param array $errors   Array of registration errors
     * @param string $username User's chosen username
     * @param string $email    User's email address
     *
     * @return array Array of registration errors
     */
    public function validate_user_registration( $errors, $username, $email ) {
    if ( empty( $username ) || ! validate_username( $username ) ) {
        $errors->add( 'username_error', esc_html__( 'Invalid username.', 'my-wordpress-plugin' ) );
    }

    if ( empty( $email ) || ! is_email( $email ) ) {
        $errors->add( 'email_error', esc_html__( 'Invalid email address.', 'my-wordpress-plugin' ) );
    }

    return $errors;
}