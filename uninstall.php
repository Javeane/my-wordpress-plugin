<?php
/**
 * My Wordpress Plugin Uninstall
 *
 * WordPress 插件卸载时执行的操作
 *
 * @package   My_Wordpress_Plugin
 * @category  Uninstall
 * @license   GPL-2.0+
 */

// 如果该文件不是被 WordPress 加载，则禁止访问
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

<?php
// 如果该文件被直接调用，则退出程序
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

//第一部分：删除插件创建的数据库表

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Check if it is a multisite WordPress. //检查插件数据
if ( is_multisite() ) {

    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

    if ( $blog_ids ) {
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            // Delete plugin data for each site.
            delete_option( 'my_wp_plugin_option' );
            // delete custom tables for each site if any.
            // $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}my_wp_plugin_table" );
        }
        restore_current_blog();
    }
} else {
    // Delete plugin data for single site.
    delete_option( 'my_wp_plugin_option' );
    // delete custom tables if any.
    // global $wpdb;
    // $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}my_wp_plugin_table" );
}

// Delete plugin options.
delete_option( 'my_wp_plugin_option' );

// Delete user meta data if any.
// delete_metadata( 'user', 0, 'my_wp_plugin_user_meta', '', true );

// Remove the user meta data from all users if any.
// global $wpdb;
// $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key = 'my_wp_plugin_user_meta'" );


// 定义卸载函数
function my_plugin_uninstall() {
    global $wpdb;

    // 删除插件创建的数据表
    $table_name = $wpdb->prefix . 'my_plugin_table';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}

// 调用卸载函数
my_plugin_uninstall();

//第二部分：删除插件创建的文件夹及其内容

<?php
// 防止直接访问文件
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// 删除插件创建的文件夹及其内容
$dir = plugin_dir_path(__FILE__) . 'upload/';

// 确保要删除的目录存在
if (is_dir($dir)) {
    // 删除目录及其所有内容
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteDirectory("$dir/$file") : unlink("$dir/$file");
    }
    rmdir($dir);
}

//第三部分：删除插件的设置选项

<?php
// 如果插件被激活，则删除插件的设置选项
if (get_option('my_wordpress_plugin_installed')) {
    delete_option('my_wordpress_plugin_installed');
}
