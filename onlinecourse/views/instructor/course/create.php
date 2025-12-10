<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo khóa học mới - Giảng viên</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern Dashboard Styles - Đồng bộ với layout trước */
        :root {
            --primary: #4361ee;
            --primary-light: #eef2ff;
            --secondary: #3a0ca3;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --border: #e2e8f0;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --radius-sm: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: var(--light);
            color: var(--dark);
            min-height: 100vh;
        }

        /* Modern Layout */
        .modern-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* Sidebar */
        .modern-sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            justify-content: center;
        }

        .sidebar-logo i {
            font-size: 28px;
            color: #60a5fa;
        }

        .sidebar-logo span {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #60a5fa 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-sm);
            margin-top: 16px;
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .sidebar-user-info h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .sidebar-user-info p {
            margin: 0;
            font-size: 12px;
            color: #94a3b8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 24px;
            color: #cbd5e1;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border-left-color: #3b82f6;
        }

        .sidebar-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: white;
            border-left-color: #3b82f6;
        }

        .sidebar-item i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .modern-content {
            padding: 0;
            overflow-x: hidden;
        }

        /* Top Navigation */
        .top-nav {
            background: white;
            padding: 16px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }

        .top-nav-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--gray);
            margin: 0;
        }

        .top-nav-right {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--danger);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Main Content Area */
        .content-area {
            padding: 32px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header-title h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-title p {
            font-size: 16px;
            color: var(--gray);
        }

        /* Form Container */
        .form-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .form-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }

        .form-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .required {
            color: var(--danger);
            margin-left: 2px;
        }

        /* Form Body */
        .form-body {
            padding: 32px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-label i {
            width: 20px;
            color: var(--gray);
            margin-right: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--dark);
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control.error {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--dark);
            transition: all 0.3s;
            resize: vertical;
            min-height: 120px;
            line-height: 1.5;
            font-family: inherit;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-textarea.error {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            color: var(--dark);
            background: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select.error {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Input with unit */
        .input-with-unit {
            position: relative;
        }

        .input-with-unit .unit {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
            font-size: 14px;
            font-weight: 500;
        }

        .input-with-unit .form-control {
            padding-right: 60px;
        }

        /* Helper text */
        .form-help {
            display: block;
            margin-top: 8px;
            font-size: 12px;
            color: var(--gray);
            line-height: 1.4;
        }

        /* Character counter */
        .char-counter {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 8px;
        }

        .char-counter.warning {
            color: var(--warning);
        }

        .char-counter.danger {
            color: var(--danger);
        }

        /* Image Upload */
        .image-upload {
            border: 2px dashed var(--border);
            border-radius: var(--radius-sm);
            padding: 40px 20px;
            text-align: center;
            background: var(--light);
            cursor: pointer;
            transition: all 0.3s;
        }

        .image-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .upload-icon {
            font-size: 48px;
            color: var(--gray);
            margin-bottom: 16px;
        }

        .upload-text {
            font-size: 16px;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .upload-hint {
            font-size: 14px;
            color: var(--gray);
        }

        .d-none {
            display: none !important;
        }

        /* Image Preview */
        .image-preview-container {
            margin-top: 24px;
        }

        .preview-image {
            max-width: 400px;
            width: 100%;
            height: auto;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            display: block;
        }

        .preview-actions {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        /* Form Footer */
        .form-footer {
            padding: 24px 32px;
            border-top: 1px solid var(--border);
            background: var(--light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: white;
            color: var(--dark);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: var(--gray-light);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
        }

        /* Error Messages */
        .form-error {
            display: none;
            margin-top: 8px;
            font-size: 12px;
            color: var(--danger);
            font-weight: 500;
        }

        .form-error.show {
            display: block;
        }

        /* Alert Messages */
        .alert {
            padding: 16px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            border: 1px solid #86efac;
            color: #065f46;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 1px solid #fca5a5;
            color: #991b1b;
        }

        .alert i {
            font-size: 20px;
        }

        /* Level Options */
        .level-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .level-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .level-dot.beginner {
            background: #10b981;
        }

        .level-dot.intermediate {
            background: #f59e0b;
        }

        .level-dot.advanced {
            background: #ef4444;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .modern-layout {
                grid-template-columns: 1fr;
            }

            .modern-sidebar {
                display: none;
            }

            .content-area {
                padding: 16px;
            }
        }

        @media (max-width: 768px) {
            .top-nav {
                padding: 16px;
                flex-direction: column;
                gap: 12px;
                align-items: stretch;
            }

            .top-nav-right {
                flex-direction: column;
            }

            .form-body {
                padding: 24px;
            }

            .form-footer {
                flex-direction: column;
                gap: 16px;
            }

            .form-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="modern-layout">
        <!-- Sidebar -->
        <aside class="modern-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>EduMaster</span>
                </div>
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="sidebar-user-info">
                        <h4><?php echo htmlspecialchars($_SESSION['fullname']); ?></h4>
                        <p>Giảng viên</p>
                    </div>
                </div>
            </div>

            <nav class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/course/manage" class="sidebar-item">
                    <i class="fas fa-book"></i>
                    <span>Khóa học của tôi</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/course/create" class="sidebar-item active">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tạo khóa học</span>
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/enrollments" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Học viên</span>
                </a>
                <div class="sidebar-divider"></div>
                <a href="<?php echo BASE_URL; ?>/" class="sidebar-item" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Xem trang chủ</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="modern-content">
            <!-- Top Navigation -->
            <nav class="top-nav">
                <div class="top-nav-left">
                    <h1 class="page-title">Tạo khóa học mới</h1>
                    <p class="page-subtitle">Bắt đầu xây dựng nội dung giảng dạy của bạn</p>
                </div>

                <div class="top-nav-right">
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        Đăng xuất
                    </a>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Alert Messages -->
                <?php if (isset($_GET['msg'])): ?>
                    <div
                        class="alert <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                        <i
                            class="fas <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <?php echo htmlspecialchars($_GET['msg']); ?>
                    </div>
                <?php endif; ?>

                <!-- Form Container -->
                <div class="form-container">
                    <div class="form-header">
                        <h3>
                            <i class="fas fa-info-circle"></i>
                            Thông tin khóa học
                            <small class="required">*</small>
                            <span style="font-size: 12px; color: var(--gray); margin-left: 8px;">(Các trường có dấu * là
                                bắt buộc)</span>
                        </h3>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/course/store" method="POST" enctype="multipart/form-data"
                        id="courseForm">
                        <div class="form-body">
                            <!-- Title -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-heading"></i>
                                    Tên khóa học
                                    <span class="required">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="Ví dụ: Lập trình PHP từ cơ bản đến nâng cao" required maxlength="255"
                                    oninput="updateCharCount('title', 'titleCounter', 255)">
                                <small class="form-help">
                                    Tên khóa học nên ngắn gọn, rõ ràng và hấp dẫn
                                    <span id="titleCounter" class="char-counter">0/255</span>
                                </small>
                                <div class="form-error" id="titleError"></div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-align-left"></i>
                                    Mô tả khóa học
                                    <span class="required">*</span>
                                </label>
                                <textarea id="description" name="description" class="form-textarea" rows="6"
                                    placeholder="Mô tả chi tiết về nội dung khóa học, mục tiêu học tập, đối tượng phù hợp, kỹ năng sẽ đạt được..."
                                    required oninput="updateCharCount('description', 'descCounter', 1000)"></textarea>
                                <small class="form-help">
                                    Mô tả chi tiết sẽ thu hút nhiều học viên hơn
                                    <span id="descCounter" class="char-counter">0/1000</span>
                                </small>
                                <div class="form-error" id="descError"></div>
                            </div>

                            <!-- Grid Layout for Category, Level, Price, Duration -->
                            <div class="form-grid">
                                <!-- Category -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-folder"></i>
                                        Danh mục
                                        <span class="required">*</span>
                                    </label>
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php if (isset($categories) && is_array($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <div class="form-error" id="categoryError"></div>
                                </div>

                                <!-- Level -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-signal"></i>
                                        Cấp độ
                                        <span class="required">*</span>
                                    </label>
                                    <select id="level" name="level" class="form-select" required>
                                        <option value="">-- Chọn cấp độ --</option>
                                        <option value="Beginner">
                                            <span class="level-option">
                                                <span class="level-dot beginner"></span>
                                                Beginner (Cơ bản)
                                            </span>
                                        </option>
                                        <option value="Intermediate">
                                            <span class="level-option">
                                                <span class="level-dot intermediate"></span>
                                                Intermediate (Trung cấp)
                                            </span>
                                        </option>
                                        <option value="Advanced">
                                            <span class="level-option">
                                                <span class="level-dot advanced"></span>
                                                Advanced (Nâng cao)
                                            </span>
                                        </option>
                                    </select>
                                    <small class="form-help">Chọn cấp độ phù hợp với nội dung khóa học</small>
                                    <div class="form-error" id="levelError"></div>
                                </div>

                                <!-- Price -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tag"></i>
                                        Giá khóa học
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-with-unit">
                                        <input type="number" id="price" name="price" class="form-control"
                                            placeholder="0" min="0" step="1000" required>
                                        <span class="unit">VNĐ</span>
                                    </div>
                                    <small class="form-help">Nhập 0 nếu khóa học miễn phí</small>
                                    <div class="form-error" id="priceError"></div>
                                </div>

                                <!-- Duration -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-clock"></i>
                                        Thời lượng
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-with-unit">
                                        <input type="number" id="duration_weeks" name="duration_weeks"
                                            class="form-control" placeholder="4" min="1" max="52" required>
                                        <span class="unit">tuần</span>
                                    </div>
                                    <small class="form-help">Từ 1 đến 52 tuần</small>
                                    <div class="form-error" id="durationError"></div>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-image"></i>
                                    Ảnh đại diện khóa học
                                </label>

                                <div class="image-upload" id="uploadArea"
                                    onclick="document.getElementById('image').click()">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Nhấp để tải ảnh lên</div>
                                    <div class="upload-hint">JPG, PNG, GIF (Tối đa 2MB)</div>
                                </div>

                                <input type="file" id="image" name="image" class="d-none" accept="image/*"
                                    onchange="previewImage(event)">

                                <!-- Image Preview -->
                                <div id="imagePreview" class="image-preview-container" style="display: none;">
                                    <img id="preview" src="" alt="Preview" class="preview-image">
                                    <div class="preview-actions">
                                        <button type="button" class="btn btn-danger" onclick="removePreview()">
                                            <i class="fas fa-trash"></i> Xóa ảnh
                                        </button>
                                        <button type="button" class="btn btn-secondary"
                                            onclick="document.getElementById('image').click()">
                                            <i class="fas fa-sync"></i> Thay đổi
                                        </button>
                                    </div>
                                </div>

                                <small class="form-help">
                                    💡 Kích thước đề xuất: 800x450px (tỷ lệ 16:9)
                                </small>
                                <div class="form-error" id="imageError"></div>
                            </div>
                        </div>

                        <!-- Form Footer -->
                        <div class="form-footer">
                            <a href="<?php echo BASE_URL; ?>/course/manage" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Quay lại
                            </a>
                            <div class="form-actions">
                                <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-redo"></i>
                                    Làm mới
                                </button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-plus-circle"></i>
                                    Tạo khóa học
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Character counter
        function updateCharCount(inputId, counterId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            const length = input.value.length;

            counter.textContent = `${length}/${max}`;

            // Change color based on length
            counter.classList.remove('warning', 'danger');
            if (length > max * 0.9) {
                counter.classList.add('danger');
            } else if (length > max * 0.8) {
                counter.classList.add('warning');
            }
        }

        // Image Preview
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('imagePreview');
            const uploadArea = document.getElementById('uploadArea');
            const errorDiv = document.getElementById('imageError');

            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    errorDiv.textContent = 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF)';
                    errorDiv.classList.add('show');
                    event.target.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    errorDiv.textContent = 'Kích thước file không được vượt quá 2MB';
                    errorDiv.classList.add('show');
                    event.target.value = '';
                    return;
                }

                errorDiv.classList.remove('show');

                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result;
                    previewContainer.style.display = 'block';
                    uploadArea.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        }

        // Remove preview
        function removePreview() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('uploadArea').style.display = 'block';
        }

        // Reset form
        function resetForm() {
            removePreview();
            document.querySelectorAll('.form-error').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control, .form-textarea, .form-select').forEach(el => {
                el.classList.remove('error');
            });
            updateCharCount('title', 'titleCounter', 255);
            updateCharCount('description', 'descCounter', 1000);
        }

        // Form validation
        document.getElementById('courseForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Clear previous errors
            document.querySelectorAll('.form-error').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control, .form-textarea, .form-select').forEach(el => {
                el.classList.remove('error');
            });

            let isValid = true;
            const errors = {};

            // Validate title
            const title = document.getElementById('title').value.trim();
            if (!title) {
                errors.title = 'Tiêu đề không được để trống';
                isValid = false;
            } else if (title.length < 5) {
                errors.title = 'Tiêu đề phải có ít nhất 5 ký tự';
                isValid = false;
            } else if (title.length > 255) {
                errors.title = 'Tiêu đề không được quá 255 ký tự';
                isValid = false;
            }

            // Validate description
            const description = document.getElementById('description').value.trim();
            if (!description) {
                errors.description = 'Mô tả không được để trống';
                isValid = false;
            } else if (description.length < 20) {
                errors.description = 'Mô tả phải có ít nhất 20 ký tự';
                isValid = false;
            }

            // Validate category
            const category = document.getElementById('category_id').value;
            if (!category) {
                errors.category = 'Vui lòng chọn danh mục';
                isValid = false;
            }

            // Validate level
            const level = document.getElementById('level').value;
            if (!level) {
                errors.level = 'Vui lòng chọn cấp độ';
                isValid = false;
            }

            // Validate price
            const price = document.getElementById('price').value;
            if (price === '') {
                errors.price = 'Giá không được để trống';
                isValid = false;
            } else if (parseFloat(price) < 0) {
                errors.price = 'Giá không được âm';
                isValid = false;
            }

            // Validate duration
            const duration = document.getElementById('duration_weeks').value;
            if (!duration) {
                errors.duration = 'Thời lượng không được để trống';
                isValid = false;
            } else if (duration < 1 || duration > 52) {
                errors.duration = 'Thời lượng phải từ 1 đến 52 tuần';
                isValid = false;
            }

            // Display errors
            Object.keys(errors).forEach(key => {
                const errorElement = document.getElementById(key + 'Error');
                const inputElement = document.getElementById(
                    key === 'duration' ? 'duration_weeks' :
                        key === 'category' ? 'category_id' : key
                );

                if (errorElement) {
                    errorElement.textContent = errors[key];
                    errorElement.classList.add('show');
                }
                if (inputElement) {
                    inputElement.classList.add('error');
                }
            });

            // If valid, submit form
            if (isValid) {
                // Show loading state
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
                submitBtn.disabled = true;

                // Submit the form
                this.submit();
            } else {
                // Scroll to first error
                const firstError = document.querySelector('.form-control.error, .form-textarea.error, .form-select.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize counters
            updateCharCount('title', 'titleCounter', 255);
            updateCharCount('description', 'descCounter', 1000);

            // Clear errors on input
            const inputs = ['title', 'description', 'category_id', 'level', 'price', 'duration_weeks'];
            inputs.forEach(inputId => {
                const element = document.getElementById(inputId);
                if (element) {
                    element.addEventListener('input', function () {
                        this.classList.remove('error');
                        const errorId = inputId.replace('_id', '').replace('_weeks', '') + 'Error';
                        const errorElement = document.getElementById(errorId);
                        if (errorElement) {
                            errorElement.classList.remove('show');
                            errorElement.textContent = '';
                        }
                    });
                }
            });

            // Add click animation to buttons
            document.querySelectorAll('.btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    if (!this.disabled) {
                        this.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            this.style.transform = '';
                        }, 150);
                    }
                });
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function (e) {
                // Ctrl/Cmd + S: Save form
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    document.getElementById('submitBtn').click();
                }

                // Ctrl/Cmd + R: Reset form
                if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                    e.preventDefault();
                    resetForm();
                }

                // Ctrl/Cmd + B: Go back
                if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                    e.preventDefault();
                    window.location.href = '<?php echo BASE_URL; ?>/course/manage';
                }
            });
        });
    </script>
</body>

</html>