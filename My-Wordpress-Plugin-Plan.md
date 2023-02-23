<br>

# **Wordpress 插件 My Wordpress Plugin 的功能需求**<br>

My Wordpress Plugin 是一个优化 Wordpress 基础功能的插件，希望实现代码的复用性和减少 Wordpress 的插件数量，从而提升 Wordpress 的运行效率。<br>
具体而言，插件包括以下6个方面的主要功能：<br>
1、为 Wordpress 提供登录 url 自定义功能，隐藏 wp-login.php 、wp-admin 登录地址并将其浏览器的直接访问导向 404 页面；<br>
2、为 Wordpress 新用户注册新增 密码 和 密码确认 两项表单；<br>
3、为 Wordpress 提供使用 Google Microsoft Tiktok Twitter Facebook 社交账号登录功能；<br>
4、为 Wordpress 新用户注册及用户登录加入 captcha 图形化数字验证功能；<br>
5、为 Wordpress 新用户注册及用户账户信息管理提供自定义头像上传功能；<br>
6、为 Wordpress 提供 SMTP 邮件服务配置、测试和衔接 Wordpress 邮件信息推送的功能（类似 YaySMTP 插件）。<br>

上述功能参考自 Theme My Login、Ultimate Member、Profile Builder、UserPro、WP-Members、Login Designer、WP User Profile Avatar、WPS Hide Login、Social Login、Nextend Social Login and Register、Super Socializer、WP Social Login 等 WordPress 插件。<br>

# **Wordpress 插件 My Wordpress Plugin 的文件目录结构**

 my-wordpress-plugin/<br>    
├── includes/　　　　　　··包含插件的主要功能，分为后台和前台代码。<br>
│   ├── admin/　　　　　　··包含插件后台管理菜单和插件设置等页面的代码逻辑。<br>
│   │   ├── admin.php　　　// 后台管理页面的主要代码逻辑<br>
│   │   ├── menu.php　　　// 自定义 WordPress 菜单的代码逻辑<br>
│   │   └── settings.php　　// 插件的设置页面代码逻辑<br>
│   ├── public/　　　　　　··包含插件前台主要功能如短代码和前端显示等代码逻辑。<br>
│   │   ├── public.php　　　// 插件的前台主要代码逻辑<br>
│   │   └── shortcode.php　// 插件的短代码代码逻辑<br>
│   ├── core　　　　　　　··包含包括登录、注册、社交登录和用户验证等插件的主要功能代码逻辑。<br>
│   │   ├── login.php　　　// 插件的登录功能代码逻辑<br>
│   │   ├── register.php　　// 插件的注册功能代码逻辑<br>
│   │   ├── social-login.php　　// 插件的社交登录功能代码逻辑<br>
│   │   └── verification.php　　// 插件的用户验证功能代码逻辑<br>
│   ├── email/　　　　　　　　··包含插件的邮件发送和邮件模板的代码逻辑。<br>
│   │   ├── mailer.php　　　　　// 插件的邮件发送功能代码逻辑<br>
│   │   └── email-template.php　// 插件的邮件模板代码逻辑<br>
│   ├── models/　　　　　　　　··包含插件的用户模型和邮件验证模型的代码逻辑。<br>
│   │   ├── user.php　　　　　　// 插件的用户模型代码逻辑<br>
│   │   └── email-verification.php　// 插件的邮件验证模型代码逻辑<br>
│   ├── views/　　　　　　　　　··包含登录、注册、社交登录、用户验证和用户头像上传等各种表单的代码逻辑。<br>
│   │   ├── login-form.php　　　　// 插件的登录表单代码逻辑<br>
│   │   ├── register-form.php　　　// 插件的注册表单代码逻辑<br>
│   │   ├── social-login.php　　　　// 插件的社交登录表单代码逻辑<br>
│   │   ├── verification-form.php　　// 插件的用户验证表单代码逻辑<br>
│   │   └── avatar-upload-form.php　// 插件的用户头像上传表单代码逻辑<br>
│   │   └── frontend-style.php    
│   ├── frontend/    
│   │   ├── css/    
│   │   │    └── frontend-style.css    
│   │   └── js/    
│   │        └── frontend-script.js    
│   ├── upload/　　　　　　　　　··包含插件的用户头像上传功能的代码逻辑。<br>
│   │   └── avatar-upload.php　　// 插件的用户头像上传功能代码逻辑<br>
│   └── ajax-handler.php　　　　// 插件的 Ajax 请求处理代码逻辑<br>
├── assets/　　　　　　　　　　··包含插件的 CSS 和 JavaScript 文件。<br>
│   ├── css/　　　　　　　　　　··包含插件前台和后台的 CSS 样式表。<br>
│   │   ├── style.css　　　　　　　// 插件的前台 CSS 样式表<br>
│   │   └── admin-style.css　　　　// 插件的后台 CSS 样式表<br>
│   └── js/　　　　　　　　　　　··包含插件前台和后台的 JavaScript 代码。<br>
│       ├── main.js　　　　　　　　// 插件的前台 JavaScript 代码<br>
│       └── admin.js　　　　　　　// 插件的后台 JavaScript 代码<br>
├── templates/　　　　　　　　··包含登录、注册、用户验证、社交登录和用户头像上传等各种模板。<br>
│   ├── login.php　　　　　　　// 插件的登录模板文件<br>
│   ├── register.php　　　　　　// 插件的注册模板文件<br>
│   ├── verification.php　　　　// 插件的用户验证模板文件<br>
│   ├── social-login.php　　　　// 插件的社交登录模板文件<br>
│   └── avatar-upload.php　　　// 插件的用户头像上传模板文件<br>
├── phpmailer/　　　　　　　　··包含 PHPMailer 库的代码，供插件设置页面调用。<br>
│    ├── class.phpmailer.php　　　// 包含PHPMailer代码供settings.php文件调用<br>
│    └── class.smtp.php　　　　 // SMTP 邮件服务相关<br>
├── languages/　　　　　　　　　··包含插件的语言翻译文件。<br>
│   └── my-wordpress-plugin.pot　// 插件的语言翻译文件<br>
├── my-wordpress-plugin.php　　 // 插件的主要文件，包含插件的基本信息和加载插件所需的函数。<br>
├── uninstall.php    
└── README.md　　　　　　　　// 插件的说明文档。
