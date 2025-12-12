<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Bài học - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Modern Design Styles */
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

        /* Modern Layout */
        .modern-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Modern Sidebar */
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
            background: #ef4444;
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

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            font-size: 14px;
        }

        .breadcrumb a {
            color: var(--gray);
            text-decoration: none;
            transition: color 0.3s;
        }

        .breadcrumb a:hover {
            color: var(--primary);
        }

        .breadcrumb i {
            font-size: 12px;
        }

        .top-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-notification {
            background: none;
            border: none;
            position: relative;
            cursor: pointer;
            color: var(--gray);
            transition: color 0.3s;
            font-size: 18px;
        }

        .btn-notification:hover {
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* Main Content Area */
        .content-area {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Course Header */
        .course-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 32px;
            border-radius: var(--radius);
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .course-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,100 L1000,0 L1000,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }

        .course-header-content {
            position: relative;
            z-index: 1;
        }

        .course-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .course-title-wrapper h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 12px 0;
            color: white;
        }

        .course-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .course-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: white;
            color: var(--primary);
            border: none;
            padding: 12px 24px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Statistics Cards */
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
            background: linear-gradient(135deg, #dbeafe, #93c5fd);
            color: #1d4ed8;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, #fef3c7, #fcd34d);
            color: #d97706;
        }

        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, #dcfce7, #86efac);
            color: #059669;
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

        .stat-progress {
            margin-top: 16px;
        }

        .progress-bar {
            height: 6px;
            background: var(--gray-light);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 3px;
            transition: width 0.3s;
        }

        /* Main Content Layout */
        .main-content-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 32px;
        }

        @media (max-width: 1024px) {
            .main-content-layout {
                grid-template-columns: 1fr;
            }
        }

        /* Lessons Section */
        .lessons-section {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
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

        .search-container {
            display: flex;
            gap: 12px;
        }

        .search-box {
            position: relative;
            width: 300px;
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
            padding: 12px 16px 12px 48px;
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

        .view-toggle {
            display: flex;
            background: var(--gray-light);
            border-radius: var(--radius-sm);
            padding: 4px;
        }

        .view-btn {
            padding: 8px 16px;
            border: none;
            background: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            transition: all 0.3s;
        }

        .view-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow);
        }

        /* Lessons Table */
        .lessons-table {
            width: 100%;
            border-collapse: collapse;
        }

        .lessons-table thead {
            background: var(--light);
            border-bottom: 2px solid var(--border);
        }

        .lessons-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lessons-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.3s;
        }

        .lessons-table tbody tr:hover {
            background: var(--primary-light);
        }

        .lessons-table td {
            padding: 16px;
            vertical-align: middle;
        }

        .lesson-order {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .order-badge {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .order-actions {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .order-btn {
            width: 24px;
            height: 24px;
            border: 1px solid var(--border);
            background: white;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: var(--gray);
            transition: all 0.3s;
        }

        .order-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .order-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .lesson-title-cell h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
        }

        .lesson-preview {
            font-size: 13px;
            color: var(--gray);
            line-height: 1.4;
        }

        .video-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .video-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--success);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .no-video-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: var(--gray-light);
            color: var(--gray);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .action-cell {
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

        /* Cards View */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            padding: 24px;
        }

        .lesson-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
        }

        .lesson-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .card-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-order {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            background: white;
            padding: 4px 12px;
            border-radius: 20px;
            border: 2px solid var(--primary-light);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            margin: 0 0 12px 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }

        .card-content {
            font-size: 14px;
            color: var(--gray);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .card-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--gray);
            background: var(--light);
            padding: 4px 8px;
            border-radius: 6px;
        }

        .card-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--light);
        }

        .card-date {
            font-size: 12px;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Side Panel */
        .side-panel {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .panel-card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .panel-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-header h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-body {
            padding: 20px;
        }

        .course-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .stat-item {
            text-align: center;
            padding: 16px;
            background: var(--light);
            border-radius: var(--radius-sm);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin-top: 8px;
        }

        .stat-value.price {
            color: var(--success);
        }

        .quick-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            background: var(--light);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.3s;
            text-align: left;
            color: var(--dark);
            font-size: 14px;
        }

        .quick-action-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateX(4px);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #eef2ff, #c7d2fe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .modern-layout {
                grid-template-columns: 1fr;
            }

            .modern-sidebar {
                display: none;
            }

            .content-area {
                padding: 16px;
            }

            .course-header {
                padding: 24px;
            }

            .course-header-top {
                flex-direction: column;
                gap: 16px;
            }

            .search-box {
                width: 100%;
            }

            .statistics-grid {
                grid-template-columns: 1fr;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .course-stats {
                grid-template-columns: 1fr;
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
                    <span class="badge-count"><?php echo $courseCount ?? 0; ?></span>
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
                <a href="<?php echo BASE_URL; ?>/courses/detail/<?php echo $course['id']; ?>" class="sidebar-item"
                    target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Xem công khai</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="modern-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="breadcrumb">
                    <a href="<?php echo BASE_URL; ?>/instructor/dashboard">Dashboard</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="<?php echo BASE_URL; ?>/course/manage">Khóa học</a>
                    <i class="fas fa-chevron-right"></i>
                    <span><?php echo htmlspecialchars($course['title']); ?></span>
                </div>

                <div class="top-actions">
                    <button class="btn-notification">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Course Header -->
                <div class="course-header">
                    <div class="course-header-content">
                        <div class="course-header-top">
                            <div class="course-title-wrapper">
                                <h1><?php echo htmlspecialchars($course['title']); ?></h1>
                                <div class="course-meta">
                                    <span class="course-meta-item">
                                        <i class="fas fa-signal"></i>
                                        <?php echo htmlspecialchars($course['level']); ?>
                                    </span>
                                    <span class="course-meta-item">
                                        <i class="fas fa-clock"></i>
                                        <?php echo $course['duration_weeks']; ?> tuần
                                    </span>
                                    <span class="course-meta-item">
                                        <i class="fas fa-tag"></i>
                                        <?php echo $course['price'] > 0 ? number_format($course['price'], 0, ',', '.') . 'đ' : 'Miễn phí'; ?>
                                    </span>
                                    <span class="course-meta-item <?php echo $course['status']; ?>">
                                        <i class="fas fa-circle"></i>
                                        <?php
                                        echo match ($course['status']) {
                                            'approved' => 'Đã duyệt',
                                            'rejected' => 'Đã từ chối',
                                            default => 'Chờ duyệt'
                                        };
                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="header-actions">
                                <a href="<?php echo BASE_URL; ?>/course/manage" class="btn-back">
                                    <i class="fas fa-arrow-left"></i>
                                    Quay lại
                                </a>
                                <a href="<?php echo BASE_URL; ?>/lesson/create/<?php echo $course['id']; ?>"
                                    class="btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Thêm bài học mới
                                </a>
                            </div>
                        </div>

                        <p
                            style="color: rgba(255, 255, 255, 0.9); font-size: 14px; line-height: 1.6; max-width: 800px;">
                            <?php echo htmlspecialchars(mb_substr($course['description'], 0, 200, 'UTF-8')); ?>...
                        </p>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="statistics-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number"><?php echo count($lessons); ?></div>
                                <div class="stat-label">Tổng bài học</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-list-ol"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number">
                                    <?php
                                    $videos_count = 0;
                                    foreach ($lessons as $lesson) {
                                        if (!empty($lesson['video_url'])) {
                                            $videos_count++;
                                        }
                                    }
                                    echo $videos_count;
                                    ?>
                                </div>
                                <div class="stat-label">Bài học có video</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-video"></i>
                            </div>
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar">
                                <div class="progress-fill"
                                    style="width: <?php echo count($lessons) > 0 ? ($videos_count / count($lessons) * 100) : 0; ?>%">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number">
                                    <?php
                                    $total_chars = 0;
                                    foreach ($lessons as $lesson) {
                                        $total_chars += strlen(strip_tags($lesson['content']));
                                    }
                                    $avg_chars = count($lessons) > 0 ? round($total_chars / count($lessons)) : 0;
                                    echo number_format($avg_chars);
                                    ?>
                                </div>
                                <div class="stat-label">Ký tự trung bình</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div>
                                <div class="stat-number">
                                    <?php echo count($lessons) > 0 ? '100%' : '0%'; ?>
                                </div>
                                <div class="stat-label">Hoàn thành nội dung</div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Layout -->
                <div class="main-content-layout">
                    <!-- Lessons Section -->
                    <div class="lessons-section">
                        <div class="section-header">
                            <h3>
                                <i class="fas fa-book-open"></i>
                                Danh sách bài học
                            </h3>

                            <div class="search-container">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" id="searchLessons" placeholder="Tìm kiếm bài học..."
                                        onkeyup="searchLessons()">
                                </div>

                                <div class="view-toggle">
                                    <button class="view-btn active" onclick="toggleViewMode('table')">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button class="view-btn" onclick="toggleViewMode('card')">
                                        <i class="fas fa-th"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table View -->
                        <div id="tableView" style="display: block;">
                            <table class="lessons-table">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Thứ tự</th>
                                        <th>Tên bài học</th>
                                        <th style="width: 120px;">Video</th>
                                        <th style="width: 120px;">Nội dung</th>
                                        <th style="width: 140px;">Ngày tạo</th>
                                        <th style="width: 160px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="lessonsTableBody">
                                    <?php if (!empty($lessons)): ?>
                                        <?php foreach ($lessons as $index => $lesson): ?>
                                            <tr>
                                                <td>
                                                    <div class="lesson-order">
                                                        <div class="order-badge">
                                                            <?php echo $lesson['order_num']; ?>
                                                        </div>
                                                        <div class="order-actions">
                                                            <button class="order-btn move-up"
                                                                onclick="moveLessonUp(<?php echo $lesson['id']; ?>)" <?php echo $index === 0 ? 'disabled' : ''; ?>>
                                                                <i class="fas fa-arrow-up"></i>
                                                            </button>
                                                            <button class="order-btn move-down"
                                                                onclick="moveLessonDown(<?php echo $lesson['id']; ?>)" <?php echo $index === count($lessons) - 1 ? 'disabled' : ''; ?>>
                                                                <i class="fas fa-arrow-down"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="lesson-title-cell">
                                                        <h4><?php echo htmlspecialchars($lesson['title']); ?></h4>
                                                        <div class="lesson-preview">
                                                            <?php
                                                            $content_preview = strip_tags($lesson['content']);
                                                            $preview = mb_substr($content_preview, 0, 80, 'UTF-8');
                                                            echo htmlspecialchars($preview);
                                                            if (mb_strlen($content_preview, 'UTF-8') > 80)
                                                                echo '...';
                                                            ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (!empty($lesson['video_url'])): ?>
                                                        <span class="video-badge">
                                                            <i class="fas fa-play-circle"></i>
                                                            Có video
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="no-video-badge">
                                                            <i class="fas fa-times-circle"></i>
                                                            Không có
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $content_length = strlen(strip_tags($lesson['content']));
                                                    if ($content_length < 1000) {
                                                        echo number_format($content_length) . ' ký tự';
                                                    } else {
                                                        echo round($content_length / 1000, 1) . 'k ký tự';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo date('d/m/Y', strtotime($lesson['created_at'])); ?>
                                                </td>
                                                <td>
                                                    <div class="action-cell">
                                                        <a href="<?php echo BASE_URL; ?>/lesson/edit/<?php echo $lesson['id']; ?>"
                                                            class="action-btn" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="<?php echo BASE_URL; ?>/lesson/preview/<?php echo $lesson['id']; ?>"
                                                            class="action-btn" target="_blank" title="Xem trước">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?php echo BASE_URL; ?>/lesson/materials/<?php echo $lesson['id']; ?>"
                                                            class="action-btn" title="Tài liệu">
                                                            <i class="fas fa-paperclip"></i>
                                                        </a>
                                                        <button class="action-btn delete-btn"
                                                            onclick="confirmDelete(<?php echo $lesson['id']; ?>, '<?php echo htmlspecialchars(addslashes($lesson['title'])); ?>')"
                                                            title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--gray);">
                                                <i class="fas fa-book-open"
                                                    style="font-size: 48px; margin-bottom: 16px; display: block; color: var(--border);"></i>
                                                <p style="font-size: 16px; margin-bottom: 16px;">Chưa có bài học nào</p>
                                                <a href="<?php echo BASE_URL; ?>/lesson/create/<?php echo $course['id']; ?>"
                                                    class="btn-primary" style="display: inline-flex;">
                                                    <i class="fas fa-plus"></i>
                                                    Thêm bài học đầu tiên
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Cards View -->
                        <div id="cardView" style="display: none;">
                            <div class="cards-grid" id="lessonsGrid">
                                <?php if (!empty($lessons)): ?>
                                    <?php foreach ($lessons as $lesson): ?>
                                        <div class="lesson-card">
                                            <div class="card-header">
                                                <div class="card-order">
                                                    #<?php echo $lesson['order_num']; ?>
                                                </div>
                                                <div class="card-actions">
                                                    <div class="action-cell">
                                                        <a href="<?php echo BASE_URL; ?>/lesson/edit/<?php echo $lesson['id']; ?>"
                                                            class="action-btn" title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="action-btn delete-btn"
                                                            onclick="confirmDelete(<?php echo $lesson['id']; ?>, '<?php echo htmlspecialchars(addslashes($lesson['title'])); ?>')"
                                                            title="Xóa">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title"><?php echo htmlspecialchars($lesson['title']); ?></h4>
                                                <div class="card-content">
                                                    <?php
                                                    $content_preview = strip_tags($lesson['content']);
                                                    $preview = mb_substr($content_preview, 0, 100, 'UTF-8');
                                                    echo htmlspecialchars($preview);
                                                    if (mb_strlen($content_preview, 'UTF-8') > 100)
                                                        echo '...';
                                                    ?>
                                                </div>
                                                <div class="card-meta">
                                                    <?php if (!empty($lesson['video_url'])): ?>
                                                        <div class="meta-item">
                                                            <i class="fas fa-video"></i>
                                                            <span>Có video</span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="meta-item">
                                                        <i class="fas fa-file-alt"></i>
                                                        <span>
                                                            <?php
                                                            $content_length = strlen(strip_tags($lesson['content']));
                                                            if ($content_length < 1000) {
                                                                echo number_format($content_length) . ' ký tự';
                                                            } else {
                                                                echo round($content_length / 1000, 1) . 'k ký tự';
                                                            }
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="card-date">
                                                    <i class="fas fa-calendar"></i>
                                                    <?php echo date('d/m/Y', strtotime($lesson['created_at'])); ?>
                                                </div>
                                                <div class="card-actions-footer">
                                                    <a href="<?php echo BASE_URL; ?>/lesson/preview/<?php echo $lesson['id']; ?>"
                                                        class="action-btn" target="_blank" title="Xem trước">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>/lesson/materials/<?php echo $lesson['id']; ?>"
                                                        class="action-btn" title="Tài liệu">
                                                        <i class="fas fa-paperclip"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="empty-state" style="grid-column: 1 / -1;">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <h3>Chưa có bài học nào</h3>
                                        <p>Bắt đầu xây dựng nội dung cho khóa học của bạn bằng cách thêm bài học đầu tiên.
                                        </p>
                                        <a href="<?php echo BASE_URL; ?>/lesson/create/<?php echo $course['id']; ?>"
                                            class="btn-primary">
                                            <i class="fas fa-plus-circle"></i>
                                            Thêm bài học đầu tiên
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Side Panel -->
                    <div class="side-panel">
                        <!-- Course Info -->
                        <div class="panel-card">
                            <div class="panel-header">
                                <h4>
                                    <i class="fas fa-info-circle"></i>
                                    Thông tin khóa học
                                </h4>
                                <a href="<?php echo BASE_URL; ?>/course/edit/<?php echo $course['id']; ?>"
                                    class="action-btn">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                            <div class="panel-body">
                                <p style="color: var(--gray); font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                                    <?php echo htmlspecialchars(mb_substr($course['description'], 0, 150, 'UTF-8')); ?>...
                                </p>

                                <div class="course-stats">
                                    <div class="stat-item">
                                        <div class="stat-label">Trạng thái</div>
                                        <div class="stat-value <?php echo $course['status']; ?>">
                                            <?php
                                            echo match ($course['status']) {
                                                'approved' => '✓',
                                                'rejected' => '✗',
                                                default => '⏳'
                                            };
                                            ?>
                                        </div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Bài học</div>
                                        <div class="stat-value"><?php echo count($lessons); ?></div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Thời lượng</div>
                                        <div class="stat-value"><?php echo $course['duration_weeks']; ?>w</div>
                                    </div>
                                    <div class="stat-item">
                                        <div class="stat-label">Giá</div>
                                        <div class="stat-value price">
                                            <?php echo $course['price'] > 0 ? number_format($course['price'], 0, ',', '.') . 'đ' : 'Free'; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="panel-card">
                            <div class="panel-header">
                                <h4>
                                    <i class="fas fa-bolt"></i>
                                    Thao tác nhanh
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="quick-actions">
                                    <button class="quick-action-btn" onclick="reorderLessons()">
                                        <i class="fas fa-sort-amount-down"></i>
                                        Sắp xếp tự động
                                    </button>
                                    <button class="quick-action-btn" onclick="exportLessons()">
                                        <i class="fas fa-download"></i>
                                        Xuất danh sách
                                    </button>
                                    <button class="quick-action-btn" onclick="bulkAddLessons()">
                                        <i class="fas fa-layer-group"></i>
                                        Thêm nhiều bài học
                                    </button>
                                    <a href="<?php echo BASE_URL; ?>/instructor/enrollments/<?php echo $course['id']; ?>"
                                        class="quick-action-btn">
                                        <i class="fas fa-users"></i>
                                        Xem học viên
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // View mode toggle
        function toggleViewMode(mode) {
            const tableView = document.getElementById('tableView');
            const cardView = document.getElementById('cardView');
            const tableBtn = document.querySelector('.view-toggle button:nth-child(1)');
            const cardBtn = document.querySelector('.view-toggle button:nth-child(2)');

            if (mode === 'table') {
                tableView.style.display = 'block';
                cardView.style.display = 'none';
                tableBtn.classList.add('active');
                cardBtn.classList.remove('active');
            } else {
                tableView.style.display = 'none';
                cardView.style.display = 'grid';
                tableBtn.classList.remove('active');
                cardBtn.classList.add('active');
            }
        }

        // Search functionality
        function searchLessons() {
            const searchTerm = document.getElementById('searchLessons').value.toLowerCase();
            const rows = document.querySelectorAll('#lessonsTableBody tr:not(:first-child)');

            rows.forEach(row => {
                const title = row.querySelector('.lesson-title-cell h4').textContent.toLowerCase();
                const preview = row.querySelector('.lesson-preview').textContent.toLowerCase();

                if (title.includes(searchTerm) || preview.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Confirm delete
        function confirmDelete(lessonId, lessonTitle) {
            Swal.fire({
                title: 'Xác nhận xóa',
                html: `Bạn có chắc chắn muốn xóa bài học:<br><strong>"${lessonTitle}"</strong>?<br><br>Hành động này không thể hoàn tác.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#ef4444',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?php echo BASE_URL; ?>/lesson/delete/${lessonId}`;
                }
            });
        }

        // Move lesson functions
        function moveLessonUp(lessonId) {
            fetch(`<?php echo BASE_URL; ?>/lesson/moveUp/${lessonId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        Swal.fire('Lỗi', data.message || 'Không thể di chuyển bài học', 'error');
                    }
                });
        }

        function moveLessonDown(lessonId) {
            fetch(`<?php echo BASE_URL; ?>/lesson/moveDown/${lessonId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        Swal.fire('Lỗi', data.message || 'Không thể di chuyển bài học', 'error');
                    }
                });
        }

        // Other functions
        function reorderLessons() {
            Swal.fire('Thông báo', 'Tính năng đang được phát triển', 'info');
        }

        function exportLessons() {
            window.open(`<?php echo BASE_URL; ?>/lesson/export/<?php echo $course['id']; ?>`, '_blank');
        }

        function bulkAddLessons() {
            Swal.fire('Thông báo', 'Tính năng đang được phát triển', 'info');
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            // Add keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                // Ctrl/Cmd + F: Focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    document.getElementById('searchLessons').focus();
                }

                // Ctrl/Cmd + N: New lesson
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = `<?php echo BASE_URL; ?>/lesson/create/<?php echo $course['id']; ?>`;
                }
            });

            // Add click animation to buttons
            document.querySelectorAll('.action-btn, .btn-primary, .quick-action-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
        });
    </script>
</body>

</html>