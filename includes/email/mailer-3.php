<?php
/**
 * Mailer Class
 *
 * @since      1.0.0
 * @package    my-wordpress-plugin
 * @subpackage my-wordpress-plugin/includes/email
 */

if (!class_exists('My_WP_Mailer')) {

    class My_WP_Mailer
    {

        public static function send($to, $subject, $content)
        {
            $headers = self::get_headers();
            $from_address = self::get_from_address();
            $template = self::get_template();
            $template_content = self::get_template_content($template, $content);

            $subject = apply_filters('my_wp_mailer_subject', $subject);
            $content = apply_filters('my_wp_mailer_content', $template_content);

            wp_mail($to, $subject, $content, $headers, $attachments = array());

            return true;
        }

        public static function get_from_address()
        {
            $from_address = get_option('my_wp_from_address');
            return $from_address;
        }

        public static function get_headers()
        {
            $from_address = self::get_from_address();
            $headers = array('From: ' . get_bloginfo('name') . ' <' . $from_address . '>');
            $headers = apply_filters('my_wp_mailer_headers', $headers);

            return $headers;
        }

        public static function get_template()
        {
            $template = get_option('my_wp_email_template');
            return $template;
        }

        public static function get_template_content($template, $content)
        {
            $template_path = MY_WP_PLUGIN_PATH . 'includes/email/templates/' . $template;
            $template_content = file_get_contents($template_path);
            $template_content = str_replace('{{content}}', $content, $template_content);

            return $template_content;
        }

        public static function replace_template_content($content, $data)
        {
            foreach ($data as $key => $value) {
                $content = str_replace('{{' . $key . '}}', $value, $content);
            }

            return $content;
