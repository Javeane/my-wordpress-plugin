<?php
/**
 * Plugin Name: My WordPress Plugin
 * Plugin URI: https://uscens.com/
 * Description: This is a WordPress plugin that adds various features to a WordPress website.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://uscens.com/
 * License: GPL2
 */

defined('ABSPATH') or die('No script kiddies please!');

function my_plugin_init() {
    global $email_verification_model;
    require_once(plugin_dir_path(__FILE__) . 'includes/models/email-verification.php');
    $email_verification_model = new Email_Verification_Model();
}
add_action('plugins_loaded', 'my_plugin_init');

// 导入 PHPMailer 类文件
require_once plugin_dir_path( __FILE__ ) . 'phpmailer/class.phpmailer.php';


if (!class_exists('My_WordPress_Plugin')) {
  class My_WordPress_Plugin {

    public function __construct() {
      $this->define_constants();
      $this->register_assets();
      $this->register_templates();
      $this->register_core();
      $this->register_email();
      $this->register_upload();
      add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
    }

/**
 * Define plugin constants
 */
private function define_constants() {
  define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
  define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));
  define('MY_PLUGIN_TEMPLATE_PATH', MY_PLUGIN_PATH . 'templates/');
  define('MY_PLUGIN_ASSETS_URL', MY_PLUGIN_URL . 'assets/');
}

/**
 * Register plugin assets
 */
private function register_assets() {
      add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
      add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
}

/**
 * Register plugin templates
 */
private function register_templates() {
  add_filter('template_include', array($this, 'include_template_functions'));
}

/**
 * Register plugin core
 */
private function register_core() {
  require_once MY_PLUGIN_PATH . 'includes/core/login.php';
  require_once MY_PLUGIN_PATH . 'includes/core/register.php';
  require_once MY_PLUGIN_PATH . 'includes/core/social-login.php';
  require_once MY_PLUGIN_PATH . 'includes/core/verification.php';
  require_once MY_PLUGIN_PATH . 'includes/core/password-reset.php';
}

/**
 * Register plugin email
 */
private function register_email() {
  require_once MY_PLUGIN_PATH . 'includes/email/mailer.php';
  require_once MY_PLUGIN_PATH . 'includes/email/email-template.php';
}

/**
 * Register plugin upload
 */
private function register_upload() {
  require_once MY_PLUGIN_PATH . 'includes/upload/avatar-upload.php';
}

/**
 * Add plugin settings link
 */
public function add_settings_link($links) {
  $settings_link = '<a href="' . admin_url('options-general.php?page=my-wordpress-plugin') . '">' . __('Settings') . '</a>';
  array_push($links, $settings_link);
  return $links;
/**
 * Register plugin assets
 */
private function register_assets() {
  add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
  add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
}

/**
 * Enqueue admin assets
 */
public function enqueue_admin_assets() {
  wp_enqueue_style('my-wp-plugin-admin-style', MY_PLUGIN_ASSETS_URL . 'css/admin-style.css');
  wp_enqueue_script('my-wp-plugin-admin-script', MY_PLUGIN_ASSETS_URL . 'js/admin.js', array('jquery'), '1.0', true);
}
