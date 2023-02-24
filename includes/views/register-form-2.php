<?php
/**
 * Plugin Register Form View.
 *
 * This module provides the Register Form.
 *
 * @package my-wordpress-plugin
 * @subpackage Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the Register Form.
 */
function my_wordpress_plugin_render_register_form() {
	?>
	<form method="post" id="my-wordpress-plugin-register-form">
		<label for="my-wordpress-plugin-register-username"><?php esc_html_e( 'Username', 'my-wordpress-plugin' ); ?></label>
		<input type="text" name="my-wordpress-plugin-register-username" id="my-wordpress-plugin-register-username" required>
		<label for="my-wordpress-plugin-register-email"><?php esc_html_e( 'Email', 'my-wordpress-plugin' ); ?></label>
		<input type="email" name="my-wordpress-plugin-register-email" id="my-wordpress-plugin-register-email" required>
		<label for="my-wordpress-plugin-register-password"><?php esc_html_e( 'Password', 'my-wordpress-plugin' ); ?></label>
		<input type="password" name="my-wordpress-plugin-register-password" id="my-wordpress-plugin-register-password" required>
		<label for="my-wordpress-plugin-register-password-confirm"><?php esc_html_e( 'Confirm Password', 'my-wordpress-plugin' ); ?></label>
		<input type="password" name="my-wordpress-plugin-register-password-confirm" id="my-wordpress-plugin-register-password-confirm" required>
		<input type="submit" value="<?php esc_attr_e( 'Register', 'my-wordpress-plugin' ); ?>">
	</form>
	<?php
}
