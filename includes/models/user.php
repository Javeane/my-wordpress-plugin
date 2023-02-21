<?php

class My_WordPress_Plugin_User {

  public static function init() {
    add_action('wp_ajax_nopriv_my_wordpress_plugin_login', array(__CLASS__, 'login'));
    add_action('wp_ajax_nopriv_my_wordpress_plugin_register', array(__CLASS__, 'register'));
    add_action('wp_ajax_nopriv_my_wordpress_plugin_social_login', array(__CLASS__, 'social_login'));
    add_action('wp_ajax_nopriv_my_wordpress_plugin_verify_email', array(__CLASS__, 'verify_email'));
    add_action('wp_ajax_my_wordpress_plugin_upload_avatar', array(__CLASS__, 'upload_avatar'));
    add_action('wp_ajax_my_wordpress_plugin_send_verification_code', array(__CLASS__, 'send_verification_code'));
  }

  public static function login() {
    // 处理登录逻辑
  }

  public static function register() {
    // 处理注册逻辑
  }

  public static function social_login() {
    // 处理社交账号登录逻辑
  }

  public static function verify_email() {
    // 处理邮箱验证逻辑
  }

  public static function upload_avatar() {
    // 处理上传头像逻辑
  }

  public static function send_verification_code() {
    // 发送邮箱验证码
  }

}

My_WordPress_Plugin_User::init();
