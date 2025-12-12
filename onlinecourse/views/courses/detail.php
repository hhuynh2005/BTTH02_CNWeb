<?php
// File: views/courses/detail.php
// Dữ liệu cần thiết từ Controller: $courseDetail, $progressDetail, $lessons

// Đảm bảo các biến được truyền từ Controller
$courseDetail = $courseDetail ?? [];
$lessons = $lessons ?? [];
$progressDetail = $progressDetail ?? 0;
$BASE_URL = $BASE_URL ?? '/base';
$_SESSION['fullname'] = $_SESSION['fullname'] ?? 'Học Viên';
$_SESSION['email'] = $_SESSION['email'] ?? '';

// Lấy ID khóa học
$courseId = $courseDetail['id'] ?? $courseDetail['course_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($courseDetail['title'] ?? 'Chi tiết Khóa học'); ?> - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #eef2ff;
            --success-color: #10b981;
            --dark-color: #1e293b;
            --gray-light: #f8fafc;
            --gray-border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
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

        .navbar-student {
            background: white;
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
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

        .sidebar-item:hover,
        .sidebar-item.active {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

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

        .course-header-box {
            background: white;
            border-radius: var(--radius-md);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-sm);
        }

        .course-title-detail {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
        }

        .course-meta-detail {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            color: #64748b;
        }

        .course-meta-detail span i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .course-description {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin-bottom: 1.5rem;
            color: #475569;
        }

        .progress-bar-detail {
            height: 10px;
            background-color: var(--gray-border);
            border-radius: 5px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .progress-fill-detail {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), #3730a3);
            border-radius: 5px;
            transition: width 0.5s ease;
        }

        .progress-100-detail {
            background: linear-gradient(90deg, var(--success-color), #059669);
        }

        .lesson-list-container {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .lesson-list-title {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--gray-border);
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .lesson-item {
            display: flex;
            align-items: center;
            padding: 1.25rem 2rem;
            border-bottom: 1px solid var(--gray-border);
            text-decoration: none;
            color: inherit;
            transition: background-color 0.2s;
        }

        .lesson-item:hover {
            background-color: var(--gray-light);
        }

        .lesson-item:last-child {
            border-bottom: none;
        }

        .lesson-order {
            font-size: 1.25rem;
            font-weight: 600;
            color: #94a3b8;
            min-width: 40px;
            text-align: center;
        }

        .lesson-item-completed .lesson-order {
            color: var(--success-color);
        }

        .lesson-info {
            flex-grow: 1;
            margin: 0 1.5rem;
        }

        .lesson-title-text {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0;
            line-height: 1.4;
        }

        .lesson-item-completed .lesson-title-text {
            color: #64748b;
            font-weight: 400;
        }

        .lesson-meta {
            font-size: 0.875rem;
            color: #94a3b8;
            margin-top: 0.25rem;
        }

        .lesson-actions {
            font-size: 1.25rem;
        }

        .lesson-actions i.fa-check-circle {
            color: var(--success-color);
        }

        .lesson-actions i.fa-play-circle {
            color: var(--primary-color);
        }

        .back-link {
            color: #64748b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-student">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 fw-bold text-primary">Online Course</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></div>
                    <div class="text-muted small">Học viên</div>
                </div>
                <div class="student-avatar">
                    <?php echo strtoupper(substr($_SESSION['fullname'] ?? 'H', 0, 1)); ?>
                </div>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    <span class="d-none d-md-inline">Đăng xuất</span>
                </a>
            </div>
        </div>
    </nav>

    <aside class="sidebar">
        <div class="sidebar-menu">
            <a href="<?php echo BASE_URL; ?>/student/dashboard" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/my-courses" class="sidebar-item active">
                <i class="fas fa-book-open"></i>
                <span>Khóa học của tôi</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/courses" class="sidebar-item">
                <i class="fas fa-search"></i>
                <span>Tìm khóa học</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <a href="<?php echo BASE_URL; ?>/student/my-courses" class="back-link">
            <i class="fas fa-arrow-left"></i>
            <span>Trở về danh sách khóa học</span>
        </a>

        <div class="course-header-box">
            <h1 class="course-title-detail">
                <?php echo htmlspecialchars($courseDetail['title'] ?? 'Khóa học không tìm thấy'); ?>
            </h1>

            <div class="course-meta-detail">
                <span>
                    <i class="fas fa-chalkboard-teacher"></i>
                    Giảng viên: <?php echo htmlspecialchars($courseDetail['instructor_name'] ?? 'Chưa cập nhật'); ?>
                </span>
                <span>
                    <i class="fas fa-list-ol"></i>
                    Tổng số bài học: <?php echo count($lessons); ?>
                </span>
            </div>

            <div class="course-description">
                <?php echo nl2br(htmlspecialchars($courseDetail['description'] ?? 'Không có mô tả.')); ?>
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-2 small fw-bold">
                    <span>Tiến độ của bạn</span>
                    <span class="<?php echo $progressDetail == 100 ? 'text-success' : 'text-primary'; ?>">
                        <?php echo $progressDetail; ?>% hoàn thành
                    </span>
                </div>
                <div class="progress-bar-detail">
                    <div class="progress-fill-detail <?php echo $progressDetail == 100 ? 'progress-100-detail' : ''; ?>"
                        style="width: <?php echo $progressDetail; ?>%">
                    </div>
                </div>
            </div>
        </div>

        <div class="lesson-list-container">
            <div class="lesson-list-title">
                Nội dung Khóa học
            </div>

            <div class="list-group list-group-flush">
                <?php if (!empty($lessons) && $courseId): ?>
                    <?php foreach ($lessons as $lesson):
                        $lesson_order = $lesson['lesson_order'] ?? $lesson['id'];
                        $is_completed = $lesson['is_completed'] ?? false;
                        $lesson_url = BASE_URL . '/student/course/' . $courseId . '?lesson_id=' . $lesson['id'];
                        $item_class = $is_completed ? 'lesson-item lesson-item-completed' : 'lesson-item';
                        ?>
                        <a href="<?php echo $lesson_url; ?>" class="<?php echo $item_class; ?>">
                            <div class="lesson-order">
                                #<?php echo htmlspecialchars($lesson_order); ?>
                            </div>

                            <div class="lesson-info">
                                <h5 class="lesson-title-text">
                                    <?php echo htmlspecialchars($lesson['title'] ?? 'Tiêu đề bài học'); ?>
                                </h5>
                                <div class="lesson-meta">
                                    <i class="fas fa-clock"></i>
                                    Ngày tạo: <?php echo date('d/m/Y', strtotime($lesson['created_at'] ?? 'now')); ?>
                                </div>
                            </div>

                            <div class="lesson-actions">
                                <?php if ($is_completed): ?>
                                    <i class="fas fa-check-circle" title="Đã hoàn thành"></i>
                                <?php else: ?>
                                    <i class="fas fa-play-circle" title="Bắt đầu học"></i>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-exclamation-circle"></i>
                        <p class="mb-0">Khóa học này chưa có bài học nào được đăng tải.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Animate progress bar
            const progressBar = document.querySelector('.progress-fill-detail');
            if (progressBar) {
                const targetWidth = progressBar.style.width;
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = targetWidth;
                }, 100);
            }

            // Mobile sidebar toggle (if needed)
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>

</html>