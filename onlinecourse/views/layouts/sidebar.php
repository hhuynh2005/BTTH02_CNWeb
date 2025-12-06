<?php
    // 1. Lấy URL hiện tại để active menu
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    
    // Hàm kiểm tra active
    if (!function_exists('isActive')) {
        function isActive($url, $checkString) {
            return (strpos($url, $checkString) !== false) ? 'active' : '';
        }
    }

    // 2. Xác định Role và Tên hiển thị
    $userRole = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : -1; // -1 là khách
    $roleName = 'Khách';
    
    if ($userRole === 0) $roleName = 'Học viên';
    elseif ($userRole === 1) $roleName = 'Giảng viên';
    elseif ($userRole === 2) $roleName = 'Quản trị viên';
?>

<aside class="sidebar-student">
    <div class="user-info">
        <div class="avatar">
            <?php 
                $avatarPath = isset($_SESSION['user']['avatar']) && $_SESSION['user']['avatar'] 
                    ? 'assets/uploads/avatars/' . $_SESSION['user']['avatar'] 
                    : 'assets/uploads/avatars/default.png';
            ?>
            <img src="<?php echo $avatarPath; ?>" alt="Avatar">
        </div>
        <div class="info">
            <p>Xin chào,</p>
            <h4><?php echo isset($_SESSION['user']['fullname']) ? $_SESSION['user']['fullname'] : $roleName; ?></h4>
            <small style="color: #666;">(<?php echo $roleName; ?>)</small>
        </div>
    </div>

    <hr>

    <ul class="menu-list">
        
        <?php if ($userRole === 0): ?>
<!--             
            <li class="menu-item <?php echo isActive($url, 'enrollment/dashboard'); ?>">
                <a href="index.php?url=enrollment/dashboard">
                    <i class="icon-dashboard"></i> 
                    Tổng quan (Dashboard)
                </a>
            </li> -->

            <li class="menu-item <?php echo isActive($url, 'enrollment/my_courses'); ?>">
                <a href="index.php?url=enrollment/my_courses">
                    <i class="icon-book"></i> 
                    Khóa học của tôi
                </a>
            </li>

            <li class="menu-item <?php echo isActive($url, 'enrollment/course_progress'); ?>">
                <a href="index.php?url=enrollment/course_progress">
                    <i class="icon-chart-bar"></i> 
                    Theo dõi tiến độ
                </a>
            </li>

            <li class="menu-item <?php echo isActive($url, 'course/index'); ?>">
                <a href="index.php?url=course/index">
                    <i class="icon-search"></i> 
                    Đăng ký khóa học mới
                </a>
            </li>

        <?php elseif ($userRole === 1): ?>
            
            <li class="menu-item <?php echo isActive($url, 'instructor/dashboard'); ?>">
                <a href="index.php?url=instructor/dashboard">
                    <i class="icon-dashboard"></i> 
                    Dashboard GV
                </a>
            </li>
            <li class="menu-item <?php echo isActive($url, 'instructor/course/manage'); ?>">
                <a href="index.php?url=instructor/course/manage">
                    <i class="icon-folder-open"></i> 
                    Quản lý khóa học
                </a>
            </li>
            <li class="menu-item <?php echo isActive($url, 'instructor/course/create'); ?>">
                <a href="index.php?url=instructor/course/create">
                    <i class="icon-plus"></i> 
                    Tạo khóa học
                </a>
            </li>

        <?php elseif ($userRole === 2): ?>

            <li class="menu-item <?php echo isActive($url, 'admin/dashboard'); ?>">
                <a href="index.php?url=admin/dashboard">
                    <i class="icon-dashboard"></i> 
                    Thống kê hệ thống
                </a>
            </li>
            <li class="menu-item <?php echo isActive($url, 'admin/users'); ?>">
                <a href="index.php?url=admin/users/manage">
                    <i class="icon-users"></i> 
                    Quản lý người dùng
                </a>
            </li>

        <?php else: ?>
            
            <li class="menu-item">
                <a href="index.php?url=auth/login">
                    <i class="icon-login"></i> 
                    Đăng nhập
                </a>
            </li>
            <li class="menu-item">
                <a href="index.php?url=auth/register">
                    <i class="icon-user-add"></i> 
                    Đăng ký
                </a>
            </li>
            <li class="menu-item <?php echo isActive($url, 'course/index'); ?>">
                <a href="index.php?url=course/index">
                    <i class="icon-search"></i> 
                    Xem danh sách khóa học
                </a>
            </li>

        <?php endif; ?>

        <?php if ($userRole !== -1): ?>
            <hr>
            <li class="menu-item logout">
                <a href="index.php?url=auth/logout" onclick="return confirm('Bạn có chắc muốn đăng xuất?');">
                    <i class="icon-logout"></i> 
                    Đăng xuất
                </a>
            </li>
        <?php endif; ?>

    </ul>
</aside>

<style>
    /* CSS Sidebar (Giữ nguyên như cũ) */
    .menu-item.active a {
        background-color: #e3f2fd;
        color: #0d6efd;
        font-weight: bold;
        border-left: 4px solid #0d6efd;
    }
    .menu-item a {
        display: block;
        padding: 10px 15px;
        text-decoration: none;
        color: #333;
        transition: 0.3s;
    }
    .menu-item a:hover {
        background-color: #f1f1f1;
    }
</style>