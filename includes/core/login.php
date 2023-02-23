<?php
/**
 * The file that defines the login functionality of the plugin.
 *
 * @link       https://github.com/Javeane/my-wordpress-plugin
 * @since      1.0.0
 *
 * @package    My_WordPress_Plugin
 * @subpackage My_WordPress_Plugin/includes/core
 */

/**
 * The login-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for how to
 * authenticate users to the WordPress site.
 *
 * @package    My_WordPress_Plugin
 * @subpackage My_WordPress_Plugin/includes/core
 */
class My_WordPress_Plugin_Login {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the login page.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name . '-login-style', plugin_dir_url( __FILE__ ) . '../../assets/css/login-style.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the login page.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name . '-login-script', plugin_dir_url( __FILE__ ) . '../../assets/js/login.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the login page shortcode.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function add_login_shortcode() {

		add_shortcode( 'my_wp_plugin_login_form', array( $this, 'render_login_form' ) );

	}

	/**
	 * Render the login form.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    array     $atts    Shortcode attributes.
	 * @return   string             The HTML markup for the login form.
	 */
	public function render_login_form( $atts ) {

		// Get the shortcode attributes.
		$atts = shortcode_atts( array(
			'redirect_to' => '',
		), $atts, 'my_wp_plugin_login_form' );

		// Get the redirect URL.
		$redirect_to = isset( $atts['redirect_to'] ) ? esc_url_raw( $atts['redirect_to'] ) : '';

		// Get the error message if present.
		$error = isset( $_GET['login_error'] ) ? $this->get_error_message( $_GET['login_error'] ) : '';
			// Check if the user is already logged in.
	if ( is_user_logged_in() ) {
		// Redirect to the homepage or the specified URL.
		wp_redirect( $redirect_to ? $redirect_to : home_url() );
		exit;
	}

	// Get the login URL.
	$login_url = esc_url( wp_login_url( $redirect_to ) );

	// Start building the HTML markup for the login form.
	$output = '';

	// Add the error message if present.
	if ( $error ) {
		$output .= '<p class="error">' . esc_html( $error ) . '</p>';
	}

	// Add the login form.
	$output .= '<form id="my_wp_plugin_login_form" class="my-wp-plugin-form" action="' . $login_url . '" method="post">';
	$output .= '<p class="form-row form-row-wide">';
	$output .= '<label for="my_wp_plugin_username">' . esc_html__( 'Username or Email Address', 'my-wp-plugin' ) . ' <span class="required">*</span></label>';
	$output .= '<input type="text" name="log" id="my_wp_plugin_username" class="input-text" value="' . esc_attr( $this->get_posted_value( 'log' ) ) . '" required />';
	$output .= '</p>';
	$output .= '<p class="form-row form-row-wide">';
	$output .= '<label for="my_wp_plugin_password">' . esc_html__( 'Password', 'my-wp-plugin' ) . ' <span class="required">*</span></label>';
	$output .= '<input type="password" name="pwd" id="my_wp_plugin_password" class="input-text" value="" required />';
	$output .= '</p>';

	// Add the submit button.
	$output .= '<p class="form-row">';
	$output .= '<button type="submit" name="wp-submit" id="my_wp_plugin_submit" class="button">' . esc_html__( 'Log in', 'my-wp-plugin' ) . '</button>';
	$output .= '<input type="hidden" name="redirect_to" value="' . esc_attr( $redirect_to ) . '" />';
	$output .= '<input type="hidden" name="my_wp_plugin_login" value="1" />';
	$output .= '</p>';
	$output .= '</form>';

	// Return the login form.
	return $output;
}

/**
 * Get the error message for a given error code.
 *
 * @since    1.0.0
 * @access   protected
 * @param    string    $error_code    The error code.
 * @return   string                   The error message.
 */
protected function get_error_message( $error_code ) {
    switch ( $error_code ) {
        case 'invalid_username':
            return esc_html__( 'Invalid username.', 'my-wp-plugin' );
        case 'empty_username':
            return esc_html__( 'The username field is empty.', 'my-wp-plugin' );
        case 'invalid_email':
            return esc_html__( 'Invalid email address.', 'my-wp-plugin' );
        case 'empty_password':
            return esc_html__( 'The password field is empty.', 'my-wp-plugin' );
        case 'incorrect_password':
            return esc_html__( 'Incorrect password.', 'my-wp-plugin' );
        default:
            return esc_html__( 'Login failed. Please try again.', 'my-wp-plugin' );
    }
}
