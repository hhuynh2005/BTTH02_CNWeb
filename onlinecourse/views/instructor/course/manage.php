<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khóa học - Giảng viên</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f9fafb;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state svg {
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
        }
    </style>
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
                <span>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong></span>
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </div>
    </nav>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/course/manage" class="sidebar-item active">
                    <i class="fas fa-book"></i> Khóa học của tôi
                </a>
                <a href="<?php echo BASE_URL; ?>/course/create" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i> Tạo khóa học mới
                </a>
            </div>
        </aside>

        <main class="admin-content">
            <div class="content-header">
                <h1><i class="fas fa-book"></i> Quản lý khóa học</h1>
                <p>Danh sách tất cả khóa học của bạn</p>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div
                    class="alert <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <i
                        class="fas <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-list"></i> Danh sách khóa học</h3>
                    <a href="<?php echo BASE_URL; ?>/course/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus-circle"></i> Tạo khóa học mới
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($courses)): ?>
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên khóa học</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Level</th>
                                        <th>Thời lượng</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($course['image'])): ?>
                                                    <img src="<?php echo BASE_URL; ?>/assets/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>"
                                                        alt="Course"
                                                        style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                <?php else: ?>
                                                    <div
                                                        style="width: 60px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px;">
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                                                <br>
                                                <small style="color: #6b7280;">
                                                    <?php echo htmlspecialchars(substr($course['description'], 0, 50)); ?>...
                                                </small>
                                            </td>
                                            <td><?php echo htmlspecialchars($course['category_name'] ?? 'N/A'); ?></td>
                                            <td>
                                                <?php if ($course['price'] > 0): ?>
                                                    <strong><?php echo number_format($course['price'], 0, ',', '.'); ?>đ</strong>
                                                <?php else: ?>
                                                    <span class="badge badge-success">Miễn phí</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-info"><?php echo htmlspecialchars($course['level']); ?></span>
                                            </td>
                                            <td><?php echo $course['duration_weeks']; ?> tuần</td>
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
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>/lesson/manage/<?php echo $course['id']; ?>"
                                                        class="btn btn-sm btn-primary" title="Quản lý bài học">
                                                        <i class="fas fa-book-open"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>/course/delete/<?php echo $course['id']; ?>"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Bạn có chắc muốn xóa khóa học này?')"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
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
                            <p>Bạn chưa có khóa học nào</p>
                            <a href="<?php echo BASE_URL; ?>/course/create" class="btn btn-primary"
                                style="margin-top: 1rem;">
                                <i class="fas fa-plus-circle"></i> Tạo khóa học đầu tiên
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>

</html>