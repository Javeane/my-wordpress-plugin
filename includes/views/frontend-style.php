<?php

/**
 * This file is responsible for enqueuing all the frontend styles and scripts
 *
 * @package My_Wordpress_Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue frontend scripts and styles
 */
function my_wp_plugin_enqueue_frontend_scripts() {
	// Enqueue main stylesheet.
	wp_enqueue_style( 'my-wp-plugin-frontend-style', MY_WP_PLUGIN_PLUGIN_URL . '/assets/css/frontend-style.css', array(), MY_WP_PLUGIN_VERSION );

	// Enqueue jQuery library.
	wp_enqueue_script( 'jquery' );

	// Enqueue main JavaScript file.
	wp_enqueue_script( 'my-wp-plugin-frontend-script', MY_WP_PLUGIN_PLUGIN_URL . '/assets/js/frontend-script.js', array( 'jquery' ), MY_WP_PLUGIN_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'my_wp_plugin_enqueue_frontend_scripts' );
