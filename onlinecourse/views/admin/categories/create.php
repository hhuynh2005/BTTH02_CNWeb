<?php
// File: views/admin/categories/create.php
$category = $category ?? [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Danh mục - Admin</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Đồng bộ */
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
            max-width: 800px;
            margin: 0 auto;
        }

        .sidebar-item i {
            margin-right: 10px;
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

        .card-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.3s;
            border: none;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
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
                <a href="<?php echo BASE_URL; ?>/admin/categories" class="sidebar-item active">
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
                <h1><i class="fas fa-plus-circle"></i> Thêm Danh mục mới</h1>
                <p>Điền thông tin để tạo danh mục khóa học.</p>
            </div>

            <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn btn-secondary" style="margin-bottom: 20px;">
                <i class="fas fa-arrow-left"></i> Quay lại Danh sách
            </a>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/admin/storeCategory" method="POST">
                        <div class="form-group">
                            <label for="name">Tên Danh mục (*)</label>
                            <input type="text" id="name" name="name" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu Danh mục
                        </button>
                    </form>
                </div>
            </div>
        </main>

    </div>
</body>

</html>