<?php
/**
 * Email Template
 *
 * Provides the HTML template for the email.
 *
 * @package My_Wordpress_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Email Template Class
 */
class My_Wordpress_Plugin_Email_Template {

	/**
	 * Email recipient.
	 *
	 * @var string
	 */
	protected $recipient;

	/**
	 * Email subject.
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Email message.
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Email headers.
	 *
	 * @var string
	 */
	protected $headers;

	/**
	 * Constructor
	 *
	 * @param string $recipient Email recipient.
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 * @param string $headers Email headers.
	 */
	public function __construct( $recipient, $subject, $message, $headers ) {
		$this->recipient = $recipient;
		$this->subject   = $subject;
		$this->message   = $message;
		$this->headers   = $headers;
	}

	/**
	 * Get email body.
	 *
	 * @return string
	 */
	protected function get_body() {
		ob_start();
		?>
		<!-- Replace with your email HTML template -->
		<!doctype html>
		<html>
			<head>
				<meta charset="utf-8">
				<title><?php echo esc_html( $this->subject ); ?></title>
			</head>
			<body>
				<div>
					<h2><?php esc_html_e( 'Dear', 'my-wordpress-plugin' ); ?> <?php echo esc_html( $this->recipient ); ?>,</h2>
					<p><?php echo wp_kses_post( $this->message ); ?></p>
					<p><?php esc_html_e( 'Regards,', 'my-wordpress-plugin' ); ?></p>
				</div>
			</body>
		</html>
		<?php
		return ob_get_clean();
	}

	/**
	 * Send email.
	 *
	 * @return bool Whether the email was sent successfully.
	 */
	public function send() {
		add_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ) );
		$sent = wp_mail( $this->recipient, $this->subject, $this->get_body(), $this->headers );
		remove_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ) );
		return $sent;
	}

	/**
	 * Set email content type to HTML.
	 *
	 * @return string
	 */
	public function set_email_content_type() {
		return 'text/html';
	}
}
