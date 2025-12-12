<?php
// File: views/student/materials.php
// Mục đích: Hiển thị danh sách tài liệu từ tất cả các khóa học đã đăng ký
// Dữ liệu từ Controller: $pageTitle, $materials

$BASE_URL = $BASE_URL ?? '/base';
$pageTitle = $pageTitle ?? 'Tài liệu học tập';
// Giả định $materials là mảng các tài liệu đã được gán (hoặc là mảng rỗng)
$materials = $materials ?? [];

// Giả định các biến session đã có
$_SESSION['fullname'] = $_SESSION['fullname'] ?? 'Học viên';
$_SESSION['email'] = $_SESSION['email'] ?? 'student@example.com';
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
        /* CSS Tùy chỉnh (Đồng bộ với Layout Học viên) */
        :root {
            --primary-color: #4f46e5;
            --primary-light: #eef2ff;
            --dark-color: #1e293b;
            --gray-color: #64748b;
            --gray-border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.12);
            --radius-md: 12px;
        }

        body {
            background-color: #f9fafb;
            color: #334155;
            font-family: 'Inter', sans-serif;
        }

        /* Layout */
        .navbar-student {
            background: white;
            box-shadow: var(--shadow-sm);
            padding: 0.75rem 0;
        }

        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 1.5rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
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

        /* Sidebar Menu Items */
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.85rem 1.5rem;
            color: var(--gray-color);
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0.25rem 0.5rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        /* Header */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--gray-color);
            font-size: 0.95rem;
        }

        /* Material Card Styles */
        .material-card {
            background: white;
            border: 1px solid var(--gray-border);
            border-radius: var(--radius-md);
            transition: all 0.3s;
            height: 100%;
        }

        .material-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .file-icon-wrapper {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        /* Tùy chỉnh màu theo loại file */
        .file-type-pdf .file-icon-wrapper {
            background: #fee2e2;
            color: #ef4444;
        }

        .file-type-image .file-icon-wrapper {
            background: #d1fae5;
            color: #10b981;
        }

        .file-type-zip .file-icon-wrapper {
            background: #e0f2f1;
            color: #0f766e;
        }

        .file-type-sql .file-icon-wrapper {
            background: #f0f9ff;
            color: #3b82f6;
        }

        .file-type-code .file-icon-wrapper {
            background: #fdf2f8;
            color: #c026d3;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-student sticky-top">
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
        <nav class="sidebar-menu">
            <a href="<?php echo BASE_URL; ?>/student/dashboard" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/my-courses" class="sidebar-item">
                <i class="fas fa-book-open"></i>
                <span>Khóa học của tôi</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/student/materials" class="sidebar-item active">
                <i class="fas fa-file-alt"></i>
                <span>Tài liệu học tập</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/courses" class="sidebar-item">
                <i class="fas fa-search"></i>
                <span>Tìm khóa học</span>
            </a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-file-alt me-2 text-primary" style="font-size: 1.5rem;"></i>
                <?php echo $pageTitle; ?>
            </h1>
            <p class="page-subtitle">
                Tất cả các tài liệu đính kèm từ các bài học bạn đã đăng ký.
            </p>
        </div>

        <?php if (!empty($materials)): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php
                foreach ($materials as $material):
                    // [SỬ DỤNG DỮ LIỆU TỪ DB]
                    // Giả định Material Model trả về: filename, file_type, file_path, course_title
                    $fileType = strtolower($material['file_type'] ?? 'unknown');
                    $fileName = htmlspecialchars($material['filename'] ?? 'Tài liệu không tên');
                    $courseTitle = htmlspecialchars($material['course_title'] ?? 'Khóa học không rõ');
                    $filePath = htmlspecialchars($material['file_path'] ?? '#');
                    $fileSize = htmlspecialchars($material['size'] ?? 'N/A'); // Giả định Model tính toán size
            
                    // Xác định icon và class CSS
                    $fileIcon = match ($fileType) {
                        'pdf' => 'fas fa-file-pdf',
                        'image' => 'fas fa-image',
                        'zip', 'archive' => 'fas fa-file-archive',
                        'sql' => 'fas fa-database',
                        'doc', 'docx' => 'fas fa-file-word',
                        'ppt', 'pptx' => 'fas fa-file-powerpoint',
                        'code' => 'fas fa-file-code',
                        default => 'fas fa-file',
                    };
                    ?>
                    <div class="col">
                        <div class="material-card p-4 d-flex align-items-center file-type-<?php echo $fileType; ?>">
                            <div class="file-icon-wrapper me-3">
                                <i class="<?php echo $fileIcon; ?>"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="mb-1 fw-bold fs-6 text-truncate" title="<?php echo $fileName; ?>">
                                    <?php echo $fileName; ?>
                                </h5>
                                <small class="text-muted d-block">Khóa học: <?php echo $courseTitle; ?></small>
                                <small class="text-muted d-block"><?php echo strtoupper($fileType); ?> |
                                    <?php echo $fileSize; ?></small>
                            </div>
                            <a href="<?php echo $filePath; ?>" download="<?php echo $fileName; ?>"
                                class="btn btn-sm btn-primary ms-3" title="Tải xuống">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center p-5 border-0 shadow-sm rounded-3">
                <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                <h4>Chưa có tài liệu nào</h4>
                <p>Tài liệu đính kèm từ các bài học sẽ xuất hiện ở đây sau khi bạn đăng ký và truy cập khóa học.</p>
                <a href="<?php echo BASE_URL; ?>/student/my-courses" class="btn btn-primary mt-2">
                    <i class="fas fa-book-open me-2"></i>Xem Khóa học của tôi
                </a>
            </div>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>