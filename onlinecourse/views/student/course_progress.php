<?php
// File: views/student/course_progress.php
// Dữ liệu từ Controller: $myCourses (chứa progress)
$myCourses = $myCourses ?? [];
$courses = $myCourses; // Dùng $courses để đồng nhất với logic thống kê

// Tính toán thống kê (Lấy từ Dashboard Controller logic)
$totalCourses = count($courses);
$completedCount = 0;
$totalProgress = 0;

foreach ($courses as $course) {
    // Giả định khóa 'progress' tồn tại và là số %
    $progress = $course['progress'] ?? 0;
    $totalProgress += $progress;
    if ($progress == 100) {
        $completedCount++;
    }
}
$avgProgress = $totalCourses > 0 ? round($totalProgress / $totalCourses) : 0;

// Dữ liệu giả định từ Session (Cần cho Navbar và Sidebar)
$student = $student ?? ['fullname' => $_SESSION['fullname'] ?? 'Học viên', 'email' => $_SESSION['email'] ?? ''];

// Stats cơ bản cho Sidebar Badge
$stats = ['total_courses' => $totalCourses];

// Lấy CSS và cấu trúc HTML từ file dashboard.php bạn cung cấp
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiến độ học tập - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
    /* Toàn bộ CSS từ file dashboard.php (đã được người dùng cung cấp) */
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
        margin-top: 7px;
        /* Thêm margin top để nội dung không bị che bởi navbar sticky */
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

    /* ===== STATS CARDS (Customized for Progress List) ===== */
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

    /* ===== PROGRESS LIST ITEMS ===== */
    .course-progress-item {
        display: flex;
        align-items: center;
        background: white;
        padding: 1.25rem;
        border-radius: var(--radius-md);
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s;
        border-left: 5px solid var(--primary-color);
        /* Thêm viền màu */
        text-decoration: none !important;
    }

    .course-progress-item:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
        border-left-color: #3730a3;
    }

    .course-thumb {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }

    .progress-bar-container {
        width: 250px;
        min-width: 180px;
    }

    .progress-bar-custom {
        height: 8px;
        background-color: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill-custom {
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color) 0%, #3730a3 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-100-custom {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%) !important;
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


    <aside class="sidebar d-none d-lg-block">
        <div class="sidebar-header">
            <div class="student-info">
                <div class="student-avatar">
                    <?php echo strtoupper(substr($student['fullname'], 0, 1)); ?>
                </div>
                <div>
                    <div class="student-name"><?php echo htmlspecialchars($student['fullname']); ?></div>
                    <div class="student-email"><?php echo htmlspecialchars($student['email']); ?></div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="<?php echo BASE_URL; ?>/student/dashboard" class="sidebar-item">
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
            <a href="<?php echo BASE_URL; ?>/courses" class="sidebar-item">
                <i class="fas fa-search"></i>
                <span>Tìm khóa học</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/course_progress" class="sidebar-item active">
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

    <main class="main-content">
        <div class="page-header">
            <h2 class="page-title">
                <i class="fas fa-chart-line"></i>
                <span>Tiến độ học tập</span>
            </h2>
            <p class="page-subtitle">Theo dõi quá trình và kết quả hoàn thành các khóa học của bạn.</p>
        </div>

        <?php if ($totalCourses > 0): ?>
        <div class="row g-4 mb-5">
            <div class="col-lg-4 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-icon icon-primary">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stats-number"><?php echo $totalCourses; ?></div>
                    <div class="stats-label">Khóa học đã đăng ký</div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-icon icon-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number"><?php echo $completedCount; ?></div>
                    <div class="stats-label">Khóa học đã hoàn thành</div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-icon icon-warning">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stats-number"><?php echo $avgProgress; ?>%</div>
                    <div class="stats-label">Tiến độ trung bình</div>
                </div>
            </div>
        </div>

        <div class="section-header">
            <h3 class="section-title">Chi tiết Tiến độ Khóa học</h3>
        </div>
        <div class="progress-list">
            <?php foreach ($courses as $course):
                    $progress = $course['progress'] ?? 0;
                    $isCompleted = $progress == 100;

                    $imgSrc = !empty($course['image'])
                        ? BASE_URL . '/assets/images/' . $course['image']
                        : BASE_URL . '/assets/images/default.jpg'; // Dùng ảnh mặc định nếu không có

                    $courseId = $course['course_id'] ?? $course['id'];
                ?>
            <a href="" class="course-progress-item text-dark"
                style="border-left-color: <?php echo $isCompleted ? 'var(--success-color)' : 'var(--primary-color)'; ?>;">



                <div class="flex-grow-1 me-4">
                    <h5 class="mb-1 fw-bold course-title-list">
                        <?php echo htmlspecialchars($course['title'] ?? 'Khóa học chưa đặt tên'); ?>
                    </h5>
                    <div class="course-meta">
                        <div class="course-instructor">
                            <i class="fas fa-user-tie"></i>
                            <span>GV: <?php echo htmlspecialchars($course['instructor_name'] ?? 'Giảng viên'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="progress-bar-container me-3 d-none d-sm-block">
                    <div class="progress-label">
                        <span class="fw-bold">Tiến độ</span>
                        <span class="fw-bold text-primary"><?php echo $progress; ?>%</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill-custom <?php echo $isCompleted ? 'progress-100-custom' : ''; ?>"
                            style="width: <?php echo $progress; ?>%">
                        </div>
                    </div>
                </div>


            </a>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h4>Chưa có dữ liệu tiến độ</h4>
            <p>Bạn chưa đăng ký hoặc chưa bắt đầu khóa học nào.</p>
            <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary mt-3">
                <i class="fas fa-search me-2"></i> Khám phá khóa học
            </a>
        </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Toggle sidebar on mobile
    document.querySelector('.sidebar-toggle')?.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Auto update progress bars (Hiệu ứng animation khi load trang)
    document.addEventListener('DOMContentLoaded', function() {
        const progressFills = document.querySelectorAll('.progress-fill-custom');
        progressFills.forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = targetWidth;
            }, 100);
        });
    });
    </script>
</body>

</html>