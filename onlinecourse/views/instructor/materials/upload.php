<?php
// File: views/instructor/materials/upload.php
// Hiển thị Form Đăng tải và Danh sách Tài liệu hiện có.
// Dữ liệu cần: $lesson, $course, $materials (Cần đảm bảo Controller truyền đủ biến $materials)
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tài liệu - <?php echo htmlspecialchars($lesson['title'] ?? 'Bài học'); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Chung */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4f46e5;
            border-bottom: 2px solid #e0e7ff;
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Info & Form */
        .info-box {
            background: #eef2ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 5px 0;
            font-weight: 500;
            color: #1e293b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #1e293b;
        }

        .form-group input[type="text"],
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .btn-submit {
            background: #4f46e5;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
        }

        .btn-back {
            display: inline-block;
            color: #4f46e5;
            text-decoration: none;
            margin-bottom: 15px;
        }

        /* List Styles */
        .material-list {
            list-style: none;
            padding: 0;
        }

        .material-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .material-item:hover {
            background-color: #f8fafc;
        }

        .file-details {
            flex-grow: 1;
            margin-left: 15px;
        }

        .file-details h4 {
            margin: 0;
            font-size: 16px;
            color: #1e293b;
        }

        .file-details small {
            color: #64748b;
            font-size: 12px;
        }

        .actions a {
            margin-left: 10px;
            text-decoration: none;
            font-size: 14px;
        }

        .text-danger {
            color: #ef4444;
        }

        .text-success {
            color: #10b981;
        }

        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 30px;
            border: 1px dashed #e2e8f0;
            border-radius: 8px;
            margin-top: 20px;
        }

        /* Thông báo */
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
    </style>
</head>

<body>
    <div class="container">

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['message_type'] ?? 'info'; ?>">
                <?php echo $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>/lesson/manage/<?php echo $course['id'] ?? ''; ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại Quản lý Bài học
        </a>

        <h1><i class="fas fa-paperclip"></i> Quản lý Tài liệu Bài học</h1>

        <div class="info-box">
            <p><strong>Khóa học:</strong> <?php echo htmlspecialchars($course['title'] ?? 'N/A'); ?></p>
            <p><strong>Bài học:</strong> <?php echo htmlspecialchars($lesson['title'] ?? 'N/A'); ?></p>
        </div>

        <h2><i class="fas fa-upload"></i> Đăng tải Tài liệu mới</h2>

        <form action="<?php echo BASE_URL; ?>/lesson/uploadMaterial" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="lesson_id" value="<?php echo $lesson['id'] ?? ''; ?>">

            <div class="form-group">
                <label for="material_name">Tên Tài liệu</label>
                <input type="text" id="material_name" name="material_name" required
                    placeholder="Ví dụ: Slide thuyết trình buổi 1">
            </div>

            <div class="form-group">
                <label for="file_upload">Chọn Tệp (File)</label>
                <input type="file" id="file_upload" name="file_upload" required>
                <small class="file-format-hint">Hỗ trợ các định dạng: PDF, DOCX, ZIP, SQL, TXT, PNG/JPG, v.v. Kích thước
                    tối đa 10MB.</small>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-cloud-upload-alt"></i> Đăng tải Tài liệu
            </button>
        </form>

        <div class="divider"></div>

        <?php $materials = $materials ?? []; // Đảm bảo biến tồn tại ?>
        <h2><i class="fas fa-list-ul"></i> Tài liệu đã đăng tải (<?php echo count($materials); ?>)</h2>

        <?php if (!empty($materials)): ?>
            <ul class="material-list">
                <?php foreach ($materials as $material): ?>
                    <li class="material-item">
                        <div style="display: flex; align-items: center;">
                            <i class="fas fa-file-alt file-icon"></i>
                            <div class="file-details">
                                <h4><?php echo htmlspecialchars($material['filename']); ?></h4>
                                <small>Loại: <?php echo htmlspecialchars($material['file_type']); ?> | Tải lên:
                                    <?php echo date('d/m/Y H:i', strtotime($material['uploaded_at'])); ?></small>
                            </div>
                        </div>
                        <div class="actions">
                            <a href="<?php echo BASE_URL; ?>/<?php echo htmlspecialchars($material['file_path']); ?>"
                                target="_blank" class="text-success" title="Tải xuống">
                                <i class="fas fa-download"></i> Tải
                            </a>
                            <a href="<?php echo BASE_URL; ?>/lesson/deleteMaterial/<?php echo $material['id']; ?>"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');" class="text-danger"
                                title="Xóa tài liệu">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-info-circle"></i>
                <p>Bài học này hiện chưa có tài liệu nào.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>