<?php
/**
 * The template for displaying the login form.
 *
 * This template can be overridden by copying it to yourtheme/my-wordpress-plugin/views/login-form.php.
 *
 * @package My_WordPress_Plugin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<form method="post" class="my-wp-plugin-form">
    <h3><?php esc_html_e( 'Login', 'my-wp-plugin' ); ?></h3>

    <?php if ( isset( $login_error ) ) : ?>
        <div class="my-wp-plugin-error">
            <p><?php echo esc_html( $login_error ); ?></p>
        </div>
    <?php endif; ?>

    <div class="my-wp-plugin-form-row">
        <label for="username"><?php esc_html_e( 'Username', 'my-wp-plugin' ); ?></label>
        <input type="text" name="username" id="username" required>
    </div>

    <div class="my-wp-plugin-form-row">
        <label for="password"><?php esc_html_e( 'Password', 'my-wp-plugin' ); ?></label>
        <input type="password" name="password" id="password" required>
    </div>

    <div class="my-wp-plugin-form-row">
        <input type="hidden" name="action" value="my_wp_plugin_login">
        <button type="submit" class="my-wp-plugin-button"><?php esc_html_e( 'Login', 'my-wp-plugin' ); ?></button>
    </div>
</form>
