<?php
/**
 * The template for displaying social login buttons
 *
 * This template can be overridden by copying it to yourtheme/wpum/social-login.php.
 * @package My_Wordpress_Plugin
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$enabled_providers = wpum_get_option( 'social_login_providers' );

if ( ! $enabled_providers ) {
    return;
}

$social_login_providers = wpum_get_social_login_providers();

foreach ( $social_login_providers as $provider => $settings ) {
    if ( ! in_array( $provider, $enabled_providers, true ) ) {
        continue;
    }

    $provider_name = isset( $settings['label'] ) ? $settings['label'] : ucfirst( $provider );
    $provider_icon = isset( $settings['icon'] ) ? $settings['icon'] : $provider;
    $login_url = wpum_get_provider_login_url( $provider );

    switch ($provider) {
        case 'google':
            $provider_name = 'Google';
            $provider_icon = '<i class="fab fa-google"></i>';
            break;
        case 'microsoft':
            $provider_name = 'Microsoft';
            $provider_icon = '<i class="fab fa-windows"></i>';
            break;
        case 'tiktok':
            $provider_name = 'TikTok';
            $provider_icon = '<i class="fab fa-tiktok"></i>';
            break;
        case 'twitter':
            $provider_name = 'Twitter';
            $provider_icon = '<i class="fab fa-twitter"></i>';
            break;
        case 'facebook':
            $provider_name = 'Facebook';
            $provider_icon = '<i class="fab fa-facebook-f"></i>';
            break;
        default:
            break;
    }

    ?>
    <a href="<?php echo esc_url( $login_url ); ?>" class="wpum-social-login-provider wpum-social-login-<?php echo esc_attr( $provider ); ?>" title="<?php printf( __( 'Log in with %s', 'wpum' ), $provider_name ); ?>">
        <span class="wpum-social-login-icon"><?php echo $provider_icon; ?></span>
        <span class="wpum-social-login-label"><?php echo $provider_name; ?></span>
    </a>
    <?php
}
