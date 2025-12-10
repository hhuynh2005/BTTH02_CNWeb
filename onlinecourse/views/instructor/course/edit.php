<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa khóa học - Giảng viên</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .required {
            color: #ef4444;
        }

        .form-help {
            display: block;
            margin-top: 0.25rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .form-error {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .input-with-unit {
            position: relative;
        }

        .input-with-unit .form-control {
            padding-right: 4rem;
        }

        .input-with-unit .unit {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 0.875rem;
        }

        .image-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 2rem;
            text-align: center;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload-area:hover {
            border-color: #4f46e5;
            background: #f3f4f6;
        }

        .upload-placeholder i {
            font-size: 2.5rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .upload-placeholder p {
            margin: 0.5rem 0;
            color: #6b7280;
        }

        .upload-hint {
            font-size: 0.875rem;
            color: #9ca3af !important;
        }

        .d-none {
            display: none !important;
        }

        .current-image {
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #f9fafb;
        }

        .current-image-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #6b7280;
        }

        .current-image img {
            max-width: 300px;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .image-preview-container {
            margin-top: 1rem;
        }

        .image-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #6b7280;
        }

        .image-preview {
            max-width: 300px;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }

            .image-preview,
            .current-image img {
                max-width: 100%;
            }
        }

        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
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

    <!-- Main Content -->
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/course/manage" class="sidebar-item">
                    <i class="fas fa-book"></i> Khóa học của tôi
                </a>
                <a href="<?php echo BASE_URL; ?>/course/create" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i> Tạo khóa học mới
                </a>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="admin-content">
            <div class="content-header">
                <h1><i class="fas fa-edit"></i> Chỉnh sửa khóa học</h1>
                <p>Cập nhật thông tin khóa học</p>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div
                    class="alert <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <i
                        class="fas <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?php echo BASE_URL; ?>/course/update/<?php echo $course['id']; ?>" method="POST"
                enctype="multipart/form-data" id="courseForm">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-info-circle"></i> Thông tin khóa học</h3>
                        <small class="text-muted">Các trường có dấu * là bắt buộc</small>
                    </div>

                    <div class="card-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Tên khóa học <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control"
                                value="<?php echo htmlspecialchars($course['title']); ?>" required maxlength="255">
                            <small class="form-help">Tối đa 255 ký tự</small>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Mô tả khóa học <span class="required">*</span></label>
                            <textarea id="description" name="description" class="form-textarea" rows="6"
                                required><?php echo htmlspecialchars($course['description']); ?></textarea>
                            <small class="form-help">Mô tả chi tiết sẽ thu hút nhiều học viên hơn</small>
                        </div>

                        <!-- Category & Level -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="category_id">Danh mục <span class="required">*</span></label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <!-- SỬA LỖI: Thay while bằng foreach -->
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>" <?php echo $course['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="level">Cấp độ <span class="required">*</span></label>
                                <select id="level" name="level" class="form-control" required>
                                    <option value="">-- Chọn cấp độ --</option>
                                    <option value="Beginner" <?php echo $course['level'] == 'Beginner' ? 'selected' : ''; ?>>Beginner (Cơ bản)</option>
                                    <option value="Intermediate" <?php echo $course['level'] == 'Intermediate' ? 'selected' : ''; ?>>Intermediate (Trung cấp)</option>
                                    <option value="Advanced" <?php echo $course['level'] == 'Advanced' ? 'selected' : ''; ?>>Advanced (Nâng cao)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Price & Duration -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Giá (VNĐ) <span class="required">*</span></label>
                                <div class="input-with-unit">
                                    <input type="number" id="price" name="price" class="form-control"
                                        value="<?php echo $course['price']; ?>" min="0" step="1000" required>
                                    <span class="unit">VNĐ</span>
                                </div>
                                <small class="form-help">Nhập 0 nếu khóa học miễn phí</small>
                            </div>

                            <div class="form-group">
                                <label for="duration_weeks">Thời lượng (tuần) <span class="required">*</span></label>
                                <div class="input-with-unit">
                                    <input type="number" id="duration_weeks" name="duration_weeks" class="form-control"
                                        value="<?php echo $course['duration_weeks']; ?>" min="1" max="52" required>
                                    <span class="unit">tuần</span>
                                </div>
                                <small class="form-help">Từ 1 đến 52 tuần</small>
                            </div>
                        </div>

                        <!-- Current Image -->
                        <?php if (!empty($course['image'])): ?>
                            <div class="form-group">
                                <label>Ảnh hiện tại</label>
                                <div class="current-image">
                                    <p class="current-image-label">
                                        <i class="fas fa-image"></i> Ảnh đang sử dụng:
                                    </p>
                                    <img src="<?php echo BASE_URL; ?>/assets/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>"
                                        alt="Current Image" class="current-image-preview">
                                    <div class="mt-2">
                                        <input type="checkbox" id="remove_image" name="remove_image" value="1">
                                        <label for="remove_image" style="color: #6b7280; font-size: 0.875rem;">
                                            <i class="fas fa-trash"></i> Xóa ảnh này
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- New Image Upload -->
                        <div class="form-group">
                            <label for="image">Ảnh mới (thay thế)</label>
                            <div class="image-upload-area" id="uploadArea">
                                <div class="upload-placeholder" onclick="document.getElementById('image').click()">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Nhấp để tải ảnh mới lên</p>
                                    <p class="upload-hint">JPG, PNG, GIF (Tối đa 2MB)</p>
                                </div>
                                <input type="file" id="image" name="image" class="d-none" accept="image/*"
                                    onchange="previewImage(event)">
                            </div>

                            <!-- New Image Preview -->
                            <div id="imagePreview" class="image-preview-container" style="display: none;">
                                <p class="image-label">Ảnh mới:</p>
                                <img id="preview" src="" alt="Preview" class="image-preview">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removePreview()">
                                    <i class="fas fa-trash"></i> Xóa ảnh mới
                                </button>
                            </div>

                            <small class="form-help">Kích thước đề xuất: 800x450px. Nếu không chọn ảnh mới, ảnh cũ sẽ
                                được giữ nguyên.</small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-actions">
                            <a href="<?php echo BASE_URL; ?>/course/manage" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary" name="submit" id="submitBtn">
                                <i class="fas fa-save"></i> Cập nhật khóa học
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        // Image Preview
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('imagePreview');
            const uploadArea = document.getElementById('uploadArea');

            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    alert('Chỉ chấp nhận file ảnh (JPG, PNG, GIF)');
                    event.target.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Kích thước file không được vượt quá 2MB');
                    event.target.value = '';
                    return;
                }

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

        // Form validation
        document.getElementById('courseForm').addEventListener('submit', function (e) {
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>