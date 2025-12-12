<?php
// Lấy vai trò người dùng từ Session
// Giả sử $_SESSION['role'] được thiết lập sau khi đăng nhập thành công
$role = $_SESSION['role'] ?? 0;
// Định nghĩa BASE_URL để sử dụng trong các link
$BASE_URL = BASE_URL; // Đã được định nghĩa trong index.php

// 1. LOGIC XÁC ĐỊNH ĐƯỜNG DẪN HIỆN TẠI VÀ CHỨC NĂNG ACTIVE
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
// Loại bỏ BASE_URL (nếu cần) và Query String để so sánh
$currentPath = str_replace($BASE_URL, '', $currentUri);
if (($qPos = strpos($currentPath, '?')) !== false) {
    $currentPath = substr($currentPath, 0, $qPos);
}
// Đảm bảo path sạch (ví dụ: /student/my_courses)
$currentPath = trim($currentPath, '/');

// Hàm kiểm tra đường dẫn
function isActive($pathSegment)
{
    global $currentPath;
    // Kiểm tra xem đường dẫn hiện tại có chứa đoạn đường dẫn đang so sánh không
    return (strpos($currentPath, $pathSegment) !== false || $currentPath === $pathSegment) ? 'active' : '';
}
?>

<div class="border-end" id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="fas fa-graduation-cap me-2 text-primary"></i> Quản Lý Khóa Học
    </div>
    <div class="list-group list-group-flush">

        <a class="list-group-item list-group-item-action <?php echo isActive('home/index') || $currentPath === '' ? 'active' : ''; ?>"
            href="<?php echo $BASE_URL; ?>/home/index">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <a class="list-group-item list-group-item-action <?php echo isActive('courses/index') ? 'active' : ''; ?>"
            href="<?php echo $BASE_URL; ?>/courses/index">
            <i class="fas fa-list-alt"></i> Danh Sách Khóa Học
        </a>

        <?php if ($role == 0): // Menu CHỨC NĂNG HỌC VIÊN 
        ?>
            <div class="sidebar-category">TÀI KHOẢN HỌC VIÊN</div>

            <a class="list-group-item list-group-item-action <?php echo isActive('student/my_courses') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/student/my_courses">
                <i class="fas fa-book-reader"></i> Khóa Học Của Tôi
            </a>

            <a class="list-group-item list-group-item-action <?php echo isActive('student/course_progress') || isActive('student/progress') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/student/progress">
                <i class="fas fa-chart-line"></i> Theo Dõi Tiến Độ
            </a>

            <a class="list-group-item list-group-item-action <?php echo isActive('user/profile') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/user/profile">
                <i class="fas fa-user-cog"></i> Cập nhật Profile/Avatar
            </a>
        <?php endif; ?>

        <?php if ($role >= 1): // Menu QUẢN LÝ (Giảng viên/Admin) 
        ?>
            <div class="sidebar-category">QUẢN LÝ KHÓA HỌC</div>
            <a class="list-group-item list-group-item-action <?php echo isActive('instructor/dashboard') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/instructor/dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard Quản Lý
            </a>
            <a class="list-group-item list-group-item-action <?php echo isActive('instructor/course/manage') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/instructor/course/manage">
                <i class="fas fa-folder-open"></i> Quản lý Khóa học
            </a>
            <a class="list-group-item list-group-item-action <?php echo isActive('instructor/materials/upload') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/instructor/materials/upload">
                <i class="fas fa-cloud-upload-alt"></i> Đăng tải Tài liệu
            </a>
            <a class="list-group-item list-group-item-action <?php echo isActive('instructor/students') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/instructor/students/list">
                <i class="fas fa-users"></i> Danh sách Học viên
            </a>
        <?php endif; ?>

        <?php if ($role == 2): // Menu ADMIN (Role 2) 
        ?>
            <div class="sidebar-category">QUẢN TRỊ HỆ THỐNG</div>
            <a class="list-group-item list-group-item-action <?php echo isActive('admin/users') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/admin/users/manage">
                <i class="fas fa-user-shield"></i> Quản lý Người dùng
            </a>
            <a class="list-group-item list-group-item-action <?php echo isActive('admin/categories') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/admin/categories/list">
                <i class="fas fa-tags"></i> Quản lý Danh mục
            </a>
            <a class="list-group-item list-group-item-action <?php echo isActive('admin/reports') ? 'active' : ''; ?>"
                href="<?php echo $BASE_URL; ?>/admin/reports/statistics">
                <i class="fas fa-chart-area"></i> Xem Thống kê
            </a>
        <?php endif; ?>

    </div>
</div>