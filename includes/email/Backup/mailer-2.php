<?php
/**
 * Mailer Class
 *
 * @since      1.0.0
 * @package    my-wordpress-plugin
 * @subpackage my-wordpress-plugin/includes/email
 */

class My_Wp_Plugin_Mailer {

    public static function send_email($to, $subject, $template, $data) {
        $headers = array('Content-Type: text/html; charset=UTF-8');

        $template_content = self::get_template_content($template, $data);
        $email_content = self::replace_template_content($template_content, $data);

        wp_mail($to, $subject, $email_content, $headers);
    }

    public static function get_template_content($template, $data) {
        $template_path = MY_WP_PLUGIN_PATH . 'includes/email/templates/' . $template;
        $template_content = file_get_contents($template_path);
        $template_content = self::replace_template_content($template_content, $data);

        return $template_content;
    }

    public static function replace_template_content($content, $data) {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }
}
