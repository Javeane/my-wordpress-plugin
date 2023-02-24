<?php
/**
 * Login form view template.
 *
 * This template is used to display the login form.
 *
 * @package My_Wordpress_Plugin
 */

if (!defined('ABSPATH')) {
  exit;
}

?>

<!-- Login Form -->
<div class="my-plugin-login-form">
  <?php
  // Check if user is logged in
  if (is_user_logged_in()) {
    echo '<p>You are already logged in.</p>';
  } else {
    // Check if form submitted
    if (isset($_POST['my_plugin_login_submit'])) {
      // Process form data
      $username = sanitize_text_field($_POST['my_plugin_username']);
      $password = $_POST['my_plugin_password'];

      // Validate form data
      $errors = array();

      if (empty($username)) {
        $errors[] = 'Please enter a username.';
      }

      if (empty($password)) {
        $errors[] = 'Please enter a password.';
      }

      // Check for errors and log user in
      if (empty($errors)) {
        $credentials = array(
          'user_login' => $username,
          'user_password' => $password,
        );

        $user = wp_signon($credentials, false);

        if (is_wp_error($user)) {
          echo '<p class="my-plugin-error">Invalid username or password.</p>';
        } else {
          echo '<p class="my-plugin-success">Login successful. Redirecting...</p>';
          echo '<meta http-equiv="refresh" content="3; url=' . home_url() . '">';
        }
      } else {
        // Display error messages
        foreach ($errors as $error) {
          echo '<p class="my-plugin-error">' . $error . '</p>';
        }
      }
    }

    // Display login form
    echo '<form id="my-plugin-login-form" action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
    echo '<p>';
    echo '<label for="my_plugin_username">Username:</label>';
    echo '<input type="text" name="my_plugin_username" id="my_plugin_username" />';
    echo '</p>';
    echo '<p>';
    echo '<label for="my_plugin_password">Password:</label>';
    echo '<input type="password" name="my_plugin_password" id="my_plugin_password" />';
    echo '</p>';
    echo '<p>';
    echo '<input type="submit" name="my_plugin_login_submit" value="Login" />';
    echo '</p>';
    echo '</form>';

    // Display password reset link
    echo '<p><a href="' . wp_lostpassword_url() . '">Lost your password?</a></p>';

    // Display registration link
    echo '<p><a href="' . wp_registration_url() . '">Register</a></p>';
  }
  ?>
</div>
<p class="lost-password">
    <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'my-wordpress-plugin' ); ?></a>
</p>

<?php
/**
 * Login form view template.
 *
 * This template is used to display the login form.
 *
 * @package My_Wordpress_Plugin
 */

// Check if form was submitted
if ( isset( $_POST['my_wp_login_nonce'] ) ) {

    // Verify form nonce for security
    if ( ! wp_verify_nonce( $_POST['my_wp_login_nonce'], 'my_wp_login' ) ) {
        // Nonce verification failed, display error message
        $error = new WP_Error( 'nonce_verification_failed', __( 'Security check failed. Please try again.', 'my-wordpress-plugin' ) );
    }

    // Get form data
    $username = sanitize_user( $_POST['my_wp_username'] );
    $password = sanitize_text_field( $_POST['my_wp_password'] );
    $remember = isset( $_POST['my_wp_remember'] ) ? true : false;

    // Validate form data
    if ( empty( $username ) ) {
        // Username is empty, display error message
        $error = new WP_Error( 'empty_username', __( 'Please enter your username.', 'my-wordpress-plugin' ) );
    } elseif ( empty( $password ) ) {
        // Password is empty, display error message
        $error = new WP_Error( 'empty_password', __( 'Please enter your password.', 'my-wordpress-plugin' ) );
    } else {
        // Form data is valid, authenticate user
        $user = wp_signon( array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        ) );

        if ( is_wp_error( $user ) ) {
            // Authentication failed, display error message
            $error = $user;
        } else {
            // Authentication succeeded, redirect to home page
            wp_redirect( home_url() );
            exit;
        }
    }
}

// Display error message if any
if ( isset( $error ) ) {
    echo '<div class="error">' . $error->get_error_message() . '</div>';
}
?>
<?php
/**
 * Login form view template.
 *
 * This template is used to display the login form.
 *
 * @package My_Wordpress_Plugin
 */

// Check if form was submitted
if ( isset( $_POST['my_wp_login_nonce'] ) ) {

    // Verify form nonce for security
    if ( ! wp_verify_nonce( $_POST['my_wp_login_nonce'], 'my_wp_login' ) ) {
        // Nonce verification failed, display error message
        $error = new WP_Error( 'nonce_verification_failed', __( 'Security check failed. Please try again.', 'my-wordpress-plugin' ) );
    }

    // Get form data
    $username = sanitize_user( $_POST['my_wp_username'] );
    $password = sanitize_text_field( $_POST['my_wp_password'] );
    $remember = isset( $_POST['my_wp_remember'] ) ? true : false;

    // Validate form data
    if ( empty( $username ) ) {
        // Username is empty, display error message
        $error = new WP_Error( 'empty_username', __( 'Please enter your username.', 'my-wordpress-plugin' ) );
    } elseif ( empty( $password ) ) {
        // Password is empty, display error message
        $error = new WP_Error( 'empty_password', __( 'Please enter your password.', 'my-wordpress-plugin' ) );
    } else {
        // Form data is valid, authenticate user
        $user = wp_signon( array(
            'user_login'    => $username,
            'user_password' => $password,
            'remember'      => $remember,
        ) );

        if ( is_wp_error( $user ) ) {
            // Authentication failed, display error message
            $error = $user;
        } else {
            // Authentication succeeded, redirect to home page
            wp_redirect( home_url() );
            exit;
        }
    }
}

// Display error message if any
if ( isset( $error ) ) {
    echo '<div class="error">' . $error->get_error_message() . '</div>';
}

// Display success message if any
if ( isset( $_GET['login_success'] ) && $_GET['login_success'] == 'true' ) {
    echo '<div class="success">' . __( 'You have successfully logged in.', 'my-wordpress-plugin' ) . '</div>';
}
?>

<?php
/**
 * Login form view template.
 *
 * This template is used to display the login form.
 *
 * @package My_Wordpress_Plugin
 */

// Check if form was submitted
if ( isset( $_POST['my_wp_login_nonce'] ) ) {
    // ...
}

// Display error message if any
if ( isset( $error ) ) {
    echo '<div class="error">' . $error->get_error_message() . '</div>';
}
?>

<form method="post">
    <!-- Login form fields here -->
    
    <p class="my-wp-register-link"><?php printf( __( 'Don\'t have an account? <a href="%s">Register now</a>.', 'my-wordpress-plugin' ), esc_url( wp_registration_url() ) ); ?></p>
</form>
