<?php
// File: views/instructor/students/progress_detail.php
// Hiển thị chi tiết tiến độ học tập của một học viên trong một khóa học cụ thể.
// Dữ liệu cần: $course, $studentInfo, $lessonsProgress
// Giả định BASE_URL đã được định nghĩa
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiến độ chi tiết - <?php echo htmlspecialchars($studentInfo['fullname'] ?? 'Học viên'); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
        }

        .container {
            max-width: 1000px;
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

        .info-card {
            background: #eef2ff;
            border-left: 5px solid #4f46e5;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .info-card h2 {
            font-size: 18px;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 10px;
        }

        .info-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #64748b;
        }

        .progress-list {
            list-style: none;
            padding: 0;
        }

        .progress-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            transition: background 0.3s;
        }

        .progress-item:hover {
            background: #f8fafc;
        }

        .lesson-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 16px;
        }

        .lesson-meta {
            font-size: 12px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-completed {
            background: #dcfce7;
            color: #047857;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-score {
            background: #dbeafe;
            color: #1d4ed8;
            margin-left: 10px;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #4f46e5;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .info-card {
                flex-direction: column;
                gap: 20px;
            }

            .progress-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <a href="<?php echo BASE_URL; ?>/instructor/enrollments" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại Tổng quan Học viên
        </a>

        <h1>
            <i class="fas fa-chart-line"></i>
            Tiến độ Khóa học: <?php echo htmlspecialchars($course['title'] ?? 'N/A'); ?>
        </h1>

        <div class="info-card">
            <div class="info-group">
                <h2><i class="fas fa-user-graduate"></i> Thông tin Học viên</h2>
                <p><strong>Tên:</strong> <?php echo htmlspecialchars($studentInfo['fullname'] ?? 'N/A'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($studentInfo['email'] ?? 'N/A'); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($studentInfo['id'] ?? 'N/A'); ?></p>
            </div>
            <div class="info-group">
                <h2><i class="fas fa-book"></i> Tổng quan Khóa học</h2>
                <p><strong>Giảng viên:</strong> <?php echo htmlspecialchars($_SESSION['fullname'] ?? 'Bạn'); ?></p>
                <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($course['status'] ?? 'N/A'); ?></p>
                <p><strong>Tổng bài học:</strong> <?php echo count($lessonsProgress); ?></p>
            </div>
        </div>

        <h3>Danh sách Bài học & Tình trạng Hoàn thành</h3>

        <ul class="progress-list">
            <?php if (!empty($lessonsProgress)): ?>
                <?php $completedCount = 0; ?>
                <?php foreach ($lessonsProgress as $lesson): ?>
                    <li class="progress-item">
                        <div>
                            <div class="lesson-title">
                                Bài <?php echo $lesson['lesson_order'] ?? 'N/A'; ?>:
                                <?php echo htmlspecialchars($lesson['lesson_title']); ?>
                                (<?php echo htmlspecialchars($lesson['content_type'] ?? 'Video'); ?>)
                            </div>
                            <div class="lesson-meta">
                                <?php if ($lesson['is_completed']): ?>
                                    Hoàn thành vào: <?php echo date('d/m/Y H:i', strtotime($lesson['completion_date'])); ?>
                                    <?php $completedCount++; ?>
                                <?php else: ?>
                                    Chưa hoàn thành.
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center;">
                            <?php if ($lesson['is_completed']): ?>
                                <span class="status-badge status-completed">
                                    <i class="fas fa-check-circle"></i> Đã Hoàn thành
                                </span>
                                <?php if (isset($lesson['score']) && $lesson['score'] !== null): ?>
                                    <span class="status-badge status-score">
                                        Điểm: <?php echo $lesson['score']; ?>
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> Chưa Hoàn thành
                                </span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>

                <div style="margin-top: 20px; padding: 15px; background: #eef2ff; border-radius: 8px; font-weight: 600;">
                    Tỷ lệ hoàn thành: **<?php echo $completedCount; ?> / <?php echo count($lessonsProgress); ?> bài**
                </div>

            <?php else: ?>
                <div class="no-data" style="padding: 20px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Khóa học này chưa có bài học nào được thêm.</p>
                </div>
            <?php endif; ?>
        </ul>
    </div>
</body>

</html>