# My Wordpress Plugin Introduction

## **Describe**

My WordPress Plugin is a WordPress plugin that adds user registration, login URL customization, and email verification features to Wordpress. It also includes social account login, user-defined avatar upload, and front-end CSS customization.

## **Feature**

On the basis of customizing user registration and login URLs, My WordPress Plugin also hides the default user login address and background management entry URL of Wordpress to enhance the security of Wordpress sites.
At the same time, My WordPress Plugin also provides Wordpress sites with functional configurations that allow users to log in with common social platform accounts such as Google, Microsoft, Tiktok, Twitter, and Facebook, and adds a Captcha security verification function for user registration and login.
My WordPress Plugin also provides SMTP mail service configuration function for Wordpress site.

## **Install**

Upload the My-Wordpress-Plugin folder to the /wp-content/plugins/ directory and activate the plugin through the Plugins menu in WordPress. Use the [my_wp_plugin] shortcode to display a signup form on the page.

## **Frequently Asked Questions**

### How to change front-end CSS？

You can edit the frontend-style.css file located in the /includes/frontend/css/ directory.
Alternatively, you can create your own CSS file and enqueue it using the wp_enqueue_style() function.

### · How to enable social login?

To enable social logins for your Wordpress site, first you must create an API key for the social network you want to support. Then, go to my WordPress plugin settings page and enter the API key for each social network.

### · How to customize the email template?

You can edit the email-template.php file located in the /includes/email/ directory. Alternatively, you can use a plugin like WP HTML Mail to create and customize email templates.

## **Changelog**

1.0.0

Initial release.

## **Upgrade Notice**

None at this time.

# My Wordpress Plugin 简介

## **描述**

My WordPress Plugin 是一个 WordPress插件，它为 Wordpress 增用户注册、登录 URL 自定义和电子邮件验证的功能，它还包括社交账号登录、用户自定义头像上传和前端 CSS 自定义。

## **特征**

My WordPress Plugin在实现自定义用户注册、登录URL的基础上，还实现了隐藏Wordpress默认的用户登录地址和后台管理入口URL，以增强Wordpress站点的安全性。
同时，My WordPress Plugin还为 Wordpress 站点提供了让用户使用Google、Microsoft、Tiktok、Twitter Facebook等常用社交平台账号登录的功能配置，并为用户注册登录加入了 Captcha 安全验证功能。
My WordPress Plugin 也为Wordpress站点提供了SMTP邮件服务配置功能。

## **安装**

将 My-Wordpress-Plugin 文件夹上传到 /wp-content/plugins/ 目录，通过 WordPress 中的“插件”菜单激活插件。 使用 [my_wp_plugin] 短代码在页面上显示注册表单。 

## **常见问题解答**

### ·如何更改前端CSS

您可以编辑位于 /includes/frontend/css/ 目录中的 frontend-style.css 文件。 
或者，您可以创建自己的 CSS 文件并使用 wp_enqueue_style() 函数将其加入队列。

### ·如何启用社交登录？

要为Wordpress站点启用社交账号登录，首先您必须为要支持的社交网络创建 API 密钥。 然后，转到我的 WordPress 插件设置页面并输入每个社交网络的 API 密钥。

### ·如何自定义电子邮件模板？

您可以编辑位于 /includes/email/ 目录中的 email-template.php 文件。 或者，您可以使用 WP HTML Mail 之类的插件来创建和自定义电子邮件模板。

### **变更日志**

1.0.0

初始发行。

### **升级通知**

目前没有。


#  **My Wordpress Plugin 插件功能概述**

My Wordpress Plugin 是一个优化 Wordpress 基础功能的插件，希望通过代码的复用和减少插件数量，从而提升的运行效率。

插件包括以下功能：

1、为 Wordpress 提供登录 url 自定义功能，隐藏 wp-login.php 、wp-admin 登录地址并将其浏览器的直接访问导向 404 页面；

2、为 Wordpress 新用户注册新增 密码 和 密码确认 两项表单；

3、为 Wordpress 提供使用 Google Microsoft Tiktok Twitter Facebook 社交账号登录功能；

4、为 Wordpress 新用户注册及用户登录加入 Captcha 图形化数字验证功能；

5、为 Wordpress 新用户注册及用户账户信息管理提供自定义头像上传功能；

6、为 Wordpress 提供 SMTP 邮件服务配置、测试和衔接 Wordpress 邮件信息推送的功能。


# **My Wordpress Plugin 的插件文件目录结构**

my-wordpress-plugin/
├── includes/				 				 —包含插件的主要功能，分为后台和前台代码。
│   ├── admin/			 					—包含插件后台管理页面的代码逻辑，包括自定义 WordPress 菜单和插件设置页面的代码逻辑。
│   │   ├── admin.php					// 后台管理页面的主要代码逻辑
│   │   ├── menu.php					// 自定义 WordPress 菜单的代码逻辑
│   │   └── settings.php				// 插件的设置页面代码逻辑
│   ├── public/								—包含插件前台主要功能的代码逻辑，包括插件的短代码和前端显示的代码逻辑。
│   │   ├── public.php					// 插件的前台主要代码逻辑
│   │   └── shortcode.php			// 插件的短代码代码逻辑 
│   │   └── display.php					// 插件的前端显示的代码逻辑
│   ├── core/								—包含插件的主要功能代码逻辑，包括登录、注册、社交登录和用户验证的代码逻辑。
│   │   ├── login.php						// 插件的登录功能代码逻辑
│   │   ├── register.php					// 插件的注册功能代码逻辑
│   │   ├── social-login.php			// 插件的社交登录功能代码逻辑
│   │   └── verification.php			// 插件的用户验证功能代码逻辑
│   ├── email/									—包含插件的邮件发送和邮件模板的代码逻辑。
│   │   ├── mailer.php						// 插件的邮件发送功能代码逻辑
│   │   └── email-template.php		// 插件的邮件模板代码逻辑
│   ├── models/							 	—包含插件的用户模型和邮件验证模型的代码逻辑。
│   │   ├── user.php						// 插件的用户模型代码逻辑
│   │   └── email-verification.php	// 插件的邮件验证模型代码逻辑
│   ├── views/									—包含插件的各种表单的代码逻辑，包括登录表单、注册表单、社交登录表单、用户验证表单和用户头像上传表单的代码逻辑。
│   │   ├── login-form.php					// 插件的登录表单代码逻辑
│   │   ├── register-form.php				// 插件的注册表单代码逻辑
│   │   ├── social-login.php				// 插件的社交登录表单代码逻辑
│   │   ├── verification-form.php		// 插件的用户验证表单代码逻辑
│   │   └── avatar-upload-form.php	// 插件的用户头像上传表单代码逻辑
│   │   └── frontend-style.php
│   ├── frontend/
│   │   ├── css/
│   │   │    └── frontend-style.css
│   │   └── js/
│   │        └── frontend-script.js
│   ├── upload/								—包含插件的用户头像上传功能的代码逻辑。
│   │   └── avatar-upload.php			// 插件的用户头像上传功能代码逻辑
│   └── ajax-handler.php					// 插件的 Ajax 请求处理代码逻辑
├── assets/										—包含插件的 CSS 和 JavaScript 文件。
│   ├── css/									—包含插件前台和后台的 CSS 样式表。
│   │   ├── style.css							// 插件的前台 CSS 样式表
│   │   └── admin-style.css					// 插件的后台 CSS 样式表
│   └── js/											 —包含插件前台和后台的 JavaScript 代码。
│       ├── main.js								// 插件的前台 JavaScript 代码
│       └── admin.js							// 插件的后台 JavaScript 代码
├── templates/								—包含插件的各种模板文件，包括登录模板、注册模板、用户验证模板、社交登录模板和用户头像上传模板。
│   ├── login.php							// 插件的登录模板文件
│   ├── register.php						// 插件的注册模板文件
│   ├── verification.php				// 插件的用户验证模板文件
│   ├── social-login.php				// 插件的社交登录模板文件
│   └── avatar-upload.php			// 插件的用户头像上传模板文件
├── phpmailer/							 —包含 PHPMailer 库的代码，供插件设置页面调用。
│    ├── class.phpmailer.php		// 包含PHPMailer代码供settings.php文件调用
│    └── class.smtp.php					// SMTP 邮件服务相关
├── languages/							—包含插件的语言翻译文件。
│   └── my-wordpress-plugin.pot		// 插件的语言翻译文件
├── my-wordpress-plugin.php			// 插件的主要文件，包含插件的基本信息和加载插件所需的函数。
├── uninstall.php
└── README.md						// 插件的说明文档。