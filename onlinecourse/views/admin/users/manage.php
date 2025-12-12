<?php
// File: views/admin/users/manage.php
// Hiển thị danh sách người dùng (users) để Quản trị viên quản lý.
// Dữ liệu cần: $users (Danh sách user từ User Model)
// Giả định BASE_URL, $_SESSION['fullname'], $_SESSION['user_role'] đã có.
$users = $users ?? [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng - Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS cho manage.php (đồng bộ với dashboard) */
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

        .content-header h1 {
            color: #1e293b;
            border-bottom: none;
            font-size: 28px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th,
        .user-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            vertical-align: middle;
        }

        .user-table th {
            background-color: #f3f4f6;
            color: #1e293b;
            font-weight: 600;
        }

        .user-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-admin {
            background-color: #fecaca;
            color: #dc2626;
        }

        .badge-instructor {
            background-color: #fde68a;
            color: #b45309;
        }

        .badge-student {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .badge-active {
            background-color: #d1fae5;
            color: #059669;
        }

        .badge-inactive {
            background-color: #ffe4e6;
            color: #be123c;
        }

        .action-btn {
            color: #4f46e5;
            margin-left: 5px;
            text-decoration: none;
            padding: 5px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .action-btn:hover {
            background: #eef2ff;
        }

        .btn-delete {
            color: #ef4444;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
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
                <a href="<?php echo BASE_URL; ?>/admin/users" class="sidebar-item active">
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
                <h1><i class="fas fa-users"></i> Quản lý Người dùng</h1>
                <p>Xem, kích hoạt và vô hiệu hóa tài khoản người dùng.</p>
            </div>


            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724;">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($users)): ?>
                <div style="overflow-x: auto;">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ & Tên</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th style="width: 150px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['fullname'] ?: $user['username']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <?php
                                        $role = $user['role'];
                                        $role_text = 'Học viên';
                                        $role_class = 'badge-student';
                                        if ($role == 1) {
                                            $role_text = 'Giảng viên';
                                            $role_class = 'badge-instructor';
                                        }
                                        if ($role == 2) {
                                            $role_text = 'Quản trị viên';
                                            $role_class = 'badge-admin';
                                        }
                                        ?>
                                        <span class="badge <?php echo $role_class; ?>"><?php echo $role_text; ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $user['status'];
                                        $status_text = $status == 1 ? 'Hoạt động' : 'Bị khóa';
                                        $status_class = $status == 1 ? 'badge-active' : 'badge-inactive';
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/toggleStatus/<?php echo $user['id']; ?>"
                                            class="action-btn"
                                            title="<?php echo $status == 1 ? 'Vô hiệu hóa' : 'Kích hoạt'; ?>">
                                            <i class="fas <?php echo $status == 1 ? 'fa-lock' : 'fa-unlock'; ?>"></i>
                                        </a>

                                        <a href="<?php echo BASE_URL; ?>/admin/deleteUser/<?php echo $user['id']; ?>"
                                            class="action-btn btn-delete"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa user <?php echo htmlspecialchars($user['username']); ?>?');"
                                            title="Xóa người dùng">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="padding: 15px; background-color: #f0f8ff; border: 1px solid #b3e5fc;">
                    Chưa có người dùng nào được đăng ký trong hệ thống.</div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>