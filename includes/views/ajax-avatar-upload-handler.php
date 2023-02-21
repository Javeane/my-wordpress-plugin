<?php

/**
 * Ajax Avatar Upload Handler
 *
 * This file is used to handle avatar uploads via AJAX.
 *
 * @package My_WordPress_Plugin
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_upload_avatar', 'my_wp_plugin_ajax_upload_avatar' );
add_action( 'wp_ajax_nopriv_upload_avatar', 'my_wp_plugin_ajax_upload_avatar' );

function my_wp_plugin_ajax_upload_avatar() {
    // Check if the user is logged in.
    if ( ! is_user_logged_in() ) {
        wp_send_json_error( __( 'You must be logged in to upload an avatar.', 'my-wordpress-plugin' ) );
    }

    // Check if the upload nonce is valid.
    if ( ! wp_verify_nonce( $_POST['nonce'], 'my_wordpress_plugin_upload_avatar' ) ) {
        wp_send_json_error( array(
            'message' => __( 'Invalid nonce', 'my-wordpress-plugin' )
        ) );
    }

    // Check if current user can upload an avatar
    if ( ! current_user_can( 'upload_files' ) ) {
        wp_send_json_error( __( 'You are not allowed to upload an avatar.', 'my-wordpress-plugin' ) );
    }

    // Check if the file was uploaded
    if ( empty( $_FILES['avatar_file'] ) || $_FILES['avatar_file']['error'] ) {
        wp_send_json_error( __( 'The avatar file could not be uploaded.', 'my-wordpress-plugin' ) );
    }


    // Check file type
    $file_type = wp_check_filetype( $_FILES['avatar_file']['name'] );
    if ( ! in_array( $file_type['ext'], array( 'jpg', 'jpeg', 'gif', 'png' ) ) ) {
        wp_send_json_error( __( 'Invalid file type.', 'my-wordpress-plugin' ) );
    }

    // Check file size
    if ( $_FILES['avatar_file']['size'] > wp_max_upload_size() ) {
        wp_send_json_error( __( 'The file size exceeds the allowed limit.', 'my-wordpress-plugin' ) );
    }

    // Set upload directory
    $upload_dir = wp_upload_dir();
    $upload_file_path = $upload_dir['basedir'] . '/avatars/';
    $upload_file_url = $upload_dir['baseurl'] . '/avatars/';

    // Create the avatars directory if it does not exist
    if ( ! file_exists( $upload_file_path ) ) {
        wp_mkdir_p( $upload_file_path );
    }

    // Set correct file permissions for upload directory
    $upload_dir = wp_upload_dir();
    $wp_upload_dir = $upload_dir['basedir'];
    $htaccess_file = $wp_upload_dir . '/.htaccess';
    if ( ! file_exists( $htaccess_file ) ) {

        // Create .htaccess file if it does not exist
        $htaccess_template = "Deny from all";
        $fp = fopen( $htaccess_file, 'w' );
        fwrite( $fp, $htaccess_template );
        fclose( $fp );
    }
    $perms = 0644;
    $htperms = 0640;
    $dirs = array( $wp_upload_dir );
    foreach ( $dirs as $dir ) {
        if ( is_dir( $dir ) ) {
            chmod( $dir, $perms );
        }
        if ( is_file( $htaccess_file ) ) {
            chmod( $htaccess_file, $htperms );
        }
    }
    // Generate a unique file name for the uploaded file
    $filename = wp_unique_filename( $upload_file_path, $uploaded_file['name'] );

    // Move the uploaded file to the avatars directory
    if ( ! move_uploaded_file( $uploaded_file['tmp_name'], $upload_file_path . $filename ) ) {
        wp_send_json_error( __( 'The avatar file could not be moved.', 'my-wordpress-plugin' ) );
    }

    // Get the current user ID
    $user_id = get_current_user_id();

    // Get the user's current avatar URL
    $old_avatar_url = get_user_meta( $user_id, 'avatar', true );

    // Delete the user's old avatar if it exists
    if ( ! empty( $old_avatar_url ) ) {
        $old_avatar_path = str_replace( $upload_file_url, $upload_file_path, $old_avatar_url );
        if ( file_exists( $old_avatar_path ) ) {
            unlink( $old_avatar_path );
        }
    }

    // Update the user meta with the file URL
    update_user_meta( $user_id, 'avatar', $upload_file_url . $filename );

    // Return the success message and the new avatar URL
    $response = array(
        'success' => true,
        'message' => __( 'Avatar uploaded successfully.', 'my-wordpress-plugin' ),
        'avatar_url' => $upload_file_url . $filename,
    );
    wp_send_json( $response );
    wp_die();
    