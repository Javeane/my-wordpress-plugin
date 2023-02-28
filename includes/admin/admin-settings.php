<?php
/**
 * Plugin Settings Page
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

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Add settings page to WordPress menu
 */
function my_wordpress_plugin_settings_page() {
    add_options_page(
        __( 'My WordPress Plugin Settings', 'my-wordpress-plugin' ),
        __( 'My WP Plugin', 'my-wordpress-plugin' ),
        'manage_options',
        'my-wordpress-plugin-settings',
        'my_wordpress_plugin_settings_page_html'
    );

    if ($current_tab == 'smtp') {
        /**
         * Handle SMTP settings form submission
         */
    if (isset($_POST['myplugin_smtp_settings_submit'])) {
        // Update the settings
        update_option('myplugin_smtp_settings', $_POST);

       // Test the connection
       $result = myplugin_test_smtp_connection($_POST);
       if ($result) {
        // Connection successful
        add_settings_error('myplugin_smtp_settings', 'smtp_test_success', __('SMTP connection successful', 'myplugin'), 'updated');
    } else {
        // Connection failed
        add_settings_error('myplugin_smtp_settings', 'smtp_test_failed', __('SMTP connection failed', 'myplugin'), 'error');
    }
}

/**
 * Test SMTP connection callback function
 */
function myplugin_smtp_test_callback()
{
    // Get the SMTP settings from the database
    $settings = get_option('myplugin_smtp_settings');

    // Test the connection
    $result = myplugin_test_smtp_connection($settings);
    if ($result) {
        // Connection successful
        wp_send_json_success(__('SMTP connection successful', 'myplugin'));
    } else {
        // Connection failed
        wp_send_json_error(__('SMTP connection failed', 'myplugin'));
    }
}
    }

}
add_action( 'admin_menu', 'my_wordpress_plugin_settings_page' );

/**
 * Define settings page HTML
 */
function my_wordpress_plugin_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'my_wordpress_plugin_settings' );
            do_settings_sections( 'my-wordpress-plugin-settings' );
            submit_button( __( 'Save Settings', 'my-wordpress-plugin' ) );
            ?>
        </form>
    </div>
    <?php
}
//第二部分

// Add a settings link to the plugin entry in the plugins list
function wp_books_add_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=wp-books-settings">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wp_books_add_settings_link');

// Register settings
function wp_books_register_settings()
{
    add_option('wp_books_api_key', '');
    register_setting('wp_books_settings_group', 'wp_books_api_key');
}
add_action('admin_init', 'wp_books_register_settings');

// Add settings page to the admin menu
function wp_books_add_settings_page()
{
    add_options_page(
        'WP Books Settings',
        'WP Books',
        'manage_options',
        'wp-books-settings',
        'wp_books_render_settings_page'
    );
}
add_action('admin_menu', 'wp_books_add_settings_page');

// Render the settings page
function wp_books_render_settings_page()
{
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('wp_books_settings_group'); ?>
            <?php do_settings_sections('wp_books_settings_group'); ?>
            <?php submit_button(__('Save Settings', 'wp-books')); ?>
        </form>
    </div>
<?php
}

// Add basic information form to settings page
function wp_books_render_basic_info()
{
    ?>
    <h2><?php esc_html_e('Basic Information', 'wp-books'); ?></h2>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">
                <label for="wp_books_book_title"><?php esc_html_e('Book Title', 'wp-books'); ?></label>
            </th>
            <td>
                <input type="text" id="wp_books_book_title" name="wp_books_book_title" value="<?php echo esc_attr(get_option('wp_books_book_title')); ?>" class="regular-text" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="wp_books_author"><?php esc_html_e('Author', 'wp-books'); ?></label>
            </th>
            <td>
                <input type="text" id="wp_books_author" name="wp_books_author" value="<?php echo esc_attr(get_option('wp_books_author')); ?>" class="regular-text" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <label for="wp_books_publisher"><?php esc_html_e('Publisher', 'wp-books'); ?></label>
            </th>
            <td>
                <input type="text" id="wp_books_publisher" name="wp_books_publisher" value="<?php echo esc_attr(get_option('wp_books_publisher')); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}

// Register basic information form
function wpdocs_register_basic_info_form() {
    add_settings_section(
        'basic_info_section',
        __( 'Basic Information', 'textdomain' ),
        'wpdocs_basic_info_section_cb',
        'wpdocs_basic_info'
    );

    add_settings_field(
        'plugin_name',
        __( 'Plugin Name', 'textdomain' ),
        'wpdocs_plugin_name_cb',
        'wpdocs_basic_info',
        'basic_info_section'
    );

    add_settings_field(
        'author_name',
        __( 'Author Name', 'textdomain' ),
        'wpdocs_author_name_cb',
        'wpdocs_basic_info',
        'basic_info_section'
    );

    add_settings_field(
        'plugin_version',
        __( 'Version', 'textdomain' ),
        'wpdocs_plugin_version_cb',
        'wpdocs_basic_info',
        'basic_info_section'
    );

    register_setting( 'wpdocs_basic_info', 'wpdocs_plugin_name' );
    register_setting( 'wpdocs_basic_info', 'wpdocs_author_name' );
    register_setting( 'wpdocs_basic_info', 'wpdocs_plugin_version' );
}

function wpdocs_basic_info_section_cb() {
    echo '<p>' . __( 'Please fill out the basic information for your plugin', 'textdomain' ) . '</p>';
}

function wpdocs_plugin_name_cb() {
    $plugin_name = get_option( 'wpdocs_plugin_name' );
    echo '<input type="text" id="plugin_name" name="wpdocs_plugin_name" value="' . esc_attr( $plugin_name ) . '" />';
}

function wpdocs_author_name_cb() {
    $author_name = get_option( 'wpdocs_author_name' );
    echo '<input type="text" id="author_name" name="wpdocs_author_name" value="' . esc_attr( $author_name ) . '" />';
}

function wpdocs_plugin_version_cb() {
    $plugin_version = get_option( 'wpdocs_plugin_version' );
    echo '<input type="text" id="plugin_version" name="wpdocs_plugin_version" value="' . esc_attr( $plugin_version ) . '" />';
}

function register_basic_info_form() {
  // Retrieve plugin settings from database
  $settings = get_option( 'my_plugin_settings' );
  
  // Define default values for form fields
  $defaults = array(
    'plugin_name' => '',
    'plugin_description' => '',
    'plugin_version' => '',
  );
  
  // Merge saved settings with default values
  $settings = wp_parse_args( $settings, $defaults );
  
  // Output the form
  ?>
  <form method="post" action="options.php">
    <?php
      settings_fields( 'my_plugin_settings' );
      do_settings_sections( 'my_plugin_settings' );
    ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row"><?php _e( 'Plugin Name', 'my-plugin' ); ?></th>
        <td>
          <input type="text" name="my_plugin_settings[plugin_name]" value="<?php echo esc_attr( $settings['plugin_name'] ); ?>" />
          <p class="description"><?php _e( 'The name of your plugin.', 'my-plugin' ); ?></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e( 'Plugin Description', 'my-plugin' ); ?></th>
        <td>
          <textarea name="my_plugin_settings[plugin_description]"><?php echo esc_textarea( $settings['plugin_description'] ); ?></textarea>
          <p class="description"><?php _e( 'A short description of your plugin.', 'my-plugin' ); ?></p>
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><?php _e( 'Plugin Version', 'my-plugin' ); ?></th>
        <td>
          <input type="text" name="my_plugin_settings[plugin_version]" value="<?php echo esc_attr( $settings['plugin_version'] ); ?>" />
          <p class="description"><?php _e( 'The version number of your plugin.', 'my-plugin' ); ?></p>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
  <?php
}

function my_plugin_settings_page() {
  ?>
  <div class="wrap">
    <h1><?php _e( 'My Plugin Settings', 'my-plugin' ); ?></h1>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
      <?php
        settings_fields( 'my_plugin_settings' );
        do_settings_sections( 'my_plugin_settings' );
        register_basic_info_form(); // Display the basic information form
      ?>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

//第三部分

// 添加 SMTP 服务设置表单
function myplugin_add_smtp_settings() {
  add_settings_section(
    'myplugin_smtp_section',
    'SMTP 服务设置',
    'myplugin_smtp_section_callback',
    'myplugin'
  );

  add_settings_field(
    'myplugin_smtp_host',
    'SMTP 主机',
    'myplugin_smtp_host_callback',
    'myplugin',
    'myplugin_smtp_section'
  );

  add_settings_field(
    'myplugin_smtp_port',
    'SMTP 端口',
    'myplugin_smtp_port_callback',
    'myplugin',
    'myplugin_smtp_section'
  );

  add_settings_field(
    'myplugin_smtp_username',
    'SMTP 用户名',
    'myplugin_smtp_username_callback',
    'myplugin',
    'myplugin_smtp_section'
  );

  add_settings_field(
    'myplugin_smtp_password',
    'SMTP 密码',
    'myplugin_smtp_password_callback',
    'myplugin',
    'myplugin_smtp_section'
  );

  add_settings_field(
    'myplugin_smtp_from_email',
    '发件人邮箱',
    'myplugin_smtp_from_email_callback',
    'myplugin',
    'myplugin_smtp_section'
  );

  add_settings_field(
    'myplugin_smtp_from_name',
    '发件人名称',
    'myplugin_smtp_from_name_callback',
    'myplugin',
    'myplugin_smtp_section'
  );

  register_setting(
    'myplugin_options_group',
    'myplugin_smtp_options'
  );
}

function myplugin_smtp_section_callback() {
  echo '<p>请填写 SMTP 服务的配置信息。</p>';
}

function myplugin_smtp_host_callback() {
  $options = get_option( 'myplugin_smtp_options' );
  $host = isset( $options['host'] ) ? $options['host'] : '';
  echo '<input type="text" id="myplugin_smtp_host" name="myplugin_smtp_options[host]" value="' . $host . '" />';
}

function myplugin_smtp_port_callback() {
  $options = get_option( 'myplugin_smtp_options' );
  $port = isset( $options['port'] ) ? $options['port'] : '';
  echo '<input type="text" id="myplugin_smtp_port" name="myplugin_smtp_options[port]" value="' . $port . '" />';
}

function myplugin_smtp_username_callback() {
  $options = get_option( 'myplugin_smtp_options' );
  $username = isset( $options['username'] ) ? $options['username'] : '';
  echo '<input type="text" id="myplugin_smtp_username" name="myplugin_smtp_options[username]" value="' . $username . '" />';
}

function myplugin_smtp_password_callback() {
  $options = get_option( 'myplugin_smtp_options' );
  $password = isset( $options['password'] ) ? $options['password'] : '';
  echo '<input type="password" id="myplugin_smtp_password" name="myplugin_smtp_options[password]" value="' . $password . '" />';
}

function myplugin_smtp_from_email_callback() {
  $options = get_option( 'myplugin_smtp_options' );
  $from_email = isset( $options['from_email'] ) ? $options['from_email'] : '';
  echo '<input type="email" id="myplugin_smtp_from_email" name="myplugin_smtp_options[from_email]" value="' . $from_email . '" />';
}

function myplugin_smtp_from_name_callback() {
  $options = get_option( 'myplugin_smtp_options' );
  $from_name = isset( $options['from_name'] ) ? $options['from_name'] : '';
  echo '<input type="text" id="myplugin_smtp_from_name" name="myplugin_smtp_options[from_name]" value="' . $from_name . '" />';
}

// 添加测试 SMTP 服务连接的按钮
function myplugin_add_test_smtp_button() {
  add_settings_section(
    'myplugin_test_smtp_section',
    '测试 SMTP 服务连接',
    'myplugin_test_smtp_section_callback',
    'myplugin'
  );

  add_settings_field(
    'myplugin_test_smtp_button',
    '',
    'myplugin_test_smtp_button_callback',
    'myplugin',
    'myplugin_test_smtp_section'
  );
}

function myplugin_test_smtp_section_callback() {
  echo '<p>您可以通过下面的按钮来测试 SMTP 服务是否能够成功连接。</p>';
}

function myplugin_test_smtp_button_callback() {
  echo '<button id="myplugin_test_smtp_button" class="button">测试 SMTP 连接</button>';
}

// 注册设置页面和保存设置
function myplugin_register_settings() {
  add_action( 'admin_init', 'myplugin_add_smtp_settings' );
  add_action( 'admin_init', 'myplugin_add_test_smtp_button' );
  register_setting( 'myplugin_options_group', 'myplugin_smtp_options' );
}

add_action( 'admin_menu', 'myplugin_add_settings_page' );
add_action( 'admin_init', 'myplugin_register_settings' );

function myplugin_smtp_test_callback() {

  function myplugin_smtp_test_callback() {
  if ( !current_user_can( 'manage_options' ) ) {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  $to = sanitize_email( $_POST['to'] );
  if ( !is_email( $to ) ) {
    wp_send_json_error( __( 'Please enter a valid email address.' ) );
  }
  $subject = sanitize_text_field( $_POST['subject'] );
  $message = sanitize_textarea_field( $_POST['message'] );
  $headers = array(
    'From: ' . get_option( 'myplugin_smtp_sender_name' ) . ' <' . get_option( 'myplugin_smtp_sender_email' ) . '>',
    'Content-Type: text/html; charset=UTF-8',
  );
  $smtp_options = array(
    'host'     => get_option( 'myplugin_smtp_host' ),
    'port'     => get_option( 'myplugin_smtp_port' ),
    'secure'   => get_option( 'myplugin_smtp_encryption' ),
    'auth'     => get_option( 'myplugin_smtp_authentication' ),
    'username' => get_option( 'myplugin_smtp_username' ),
    'password' => get_option( 'myplugin_smtp_password' ),
  );
  if ( !MyPluginSMTP::validate_smtp_options( $smtp_options ) ) {
    wp_send_json_error( __( 'SMTP settings are not configured properly. Please check your settings and try again.' ) );
  }
    add_filter( 'wp_mail_content_type', 'myplugin_set_html_content_type' );
    $result = wp_mail( $to, $subject, $message, $headers, array(), $smtp_options );
    remove_filter( 'wp_mail_content_type', 'myplugin_set_html_content_type' );
    if ( $result ) {
    wp_send_json_success( __( 'Email sent successfully!' ) );
    } else {
    wp_send_json_error( __( 'Email failed to send. Please check your SMTP settings and try again.' ) );
    }
    die();
}
    // Get the saved SMTP settings
    $smtp_settings = get_option('myplugin_smtp_settings');

    // Set up the PHPMailer object
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $smtp_settings['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_settings['username'];
    $mail->Password = $smtp_settings['password'];
    $mail->SMTPSecure = $smtp_settings['smtp_secure'];
    $mail->Port = $smtp_settings['port'];

    // Set the from, to, subject, and body of the test email
    $from = $smtp_settings['from_email'];
    $to = 'test@example.com';
    $subject = 'Test email';
    $body = 'This is a test email sent from the MyPlugin SMTP settings page.';

    // Set the message headers
    $mail->setFrom($from);
    $mail->addAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $body;

    // Attempt to send the test email and check for errors
    if(!$mail->send()) {
        echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }
//  function myplugin_add_settings_page() {
//    add_options_page(
//    ( 'MyPlugin SMTP Settings', 'myplugin' ),
//    ( 'MyPlugin SMTP', 'myplugin' ),
//    'manage_options',
//    'myplugin-smtp-settings',
//    'myplugin_render_smtp_settings_page'
//    );
//  }

function myplugin_add_settings_page() {
    add_options_page(
        'MyPlugin SMTP Settings', // 页面标题
        'MyPlugin SMTP', // 菜单标题
        'manage_options', // 用户权限
        'myplugin-smtp-settings', // 菜单 slug
        'myplugin_render_smtp_settings_page' // 渲染回调函数
    );
}

}

//第四部分

/**
 * Add social login API settings to plugin settings page
 */
function myplugin_add_social_login_settings() {
    add_settings_section(
        'myplugin_social_login_section',
        'Social Login API Settings',
        'myplugin_social_login_section_callback',
        'myplugin'
    );
    
    add_settings_field(
        'myplugin_social_login_api_endpoint',
        'API Endpoint URL',
        'myplugin_social_login_api_endpoint_callback',
        'myplugin',
        'myplugin_social_login_section'
    );
    
    add_settings_field(
        'myplugin_social_login_api_key',
        'API Key',
        'myplugin_social_login_api_key_callback',
        'myplugin',
        'myplugin_social_login_section'
    );
    
    register_setting('myplugin', 'myplugin_social_login_api_endpoint');
    register_setting('myplugin', 'myplugin_social_login_api_key');
}

/**
 * Render social login settings section description
 */
function myplugin_social_login_section_callback() {
    echo 'Configure the API settings for social login.';
}

/**
 * Render social login API endpoint field
 */
function myplugin_social_login_api_endpoint_callback() {
    $value = get_option('myplugin_social_login_api_endpoint');
    echo '<input type="url" name="myplugin_social_login_api_endpoint" value="' . esc_attr($value) . '" />';
}

/**
 * Render social login API key field
 */
function myplugin_social_login_api_key_callback() {
    $value = get_option('myplugin_social_login_api_key');
    echo '<input type="text" name="myplugin_social_login_api_key" value="' . esc_attr($value) . '" />';
}

/**
 * Add social login API test button to plugin settings page
 */
function myplugin_add_social_login_test_button() {
    add_settings_field(
        'myplugin_social_login_test',
        'Test Social Login API',
        'myplugin_social_login_test_callback',
        'myplugin',
        'myplugin_social_login_section'
    );
}

/**
 * Render social login API test button
 */
function myplugin_social_login_test_callback() {
    echo '<button class="button" id="myplugin-social-login-test-button">Test</button>';
}

/**
 * Handle social login API test request
 */
function myplugin_social_login_test_request() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    $provider = isset($_POST['provider']) ? sanitize_text_field($_POST['provider']) : '';

    // Verify nonce
    if (!wp_verify_nonce($nonce, 'myplugin_social_login_test') || empty($provider)) {
        wp_send_json_error('Invalid request.');
    }

    // Get API endpoint and key
    $endpoint = get_option('myplugin_social_login_api_endpoint');
    $key = get_option('myplugin_social_login_api_key');

    if (empty($endpoint) || empty($key)) {
        wp_send_json_error('Please enter both the API endpoint URL and API key.');
    }

    // Send test request
    $url = $endpoint . '/test';
    $response = wp_remote_get($url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $key
        ),
        'timeout' => 10
    ));

    if (is_wp_error($response)) {
        wp_send_json_error('Error: ' . $response->get_error_message());
    } else {
        wp_send_json_success($response['body']);
    }
}

/**
 * Enqueue social login API test script
 */
function myplugin_social_login_test_script() {
    wp_enqueue_script( 'myplugin-social-login-test', plugin_dir_url( __FILE__ ) . 'js/social-login-test.js', array( 'jquery' ), MYPLUGIN_VERSION, true );
    wp_localize_script( 'myplugin-social-login-test', 'mypluginSocialLoginTest', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'myplugin_social_login_test' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'myplugin_social_login_test_script' );

jQuery( document ).ready( function( $ ) { // line 656
    $( '#myplugin-social-login-test-button' ).on( 'click', function() {
        var data = {
            action: 'myplugin_social_login_test',
            nonce: mypluginSocialLoginTest.nonce
        };
        $.post( mypluginSocialLoginTest.ajaxUrl, data, function( response ) {
            $( '#myplugin-social-login-test-result' ).html( response );
        } );
    } );
} );

function myplugin_admin_enqueue_scripts() {
    wp_enqueue_script(
        'myplugin-admin-script',
        plugins_url( '/js/admin-script.js', dirname( __FILE__ ) ),
        array( 'jquery' ),
        MYPLUGIN_VERSION,
        true
    );
    wp_localize_script( 'myplugin-admin-script', 'mypluginSocialLoginTest', array(
        'nonce' => wp_create_nonce( 'myplugin_social_login_test' ),
        'ajaxUrl' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'myplugin_admin_enqueue_scripts' );

add_action( 'wp_ajax_myplugin_social_login_test', 'myplugin_social_login_test_request' );


//第五部分

/**
 * Add captcha API settings to plugin settings page
 */
function myplugin_add_captcha_api_settings() {
    add_settings_section(
        'myplugin_captcha_api_section',
        'Captcha API Settings',
        'myplugin_captcha_api_section_callback',
        'myplugin'
    );
    
    add_settings_field(
        'myplugin_captcha_api_endpoint',
        'API Endpoint URL',
        'myplugin_captcha_api_endpoint_callback',
        'myplugin',
        'myplugin_captcha_api_section'
    );
    
    add_settings_field(
        'myplugin_captcha_api_key',
        'API Key',
        'myplugin_captcha_api_key_callback',
        'myplugin',
        'myplugin_captcha_api_section'
    );
    
    register_setting('myplugin', 'myplugin_captcha_api_endpoint');
    register_setting('myplugin', 'myplugin_captcha_api_key');
}

/**
 * Render captcha API settings section description
 */
function myplugin_captcha_api_section_callback() {
    echo 'Configure the API settings for captcha verification.';
}

/**
 * Render captcha API endpoint field
 */
function myplugin_captcha_api_endpoint_callback() {
    $value = get_option('myplugin_captcha_api_endpoint');
    echo '<input type="url" name="myplugin_captcha_api_endpoint" value="' . esc_attr($value) . '" />';
}

/**
 * Render captcha API key field
 */
function myplugin_captcha_api_key_callback() {
    $value = get_option('myplugin_captcha_api_key');
    echo '<input type="text" name="myplugin_captcha_api_key" value="' . esc_attr($value) . '" />';
}

/**
 * Add captcha API test button to plugin settings page
 */
function myplugin_add_captcha_test_button() {
    add_settings_field(
        'myplugin_captcha_test',
        'Test Captcha API',
        'myplugin_captcha_test_callback',
        'myplugin',
        'myplugin_captcha_api_section'
    );
}

/**
 * Render captcha API test button
 */
function myplugin_captcha_test_callback() {
    echo '<button class="button" id="myplugin-captcha-test">Test</button>';
}

/**
 * Handle captcha API test request
 */
function myplugin_captcha_test_request() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    $site_key = isset($_POST['site_key']) ? sanitize_text_field($_POST['site_key']) : '';
    $secret_key = isset($_POST['secret_key']) ? sanitize_text_field($_POST['secret_key']) : '';
    $response = isset($_POST['response']) ? sanitize_text_field($_POST['response']) : '';

    // Verify nonce
    if (!wp_verify_nonce($nonce, 'myplugin_captcha_test') || empty($site_key) || empty($secret_key) || empty($response)) {
        wp_send_json_error('Invalid request.');
    }

    // Get API endpoint
    $endpoint = get_option('myplugin_captcha_api_endpoint');

    if (empty($endpoint)) {
        wp_send_json_error('Please enter the API endpoint URL.');
    }

    // Send test request
    $url = $endpoint . '/verify';
    $args = array(
        'body' => array(
            'site_key' => $site_key,
            'secret_key' => $secret_key,
            'response' => $response
        ),
        'timeout' => 10
    );
    $response = wp
// Send test request
$url = $endpoint . '/test';
$response = wp_remote_get($url, array(
'headers' => array(
'Authorization' => 'Bearer ' . $key
),
'timeout' => 10
));
if (is_wp_error($response)) {
    wp_send_json_error('Error: ' . $response->get_error_message());
} else {
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code == 200) {
        wp_send_json_success('API test successful.');
    } else {
        wp_send_json_error('Error: API test failed.');
    }
}
/**

Add captcha API settings to plugin settings page
*/
function myplugin_add_captcha_settings() {
add_settings_section(
'myplugin_captcha_section',
'Captcha API Settings',
'myplugin_captcha_section_callback',
'myplugin'
);

add_settings_field(
'myplugin_captcha_api_endpoint',
'API Endpoint URL',
'myplugin_captcha_api_endpoint_callback',
'myplugin',
'myplugin_captcha_section'
);

add_settings_field(
'myplugin_captcha_api_key',
'API Key',
'myplugin_captcha_api_key_callback',
'myplugin',
'myplugin_captcha_section'
);

register_setting('myplugin', 'myplugin_captcha_api_endpoint');
register_setting('myplugin', 'myplugin_captcha_api_key');
}

/**

Render captcha settings section description
*/
function myplugin_captcha_section_callback() {
echo 'Configure the API settings for captcha.';
}
/**

Render captcha API endpoint field
*/
function myplugin_captcha_api_endpoint_callback() {
$value = get_option('myplugin_captcha_api_endpoint');
echo '<input type="url" name="myplugin_captcha_api_endpoint" value="' . esc_attr($value) . '" />';
}
/**

Render captcha API key field
*/
function myplugin_captcha_api_key_callback() {
$value = get_option('myplugin_captcha_api_key');
echo '<input type="text" name="myplugin_captcha_api_key" value="' . esc_attr($value) . '" />';
}
/**

Add captcha API test button to plugin settings page
*/
function myplugin_add_captcha_test_button() {
add_settings_field(
'myplugin_captcha_test',
'Test Captcha API',
'myplugin_captcha_test_callback',
'myplugin',
'myplugin_captcha_section'
);
}
/**

Render captcha API test button
*/
function myplugin_captcha_test_callback() {
echo '<button class="button" id="myplugin-captcha-test">Test</button>';
}
/**

Handle captcha API test request
*/
function myplugin_captcha_test_request() {
$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
$secret_key = isset($_POST['secret_key']) ? sanitize_text_field($_POST['secret_key']) : '';

// Verify nonce
if (!wp_verify_nonce($nonce, 'myplugin_captcha_test') || empty($secret_key)) {
wp_send_json_error('Invalid request.');
}

// Get API endpoint
$endpoint = get_option('myplugin_captcha_api_endpoint');
if (empty($endpoint)) {
wp_send_json_error('Please enter the API endpoint URL.');
}

// Send test request
$url = $endpoint . '/test';

// Add query parameters
$params = array(
'secret' => $key,
'response' => '1',
);

$url = add_query_arg($params, $url);

// Send test request
$response = wp_remote_get($url, array(
'timeout' => 10
));

if (is_wp_error($response)) {
wp_send_json_error('Error: ' . $response->get_error_message());
} else {
$body = json_decode($response['body'], true);
if ($body['success']) {
wp_send_json_success('Success!');
} else {
wp_send_json_error('Verification failed.');
}
}

//第六部分

<?php
// Save and update plugin settings
if (isset($_POST['my_plugin_save_settings'])) {
  $my_plugin_settings['smtp_host'] = sanitize_text_field($_POST['my_plugin_smtp_host']);
  $my_plugin_settings['smtp_port'] = sanitize_text_field($_POST['my_plugin_smtp_port']);
  $my_plugin_settings['smtp_username'] = sanitize_text_field($_POST['my_plugin_smtp_username']);
  $my_plugin_settings['smtp_password'] = sanitize_text_field($_POST['my_plugin_smtp_password']);
  $my_plugin_settings['smtp_encryption'] = sanitize_text_field($_POST['my_plugin_smtp_encryption']);
  $my_plugin_settings['social_login'] = sanitize_text_field($_POST['my_plugin_social_login']);
  $my_plugin_settings['google_client_id'] = sanitize_text_field($_POST['my_plugin_google_client_id']);
  $my_plugin_settings['google_client_secret'] = sanitize_text_field($_POST['my_plugin_google_client_secret']);
  $my_plugin_settings['microsoft_client_id'] = sanitize_text_field($_POST['my_plugin_microsoft_client_id']);
  $my_plugin_settings['microsoft_client_secret'] = sanitize_text_field($_POST['my_plugin_microsoft_client_secret']);
  $my_plugin_settings['tiktok_client_id'] = sanitize_text_field($_POST['my_plugin_tiktok_client_id']);
  $my_plugin_settings['tiktok_client_secret'] = sanitize_text_field($_POST['my_plugin_tiktok_client_secret']);
  $my_plugin_settings['twitter_api_key'] = sanitize_text_field($_POST['my_plugin_twitter_api_key']);
  $my_plugin_settings['twitter_api_secret'] = sanitize_text_field($_POST['my_plugin_twitter_api_secret']);
  $my_plugin_settings['facebook_app_id'] = sanitize_text_field($_POST['my_plugin_facebook_app_id']);
  $my_plugin_settings['facebook_app_secret'] = sanitize_text_field($_POST['my_plugin_facebook_app_secret']);
  $my_plugin_settings['captcha_type'] = sanitize_text_field($_POST['my_plugin_captcha_type']);
  $my_plugin_settings['captcha_api_key'] = sanitize_text_field($_POST['my_plugin_captcha_api_key']);

  update_option('my_plugin_settings', $my_plugin_settings);
  echo '<div class="notice notice-success is-dismissible"><p>'.__('Settings updated successfully.', 'my-plugin').'</p></div>';
}
?>

//WordPress Settings API 调用

<?php
// 在 admin_menu 钩子中添加选项页面
add_action( 'admin_menu', 'my_wp_plugin_settings_page' );
function my_wp_plugin_settings_page() {
    add_options_page(
        'My WP Plugin Settings',
        'My WP Plugin',
        'manage_options',
        'my_wp_plugin_settings',
        'my_wp_plugin_settings_page_callback'
    );
}

// 在选项页面回调函数中添加表单和字段
function my_wp_plugin_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>My WP Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            // 输出隐藏字段和设置部分
            settings_fields( 'my_wp_plugin_settings_group' );
            do_settings_sections( 'my_wp_plugin_settings_page' );
            ?>
            <?php submit_button( 'Save Changes' ); ?>
        </form>
    </div>
    <?php
}

// 在 admin_init 钩子中注册设置和字段
add_action( 'admin_init', 'my_wp_plugin_register_settings' );
function my_wp_plugin_register_settings() {
    // 注册一个设置部分
    add_settings_section(
        'my_wp_plugin_settings_section',
        'My WP Plugin Settings',
        'my_wp_plugin_settings_section_callback',
        'my_wp_plugin_settings_page'
    );

    // 注册一个设置字段
    add_settings_field(
        'my_wp_plugin_setting_field',
        'My Setting Field',
        'my_wp_plugin_setting_field_callback',
        'my_wp_plugin_settings_page',
        'my_wp_plugin_settings_section'
    );

    // 注册设置并指定它们的设置部分和回调函数
    register_setting(
        'my_wp_plugin_settings_group',
        'my_wp_plugin_setting',
        'my_wp_plugin_setting_validation_callback'
    );
}

// 设置部分的回调函数
function my_wp_plugin_settings_section_callback() {
    echo 'This is the description for the My WP Plugin Settings section.';
}

// 设置字段的回调函数
function my_wp_plugin_setting_field_callback() {
    $setting = get_option( 'my_wp_plugin_setting', '' );
    echo '<input type="text" name="my_wp_plugin_setting" value="' . esc_attr( $setting ) . '" />';
}

// 设置字段的验证回调函数
function my_wp_plugin_setting_validation_callback( $input ) {
    $output = sanitize_text_field( $input );
    return $output;
}
function my_wordpress_plugin_register_settings() {
  register_setting('my_wordpress_plugin_settings_group', 'my_wordpress_plugin_settings'); // register_setting('my_wordpress_plugin_settings', 'my_wordpress_plugin_ajaxurl');
}
add_action('admin_init', 'my_wordpress_plugin_register_settings');

function my_wordpress_plugin_add_settings_page() {
  add_options_page('My Wordpress Plugin Settings', 'My Wordpress Plugin', 'manage_options', 'my_wordpress_plugin', 'my_wordpress_plugin_settings_page');
}
add_action('admin_menu', 'my_wordpress_plugin_add_settings_page');

function my_wordpress_plugin_settings_page() {
  $ajaxurl = admin_url('admin-ajax.php');
  ?>
  <div class="wrap">
    <h1>My Wordpress Plugin Settings</h1>
    <form method="post" action="options.php">
      <?php settings_fields('my_wordpress_plugin_settings'); ?>
      <?php do_settings_sections('my_wordpress_plugin_settings'); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Ajax URL</th>
          <td><input type="text" name="my_wordpress_plugin_ajaxurl" value="<?php echo esc_attr($ajaxurl); ?>" /></td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}
