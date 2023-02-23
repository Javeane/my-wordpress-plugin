<?php
/**
 * Plugin social login functionality.
 *
 * This module provides the social login functionality. for the plugin.
 *
 * @package my-wordpress-plugin
 * @subpackage Core
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class to handle the social login functionality.
 */
class MWP_Social_Login {

    /**
     * Constructor to initialize the social login functionality.
     */
    public function __construct() {
        // Add social login buttons to the login page.
        add_action('login_form', array($this, 'add_social_login_buttons'));
        
        // Handle the social login.
        add_action('wp', array($this, 'handle_social_login'));
    }

    /**
     * Add social login buttons to the login page.
     */
    public function add_social_login_buttons() {
        // Check if the user is not logged in.
        if (!is_user_logged_in()) {
            // Add the social login buttons to the login page.
            ?>
            <div class="social-login">
                <h3><?php _e('Login with Social Accounts', 'my-wordpress-plugin'); ?></h3>
                <a href="<?php echo esc_url($this->get_social_login_url('facebook')); ?>" class="social-login-button facebook"><?php _e('Login with Facebook', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('twitter')); ?>" class="social-login-button twitter"><?php _e('Login with Twitter', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('google')); ?>" class="social-login-button google"><?php _e('Login with Google', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('microsoft')); ?>" class="social-login-button microsoft"><?php _e('Login with Microsoft', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('tiktok')); ?>" class="social-login-button tiktok"><?php _e('Login with TikTok', 'my-wordpress-plugin'); ?></a>
            </div>
            <?php
        }
    }

/**
 * Handle the social login.
 */
public function handle_social_login() {
    // Check if a social login request has been made.
    if (isset($_GET['social-login'])) {
        // Verify the CSRF token.
        if (!isset($_GET['state']) || $_GET['state'] !== wp_create_nonce('social-login')) {
            wp_die(__('CSRF verification failed.', 'my-wordpress-plugin'));
        }

        // Get the social provider.
        $provider = sanitize_text_field($_GET['social-login']);

        // Get the social user data.
        $social_user = $this->get_social_user_data($provider);

        // Get the user ID by social user ID.
        $user_id = $this->get_user_by_social_id($provider, $social_user['id']);

        // Check if the user already exists.
        if ($user_id) {
            // Log the user in.
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', get_user_by('ID', $user_id));
        } else {
            // Check if the email is already in use.
            $user_email = $social_user['email'];
            $user_id_by_email = email_exists($user_email);

            if ($user_id_by_email) {
                // Associate the social account with the existing user account.
                $this->associate_social_account($provider, $social_user, $user_id_by_email);

                // Log the user in.
                wp_set_current_user($user_id_by_email);
                wp_set_auth_cookie($user_id_by_email);
                do_action('wp_login', get_user_by('ID', $user_id_by_email));
            } else {
                // Register the user with the social account.
                $user_id = $this->register_user_with_social_account($provider, $social_user);

                // Log the user in.
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                do_action('wp_login', get_user_by('ID', $user_id));
            }
        }

        // Redirect the user to the home page.
        wp_redirect(home_url());
        exit;
    }
}

/**
 * Handle social login and associate social account with existing user or create new user.
 */
register_user_with_social_account {
    if (isset($_GET['social_login']) && isset($_GET['social_network'])) {
        $social_network = sanitize_text_field($_GET['social_network']);

        if (in_array($social_network, array('facebook', 'twitter', 'google', 'microsoft', 'tiktok'))) {
            // Get social network user details.
            $user_details = $this->get_social_user_details($social_network);

            if (is_wp_error($user_details)) {
                wp_die($user_details->get_error_message());
            }

            // Check if user with same email already exists.
            $user_id = email_exists($user_details->email);

            if (!$user_id) {
                // Create new user.
                $username = sanitize_user(str_replace(' ', '', strtolower($user_details->name)));

                $user_id = wp_insert_user(array(
                    'user_login' => $username,
                    'user_pass' => wp_generate_password(),
                    'user_email' => $user_details->email,
                    'display_name' => $user_details->name,
                ));

                if (is_wp_error($user_id)) {
                    wp_die($user_id->get_error_message());
                }

                // Notify user of new account.
                wp_new_user_notification($user_id, null, 'user');

                // Update user meta with social network details.
                update_user_meta($user_id, $social_network . '_id', $user_details->id);
                update_user_meta($user_id, $social_network . '_token', $user_details->token);
            } else {
                // Associate social network with existing user.
                update_user_meta($user_id, $social_network . '_id', $user_details->id);
                update_user_meta($user_id, $social_network . '_token', $user_details->token);
            }

            // Log the user in.
            wp_set_auth_cookie($user_id, true);

            // Redirect user to the home page.
            wp_safe_redirect(home_url());
            exit;
        }
    }
}


/**
 * Class to handle the social login functionality.
 */
class MWP_Social_Login {

    /**
     * Constructor to initialize the social login functionality.
     */
    public function __construct() {
        // Add social login buttons to the login page.
        add_action('login_form', array($this, 'add_social_login_buttons'));
        
        // Handle the social login.
        add_action('wp', array($this, 'handle_social_login'));
        
        // Display social login messages.
        add_action('login_footer', array($this, 'display_social_login_messages'));
    }

    /**
     * Add social login buttons to the login page.
     */
    public function add_social_login_buttons() {
        // Check if the user is not logged in.
        if (!is_user_logged_in()) {
            // Add the social login buttons to the login page.
            ?>
            <div class="social-login">
                <h3><?php _e('Login with Social Accounts', 'my-wordpress-plugin'); ?></h3>
                <a href="<?php echo esc_url($this->get_social_login_url('facebook')); ?>" class="social-login-button facebook"><?php _e('Login with Facebook', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('twitter')); ?>" class="social-login-button twitter"><?php _e('Login with Twitter', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('google')); ?>" class="social-login-button google"><?php _e('Login with Google', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('microsoft')); ?>" class="social-login-button microsoft"><?php _e('Login with Microsoft', 'my-wordpress-plugin'); ?></a>
                <a href="<?php echo esc_url($this->get_social_login_url('tiktok')); ?>" class="social-login-button tiktok"><?php _e('Login with TikTok', 'my-wordpress-plugin'); ?></a>
            </div>
            <?php
        }
    }

    /**
     * Handle the social login.
     */
    public function handle_social_login() {
        // Check if a social login request has been made.
        if (isset($_GET['social-login'])) {
            // Get the social network.
            $social_network = sanitize_text_field($_GET['social-login']);

            // Check if the social network is valid.
            if (in_array($social_network, array('facebook', 'twitter', 'google', 'microsoft', 'tiktok'))) {
                // Get the social network user details.
                $social_user = $this->get_social_user_details($social_network);

                // Check if the social user details are valid.
                if ($social_user && isset($social_user->email)) {
                    // Check if the social user email is already registered.
                    $user_id = email_exists($social_user->email);

                    if (!$user_id) {
                        // Create a new user account.
                        $user_id = $this->create_new_user_account($social_user);

                        if (!$user_id) {
                            // Failed to create a new user account.
                            $this->set_social_login_message('error', __('Failed to create a new user account.', 'my-wordpress-plugin'));
                        }
                    }

                    // Check if the user account is valid.
                    if ($user_id) {
                        // Sign in the user.
                        wp_set_auth_cookie($user_id);
                        wp_set_current_user($user_id);

                        // Redirect to the home page
                if (is_wp_error($user_id)) {
                    // If there was an error creating or updating the user, show an error message.
                    $error_message = $user_id->get_error_message();
                    wp_die($error_message, __('User account creation error', 'my-wordpress-plugin'));
                } else {
                    // If the user was successfully created or updated, log the user in and redirect to the homepage.
                    wp_set_auth_cookie($user_id, true);
                    wp_redirect(home_url());
                    exit;
                }
            }
        }
    }

    /**
     * Get the social login URL for the specified provider.
     *
     * @param string $provider The social login provider to get the URL for.
     *
     * @return string The social login URL.
     */
    private function get_social_login_url($provider) {
        // Get the redirect URL.
        $redirect_url = home_url('social-login-callback');
        
        // Get the social login URL for the specified provider.
        switch ($provider) {
            case 'facebook':
                $social_login_url = 'https://www.facebook.com/v10.0/dialog/oauth?client_id=' . urlencode($this->get_option('facebook_app_id')) . '&redirect_uri=' . urlencode($redirect_url) . '&scope=email';
                break;
            case 'twitter':
                $social_login_url = 'https://api.twitter.com/oauth/authenticate?oauth_token=' . $this->get_request_token('twitter');
                break;
            case 'google':
                $social_login_url = 'https://accounts.google.com/o/oauth2/auth?client_id=' . urlencode($this->get_option('google_client_id')) . '&redirect_uri=' . urlencode($redirect_url) . '&scope=email&response_type=code';
                break;
            case 'microsoft':
                $social_login_url = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=' . urlencode($this->get_option('microsoft_client_id')) . '&redirect_uri=' . urlencode($redirect_url) . '&scope=User.Read&response_type=code';
                break;
            case 'tiktok':
                $social_login_url = 'https://open-api.tiktok.com/platform/oauth/connect?client_key=' . urlencode($this->get_option('tiktok_client_key')) . '&redirect_uri=' . urlencode($redirect_url) . '&scope=user.info.basic';
                break;
            default:
                $social_login_url = '';
        }
        
        return $social_login_url;
    }
}

