<?php
// 安全校验
defined('ABSPATH') or die('No script kiddies please!');

// 添加插件设置菜单项
add_action('admin_menu', 'my_wordpress_plugin_add_admin_menu');

function my_wordpress_plugin_add_admin_menu() {
    add_menu_page(
        'My WordPress Plugin',             // 页面标题
        'My WP Plugin',                    // 菜单标题
        'manage_options',                  // 菜单权限
        'my_wordpress_plugin_settings',    // 菜单slug
        'my_wordpress_plugin_settings_page',// 显示的页面回调函数
        'dashicons-admin-generic'          // 菜单图标
    );
}

// 插件设置页面回调函数
function my_wordpress_plugin_settings_page() {
    // 检查用户权限
    if (!current_user_can('manage_options')) {
        return;
    }

    // 处理表单提交
    if (isset($_POST['my_wordpress_plugin_settings'])) {
        // 获取表单数据并进行安全过滤
        $login_url = sanitize_text_field($_POST['my_wordpress_plugin_login_url']);
        $register_enable_password_confirmation = isset($_POST['my_wordpress_plugin_register_enable_password_confirmation']) ? '1' : '0';
        $social_login_providers = isset($_POST['my_wordpress_plugin_social_login_providers']) ? sanitize_text_field($_POST['my_wordpress_plugin_social_login_providers']) : '';

        // 更新选项
        update_option('my_wordpress_plugin_login_url', $login_url);
        update_option('my_wordpress_plugin_register_enable_password_confirmation', $register_enable_password_confirmation);
        update_option('my_wordpress_plugin_social_login_providers', $social_login_providers);
    }

    // 获取选项值
    $login_url = get_option('my_wordpress_plugin_login_url', '');
    $register_enable_password_confirmation = get_option('my_wordpress_plugin_register_enable_password_confirmation', '0');
    $social_login_providers = get_option('my_wordpress_plugin_social_login_providers', '');

    // 插件设置页面 HTML
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('my_wordpress_plugin_settings'); ?>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="my_wordpress_plugin_login_url">自定义登录链接</label></th>
                        <td>
                            <input name="my_wordpress_plugin_login_url" type="url" id="my_wordpress_plugin_login_url" value="<?php echo esc_attr($login_url); ?>" class="regular-text">
                            <p class="description">在这里输入自定义的登录链接，留空则使用默认链接。</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>用户注册设置</label></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span>用户注册设置</span></legend>
                                <label>
                                    <input name="my_wordpress_plugin_register_enable_password_confirmation" type="checkbox" id="my_wordpress_plugin_register_enable_password_confirmation" value="1" <?php checked($register_enable_password_confirmation, '1'); ?>>
                                    开启密码确认
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="my_wordpress_plugin_social_login_providers">社交登录设置</label></th>
                        <td>
                            <input name="my_wordpress_plugin_social_login_providers" type="text" id="my_wordpress_plugin_social_login_providers" value="<?php echo esc_attr($social_login_providers); ?>" class="regular-text">
                            <p class="description">在这里输入启用的社交登录提供商，多个提供商之间用英文逗号分隔，如：facebook,twitter,linkedin。</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name
                