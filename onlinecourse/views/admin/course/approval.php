<?php
// File: views/admin/course/approval.php
// Dữ liệu cần: $pendingCourses (danh sách khóa học chờ duyệt)
$pendingCourses = $pendingCourses ?? [];
// Giả định BASE_URL, $_SESSION['fullname'] đã có
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyệt Khóa học - Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Đồng bộ (Lấy từ Dashboard và các trang quản lý) */
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

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Buttons */
        .btn-action-group {
            display: flex;
            gap: 5px;
        }

        .btn-approve {
            background: #10b981;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.2s;
        }

        .btn-approve:hover {
            background: #047857;
        }

        .btn-reject {
            background: #ef4444;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            transition: background 0.2s;
        }

        .btn-reject:hover {
            background: #b91c1c;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background-color: #f0f8ff;
            border: 1px solid #b3e5fc;
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
                <a href="<?php echo BASE_URL; ?>/admin/systemStatistics" class="sidebar-item">
                    <i class="fas fa-chart-bar"></i> Thống kê Hệ thống
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/courseApproval" class="sidebar-item active">
                    <i class="fas fa-check-circle"></i> Duyệt khóa học
                </a>
            </div>
        </aside>

        <main class="admin-content">

            <div class="content-header">
                <h1><i class="fas fa-check-circle"></i> Duyệt Khóa học</h1>
                <p>Danh sách các khóa học đang chờ được phê duyệt.</p>
            </div>

            <?php
            // Hiển thị thông báo (sử dụng session nếu có, hoặc GET msg)
            $msg = $_GET['msg'] ?? ($_SESSION['message'] ?? null);
            $msg_type = $_SESSION['message_type'] ?? (strpos($msg, '✅') !== false ? 'success' : 'error');

            if (isset($msg)): ?>
                <div class="alert alert-<?php echo $msg_type; ?>">
                    <?php echo htmlspecialchars($msg); ?>
                </div>
                <?php
                // Xóa session messages sau khi hiển thị
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            endif;
            ?>

            <?php if (!empty($pendingCourses)): ?>
                <div style="overflow-x: auto;" class="card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Khóa học</th>
                                <th>Giảng viên</th>
                                <th>Ngày tạo</th>
                                <th style="width: 200px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingCourses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['id']); ?></td>
                                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td><?php echo htmlspecialchars($course['instructor_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($course['created_at'] ?? 'now')); ?></td>
                                    <td>
                                        <div class="btn-action-group">
                                            <a href="<?php echo BASE_URL; ?>/admin/approveCourse/<?php echo $course['id']; ?>"
                                                class="btn-approve"
                                                onclick="return confirm('Chắc chắn chấp thuận khóa học <?php echo htmlspecialchars($course['title']); ?>?');">
                                                <i class="fas fa-check"></i> Chấp thuận
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/admin/rejectCourse/<?php echo $course['id']; ?>"
                                                class="btn-reject"
                                                onclick="return confirm('Chắc chắn từ chối khóa học <?php echo htmlspecialchars($course['title']); ?>?');">
                                                <i class="fas fa-times"></i> Từ chối
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="padding: 15px;">
                    Không có khóa học nào đang chờ duyệt.
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>