<?php
/**
 * Admin Settings
 *
 * This file contains the Admin Settings class for My WordPress Plugin
 *
 * @package My_WordPress_Plugin
 */

if (!class_exists('Admin_Settings')) {
    /**
     * Admin Settings Class
     *
     * This class handles the creation and rendering of the admin settings page.
     *
     * @since 1.0.0
     */
    class Admin_Settings {
        /**
         * The ID of this plugin.
         *
         * @since 1.0.0
         * @access private
         * @var string $plugin_name The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since 1.0.0
         * @access private
         * @var string $version The current version of this plugin.
         */
        private $version;

        /**
         * Initialize the class and set its properties.
         *
         * @since 1.0.0
         * @param string $plugin_name The name of the plugin.
         * @param string $version The version of the plugin.
         */
        public function __construct($plugin_name, $version) {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
        }

        /**
         * Register the settings page.
         *
         * @since 1.0.0
         */
        public function register_settings_page() {
            add_options_page(
                'My WordPress Plugin Settings',
                'My WordPress Plugin',
                'manage_options',
                'my-wordpress-plugin',
                array($this, 'render_settings_page')
            );
        }

        /**
         * Render the settings page.
         *
         * @since 1.0.0
         */
        public function render_settings_page() {
            // Verify that the user is allowed to access the settings page.
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // If the form has been submitted, process the data.
            if (isset($_POST['my_wordpress_plugin_settings_submit'])) {
                $this->process_settings();
            }

            // Output the settings page HTML.
            include_once plugin_dir_path(__FILE__) . 'partials/admin-settings.php';
        }

        /**
         * Process the settings form data.
         *
         * @since 1.0.0
         * @access private
         */
        private function process_settings() {
            // Verify the nonce.
            if (!isset($_POST['my_wordpress_plugin_settings_nonce']) || !wp_verify_nonce($_POST['my_wordpress_plugin_settings_nonce'], 'my_wordpress_plugin_settings')) {
                wp_die(__('You do not have sufficient permissions to modify these settings.'));
            }

            // Process the data.
            // TODO: Add your own data processing code here.
        }
    }
}
