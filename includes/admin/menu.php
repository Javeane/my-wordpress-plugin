<?php
/**
 * Adds a custom menu item to the WordPress admin menu.
 */
function my_wp_plugin_add_menu_item() {
    // Add a top-level menu item.
    add_menu_page(
        __('My WP Plugin', 'my-wp-plugin'),
        __('My WP Plugin', 'my-wp-plugin'),
        'manage_options',
        'my-wp-plugin',
        'my_wp_plugin_dashboard',
        'dashicons-admin-plugins',
        30
    );

    // Add a submenu item for the dashboard.
    add_submenu_page(
        'my-wp-plugin',
        __('Dashboard', 'my-wp-plugin'),
        __('Dashboard', 'my-wp-plugin'),
        'manage_options',
        'my-wp-plugin'
    );

    // Add a submenu item for the plugin settings page.
    add_submenu_page(
        'my-wp-plugin',
        __('Settings', 'my-wp-plugin'),
        __('Settings', 'my-wp-plugin'),
        'manage_options',
        'my-wp-plugin-settings',
        'my_wp_plugin_settings_page'
    );

    // Add a submenu item for the plugin documentation page.
    add_submenu_page(
        'my-wp-plugin',
        __('Documentation', 'my-wp-plugin'),
        __('Documentation', 'my-wp-plugin'),
        'manage_options',
        'my-wp-plugin-documentation',
        'my_wp_plugin_documentation_page'
    );

    // Add a submenu item for the plugin support page.
    add_submenu_page(
        'my-wp-plugin',
        __('Support', 'my-wp-plugin'),
        __('Support', 'my-wp-plugin'),
        'manage_options',
        'my-wp-plugin-support',
        'my_wp_plugin_support_page'
    );
}
add_action( 'admin_menu', 'my_wp_plugin_add_menu_item' );

/**
 * Renders the plugin dashboard page.
 */
function my_wp_plugin_dashboard() {
    // Check user capabilities.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Render the dashboard template.
    include_once MY_WP_PLUGIN_PATH . 'templates/dashboard.php';
}

/**
 * Renders the plugin settings page.
 */
function my_wp_plugin_settings_page() {
    // Check user capabilities.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Render the settings template.
    include_once MY_WP_PLUGIN_PATH . 'templates/settings.php';
}

/**
 * Renders the plugin documentation page.
 */
function my_wp_plugin_documentation_page() {
    // Check user capabilities.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Render the documentation template.
    include_once MY_WP_PLUGIN_PATH . 'templates/documentation.php';
}

/**
 * Renders the plugin support page.
 */
function my_wp_plugin_support_page() {
    // Check user capabilities.
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Render the support template.
    include_once MY_WP_PLUGIN_PATH . 'templates/support.php';
}
<?php
/**
 * Register custom menu page
 */
function my_wp_plugin_register_menu_page() {
    add_menu_page(
        'Custom Menu Page',
        'Custom Menu',
        'manage_options',
        'my_wp_plugin_custom_menu_page',
        'my_wp_plugin_render_custom_menu_page'
    );
}
add_action( 'admin_menu', 'my_wp_plugin_register_menu_page' );

/**
 * Render custom menu page
 */
function my_wp_plugin_render_custom_menu_page() {
    ?>
    <div class="wrap">
        <h1>Custom Menu Page</h1>
        <p>This is a custom menu page.</p>
    </div>
    <?php
}
