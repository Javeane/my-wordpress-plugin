<!-- templates/avatar-upload.php -->

<?php
/**
 * User Avatar Upload My Wordpress Plugin My Wordpress Plugin
 * This template file is responsible for avatar upload page.
 * 
 * @package My_Wordpress_Plugin
 */

// 获取当前用户信息
$current_user = wp_get_current_user();

// 获取用户头像 URL
$user_avatar_url = get_user_meta($current_user->ID, 'avatar_url', true);
if (empty($user_avatar_url)) {
    // 如果用户没有上传头像，使用 WordPress 默认头像
    $user_avatar_url = get_avatar_url($current_user->ID);
}

// 上传头像处理
if (isset($_POST['submit'])) {
    // 验证上传的文件是否是图像类型
    if (!empty($_FILES['avatar']['name'])) {
        $supported_types = array('jpg', 'jpeg', 'png', 'gif');
        $arr_file_type = wp_check_filetype(basename($_FILES['avatar']['name']));
        $uploaded_type = $arr_file_type['ext'];
        if (in_array($uploaded_type, $supported_types)) {
            // 将文件保存到 WordPress 媒体库中
            $upload = wp_upload_bits($_FILES['avatar']['name'], null, file_get_contents($_FILES['avatar']['tmp_name']));
            if (isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
} else {
// 保存头像信息到用户 meta 中
$user_id = get_current_user_id();
update_user_meta($user_id, 'avatar', $upload['url']);
// 显示上传成功的提示信息
echo '<div class="alert alert-success">Your avatar has been uploaded successfully!</div>';
}
} else {
// 显示上传失败的提示信息
echo '<div class="alert alert-danger">Invalid file type. Please upload a JPG, JPEG, PNG, or GIF file.</div>';
}
} else {
// 显示上传失败的提示信息
echo '<div class="alert alert-danger">Please select a file to upload.</div>';
}
}

// 显示当前用户的头像
$user_id = get_current_user_id();
$avatar_url = get_user_meta($user_id, 'avatar', true);
if (!empty($avatar_url)) {
echo '<img src="' . $avatar_url . '" class="avatar" alt="User Avatar">';
} else {
echo '<div class="alert alert-info">You have not uploaded an avatar yet.</div>';
}
