<?php
/**
 * This file contains the Verification class that is responsible for verifying the plugin's license key.
 *
 * @since      1.0.0
 * @package    My_WordPress_Plugin
 * @subpackage My_WordPress_Plugin/includes/core
 */

namespace My_WordPress_Plugin\Core;

use My_WordPress_Plugin\Admin\Notices;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Verification class.
 *
 * This class is responsible for verifying the plugin's license key.
 *
 * @since  1.0.0
 */
class Verification {
    /**
     * The API endpoint.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $api_endpoint    The API endpoint.
     */
    private $api_endpoint = 'https://example.com/api/license/verify';

    /**
     * Verify the license key.
     *
     * @since  1.0.0
     * @access public
     * @param  string    $license_key    The license key to verify.
     * @return bool                     True if the license key is valid, false otherwise.
     */
    public function verify_license_key( $license_key ) {
        // Check if license key is valid.
        if ( empty( $license_key ) ) {
            return false;
        }

        // Build the query arguments.
        $query_args = array(
            'license_key' => $license_key,
            'site_url'    => get_site_url(),
        );

        // Send the request to the API.
        $response = wp_remote_get( add_query_arg( $query_args, $this->api_endpoint ) );

        // Check if request was successful.
        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return false;
        }

        // Get the response body.
        $body = wp_remote_retrieve_body( $response );

        // Parse the response body.
        $parsed_body = json_decode( $body );

        // Check if the response body could be parsed.
        if ( empty( $parsed_body ) || ! is_object( $parsed_body ) ) {
            return false;
        }

        // Check if the license key is valid.
        if ( $parsed_body->license_valid !== true ) {
            // Display an error notice.
            Notices::add_error( __( 'Invalid license key. Please enter a valid license key.', 'my-wordpress-plugin' ) );

            return false;
        }

        // Check if the license key is active.
        if ( $parsed_body->license_active !== true ) {
            // Display an error notice.
            Notices::add_error( __( 'Inactive license key. Please activate your license key.', 'my-wordpress-plugin' ) );

            return false;
        }

        // Check if the license key is expired.
        if ( ! empty( $parsed_body->license_expiration_date ) && strtotime( $parsed_body->license_expiration_date ) < time() ) {
            // Display an error notice.
            Notices::add_error( __( 'Expired license key. Please renew your license key.', 'my-wordpress-plugin' ) );

            return false;
        }

        // License key is valid.
        return true;
    }
}
