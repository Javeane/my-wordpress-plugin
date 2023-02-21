<?php

/**
 * Plugin verification module.
 *
 * This module provides the verification feature for the plugin.
 *
 * @package my-wordpress-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$email_verification_model = new EmailVerificationModel();

//模块一：初始化函数：初始化类的实例变量

class Verification {
    private $table_name;

    private $email_template = array(
        'subject' => '',
        'body' => ''
    );

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'verification';
        $this->create_table();
        $this->set_email_template();
    }

    private function create_table() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            code VARCHAR(32) NOT NULL,
            created_at DATETIME NOT NULL,
            verified TINYINT(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY (id),
            INDEX user_id (user_id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function set_email_template() {
        $this->email_template['subject'] = __( 'Email verification', 'my-wordpress-plugin' );
        $this->email_template['body'] = __( 'Thank you for registering! To verify your email address, please click the link below:', 'my-wordpress-plugin' );
    }
}

//模块二：表单处理函数：处理表单提交的数据并完成相应的验证操作

/**
 * 表单处理函数：处理表单提交的数据并完成相应的验证操作
 */
public function handle_verification_form_submission() {
    // 判断表单是否被提交，以及表单中的隐藏字段值是否正确
    if ( isset( $_POST['verification_nonce'] ) && wp_verify_nonce( $_POST['verification_nonce'], 'verification' ) ) {
        // 获取表单中的数据
        $user_id = get_current_user_id();
        $token = sanitize_text_field( $_POST['token'] );
        $type = sanitize_text_field( $_POST['type'] );

        // 验证 token 是否有效
        $verification = $this->email_verification_model->get_verification_by_token( $token );

        if ( ! $verification ) {
            // 如果 token 无效，显示错误信息
            $this->set_error( __( 'Invalid verification token.', 'my-wordpress-plugin' ) );
        } else {
            // 如果 token 有效，验证是否超时
            if ( $this->email_verification_model->is_verification_expired( $verification ) ) {
                // 如果已过期，显示错误信息
                $this->set_error( __( 'The verification link has expired. Please request a new verification link.', 'my-wordpress-plugin' ) );
            } else {
                // 验证类型是否匹配
                if ( $verification->type !== $type ) {
                    // 如果不匹配，显示错误信息
                    $this->set_error( __( 'Invalid verification link.', 'my-wordpress-plugin' ) );
                } else {
                    // 验证用户 ID 是否匹配
                    if ( $verification->user_id != $user_id ) {
                        // 如果不匹配，显示错误信息
                        $this->set_error( __( 'Invalid verification link.', 'my-wordpress-plugin' ) );
                    } else {
                        // 验证通过，将用户状态设置为已验证，删除该条验证记录，显示成功信息
                        update_user_meta( $user_id, 'email_verified', true );
                        $this->email_verification_model->delete_verification_by_token( $token );
                        $this->set_notice( __( 'Your email has been verified successfully.', 'my-wordpress-plugin' ) );
                    }
                }
            }
        }
    }
}

//模块三：表单渲染函数：渲染用于输入验证信息的表单

/**
 * 渲染用于输入验证信息的表单
 *
 * @param array $fields 表单字段信息，每个字段包含以下属性：
 *                      name: 字段名称
 *                      label: 字段标签
 *                      type: 字段类型，可以为 text、email、password 等
 *                      value: 字段默认值
 *                      required: 是否必填，可以为 true 或 false
 *                      error: 字段验证错误信息
 *
 * @return string 渲染后的 HTML 表单
 */
function render_form($fields) {
  $html = '<form method="post">';

  foreach ($fields as $field) {
    $html .= '<div class="form-group">';
    $html .= '<label for="' . $field['name'] . '">' . $field['label'] . '</label>';

    $input_type = isset($field['type']) ? $field['type'] : 'text';
    $input_name = $field['name'];
    $input_value = isset($field['value']) ? $field['value'] : '';
    $input_required = isset($field['required']) && $field['required'] ? 'required' : '';
    $input_error = isset($field['error']) ? '<div class="error">' . $field['error'] . '</div>' : '';

    $html .= '<input type="' . $input_type . '" name="' . $input_name . '" value="' . $input_value . '" ' . $input_required . '>';
    $html .= $input_error;
    $html .= '</div>';
  }

  $html .= '<button type="submit">提交</button>';
  $html .= '</form>';

  return $html;
}

//模块四：验证结果提示函数：在表单提交后显示验证结果的提示信息

/**
 * 显示验证结果的提示信息
 *
 * @param string $type 提示类型，包括 success（成功）、error（错误）和 info（信息）
 * @param string $message 提示信息
 *
 * @return void
 */
function displayVerificationMessage($type, $message) {
    if (!in_array($type, array('success', 'error', 'info'))) {
        return;
    }

    $class = 'verification-' . $type;

    echo '<div class="verification-message ' . $class . '">' . $message . '</div>';
}

//模块五：邮件发送函数：发送包含验证链接的验证邮件

/**
 * 发送验证邮件
 * @param string $to 收件人邮箱地址
 * @param string $subject 邮件主题
 * @param string $message 邮件内容
 * @return bool 邮件是否发送成功
 */
function send_verification_email($to, $subject, $message)
{
    // 设置邮件头信息
    $headers = "From: " . "sender@example.com" . "\r\n";
    $headers .= "Reply-To: " . "sender@example.com" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // 发送邮件
    $result = mail($to, $subject, $message, $headers);

    // 返回邮件发送结果
    return $result;
}

//模块六：验证处理函数：根据验证邮件中的链接完成验证操作

/**
 * 验证处理函数
 *
 * 根据验证邮件中的链接完成验证操作
 *
 * @param string $code 验证码
 * @return string 验证结果提示信息
 */
function handle_verification($code) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'verification';
    $verification = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE code = %s",
            $code
        )
    );

    if (!$verification) {
        return '<p class="verification-error">' . __('无效的验证码。', 'textdomain') . '</p>';
    }

    $user_id = $verification->user_id;
    $verification_time = $verification->verification_time;
    $expiration_time = strtotime($verification_time) + 24 * 60 * 60;

    if (time() > $expiration_time) {
        $wpdb->delete($table_name, array('id' => $verification->id));
        return '<p class="verification-error">' . __('验证链接已过期。', 'textdomain') . '</p>';
    }

    $wpdb->delete($table_name, array('id' => $verification->id));

    update_user_meta($user_id, 'verified', true);

    return '<p class="verification-success">' . __('您的帐户已成功验证。', 'textdomain') . '</p>';
}
