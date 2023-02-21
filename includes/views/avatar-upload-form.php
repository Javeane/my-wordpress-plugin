<?php
/**
 * Avatar Upload Form
 *
 * This file is used to display the avatar upload form.
 *
 * @package My_WordPress_Plugin
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_user = wp_get_current_user();
?>

<div id="avatar-upload-form">
    <h2><?php _e( 'Upload Your Avatar', 'my-wordpress-plugin' ); ?></h2>

    <?php if ( isset( $_GET['success'] ) && $_GET['success'] == 1 ) : ?>
        <div class="notice notice-success">
            <p><?php _e( 'Your avatar has been uploaded successfully!', 'my-wordpress-plugin' ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( isset( $_GET['error'] ) ) : ?>
        <div class="notice notice-error">
            <p><?php echo esc_html( $_GET['error'] ); ?></p>
        </div>
    <?php endif; ?>

    <form id="avatar-upload" method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
        <input type="hidden" name="action" value="upload_avatar">
        <input type="hidden" name="user_id" value="<?php echo esc_attr( $current_user->ID ); ?>">

        <table class="form-table">
            <tr>
                <th>
                    <label for="avatar"><?php _e( 'Avatar', 'my-wordpress-plugin' ); ?></label>
                </th>
                <td>
                    <input type="file" name="avatar" id="avatar" accept="image/*">
                    <p class="description"><?php _e( 'Please choose a picture of yourself to use as your avatar.', 'my-wordpress-plugin' ); ?></p>
                </td>
            </tr>
        </table>

        <?php wp_nonce_field( 'upload_avatar', 'upload_avatar_nonce' ); ?>
        <?php submit_button( __( 'Upload', 'my-wordpress-plugin' ), 'primary', 'submit', false ); ?>
    </form>
</div>
