<?php

// File: views/student/my_courses.php

// Dữ liệu từ Controller: $enrolledCourses



// Cấu trúc dữ liệu yêu cầu cho Navbar và Sidebar (Lấy từ dashboard.php)

// Giả định: BASE_URL, $_SESSION['fullname'], $_SESSION['email'] đã được định nghĩa.

$enrolledCourses = $enrolledCourses ?? [];

$courses = $enrolledCourses;

$stats = [

    'total_courses' => count($enrolledCourses),

    'in_progress' => 0, // Giá trị này cần được Controller tính toán chính xác

    'completed' => 0,   // Giá trị này cần được Controller tính toán chính xác

    'avg_progress' => 0 // Giá trị này cần được Controller tính toán chính xác

];

?>

<!DOCTYPE html>

<html lang="vi">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Khóa học của tôi - Online Course</title>

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



        /* ===== HEADER (Added Page Header styles for my_courses) ===== */

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



        /* ===== COURSE CARDS (Styles adjusted to match dashboard.php) ===== */

        .course-card {

            background: white;

            border-radius: var(--radius-md);

            overflow: hidden;

            box-shadow: var(--shadow-sm);

            transition: all 0.3s ease;

            height: 100%;

            border: 1px solid var(--gray-border);

            /* Thêm lại border để dễ nhìn */

        }



        .course-card:hover {

            transform: translateY(-5px);

            box-shadow: var(--shadow-lg);

        }



        .card-img-wrapper {

            height: 160px;

            /* Đồng bộ với course-image từ dashboard */

            position: relative;

            overflow: hidden;

        }



        .card-img-wrapper img {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }



        .course-body {

            padding: 1.5rem;

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



        .instructor-name {

            color: #64748b;

            font-size: 0.875rem;

            margin-bottom: 1rem;

            display: flex;

            align-items: center;

            gap: 8px;

        }



        /* Progress Bar styles from dashboard.php */

        .progress-wrapper {

            margin-bottom: 1rem;

        }



        .progress-info {

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



        /* Sử dụng progress-fill để tạo hiệu ứng màu sắc động */

        .progress-fill {

            height: 100%;

            background: linear-gradient(135deg, var(--primary-color) 0%, #3730a3 100%);

            border-radius: 4px;

            transition: width 0.3s ease;

        }



        .progress-100 {

            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);

        }



        /* Button styles from dashboard.php */

        .btn-learning {

            display: block;

            width: 100%;

            background: var(--primary-color);

            color: white;

            border: none;

            padding: 0.6rem 1rem;

            border-radius: var(--radius-sm);

            text-decoration: none;

            text-align: center;

            font-weight: 500;

            transition: all 0.3s;

        }



        .btn-learning:hover {

            background: #3730a3;

            color: white;

            transform: translateY(-2px);

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



        /* ===== COURSE CARD STYLES (Từ my_courses.php) ===== */

        .course-card-link {

            text-decoration: none;

            color: inherit;

            display: block;

        }



        .course-card {

            border: 1px solid var(--gray-border);

            border-radius: var(--radius-md);

            overflow: hidden;

            transition: transform 0.3s, box-shadow 0.3s;

            background: white;

        }



        .course-card:hover {

            transform: translateY(-5px);

            box-shadow: var(--shadow-md);

        }



        .card-img-wrapper {

            height: 200px;

            overflow: hidden;

            /* Đảm bảo badge position-absolute hoạt động */

            position: relative;

        }



        .course-thumb {

            width: 100%;

            height: 100%;

            object-fit: cover;

        }



        .card-body {

            padding: 1.5rem;

        }



        .course-title {

            font-size: 1.25rem;

            font-weight: 600;

            color: var(--dark-color);

            margin-bottom: 0.5rem;

        }



        .course-meta {

            color: #64748b;

            font-size: 0.875rem;

            margin-bottom: 0.75rem;

        }



        .progress-bar-custom {

            height: 6px;

            background-color: #e2e8f0;

            border-radius: 4px;

            overflow: hidden;

        }



        .progress-fill {

            height: 100%;

            background: linear-gradient(90deg, var(--primary-color), #3730a3);

            border-radius: 4px;

            transition: width 0.4s ease;

        }



        /* ===== COURSE LEVEL BADGE STYLES (MỚI) ===== */

        .course-level {

            padding: 0.3rem 0.75rem;

            border-radius: var(--radius-sm);

            font-size: 0.75rem;

            font-weight: 600;

            color: white;

            z-index: 10;

            box-shadow: var(--shadow-sm);

        }



        /* Hoàn thành (Level Advanced/Success) */

        .level-advanced {

            background-color: var(--success-color);

        }



        /* Đang học (Level Intermediate/Warning) */

        .level-intermediate {

            background-color: var(--warning-color);

        }



        /* Chưa bắt đầu (Level Beginner/Info/Primary) */

        .level-beginner {

            background-color: var(--primary-color);

        }
    </style>

</head>



<body>

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

            <a href="<?php echo BASE_URL; ?>/student/dashboard" class="sidebar-item">

                <i class="fas fa-tachometer-alt"></i>

                <span>Dashboard</span>

            </a>

            <a href="<?php echo BASE_URL; ?>/student/my-courses" class="sidebar-item active">

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



    <main class="main-content">

        <div class="page-header">

            <h1 class="page-title">

                <i class="fas fa-book-open"></i>

                Khóa học của tôi

            </h1>

            <p class="page-subtitle">

                Danh sách các khóa học bạn đã đăng ký và theo dõi tiến độ.

            </p>

        </div>



        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">

            <h3 class="section-title">Danh sách (<?php echo count($courses); ?> khóa học)</h3>

            <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary">

                <i class="fas fa-plus me-2"></i>Đăng ký khóa học mới

            </a>

        </div>



        <?php if (!empty($courses)): ?>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">

                    <?php foreach ($courses as $course):

                        $progress = $course['progress'] ?? 0;

                        ?>

                            <div class="col">

                                <a href="<?php echo BASE_URL; ?>/student/course/<?php echo $course['course_id'] ?? $course['id']; ?>"
                                    class="course-card-link">

                                    <div class="course-card h-100">

                                        <div class="card-img-wrapper">

                                            <?php

                                            $imgSrc = !empty($course['image'])

                                                ? BASE_URL . '/assets/uploads/courses/' . $course['image']

                                                : null; // Thay đổi: Nếu không có ảnh, đặt $imgSrc là null
                                    


                                            $hasImage = !empty($imgSrc);

                                            ?>



                                            <?php if ($hasImage): ?>

                                                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="course-thumb"
                                                        alt="Hình ảnh khóa học">

                                            <?php else: ?>

                                                    <div class="d-flex align-items-center justify-content-center h-100"
                                                        style="background-color: #f1f5f9; color: var(--primary-color);">

                                                        <i class="fas fa-book fa-4x"></i>

                                                    </div>

                                            <?php endif; ?>

                                            <?php

                                            if ($progress == 100): ?>

                                                    <span class="course-level level-advanced position-absolute top-0 end-0 m-3">Hoàn

                                                        thành</span>

                                            <?php elseif ($progress > 0): ?>

                                                    <span class="course-level level-intermediate position-absolute top-0 end-0 m-3">Đang

                                                        học</span>

                                            <?php else: ?>

                                                    <span class="course-level level-beginner position-absolute top-0 end-0 m-3">Chưa bắt

                                                        đầu</span>

                                            <?php endif; ?>

                                        </div>



                                        <div class="course-body">

                                            <h3 class="course-title" title="<?php echo htmlspecialchars($course['title']); ?>">

                                                <?php echo htmlspecialchars($course['title']); ?>

                                            </h3>



                                            <div class="instructor-name">

                                                <i class="fas fa-chalkboard-teacher"></i>

                                                GV: <?php echo htmlspecialchars($course['instructor_name'] ?? 'Chưa cập nhật'); ?>

                                            </div>



                                            <div class="progress-wrapper">

                                                <div class="progress-info">

                                                    <span>Tiến độ</span>

                                                    <span class="fw-bold"><?php echo $progress; ?>%</span>

                                                </div>

                                                <div class="progress-bar">

                                                    <div class="progress-fill <?php echo $progress == 100 ? 'progress-100' : ''; ?>"
                                                        style="width: <?php echo $progress; ?>%">

                                                    </div>

                                                </div>

                                            </div>


                                            <a href="<?php echo BASE_URL; ?>/student/course/<?php echo $course['course_id'] ?? $course['id']; ?>"
                                                class="btn-learning">

                                                <i class="fas fa-play-circle me-1"></i>

                                                <?php echo $progress > 0 ? 'Tiếp tục học' : 'Bắt đầu học'; ?>

                                            </a>

                                        </div>

                                    </div>

                                </a>

                            </div>

                    <?php endforeach; ?>

                </div>

        <?php else: ?>

                <div class="empty-state">

                    <i class="fas fa-folder-open"></i>

                    <h4>Bạn chưa đăng ký khóa học nào</h4>

                    <p class="text-muted">Hãy khám phá thư viện khóa học phong phú của chúng tôi.</p>

                    <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary mt-3">

                        <i class="fas fa-search me-2"></i>Tìm khóa học ngay

                    </a>

                </div>

        <?php endif; ?>

    </main>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle sidebar on mobile

        document.querySelector('.sidebar-toggle').addEventListener('click', function () {

            document.querySelector('.sidebar').classList.toggle('active');

        });

        // Auto update progress bars (Lấy từ dashboard.php)

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
    </script>

</body>

</html>