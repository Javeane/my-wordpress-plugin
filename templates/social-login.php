<?php
/**
 * Social Login for My Wordpress Plugin My Wordpress Plugin - 
 * This template file is responsible for social login page.
 *
 * @package My_Wordpress_Plugin
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
    die( 'No direct script access allowed' );
}
?>

显示社交登录按钮

<div class="social-login-container">
  <h2><?php echo esc_html($args['title']); ?></h2>
  <div class="social-buttons">
    <?php if (in_array('facebook', $args['providers'])) : ?>
      <a href="<?php echo esc_url($facebook_login_url); ?>">
        <button class="btn btn-social btn-facebook">
          <i class="fa fa-facebook"></i> <?php esc_html_e('Facebook', 'mywpplugin'); ?>
        </button>
      </a>
    <?php endif; ?>
    <?php if (in_array('twitter', $args['providers'])) : ?>
      <a href="<?php echo esc_url($twitter_login_url); ?>">
        <button class="btn btn-social btn-twitter">
          <i class="fa fa-twitter"></i> <?php esc_html_e('Twitter', 'mywpplugin'); ?>
        </button>
      </a>
    <?php endif; ?>
    <?php if (in_array('google', $args['providers'])) : ?>
      <a href="<?php echo esc_url($google_login_url); ?>">
        <button class="btn btn-social btn-google">
          <i class="fa fa-google"></i> <?php esc_html_e('Google', 'mywpplugin'); ?>
        </button>
      </a>
    <?php endif; ?>
  </div>
</div>

社交登录成功或失败后的提示信息显示

<?php if (isset($_GET['social_login_message'])) { ?>
    <div class="social-login-message">
        <?php echo sanitize_text_field($_GET['social_login_message']); ?>
    </div>
<?php } ?>

跳转到其他页面的功能
<?php
// 获取登录成功后需要跳转的 URL
$redirect_url = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

// 检查 URL 是否合法，如果不合法，则跳转到网站首页
if ( ! wp_validate_redirect( $redirect_url, home_url() ) ) {
    $redirect_url = home_url();
}

// 跳转到指定页面
wp_safe_redirect( $redirect_url );
exit;
?>
