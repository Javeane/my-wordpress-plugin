<?php
/**
 * Plugin user register module.
 *
 * This module provides the social login functionality. for the plugin.
 *
 * @package my-wordpress-plugin
 * @subpackage Core
 */

// includes/core/register.php

// 加载必要的文件
require_once MY_WORDPRESS_PLUGIN_DIR_PATH . 'includes/models/user.php';
require_once MY_WORDPRESS_PLUGIN_DIR_PATH . 'includes/models/email-verification.php';
require_once MY_WORDPRESS_PLUGIN_DIR_PATH . 'includes/email/mailer.php';

// 处理表单提交
if (isset($_POST['register'])) {
    // 验证表单数据
    $errors = array();

    $username = sanitize_text_field($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // 验证用户名
    if (empty($username)) {
        $errors[] = __('请输入用户名', 'my-wordpress-plugin');
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = __('用户名只能包含字母、数字和下划线', 'my-wordpress-plugin');
    } elseif (username_exists($username)) {
        $errors[] = __('用户名已存在', 'my-wordpress-plugin');
    }

    // 验证邮箱
    if (empty($email)) {
        $errors[] = __('请输入邮箱地址', 'my-wordpress-plugin');
    } elseif (!is_email($email)) {
        $errors[] = __('邮箱地址不合法', 'my-wordpress-plugin');
    } elseif (email_exists($email)) {
        $errors[] = __('该邮箱已被注册', 'my-wordpress-plugin');
    }

    // 验证密码
    if (empty($password)) {
        $errors[] = __('请输入密码', 'my-wordpress-plugin');
    } elseif (strlen($password) < 8) {
        $errors[] = __('密码长度至少为8个字符', 'my-wordpress-plugin');
    }

    if ($password != $password_confirm) {
        $errors[] = __('两次输入的密码不一致', 'my-wordpress-plugin');
    }

    // 如果有错误，返回错误信息
    if (!empty($errors)) {
        return $errors;
    }

    // 保存用户到数据库
    $user_id = wp_insert_user(array(
        'user_login' => $username,
        'user_pass' => $password,
        'user_email' => $email,
        'user_registered' => current_time('mysql'),
        'role' => 'subscriber'
    ));

    // 保存额外的用户信息到数据库
    if (!is_wp_error($user_id)) {
        $user = new My_WP_User($user_id);

        // 在这里可以根据需要保存更多的用户信息

        // 发送验证邮件
        $verification_key = My_WP_Email_Verification::generate_verification_key($user_id);

        $verification_link = add_query_arg(
            array(
                'verification_key' => $verification_key,
                'user_id' => $user_id
            ),
            home_url('/email-verification/')
        );

        $mailer = new My_WP_Mailer();
        $mailer->send_verification_email($user, $verification_link);
    }
}
