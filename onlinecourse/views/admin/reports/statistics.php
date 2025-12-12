<?php
// File: views/admin/reports/statistics.php
// Dữ liệu cần: $userStats, $courseStats, $enrollmentTrends
// Biến $courseStats có thêm khóa 'avg_enrollments' được tính toán trong Controller/Model
$userStats = $userStats ?? [];
$courseStats = $courseStats ?? ['total_courses' => 0, 'approved_courses' => 0, 'pending_courses' => 0, 'avg_enrollments' => 0];
$enrollmentTrends = $enrollmentTrends ?? [];

// Helper để chuyển đổi User Stats thành mảng dễ truy cập
$userCount = [];
foreach ($userStats as $stats) {
    $userCount[$stats['role']] = $stats['count'];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê Hệ thống - Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS đồng bộ */
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 10px 1.5rem;
            text-decoration: none;
            color: #cbd5e1;
            transition: background 0.3s;
        }

        .sidebar-item i {
            margin-right: 10px;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: #334155;
            color: #fff;
        }

        .content-header h1 {
            color: #1e293b;
            border-bottom: none;
            font-size: 28px;
            margin-bottom: 0.5rem;
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
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #f3f4f6;
            color: #1e293b;
            font-weight: 600;
        }

        /* Metric Cards */
        .stat-grid-4 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-card-small {
            background: #f8fafc;
            border-radius: 6px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }

        .metric-title {
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .metric-value {
            font-size: 24px;
            color: #1e293b;
            font-weight: 700;
        }

        /* Navbar CSS */
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
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="sidebar-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-item">
                    <i class="fas fa-users"></i> Quản lý người dùng
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/categories" class="sidebar-item">
                    <i class="fas fa-list"></i> Danh mục
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/systemStatistics" class="sidebar-item active">
                    <i class="fas fa-chart-bar"></i> Thống kê Hệ thống
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/courseApproval" class="sidebar-item">
                    <i class="fas fa-check-circle"></i> Duyệt khóa học
                </a>
            </div>
        </aside>

        <main class="admin-content">
            <div class="content-header">
                <h1><i class="fas fa-chart-bar"></i> Thống kê Sử dụng Hệ thống</h1>
                <p>Báo cáo chi tiết về người dùng, khóa học và xu hướng đăng ký.</p>
            </div>

            <?php
            // Hiển thị thông báo (nếu có)
            if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?>">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                </div>
                <?php
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            endif;
            ?>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-book-open"></i> Báo cáo Khóa học
                </div>
                <div class="card-body">
                    <div class="stat-grid-4">
                        <div class="stat-card-small">
                            <div class="metric-title">Tổng Khóa học</div>
                            <div class="metric-value">
                                <?php echo htmlspecialchars($courseStats['total_courses'] ?? 0); ?>
                            </div>
                        </div>
                        <div class="stat-card-small" style="background: #e6ffed;">
                            <div class="metric-title">Đã duyệt</div>
                            <div class="metric-value" style="color: #10b981;">
                                <?php echo htmlspecialchars($courseStats['approved_courses'] ?? 0); ?>
                            </div>
                        </div>
                        <div class="stat-card-small" style="background: #fffbef;">
                            <div class="metric-title">Chờ duyệt / Draft</div>
                            <div class="metric-value" style="color: #f59e0b;">
                                <?php echo htmlspecialchars($courseStats['pending_courses'] ?? 0); ?>
                            </div>
                        </div>
                        <div class="stat-card-small">
                            <div class="metric-title">TB Đăng ký/Khóa</div>
                            <div class="metric-value">
                                <?php echo number_format($courseStats['avg_enrollments'] ?? 0, 1); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users"></i> Phân loại Người dùng
                </div>
                <div class="card-body">
                    <div class="stat-grid-4">
                        <div class="stat-card-small" style="background: #e0e7ff;">
                            <div class="metric-title">Học viên (0)</div>
                            <div class="metric-value" style="color: #4f46e5;">
                                <?php echo htmlspecialchars($userCount[0] ?? 0); ?>
                            </div>
                        </div>
                        <div class="stat-card-small" style="background: #fff3e6;">
                            <div class="metric-title">Giảng viên (1)</div>
                            <div class="metric-value" style="color: #f97316;">
                                <?php echo htmlspecialchars($userCount[1] ?? 0); ?>
                            </div>
                        </div>
                        <div class="stat-card-small" style="background: #fee2e2;">
                            <div class="metric-title">Quản trị viên (2)</div>
                            <div class="metric-value" style="color: #ef4444;">
                                <?php echo htmlspecialchars($userCount[2] ?? 0); ?>
                            </div>
                        </div>
                        <div class="stat-card-small">
                            <div class="metric-title">Tổng cộng</div>
                            <div class="metric-value">
                                <?php echo htmlspecialchars(($userCount[0] ?? 0) + ($userCount[1] ?? 0) + ($userCount[2] ?? 0)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-area"></i> Xu hướng Đăng ký (12 tháng gần nhất)
                </div>
                <div class="card-body">
                    <?php if (!empty($enrollmentTrends)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tháng/Năm</th>
                                    <th>Số lượt Đăng ký</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($enrollmentTrends as $trend): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($trend['month']); ?></td>
                                        <td><?php echo htmlspecialchars($trend['count']); ?> lượt</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Không có dữ liệu về xu hướng đăng ký trong 12 tháng gần nhất.</p>
                    <?php endif; ?>
                </div>
            </div>

        </main>
    </div>
</body>

</html>