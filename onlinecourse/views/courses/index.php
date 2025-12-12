<?php
// File: views/courses/index.php (Danh sách Khóa học công khai / Khám phá)
// Dữ liệu từ Controller: $courses, $categories, $categoryId, $keyword, $enrolledCourseIds
// =========================================================================

$BASE_URL = $BASE_URL ?? '/base';
$courses = $courses ?? [];
$categories = $categories ?? [];
$categoryId = $categoryId ?? null;
$keyword = $keyword ?? '';
$enrolledCourseIds = $enrolledCourseIds ?? []; // Danh sách ID khóa học đã đăng ký

// Xác định nếu người dùng là Học viên đã đăng nhập
$isStudentLoggedIn = ($_SESSION['user_role'] ?? -1) == 0;
$userName = htmlspecialchars($_SESSION['fullname'] ?? 'Khách');

// Xác định tiêu đề
$pageTitle = 'Danh mục Khóa học';
if ($isStudentLoggedIn) {
    $pageTitle = 'Khám phá Khóa học mới';
}
if (!empty($keyword)) {
    $pageTitle = 'Kết quả tìm kiếm: "' . htmlspecialchars($keyword) . '"';
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* [CSS Styles từ file gốc của bạn] */
        :root {
            --primary-color: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --dark-color: #1e293b;
        }

        body {
            background-color: #f9fafb;
            color: #334155;
            font-family: 'Inter', sans-serif;
        }

        .navbar-public {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #4f46e5;
            text-decoration: none;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 20px 20px;
        }

        .page-title {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .search-filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .course-card-public {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #e2e8f0;
        }

        .course-card-public:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        }

        .card-img-wrapper {
            height: 200px;
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            position: relative;
            overflow: hidden;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .course-card-public:hover .card-img-wrapper img {
            transform: scale(1.05);
        }

        .level-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .level-Beginner {
            background-color: var(--primary-color);
        }

        .level-Intermediate {
            background-color: var(--warning-color);
        }

        .level-Advanced {
            background-color: var(--success-color);
        }

        .enrolled-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #10b981;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .course-body-public {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .course-title-public {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            line-height: 1.4;
            min-height: 2.8em;
        }

        .instructor-public {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instructor-public i {
            color: #4f46e5;
        }

        .course-meta-public {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
        }

        .course-footer {
            margin-top: auto;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 0.75rem;
            display: block;
        }

        .price-tag.free {
            color: #4f46e5;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-enroll {
            flex: 1;
            padding: 0.6rem 1rem;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            cursor: pointer;
            text-align: center;
        }

        .btn-enroll:hover:not(:disabled) {
            background-color: #4338ca;
            transform: translateY(-2px);
        }

        .btn-enrolled {
            background-color: #10b981;
        }

        .btn-enrolled:hover {
            background-color: #059669;
        }

        /* Chuyển .btn-detail thành button để dễ dàng sử dụng onclick */
        .btn-detail {
            flex: 1;
            padding: 0.6rem 1rem;
            background-color: transparent;
            color: #4f46e5;
            border: 2px solid #4f46e5;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            cursor: pointer;
            /* Thêm cursor pointer cho button */
        }

        .btn-detail:hover {
            background-color: #eef2ff;
            color: #4338ca;
        }

        /* Cần đảm bảo form đăng ký trong action-buttons cũng có flex: 1 */
        .action-buttons form {
            flex: 1;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        /* [Kết thúc CSS Styles] */
    </style>
</head>

<body>
    <div class="toast-container">
    </div>

    <nav class="navbar-public">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="<?php echo BASE_URL; ?>" class="navbar-brand">
                    <i class="fas fa-graduation-cap me-2"></i>Online Course
                </a>
                <div class="d-flex gap-3">
                    <?php if ($isStudentLoggedIn): ?>
                        <a href="<?php echo BASE_URL; ?>/student/dashboard" class="btn btn-outline-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            <span class="d-none d-md-inline">Dashboard</span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/student/my-courses" class="btn btn-primary">
                            <i class="fas fa-book-open me-2"></i>
                            <span class="d-none d-md-inline">Khóa học của tôi</span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <span class="d-none d-md-inline">Đăng nhập</span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>
                            <span class="d-none d-md-inline">Đăng ký</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="page-header">
        <div class="container text-center">
            <h1 class="page-title">
                <?php echo $isStudentLoggedIn ? "Chào mừng, {$userName}!" : 'Khám phá Khóa học'; ?>
            </h1>
            <p class="page-subtitle mb-0">
                <?php
                if (!empty($keyword)) {
                    echo 'Tìm thấy ' . count($courses) . ' khóa học phù hợp với từ khóa của bạn.';
                } elseif ($isStudentLoggedIn) {
                    echo 'Chào mừng, ' . $userName . '! Dưới đây là các khóa học mới bạn có thể tham gia.';
                } else {
                    echo 'Học tập mọi lúc, mọi nơi với các khóa học chất lượng cao';
                }
                ?>
            </p>
        </div>
    </div>

    <div class="container pb-5">
        <div class="search-filter-section">
            <form method="GET" action="<?php echo BASE_URL; ?>/courses/search" class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" name="keyword" placeholder="Tìm kiếm khóa học..."
                            value="<?php echo htmlspecialchars($keyword); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category_id">
                        <option value="">Tất cả danh mục</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $categoryId == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        <?php if (!empty($courses)): ?>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Tìm thấy <?php echo count($courses); ?> khóa học
                </small>
                <?php if ($isStudentLoggedIn && !empty($enrolledCourseIds)): ?>
                    <small class="text-muted">
                        <i class="fas fa-check-circle text-success me-1"></i>
                        Đã đăng ký: <?php echo count($enrolledCourseIds); ?> khóa học
                    </small>
                <?php endif; ?>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                <?php foreach ($courses as $course):
                    $courseId = $course['id'] ?? $course['course_id'];
                    $imageSrc = !empty($course['image'])
                        ? BASE_URL . '/assets/uploads/courses/' . $course['image']
                        : 'https://via.placeholder.com/400x200/4f46e5/ffffff?text=' . urlencode($course['title'] ?? 'Course');
                    $levelClass = str_replace(' ', '', $course['level'] ?? 'Beginner');
                    $price = $course['price'] ?? 0;
                    $isFree = $price == 0;

                    // Kiểm tra khóa học đã đăng ký chưa
                    $isEnrolled = in_array($courseId, $enrolledCourseIds);
                    ?>
                    <div class="col">
                        <div class="course-card-public" data-course-id="<?php echo $courseId; ?>">
                            <div class="card-img-wrapper">
                                <img src="<?php echo htmlspecialchars($imageSrc); ?>"
                                    alt="<?php echo htmlspecialchars($course['title'] ?? 'Khóa học'); ?>" loading="lazy">
                                <span class="level-badge level-<?php echo htmlspecialchars($levelClass); ?>">
                                    <?php echo htmlspecialchars($course['level'] ?? 'Cơ bản'); ?>
                                </span>
                                <?php if ($isEnrolled): ?>
                                    <span class="enrolled-badge">
                                        <i class="fas fa-check me-1"></i>Đã đăng ký
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="course-body-public">
                                <h5 class="course-title-public">
                                    <?php echo htmlspecialchars($course['title'] ?? 'Tiêu đề khóa học'); ?>
                                </h5>

                                <div class="instructor-public">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span><?php echo htmlspecialchars($course['instructor_name'] ?? 'Giảng viên'); ?></span>
                                </div>

                                <div class="course-meta-public">
                                    <span class="stats-badge">
                                        <i class="fas fa-clock"></i>
                                        <?php echo htmlspecialchars($course['duration_weeks'] ?? 'N/A'); ?> tuần
                                    </span>
                                    <span class="stats-badge">
                                        <i class="fas fa-list"></i>
                                        <?php echo htmlspecialchars($course['lesson_count'] ?? '0'); ?> bài
                                    </span>
                                </div>

                                <div class="course-footer">
                                    <span class="price-tag <?php echo $isFree ? 'free' : ''; ?>">
                                        <?php echo $isFree ? 'Miễn phí' : number_format($price, 0, ',', '.') . 'đ'; ?>
                                    </span>

                                    <div class="action-buttons">
                                        <?php if ($isStudentLoggedIn): ?>
                                            <?php if ($isEnrolled): ?>
                                                <a href="<?php echo BASE_URL; ?>/student/course/<?php echo $courseId; ?>"
                                                    class="btn-enroll btn-enrolled">
                                                    <i class="fas fa-play-circle me-1"></i>Vào học
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>/courses/detail/<?php echo $courseId; ?>"
                                                    class="btn-detail">
                                                    Chi tiết
                                                </a>
                                            <?php else: ?>
                                                <form method="POST" action="<?php echo BASE_URL; ?>/enrollment/register"
                                                    style="display: inline; flex: 1;">
                                                    <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                                                    <button type="submit" class="btn-enroll w-100">
                                                        <i class="fas fa-plus-circle me-1"></i>Đăng ký
                                                    </button>
                                                </form>
                                                <button type="button" class="btn-detail"
                                                    onclick="alert('Vui lòng đăng ký khóa học để xem chi tiết nội dung đầy đủ.');">
                                                    Chi tiết
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>/auth/login?redirect=/enrollment/register"
                                                class="btn-enroll">
                                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập để đăng ký
                                            </a>
                                            <button type="button" class="btn-detail"
                                                onclick="alert('Vui lòng đăng nhập để xem chi tiết và đăng ký khóa học.');">
                                                Chi tiết
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Không tìm thấy khóa học</h3>
                <p>
                    <?php
                    // Hiển thị thông báo khi không tìm thấy khóa học
                    if (!empty($keyword)) {
                        echo 'Không tìm thấy khóa học nào khớp với từ khóa/bộ lọc của bạn.';
                    } elseif ($isStudentLoggedIn) {
                        echo 'Bạn đã đăng ký tất cả các khóa học hiện có hoặc không có khóa học mới.';
                    } else {
                        echo 'Hiện tại không có khóa học công khai nào được tìm thấy. Vui lòng quay lại sau.';
                    }
                    ?>
                </p>
                <?php if ($isStudentLoggedIn): ?>
                    <a href="<?php echo BASE_URL; ?>/student/my-courses" class="btn btn-primary btn-lg">
                        <i class="fas fa-book-open me-2"></i>Xem khóa học của tôi
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/courses" class="btn btn-primary btn-lg">
                        <i class="fas fa-sync-alt me-2"></i>Tải lại trang
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>