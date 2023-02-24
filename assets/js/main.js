/**
 * This file is responsible for all the frontend JavaScript functionality
 *
 * @package My_Wordpress_Plugin
 */
jQuery(document).ready(function($) {
  // 页面加载时的初始化功能
  // 在页面加载完成后，通过 Ajax 请求判断用户是否已经登录，并根据登录状态进行相应的处理
  $.ajax({
    url: my_plugin_params.ajax_url,
    type: 'POST',
    dataType: 'json',
    data: {
      action: 'my_plugin_check_login_status',
    },
    success: function(response) {
      if (response.logged_in) {
        // 用户已登录
        $('#my-plugin-login-btn').hide();
        $('#my-plugin-register-btn').hide();
        $('#my-plugin-logout-btn').show();
        $('#my-plugin-username').text(response.username);
        $('#my-plugin-userinfo').show();
      } else {
        // 用户未登录
        $('#my-plugin-login-btn').show();
        $('#my-plugin-register-btn').show();
        $('#my-plugin-logout-btn').hide();
        $('#my-plugin-userinfo').hide();
      }
    },
    error: function(xhr, status, error) {
      console.log('Ajax error: ' + xhr.responseText);
    }
  });

  // 其他初始化功能代码
  // ...
});
jQuery(document).ready(function($) {
  // 用户登录
  $("#login-form").on("submit", function(e) {
    e.preventDefault();

    var username = $("#login-username").val();
    var password = $("#login-password").val();

    $.ajax({
      url: my_plugin.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "my_plugin_user_login",
        username: username,
        password: password
      },
      success: function(data) {
        if (data.success) {
          window.location.reload();
        } else {
          alert(data.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("发生错误：" + errorThrown);
      }
    });
  });

  // 用户注册
  $("#register-form").on("submit", function(e) {
    e.preventDefault();

    var username = $("#register-username").val();
    var email = $("#register-email").val();
    var password = $("#register-password").val();

    $.ajax({
      url: my_plugin.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "my_plugin_user_register",
        username: username,
        email: email,
        password: password
      },
      success: function(data) {
        if (data.success) {
          window.location.reload();
        } else {
          alert(data.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("发生错误：" + errorThrown);
      }
    });
  });

  // 用户注销
  $("#logout-link").on("click", function(e) {
    e.preventDefault();

    $.ajax({
      url: my_plugin.ajax_url,
      type: "POST",
      dataType: "json",
      data: {
        action: "my_plugin_user_logout"
      },
      success: function(data) {
        if (data.success) {
          window.location.reload();
        } else {
          alert(data.message);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("发生错误：" + errorThrown);
      }
    });
  });
});
// 获取用户个人信息
function getUserInfo() {
  var currentUserId = jQuery('#current-user-id').val(); // 获取当前登录用户的 ID
  var data = {
    action: 'get_user_info',
    user_id: currentUserId
  };

  jQuery.ajax({
    type: 'POST',
    url: ajaxurl,
    data: data,
    success: function(response) {
      var userInfo = JSON.parse(response); // 将返回的 JSON 数据解析为对象
      // 将用户信息显示在页面上
      jQuery('#user-display-name').html(userInfo.display_name);
      jQuery('#user-email').html(userInfo.user_email);
      jQuery('#user-bio').html(userInfo.description);
      // 将用户信息填充到编辑表单
      jQuery('#edit-user-display-name').val(userInfo.display_name);
      jQuery('#edit-user-email').val(userInfo.user_email);
      jQuery('#edit-user-bio').val(userInfo.description);
    }
  });
}

// 编辑用户个人信息
function editUserInfo() {
  var currentUserId = jQuery('#current-user-id').val(); // 获取当前登录用户的 ID
  var displayName = jQuery('#edit-user-display-name').val();
  var userEmail = jQuery('#edit-user-email').val();
  var userBio = jQuery('#edit-user-bio').val();
  var data = {
    action: 'edit_user_info',
    user_id: currentUserId,
    display_name: displayName,
    user_email: userEmail,
    description: userBio
  };

  jQuery.ajax({
    type: 'POST',
    url: ajaxurl,
    data: data,
    success: function(response) {
      var userInfo = JSON.parse(response); // 将返回的 JSON 数据解析为对象
      // 将修改后的用户信息显示在页面上
      jQuery('#user-display-name').html(userInfo.display_name);
      jQuery('#user-email').html(userInfo.user_email);
      jQuery('#user-bio').html(userInfo.description);
    }
  });
}

// 页面加载时获取用户信息并显示
jQuery(document).ready(function() {
  getUserInfo();

  // 点击编辑按钮，切换到编辑模式
  jQuery('#edit-user-btn').click(function() {
    jQuery('.user-info-view').hide();
    jQuery('.user-info-edit').show();
  });

  // 点击保存按钮，保存修改后的用户信息
  jQuery('#save-user-btn').click(function() {
    editUserInfo();
    jQuery('.user-info-view').show();
    jQuery('.user-info-edit').hide();
  });
});
// 初始化 Dropzone.js
function initDropzone() {
  jQuery('#avatar-dropzone').dropzone({
    url: ajaxurl,
    paramName: 'avatar',
    uploadMultiple: false,
    maxFiles: 1,
    acceptedFiles: 'image/*'
    dictDefaultMessage: '将图片拖到此处或点击上传',
    dictFallbackMessage: '您的浏览器不支持拖放文件上传',
    dictInvalidFileType: '不支持上传此类型的文件',
    dictFileTooBig: '文件大小超出限制',
    dictCancelUpload: '取消上传',
    dictCancelUploadConfirmation: '您确定要取消上传吗？',
    dictRemoveFile: '删除文件',
    dictMaxFilesExceeded: '已达到上传文件数量上限',
    init: function() {
    this.on('success', function(file, response) {
    if (response.success) {
        jQuery('#avatar-img').attr('src', response.data.avatar_url);
        jQuery('#avatar-upload-modal').modal('hide');
    // 显示成功消息
        showSuccessMessage(response.data.message);
    } else {
    // 显示错误消息
        showErrorMessage(response.data.message);
        }
    });
    }
    });
}

    // 显示头像上传模态框
    function showAvatarUploadModal() {
    jQuery('#avatar-upload-modal').modal('show');
    }

    // 初始化头像上传模态框
    function initAvatarUploadModal() {
    // 点击上传按钮后弹出文件选择对话框
    jQuery('#avatar-select-btn').click(function() {
    jQuery('#avatar-select-input').trigger('click');
    });

    // 显示头像上传模态框
    jQuery('#avatar-upload-btn').click(function() {
    showAvatarUploadModal();
    });

    // 上传头像表单提交
    jQuery('#avatar-upload-form').submit(function(event) {
    event.preventDefault();
    jQuery.ajax({
    url: ajaxurl,
    type: 'POST',
    dataType: 'json',
    data: {
    action: 'my_wordpress_plugin_upload_avatar',
    nonce: jQuery('#avatar-upload-nonce').val(),
    avatar: jQuery('#avatar-select-input').prop('files')[0]
    },
    success: function(response) {
    if (response.success) {
    jQuery('#avatar-img').attr('src', response.data.avatar_url);
    jQuery('#avatar-upload-modal').modal('hide');
    // 显示成功消息
    showSuccessMessage(response.data.message);
    } else {
    // 显示错误消息
    showErrorMessage(response.data.message);
    }
    },
    error: function(jqXHR, textStatus, errorThrown) {
    // 显示错误消息
    showErrorMessage(errorThrown);
    }
    });
    });
    }

    // 初始化用户信息管理页面
    function initUserManagement() {
    initDropzone();
    initAvatarUploadModal();
    }

    // 页面加载时初始化用户信息管理
    jQuery(document).ready(function() {
    initUserManagement();
    });
// 页面加载时初始化用户信息管理
jQuery(document).ready(function() {
  initUserManagement();
});

// 初始化用户信息管理
function initUserManagement() {
  // 绑定修改头像事件
  jQuery('#change-avatar-btn').on('click', function() {
    jQuery('#avatar-modal').modal('show');
    initDropzone();
  });

  // 绑定更新用户信息事件
  jQuery('#update-user-info-btn').on('click', function() {
    updateUserInformation();
  });

  // 绑定修改密码事件
  jQuery('#change-password-btn').on('click', function() {
    jQuery('#password-modal').modal('show');
  });

  // 绑定更新密码事件
  jQuery('#update-password-btn').on('click', function() {
    updatePassword();
  });
}

// 上传和修改头像的代码
// 初始化 Dropzone.js
function initDropzone() {
  jQuery('#avatar-dropzone').dropzone({
    url: ajaxurl,
    paramName: 'avatar',
    uploadMultiple: false,
    maxFiles: 1,
    acceptedFiles: 'image/*',
    init: function() {
      this.on('success', function(file, response) {
        if (response.success) {
          jQuery('#avatar-preview').attr('src', response.data.url);
          jQuery('#avatar-modal').modal('hide');
        } else {
          alert(response.data.message);
        }
      });
    }
  });
}

// 更新用户信息
function updateUserInformation() {
  var data = {
    action: 'update_user_information',
    display_name: jQuery('#display-name-input').val(),
    description: jQuery('#description-input').val(),
    user_email: jQuery('#user-email-input').val(),
    user_url: jQuery('#user-url-input').val()
  };

  jQuery.post(ajaxurl, data, function(response) {
    if (response.success) {
      alert('用户信息更新成功');
    } else {
      alert(response.data.message);
    }
  });
}

// 更新密码
function updatePassword() {
  var data = {
    action: 'update_password',
    old_password: jQuery('#old-password-input').val(),
    new_password: jQuery('#new-password-input').val(),
    confirm_new_password: jQuery('#confirm-new-password-input').val()
  };

  jQuery.post(ajaxurl, data, function(response) {
    if (response.success) {
      alert('密码更新成功');
      jQuery('#password-modal').modal('hide');
    } else {
      alert(response.data.message);
    }
  });
}






















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


// 后端处理请求的 URL 地址
const ajaxurl = MyWPPlugin.ajaxurl;

// 参数名称常量
const PARAM_ACTION = 'action';
const PARAM_NONCE = 'nonce';
const PARAM_USERNAME = 'username';
const PARAM_PASSWORD = 'password';
const PARAM_EMAIL = 'email';
const PARAM_AVATAR = 'avatar';
/**
 * 向后端发送 AJAX 请求
 *
 * @param {string} action - 请求的操作名称
 * @param {object} data - 请求的参数数据
 * @param {function} successCallback - 请求成功后的回调函数
 * @param {function} errorCallback - 请求失败后的回调函数
 */
function sendAjaxRequest(action, data, successCallback, errorCallback) {
  // 添加默认的操作名称和安全性标记参数
  data[PARAM_ACTION] = action;
  data[PARAM_NONCE] = MyWPPlugin.nonce;

  // 发送 AJAX 请求
  jQuery.ajax({
    url: ajaxurl,
    type: 'POST',
    data: data,
    dataType: 'json',
    success: successCallback,
    error: errorCallback,
  });
}

/**
 * 发送登录请求
 *
 * @param {string} username - 用户名
 * @param {string} password - 密码
 * @param {function} successCallback - 登录成功后的回调函数
 * @param {function} errorCallback - 登录失败后的回调函数
 */
function login(username, password, successCallback, errorCallback) {
  const data = {
    [PARAM_USERNAME]: username,
    [PARAM_PASSWORD]: password,
  };

  sendAjaxRequest('mywpplugin_login', data, successCallback, errorCallback);
}

/**
 * 发送注册请求
 *
 * @param {string} username - 用户名
 * @param {string} password - 密码
 * @param {string} email - 邮箱地址
 * @param {function} successCallback - 注册成功后的回调函数
 * @param {function} errorCallback - 注册失败后的回调函数
 */
function register(username, password, email, successCallback, errorCallback) {
  const data = {
    [PARAM_USERNAME]: username,
    [PARAM_PASSWORD]: password,
    [PARAM_EMAIL]: email,
  };

  sendAjaxRequest('mywpplugin_register', data, successCallback, errorCallback);
}

/**
 * 发送注销请求
 *
 * @param {function} successCallback - 注销成功后的回调函数
 * @param {function} errorCallback - 注销失败后的回调函数
 */
function logout(successCallback, errorCallback) {
  const data = {};

  sendAjaxRequest('mywpplugin_logout', data, successCallback, errorCallback);
}

/**
 * 发送 Ajax 请求
 *
 * @param {string} action - 请求的操作名称
 * @param {object} data - 请求的数据对象
 * @param {function} successCallback - 请求成功后的回调函数
 * @param {function} errorCallback - 请求失败后的回调函数
 */
function sendAjaxRequest(action, data, successCallback, errorCallback) {
  jQuery.ajax({
    url: ajaxurl,
    type: 'POST',
    data: Object.assign(data, {
      action: action,
    }),
    success: function(response) {
      if (response.success) {
        if (successCallback) {
          successCallback(response.data);
        }
      } else {
        if (errorCallback) {
          errorCallback(response.data);
        }
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      if (errorCallback) {
        errorCallback(errorThrown);
      }
    },
  });
}
