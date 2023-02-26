<?php
/**
 * Class My Wordpress Plugin.
 *
 * This module provides the user for the plugin.
 *
 * @package my-wordpress-plugin
 * @subpackage Core
 */

class My_Plugin {

    private static $instance;

    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    public function register_post_type() {
        $labels = array(
            'name' => __( 'My Plugin', 'my-wordpress-plugin' ),
            'singular_name' => __( 'My Plugin', 'my-wordpress-plugin' ),
            'add_new' => __( 'Add New', 'my-wordpress-plugin' ),
            'add_new_item' => __( 'Add New My Plugin', 'my-wordpress-plugin' ),
            'edit_item' => __( 'Edit My Plugin', 'my-wordpress-plugin' ),
            'new_item' => __( 'Add New My Plugin', 'my-wordpress-plugin' ),
            'view_item' => __( 'View My Plugin', 'my-wordpress-plugin' ),
            'search_items' => __( 'Search My Plugin', 'my-wordpress-plugin' ),
            'not_found' => __( 'No My Plugin Found', 'my-wordpress-plugin' ),
            'not_found_in_trash' => __( 'No My Plugin Found in Trash', 'my-wordpress-plugin' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'supports' => array( 'title', 'editor', 'thumbnail' ),
            'capability_type' => 'post',
            'has_archive' => true,
        );

        register_post_type( 'my-plugin', $args );
    }

    public function enqueue_public_assets() {
        wp_enqueue_style( 'my-plugin-public-style', MY_PLUGIN_ASSETS_URL . 'css/style.css', array(), '1.0.0', 'all' );
        wp_enqueue_script( 'my-plugin-public-script', MY_PLUGIN_ASSETS_URL . 'js/main.js', array( 'jquery' ), '1.0.0', true );
    }

    public function enqueue_admin_assets() {
        wp_enqueue_style( 'my-plugin-admin-style', MY_PLUGIN_ASSETS_URL . 'css/admin-style.css', array(), '1.0.0', 'all' );
        wp_enqueue_script( 'my-plugin-admin-script', MY_PLUGIN_ASSETS_URL . 'js/admin.js', array( 'jquery' ), '1.0.0', true );
    }

    private function register_assets() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    public function init() {
        $this->register_assets();
    }

}

My_Plugin::get_instance();
