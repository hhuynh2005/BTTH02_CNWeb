<?php
// Lấy vai trò người dùng từ Session
// Giả sử $_SESSION['role'] được thiết lập sau khi đăng nhập thành công
$role = $_SESSION['role'] ?? 0;
// Định nghĩa BASE_URL để sử dụng trong các link
$BASE_URL = BASE_URL; // Đã được định nghĩa trong index.php
?>

<div class="border-end" id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="fas fa-graduation-cap me-2 text-primary"></i> Quản Lý Khóa Học
    </div>
    <div class="list-group list-group-flush">

        <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/home/index">
            <i class="fas fa-home"></i> Trang Chủ
        </a>
        <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/courses/index">
            <i class="fas fa-list-alt"></i> Danh Sách Khóa Học
        </a>

        [cite_start]<?php if ($role == 0): // Menu CHỨC NĂNG HỌC VIÊN[cite: 73] ?>
            <div class="sidebar-category">TÀI KHOẢN HỌC VIÊN</div>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/student/my_courses">
                [cite_start]<i class="fas fa-book-reader"></i> Khóa Học Của Tôi [cite: 77]
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/student/course_progress">
                [cite_start]<i class="fas fa-chart-line"></i> Theo Dõi Tiến Độ [cite: 78]
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/user/profile">
                <i class="fas fa-user-cog"></i> Cập nhật Profile/Avatar
            </a>
        <?php endif; ?>

        <?php if ($role >= 1): // Menu QUẢN LÝ (Giảng viên/Admin) ?>
            <div class="sidebar-category">QUẢN LÝ KHÓA HỌC</div>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/instructor/dashboard">
                <i class="fas fa-tachometer-alt"></i> Dashboard Quản Lý
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/instructor/course/manage">
                [cite_start]<i class="fas fa-folder-open"></i> Quản lý Khóa học [cite: 82]
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/instructor/materials/upload">
                [cite_start]<i class="fas fa-cloud-upload-alt"></i> Đăng tải Tài liệu [cite: 84]
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/instructor/students/list">
                [cite_start]<i class="fas fa-users"></i> Danh sách Học viên [cite: 85]
            </a>
        <?php endif; ?>

        <?php if ($role == 2): // Menu ADMIN (Role 2) ?>
            <div class="sidebar-category">QUẢN TRỊ HỆ THỐNG</div>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/admin/users/manage">
                [cite_start]<i class="fas fa-user-shield"></i> Quản lý Người dùng [cite: 88]
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/admin/categories/list">
                [cite_start]<i class="fas fa-tags"></i> Quản lý Danh mục [cite: 89]
            </a>
            <a class="list-group-item list-group-item-action" href="<?php echo $BASE_URL; ?>/admin/reports/statistics">
                [cite_start]<i class="fas fa-chart-area"></i> Xem Thống kê [cite: 90]
            </a>
        <?php endif; ?>

    </div>
</div>