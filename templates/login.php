<?php
/**
 * Login template for My Wordpress Plugin
 *
 * This template file is responsible for displaying the user login page,
 * including the login form, form validation, and success/failure messages.
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

<div class="mywp-login-form">
  <h2><?php echo esc_html( get_option( 'mywp_login_title' ) ); ?></h2>
  <?php if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
    <p class="mywp-login-error"><?php echo esc_html( get_option( 'mywp_login_failed_message' ) ); ?></p>
  <?php endif; ?>
  <form name="loginform" id="loginform" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
    <div class="mywp-login-username">
      <label for="user_login"><?php echo esc_html( get_option( 'mywp_login_username_label' ) ); ?></label>
      <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" />
    </div>
    <div class="mywp-login-password">
      <label for="user_pass"><?php echo esc_html( get_option( 'mywp_login_password_label' ) ); ?></label>
      <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
    </div>
    <?php do_action( 'login_form' ); ?>
    <div class="mywp-login-submit">
      <input type="hidden" name="action" value="mywp_login">
      <input type="hidden" name="redirect_to" value="<?php echo esc_attr( get_option( 'mywp_login_redirect' ) ); ?>">
      <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php echo esc_attr( get_option( 'mywp_login_button_label' ) ); ?>">
    </div>
  </form>
</div>

提交表单时的数据验证
<form id="my-plugin-login-form" method="post" onsubmit="return myPluginLoginFormValidation();">
  <div class="form-group">
    <label for="username"><?php esc_html_e( 'Username', 'my-plugin' ); ?></label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="password"><?php esc_html_e( 'Password', 'my-plugin' ); ?></label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Login', 'my-plugin' ); ?></button>
</form>
<script>
function myPluginLoginFormValidation() {
  // 获取表单输入框中的值
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();

  // 验证表单输入是否为空
  if (username === '' || password === '') {
    alert('<?php esc_html_e( 'Please enter username and password', 'my-plugin' ); ?>');
    return false;
  }

  // 表单验证通过，返回 true
  return true;
}
</script>

登录成功或失败后的提示信息显示
<form id="my-plugin-login-form" method="post" onsubmit="return myPluginLoginFormValidation();">
  <div class="form-group">
    <label for="username"><?php esc_html_e( 'Username', 'my-plugin' ); ?></label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="password"><?php esc_html_e( 'Password', 'my-plugin' ); ?></label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Login', 'my-plugin' ); ?></button>
</form>

<?php
if ( isset( $_GET['login'] ) ) {
  $login = $_GET['login'];

  // 判断登录状态
  if ( $login === 'failed' ) {
    echo '<div class="alert alert-danger" role="alert">';
    esc_html_e( 'Login failed, please try again.', 'my-plugin' );
    echo '</div>';
  } elseif ( $login === 'empty' ) {
    echo '<div class="alert alert-danger" role="alert">';
    esc_html_e( 'Please enter username and password.', 'my-plugin' );
    echo '</div>';
  }
}
?>

跳转到其他页面的功能
<form id="my-plugin-login-form" method="post" onsubmit="return myPluginLoginFormValidation();">
  <div class="form-group">
    <label for="username"><?php esc_html_e( 'Username', 'my-plugin' ); ?></label>
    <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="form-group">
    <label for="password"><?php esc_html_e( 'Password', 'my-plugin' ); ?></label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Login', 'my-plugin' ); ?></button>
</form>

<?php
if ( isset( $_GET['login'] ) ) {
  $login = $_GET['login'];

  // 判断登录状态
  if ( $login === 'failed' ) {
    echo '<div class="alert alert-danger" role="alert">';
    esc_html_e( 'Login failed, please try again.', 'my-plugin' );
    echo '</div>';
  } elseif ( $login === 'empty' ) {
    echo '<div class="alert alert-danger" role="alert">';
    esc_html_e( 'Please enter username and password.', 'my-plugin' );
    echo '</div>';
  }
}

// 处理登录请求
if ( isset( $_POST['submit'] ) ) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // 验证用户身份
  if ( ! wp_authenticate( $username, $password ) ) {
    // 登录失败，重定向到当前页面并添加错误参数
    wp_redirect( add_query_arg( 'login', 'failed', $_SERVER['REQUEST_URI'] ) );
    exit;
  }

  // 登录成功，重定向到指定页面
  wp_redirect( home_url( '/dashboard' ) );
  exit;
}
?>
