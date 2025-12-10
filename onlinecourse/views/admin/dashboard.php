<?php
// File: views/admin/dashboard.php
// Dữ liệu cần: $count['total'], $count['student'], $count['instructor'], $count['admin']
// Giả định các biến $count đã được tính toán trong AdminController::dashboard()
// và BASE_URL, $_SESSION['fullname'] đã có.

// Thiết lập các biến nếu chưa tồn tại (để tránh lỗi undefined variable)
$count = $count ?? ['total' => 0, 'student' => 0, 'instructor' => 0, 'admin' => 0];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Online Course</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Bổ sung CSS cho Dashboard */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            padding: 1.5rem;
        }

        .stat-icon {
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 1rem;
            color: #fff;
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
        }

        .stat-info h3 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Colors for stat icons */
        .primary {
            background-color: #4f46e5;
        }

        .success {
            background-color: #10b981;
        }

        .warning {
            background-color: #f59e0b;
        }

        .danger {
            background-color: #ef4444;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-outline {
            border: 1px solid #4f46e5;
            color: #4f46e5;
            background: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .btn-outline:hover {
            background: #f0f4ff;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #3a0ca3;
        }

        /* Layout CSS from your original file */
        .admin-layout {
            display: flex;
        }

        .admin-sidebar {
            width: 250px;
            background: #1e293b;
            color: white;
            height: calc(100vh - 60px);
            padding-top: 1rem;
        }

        .admin-content {
            flex-grow: 1;
            padding: 1.5rem;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 10px 1.5rem;
            text-decoration: none;
            color: #cbd5e1;
            transition: background 0.3s;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: #334155;
            color: #fff;
        }

        .sidebar-item i {
            margin-right: 10px;
        }

        /* Đã sửa SVG thành i cho đồng bộ */
        .content-header h1 {
            color: #1e293b;
            border-bottom: none;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            height: 60px;
        }

        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container navbar-container">
            <div class="navbar-brand">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#4F46E5" />
                    <path
                        d="M20 12.5L12.5 16.25V23.75C12.5 27.5 15.625 30.875 20 31.875C24.375 30.875 27.5 27.5 27.5 23.75V16.25L20 12.5Z"
                        fill="white" />
                </svg>
                <span>Online Course - Admin</span>
            </div>
            <div class="navbar-actions">
                <span>Xin chào,
                    <strong><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Admin'); ?></strong></span>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-danger btn-sm"
                    style="padding: 5px 10px; text-decoration: none; border-radius: 4px; color: white; background: #ef4444;">Đăng
                    xuất</a>
            </div>
        </div>
    </nav>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-item active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-item">
                    <i class="fas fa-users"></i> Quản lý người dùng
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/categories" class="sidebar-item">
                    <i class="fas fa-list"></i> Danh mục
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/systemStatistics" class="sidebar-item">
                    <i class="fas fa-chart-bar"></i> Thống kê Hệ thống
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/courseApproval" class="sidebar-item">
                    <i class="fas fa-check-circle"></i> Duyệt khóa học
                </a>
            </div>
        </aside>

        <main class="admin-content">
            <div class="content-header">
                <h1><i class="fas fa-chart-line"></i> Dashboard</h1>
                <p>Tổng quan về hệ thống</p>
            </div>


            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Tổng người dùng</h3>
                        <div class="stat-number"><?php echo $count['total']; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Học viên</h3>
                        <div class="stat-number"><?php echo $count['student']; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Giảng viên</h3>
                        <div class="stat-number"><?php echo $count['instructor']; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Quản trị viên</h3>
                        <div class="stat-number"><?php echo $count['admin']; ?></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Hành động nhanh</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-outline"
                            style="justify-content: center;">
                            <i class="fas fa-users"></i> Quản lý người dùng
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn btn-outline"
                            style="justify-content: center;">
                            <i class="fas fa-list"></i> Quản lý danh mục
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/systemStatistics" class="btn btn-outline"
                            style="justify-content: center;">
                            <i class="fas fa-chart-bar"></i> Thống kê Hệ thống
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/courseApproval" class="btn btn-outline"
                            style="justify-content: center;">
                            <i class="fas fa-check-circle"></i> Duyệt khóa học
                        </a>
                        <a href="<?php echo BASE_URL; ?>/" class="btn btn-primary" style="justify-content: center;">
                            <i class="fas fa-home"></i> Về trang chủ
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Hoạt động gần đây</h3>
                </div>
                <div class="card-body">
                    <p>Không có dữ liệu hoạt động gần đây.</p>
                </div>
            </div>
        </main>
    </div>
</body>

</html>