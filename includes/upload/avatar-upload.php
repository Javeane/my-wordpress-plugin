<?php
/**
 * This file handles the user avatar upload functionality.
 *
 * @package My_Wordpress_Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Process user avatar upload request
 */
function my_wp_plugin_process_avatar_upload() {
    // Verify the nonce before proceeding.
	if ( ! isset( $_POST['my_wp_plugin_avatar_upload_nonce'] ) || ! wp_verify_nonce( $_POST['my_wp_plugin_avatar_upload_nonce'], 'my_wp_plugin_avatar_upload' ) ) {
		wp_send_json_error( __( 'Security check failed.', 'my-wp-plugin' ) );
	}

    // Check if user is logged in.
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( __( 'You need to be logged in to perform this action.', 'my-wp-plugin' ) );
	}

    // Check if the file was uploaded.
	if ( empty( $_FILES ) || ! isset( $_FILES['avatar'] ) ) {
		wp_send_json_error( __( 'No file was uploaded.', 'my-wp-plugin' ) );
	}

	// Get current user ID.
	$user_id = get_current_user_id();

	// Get the uploaded file info.
	$file = $_FILES['avatar'];

	// Get the file extension.
	$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

	// Allowed file types.
	$allowed_types = array( 'jpg', 'jpeg', 'png', 'gif' );

	// Check if a file was uploaded.
	if ( empty( $_FILES ) || ! isset( $_FILES['avatar'] ) || ! is_uploaded_file( $_FILES['avatar']['tmp_name'] ) ) {
		wp_send_json_error( __( 'No file was uploaded.', 'my-wp-plugin' ) );
	}

	// Check if the file type is allowed.
	if ( ! in_array( $ext, $allowed_types ) ) {
		wp_send_json_error( __( 'Only jpg, jpeg, png and gif file types are allowed.', 'my-wp-plugin' ) );
	}

	// Set the file name.
	$file_name = 'avatar-' . $user_id . '.' . $ext;

	// Set the file path.
	$file_path = MY_WP_PLUGIN_PLUGIN_DIR . '/uploads/' . $file_name;

	// Move the uploaded file to the plugin uploads folder.
	if ( move_uploaded_file( $file['tmp_name'], $file_path ) ) {

		// Update the user meta with the avatar URL.
		update_user_meta( $user_id, 'my_wp_plugin_avatar_url', MY_WP_PLUGIN_PLUGIN_URL . '/uploads/' . $file_name );

		wp_send_json_success( __( 'Avatar uploaded successfully.', 'my-wp-plugin' ) );
	}

	wp_send_json_error( __( 'Error uploading file.', 'my-wp-plugin' ) );
}

add_action( 'wp_ajax_my_wp_plugin_avatar_upload', 'my_wp_plugin_process_avatar_upload' );
