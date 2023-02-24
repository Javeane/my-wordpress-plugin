<?php
/**
 * Register template for My Wordpress Plugin
 *
 * This template file is responsible for displaying the register login page,
 * including the register form, form validation, and success/failure messages.
 * It also provides the ability to redirect to other pages.
 *
 * @package My_Wordpress_Plugin
 * @subpackage Templates
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>

显示注册表单
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <form id="my-plugin-register-form" method="post" onsubmit="return myPluginRegisterFormValidation();">
        <div class="form-group">
          <label for="username"><?php esc_html_e( 'Username', 'my-plugin' ); ?></label>
          <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
          <label for="email"><?php esc_html_e( 'Email', 'my-plugin' ); ?></label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password"><?php esc_html_e( 'Password', 'my-plugin' ); ?></label>
          <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="confirm-password"><?php esc_html_e( 'Confirm Password', 'my-plugin' ); ?></label>
          <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
        </div>
        <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Register', 'my-plugin' ); ?></button>
      </form>
    </div>
  </div>
</div>

提交表单时的数据验证
<?php
if ( isset( $_POST['my_plugin_register_nonce'] ) && wp_verify_nonce( $_POST['my_plugin_register_nonce'], 'my_plugin_register' ) ) {

  $username = sanitize_user( $_POST['username'] );
  $email = sanitize_email( $_POST['email'] );
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // 判断用户名和电子邮件是否为空
  if ( empty( $username ) || empty( $email ) ) {
    $error_message = esc_html__( 'Username and email are required fields.', 'my-plugin' );
  }

  // 判断两次密码输入是否一致
  if ( $password !== $confirm_password ) {
    $error_message = esc_html__( 'Passwords do not match.', 'my-plugin' );
  }

  // 如果没有错误信息，执行用户注册
  if ( ! isset( $error_message ) ) {
    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
      $error_message = $user_id->get_error_message();
    } else {
      wp_redirect( home_url() );
      exit;
    }
  }
}
?>

注册成功或失败后的提示信息显示
<form id="my-plugin-register-form" method="post" onsubmit="return myPluginRegisterFormValidation();">
  <div class="form-group">
    <label for="username"><?php esc_html_e( 'Username', 'my-plugin' ); ?></label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="email"><?php esc_html_e( 'Email', 'my-plugin' ); ?></label>
    <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="form-group">
    <label for="password"><?php esc_html_e( 'Password', 'my-plugin' ); ?></label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Register', 'my-plugin' ); ?></button>
</form>

<?php
if ( isset( $_GET['register'] ) ) {
  $register = $_GET['register'];

  // 判断注册状态
  if ( $register === 'success' ) {
    echo '<div class="alert alert-success" role="alert">';
    esc_html_e( 'Registration successful, please login.', 'my-plugin' );
    echo '</div>';
  } elseif ( $register === 'failed' ) {
    echo '<div class="alert alert-danger" role="alert">';
    esc_html_e( 'Registration failed, please try again.', 'my-plugin' );
    echo '</div>';
  }
}
?>

跳转到其他页面的功能
<?php
if ( isset( $_GET['register'] ) ) {
  $register = $_GET['register'];

  // 判断注册状态
  if ( $register === 'success' ) {
    echo '<div class="alert alert-success" role="alert">';
    esc_html_e( 'Registration successful. You can now log in.', 'my-plugin' );
    echo '</div>';
  } elseif ( $register === 'failed' ) {
    echo '<div class="alert alert-danger" role="alert">';
    esc_html_e( 'Registration failed, please try again.', 'my-plugin' );
    echo '</div>';
  }
}
?>
