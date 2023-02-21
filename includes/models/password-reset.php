<?php

/**
 * Generates and validates the password reset token for a given user.
 */
class Password_Reset {

    /**
     * Generates a new unique password reset token and stores it in the database.
     *
     * @param int $user_id The ID of the user whose password is being reset.
     * @return string The generated password reset token.
     */
    public static function generate_token($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'password_reset_tokens';

        // Generate a new unique token
        $token = bin2hex(random_bytes(20));

        // Insert the token into the database along with the user ID and the expiration date
        $expiration = date('Y-m-d H:i:s', strtotime('+1 day'));
        $wpdb->insert($table_name, array(
            'user_id' => $user_id,
            'token' => $token,
            'expiration' => $expiration
        ));

        return $token;
    }

    /**
     * Validates the given password reset token and returns the user ID associated with it.
     * If the token is invalid or has expired, returns false.
     *
     * @param string $token The password reset token to validate.
     * @return int|false The ID of the user associated with the token, or false if the token is invalid.
     */
    public static function validate_token($token) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'password_reset_tokens';

        // Get the token and user ID from the database
        $result = $wpdb->get_row($wpdb->prepare("SELECT user_id, expiration FROM $table_name WHERE token = %s", $token));

        // Check if the token exists and has not expired
        if ($result && strtotime($result->expiration) >= time()) {
            return $result->user_id;
        } else {
            return false;
        }
    }

    /**
     * Deletes the password reset token associated with the given user ID.
     *
     * @param int $user_id The ID of the user whose token should be deleted.
     */
    public static function delete_token($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'password_reset_tokens';
        $wpdb->delete($table_name, array('user_id' => $user_id));
    }
}

<?php
/**
 * Handles the password reset functionality.
 */
class Password_Reset {
  /**
   * Resets the password for the given user ID.
   *
   * @param int $user_id User ID.
   * @param string $new_password New password.
   * @return bool True on success, false on failure.
   */
  public static function reset_password($user_id, $new_password) {
    // Generate a new password hash.
    $password_hash = wp_hash_password($new_password);

    // Update the user's password in the database.
    $result = wp_update_user(array(
      'ID' => $user_id,
      'user_pass' => $password_hash
    ));

    // Return true on success, false on failure.
    return !is_wp_error($result);
  }
}
