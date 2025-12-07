<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'EduLearn | Học trực tuyến' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon/favicon-16x16.png">
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="Nền tảng học trực tuyến chất lượng cao với hàng ngàn khóa học từ chuyên gia">
    <meta name="keywords" content="học online, khóa học, giáo dục, kỹ năng">
    <meta name="author" content="EduLearn Team">
    
    <!-- Open Graph -->
    <meta property="og:title" content="EduLearn | Học trực tuyến">
    <meta property="og:description" content="Nền tảng học trực tuyến chất lượng cao">
    <meta property="og:image" content="/assets/images/og-image.jpg">
    <meta property="og:url" content="https://edulearn.edu.vn">
    
    <?php if (isset($additionalCSS)): ?>
            <?php foreach ($additionalCSS as $css): ?>
                    <link rel="stylesheet" href="<?= $css ?>">
            <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- ===== TOP BAR ===== -->
    <div class="top-bar bg-dark text-white py-2 d-none d-lg-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="top-bar-info">
                        <span class="me-3"><i class="fas fa-phone-alt me-1"></i> 1800 1234</span>
                        <span><i class="fas fa-envelope me-1"></i> support@edulearn.edu.vn</span>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="top-bar-links">
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white-50 me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white-50"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MAIN NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <div class="logo-icon bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="fas fa-graduation-cap text-white fa-lg"></i>
                </div>
                <span class="logo-text fw-bold text-primary fs-3">Edu<span class="text-dark">Learn</span></span>
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Search Bar (Desktop) -->
            <div class="d-none d-lg-flex mx-4 flex-grow-1" style="max-width: 500px;">
                <div class="input-group search-container">
                    <input type="text" class="form-control border-end-0 rounded-start-pill ps-4" placeholder="Tìm kiếm khóa học...">
                    <button class="btn btn-primary rounded-end-pill px-4" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Search Bar (Mobile) -->
                <div class="d-lg-none my-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm...">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <ul class="navbar-nav mx-auto mx-lg-0">
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link fw-medium px-3 py-2 rounded" href="/">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link fw-medium px-3 py-2 rounded" href="/courses">
                            <i class="fas fa-book me-1"></i> Khóa học
                        </a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link fw-medium px-3 py-2 rounded" href="/categories">
                            <i class="fas fa-th-large me-1"></i> Danh mục
                        </a>
                    </li>
                    <li class="nav-item mx-lg-1">
                        <a class="nav-link fw-medium px-3 py-2 rounded" href="/instructors">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Giảng viên
                        </a>
                    </li>
                    
                    <?php if (isset($_SESSION['user'])): ?>
                            <!-- Role-based Navigation -->
                            <?php if ($_SESSION['user']['role'] == 1 || $_SESSION['user']['role'] == 2): ?>
                                    <li class="nav-item mx-lg-1">
                                        <a class="nav-link fw-medium px-3 py-2 rounded text-success" href="/instructor/dashboard">
                                            <i class="fas fa-chalkboard me-1"></i> Dạy học
                                        </a>
                                    </li>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['role'] == 2): ?>
                                    <li class="nav-item mx-lg-1">
                                        <a class="nav-link fw-medium px-3 py-2 rounded text-danger" href="/admin/dashboard">
                                            <i class="fas fa-cogs me-1"></i> Quản trị
                                        </a>
                                    </li>
                            <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <!-- User Actions -->
                <div class="navbar-nav align-items-lg-center">
                    <?php if (isset($_SESSION['user'])): ?>
                            <!-- Notifications -->
                            <div class="nav-item dropdown mx-2">
                                <a class="nav-link position-relative p-2" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell fa-lg text-secondary"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        3
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 300px;">
                                    <div class="px-3 py-2 border-bottom">
                                        <h6 class="mb-0">Thông báo</h6>
                                    </div>
                                    <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                                        <a href="#" class="dropdown-item py-3 border-bottom">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-book"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p class="mb-1">Khóa học mới: "Lập trình Python"</p>
                                                    <small class="text-muted">2 giờ trước</small>
                                                </div>
                                            </div>
                                        </a>
                                        <!-- More notifications -->
                                    </div>
                                    <div class="px-3 py-2 border-top">
                                        <a href="#" class="text-primary small">Xem tất cả</a>
                                    </div>
                                </div>
                            </div>

                            <!-- User Dropdown -->
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center p-2" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="avatar-sm overflow-hidden rounded-circle border border-2 border-primary">
                                        <?php if (!empty($_SESSION['user']['avatar'])): ?>
                                                <img src="/assets/uploads/avatars/<?= $_SESSION['user']['avatar'] ?>" 
                                                     alt="Avatar" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                                <div class="w-100 h-100 bg-primary d-flex align-items-center justify-content-center text-white">
                                                    <?= strtoupper(substr($_SESSION['user']['fullname'], 0, 1)) ?>
                                                </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-none d-lg-block ms-2">
                                        <span class="fw-medium"><?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
                                        <small class="d-block text-muted">
                                            <?php
                                            switch ($_SESSION['user']['role']) {
                                                case 0:
                                                    echo 'Học viên';
                                                    break;
                                                case 1:
                                                    echo 'Giảng viên';
                                                    break;
                                                case 2:
                                                    echo 'Quản trị viên';
                                                    break;
                                            }
                                            ?>
                                        </small>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 220px;">
                                    <li>
                                        <a class="dropdown-item py-2" href="/student/dashboard">
                                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/profile">
                                            <i class="fas fa-user me-2"></i> Hồ sơ cá nhân
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/my-courses">
                                            <i class="fas fa-book-open me-2"></i> Khóa học của tôi
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="/settings">
                                            <i class="fas fa-cog me-2"></i> Cài đặt
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="/auth/logout">
                                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>

                    <?php else: ?>
                            <!-- Guest Actions -->
                            <div class="d-flex align-items-center">
                                <a href="/auth/login" class="btn btn-outline-primary me-2 px-4">
                                    <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                                </a>
                                <a href="/auth/register" class="btn btn-primary px-4">
                                    <i class="fas fa-user-plus me-1"></i> Đăng ký
                                </a>
                            </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== BREADCRUMB (Conditional) ===== -->
    <?php if (isset($showBreadcrumb) && $showBreadcrumb): ?>
        <nav aria-label="breadcrumb" class="bg-light py-3 border-bottom">
            <div class="container">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
                    <?php if (isset($breadcrumbItems)): ?>
                            <?php foreach ($breadcrumbItems as $item): ?>
                                    <?php if (isset($item['active']) && $item['active']): ?>
                                            <li class="breadcrumb-item active" aria-current="page"><?= $item['title'] ?></li>
                                    <?php else: ?>
                                            <li class="breadcrumb-item"><a href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
                                    <?php endif; ?>
                            <?php endforeach; ?>
                    <?php endif; ?>
                </ol>
            </div>
        </nav>
    <?php endif; ?>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="flex-grow-1">