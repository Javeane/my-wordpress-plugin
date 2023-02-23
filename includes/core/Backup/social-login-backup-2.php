<?php
/**
 * Social Login Class.
 *
 * This class handles the social login functionality.
 *
 * @since 1.0.0
 */

class My_WP_Social_Login {

    private $social_login_providers;

    public function __construct() {
        $this->social_login_providers = array(
            'google'   => array(
                'label' => __( 'Google', 'my-wp-plugin' ),
                'url'   => 'https://accounts.google.com/o/oauth2/auth',
            ),
            'microsoft' => array(
                'label' => __( 'Microsoft', 'my-wp-plugin' ),
                'url'   => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
            ),
            'tiktok' => array(
                'label' => __( 'Tiktok', 'my-wp-plugin' ),
                'url'   => 'https://open-api.tiktok.com/platform/oauth/connect',
            ),
            'twitter'  => array(
                'label' => __( 'Twitter', 'my-wp-plugin' ),
                'url'   => 'https://api.twitter.com/oauth/authenticate',
            ),
            'facebook' => array(
                'label' => __( 'Facebook', 'my-wp-plugin' ),
                'url'   => 'https://www.facebook.com/v12.0/dialog/oauth',
            ),
        );

        add_action( 'login_form', array( $this, 'add_social_login_buttons' ) );
        add_action( 'wp_ajax_nopriv_my_wp_social_login', array( $this, 'social_login' ) );
        add_action( 'wp_ajax_my_wp_social_login', array( $this, 'social_login' ) );
        add_filter( 'authenticate', array( $this, 'social_login_authenticate' ), 10, 3 );
    }

    public function add_social_login_buttons( $args ) {
        if ( $this->social_login_providers ) {
            foreach ( $this->social_login_providers as $provider => $data ) {
                $class = 'button button-' . $provider . ' my-wp-social-login-button';
                printf( '<a href="%s" class="%s">%s</a>',
                    esc_url( wp_login_url() . '?loginSocial=' . $provider ),
                    esc_attr( $class ),
                    esc_html( $data['label'] )
                );
            }
        }
    }

    public function social_login( $args ) {
        $social_login_provider = isset( $_REQUEST['provider'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['provider'] ) ) : '';

        if ( ! $this->is_valid_provider( $social_login_provider ) ) {
            return;
        }

        $this->set_provider_session( $social_login_provider );
        wp_safe_redirect( wp_login_url() );
        exit;
    }

    public function social_login_authenticate( $user, $username, $password ) {
        if ( empty( $username )
            return $user;
        }
        $social_login_provider = $this->get_provider_session();

    if ( empty( $social_login_provider ) ) {
        return $user;
    }

    if ( ! $this->is_valid_provider( $social_login_provider ) ) {
        return $user;
    }

    $social_login_user_id = get_user_meta( $user->ID, 'social_login_user_id', true );

    if ( empty( $social_login_user_id ) ) {
        add_user_meta( $user->ID, 'social_login_user_id', $social_login_provider . '_' . $username );
    }

    return $user;
}

public function social_login_register( $user_id ) {
    $social_login_provider = $this->get_provider_session();

    if ( empty( $social_login_provider ) ) {
        return;
    }

    if ( ! $this->is_valid_provider( $social_login_provider ) ) {
        return;
    }

    $username = '';

    if ( ! empty( $_SESSION['social_login_username'] ) ) {
        $username = sanitize_user( $_SESSION['social_login_username'], true );
        unset( $_SESSION['social_login_username'] );
    }

    if ( empty( $username ) ) {
        $username = $social_login_provider . '_' . $user_id;
    }

    $user_data = array(
        'ID'         => $user_id,
        'user_login' => $username,
    );

    wp_update_user( $user_data );

    add_user_meta( $user_id, 'social_login_user_id', $social_login_provider . '_' . $username );
    }

public function is_valid_provider( $provider ) {
    return in_array( $provider, $this->providers, true );
    }

public function set_provider_session( $provider ) {
    $_SESSION['social_login_provider'] = $provider;
    }

public function get_provider_session() {
    return isset( $_SESSION['social_login_provider'] ) ? sanitize_text_field( wp_unslash( $_SESSION['social_login_provider'] ) ) : '';
}
