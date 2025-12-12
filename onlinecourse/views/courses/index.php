<?php
// File: views/courses/index.php (Danh sách Khóa học công khai)
// Dữ liệu cần thiết: $courses, $categories, $enrolledCourseIds

$BASE_URL = $BASE_URL ?? '/base';
$courses = $courses ?? [];
$categories = $categories ?? [];
$categoryId = $categoryId ?? null;
$keyword = $keyword ?? '';
$enrolledCourseIds = $enrolledCourseIds ?? []; // Danh sách ID khóa học đã đăng ký

// Xác định nếu người dùng là Học viên đã đăng nhập
$isStudentLoggedIn = ($_SESSION['user_role'] ?? -1) == 0;
$userName = $_SESSION['fullname'] ?? '';
$userId = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục Khóa học - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f9fafb;
            color: #334155;
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
            background-color: #4f46e5;
        }

        .level-Intermediate {
            background-color: #f59e0b;
        }

        .level-Advanced {
            background-color: #ef4444;
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
        }

        .btn-enroll:hover:not(:disabled) {
            background-color: #4338ca;
            transform: translateY(-2px);
        }

        .btn-enroll:disabled {
            background-color: #cbd5e1;
            cursor: not-allowed;
        }

        .btn-enrolled {
            background-color: #10b981;
        }

        .btn-enrolled:hover {
            background-color: #059669;
        }

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
        }

        .btn-detail:hover {
            background-color: #eef2ff;
            color: #4338ca;
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

        .empty-state h3 {
            color: #475569;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #94a3b8;
            margin-bottom: 2rem;
        }

        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Loading spinner */
        .btn-enroll .spinner-border {
            width: 1rem;
            height: 1rem;
            border-width: 2px;
        }

        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 1050;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.75rem;
            }

            .card-img-wrapper {
                height: 160px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container">
        <div id="enrollToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Thành công</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body"></div>
        </div>
    </div>

    <!-- Navigation Bar -->
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

    <!-- Page Header -->
    <div class="page-header">
        <div class="container text-center">
            <h1 class="page-title">
                <?php echo $isStudentLoggedIn ? "Chào mừng, {$userName}!" : 'Khám phá Khóa học'; ?>
            </h1>
            <p class="page-subtitle mb-0">
                <?php
                echo $isStudentLoggedIn
                    ? 'Tìm kiếm và đăng ký khóa học mới phù hợp với bạn'
                    : 'Học tập mọi lúc, mọi nơi với các khóa học chất lượng cao';
                ?>
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container pb-5">
        <!-- Search & Filter Section -->
        <div class="search-filter-section">
            <form method="GET" action="<?php echo BASE_URL; ?>/courses" class="row g-3">
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

        <!-- Courses Grid -->
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
                                            <?php else: ?>
                                                <button class="btn-enroll enroll-btn" data-course-id="<?php echo $courseId; ?>"
                                                    data-course-title="<?php echo htmlspecialchars($course['title']); ?>">
                                                    <i class="fas fa-plus-circle me-1"></i>Đăng ký
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>/auth/login?redirect=/courses/detail/<?php echo $courseId; ?>"
                                                class="btn-enroll">
                                                <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập để đăng ký
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?php echo BASE_URL; ?>/courses/detail/<?php echo $courseId; ?>"
                                            class="btn-detail">
                                            Chi tiết
                                        </a>
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
                    echo $isStudentLoggedIn
                        ? 'Không có khóa học nào phù hợp với bộ lọc của bạn.'
                        : 'Không có khóa học nào phù hợp với tìm kiếm của bạn. Vui lòng thử lại với từ khóa khác.';
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const enrollButtons = document.querySelectorAll('.enroll-btn');
            const toast = new bootstrap.Toast(document.getElementById('enrollToast'));

            enrollButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const courseId = this.getAttribute('data-course-id');
                    const courseTitle = this.getAttribute('data-course-title');
                    const originalHTML = this.innerHTML;

                    // Disable button and show loading
                    this.disabled = true;
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang xử lý...';

                    // Send enrollment request
                    fetch('<?php echo BASE_URL; ?>/api/enroll', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            course_id: courseId,
                            user_id: <?php echo $userId ?? 'null'; ?>
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update button to "Vào học"
                                this.classList.add('btn-enrolled');
                                this.innerHTML = '<i class="fas fa-play-circle me-1"></i>Vào học';
                                this.disabled = false;

                                // Change button to link
                                const link = document.createElement('a');
                                link.href = '<?php echo BASE_URL; ?>/student/course/' + courseId;
                                link.className = 'btn-enroll btn-enrolled';
                                link.innerHTML = '<i class="fas fa-play-circle me-1"></i>Vào học';
                                this.parentNode.replaceChild(link, this);

                                // Add enrolled badge
                                const card = document.querySelector(`[data-course-id="${courseId}"]`);
                                const imgWrapper = card.querySelector('.card-img-wrapper');
                                if (!imgWrapper.querySelector('.enrolled-badge')) {
                                    const badge = document.createElement('span');
                                    badge.className = 'enrolled-badge';
                                    badge.innerHTML = '<i class="fas fa-check me-1"></i>Đã đăng ký';
                                    imgWrapper.appendChild(badge);
                                }

                                // Show success toast
                                const toastBody = document.querySelector('#enrollToast .toast-body');
                                toastBody.textContent = `Đã đăng ký thành công khóa học "${courseTitle}"!`;
                                toast.show();
                            } else {
                                throw new Error(data.message || 'Đăng ký thất bại');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.disabled = false;
                            this.innerHTML = originalHTML;

                            // Show error toast
                            const toastHeader = document.querySelector('#enrollToast .toast-header');
                            const toastBody = document.querySelector('#enrollToast .toast-body');
                            const icon = toastHeader.querySelector('i');

                            icon.className = 'fas fa-exclamation-circle text-danger me-2';
                            toastHeader.querySelector('strong').textContent = 'Lỗi';
                            toastBody.textContent = error.message || 'Có lỗi xảy ra. Vui lòng thử lại!';
                            toast.show();

                            // Reset toast style after hide
                            setTimeout(() => {
                                icon.className = 'fas fa-check-circle text-success me-2';
                                toastHeader.querySelector('strong').textContent = 'Thành công';
                            }, 3000);
                        });
                });
            });
        });
    </script>
</body>

</html>