Description
My WordPress Plugin is a plugin for WordPress that adds functionality for user registration, login, and email verification. It also includes social login, avatar upload, and frontend CSS customization.

Features
User registration with email verification
User login with email and password or social media accounts
Social login support for Google, Facebook, Twitter, and LinkedIn
Avatar upload and cropping
Frontend CSS customization

Installation
Upload the my-wordpress-plugin folder to the /wp-content/plugins/ directory.
Activate the plugin through the 'Plugins' menu in WordPress.
Use the [my_wp_plugin] shortcode to display the registration form on a page.
Frequently Asked Questions
How do I change the frontend CSS?
You can edit the frontend-style.css file located in the /includes/frontend/css/ directory. Alternatively, you can create your own CSS file and enqueue it using the wp_enqueue_style() function.

How do I enable social login?
You must first create API keys for the social networks you want to support. Then, go to the My WordPress Plugin settings page and enter the API keys for each social network.

How do I customize the email templates?
You can edit the email-template.php file located in the /includes/email/ directory. Alternatively, you can use a plugin like WP HTML Mail to create and customize email templates.

Changelog
1.0.0
Initial release.
Upgrade Notice
None at this time.


My Wordpress Plugin 是一个优化 Wordpress 基础功能的插件，希望通过代码的复用和减少插件数量，从而提升的运行效率。

插件包括以下功能：

1、为 Wordpress 提供登录 url 自定义功能，隐藏 wp-login.php 、wp-admin 登录地址并将其浏览器的直接访问导向 404 页面；

2、为 Wordpress 新用户注册新增 密码 和 密码确认 两项表单；

3、为 Wordpress 提供使用 Google Microsoft Tiktok Twitter Facebook 社交账号登录功能；

4、为 Wordpress 新用户注册及用户登录加入 Captcha 图形化数字验证功能；

5、为 Wordpress 新用户注册及用户账户信息管理提供自定义头像上传功能；

6、为 Wordpress 提供 SMTP 邮件服务配置、测试和衔接 Wordpress 邮件信息推送的功能。
