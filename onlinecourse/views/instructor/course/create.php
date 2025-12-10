<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo khóa học mới - Giảng viên</title>
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
            display: none;
        }

        .form-error.show {
            display: block;
        }

        .form-control.error,
        .form-textarea.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
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
            font-weight: 500;
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
            font-size: 3rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }

        .upload-placeholder p {
            margin: 0.5rem 0;
            color: #6b7280;
            font-weight: 500;
        }

        .upload-hint {
            font-size: 0.875rem;
            color: #9ca3af !important;
        }

        .d-none {
            display: none !important;
        }

        .image-preview-container {
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            background: #f9fafb;
        }

        .image-label {
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .image-preview {
            max-width: 400px;
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: block;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-group {
            display: flex;
            gap: 1rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert i {
            font-size: 1.25rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .form-actions {
                flex-direction: column-reverse;
                gap: 1rem;
            }

            .btn-group {
                width: 100%;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Character counter colors */
        .char-warning {
            color: #f59e0b !important;
        }

        .char-danger {
            color: #ef4444 !important;
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
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="btn btn-danger btn-sm">Đăng xuất</a>
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
                <a href="<?php echo BASE_URL; ?>/course/create" class="sidebar-item active">
                    <i class="fas fa-plus-circle"></i> Tạo khóa học mới
                </a>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="admin-content">
            <div class="content-header">
                <h1><i class="fas fa-plus-circle"></i> Tạo khóa học mới</h1>
                <p>Điền đầy đủ thông tin để tạo khóa học của bạn</p>
            </div>

            <!-- Messages -->
            <?php if (isset($_GET['msg'])): ?>
                <div
                    class="alert <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'alert-success' : 'alert-error'; ?>">
                    <i
                        class="fas <?php echo strpos($_GET['msg'], 'thành công') !== false ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?php echo BASE_URL; ?>/course/store" method="POST" enctype="multipart/form-data"
                id="courseForm">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-info-circle"></i> Thông tin khóa học</h3>
                        <small class="text-muted">Các trường có dấu <span class="required">*</span> là bắt buộc</small>
                    </div>

                    <div class="card-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">
                                <i class="fas fa-heading"></i> Tên khóa học <span class="required">*</span>
                            </label>
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Ví dụ: Lập trình PHP từ cơ bản đến nâng cao" required maxlength="255"
                                oninput="updateCharCount('title', 'titleCounter', 255)">
                            <small class="form-help">
                                <span id="titleCounter">0/255</span> ký tự
                            </small>
                            <div class="form-error" id="titleError"></div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-align-left"></i> Mô tả khóa học <span class="required">*</span>
                            </label>
                            <textarea id="description" name="description" class="form-textarea" rows="6"
                                placeholder="Mô tả chi tiết về nội dung khóa học, mục tiêu học tập, đối tượng phù hợp, kỹ năng sẽ đạt được..."
                                required oninput="updateCharCount('description', 'descCounter', 1000)"></textarea>
                            <small class="form-help">
                                <span id="descCounter">0/1000</span> ký tự. Mô tả chi tiết sẽ thu hút nhiều học viên hơn
                            </small>
                            <div class="form-error" id="descError"></div>
                        </div>

                        <!-- Category & Level -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="category_id">
                                    <i class="fas fa-folder"></i> Danh mục <span class="required">*</span>
                                </label>
                                <select id="category_id" name="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-error" id="categoryError"></div>
                            </div>

                            <div class="form-group">
                                <label for="level">
                                    <i class="fas fa-signal"></i> Cấp độ <span class="required">*</span>
                                </label>
                                <select id="level" name="level" class="form-control" required>
                                    <option value="">-- Chọn cấp độ --</option>
                                    <option value="Beginner">🟢 Beginner (Cơ bản)</option>
                                    <option value="Intermediate">🟡 Intermediate (Trung cấp)</option>
                                    <option value="Advanced">🔴 Advanced (Nâng cao)</option>
                                </select>
                                <div class="form-error" id="levelError"></div>
                            </div>
                        </div>

                        <!-- Price & Duration -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">
                                    <i class="fas fa-tag"></i> Giá khóa học <span class="required">*</span>
                                </label>
                                <div class="input-with-unit">
                                    <input type="number" id="price" name="price" class="form-control" placeholder="0"
                                        min="0" step="1000" required>
                                    <span class="unit">VNĐ</span>
                                </div>
                                <small class="form-help">💡 Nhập 0 nếu khóa học miễn phí</small>
                                <div class="form-error" id="priceError"></div>
                            </div>

                            <div class="form-group">
                                <label for="duration_weeks">
                                    <i class="fas fa-clock"></i> Thời lượng <span class="required">*</span>
                                </label>
                                <div class="input-with-unit">
                                    <input type="number" id="duration_weeks" name="duration_weeks" class="form-control"
                                        placeholder="4" min="1" max="52" required>
                                    <span class="unit">tuần</span>
                                </div>
                                <small class="form-help">📅 Từ 1 đến 52 tuần</small>
                                <div class="form-error" id="durationError"></div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label for="image">
                                <i class="fas fa-image"></i> Ảnh đại diện khóa học
                            </label>

                            <div class="image-upload-area" id="uploadArea"
                                onclick="document.getElementById('image').click()">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Nhấp để tải ảnh lên</p>
                                    <p class="upload-hint">JPG, PNG, GIF (Tối đa 2MB)</p>
                                </div>
                            </div>

                            <input type="file" id="image" name="image" class="d-none" accept="image/*"
                                onchange="previewImage(event)">

                            <!-- Image Preview -->
                            <div id="imagePreview" class="image-preview-container" style="display: none;">
                                <p class="image-label">
                                    <i class="fas fa-check-circle" style="color: #10b981;"></i> Ảnh đã chọn:
                                </p>
                                <img id="preview" src="" alt="Preview" class="image-preview">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removePreview()">
                                    <i class="fas fa-trash"></i> Xóa ảnh
                                </button>
                            </div>

                            <small class="form-help">
                                💡 Kích thước đề xuất: 800x450px (tỷ lệ 16:9)
                            </small>
                            <div class="form-error" id="imageError"></div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card-footer">
                        <div class="form-actions">
                            <a href="<?php echo BASE_URL; ?>/course/manage" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <div class="btn-group">
                                <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-redo"></i> Làm mới
                                </button>
                                <button type="submit" class="btn btn-primary" name="submit" id="submitBtn">
                                    <i class="fas fa-plus-circle"></i> Tạo khóa học
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        // Character counter
        function updateCharCount(inputId, counterId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            const length = input.value.length;

            counter.textContent = `${length}/${max}`;

            // Change color based on length
            if (length > max * 0.9) {
                counter.classList.add('char-danger');
                counter.classList.remove('char-warning');
            } else if (length > max * 0.8) {
                counter.classList.add('char-warning');
                counter.classList.remove('char-danger');
            } else {
                counter.classList.remove('char-warning', 'char-danger');
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
            document.querySelectorAll('.form-control, .form-textarea').forEach(el => {
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
            document.querySelectorAll('.form-control, .form-textarea').forEach(el => {
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
                const firstError = document.querySelector('.form-control.error, .form-textarea.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Real-time validation
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
        });
    </script>
</body>

</html>