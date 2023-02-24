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

           
