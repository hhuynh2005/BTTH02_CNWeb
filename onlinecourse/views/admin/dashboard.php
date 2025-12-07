<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hệ thống khóa học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?controller=admin&action=dashboard">
                <i class="fas fa-user-shield me-2"></i>ADMIN PANEL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-light">
                            Xin chào,
                            <strong><?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'Admin'; ?></strong>
                        </span>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="index.php?controller=auth&action=logout" class="btn btn-danger btn-sm mt-1">
                            <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4 text-secondary border-bottom pb-2">Tổng quan hệ thống</h2>

        <div class="row g-4 mb-5">

            <div class="col-md-6 col-lg-3">
                <div class="card text-white bg-primary h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-1">Tổng thành viên</h6>
                                <h2 class="display-6 fw-bold mb-0">
                                    <?php echo isset($count['total']) ? $count['total'] : 0; ?></h2>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card text-white bg-success h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-1">Học viên</h6>
                                <h2 class="display-6 fw-bold mb-0">
                                    <?php echo isset($count['student']) ? $count['student'] : 0; ?></h2>
                            </div>
                            <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card text-dark bg-warning h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-1">Giảng viên</h6>
                                <h2 class="display-6 fw-bold mb-0">
                                    <?php echo isset($count['instructor']) ? $count['instructor'] : 0; ?></h2>
                            </div>
                            <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card text-white bg-danger h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-uppercase mb-1">Quản trị viên</h6>
                                <h2 class="display-6 fw-bold mb-0">
                                    <?php echo isset($count['admin']) ? $count['admin'] : 0; ?></h2>
                            </div>
                            <i class="fas fa-user-shield fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="mb-3 text-secondary">Chức năng quản lý</h4>
        <div class="row">
            <div class="col-md-4">
                <a href="index.php?controller=admin&action=listUsers" class="text-decoration-none">
                    <div class="card shadow-sm hover-shadow transition">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-users-cog fa-3x text-primary mb-3"></i>
                            <h5 class="card-title text-dark">Quản lý người dùng</h5>
                            <p class="card-text text-muted">Xem, sửa, xóa tài khoản hệ thống</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm opacity-50">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-book fa-3x text-secondary mb-3"></i>
                        <h5 class="card-title text-dark">Quản lý khóa học</h5>
                        <p class="card-text text-muted">(Đang phát triển bởi Nhóm 2)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>