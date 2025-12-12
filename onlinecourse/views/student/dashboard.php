<?php
// File: views/student/dashboard.php
// Dữ liệu cần: $student, $stats, $enrolledCourses, $recentCourses, $upcomingLessons
$student = $student ?? [];
$stats = $stats ?? [
    'total_courses' => 0,
    'in_progress' => 0,
    'completed' => 0,
    'avg_progress' => 0
];
$enrolledCourses = $enrolledCourses ?? [];
$recentCourses = array_slice($enrolledCourses ?? [], 0, 3);
$upcomingLessons = $upcomingLessons ?? [];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Học viên - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #eef2ff;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --gray-light: #f8fafc;
            --gray-border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f9fafb;
            color: #334155;
            line-height: 1.6;
        }

        /* ===== NAVBAR ===== */
        .navbar-student {
            background: white;
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color);
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .navbar-brand:hover {
            opacity: 0.9;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon i {
            font-size: 18px;
            color: white;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: var(--shadow-md);
            min-height: calc(100vh - 70px);
            position: fixed;
            left: 0;
            top: 70px;
            padding: 1.5rem 0;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--gray-border);
            margin-bottom: 1rem;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .student-email {
            font-size: 0.875rem;
            color: #64748b;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1.5rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
        }

        .sidebar-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-item:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, #3730a3 100%);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .badge-notification {
            background-color: var(--danger-color);
            color: white;
            font-size: 0.75rem;
            padding: 0.15rem 0.5rem;
            border-radius: 10px;
            margin-left: auto;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar.active {
                transform: translateX(0);
            }
        }

        /* ===== HEADER ===== */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .page-title i {
            color: var(--primary-color);
            background: var(--primary-light);
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* ===== STATS CARDS ===== */
        .stats-card {
            background: white;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stats-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .icon-primary {
            background-color: #eef2ff;
            color: var(--primary-color);
        }

        .icon-success {
            background-color: #d1fae5;
            color: var(--success-color);
        }

        .icon-warning {
            background-color: #fef3c7;
            color: var(--warning-color);
        }

        .icon-info {
            background-color: #dbeafe;
            color: var(--info-color);
        }

        .stats-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .stats-label {
            color: #64748b;
            font-size: 0.875rem;
        }

        .stats-change {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .change-up {
            background-color: #d1fae5;
            color: #065f46;
        }

        .change-down {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* ===== COURSE CARDS ===== */
        .course-card {
            background: white;
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            height: 100%;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .course-image {
            height: 160px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .course-level {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .level-beginner {
            background: #d1fae5;
            color: #065f46;
        }

        .level-intermediate {
            background: #fef3c7;
            color: #92400e;
        }

        .level-advanced {
            background: #fee2e2;
            color: #991b1b;
        }

        .course-content {
            padding: 1.5rem;
        }

        .course-category {
            font-size: 0.75rem;
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .course-title {
            font-weight: 700;
            color: var(--dark-color);
            font-size: 1.125rem;
            margin-bottom: 0.75rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .course-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .course-instructor {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instructor-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .progress-container {
            margin-bottom: 1rem;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        .progress-bar {
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-100 {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .course-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-continue {
            flex: 1;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-continue:hover {
            background: #3730a3;
            color: white;
            transform: translateY(-2px);
        }

        .btn-certificate {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-certificate:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
        }

        /* ===== UPCOMING LESSONS ===== */
        .lesson-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: var(--radius-sm);
            margin-bottom: 0.75rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
        }

        .lesson-item:hover {
            transform: translateX(5px);
            box-shadow: var(--shadow-md);
        }

        .lesson-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .lesson-info h5 {
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .lesson-meta {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* ===== SECTION HEADERS ===== */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--dark-color);
        }

        .view-all {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: white;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            color: var(--primary-color);
        }

        .action-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }

        .action-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .action-desc {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* ===== EMPTY STATES ===== */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #94a3b8;
            max-width: 400px;
            margin: 0 auto 1.5rem;
        }

        /* ===== MOBILE TOGGLE ===== */
        .sidebar-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        @media (max-width: 992px) {
            .sidebar-toggle {
                display: block;
            }

            .quick-actions {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-student">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle me-3">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                    <div class="brand-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span>Online Course</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Học viên'); ?></div>
                    <div class="text-muted small">Học viên</div>
                </div>
                <div class="student-avatar">
                    <?php echo strtoupper(substr($_SESSION['fullname'] ?? 'H', 0, 1)); ?>
                </div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="student-info">
                <div class="student-avatar">
                    <?php echo strtoupper(substr($_SESSION['fullname'] ?? 'H', 0, 1)); ?>
                </div>
                <div>
                    <div class="student-name"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Học viên'); ?></div>
                    <div class="student-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="<?php echo BASE_URL; ?>/student/dashboard" class="sidebar-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/my-courses" class="sidebar-item">
                <i class="fas fa-book-open"></i>
                <span>Khóa học của tôi</span>
                <?php if ($stats['total_courses'] > 0): ?>
                    <span class="badge-notification"><?php echo $stats['total_courses']; ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo BASE_URL; ?>/courses/explore" class="sidebar-item">
                <i class="fas fa-search"></i>
                <span>Tìm khóa học</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/progress" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                <span>Tiến độ học tập</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/calendar" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Lịch học</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/materials" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                <span>Tài liệu học tập</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/certificates" class="sidebar-item">
                <i class="fas fa-award"></i>
                <span>Chứng chỉ</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/profile" class="sidebar-item">
                <i class="fas fa-user"></i>
                <span>Hồ sơ cá nhân</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard Học viên
            </h1>
            <p class="page-subtitle">
                Chào mừng <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Học viên'); ?>! Theo dõi tiến độ học tập
                của bạn tại đây.
            </p>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions mb-4">
            <a href="<?php echo BASE_URL; ?>/courses" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="action-title">Tìm khóa học</h4>
                <p class="action-desc">Khám phá các khóa học mới</p>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/my-courses" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h4 class="action-title">Khóa học của tôi</h4>
                <p class="action-desc">Tiếp tục học tập</p>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/course_progress" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="action-title">Tiến độ học tập</h4>
                <p class="action-desc">Theo dõi kết quả</p>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/materials" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-file-download"></i>
                </div>
                <h4 class="action-title">Tài liệu</h4>
                <p class="action-desc">Tải tài liệu học tập</p>
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-card-icon icon-primary">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stats-number"><?php echo $stats['total_courses']; ?></div>
                    <div class="stats-label">Tổng khóa học</div>
                    <span class="stats-change change-up">
                        <i class="fas fa-arrow-up me-1"></i> <?php echo count($enrolledCourses); ?> đang học
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-card-icon icon-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo $stats['completed']; ?></div>
                    <div class="stats-label">Đã hoàn thành</div>
                    <span class="stats-change change-up">
                        <i class="fas fa-trophy me-1"></i> <?php echo $stats['completed']; ?> chứng chỉ
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-card-icon icon-warning">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <div class="stats-number"><?php echo $stats['in_progress']; ?></div>
                    <div class="stats-label">Đang học</div>
                    <span class="stats-change change-up">
                        <i class="fas fa-clock me-1"></i> Tiếp tục học
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-card-icon icon-info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-number"><?php echo $stats['avg_progress']; ?>%</div>
                    <div class="stats-label">Tiến độ trung bình</div>
                    <span class="stats-change change-up">
                        <i class="fas fa-arrow-up me-1"></i> Cập nhật hôm nay
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Courses & Upcoming Lessons -->
        <div class="row g-4">
            <!-- Recent Courses -->
            <div class="col-lg-8">
                <div class="section-header">
                    <h3 class="section-title">Khóa học gần đây</h3>
                    <a href="<?php echo BASE_URL; ?>/student/my-courses" class="view-all">
                        Xem tất cả <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <?php if (!empty($enrolledCourses)): ?>
                    <div class="row g-4">
                        <?php foreach ($recentCourses as $course): ?>
                            <div class="col-md-6">
                                <div class="course-card">
                                    <div class="course-image">
                                        <?php if ($course['image']): ?>
                                            <img src="<?php echo BASE_URL . '/assets/images/' . htmlspecialchars($course['image']); ?>"
                                                alt="<?php echo htmlspecialchars($course['title']); ?>">
                                        <?php else: ?>
                                            <div class="d-flex align-items-center justify-content-center h-100 text-white">
                                                <i class="fas fa-book fa-3x"></i>
                                            </div>
                                        <?php endif; ?>
                                        <span
                                            class="course-level level-<?php echo strtolower($course['level'] ?? 'beginner'); ?>">
                                            <?php echo htmlspecialchars($course['level'] ?? 'Cơ bản'); ?>
                                        </span>
                                    </div>
                                    <div class="course-content">
                                        <div class="course-category">
                                            <?php echo htmlspecialchars($course['category_name'] ?? 'Khác'); ?>
                                        </div>
                                        <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                                        <div class="course-meta">
                                            <div class="course-instructor">
                                                <div class="instructor-avatar">
                                                    <?php echo strtoupper(substr($course['instructor_name'] ?? 'G', 0, 1)); ?>
                                                </div>
                                                <span><?php echo htmlspecialchars($course['instructor_name'] ?? 'Giảng viên'); ?></span>
                                            </div>
                                            <div>
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo htmlspecialchars($course['duration_weeks'] ?? '0'); ?> tuần
                                            </div>
                                        </div>

                                        <div class="progress-container">
                                            <div class="progress-label">
                                                <span>Tiến độ</span>
                                                <span><?php echo $course['progress'] ?? 0; ?>%</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill <?php echo ($course['progress'] ?? 0) == 100 ? 'progress-100' : ''; ?>"
                                                    style="width: <?php echo $course['progress'] ?? 0; ?>%"></div>
                                            </div>
                                        </div>

                                        <div class="course-actions">
                                            <?php if (($course['progress'] ?? 0) < 100): ?>
                                                <a href="<?php echo BASE_URL; ?>/student/course/<?php echo $course['course_id'] ?? $course['id']; ?>"
                                                    class="btn-continue">
                                                    <i class="fas fa-play-circle me-2"></i> Tiếp tục học
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo BASE_URL; ?>/student/certificate/<?php echo $course['course_id'] ?? $course['id']; ?>"
                                                    class="btn-certificate">
                                                    <i class="fas fa-award me-2"></i> Xem chứng chỉ
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <h4 class="mb-2">Chưa có khóa học nào</h4>
                        <p class="mb-4">Bắt đầu học ngay bằng cách đăng ký các khóa học phù hợp với bạn!</p>
                        <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Tìm khóa học
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Upcoming Lessons & Notifications -->
            <div class="col-lg-4">
                <!-- Upcoming Lessons -->
                <div class="mb-4">
                    <div class="section-header">
                        <h3 class="section-title">Bài học sắp tới</h3>
                    </div>

                    <?php if (!empty($upcomingLessons)): ?>
                        <?php foreach ($upcomingLessons as $lesson): ?>
                            <div class="lesson-item">
                                <div class="lesson-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                                <div class="lesson-info">
                                    <h5><?php echo htmlspecialchars($lesson['title']); ?></h5>
                                    <div class="lesson-meta">
                                        <i class="fas fa-book me-1"></i>
                                        <?php echo htmlspecialchars($lesson['course_title']); ?>
                                        <br>
                                        <i class="far fa-clock me-1"></i>
                                        <?php echo date('H:i d/m', strtotime($lesson['start_time'] ?? 'now')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-light">
                            <i class="fas fa-info-circle me-2"></i>
                            Không có bài học nào sắp diễn ra.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Notifications -->
                <div class="mb-4">
                    <div class="section-header">
                        <h3 class="section-title">Thông báo</h3>
                    </div>
                    <div class="alert alert-primary">
                        <i class="fas fa-bell me-2"></i>
                        <strong>Thông báo mới:</strong> Đã có 3 khóa học mới được thêm vào thư viện.
                    </div>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Chúc mừng:</strong> Bạn đã hoàn thành 2 khóa học trong tháng này.
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nhắc nhở:</strong> 2 khóa học của bạn sắp hết hạn.
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <div class="section-header">
                        <h3 class="section-title">Truy cập nhanh</h3>
                    </div>
                    <div class="list-group">
                        <a href="<?php echo BASE_URL; ?>/student/profile"
                            class="list-group-item list-group-item-action">
                            <i class="fas fa-user-cog me-2"></i> Cài đặt tài khoản
                        </a>
                        <a href="<?php echo BASE_URL; ?>/student/certificates"
                            class="list-group-item list-group-item-action">
                            <i class="fas fa-award me-2"></i> Chứng chỉ của tôi
                        </a>
                        <a href="<?php echo BASE_URL; ?>/student/materials"
                            class="list-group-item list-group-item-action">
                            <i class="fas fa-download me-2"></i> Tải tài liệu
                        </a>
                        <a href="<?php echo BASE_URL; ?>/help" class="list-group-item list-group-item-action">
                            <i class="fas fa-question-circle me-2"></i> Trợ giúp & Hỗ trợ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.querySelector('.sidebar-toggle').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Auto update progress bars
        document.addEventListener('DOMContentLoaded', function () {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const targetWidth = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = targetWidth;
                }, 100);
            });
        });

        // Mark notifications as read
        document.querySelectorAll('.alert').forEach(alert => {
            alert.addEventListener('click', function () {
                this.style.opacity = '0.7';
            });
        });
    </script>
</body>

</html>