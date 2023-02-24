<?php

/**
 * This file is responsible for handling AJAX requests
 *
 * @package My_Wordpress_Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Handle avatar upload AJAX request
 */
function my_wp_plugin_handle_avatar_upload() {
    $response = array();

    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['nonce'], 'my-wp-plugin-avatar-upload' ) ) {
        $response['success'] = false;
        $response['message'] = __( 'Nonce verification failed', 'my-wp-plugin' );
        wp_send_json( $response );
    }

    // Check if a file was uploaded
    if ( ! isset( $_FILES['file'] ) ) {
        $response['success'] = false;
        $response['message'] = __( 'No file uploaded', 'my-wp-plugin' );
        wp_send_json( $response );
    }

    // Get the uploaded file
    $file = $_FILES['file'];

    // Check for errors
    if ( $file['error'] !== UPLOAD_ERR_OK ) {
        $response['success'] = false;
        $response['message'] = __( 'File upload error', 'my-wp-plugin' );
        wp_send_json( $response );
    }

    // Generate a unique file name
    $file_name = uniqid() . '_' . $file['name'];

    // Move the uploaded file to the plugin's upload directory
    $upload_dir = MY_WP_PLUGIN_UPLOAD_DIR;
    if ( ! file_exists( $upload_dir ) ) {
        mkdir( $upload_dir, 0755, true );
    }
    $file_path = $upload_dir . '/' . $file_name;
    if ( ! move_uploaded_file( $file['tmp_name'], $file_path ) ) {
        $response['success'] = false;
        $response['message'] = __( 'Error saving uploaded file', 'my-wp-plugin' );
        wp_send_json( $response );
    }

    // Get the user ID
    $user_id = get_current_user_id();

    // Update the user's meta data
    update_user_meta( $user_id, 'my_wp_plugin_avatar', $file_path );

    // Return the file URL to the frontend
    $response['success'] = true;
    $response['file_url'] = MY_WP_PLUGIN_UPLOAD_URL . '/' . $file_name;
    wp_send_json( $response );
}
add_action( 'wp_ajax_my_wp_plugin_avatar_upload', 'my_wp_plugin_handle_avatar_upload' );
add_action( 'wp_ajax_nopriv_my_wp_plugin_avatar_upload', 'my_wp_plugin_handle_avatar_upload' );
