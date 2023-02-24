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
