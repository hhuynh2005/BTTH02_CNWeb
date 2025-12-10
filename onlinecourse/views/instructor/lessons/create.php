<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo bài học mới - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container navbar-container">
            <div class="navbar-brand">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                    <rect width="40" height="40" rx="8" fill="#4f46e5" />
                    <path
                        d="M20 12.5L12.5 16.25V23.75C12.5 27.5 15.625 30.875 20 31.875C24.375 30.875 27.5 27.5 27.5 23.75V16.25L20 12.5Z"
                        fill="white" />
                </svg>
                <span>Tạo bài học mới</span>
            </div>
            <div class="navbar-actions">
                <a href="<?php echo BASE_URL; ?>/instructor/lessons/<?php echo $course['id']; ?>"
                    class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </div>
    </nav>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-menu">
                <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="sidebar-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/courses" class="sidebar-item">
                    <i class="fas fa-book"></i> Khóa học
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/lessons/<?php echo $course['id']; ?>"
                    class="sidebar-item active">
                    <i class="fas fa-list-ul"></i> Bài học
                </a>
                <a href="<?php echo BASE_URL; ?>/instructor/createCourse" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i> Tạo khóa học
                </a>
            </div>

            <div class="sidebar-course-info">
                <h4>Khóa học hiện tại</h4>
                <div class="course-summary">
                    <h5><?php echo htmlspecialchars($course['title']); ?></h5>
                    <p><strong>Level:</strong> <?php echo htmlspecialchars($course['level']); ?></p>
                    <p><strong>Trạng thái:</strong>
                        <span
                            class="badge <?php echo $course['status'] == 'approved' ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo $course['status'] == 'approved' ? 'Đã duyệt' : 'Chờ duyệt'; ?>
                        </span>
                    </p>
                    <p><strong>Số bài học:</strong> <?php echo $total_lessons ?? 0; ?></p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <div class="content-header">
                <div>
                    <h1>Tạo bài học mới</h1>
                    <p>Khóa học: <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
                </div>
                <a href="<?php echo BASE_URL; ?>/instructor/lessons/<?php echo $course['id']; ?>"
                    class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?php echo BASE_URL; ?>/course/storeLesson/<?php echo $course['id']; ?>" method="POST"
                id="lessonForm">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-info-circle"></i> Thông tin bài học</h3>
                    </div>
                    <div class="card-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="required">Tiêu đề bài học</label>
                            <input type="text" id="title" name="title" class="form-control"
                                placeholder="Nhập tiêu đề bài học" required maxlength="255"
                                oninput="updateCharCount('title', 'titleCounter', 255)">
                            <div class="form-help">
                                <span id="titleCounter">0/255</span> ký tự
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="form-group">
                            <label for="content" class="required">Nội dung bài học</label>
                            <textarea id="content" name="content" class="form-textarea" rows="10"
                                placeholder="Nhập nội dung chi tiết của bài học..." required
                                oninput="updateCharCount('content', 'contentCounter')"></textarea>
                            <div class="form-help">
                                <span id="contentCounter">0</span> ký tự
                            </div>
                        </div>

                        <div class="form-row">
                            <!-- Video URL -->
                            <div class="form-group">
                                <label for="video_url">Video URL (YouTube, Vimeo, ...)</label>
                                <input type="url" id="video_url" name="video_url" class="form-control"
                                    placeholder="https://www.youtube.com/watch?v=..." onblur="validateVideoUrl(this)">
                                <div class="form-help">
                                    Hỗ trợ: YouTube, Vimeo, MP4 links
                                </div>
                                <div class="video-preview" id="videoPreview" style="display: none;">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-play-circle"></i>
                                        <p>Video preview sẽ hiển thị ở đây</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Number -->
                            <div class="form-group">
                                <label for="order_num" class="required">Thứ tự bài học</label>
                                <input type="number" id="order_num" name="order_num" class="form-control" min="1"
                                    value="<?php echo ($total_lessons ?? 0) + 1; ?>" required>
                                <div class="form-help">
                                    Số thứ tự trong danh sách bài học
                                </div>
                            </div>
                        </div>

                        <!-- Estimated Duration -->
                        <div class="form-group">
                            <label for="estimated_duration">Thời lượng ước tính (phút)</label>
                            <div class="input-group">
                                <input type="number" id="estimated_duration" name="estimated_duration"
                                    class="form-control" min="1" max="240" value="30">
                                <span class="input-group-text">phút</span>
                            </div>
                            <div class="form-help">
                                Thời gian trung bình để hoàn thành bài học
                            </div>
                        </div>

                        <!-- Lesson Type -->
                        <div class="form-group">
                            <label>Loại bài học</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="lesson_type" value="theory" checked>
                                    <span class="radio-custom"></span>
                                    <span class="radio-text">
                                        <i class="fas fa-book"></i> Lý thuyết
                                    </span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="lesson_type" value="practice">
                                    <span class="radio-custom"></span>
                                    <span class="radio-text">
                                        <i class="fas fa-laptop-code"></i> Thực hành
                                    </span>
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="lesson_type" value="quiz">
                                    <span class="radio-custom"></span>
                                    <span class="radio-text">
                                        <i class="fas fa-question-circle"></i> Kiểm tra
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="form-actions">
                            <a href="<?php echo BASE_URL; ?>/instructor/lessons/<?php echo $course['id']; ?>"
                                class="btn btn-outline">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo bài học
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Quick Tips -->
            <div class="card mt-4">
                <div class="card-header">
                    <h3><i class="fas fa-lightbulb"></i> Mẹo tạo bài học hiệu quả</h3>
                </div>
                <div class="card-body">
                    <div class="tips-grid">
                        <div class="tip">
                            <div class="tip-icon">
                                <i class="fas fa-heading"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Tiêu đề rõ ràng</h4>
                                <p>Tiêu đề ngắn gọn, dễ hiểu, thể hiện nội dung chính</p>
                            </div>
                        </div>
                        <div class="tip">
                            <div class="tip-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Thêm video</h4>
                                <p>Video giảng dạy giúp học viên dễ hiểu hơn</p>
                            </div>
                        </div>
                        <div class="tip">
                            <div class="tip-icon">
                                <i class="fas fa-ruler"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Chia nhỏ nội dung</h4>
                                <p>Mỗi bài học nên tập trung vào 1 chủ đề nhỏ</p>
                            </div>
                        </div>
                        <div class="tip">
                            <div class="tip-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="tip-content">
                                <h4>Bài tập thực hành</h4>
                                <p>Thêm bài tập giúp học viên áp dụng kiến thức</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Character counter
        function updateCharCount(inputId, counterId, max = null) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            const length = input.value.length;

            if (max) {
                counter.textContent = `${length}/${max}`;
                if (length > max * 0.8) {
                    counter.style.color = '#f59e0b';
                } else {
                    counter.style.color = '';
                }
                if (length > max) {
                    input.value = input.value.substring(0, max);
                }
            } else {
                counter.textContent = `${length} ký tự`;
            }
        }

        // Video URL validation
        function validateVideoUrl(input) {
            const url = input.value.trim();
            if (!url) {
                document.getElementById('videoPreview').style.display = 'none';
                return;
            }

            const preview = document.getElementById('videoPreview');

            // YouTube
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                let videoId = '';
                if (url.includes('youtube.com/watch?v=')) {
                    videoId = url.split('v=')[1].split('&')[0];
                } else if (url.includes('youtu.be/')) {
                    videoId = url.split('youtu.be/')[1].split('?')[0];
                }

                if (videoId) {
                    preview.innerHTML = `
                        <div class="embed-responsive">
                            <iframe src="https://www.youtube.com/embed/${videoId}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen></iframe>
                        </div>
                    `;
                    preview.style.display = 'block';
                }
            }
            // Vimeo
            else if (url.includes('vimeo.com')) {
                const videoId = url.split('vimeo.com/')[1].split('?')[0];
                if (videoId) {
                    preview.innerHTML = `
                        <div class="embed-responsive">
                            <iframe src="https://player.vimeo.com/video/${videoId}" 
                                    frameborder="0" 
                                    allow="autoplay; fullscreen; picture-in-picture" 
                                    allowfullscreen></iframe>
                        </div>
                    `;
                    preview.style.display = 'block';
                }
            }
        }

        // Form validation
        document.getElementById('lessonForm').addEventListener('submit', function (e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            const orderNum = document.getElementById('order_num').value;

            let isValid = true;
            let errorMessage = '';

            if (!title) {
                isValid = false;
                errorMessage += 'Tiêu đề bài học không được để trống\n';
            }

            if (!content) {
                isValid = false;
                errorMessage += 'Nội dung bài học không được để trống\n';
            }

            if (!orderNum || orderNum < 1) {
                isValid = false;
                errorMessage += 'Thứ tự bài học phải là số dương\n';
            }

            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng sửa các lỗi sau:\n' + errorMessage);
            }
        });

        // Initialize counters
        document.addEventListener('DOMContentLoaded', function () {
            updateCharCount('title', 'titleCounter', 255);
            updateCharCount('content', 'contentCounter');
        });
    </script>

    <style>
        .required::after {
            content: " *";
            color: #ef4444;
        }

        .form-help {
            margin-top: 0.25rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 200px;
            gap: 1.5rem;
        }

        .input-group {
            display: flex;
        }

        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group-text {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-left: none;
            padding: 0.5rem 1rem;
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
            color: #6b7280;
        }

        .radio-group {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .radio-label input[type="radio"] {
            display: none;
        }

        .radio-custom {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .radio-label input[type="radio"]:checked+.radio-custom {
            border-color: #4f46e5;
        }

        .radio-label input[type="radio"]:checked+.radio-custom::after {
            content: '';
            position: absolute;
            top: 4px;
            left: 4px;
            width: 8px;
            height: 8px;
            background: #4f46e5;
            border-radius: 50%;
        }

        .radio-text {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .video-preview {
            margin-top: 1rem;
        }

        .preview-placeholder {
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 3rem;
            text-align: center;
            color: #6b7280;
        }

        .preview-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #9ca3af;
        }

        .embed-responsive {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .embed-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .sidebar-course-info {
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            margin-top: auto;
        }

        .sidebar-course-info h4 {
            font-size: 0.875rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
        }

        .course-summary {
            background: #f9fafb;
            border-radius: 0.5rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
        }

        .course-summary h5 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #111827;
        }

        .course-summary p {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .tips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .tip {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .tip-icon {
            width: 48px;
            height: 48px;
            background: #e0e7ff;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .tip-content h4 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
            color: #111827;
        }

        .tip-content p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .radio-group {
                flex-direction: column;
                gap: 0.75rem;
            }

            .tips-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</body>

</html>