/**
 * This file is responsible for all the frontend JavaScript functionality
 *
 * @package My_Wordpress_Plugin
 */
jQuery(document).ready(function($) {
    var my_wp_plugin_ajax_url = '/wp-admin/admin-ajax.php'; // 添加变量声明或赋值

    // Handle avatar upload form submission
    $('form#my-wp-plugin-avatar-upload-form').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        var fileInput = $('input#my-wp-plugin-avatar-file');
        var file = fileInput[0].files[0];
        var formData = new FormData();
        formData.append('file', file);
        formData.append('nonce', form.data('nonce'));

        // Show loading spinner
        form.find('.my-wp-plugin-avatar-upload-spinner').show();

        // Send AJAX request
        $.ajax({
            url: my_wp_plugin_ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Update user avatar
                    var img = form.siblings('img.my-wp-plugin-avatar');
                    img.attr('src', response.file_url);

                    // Hide upload form
                    form.slideUp();

                    // Show success message
                    form.siblings('.my-wp-plugin-avatar-success-message').slideDown();
                } else {
                    // Show error message
                    form.siblings('.my-wp-plugin-avatar-error-message').slideDown();
                }
            },
            error: function() {
                // Show error message
                form.siblings('.my-wp-plugin-avatar-error-message').slideDown();
            },
            complete: function() {
                // Hide loading spinner
                form.find('.my-wp-plugin-avatar-upload-spinner').hide();
            }
        });
    });

    // 修改登录链接
    $('#login-link').attr('href', 'https://your-custom-login-page-url.com');

    // 表单验证
    $('#login-form').validate({
        // 验证规则
        rules: {
            username: {
                required: true,
                minlength: 3
            },
            password: {
                required: true,
                minlength: 6
            },
    // 验证信息提示
    messages: {
        username: {
            required: '请输入用户名',
            minlength: '用户名长度不能小于3个字符'
        },
        password: {
            required: '请输入密码',
            minlength: '密码长度不能小于6个字符'
        }
    },
    // 提交表单处理
    submitHandler: function(form) {
        // 表单提交代码
        $.ajax({
            url: 'https://your-api-url.com/login',
            type: 'POST',
            data: $('#login-form').serialize(),
            success: function(response) {
                // 登录成功后的处理代码
                console.log(response);
                // 跳转到登录成功页面
                window.location.href = 'https://your-success-page-url.com';
            },
            error: function(xhr, status, error) {
                // 登录失败后的处理代码
                console.log(xhr.responseText);
                alert('登录失败，请重试');
            }
        });
    }
});

// 切换密码可见性
$('#toggle-password').click(function() {
    var passwordInput = $('#password');
    var passwordInputType = passwordInput.attr('type');
    if (passwordInputType === 'password') {
        passwordInput.attr('type', 'text');
        $(this).html('<i class="far fa-eye-slash"></i>');
    } else {
        passwordInput.attr('type', 'password');
        $(this).html('<i class="far fa-eye"></i>');
    }
});

// 初始化页面
$(document).ready(function() {
    // 页面加载完成后执行的代码
});
// Add a menu tab and a page to display plugin settings
function my_wp_plugin_add_settings_page() {
  // Create the menu tab
  var settings_tab = $('<a href="#my-wp-plugin-settings-page">My Plugin Settings</a>');
  $('ul#wp-admin-bar-root-default').append($('<li></li>').append(settings_tab));

  // Create the settings page
  var settings_page = $('<div id="my-wp-plugin-settings-page"></div>').hide();
  $('div.wrap').append(settings_page);

  // Display the settings page when the menu tab is clicked
  settings_tab.click(function(event) {
    event.preventDefault();
    settings_page.show();
    $('div.wrap > h1').text('My Plugin Settings');
  });

  // Add some content to the settings page
  settings_page.append($('<p>Here you can configure your plugin settings.</p>'));
  settings_page.append($('<label for="my-wp-plugin-api-key">API Key</label>'));
  settings_page.append($('<input type="text" id="my-wp-plugin-api-key" name="my_wp_plugin_api_key">'));
  settings_page.append($('<input type="submit" value="Save">'));
}
$(document).ready(function() {
  my_wp_plugin_add_settings_page();
});
// Register a cron job to clean up plugin data once a day
function my_wp_plugin_cleanup() {
  // Add your cleanup code here
}
add_action('my_wp_plugin_cleanup_hook', 'my_wp_plugin_cleanup');
wp_schedule_event(time(), 'daily', 'my_wp_plugin_cleanup_hook');

// Register the widget
function my_wp_plugin_register_widget() {
  register_widget('My_WP_Plugin_Widget');
}
add_action('widgets_init', 'my_wp_plugin_register_widget');

// Define the widget class
class My_WP_Plugin_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'my_wp_plugin_widget',
            __( 'My WordPress Plugin Widget', 'my-wp-plugin' ),
            array( 'description' => __( 'Displays some information about My WordPress Plugin', 'my-wp-plugin' ) )
        );
    }

    public function widget( $args,$instance ) {

// Widget output
echo $args['before_widget'];
echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
echo '<p>' . $instance['content'] . '</p>';
echo $args['after_widget'];
}
public function form( $instance ) {
    // Widget form fields
    $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'My WordPress Plugin', 'my-wp-plugin' );
    $content = ! empty( $instance['content'] ) ? $instance['content'] : __( 'This is some information about My WordPress Plugin', 'my-wp-plugin' );
    ?>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content:' ); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>"><?php echo esc_attr( $content ); ?></textarea>
    </p>
    <?php
}

public function update( $new_instance, $old_instance ) {
    // Update widget fields
    $instance = array();
    $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
    $instance['content'] = ! empty( $new_instance['content'] ) ? sanitize_text_field( $new_instance['content'] ) : '';
    return $instance;
}
}
// Register widget
function my_wp_plugin_register_widget() {
register_widget( 'My_WP_Plugin_Widget' );
}
add_action( 'widgets_init', 'my_wp_plugin_register_widget' );
