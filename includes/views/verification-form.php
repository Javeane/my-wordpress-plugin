<?php
/**
 * Verification form template
 *
 * This template is used to display the user verification form.
 *
 * @package My_WordPress_Plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="my-plugin-form-group">
    <label for="verification_code"><?php esc_html_e( 'Verification Code', 'my-wordpress-plugin' ); ?> <span class="required">*</span></label>
    <input type="text" id="verification_code" name="verification_code" required>
</div>

<div class="my-plugin-form-group">
    <img id="verification_code_image" src="<?php echo esc_url( $verification_code_image_url ); ?>" alt="<?php esc_attr_e( 'Verification Code Image', 'my-wordpress-plugin' ); ?>">
    <button type="button" id="regenerate_verification_code"><?php esc_html_e( 'Regenerate', 'my-wordpress-plugin' ); ?></button>
</div>

<div class="my-plugin-form-group">
    <button type="submit" id="submit_verification_form" disabled><?php esc_html_e( 'Submit', 'my-wordpress-plugin' ); ?></button>
</div>

<script>
    jQuery( document ).ready( function() {
        var is_form_ready = false;

        function check_form_ready() {
            if ( jQuery( '#verification_code' ).val() ) {
                is_form_ready = true;
                jQuery( '#submit_verification_form' ).prop( 'disabled', false );
            } else {
                is_form_ready = false;
                jQuery( '#submit_verification_form' ).prop( 'disabled', true );
            }
        }

        function generate_verification_code() {
            var verification_code = '';
            var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            for ( var i = 0; i < 6; i++ ) {
                verification_code += possible.charAt( Math.floor( Math.random() * possible.length ) );
            }

            return verification_code;
        }

        function refresh_verification_code_image() {
            var verification_code = generate_verification_code();
            var verification_code_image_url = '<?php echo esc_url( admin_url( 'admin-ajax.php?action=my_plugin_generate_verification_code_image' ) ); ?>' + '&code=' + verification_code;

            jQuery( '#verification_code' ).val( '' );
            jQuery( '#verification_code_image' ).attr( 'src', verification_code_image_url );
            jQuery( '#verification_code_image' ).attr( 'alt', verification_code );
        }

        jQuery( '#verification_code' ).on( 'input', function() {
            check_form_ready();
        } );

        jQuery( '#submit_verification_form' ).on( 'click', function() {
            if ( ! is_form_ready ) {
                alert( '<?php esc_html_e( 'Please fill in all required fields.', 'my-wordpress-plugin' ); ?>' );
                return false;
            }
        } );

        jQuery( '#regenerate_verification_code' ).on( 'click', function() {
            refresh_verification_code_image();
        } );

        refresh_verification_code_image();
    } );
</script>
