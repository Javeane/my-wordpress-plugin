/**
 * This file is responsible for all the frontend JavaScript functionality
 *
 * @package My_Wordpress_Plugin
 */
jQuery(document).ready(function($) {

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

});

jQuery(document).ready(function($) {
    $('#login-link').attr('href', 'https://your-custom-login-page-url.com');
});

jQuery(document).ready(function($) {
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
            captcha: {
                required: true,
                minlength: 4
            }
        },
        // 错误提示信息
        messages: {
            username: {
                required: "请输入用户名",
                minlength: "用户名至少需要3个字符"
            },
            password: {
                required: "请输入密码",
                minlength: "密码至少需要6个字符"
            },
            captcha: {
                required: "请输入验证码",
                minlength: "验证码至少需要4个字符"
            }
        }
    });

    $('#register-form').validate({
        // 验证规则
        rules: {
            username: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            },
            captcha: {
                required: true,
                minlength: 4
            }
        },
        // 错误提示信息
        messages: {
            username: {
                required: "请输入用户名",
                minlength: "用户名至少需要3个字符"
            },
            email: {
                required: "请输入邮箱",
                email: "请输入正确的邮箱格式"
            },
            password: {
                required: "请输入密码",
                minlength: "密码至少需要6个字符"
            },
            confirm_password: {
                required: "请输入确认密码",
                minlength: "确认密码至少需要6个字符",
                equalTo: "两次输入的密码不一致",
}

jQuery(document).ready(function($) {

  // 显示用户头像上传表单
  $('body').on('click', '.upload-avatar', function(e) {
    e.preventDefault();
    $('.avatar-upload-form').slideToggle();
  });

  // 在前端显示上传的用户头像
  $('body').on('change', '#avatar-upload', function() {
    var input = this;
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('.avatar-image').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  });

  // 使用 Ajax 提交用户头像表单
  $('body').on('submit', '#avatar-upload-form', function(e) {
    e.preventDefault();
    var form_data = new FormData(this);
    $.ajax({
      type: 'POST',
      url: my_wordpress_plugin_ajax.ajaxurl,
      data: form_data,
      processData: false,
      contentType: false,
      success: function(response) {
        $('.avatar-upload-form').slideUp();
        $('.avatar-image').attr('src', response);
      }
    });
  });

});

