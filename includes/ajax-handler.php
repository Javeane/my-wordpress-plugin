<?php

/**
 * This file is responsible for handling all Ajax requests
 *
 * @package My_Wordpress_Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle Ajax requests
 */
function my_wp_plugin_ajax_handler() {

	// Check if request is an Ajax request
	if ( ! isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) || strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) != 'xmlhttprequest' ) {
		return;
	}

	// Check the action parameter and call the corresponding function
	if ( isset( $_POST['action'] ) ) {

		switch ( $_POST['action'] ) {

			case 'my_wp_plugin_upload_avatar':
				my_wp_plugin_upload_avatar();
				break;

			case 'my_wp_plugin_delete_avatar':
				my_wp_plugin_delete_avatar();
				break;

			default:
				wp_send_json_error( 'Invalid action' );
				break;
		}

	} else {
		wp_send_json_error( 'No action specified' );
	}

	exit;
}
add_action( 'wp_ajax_my_wp_plugin_ajax_handler', 'my_wp_plugin_ajax_handler' );
add_action( 'wp_ajax_nopriv_my_wp_plugin_ajax_handler', 'my_wp_plugin_ajax_handler' );

/**
 * Upload user avatar
 */
function my_wp_plugin_upload_avatar() {

	// Check if user is logged in
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( 'You must be logged in to upload an avatar' );
	}

	// Check nonce
	if ( ! wp_verify_nonce( $_POST['nonce'], 'my_wp_plugin_upload_avatar' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	// Check if file was uploaded
	if ( ! isset( $_FILES['avatar_file'] ) || ! $_FILES['avatar_file']['error'] == UPLOAD_ERR_OK ) {
		wp_send_json_error( 'No file uploaded' );
	}

	// Get the current user's ID
	$user_id = get_current_user_id();

	// Get the file extension
	$file_extension = strtolower( pathinfo( $_FILES['avatar_file']['name'], PATHINFO_EXTENSION ) );

	// Check if file extension is allowed
	$allowed_extensions = array( 'jpg', 'jpeg', 'png', 'gif' );
	if ( ! in_array( $file_extension, $allowed_extensions ) ) {
		wp_send_json_error( 'Invalid file type' );
	}

	// Generate a unique file name
	$file_name = uniqid( 'avatar_' . $user_id . '_' ) . '.' . $file_extension;

	// Upload the file
	$upload_dir = wp_upload_dir();
	$upload_path = $upload_dir['basedir'] . '/my_wp_plugin_avatars/';
	if ( ! file_exists( $upload_path ) ) {
		mkdir( $upload_path, 0755, true );
	}
	if ( ! move_uploaded_file( $_FILES['avatar_file']['tmp_name'], $upload_path . $file_name ) ) {
		wp_send_json_error( 'Failed to upload file' );
	}

		// Update the user's meta data
	update_user_meta( $user_id, 'my_wp_plugin_avatar', $file_name );

	// Create response object
	$response = array(
		'success' => true,
		'message' => __( 'Avatar uploaded successfully.', 'my-wp-plugin' ),
		'url'     => $upload_dir['baseurl'] . '/' . $file_name,
	);

	// Send the JSON response
	wp_send_json( $response );
}
add_action( 'wp_ajax_my_wp_plugin_upload_avatar', 'my_wp_plugin_upload_avatar' );
add_action( 'wp_ajax_nopriv_my_wp_plugin_upload_avatar', 'my_wp_plugin_upload_avatar' );
