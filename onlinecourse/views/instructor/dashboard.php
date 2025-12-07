<?php
// Giả sử logic kiểm tra Role (Nhóm 1/2) đã chạy trước khi hiển thị View này

include_once VIEWS_PATH . '/layouts/header.php';
?>

<?php
// Giả sử sidebar.php chứa logic hiển thị link dựa trên $_SESSION['role']
// Ví dụ: <a href="<?php echo BASE_URL; /instructor/my_courses">Quản lý Khóa học</a>
?>

<h1 class="mt-4">Dashboard Giảng viên</h1>
<p class="lead">Chào mừng, <?php echo $_SESSION['fullname'] ?? 'Giảng viên'; ?>! Đây là tóm tắt công việc của bạn.</p>
<hr>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-primary border-5 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng Khóa học</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-success border-5 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng Học viên</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">250</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 bg-light">
                <h6 class="m-0 font-weight-bold text-primary">Hành động nhanh</h6>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>/instructor/course/create" class="btn btn-outline-primary me-2 mb-2">
                    <i class="fas fa-plus"></i> Tạo Khóa học Mới
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/my_courses" class="btn btn-outline-secondary me-2 mb-2">
                    <i class="fas fa-edit"></i> Quản lý Khóa học
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/students/list" class="btn btn-outline-info me-2 mb-2">
                    <i class="fas fa-eye"></i> Xem Học viên
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Khóa học của tôi (Gần đây)</h6>
    </div>
    <div class="card-body">
        <p>Tạm thời chưa có dữ liệu khóa học...</p>
    </div>
</div>

<?php include_once VIEWS_PATH . '/layouts/footer.php'; ?>