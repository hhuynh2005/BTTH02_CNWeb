<?php
/**
 * File: views/courses/detail.php
 * Mô tả: Trang học tập của Học viên (Student Learning Interface)
 * URL: /student/course/{course_id}?lesson_id={lesson_id}
 */

// ====================================================
// PHẦN 1: KHỞI TẠO VÀ VALIDATE DỮ LIỆU
// ====================================================

// Nhận dữ liệu từ Controller
$courseDetail = $courseDetail ?? [];
$lessons = $lessons ?? [];
$progressDetail = $progressDetail ?? 0;
$currentLesson = $currentLesson ?? null;
$BASE_URL = defined('BASE_URL') ? BASE_URL : '';

// Session data
$_SESSION['fullname'] = $_SESSION['fullname'] ?? 'Học Viên';
$_SESSION['email'] = $_SESSION['email'] ?? '';

// ====================================================
// LẤY COURSE_ID - LOGIC ƯU TIÊN
// ====================================================
$courseId = null;

// Ưu tiên 1: Từ courseDetail['id']
if (isset($courseDetail['id']) && !empty($courseDetail['id'])) {
    $courseId = (int) $courseDetail['id'];
}
// Ưu tiên 2: Từ courseDetail['course_id'] 
elseif (isset($courseDetail['course_id']) && !empty($courseDetail['course_id'])) {
    $courseId = (int) $courseDetail['course_id'];
}
// Ưu tiên 3: Từ URL parameter (fallback)
elseif (isset($_GET['course_id']) && !empty($_GET['course_id'])) {
    $courseId = (int) $_GET['course_id'];
}
// Ưu tiên 4: Từ lesson đầu tiên (last resort)
elseif (!empty($lessons) && isset($lessons[0]['course_id'])) {
    $courseId = (int) $lessons[0]['course_id'];
}

// Kiểm tra courseId có hợp lệ không
if (!$courseId || $courseId <= 0) {
    die("<div style='padding:50px;text-align:center;'>
        <h2>❌ Lỗi: Không xác định được ID khóa học</h2>
        <p>CourseDetail Data: <pre>" . print_r($courseDetail, true) . "</pre></p>
        <a href='{$BASE_URL}/student/my-courses' class='btn btn-primary'>← Quay lại danh sách khóa học</a>
    </div>");
}

// Lấy ID bài học hiện tại
$currentLessonId = $currentLesson['id'] ?? null;

// Course info
$courseTitle = $courseDetail['title'] ?? 'Khóa học';
$instructorName = $courseDetail['instructor_name'] ?? 'Chưa cập nhật';
$totalLessons = count($lessons);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($courseTitle); ?> - Online Course Platform</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--gray-50);
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* ====================================================
           NAVBAR
        ==================================================== */
        .navbar-student {
            background: white;
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--gray-200);
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
            box-shadow: var(--shadow-sm);
        }

        /* ====================================================
           SIDEBAR
        ==================================================== */
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
            border-right: 1px solid var(--gray-200);
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1.5rem;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.2s ease;
            margin: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .sidebar-item:hover {
            background-color: var(--gray-100);
            color: var(--primary-color);
        }

        .sidebar-item.active {
            background-color: var(--primary-light);
            color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar-item i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* ====================================================
           MAIN CONTENT
        ==================================================== */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: calc(100vh - 70px);
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* ====================================================
           COURSE HEADER
        ==================================================== */
        .course-header-box {
            background: white;
            border-radius: var(--radius-md);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }

        .back-link {
            color: var(--gray-500);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .back-link:hover {
            color: var(--primary-color);
            gap: 0.75rem;
        }

        .course-title-detail {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .course-meta-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        .course-meta-detail span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .course-meta-detail i {
            color: var(--primary-color);
        }

        /* ====================================================
           PROGRESS BAR
        ==================================================== */
        .progress-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .progress-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.9rem;
        }

        .progress-percentage {
            font-weight: 700;
            font-size: 1rem;
        }

        .progress-percentage.text-success {
            color: var(--success-color);
        }

        .progress-percentage.text-primary {
            color: var(--primary-color);
        }

        .progress-bar-detail {
            height: 12px;
            background-color: var(--gray-200);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .progress-fill-detail {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            border-radius: 6px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-fill-detail.progress-100-detail {
            background: linear-gradient(90deg, var(--success-color), #059669);
        }

        .progress-fill-detail::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* ====================================================
           VIDEO & CONTENT CARD
        ==================================================== */
        .video-card {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
        }

        .video-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .video-container {
            position: relative;
            background: #000;
            height: 450px;
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--gray-400);
            background: var(--gray-100);
        }

        .video-placeholder i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .lesson-content-section {
            padding: 2rem;
        }

        .lesson-content-section h6 {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .lesson-content {
            color: var(--gray-600);
            line-height: 1.8;
            font-size: 0.95rem;
        }

        .card-footer-actions {
            padding: 1.5rem;
            background: var(--gray-50);
            border-top: 1px solid var(--gray-200);
            text-align: right;
        }

        .btn-complete-lesson {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: var(--shadow-sm);
        }

        .btn-complete-lesson:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ====================================================
           EMPTY STATE
        ==================================================== */
        .empty-state-card {
            background: white;
            border-radius: var(--radius-md);
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 2px dashed var(--gray-300);
        }

        .empty-state-card i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }

        .empty-state-card h4 {
            color: var(--dark-color);
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        .empty-state-card p {
            color: var(--gray-500);
            margin: 0;
        }

        /* ====================================================
           LESSON LIST
        ==================================================== */
        .lesson-list-container {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            border: 1px solid var(--gray-200);
        }

        .lesson-list-header {
            padding: 1.5rem;
            border-bottom: 2px solid var(--gray-200);
            background: var(--gray-50);
        }

        .lesson-list-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .lesson-count-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .lesson-item {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            text-decoration: none;
            color: inherit;
            transition: all 0.2s ease;
            position: relative;
        }

        .lesson-item:last-child {
            border-bottom: none;
        }

        .lesson-item:hover {
            background-color: var(--gray-50);
        }

        .lesson-item.active {
            background-color: var(--primary-light);
            border-left: 4px solid var(--primary-color);
        }

        .lesson-item.lesson-item-completed {
            background-color: var(--success-light);
        }

        .lesson-order {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-400);
            min-width: 45px;
            text-align: center;
        }

        .lesson-item.active .lesson-order {
            color: var(--primary-color);
        }

        .lesson-item-completed .lesson-order {
            color: var(--success-color);
        }

        .lesson-info {
            flex-grow: 1;
            margin: 0 1.5rem;
        }

        .lesson-title-text {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.25rem;
            line-height: 1.4;
            font-size: 1rem;
        }

        .lesson-item.active .lesson-title-text {
            color: var(--primary-color);
            font-weight: 700;
        }

        .lesson-item-completed .lesson-title-text {
            color: var(--gray-600);
        }

        .lesson-meta {
            font-size: 0.85rem;
            color: var(--gray-400);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .lesson-meta i {
            font-size: 0.8rem;
        }

        .lesson-actions {
            font-size: 1.5rem;
        }

        .lesson-actions i.fa-check-circle {
            color: var(--success-color);
        }

        .lesson-actions i.fa-play-circle {
            color: var(--primary-color);
        }

        .lesson-actions i.fa-circle {
            color: var(--gray-300);
        }

        /* Empty lesson state */
        .empty-lessons {
            padding: 3rem 2rem;
            text-align: center;
            color: var(--gray-400);
        }

        .empty-lessons i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-lessons p {
            margin: 0;
            color: var(--gray-500);
        }
    </style>
</head>

<body>
    <!-- ====================================================
         NAVBAR
    ==================================================== -->
    <nav class="navbar navbar-student">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-lg-none me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-graduation-cap me-2"></i>Online Course
                </h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
                    <div class="text-muted small">Học viên</div>
                </div>
                <div class="student-avatar">
                    <?php echo strtoupper(substr($_SESSION['fullname'], 0, 1)); ?>
                </div>
                <a href="<?php echo $BASE_URL; ?>/auth/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    <span class="d-none d-md-inline">Đăng xuất</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- ====================================================
         SIDEBAR
    ==================================================== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-menu">
            <a href="<?php echo $BASE_URL; ?>/student/dashboard" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo $BASE_URL; ?>/student/my-courses" class="sidebar-item active">
                <i class="fas fa-book-open"></i>
                <span>Khóa học của tôi</span>
            </a>
            <a href="<?php echo $BASE_URL; ?>/courses" class="sidebar-item">
                <i class="fas fa-search"></i>
                <span>Tìm khóa học</span>
            </a>
            <a href="<?php echo $BASE_URL; ?>/student/course_progress" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                <span>Tiến độ học tập</span>
            </a>
        </div>
    </aside>

    <!-- ====================================================
         MAIN CONTENT
    ==================================================== -->
    <main class="main-content">
        <!-- COURSE HEADER -->
        <div class="course-header-box">
            <a href="<?php echo $BASE_URL; ?>/student/my-courses" class="back-link">
                <i class="fas fa-arrow-left"></i>
                <span>Trở về danh sách khóa học</span>
            </a>

            <h1 class="course-title-detail">
                <?php echo htmlspecialchars($courseTitle); ?>
            </h1>

            <div class="course-meta-detail">
                <span>
                    <i class="fas fa-chalkboard-teacher"></i>
                    Giảng viên: <?php echo htmlspecialchars($instructorName); ?>
                </span>
                <span>
                    <i class="fas fa-list-ol"></i>
                    Tổng số bài học: <?php echo $totalLessons; ?>
                </span>
                <span>
                    <i class="fas fa-clock"></i>
                    Thời lượng:
                    <?php
                    $totalDuration = $totalLessons * 30; // Giả định mỗi bài 30 phút
                    echo ceil($totalDuration / 60) . ' giờ';
                    ?>
                </span>
            </div>

            <!-- PROGRESS BAR -->
            <div class="progress-section">
                <div class="progress-header">
                    <span class="progress-label">Tiến độ học tập của bạn</span>
                    <span
                        class="progress-percentage <?php echo $progressDetail == 100 ? 'text-success' : 'text-primary'; ?>">
                        <?php echo round($progressDetail); ?>% hoàn thành
                    </span>
                </div>
                <div class="progress-bar-detail">
                    <div class="progress-fill-detail <?php echo $progressDetail == 100 ? 'progress-100-detail' : ''; ?>"
                        data-progress="<?php echo $progressDetail; ?>" style="width: 0%">
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN LEARNING AREA -->
        <div class="row">
            <!-- VIDEO/CONTENT COLUMN -->
            <div class="col-lg-8">
                <?php if ($currentLesson): ?>
                    <!-- VIDEO CARD -->
                    <div class="video-card">
                        <div class="video-card-header">
                            <i class="fas fa-play-circle"></i>
                            <span>Đang học: <?php echo htmlspecialchars($currentLesson['title'] ?? 'Bài học'); ?></span>
                        </div>

                        <!-- VIDEO CONTAINER -->
                        <div class="video-container">
                            <?php if (!empty($currentLesson['video_url'])): ?>
                                <?php
                                // Convert YouTube URL to embed format
                                $embedUrl = $currentLesson['video_url'];
                                $videoId = '';

                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $embedUrl, $matches)) {
                                    $videoId = $matches[1];
                                    $embedUrl = "https://www.youtube.com/embed/{$videoId}";
                                }
                                ?>
                                <iframe src="<?php echo htmlspecialchars($embedUrl); ?>"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>
                            <?php else: ?>
                                <div class="video-placeholder">
                                    <i class="fas fa-video-slash"></i>
                                    <p>Không có video cho bài học này</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- LESSON CONTENT -->
                        <div class="lesson-content-section">
                            <h6><i class="fas fa-file-alt me-2"></i>Nội dung bài học</h6>
                            <div class="lesson-content">
                                <?php
                                $content = $currentLesson['content'] ?? 'Nội dung đang được cập nhật...';
                                echo nl2br(htmlspecialchars($content));
                                ?>
                            </div>
                        </div>

                        <!-- FOOTER ACTIONS -->
                        <div class="card-footer-actions">
                            <form method="POST" action="<?php echo $BASE_URL; ?>/enrollment/completeLesson"
                                style="display:inline;">
                                <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                                <input type="hidden" name="lesson_id" value="<?php echo $currentLesson['id']; ?>">
                                <button type="submit" class="btn btn-complete-lesson">
                                    <i class="fas fa-check me-2"></i>Đánh dấu đã hoàn thành
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- EMPTY STATE -->
                    <div class="empty-state-card">
                        <i class="fas fa-hand-pointer"></i>
                        <h4>Chọn Bài Học Để Bắt Đầu</h4>
                        <p>Vui lòng chọn một bài học từ danh sách bên phải để bắt đầu học tập</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- LESSON LIST COLUMN -->
            <div class="col-lg-4">
                <div class="lesson-list-container">
                    <div class="lesson-list-header">
                        <h3 class="lesson-list-title">
                            <i class="fas fa-list"></i>
                            Danh sách bài học
                            <span class="lesson-count-badge"><?php echo $totalLessons; ?></span>
                        </h3>
                    </div>

                    <div class="list-group list-group-flush">
                        <?php if (!empty($lessons)): ?>
                            <?php foreach ($lessons as $index => $lesson):
                                $lessonId = $lesson['id'];
                                $lessonOrder = $lesson['lesson_order'] ?? ($index + 1);
                                $lessonTitle = $lesson['title'] ?? 'Bài học ' . $lessonOrder;
                                $isCompleted = $lesson['is_completed'] ?? false;
                                $isActive = ($currentLessonId == $lessonId);
                                $createdAt = $lesson['created_at'] ?? date('Y-m-d H:i:s');

                                // Build lesson URL - QUAN TRỌNG: Sử dụng $courseId đã validate
                                $lessonUrl = $BASE_URL . '/student/course/' . $courseId . '?lesson_id=' . $lessonId;

                                // CSS classes
                                $itemClass = 'lesson-item';
                                if ($isCompleted)
                                    $itemClass .= ' lesson-item-completed';
                                if ($isActive)
                                    $itemClass .= ' active';
                                ?>
                                <a href="<?php echo $lessonUrl; ?>" class="<?php echo $itemClass; ?>">
                                    <div class="lesson-order">
                                        #<?php echo $lessonOrder; ?>
                                    </div>

                                    <div class="lesson-info">
                                        <h5 class="lesson-title-text">
                                            <?php echo htmlspecialchars($lessonTitle); ?>
                                        </h5>
                                        <div class="lesson-meta">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo date('d/m/Y', strtotime($createdAt)); ?>
                                        </div>
                                    </div>

                                    <div class="lesson-actions">
                                        <?php if ($isCompleted): ?>
                                            <i class="fas fa-check-circle" title="Đã hoàn thành"></i>
                                        <?php elseif ($isActive): ?>
                                            <i class="fas fa-play-circle" title="Đang học"></i>
                                        <?php else: ?>
                                            <i class="far fa-circle" title="Chưa học"></i>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- EMPTY LESSON STATE -->
                            <div class="empty-lessons">
                                <i class="fas fa-inbox"></i>
                                <p>Khóa học này chưa có bài học nào</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- COURSE STATS (Optional) -->
                <?php if ($totalLessons > 0): ?>
                    <div class="card mt-3 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-3">
                                <i class="fas fa-chart-pie text-primary me-2"></i>Thống kê học tập
                            </h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Đã hoàn thành:</span>
                                <span class="fw-bold text-success">
                                    <?php
                                    $completedCount = count(array_filter($lessons, function ($l) {
                                        return $l['is_completed'] ?? false;
                                    }));
                                    echo $completedCount . '/' . $totalLessons;
                                    ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Còn lại:</span>
                                <span class="fw-bold text-warning">
                                    <?php echo ($totalLessons - $completedCount) . ' bài'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- ====================================================
         JAVASCRIPT
    ==================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Animate progress bar on page load
        document.addEventListener('DOMContentLoaded', function () {
            const progressBar = document.querySelector('.progress-fill-detail');

            if (progressBar) {
                const targetWidth = progressBar.getAttribute('data-progress') + '%';

                // Start from 0
                progressBar.style.width = '0%';

                // Animate to target
                setTimeout(() => {
                    progressBar.style.width = targetWidth;
                }, 100);
            }

            // Auto-hide success message
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('msg')) {
                const msg = urlParams.get('msg');
                if (msg === 'completed') {
                    showNotification('✅ Đã hoàn thành bài học!', 'success');
                }
            }

            // Smooth scroll to active lesson
            const activeLesson = document.querySelector('.lesson-item.active');
            if (activeLesson && window.innerWidth >= 992) {
                setTimeout(() => {
                    activeLesson.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }, 300);
            }
        });

        // Show notification (optional)
        function showNotification(message, type = 'info') {
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                info: '#4f46e5',
                warning: '#f59e0b'
            };

            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 90px;
                right: 20px;
                background: ${colors[type]};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                z-index: 9999;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = event.target.closest('.btn-link');

            if (window.innerWidth < 992 &&
                sidebar.classList.contains('active') &&
                !sidebar.contains(event.target) &&
                !toggleBtn) {
                sidebar.classList.remove('active');
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (event) {
            // ESC to close sidebar on mobile
            if (event.key === 'Escape' && window.innerWidth < 992) {
                const sidebar = document.getElementById('sidebar');
                sidebar.classList.remove('active');
            }

            // Arrow keys for navigation (optional)
            if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                const lessons = document.querySelectorAll('.lesson-item');
                const currentIndex = Array.from(lessons).findIndex(l => l.classList.contains('active'));

                if (currentIndex !== -1) {
                    let nextIndex;
                    if (event.key === 'ArrowUp') {
                        nextIndex = Math.max(0, currentIndex - 1);
                    } else {
                        nextIndex = Math.min(lessons.length - 1, currentIndex + 1);
                    }

                    if (nextIndex !== currentIndex) {
                        event.preventDefault();
                        lessons[nextIndex].click();
                    }
                }
            }
        });

        // Video player ready detection (optional)
        window.addEventListener('load', function () {
            const iframe = document.querySelector('.video-container iframe');
            if (iframe) {
                console.log('✅ Video player loaded successfully');
            }
        });

        // Debug info (remove in production)
        console.log('📚 Course Learning Page Loaded');
        console.log('Course ID: <?php echo $courseId; ?>');
        console.log('Current Lesson ID: <?php echo $currentLessonId ?? 'None'; ?>');
        console.log('Total Lessons: <?php echo $totalLessons; ?>');
        console.log('Progress: <?php echo round($progressDetail); ?>%');
    </script>
</body>

</html>