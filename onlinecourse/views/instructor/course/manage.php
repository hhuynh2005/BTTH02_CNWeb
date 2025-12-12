<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Khóa học - Giảng viên</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Dashboard Styles - Đồng bộ với layout trước */
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --secondary: #3a0ca3;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--light);
            color: var(--dark);
            min-height: 100vh;
        }

        /* Modern Layout */
        .modern-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .modern-sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
        }

        .sidebar-logo i {
            font-size: 28px;
            color: #60a5fa;
        }

        .sidebar-logo span {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-sm);
            margin-top: 16px;
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sidebar-user-info h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .sidebar-user-info p {
            margin: 0;
            font-size: 12px;
            color: #94a3b8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: #3b82f6;
        }

        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: white;
            border-left-color: #3b82f6;
        }

        .sidebar-item i {
            width: 20px;
            text-align: center;
        }

        .badge-count {
            background: var(--danger);
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Main Content */
        .modern-content {
            padding: 0;
            overflow-x: hidden;
        }

        /* Top Navigation */
        .top-nav {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }

        .top-nav-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--gray);
            margin: 0;
        }

        .top-nav-right {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn-view-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-view-home:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Main Content Area */
        .content-area {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header-title h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .header-title p {
            font-size: 16px;
            color: var(--gray);
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Statistics Grid */
        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: linear-gradient(135deg, #fef3c7, #fcd34d);
            color: #d97706;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, #dcfce7, #86efac);
            color: #059669;
        }

        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            color: #1d4ed8;
        }

        .stat-card:nth-child(4) .stat-icon {
            background: linear-gradient(135deg, #fce7f3, #f9a8d4);
            color: #db2777;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
        }

        /* Filters */
        .filters-section {
            background: white;
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 32px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        .filter-select {
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            background: white;
            color: var(--dark);
            transition: all 0.3s;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 48px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Courses Grid */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .course-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            border: 1px solid transparent;
        }

        .course-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .course-image {
            height: 200px;
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            position: relative;
        }

        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .course-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 6px 12px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .course-content {
            padding: 24px;
        }

        .course-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .course-description {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .course-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--gray);
        }

        .course-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--light);
        }

        .course-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--success);
        }

        .course-price.free {
            color: var(--gray);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            transition: all 0.3s;
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* Style cho nút xóa */
        .action-btn.delete-btn {
            color: var(--danger);
            border-color: var(--danger);
        }

        .action-btn.delete-btn:hover {
            background: var(--danger) !important;
            border-color: var(--danger) !important;
            color: white !important;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.pending {
            background: linear-gradient(135deg, #fef3c7, #fcd34d);
            color: #92400e;
        }

        .status-badge.approved {
            background: linear-gradient(135deg, #dcfce7, #86efac);
            color: #065f46;
        }

        .status-badge.rejected {
            background: linear-gradient(135deg, #fee2e2, #fca5a5);
            color: #7f1d1d;
        }

        /* Empty State */
        .empty-state {
            background: white;
            padding: 80px 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            grid-column: 1 / -1;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #eef2ff, #c7d2fe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 48px;
            color: var(--primary);
        }

        .empty-state h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .empty-state p {
            color: var(--gray);
            margin-bottom: 24px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
        }

        .pagination-btn {
            padding: 8px 16px;
            border: 1px solid var(--border);
            background: white;
            border-radius: var(--radius-sm);
            cursor: pointer;
            color: var(--gray);
            transition: all 0.3s;
            text-decoration: none;
            font-size: 14px;
        }

        .pagination-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-number {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--dark);
        }

        .page-number.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .modern-layout {
                grid-template-columns: 1fr;
            }

            .modern-sidebar {
                display: none;
            }

            .content-area {
                padding: 16px;
            }

            .courses-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 16px;
                align-items: stretch;
            }

            .top-nav {
                padding: 16px;
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .top-nav-right {
                flex-direction: column;
            }

            .statistics-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .courses-grid {
                grid-template-columns: 1fr;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .statistics-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <div class="modern-layout">
        <!-- Sidebar -->
        <aside class="modern-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>EduMaster</span>
                </div>
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="sidebar-user-info">
                        <h4><?php echo htmlspecialchars($_SESSION['fullname']); ?></h4>
                        <p>Giảng viên</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/course/manage" class="sidebar-item active">
                    <i class="fas fa-book"></i>
                    <span>Khóa học của tôi</span>
                    <?php if (isset($totalCourses) && $totalCourses > 0): ?>
                        <span class="badge-count"><?php echo $totalCourses; ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo BASE_URL; ?>/course/create" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tạo khóa học</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/enrollments" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Học viên</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/progress" class="sidebar-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Theo dõi tiến độ</span>
                </a>
                <div class="sidebar-divider"></div>
                <a href="<?php echo BASE_URL; ?>/" class="sidebar-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Xem trang chủ</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="modern-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="top-nav-left">
                    <h1 class="page-title">Quản lý Khóa học</h1>
                    <p class="page-subtitle">Quản lý và theo dõi các khóa học của bạn</p>
                </div>

                <div class="top-nav-right">
                    <a href="<?php echo BASE_URL; ?>/" class="btn-view-home" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        Xem trang chủ
                    </a>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Đăng xuất
                    </a>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="header-title">
                        <h1>Khóa học của tôi</h1>
                        <p>Quản lý tất cả khóa học bạn đã tạo</p>
                    </div>
                    <div class="header-actions">
                        <a href="<?php echo BASE_URL; ?>/course/create" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            Tạo khóa học mới
                        </a>
                    </div>
                </div>

                <!-- Statistics -->
                <?php
                // Tính toán số lượng khóa học theo trạng thái
                $totalCourses = isset($courses) ? count($courses) : 0;
                $approvedCourses = 0;
                $pendingCourses = 0;
                $rejectedCourses = 0;

                if (isset($courses)) {
                    foreach ($courses as $course) {
                        $status = $course['status'] ?? 'pending';
                        switch ($status) {
                            case 'approved':
                                $approvedCourses++;
                                break;
                            case 'rejected':
                                $rejectedCourses++;
                                break;
                            case 'pending':
                            default:
                                $pendingCourses++;
                                break;
                        }
                    }
                }
                ?>

                <div class="statistics-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number"><?php echo $totalCourses; ?></div>
                                <div class="stat-label">Tổng khóa học</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number"><?php echo $approvedCourses; ?></div>
                                <div class="stat-label">Đã phê duyệt</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number"><?php echo $pendingCourses; ?></div>
                                <div class="stat-label">Chờ duyệt</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number"><?php echo $rejectedCourses; ?></div>
                                <div class="stat-label">Đã từ chối</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters-section">
                    <form method="GET" action="">
                        <div class="filters-grid">
                            <div class="search-box">
                                <label>&nbsp;</label>
                                <i class="fas fa-search"></i>
                                <input type="text" name="search" placeholder="Tìm kiếm khóa học..."
                                    value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            </div>

                            <div class="filter-group">
                                <label>Danh mục</label>
                                <select name="category" class="filter-select">
                                    <option value="">Tất cả danh mục</option>
                                    <?php if (isset($categories) && is_array($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>"
                                                <?php echo (isset($_GET['category']) && $_GET['category'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Trạng thái</label>
                                <select name="status" class="filter-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending"
                                        <?php echo (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : ''; ?>>
                                        Chờ duyệt</option>
                                    <option value="approved"
                                        <?php echo (isset($_GET['status']) && $_GET['status'] == 'approved') ? 'selected' : ''; ?>>
                                        Đã duyệt</option>
                                    <option value="rejected"
                                        <?php echo (isset($_GET['status']) && $_GET['status'] == 'rejected') ? 'selected' : ''; ?>>
                                        Đã từ chối</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn-primary" style="width: 100%;">
                                    <i class="fas fa-filter"></i>
                                    Lọc kết quả
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Courses Grid -->
                <?php if (isset($courses) && !empty($courses)): ?>
                    <div class="courses-grid">
                        <?php foreach ($courses as $course): ?>
                            <div class="course-card">
                                <div class="course-image">
                                    <?php if (!empty($course['image'])): ?>
                                        <img src="<?php echo BASE_URL; ?>/assets/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>"
                                            alt="<?php echo htmlspecialchars($course['title']); ?>">
                                    <?php else: ?>
                                        <i class="fas fa-book-open"></i>
                                    <?php endif; ?>

                                    <div class="course-badge">
                                        <span class="status-badge <?php echo $course['status'] ?? 'pending'; ?>">
                                            <?php
                                            $status = $course['status'] ?? 'pending';
                                            echo match ($status) {
                                                'approved' => 'Đã duyệt',
                                                'rejected' => 'Đã từ chối',
                                                default => 'Chờ duyệt'
                                            };
                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="course-content">
                                    <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                                    <p class="course-description">
                                        <?php
                                        $description = strip_tags($course['description'] ?? '');
                                        echo htmlspecialchars(mb_substr($description, 0, 150, 'UTF-8'));
                                        if (mb_strlen($description, 'UTF-8') > 150) echo '...';
                                        ?>
                                    </p>

                                    <div class="course-meta">
                                        <div class="meta-item">
                                            <i class="fas fa-signal"></i>
                                            <span><?php echo htmlspecialchars($course['level'] ?? 'Beginner'); ?></span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo htmlspecialchars($course['duration_weeks'] ?? 0); ?> tuần</span>
                                        </div>
                                        <div class="meta-item">
                                            <i class="fas fa-list-ol"></i>
                                            <span>0 bài học</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <div class="course-price <?php echo ($course['price'] ?? 0) > 0 ? '' : 'free'; ?>">
                                        <?php if (($course['price'] ?? 0) > 0): ?>
                                            <?php echo number_format($course['price'], 0, ',', '.'); ?>đ
                                        <?php else: ?>
                                            Miễn phí
                                        <?php endif; ?>
                                    </div>

                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/lesson/manage/<?php echo $course['id']; ?>"
                                            class="action-btn" title="Quản lý bài học">
                                            <i class="fas fa-book-open"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/course/edit/<?php echo $course['id']; ?>"
                                            class="action-btn" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/courses/detail/<?php echo $course['id']; ?>"
                                            class="action-btn" target="_blank" title="Xem trước">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo BASE_URL; ?>/instructor/enrollments/<?php echo $course['id']; ?>"
                                            class="action-btn" title="Học viên">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <!-- NÚT XÓA -->
                                        <a href="<?php echo BASE_URL; ?>/course/delete/<?php echo $course['id']; ?>"
                                            class="action-btn delete-btn" title="Xóa khóa học"
                                            onclick="return confirmDelete(<?php echo $course['id']; ?>, '<?php echo addslashes(htmlspecialchars($course['title'])); ?>')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&category=<?php echo $_GET['category'] ?? ''; ?>&status=<?php echo $_GET['status'] ?? ''; ?>"
                                    class="pagination-btn">
                                    <i class="fas fa-chevron-left"></i>
                                    Trước
                                </a>
                            <?php else: ?>
                                <span class="pagination-btn" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                    Trước
                                </span>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php if ($i == $currentPage): ?>
                                    <span class="page-number active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&category=<?php echo $_GET['category'] ?? ''; ?>&status=<?php echo $_GET['status'] ?? ''; ?>"
                                        class="page-number">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&category=<?php echo $_GET['category'] ?? ''; ?>&status=<?php echo $_GET['status'] ?? ''; ?>"
                                    class="pagination-btn">
                                    Sau
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="pagination-btn" disabled>
                                    Sau
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3>Chưa có khóa học nào</h3>
                        <p>Bắt đầu sự nghiệp giảng dạy của bạn bằng cách tạo khóa học đầu tiên.</p>
                        <a href="<?php echo BASE_URL; ?>/course/create" class="btn-primary" style="margin-top: 16px;">
                            <i class="fas fa-plus-circle"></i>
                            Tạo khóa học đầu tiên
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Hàm xác nhận xóa khóa học
        function confirmDelete(courseId, courseTitle) {
            Swal.fire({
                title: 'Xác nhận xóa',
                html: `Bạn có chắc chắn muốn xóa khóa học:<br><strong>"${courseTitle}"</strong>?<br><br>Hành động này không thể hoàn tác và sẽ xóa tất cả bài học liên quan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#ef4444',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Chuyển hướng đến URL xóa
                    window.location.href = `<?php echo BASE_URL; ?>/course/delete/${courseId}`;
                }
            });
            return false; // Ngăn chặn hành động mặc định của link
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add click animation to buttons
            document.querySelectorAll('.action-btn, .btn-primary, .btn-logout, .btn-view-home').forEach(element => {
                element.addEventListener('click', function(e) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + C: Create new course
                if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                    e.preventDefault();
                    window.location.href = '<?php echo BASE_URL; ?>/course/create';
                }

                // Ctrl/Cmd + F: Focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    document.querySelector('input[name="search"]').focus();
                }

                // Ctrl/Cmd + H: Go to home page
                if ((e.ctrlKey || e.metaKey) && e.key === 'h') {
                    e.preventDefault();
                    window.open('<?php echo BASE_URL; ?>/', '_blank');
                }
            });

            // Course card hover effect
            document.querySelectorAll('.course-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>