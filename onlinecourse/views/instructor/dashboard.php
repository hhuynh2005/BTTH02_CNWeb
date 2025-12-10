<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Giảng viên - Online Course</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
</head>

<body>
    <nav class="navbar">
        <div class="container navbar-container">
            <div class="navbar-brand">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#F59E0B" />
                    <path
                        d="M20 12.5L12.5 16.25V23.75C12.5 27.5 15.625 30.875 20 31.875C24.375 30.875 27.5 27.5 27.5 23.75V16.25L20 12.5Z"
                        fill="white" />
                </svg>
                <span>Online Course - Giảng viên</span>
            </div>
            <div class="navbar-actions">
                <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline btn-sm">Xem trang chủ</a>
                <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong></span>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-danger btn-sm">Đăng xuất</a>
            </div>
        </div>
    </nav>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item active">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/course/manage" class="sidebar-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z" />
                    </svg>
                    Khóa học của tôi
                </a>
                <a href="<?php echo BASE_URL; ?>/course/create" class="sidebar-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" />
                    </svg>
                    Tạo khóa học mới
                </a>
            </div>
        </aside>

        <main class="admin-content">
            <div class="content-header">
                <h1>Dashboard Giảng viên</h1>
                <p>Chào mừng trở lại, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Tổng khóa học</h3>
                        <div class="stat-number"><?php echo $totalCourses; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Đã phê duyệt</h3>
                        <div class="stat-number"><?php echo $approvedCourses; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Chờ duyệt</h3>
                        <div class="stat-number"><?php echo $pendingCourses; ?></div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon primary">
                        <svg width="32" height="32" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                    </div>
                    <div class="stat-info">
                        <h3>Học viên</h3>
                        <div class="stat-number">0</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Khóa học gần đây</h3>
                    <a href="<?php echo BASE_URL; ?>/course/manage" class="btn btn-outline btn-sm">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($courses)): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tên khóa học</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recentCourses = array_slice($courses, 0, 5);
                                    foreach ($recentCourses as $course):
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($course['category_name']); ?></td>
                                            <td>
                                                <?php if ($course['price'] > 0): ?>
                                                    <?php echo number_format($course['price'], 0, ',', '.'); ?>đ
                                                <?php else: ?>
                                                    <span class="badge badge-success">Miễn phí</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusClass = [
                                                    'pending' => 'badge-warning',
                                                    'approved' => 'badge-success',
                                                    'rejected' => 'badge-danger'
                                                ];
                                                $statusText = [
                                                    'pending' => 'Chờ duyệt',
                                                    'approved' => 'Đã duyệt',
                                                    'rejected' => 'Từ chối'
                                                ];
                                                $status = $course['status'] ?? 'pending';
                                                ?>
                                                <span class="badge <?php echo $statusClass[$status]; ?>">
                                                    <?php echo $statusText[$status]; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="<?php echo BASE_URL; ?>/course/edit/<?php echo $course['id']; ?>"
                                                        class="btn btn-sm btn-outline" title="Chỉnh sửa">
                                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                        </svg>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>/lesson/manage/<?php echo $course['id']; ?>"
                                                        class="btn btn-sm btn-primary" title="Quản lý bài học">
                                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                            <path
                                                                d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
                                <circle cx="60" cy="60" r="60" fill="#F3F4F6" />
                                <path d="M60 30L35 42.5V72.5C35 87.5 47.5 100 60 102.5C72.5 100 85 87.5 85 72.5V42.5L60 30Z"
                                    fill="#D1D5DB" />
                            </svg>
                            <p>Bạn chưa có khóa học nào. Hãy tạo khóa học đầu tiên!</p>
                            <a href="<?php echo BASE_URL; ?>/course/create" class="btn btn-primary"
                                style="margin-top: 1rem;">
                                Tạo khóa học mới
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>