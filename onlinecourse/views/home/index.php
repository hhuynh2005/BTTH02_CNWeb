<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Online Course</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/home.css">
</head>

<body>
    <!-- Header/Navigation -->
    <nav class="navbar">
        <div class="container navbar-container">
            <div class="navbar-brand">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" rx="8" fill="#4F46E5" />
                    <path
                        d="M20 12.5L12.5 16.25V23.75C12.5 27.5 15.625 30.875 20 31.875C24.375 30.875 27.5 27.5 27.5 23.75V16.25L20 12.5Z"
                        fill="white" />
                </svg>
                <span>Online Course</span>
            </div>

            <ul class="navbar-menu">
                <li class="navbar-item"><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                <li class="navbar-item"><a href="<?php echo BASE_URL; ?>/course/index">Khóa học</a></li>
                <li class="navbar-item"><a href="<?php echo BASE_URL; ?>/about">Giới thiệu</a></li>
                <li class="navbar-item"><a href="<?php echo BASE_URL; ?>/contact">Liên hệ</a></li>
            </ul>

            <div class="navbar-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <button class="btn btn-outline btn-sm">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                            <?php echo htmlspecialchars($_SESSION['fullname']); ?>
                        </button>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline btn-sm">Đăng nhập</a>
                    <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-primary btn-sm">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Học tập không giới hạn với
                        <span class="text-gradient">Online Course</span>
                    </h1>
                    <p class="hero-description">
                        Khám phá hàng nghìn khóa học chất lượng cao từ các giảng viên hàng đầu.
                        Bắt đầu hành trình học tập của bạn ngay hôm nay!
                    </p>
                    <div class="hero-actions">
                        <a href="<?php echo BASE_URL; ?>/course/index" class="btn btn-primary btn-lg">
                            Khám phá khóa học
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" />
                            </svg>
                        </a>
                        <a href="<?php echo BASE_URL; ?>/about" class="btn btn-outline btn-lg">Tìm hiểu thêm</a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Khóa học</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Học viên</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">200+</div>
                            <div class="stat-label">Giảng viên</div>
                        </div>
                    </div>
                </div>

                <div class="hero-image">
                    <svg viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="250" cy="250" r="200" fill="#EEF2FF" />
                        <rect x="150" y="180" width="200" height="140" rx="12" fill="white" stroke="#4F46E5"
                            stroke-width="3" />
                        <rect x="170" y="200" width="60" height="8" rx="4" fill="#4F46E5" />
                        <rect x="170" y="220" width="120" height="6" rx="3" fill="#C7D2FE" />
                        <rect x="170" y="235" width="100" height="6" rx="3" fill="#C7D2FE" />
                        <circle cx="250" cy="120" r="40" fill="#4F46E5" />
                        <path d="M250 100L230 120L240 130L250 140L275 115L265 105L250 120V100Z" fill="white" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="courses-section">
        <div class="container">
            <div class="section-header">
                <h2>Khóa học nổi bật</h2>
                <p>Các khóa học được yêu thích nhất trên nền tảng</p>
            </div>

            <div class="courses-grid">
                <?php if (!empty($newestCourses)): ?>
                    <?php foreach ($newestCourses as $course): ?>
                        <div class="course-card">
                            <div class="course-image">
                                <?php if (!empty($course['image'])): ?>
                                    <img src="<?php echo BASE_URL; ?>/assets/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>"
                                        alt="<?php echo htmlspecialchars($course['title']); ?>">
                                <?php else: ?>
                                    <div class="course-image-placeholder">
                                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                                            <rect width="80" height="80" fill="#EEF2FF" />
                                            <path
                                                d="M40 25L25 32.5V47.5C25 55 31.25 61.75 40 63.75C48.75 61.75 55 55 55 47.5V32.5L40 25Z"
                                                fill="#4F46E5" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <span class="course-level"><?php echo htmlspecialchars($course['level']); ?></span>
                            </div>

                            <div class="course-content">
                                <h3 class="course-title">
                                    <a href="<?php echo BASE_URL; ?>/course/detail/<?php echo $course['id']; ?>">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </a>
                                </h3>

                                <p class="course-description">
                                    <?php echo htmlspecialchars(substr($course['description'], 0, 100)); ?>...
                                </p>

                                <div class="course-meta">
                                    <div class="course-instructor">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                        </svg>
                                        <span>Giảng viên</span>
                                    </div>
                                    <div class="course-duration">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                        </svg>
                                        <span><?php echo $course['duration_weeks']; ?> tuần</span>
                                    </div>
                                </div>

                                <div class="course-footer">
                                    <div class="course-price">
                                        <?php if ($course['price'] > 0): ?>
                                            <span class="price"><?php echo number_format($course['price'], 0, ',', '.'); ?>đ</span>
                                        <?php else: ?>
                                            <span class="price free">Miễn phí</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo BASE_URL; ?>/course/detail/<?php echo $course['id']; ?>"
                                        class="btn btn-primary btn-sm">
                                        Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
                            <circle cx="60" cy="60" r="60" fill="#F3F4F6" />
                            <path d="M60 30L35 42.5V72.5C35 87.5 47.5 100 60 102.5C72.5 100 85 87.5 85 72.5V42.5L60 30Z"
                                fill="#D1D5DB" />
                        </svg>
                        <p>Chưa có khóa học nào</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="section-footer">
                <a href="<?php echo BASE_URL; ?>/course/index" class="btn btn-outline">
                    Xem tất cả khóa học
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Online Course</h4>
                    <p>Nền tảng học tập trực tuyến hàng đầu Việt Nam</p>
                </div>
                <div class="footer-section">
                    <h4>Liên kết</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/course/index">Khóa học</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/about">Giới thiệu</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Hỗ trợ</h4>
                    <ul>
                        <li><a href="#">Trợ giúp</a></li>
                        <li><a href="#">Điều khoản</a></li>
                        <li><a href="#">Chính sách</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Online Course. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>