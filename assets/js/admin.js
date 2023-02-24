/**
 * My Wordpress Plugin - Admin JS
 *
 * This file contains the JavaScript code for the admin pages of the My Wordpress Plugin.
 * It handles the interaction with the user interface and communicates with the backend
 * through AJAX requests.
 *
 * @package My_Wordpress_Plugin
 */

/**
 * 页面加载时的初始化功能
 */
function initPage() {
  // 加载插件菜单
  loadPluginMenu();

  // 显示欢迎页面
  showWelcomePage();

  // 绑定插件设置页面的保存按钮点击事件
  jQuery('#mywpplugin-save-settings-btn').click(savePluginSettings);
}

/**
 * 加载插件菜单
 */
function loadPluginMenu() {
  // 创建插件菜单项
  const pluginMenuHtml = `
    <div class="wrap">
      <h1>My Wordpress Plugin</h1>
      <nav class="nav-tab-wrapper">
        <a href="?page=mywpplugin" class="nav-tab nav-tab-active">欢迎页面</a>
        <a href="?page=mywpplugin_settings" class="nav-tab">设置</a>
        <a href="?page=mywpplugin_users" class="nav-tab">用户管理</a>
      </nav>
    </div>
  `;

  // 将插件菜单项添加到页面中
  jQuery('#wpbody-content .wrap').first().before(pluginMenuHtml);
}

/**
 * 显示欢迎页面
 */
function showWelcomePage() {
  // 加载欢迎页面的 HTML
  const welcomePageHtml = `
    <div class="wrap">
      <h1>欢迎来到 My Wordpress Plugin！</h1>
      <p>这是一个功能强大的 Wordpress 插件，可以帮助您管理您的网站。</p>
    </div>
  `;

  // 将欢迎页面添加到页面中
  jQuery('#wpbody-content .wrap').first().html(welcomePageHtml);
}

/**
 * 绑定插件设置页面的保存按钮点击事件
 */
function bindSavePluginSettings() {
  // TODO: 实现保存插件设置的逻辑
}

// 页面加载时执行初始化功能
jQuery(document).ready(function() {
  initPage();
});

/**
 * 初始化插件设置
 */
function initSettings() {
  // TODO: 初始化插件设置界面和数据
}
// 使用 AJAX 请求获取插件设置数据
sendAjaxRequest('mywpplugin_get_settings', {}, function(response) {
  // TODO: 将获取到的数据填充到页面中
}, function(error) {
  console.error('Failed to get plugin settings:', error);
});

// 绑定表单的提交事件
jQuery('#settings-form').submit(function(event) {
  event.preventDefault();  // 阻止表单默认的提交行为

  // 构造表单数据
  const formData = {
    settingA: jQuery('#setting-a').val(),
    settingB: jQuery('#setting-b').val(),
    settingC: jQuery('#setting-c').val(),
  };

  // 使用 AJAX 请求保存插件设置
  sendAjaxRequest('mywpplugin_save_settings', formData, function(response) {
    alert('Settings saved successfully!');
  }, function(error) {
    alert('Error saving settings. Please try again later.');
  });
});

/**
 * 初始化用户管理
 */
function initUsers() {
  // 发送 AJAX 请求获取用户列表
  sendAjaxRequest('mywpplugin_get_users', {}, function(response) {
    // 将用户数据渲染到页面中
    const userList = jQuery('#user-list');
    userList.empty(); // 清空列表

    if (response.success && response.data && response.data.length) {
      // 构造用户列表 HTML
      let html = '';
      response.data.forEach(function(user) {
        html += `<tr>
          <td>${user.ID}</td>
          <td>${user.user_login}</td>
          <td>${user.user_email}</td>
        </tr>`;
      });

      // 将用户列表 HTML 插入到页面中
      userList.html(html);
    } else {
      // 显示错误消息
      userList.html('<tr><td colspan="3">Failed to load user data.</td></tr>');
    }
  }, function(error) {
    console.error('Failed to get user data:', error);
  });
}

//第三部分：用户管理相关功能的实现

/**
 * 初始化用户管理
 */
function initUsers() {
  // 发送 AJAX 请求获取用户列表
  sendAjaxRequest('mywpplugin_get_users', {}, function(response) {
    // 将用户数据渲染到页面中
    const userList = jQuery('#user-list');
    userList.empty(); // 清空列表

    if (response.success && response.data && response.data.length) {
      // 构造用户列表 HTML
      let html = '';
      response.data.forEach(function(user) {
        html += `<tr>
          <td>${user.ID}</td>
          <td>${user.user_login}</td>
          <td>${user.user_email}</td>
          <td>
            <button class="button edit-user" data-user-id="${user.ID}" data-username="${user.user_login}" data-email="${user.user_email}">Edit</button>
            <button class="button delete-user" data-user-id="${user.ID}">Delete</button>
          </td>
        </tr>`;
      });

      // 将用户列表 HTML 插入到页面中
      userList.html(html);

      // 绑定删除用户按钮的事件处理函数
      jQuery('.delete-user').click(function() {
        const userId = jQuery(this).data('user-id');

        // 发送 AJAX 请求删除用户
        sendAjaxRequest('mywpplugin_delete_user', {userId: userId}, function(response) {
          alert('User deleted successfully!');
          initUsers(); // 刷新用户列表
        }, function(error) {
          alert('Error deleting user. Please try again later.');
        });
      });

      // 绑定编辑用户按钮的事件处理函数
      jQuery('.edit-user').click(function() {
        const userId = jQuery(this).data('user-id');
        const username = jQuery(this).data('username');
        const email = jQuery(this).data('email');

        // 将用户信息填充到编辑表单中
        jQuery('#edit-user-id').val(userId);
        jQuery('#edit-username').val(username);
        jQuery('#edit-email').val(email);

        // 显示编辑用户模态框
        jQuery('#edit-user-modal').modal('show');
      });
    }
  }, function(error) {
    console.error('Failed to get user list:', error);
  });
}

// 初始化用户管理界面
function initUserManagement() {
  initUsers();

  // 绑定表单的提交事件
  jQuery('#add-user-form').submit(function(event) {
    event.preventDefault();  // 阻止表单默认的提交行为

    // 构造表单数据
    const formData = {
      username: jQuery('#username').val(),
      email: jQuery('#email').val(),
    };

    // 使用 AJAX 请求添加用户
    sendAjaxRequest('mywpplugin_add_user', formData, function(response) {
      alert('User added successfully!');
      jQuery('#add-user-modal').modal('hide'); // 隐藏添加用户模态框
      initUsers(); // 刷新用户列表
    }, function(error) {
      alert('Error adding user. Please try again later.');
    });
  });

  // 绑定编辑用户表单的提交事件
  jQuery('#edit-user-form').submit(function(event) {
    event.preventDefault();  // 阻止表单默认的提交
  // 构造表单数据
  const formData = {
    userId: jQuery('#edit-user-id').val(),
    username: jQuery('#edit-username').val(),
    email: jQuery('#edit-email').val(),
  };

// 使用 AJAX 请求编辑用户
sendAjaxRequest('mywpplugin_edit_user', formData, function(response) {
  alert('User edited successfully!');
  jQuery('#edit-user-modal').modal('hide'); // 隐藏编辑用户模态框
  initUsers(); // 刷新用户列表
}, function(error) {
  alert('Error editing user. Please try again later.');
});
});

// 绑定搜索用户输入框的输入事件
jQuery('#search-users').on('input', function(event) {
// 获取搜索关键字
const keyword = jQuery(event.target).val();
// 如果搜索关键字为空，则显示所有用户
if (!keyword) {
  jQuery('#user-list tr').show();
  return;
}

// 隐藏所有用户
jQuery('#user-list tr').hide();

// 显示匹配关键字的用户
jQuery(`#user-list td:contains(${keyword})`).parent().show();
});
}

//第四部分

/**

发送 AJAX 请求
* @param {string} action - 要执行的 WordPress action
* @param {object} data - 要发送的数据
* @param {function} successCallback - 成功时的回调函数
* @param {function} errorCallback - 失败时的回调函数
*/
function sendAjaxRequest(action, data, successCallback, errorCallback) {
// 构造 AJAX 请求参数
const ajaxOptions = {
url: myPlugin.ajaxUrl,
type: 'POST',
dataType: 'json',
data: {
action: action,
nonce: myPlugin.nonce,
data: data,
},
success: function(response) {
if (typeof successCallback === 'function') {
successCallback(response);
}
},
error: function(jqXHR, textStatus, errorThrown) {
if (typeof errorCallback === 'function') {
errorCallback(errorThrown);
}
},
};
// 发送 AJAX 请求
jQuery.ajax(ajaxOptions);
}


