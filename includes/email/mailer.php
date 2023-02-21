<?php
namespace My_WP_Plugin\Includes\Email;

class Mailer {
    /**
     * The email sender.
     *
     * @var string
     */
    private $from_email;

    /**
     * The email recipient(s).
     *
     * @var string|array
     */
    private $to_email;

    /**
     * The email subject.
     *
     * @var string
     */
    private $subject;

    /**
     * The email message body.
     *
     * @var string
     */
    private $body;

    /**
     * Additional headers for the email.
     *
     * @var array
     */
    private $headers;

    /**
     * Constructor.
     *
     * @param string       $to_email The recipient(s) of the email.
     * @param string       $subject  The subject of the email.
     * @param string       $body     The message body of the email.
     * @param string       $from_email Optional. The sender of the email.
     * @param array|string $headers Optional. Additional headers for the email.
     */
    public function __construct( $to_email, $subject, $body, $from_email = '', $headers = '' ) {
        $this->from_email = $from_email;
        $this->to_email   = $to_email;
        $this->subject    = $subject;
        $this->body       = $body;
        $this->headers    = $headers;
    }

    /**
     * Send the email.
     *
     * @return bool Whether the email was sent successfully.
     */
    public function send() {
        // Check for required properties.
        if ( empty( $this->from_email ) || empty( $this->to_email ) || empty( $this->subject ) || empty( $this->body ) ) {
            return false;
        }

        // Set the headers.
        $headers = $this->headers;

        // Set the from email header if not set.
        if ( ! is_array( $headers ) ) {
            $headers = array();
        }

        if ( ! array_key_exists( 'From', $headers ) ) {
            $headers['From'] = $this->from_email;
        }

        // Set the content type header.
        if ( ! array_key_exists( 'Content-Type', $headers ) ) {
            $headers['Content-Type'] = 'text/html; charset=UTF-8';
        }

        // Set the email recipients.
        $to_email = $this->to_email;

        if ( is_array( $to_email ) ) {
            $to_email = implode( ',', $to_email );
        }

        // Send the email.
        $result = wp_mail( $to_email, $this->subject, $this->body, $headers );

        return $result;
    }
}
