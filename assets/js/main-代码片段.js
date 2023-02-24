// 页面加载时初始化用户信息管理
jQuery(document).ready(function() {
  initUserManagement();
});

// 初始化用户信息管理
function initUserManagement() {
  // 绑定修改头像事件
  jQuery('#change-avatar-btn').on('click', function() {
    jQuery('#avatar-modal').modal('show');
    initDropzone();
  });

  // 绑定更新用户信息事件
  jQuery('#update-user-info-btn').on('click', function() {
    updateUserInformation();
  });

  // 绑定修改密码事件
  jQuery('#change-password-btn').on('click', function() {
    jQuery('#password-modal').modal('show');
  });

  // 绑定更新密码事件
  jQuery('#update-password-btn').on('click', function() {
    updatePassword();
  });
}

// 上传和修改头像的代码
// 初始化 Dropzone.js
function initDropzone() {
  jQuery('#avatar-dropzone').dropzone({
    url: ajaxurl,
    paramName: 'avatar',
    uploadMultiple: false,
    maxFiles: 1,
    acceptedFiles: 'image/*',
    init: function() {
      this.on('success', function(file, response) {
        if (response.success) {
          jQuery('#avatar-preview').attr('src', response.data.url);
          jQuery('#avatar-modal').modal('hide');
        } else {
          alert(response.data.message);
        }
      });
    }
  });
}

// 更新用户信息
function updateUserInformation() {
  var data = {
    action: 'update_user_information',
    display_name: jQuery('#display-name-input').val(),
    description: jQuery('#description-input').val(),
    user_email: jQuery('#user-email-input').val(),
    user_url: jQuery('#user-url-input').val()
  };

  jQuery.post(ajaxurl, data, function(response) {
    if (response.success) {
      alert('用户信息更新成功');
    } else {
      alert(response.data.message);
    }
  });
}

// 更新密码
function updatePassword() {
  var data = {
    action: 'update_password',
    old_password: jQuery('#old-password-input').val(),
    new_password: jQuery('#new-password-input').val(),
    confirm_new_password: jQuery('#confirm-new-password-input').val()
  };

  jQuery.post(ajaxurl, data, function(response) {
    if (response.success) {
      alert('密码更新成功');
      jQuery('#password-modal').modal('hide');
    } else {
      alert(response.data.message);
    }
  });
}
