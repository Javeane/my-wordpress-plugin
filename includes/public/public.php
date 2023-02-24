<?php
/**
 * This file contains the Public class
 *
 * @package my-wordpress-plugin
 *
 * The Public class contains code for the plugin's public-facing functionality
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
class Public {
    /**
     * The constructor function initializes the class
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_shortcodes' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_styles_and_scripts' ) );
    }

    /**
     * Enqueue scripts and styles
     */
    function my_wordpress_plugin_enqueue_scripts()
    {
      wp_enqueue_style('my-wordpress-plugin', plugins_url('public/css/style.css', dirname(__FILE__)), array(), '1.0.0', 'all');
      wp_enqueue_script('my-wordpress-plugin', plugins_url('public/js/script.js', dirname(__FILE__)), array('jquery'), '1.0.0', true);
}
    add_action('wp_enqueue_scripts', 'my_wordpress_plugin_enqueue_scripts');

/**
 * Shortcode for displaying plugin content
 */
function my_wordpress_plugin_shortcode($atts)
{
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'title' => 'My Wordpress Plugin',
        'text' => 'This is my Wordpress plugin.'
    ), $atts, 'my_wordpress_plugin');

    // Shortcode output
    $output = '<div class="my-wordpress-plugin">';
    $output .= '<h2>' . esc_html($atts['title']) . '</h2>';
    $output .= '<p>' . esc_html($atts['text']) . '</p>';
    $output .= '</div>';

    return $output;
}
add_shortcode('my_wordpress_plugin', 'my_wordpress_plugin_shortcode');

    /**
     * The register_shortcodes function registers the plugin's shortcodes
     */
    public function register_shortcodes() {
        add_shortcode( 'myplugin_register_form', array( $this, 'register_form_shortcode' ) );
    }

    /**
     * The register_form_shortcode function generates the shortcode output for the registration form
     */
    public function register_form_shortcode( $atts ) {
        // Extract shortcode attributes
        extract( shortcode_atts( array(
            'redirect' => '',
        ), $atts ) );

        // Validate and filter redirect parameter
        $redirect = wp_validate_redirect( $redirect, home_url() );

        // Output buffer
        ob_start();

        if ( isset( $_POST['myplugin_register_submit'] ) ) {
            // Verify nonce
            if ( ! wp_verify_nonce( $_POST['myplugin_register_nonce'], 'myplugin_register' ) ) {
                wp_die( 'Security check failed' );
            }

            // Process form data
            $user_login = sanitize_user( $_POST['myplugin_register_username'] );
            $user_email = sanitize_email( $_POST['myplugin_register_email'] );
            $user_password = $_POST['myplugin_register_password'];
            $user_firstname = sanitize_text_field( $_POST['myplugin_register_firstname'] );
            $user_lastname = sanitize_text_field( $_POST['myplugin_register_lastname'] );

            // Create new user
            $user_id = wp_insert_user( array(
                'user_login' => $user_login,
                'user_email' => $user_email,
                'user_pass' => $user_password,
                'first_name' => $user_firstname,
                'last_name' => $user_lastname,
                'role' => 'subscriber'
            ) );

            // Check if user was created successfully
            if ( is_wp_error( $user_id ) ) {
                // Display error message
                $error_message = $user_id->get_error_message();
                echo '<div class="myplugin-register-error">' . $error_message . '</div>';
            } else {
                // Redirect to specified page or homepage
                wp_safe_redirect( $redirect ? $redirect : home_url() );
                        }
        ?>

        <form method="post" class="myplugin-register-form">
            <?php wp_nonce_field( 'myplugin_register', 'myplugin_register_nonce' ); ?>
            <p>
                <label for="myplugin-register-username"><?php _e( 'Username', 'myplugin' ); ?></label>
                <input type="text" id="myplugin-register-username" name="myplugin_register_username" required>
            </p>
            <p>
                <label for="myplugin-register-email"><?php _e( 'Email', 'myplugin' ); ?></label>
                <input type="email" id="myplugin-register-email" name="myplugin_register_email" required>
            </p>
            <p>
                <label for="myplugin-register-password"><?php _e( 'Password', 'myplugin' ); ?></label>
                <input type="password" id="myplugin-register-password" name="myplugin_register_password" required>
            </p>
            <p>
                <label for="myplugin-register-firstname"><?php _e( 'First Name', 'myplugin' ); ?></label>
                <input type="text" id="myplugin-register-firstname" name="myplugin_register_firstname">
            </p>
            <p>
                <label for="myplugin-register-lastname"><?php _e( 'Last Name', 'myplugin' ); ?></label>
                <input type="text" id="myplugin-register-lastname" name="myplugin_register_lastname">
            </p>
            <p>
                <input type="submit" name="myplugin_register_submit" value="<?php _e( 'Register', 'myplugin' ); ?>">
            </p>
        </form>

        <?php
        // Return output buffer
        return ob_get_clean();
    }

    /**
     * The register_styles_and_scripts function registers the plugin's styles and scripts
     */
    public function register_styles_and_scripts() {
        // Register styles
        wp_enqueue_style( 'myplugin-public-style', MYPLUGIN_URL . 'assets/css/public.css', array(), MYPLUGIN_VERSION );

        // Register scripts
        wp_enqueue_script( 'myplugin-public-script', MYPLUGIN_URL . 'assets/js/public.js', array( 'jquery' ), MYPLUGIN_VERSION, true );
    }

}
