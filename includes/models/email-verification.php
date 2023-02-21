<?php
/**
 * My WordPress Plugin Email Verification Model
 *
 * @package MyWordPressPlugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * My_WordPress_Plugin_Email_Verification class.
 */
class My_WordPress_Plugin_Email_Verification {

	/**
	 * Verification token lifetime (in seconds).
	 *
	 * @var int
	 */
	private $token_lifetime = 3600;

	/**
	 * Initialization method.
	 */
	public function init() {
		add_action( 'user_register', array( $this, 'send_verification_email' ) );
		add_action( 'wp_ajax_verify_email', array( $this, 'verify_email' ) );
	}

	/**
	 * Sends a verification email to the newly registered user.
	 *
	 * @param int $user_id User ID.
	 */
	public function send_verification_email( $user_id ) {
		$user = get_user_by( 'ID', $user_id );
		$token = $this->create_token( $user_id );
		$verification_url = add_query_arg(
			array(
				'action' => 'verify_email',
				'user_id' => $user_id,
				'token'   => $token,
			),
			site_url( '/' )
		);
		$subject = 'Please verify your email address';
		$message = sprintf( 'Click this link to verify your email address: %s', $verification_url );
		$headers = 'Content-Type: text/html; charset=UTF-8';
		wp_mail( $user->user_email, $subject, $message, $headers );
	}

	/**
	 * Creates a verification token for the given user ID.
	 *
	 * @param int $user_id User ID.
	 *
	 * @return string Verification token.
	 */
	public function create_token( $user_id ) {
		$token = wp_generate_password( 32, false );
		$this->store_token( $user_id, $token );
		return $token;
	}
/**
 * Stores the verification token in the database for the given user ID.
 *
 * @param int    $user_id User ID.
 * @param string $token   Verification token.
 */
public function store_token( $user_id, $token ) {
	update_user_meta( $user_id, 'email_verification_token', $token );
	update_user_meta( $user_id, 'email_verification_token_created_at', time() );
}

/**
 * Verifies the email address for the given user ID and token.
 *
 * @param int $user_id The ID of the user whose email address is being verified.
 * @param string $token The verification token.
 *
 * @return bool True if the email address was successfully verified, false otherwise.
 */
public function verify_email( $user_id, $token ) {
	$stored_token = get_user_meta( $user_id, 'email_verification_token', true );
	if ( $stored_token === $token ) {
		$token_creation_time = get_user_meta( $user_id, 'email_verification_token_created_at', true );
		$time_difference = time() - $token_creation_time;
		if ( $time_difference <= $this->token_lifetime ) {
			update_user_meta( $user_id, 'email_verified', true );
			delete_user_meta( $user_id, 'email_verification_token' );
			delete_user_meta( $user_id, 'email_verification_token_created_at' );
			return true;
		}
	}
	return false;
}
