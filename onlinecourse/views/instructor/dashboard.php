<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Giảng viên - Online Course</title>
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

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: var(--radius);
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,100 L1000,0 L1000,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-content h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            color: white;
        }

        .welcome-content p {
            font-size: 16px;
            opacity: 0.9;
            max-width: 600px;
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

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: var(--success);
            margin-top: 8px;
        }

        /* Recent Courses */
        .recent-courses {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .section-header {
            padding: 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-view-all {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-view-all:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .courses-table {
            width: 100%;
            border-collapse: collapse;
        }

        .courses-table thead {
            background: var(--light);
            border-bottom: 2px solid var(--border);
        }

        .courses-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .courses-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.3s;
        }

        .courses-table tbody tr:hover {
            background: var(--primary-light);
        }

        .courses-table td {
            padding: 16px;
            vertical-align: middle;
        }

        .course-title-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .course-title {
            font-weight: 600;
            color: var(--dark);
        }

        .course-category {
            font-size: 12px;
            color: var(--gray);
        }

        .price-badge {
            display: inline-flex;
            padding: 6px 12px;
            background: var(--success);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .price-badge.free {
            background: var(--gray-light);
            color: var(--gray);
        }

        .price-badge.paid {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

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

        /* Empty State */
        .empty-state {
            padding: 60px 20px;
            text-align: center;
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

        .empty-state h4 {
            font-size: 20px;
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

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .quick-action-card {
            background: white;
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .quick-action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #eef2ff, #c7d2fe);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
            color: var(--primary);
        }

        .quick-action-card h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .quick-action-card p {
            font-size: 12px;
            color: var(--gray);
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
            
            .statistics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
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
                grid-template-columns: 1fr;
            }
            
            .quick-actions-grid {
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
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/course/manage" class="sidebar-item">
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
                    <h1 class="page-title">Dashboard Giảng viên</h1>
                    <p class="page-subtitle">Quản lý khóa học và theo dõi hiệu suất</p>
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
                <!-- Welcome Banner -->
                <div class="welcome-banner">
                    <div class="welcome-content">
                        <h1>Xin chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?>! 👋</h1>
                        <p>Chào mừng trở lại bảng điều khiển giảng viên. Dưới đây là tổng quan về hoạt động giảng dạy của bạn.</p>
                    </div>
                </div>

                <!-- Statistics -->
                <?php
                // Kiểm tra và tính toán số liệu thống kê
                $totalCourses = isset($courses) ? count($courses) : 0;
                $approvedCourses = 0;
                $pendingCourses = 0;
                $rejectedCourses = 0;

                if (isset($courses) && is_array($courses)) {
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
                        <div class="stat-trend">
                            <i class="fas fa-chart-line"></i>
                            <span>+2 tuần này</span>
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
                        <div class="stat-trend">
                            <i class="fas fa-chart-line"></i>
                            <span>+1 tuần này</span>
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
                        <div class="stat-trend">
                            <i class="fas fa-chart-line"></i>
                            <span>+0 tuần này</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Courses -->
                <div class="recent-courses">
                    <div class="section-header">
                        <h3>
                            <i class="fas fa-history"></i>
                            Khóa học gần đây
                        </h3>
                        <a href="<?php echo BASE_URL; ?>/course/manage" class="btn-view-all">
                            <i class="fas fa-list"></i>
                            Xem tất cả
                        </a>
                    </div>
                    
                    <?php if (isset($courses) && !empty($courses)): ?>
                            <div class="table-container">
                                <table class="courses-table">
                                    <thead>
                                        <tr>
                                            <th>Tên khóa học</th>
                                            <th>Danh mục</th>
                                            <th>Giá</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $recentCourses = array_slice($courses, 0, 5);
                                        foreach ($recentCourses as $course):
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="course-title-cell">
                                                        <span class="course-title"><?php echo htmlspecialchars($course['title']); ?></span>
                                                        <span class="course-category"><?php echo htmlspecialchars($course['category_name'] ?? 'Chưa phân loại'); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($course['category_name'] ?? 'Chưa phân loại'); ?></td>
                                                <td>
                                                    <?php if (isset($course['price']) && $course['price'] > 0): ?>
                                                            <span class="price-badge paid">
                                                                <?php echo number_format($course['price'], 0, ',', '.'); ?>đ
                                                            </span>
                                                    <?php else: ?>
                                                            <span class="price-badge free">Miễn phí</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status = $course['status'] ?? 'pending';
                                                    ?>
                                                    <span class="status-badge <?php echo $status; ?>">
                                                        <i class="fas fa-circle"></i>
                                                        <?php echo match ($status) {
                                                            'pending' => 'Chờ duyệt',
                                                            'approved' => 'Đã duyệt',
                                                            'rejected' => 'Từ chối',
                                                            default => 'Chờ duyệt'
                                                        }; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="<?php echo BASE_URL; ?>/course/edit/<?php echo $course['id']; ?>"
                                                           class="action-btn" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="<?php echo BASE_URL; ?>/lesson/manage/<?php echo $course['id']; ?>"
                                                           class="action-btn" title="Quản lý bài học">
                                                            <i class="fas fa-book-open"></i>
                                                        </a>
                                                        <a href="<?php echo BASE_URL; ?>/courses/detail/<?php echo $course['id']; ?>"
                                                           class="action-btn" target="_blank" title="Xem trước">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                    <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h4>Chưa có khóa học nào</h4>
                                <p>Bắt đầu sự nghiệp giảng dạy của bạn bằng cách tạo khóa học đầu tiên.</p>
                                <a href="<?php echo BASE_URL; ?>/course/create" class="btn-view-all" style="margin-top: 16px;">
                                    <i class="fas fa-plus-circle"></i>
                                    Tạo khóa học đầu tiên
                                </a>
                            </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions-grid">
                    <a href="<?php echo BASE_URL; ?>/course/create" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h4>Tạo khóa học mới</h4>
                        <p>Bắt đầu xây dựng nội dung giảng dạy</p>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/course/manage" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h4>Quản lý khóa học</h4>
                        <p>Xem và chỉnh sửa tất cả khóa học</p>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/instructor/enrollments" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Học viên của tôi</h4>
                        <p>Quản lý và theo dõi học viên</p>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/instructor/progress" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Theo dõi tiến độ</h4>
                        <p>Phân tích hiệu suất khóa học</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add click animation to buttons and cards
            document.querySelectorAll('.action-btn, .btn-view-all, .btn-logout, .btn-view-home, .quick-action-card').forEach(element => {
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
                
                // Ctrl/Cmd + M: Manage courses
                if ((e.ctrlKey || e.metaKey) && e.key === 'm') {
                    e.preventDefault();
                    window.location.href = '<?php echo BASE_URL; ?>/course/manage';
                }
                
                // Ctrl/Cmd + H: Go to home page
                if ((e.ctrlKey || e.metaKey) && e.key === 'h') {
                    e.preventDefault();
                    window.open('<?php echo BASE_URL; ?>/', '_blank');
                }
            });
            
            // Update page title with notification count
            const updateNotificationCount = () => {
                const pendingCount = <?php echo $pendingCourses; ?>;
                if (pendingCount > 0) {
                    document.title = `(${pendingCount}) Dashboard Giảng viên - Online Course`;
                }
            };
            
            updateNotificationCount();
            
            // Add hover effects to table rows
            document.querySelectorAll('.courses-table tbody tr').forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'var(--primary-light)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
            
            // Add hover effects to stat cards
            document.querySelectorAll('.stat-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>