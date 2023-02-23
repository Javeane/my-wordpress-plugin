describe

My WordPress Plugin is a WordPress plugin that adds user registration, login URL customization, and email verification features to Wordpress. It also includes social account login, user-defined avatar upload, and front-end CSS customization.

feature
On the basis of customizing user registration and login URLs, My WordPress Plugin also hides the default user login address and background management entry URL of Wordpress to enhance the security of Wordpress sites.
At the same time, My WordPress Plugin also provides Wordpress sites with functional configurations that allow users to log in with common social platform accounts such as Google, Microsoft, Tiktok, Twitter, and Facebook, and adds a Captcha security verification function for user registration and login.
My WordPress Plugin also provides SMTP mail service configuration function for Wordpress site.

Install

Upload the My-Wordpress-Plugin folder to the /wp-content/plugins/ directory and activate the plugin through the Plugins menu in WordPress. Use the [my_wp_plugin] shortcode to display a signup form on the page.

Frequently Asked Questions

How to change front-end CSS？

You can edit the frontend-style.css file located in the /includes/frontend/css/ directory.
Alternatively, you can create your own CSS file and enqueue it using the wp_enqueue_style() function.

· How to enable social login?

To enable social logins for your Wordpress site, first you must create an API key for the social network you want to support. Then, go to my WordPress plugin settings page and enter the API key for each social network.

· How to customize the email template?

You can edit the email-template.php file located in the /includes/email/ directory. Alternatively, you can use a plugin like WP HTML Mail to create and customize email templates.

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
