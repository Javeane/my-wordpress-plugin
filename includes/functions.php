<?php
/**
 * Define functions used in the plugin.
 */

/**
 * Add a custom menu item to the WordPress admin menu.
 */
function add_plugin_admin_menu() {
    add_menu_page(
        'My WordPress Plugin Settings',
        'My WP Plugin',
        'manage_options',
        'my-wp-plugin-settings',
        'my_plugin_options_page'
    );
}

/**
 * Display the plugin settings page.
 */
function my_plugin_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Output the options page HTML
    include(MY_WORDPRESS_PLUGIN_DIR . '/templates/options.php');
}
