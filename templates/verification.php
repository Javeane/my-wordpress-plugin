<?php
/**
 * Verification template for My Wordpress Plugin
 *
 * This template is responsible for displaying the verification page for the user.
 *
 * @package My_Wordpress_Plugin
 * @subpackage Templates
 * @since 1.0.0
 */

//显示验证提示信息

// Get current user ID.
$user_id = get_current_user_id();

// If user is not logged in or already verified, redirect to home page.
if ( ! $user_id || get_user_meta( $user_id, 'verified', true ) ) {
    wp_redirect( home_url() );
    exit;
}

// Get user's email.
$user_email = get_user_meta( $user_id, 'email', true );

// Get verification token from query string.
$token = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';

// Verify token.
$token_verified = verify_user_token( $user_id, $token );

get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php if ( $token_verified ) : ?>
                    <p><?php esc_html_e( 'Your email has been verified. Thank you!', 'my-wordpress-plugin' ); ?></p>
                <?php else : ?>
                    <p><?php esc_html_e( 'There was an error verifying your email. Please try again.', 'my-wordpress-plugin' ); ?></p>
                <?php endif; ?>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> -->
    </main><!-- #main -->
</div><!-- #primary -->
<?php
get_footer();

//验证成功或失败后的提示信息显示

$verification_result = filter_input( INPUT_GET, 'verification_result', FILTER_SANITIZE_STRING );
?>

<div class="verification-container">
  <?php if ( $verification_result === 'success' ) : ?>
    <p class="verification-success">您的账户已成功验证！</p>
  <?php elseif ( $verification_result === 'failure' ) : ?>
    <p class="verification-failure">抱歉，您的账户验证失败，请联系管理员解决问题。</p>
  <?php endif; ?>
</div>

//跳转到其他页面的功能

<?php
// 获取插件的设置页面 URL
$settings_url = admin_url('admin.php?page=my-wordpress-plugin-settings');

// 如果验证成功，则跳转到设置页面
if ($verification_result === true) {
    $message = '您的邮箱验证成功！';
    $redirect_url = $settings_url;
// 如果验证失败，则跳转到注册页面
} else {
    $message = '验证失败，请重新注册！';
    $redirect_url = site_url('/wp-login.php?action=register');
}

// 显示提示信息
echo '<p>' . $message . '</p>';

// 跳转到指定的页面
wp_redirect($redirect_url);
exit;
?>
