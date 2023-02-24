<?php
/**
 * Plugin Admin Page
 *
 * @package           my-wordpress-plugin
 *
 * Plugin Name:       My WordPress Plugin
 * Plugin URI:        https://example.com/plugins/my-wordpress-plugin/
 * Description:       A plugin to customize WordPress basic functionalities.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jevon Wang
 * Author URI:        https://example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-wordpress-plugin
 */
// 安全校验
defined('ABSPATH') or die('No script kiddies please!');

//第一部分：定义 WordPress 后台管理页面

// 添加插件设置菜单项
add_action('admin_menu', 'My WordPress Plugin'); //add_action('admin_menu', 'my_wordpress_plugin_add_admin_menu');

function my_wordpress_plugin_add_admin_menu() {
    add_menu_page(
        'My WordPress Plugin',             // 页面标题
        'My WP Plugin',                    // 菜单标题
        'manage_options',                  // 菜单权限
        'my_wordpress_plugin_settings',    // 菜单slug
        'my_wordpress_plugin_settings_page',// 显示的页面回调函数
        'dashicons-admin-generic'          // 菜单图标
    );

    // 添加子菜单项
    add_submenu_page(
        'my_wordpress_plugin_settings',    // 父级菜单slug
        '基本设置',                       // 页面标题
        '基本设置',                       // 菜单标题
        'manage_options',                  // 菜单权限
        'my_wordpress_plugin_settings',    // 菜单slug
        'my_wordpress_plugin_settings_page' // 显示的页面回调函数
    );

    add_submenu_page(
        'my_wordpress_plugin_settings',    // 父级菜单slug
        '高级设置',                       // 页面标题
        '高级设置',                       // 菜单标题
        'manage_options',                  // 菜单权限
        'my_wordpress_plugin_advanced_settings', // 菜单slug
        'my_wordpress_plugin_advanced_settings_page' // 显示的页面回调函数
    );
}

// 插件设置页面回调函数
function my_wordpress_plugin_settings_page() {
    // 检查用户权限
    if (!current_user_can('manage_options')) {
        return;
    }

    // 处理表单提交
    if (isset($_POST['my_wordpress_plugin_settings'])) {
        // 获取表单数据并进行安全过滤
        $login_url = sanitize_text_field($_POST['my_wordpress_plugin_login_url']);
        $register_enable_password_confirmation = isset($_POST['my_wordpress_plugin_register_enable_password_confirmation']) ? '1' : '0';
        $social_login_providers = isset($_POST['my_wordpress_plugin_social_login_providers']) ? sanitize_text_field($_POST['my_wordpress_plugin_social_login_providers']) : '';

        // 更新选项
        update_option('my_wordpress_plugin_login_url', $login_url);
        update_option('my_wordpress_plugin_register_enable_password_confirmation', $register_enable_password_confirmation);
        update_option('my_wordpress_plugin_social_login_providers', $social_login_providers);
    }

    // 获取选项值
    $login_url = get_option('my_wordpress_plugin_login_url', '');
    $register_enable_password_confirmation = get_option('my_wordpress_plugin_register_enable_password_confirmation', '0');
    $social_login_providers = get_option('my_wordpress_plugin_social_login_providers', '');

    // 插件设置页面 HTML
    ?>
    <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="">
        <?php wp_nonce_field('my_wordpress_plugin_settings'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="my_wordpress_plugin_login_url">自定义登录链接</label></th>
                    <td>
                        <input name="my_wordpress_plugin_login_url" type="url" id="my_wordpress_plugin_login_url" value="<?php echo esc_attr($login_url); ?>" class="regular-text">
                        <p class="description">在这里输入自定义的登录链接，留空则使用默认链接。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label>用户注册设置</label></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text"><span>用户注册设置</span></legend>
                            <label>
                                <input name="my_wordpress_plugin_register_enable_password_confirmation" type="checkbox" id="my_wordpress_plugin_register_enable_password_confirmation" value="1" <?php checked($register_enable_password_confirmation, '1'); ?>>
                                开启密码确认
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="my_wordpress_plugin_social_login_providers">社交登录设置</label></th>
                    <td>
                        <input name="my_wordpress_plugin_social_login_providers" type="text" id="my_wordpress_plugin_social_login_providers" value="<?php echo esc_attr($social_login_providers); ?>" class="regular-text">
                        <p class="description">在这里输入启用的社交登录提供商，多个提供商之间用英文逗号分隔，如：facebook,twitter,linkedin。</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="my_wordpress_plugin_settings" id="submit" class="button button-primary" value="保存更改"></p>
        </form>
    </div>

//第二部分：实现自定义登录 url

<?php

// Add a new option page to the Settings menu
add_action( 'admin_menu', 'custom_login_url_settings_page' );

function custom_login_url_settings_page() {
add_options_page(
__( 'Custom Login URL', 'custom-login-url' ),
__( 'Custom Login URL', 'custom-login-url' ),
'manage_options',
'custom-login-url',
'custom_login_url_settings_page_callback'
);
}

// Add the form fields to the custom login URL option page
function custom_login_url_settings_page_callback() {
if ( ! current_user_can( 'manage_options' ) ) {
return;
}
if ( isset( $_GET['settings-updated'] ) ) {
    add_settings_error( 'custom_login_url_messages', 'custom_login_url_message', __( 'Settings Saved', 'custom-login-url' ), 'updated' );
}

settings_errors( 'custom_login_url_messages' );
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields( 'custom_login_url' );
        do_settings_sections( 'custom_login_url' );
        submit_button( __( 'Save Settings', 'custom-login-url' ) );
        ?>
    </form>
</div>
<?php
}

// Register the custom login URL option and settings
add_action( 'admin_init', 'register_custom_login_url_settings' );

function register_custom_login_url_settings() {
register_setting(
'custom_login_url',
'custom_login_url',
array(
'type' => 'string',
'sanitize_callback' => 'esc_url_raw'
)
);
add_settings_section(
    'custom_login_url_section',
    __( 'Custom Login URL', 'custom-login-url' ),
    'custom_login_url_section_callback',
    'custom_login_url'
);

add_settings_field(
    'custom_login_url_field',
    __( 'Custom Login URL', 'custom-login-url' ),
    'custom_login_url_field_callback',
    'custom_login_url',
    'custom_login_url_section'
);
}

// Add the description to the custom login URL settings section
function custom_login_url_section_callback() {
echo '<p>' . esc_html__( 'Enter the custom login URL that you want to use instead of the default WordPress login URL.', 'custom-login-url' ) . '</p>';
}

// Add the form field for the custom login URL option
function custom_login_url_field_callback() {
$option_value = get_option( 'custom_login_url' );
?>
<input type="text" id="custom_login_url" name="custom_login_url" value="<?php echo esc_attr( $option_value ); ?>" size="50" />
<?php
}
// Replace the default login url with the custom login url
add_filter( 'login_url', 'custom_login_url_filter' );

function custom_login_url_filter( $login_url ) {
$custom_login_url = get_option( 'custom_login_url' );
if ( ! empty( $custom_login_url ) ) {
    return esc_url_raw( $custom_login_url );
}

return $login_url;

}

// Redirect users who access the default login or admin url to the custom login url
add_action( 'init', 'redirect_default_login_and_admin_urls' );

function redirect_default_login_and_admin_urls() {
$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Check if the current url matches the default WordPress login url
if (strpos($current_url, 'wp-login.php') !== false) {
wp_redirect( home_url('/custom-login-url/') );
exit;
}

// Check if the current url matches the default WordPress admin url
if (strpos($current_url, 'wp-admin') !== false) {
wp_redirect( home_url('/custom-admin-url/') );
exit;
}
}

//第三部分：隐藏登录地址并将其导向 404 页面

// Hide the login page and redirect to 404 page
add_action( 'template_redirect', 'hide_login_page' );

function hide_login_page() {
    global $wp;

    // Get the custom login URL option
    $custom_login_url = get_option( 'custom_login_url' );

    // If the custom login URL option is empty, return
    if ( empty( $custom_login_url ) ) {
        return;
    }

    // Get the requested URL
    $requested_url = home_url( $wp->request );

    // Check if the requested URL matches the custom login URL
    if ( $requested_url === $custom_login_url ) {
        return;
    }

    // Check if the requested URL matches the default WordPress login URL
    if ( $requested_url === wp_login_url() ) {
        wp_redirect( home_url( '/404/' ) );
        exit;
    }
}

//第四部分：配置社交账号登录功能的 API 信息

<?php
// Social login settings
function my_wp_plugin_social_login_settings() {
    add_settings_section(
        'my_wp_plugin_social_login_section',
        __('Social Login Settings', 'my-wp-plugin'),
        '',
        'my_wp_plugin_settings'
    );

    add_settings_field(
        'my_wp_plugin_social_login_google',
        __('Google', 'my-wp-plugin'),
        'my_wp_plugin_social_login_google_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_social_login_section'
    );

    add_settings_field(
        'my_wp_plugin_social_login_microsoft',
        __('Microsoft', 'my-wp-plugin'),
        'my_wp_plugin_social_login_microsoft_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_social_login_section'
    );

    add_settings_field(
        'my_wp_plugin_social_login_tiktok',
        __('Tiktok', 'my-wp-plugin'),
        'my_wp_plugin_social_login_tiktok_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_social_login_section'
    );

    add_settings_field(
        'my_wp_plugin_social_login_twitter',
        __('Twitter', 'my-wp-plugin'),
        'my_wp_plugin_social_login_twitter_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_social_login_section'
    );

    add_settings_field(
        'my_wp_plugin_social_login_facebook',
        __('Facebook', 'my-wp-plugin'),
        'my_wp_plugin_social_login_facebook_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_social_login_section'
    );

    register_setting('my_wp_plugin_settings', 'my_wp_plugin_social_login_google');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_social_login_microsoft');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_social_login_tiktok');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_social_login_twitter');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_social_login_facebook');
}

// Google
function my_wp_plugin_social_login_google_callback() {
    $google_client_id = get_option('my_wp_plugin_social_login_google')['client_id'] ?? '';
    $google_client_secret = get_option('my_wp_plugin_social_login_google')['client_secret'] ?? '';
    ?>
    <div class="social-login-provider">
        <label for="my_wp_plugin_social_login_google_client_id"><?php _e('Client ID:', 'my-wp-plugin'); ?></label>
        <input type="text" id="my_wp_plugin_social_login_google_client_id" name="my_wp_plugin_social_login_google[client_id]" value="<?php echo $google_client_id; ?>">
        <label for="my_wp_plugin_social_login_google_client_secret"><?php _e('Client Secret:', 'my-wp-plugin'); ?></label>
        <input type="text" id="my_wp_plugin_social_login_google_client_secret" name="my_wp_plugin_social_login_google[client_secret]" value="<?php echo $google_client_secret; ?>">
    </div>
    <?php
}

// Microsoft
function my_wp_plugin_social_login_microsoft_callback() {
    $microsoft_client_id = get_option('my_wp_plugin_social_login_microsoft')['client_id'] ?? '';
    $microsoft_client_secret = get_option('my_wp_plugin_social_login_microsoft')['client_secret'] ?? '';
    ?>
    <div class="social-login-provider">
        <label for="my_wp_plugin_social_login_microsoft_client_id"><?php esc_html_e('Client ID', 'my-wp-plugin'); ?></label>
            <input type="text" id="my_wp_plugin_social_login_microsoft_client_id" name="my_wp_plugin_social_login_microsoft[client_id]" value="<?php echo esc_attr($microsoft_client_id); ?>">
    </div>
    <div class="social-login-provider">
    <label for="my_wp_plugin_social_login_microsoft_client_secret"><?php esc_html_e('Client Secret', 'my-wp-plugin'); ?></label>
    <input type="text" id="my_wp_plugin_social_login_microsoft_client_secret" name="my_wp_plugin_social_login_microsoft[client_secret]" value="<?php echo esc_attr($microsoft_client_secret); ?>">
</div>
<?php
}
add_action('my_wp_plugin_social_login_microsoft', 'my_wp_plugin_social_login_microsoft_callback');

//my_wp_plugin_social_login_settings(): 这个函数添加了一个名为"Social Login Settings"的设置节，并注册了5个不同的设置字段，这些字段是从其他5个函数调用的回调函数中获取的。每个回调函数在其对应的设置字段中显示一些 HTML 元素，以便用户可以输入和保存相关的 API 信息。

//my_wp_plugin_social_login_google_callback(): 这个函数显示了一个 label 元素和一个输入框元素，用于输入和保存 Google API 的客户端 ID 和客户端秘钥。

//my_wp_plugin_social_login_microsoft_callback(): 这个函数类似于 my_wp_plugin_social_login_google_callback() 函数，但是用于 Microsoft API 的客户端 ID 和客户端秘钥。

//add_action('my_wp_plugin_social_login_microsoft', 'my_wp_plugin_social_login_microsoft_callback'): 这个函数调用了 my_wp_plugin_social_login_microsoft_callback() 函数，并将其添加到 WordPress 的行为队列中，以便在需要时调用它。


//第五部分：配置 SMTP 邮件服务，并提供测试和衔接 WordPress 邮件信息推送功能

//1. 添加一个名为 my_wp_plugin_smtp_settings 的函数，用于在 WordPress 设置页面添加 SMTP 邮件服务的配置选项。

//2. 在 my_wp_plugin_smtp_settings 函数中添加一个名为 my_wp_plugin_smtp_server_callback 的回调函数，用于在设置页面中显示 SMTP 服务器的主机名和端口号配置选项。

//3. 在 my_wp_plugin_smtp_settings 函数中添加一个名为 my_wp_plugin_smtp_authentication_callback 的回调函数，用于在设置页面中显示 SMTP 身份验证的用户名和密码配置选项。

//4. 在 my_wp_plugin_smtp_settings 函数中添加一个名为 my_wp_plugin_smtp_test_callback 的回调函数，用于提供 SMTP 邮件服务的测试按钮。

//5.添加一个名为 my_wp_plugin_wp_mail_smtp 的函数，用于衔接 WordPress 的 wp_mail 函数和 SMTP 邮件服务。

<?php
// SMTP settings
// Register SMTP settings and options
function my_wp_plugin_smtp_settings() {
    add_settings_section(
        'my_wp_plugin_smtp_section',
        __('SMTP Settings', 'my-wp-plugin'),
        '',
        'my_wp_plugin_settings'
    );

    add_settings_field(
        'my_wp_plugin_smtp_server',
        __('SMTP Server', 'my-wp-plugin'),
        'my_wp_plugin_smtp_server_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    add_settings_field(
        'my_wp_plugin_smtp_port',
        __('SMTP Port', 'my-wp-plugin'),
        'my_wp_plugin_smtp_port_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    add_settings_field(
        'my_wp_plugin_smtp_encryption',
        __('Encryption', 'my-wp-plugin'),
        'my_wp_plugin_smtp_encryption_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    add_settings_field(
        'my_wp_plugin_smtp_authentication',
        __('Authentication', 'my-wp-plugin'),
        'my_wp_plugin_smtp_authentication_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    add_settings_field(
        'my_wp_plugin_smtp_from_name',
        __('From Name', 'my-wp-plugin'),
        'my_wp_plugin_smtp_from_name_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    add_settings_field(
        'my_wp_plugin_smtp_from_address',
        __('From Address', 'my-wp-plugin'),
        'my_wp_plugin_smtp_from_address_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    add_settings_field(
        'my_wp_plugin_smtp_test_email',
        __('Test Email', 'my-wp-plugin'),
        'my_wp_plugin_smtp_test_email_callback',
        'my_wp_plugin_settings',
        'my_wp_plugin_smtp_section'
    );

    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_server');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_port');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_encryption');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_authentication');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_username');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_password');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_from_name');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_from_address');
    register_setting('my_wp_plugin_settings', 'my_wp_plugin_smtp_test_email');
}

// SMTP server
function my_wp_plugin_smtp_server_callback() {
    $smtp_server = get_option('my_wp_plugin_smtp_server');
    $smtp_host = isset($smtp_server['host']) ? $smtp_server['host'] : '';
    $smtp_port = isset($smtp_server['port']) ? $smtp_server['port'] : '';
    ?>
    <div class="smtp-server">
        <label for="my_wp_plugin_smtp_host"><?php _e('Host:', 'my-wp-plugin'); ?></label>
        <input type="text" id="my_wp_plugin_smtp_host" name="my_wp_plugin_smtp_server[host]" value="<?php echo $smtp_host; ?>">
        <label for="my_wp_plugin_smtp_port"><?php _e('Port:', 'my-wp-plugin'); ?></label>
        <input type="text" id="my_wp_plugin_smtp_port" name="my_wp_plugin_smtp_server[port]" value="<?php echo $smtp_port; ?>">
    </div>
    <?php
}

// SMTP authentication
function my_wp_plugin_smtp_authentication_callback() {
    $smtp_authentication = get_option('my_wp_plugin_smtp_authentication');
    $smtp_username = isset($smtp_authentication['username']) ? $smtp_authentication['password'] : '';
?>
<div class="smtp-authentication">
<label for="my_wp_plugin_smtp_authentication_username"><?php esc_html_e('Username', 'my-wp-plugin'); ?></label>
<input type="text" id="my_wp_plugin_smtp_authentication_username" name="my_wp_plugin_smtp_authentication[username]" value="<?php echo esc_attr($smtp_username); ?>">
<label for="my_wp_plugin_smtp_authentication_password"><?php esc_html_e('Password', 'my-wp-plugin'); ?></label>
<input type="password" id="my_wp_plugin_smtp_authentication_password" name="my_wp_plugin_smtp_authentication[password]" value="<?php echo esc_attr($smtp_password); ?>">
</div>
<?php
}

// SMTP settings
function my_wp_plugin_smtp_settings_callback() {
$smtp_settings = get_option('my_wp_plugin_smtp_settings');
$smtp_host = isset($smtp_settings['host']) ? $smtp_settings['host'] : '';
$smtp_port = isset($smtp_settings['port']) ? $smtp_settings['port'] : '';
$smtp_encryption = isset($smtp_settings['encryption']) ? $smtp_settings['encryption'] : '';
?>
<div class="smtp-settings">
<label for="my_wp_plugin_smtp_settings_host"><?php esc_html_e('Host', 'my-wp-plugin'); ?></label>
<input type="text" id="my_wp_plugin_smtp_settings_host" name="my_wp_plugin_smtp_settings[host]" value="<?php echo esc_attr($smtp_host); ?>">
<label for="my_wp_plugin_smtp_settings_port"><?php esc_html_e('Port', 'my-wp-plugin'); ?></label>
<input type="text" id="my_wp_plugin_smtp_settings_port" name="my_wp_plugin_smtp_settings[port]" value="<?php echo esc_attr($smtp_port); ?>">
<label for="my_wp_plugin_smtp_settings_encryption"><?php esc_html_e('Encryption', 'my-wp-plugin'); ?></label>
<select id="my_wp_plugin_smtp_settings_encryption" name="my_wp_plugin_smtp_settings[encryption]">
<option value="" <?php selected($smtp_encryption, ''); ?>><?php esc_html_e('None', 'my-wp-plugin'); ?></option>
<option value="ssl" <?php selected($smtp_encryption, 'ssl'); ?>><?php esc_html_e('SSL', 'my-wp-plugin'); ?></option>
<option value="tls" <?php selected($smtp_encryption, 'tls'); ?>><?php esc_html_e('TLS', 'my-wp-plugin'); ?></option>
</select>
</div>
<?php
}

// SMTP test email
function my_wp_plugin_smtp_test_email_callback() {
?>
<div class="smtp-test-email">
<p><?php esc_html_e('Enter an email address to send a test email.', 'my-wp-plugin'); ?></p>
<input type="email" id="my_wp_plugin_smtp_test_email_address" name="my_wp_plugin_smtp_test_email_address" value="">
<button id="my_wp_plugin_smtp_test_email_button" class="button"><?php esc_html_e('Send Test Email', 'my-wp-plugin'); ?></button>
<p id="my_wp_plugin_smtp_test_email_result"></p>
</div>
<?php
}

// Register SMTP settings and options
function my_wp_plugin_smtp_settings() {
add_settings_section(
'my_wp_plugin_smtp_section',
__('SMTP Settings', 'my-wp-plugin'),
'',
'my_wp_plugin_settings'
);

//注：上述代码中找不到my_wp_plugin_smtp_test_email_callback()函数的定义，该函数似乎应该用于测试 SMTP 设置并在测试电子邮件发送后提供反馈。所以这个函数可能在代码的其他部分定义。

//第六部分：管理用户上传的自定义头像

/**
 * Admin Class
 *
 * @package my-wordpress-plugin
 */

namespace MyWordPressPlugin\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use MyWordPressPlugin\Includes\Views\Avatar_Upload_Form;
use MyWordPressPlugin\Includes\Models\User;
use WP_Error;

/**
 * Admin class.
 */
class Admin {

    /**
     * Class constructor.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
    }

    /**
    * Sanitize the input.
     *
     * @param array $input Contains all settings fields as array keys.
     * @return array
     */
    public function sanitize( $input ) {
        $sanitized_input = array();

        // Sanitize custom_avatar_option field.
        if ( isset( $input['custom_avatar_option'] ) ) {
            $sanitized_input['custom_avatar_option'] = sanitize_text_field( $input['custom_avatar_option'] );
        }

      return $sanitized_input;
    }

    /**
     * Add options page.
     */
    public function add_plugin_page() {
        add_users_page(
            'My WordPress Plugin Settings',
            'My WP Plugin',
            'manage_options',
            'my-wordpress-plugin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback.
     */
    public function create_admin_page() {
        // Set class property.
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <?php settings_errors(); ?>
            <form method="post" enctype="multipart/form-data" action="options.php">
                <?php
                    settings_fields( 'my_option_group' );
                    do_settings_sections( 'my-wordpress-plugin' );
                    submit_button();
                ?>
            </form>
            <?php
            $avatar_form = new Avatar_Upload_Form( new User() );
            echo $avatar_form->render();
            ?>
        </div>
        <?php
    }

    /**
     * Register and add settings.
     */
    public function page_init() {
        register_setting(
            'my_option_group', // Option group.
            'my_option_name', // Option name.
            array( $this, 'sanitize' ) // Sanitize callback.
        );

        add_settings_section(
            'setting_section_id', // ID.
            'Settings', // Title.
            array( $this, 'print_section_info' ), // Callback.
            'my-wordpress-plugin' // Page.
        );

        add_settings_field(
            'id_number', // ID.
            'ID Number', // Title.
            array( $this, 'id_number_callback' ), // Callback.
            'my-wordpress-plugin', // Page.
            'setting_section_id' // Section.
        );

        add_settings_field(
            'title', // ID.
            'Title', // Title.
            array( $this, 'custom_avatar_settings_callback' ), // Callback function.
                'custom_avatar_settings', // Page to add the setting to.
                'custom_avatar_section' // Section to add the setting to.
            );
        add_action( 'personal_options_update', array( $this, 'save_custom_avatar' ) );
        add_action( 'edit_user_profile_update', array( $this, 'save_custom_avatar' ) );
        }
/**
 * Callback function for custom avatar settings.
 */
public function custom_avatar_settings_callback() {
    echo '<p>' . esc_html__( 'Choose whether users can upload their own custom avatar or use the default avatar provided by the site.', 'my-textdomain' ) . '</p>';

    $option = get_option( 'custom_avatar_option', 'default' );

    $checked_default = '';
    $checked_custom = '';

    if ( 'default' === $option ) {
        $checked_default = 'checked';
    } else if ( 'custom' === $option ) {
        $checked_custom = 'checked';
    }

    echo '<label><input type="radio" name="custom_avatar_option" value="default" ' . $checked_default . ' /> ' . esc_html__( 'Use default avatar', 'my-textdomain' ) . '</label><br />';
    echo '<label><input type="radio" name="custom_avatar_option" value="custom" ' . $checked_custom . ' /> ' . esc_html__( 'Allow custom avatar upload', 'my-textdomain' ) . '</label>';
}

/**
 * Sanitize the custom avatar option before saving to database.
 *
 * @param string $input The input to sanitize.
 *
 * @return string The sanitized input.
 */
public function sanitize_custom_avatar_option( $input ) {
    $valid_options = array(
        'default',
        'custom'
    );

    if ( in_array( $input, $valid_options ) ) {
        return $input;
    } else {
        return 'default';
    }
}

/**
 * Add custom avatar upload field to user profile page.
 *
 * @param WP_User $user The user being edited.
 */
public function add_custom_avatar_field( $user ) {
    wp_nonce_field( 'my_custom_avatar_nonce', 'custom_avatar_nonce' );
    $custom_avatar = get_user_meta( $user->ID, 'custom_avatar', true );

    if ( $custom_avatar ) {
        $url = $custom_avatar;
    } else {
        $url = get_avatar_url( $user->ID );
    }

    ?>
    <h3><?php esc_html_e( 'Custom Avatar', 'my-textdomain' ); ?></h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="custom_avatar"><?php esc_html_e( 'Avatar', 'my-textdomain' ); ?></label>
            </th>
            <td>
                <img src="<?php echo esc_url( $url ); ?>" alt="<?php esc_attr_e( 'Avatar', 'my-textdomain' ); ?>" class="custom-avatar-preview" /><br />
                <input type="text" name="custom_avatar" id="custom_avatar" value="<?php echo esc_attr( $custom_avatar ); ?>" class="regular-text" /><br />
                <input type="file" name="custom_avatar" />
                <input type="button" class="button button-secondary custom-avatar-upload" value="<?php esc_attr_e( 'Upload Avatar', 'my-textdomain' ); ?>" />
                <input type="button" class="button button-secondary custom-avatar-remove" value="<?php esc_attr_e( 'Remove Avatar', 'my-textdomain' ); ?>" />
            </td>
        </tr>
    </table>
    <?php
    if ( $custom_avatar ) {
    $url = $custom_avatar;
    } else {
        $url = get_avatar_url( $user->ID );
        }
    ?>
    <h3><?php esc_html_e( 'Custom Avatar', 'my-textdomain' ); ?></h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="custom_avatar"><?php esc_html_e( 'Avatar', 'my-textdomain' ); ?></label>
            </th>
            <td>
                <img src="<?php echo esc_url( $url ); ?>" alt="<?php esc_attr_e( 'Avatar', 'my-textdomain' ); ?>" class="custom-avatar" />
                <br />
                <input type="file" id="custom_avatar" name="custom_avatar" />
            </td>
        </tr>
    </table>
    }
<?php
/**
 * Save custom avatar field on user profile page.
 *
 * @param int $user_id The ID of the user being edited.
 */
public function save_custom_avatar_field( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
return;
}
if ( isset( $_POST['custom_avatar'] ) ) {
    update_user_meta( $user_id, 'custom_avatar', sanitize_text_field( $_POST['custom_avatar'] ) );
} else {
    delete_user_meta( $user_id, 'custom_avatar' );
}
}

/**

Display custom avatar field on user profile page.
@param WP_User $user The user object being edited.
*/
public function custom_avatar_field( $user ) {
$custom_avatar = get_user_meta( $user->ID, 'custom_avatar', true );
?>
 <h3><?php _e( 'Custom Avatar', 'myplugin' ); ?></h3>
 <table class="form-table">
     <tr>
         <th><label for="custom_avatar"><?php _e( 'Avatar URL', 'myplugin' ); ?></label></th>
         <td>
             <input type="text" name="custom_avatar" id="custom_avatar" value="<?php echo esc_attr( $custom_avatar ); ?>" class="regular-text" /><br />
             <span class="description"><?php _e( 'Enter the URL of your custom avatar image.', 'myplugin' ); ?></span>
         </td>
     </tr>
 </table>
 <?php
}
/**

Enqueue scripts and styles for media uploader.
*/
public function enqueue_media_uploader() {
wp_enqueue_media();
wp_enqueue_script( 'myplugin-media-uploader', plugins_url( '/js/media-uploader.js', FILE ), array( 'jquery' ), '1.0.0', true );
}
/**

Save custom avatar field using media uploader.

@param int $user_id The ID of the user being edited.
*/
public function save_custom_avatar_field_media_uploader( $user_id ) {
if ( ! current_user_can( 'edit_user', $user_id ) ) {
return;
}

if ( isset( $_POST['custom_avatar'] ) ) {
update_user_meta( $user_id, 'custom_avatar', sanitize_text_field( $_POST['custom_avatar'] ) );
} elseif ( isset( $_POST['custom_avatar_attachment_id'] ) ) {
update_user_meta( $user_id, 'custom_avatar', wp_get_attachment_url( $_POST['custom_avatar_attachment_id'] ) );
} else {
delete_user_meta( $user_id, 'custom_avatar' );
}
}
/**

Display custom avatar field using media uploader.
@param WP_User $user The user object being edited.
*/
public function custom_avatar_field_media_uploader( $user ) {
$custom_avatar = get_user_meta( $user->ID, 'custom_avatar', true );
$custom_avatar_id = attachment_url_to_postid( $custom_avatar );
?>
 <h3><?php _e( 'Custom Avatar', 'myplugin' ); ?></h3>
 <table class="form-table">
     <tr>
         <th><label for="custom_avatar"><?php _e( 'Avatar', 'myplugin' ); ?></label></th>
         <td>
             <div class="custom-avatar-preview">
                 <?php if ( $custom_avatar ) : ?>
                     <img src="<?php echo esc_url( $custom_avatar ); ?>" alt="" style="max-width: 100%;" />
                 <?php endif; ?>
             </div>
             <input type="hidden" name="custom_avatar_attachment_id" id="custom_avatar_attachment_id" value="<?php echo esc_attr( $custom_avatar_id ); ?>" />
             <button type="button" class="button button-secondary custom_avatar_upload"><?php _e( 'Upload Avatar', 'myplugin' ); ?></button>
<button type="button" class="button custom_avatar_remove"><?php _e( 'Remove Avatar', 'myplugin' ); ?></button>
<p class="description"><?php _e( 'Upload a custom avatar for this user.', 'myplugin' ); ?></p>
</td>
</tr>

 </table>
<?php
}
/**

Enqueue scripts and styles for the custom avatar field media uploader.
*/
public function custom_avatar_field_media_uploader_scripts() {
wp_enqueue_media();
wp_enqueue_script( 'custom-avatar-field-media-uploader', plugin_dir_url( FILE ) . 'js/custom-avatar-field-media-uploader.js', array( 'jquery' ), '1.0.0', true );
}
/**

Save custom avatar field attachment ID on user profile page.
@param int $user_id The ID of the user being edited.
*/
public function save_custom_avatar_field_attachment_id( $user_id ) {
if ( isset( $_POST['custom_avatar_attachment_id'] ) ) {
update_user_meta( $user_id, 'custom_avatar', wp_get_attachment_url( absint( $_POST['custom_avatar_attachment_id'] ) ) );
}
}
/**

Remove custom avatar field attachment ID from user profile page.
@param int $user_id The ID of the user being edited.
*/
public function remove_custom_avatar_field_attachment_id( $user_id ) {
delete_user_meta( $user_id, 'custom_avatar' );
}

//第七部分：配置验证功能的第三方 API 信息

<?php
// ...
class My_WordPress_Plugin_Admin {

    // ...

    public function custom_verification_settings_callback() {
        printf(
            '<input type="text" id="verification_api_key" name="my_wordpress_plugin_options[verification_api_key]" value="%s" />',
            isset( $this->options['verification_api_key'] ) ? esc_attr( $this->options['verification_api_key'] ) : ''
        );
    }

    public function custom_verification_settings() {
        add_settings_section(
            'custom_verification_section', // ID.
            'Custom Verification', // Title.
            array( $this, 'custom_verification_section_callback' ), // Callback.
            'custom_verification_settings' // Page.
        );

        add_settings_field(
            'verification_api_key', // ID.
            'Verification API Key', // Title.
            array( $this, 'custom_verification_settings_callback' ), // Callback.
            'custom_verification_settings', // Page.
            'custom_verification_section' // Section.
        );

        register_setting(
            'my_wordpress_plugin_options', // Option group.
            'my_wordpress_plugin_options', // Option name.
            array( $this, 'sanitize' ) // Sanitize callback.
        );
    }

    public function custom_verification_section_callback() {
        echo 'Configure verification API information here.';
    }

    public function add_admin_menu() {
        add_options_page(
            'My WordPress Plugin', // Page title.
            'My WordPress Plugin', // Menu title.
            'manage_options', // Capability.
            'my_wordpress_plugin', // Menu slug.
            array( $this, 'admin_page' ) // Callback.
        );
    }

    public function admin_page() {
        ?>
        <div class="wrap">
            <h2>My WordPress Plugin</h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'my_wordpress_plugin_options' ); ?>
                <?php do_settings_sections( 'custom_verification_settings' ); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

$my_wordpress_plugin_admin = new My_WordPress_Plugin_Admin();
add_action( 'admin_menu', array( $my_wordpress_plugin_admin, 'add_admin_menu' ) );
add_action( 'admin_init', array( $my_wordpress_plugin_admin, 'custom_verification_settings' ) );

<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://yourwebsite.com
 * @since      1.0.0
 *
 * @package    My_WordPress_Plugin
 * @subpackage My_WordPress_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the
 * admin-specific functionality of the plugin.
 *
 * @package    My_WordPress_Plugin
 * @subpackage My_WordPress_Plugin/admin
 */
class My_WordPress_Plugin_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/my-wordpress-plugin-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/my-wordpress-plugin-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Register the menu page for the admin area.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        add_menu_page(
            __( 'My WordPress Plugin', $this->plugin_name ),
            __( 'My WP Plugin', $this->plugin_name ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-star-filled',
            100
        );

    }

    /**
     * Display the menu page for the admin area.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {

        include_once 'partials/my-wordpress-plugin-admin-display.php';

    }

}
// enqueue admin scripts
function my_plugin_admin_scripts() {
    wp_enqueue_script( 'my-plugin-admin-script', plugins_url( '/assets/js/admin.js', MY_PLUGIN_FILE ), array( 'jquery' ), MY_PLUGIN_VERSION );
    wp_enqueue_style( 'my-plugin-admin-style', plugins_url( '/assets/css/admin.css', MY_PLUGIN_FILE ), array(), MY_PLUGIN_VERSION );
}
add_action( 'admin_enqueue_scripts', 'my_plugin_admin_scripts' );

// add settings link to plugin page
function my_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=my-plugin-settings">' . __( 'Settings', 'my-plugin' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}
$plugin_settings_link = plugin_basename( MY_PLUGIN_FILE );
add_filter( "plugin_action_links_$plugin_settings_link", 'my_plugin_add_settings_link' );

// register settings
function my_plugin_register_settings() {
    register_setting( 'my-plugin-settings-group', 'my_plugin_option_1' );
    register_setting( 'my-plugin-settings-group', 'my_plugin_option_2' );
}
add_action( 'admin_init', 'my_plugin_register_settings' );

// add menu page
function my_plugin_menu_page() {
    add_options_page( __( 'My Plugin Settings', 'my-plugin' ), __( 'My Plugin', 'my-plugin' ), 'manage_options', 'my-plugin-settings', 'my_plugin_settings_page' );
}
add_action( 'admin_menu', 'my_plugin_menu_page' );

// settings page
function my_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
        <form action="options.php" method="post">
            <?php settings_fields( 'my-plugin-settings-group' ); ?>
            <?php do_settings_sections( 'my-plugin-settings-group' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e( 'Option 1', 'my-plugin' ); ?></th>
                    <td><input type="text" name="my_plugin_option_1" value="<?php echo esc_attr( get_option( 'my_plugin_option_1' ) ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e( 'Option 2', 'my-plugin' ); ?></th>
                    <td><input type="text" name="my_plugin_option_2" value="<?php echo esc_attr( get_option( 'my_plugin_option_2' ) ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
/**
 * Render the settings page for this plugin.
 */
function my_plugin_settings_page() {
  // Retrieve plugin settings from the database.
  $options = get_option( 'my_plugin_options' );

  // Render the settings page.
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form method="post" action="options.php">
      <?php
        // Render the nonce field for security.
        settings_fields( 'my_plugin_options' );

        // Render the settings section and fields.
        do_settings_sections( 'my_plugin_settings' );

        // Render the submit button.
        submit_button();
      ?>
    </form>
  </div>
  <?php
}

/**
 * Register the settings for this plugin.
 */
function my_plugin_register_settings() {
  // Register a setting for the API key.
  register_setting(
    'my_plugin_options',
    'my_plugin_options',
    array(
      'type' => 'array',
      'sanitize_callback' => 'my_plugin_sanitize_options',
      'default' => array(
        'api_key' => '',
      ),
    )
  );

  // Add a section for the plugin settings.
  add_settings_section(
    'my_plugin_settings_section',
    esc_html__( 'Plugin Settings', 'my-plugin' ),
    'my_plugin_settings_section_callback',
    'my_plugin_settings'
  );

  // Add a field for the API key.
  add_settings_field(
    'my_plugin_api_key',
    esc_html__( 'API Key', 'my-plugin' ),
    'my_plugin_api_key_callback',
    'my_plugin_settings',
    'my_plugin_settings_section'
  );
}

/**
 * Sanitize the plugin options.
 *
 * @param array $options The options to sanitize.
 * @return array The sanitized options.
 */
function my_plugin_sanitize_options( $options ) {
  // Sanitize the API key.
  if ( isset( $options['api_key'] ) ) {
    $options['api_key'] = sanitize_text_field( $options['api_key'] );
  }

  return $options;
}

/**
 * Render the plugin settings section.
 */
function my_plugin_settings_section_callback() {
  // This function intentionally left blank.
}

/**
 * Render the API key field.
 */
function my_plugin_api_key_callback() {
  // Retrieve plugin settings from the database.
  $options = get_option( 'my_plugin_options' );

  // Render the API key field.
  ?>
  <input type="text" name="my_plugin_options[api_key]" value="<?php echo esc_attr( $options['api_key'] ); ?>" class="regular-text">
  <?php
}
