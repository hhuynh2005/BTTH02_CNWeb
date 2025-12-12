<?php
// File: views/instructor/progress/overview.php
// Hiển thị tổng quan tiến độ của TẤT CẢ các khóa học do Giảng viên sở hữu.
// Dữ liệu cần: $totalStudents, $enrollmentCount, $courseSummary, $pendingCourses, $avgCompletionRate
// Giả định BASE_URL đã được định nghĩa
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo dõi Tiến độ Khóa học - Giảng viên</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Chung */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7f9;
        }

        .container {
            max-width: 1400px;
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

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #f8faff;
            padding: 25px;
            border-radius: 10px;
            border-left: 5px solid #4f46e5;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .stat-card h4 {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .stat-card p {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        /* Table */
        .progress-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .progress-table th,
        .progress-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .progress-table th {
            background-color: #f3f4f6;
            color: #1e293b;
            font-weight: 600;
        }

        .progress-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Progress Bar */
        .progress-bar-container {
            height: 10px;
            background: #e2e8f0;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-fill-avg {
            height: 100%;
            background: #059669;
            transition: width 0.5s ease;
        }

        /* Badges */
        .badge-success {
            background: #dcfce7;
            color: #047857;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
        }

        .action-btn-small {
            color: #4f46e5;
            text-decoration: none;
            padding: 5px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .action-btn-small:hover {
            background: #eef2ff;
        }

        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 60px;
            border: 1px dashed #e2e8f0;
            border-radius: 8px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">

        <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="action-btn-small" style="display: inline-block;">
            <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>

        <h1><i class="fas fa-chart-line"></i> Theo dõi Tiến độ Khóa học</h1>
        <p>Tổng quan về hiệu suất, tỷ lệ hoàn thành và mức độ tương tác của các khóa học bạn đang giảng dạy.</p>

        <?php
        // Khởi tạo các giá trị (Đảm bảo các biến được định nghĩa)
        $totalStudents = $totalStudents ?? 0;
        $enrollmentCount = $enrollmentCount ?? 0;
        $avgCompletionRate = $avgCompletionRate ?? 0;
        $pendingCourses = $pendingCourses ?? 0;
        $courseSummary = $courseSummary ?? [];
        ?>

        <div class="stats-grid">
            <div class="stat-card">
                <h4>Tổng số Học viên</h4>
                <p><?php echo number_format($totalStudents); ?></p>
            </div>
            <div class="stat-card">
                <h4>Tổng lượt Đăng ký</h4>
                <p><?php echo number_format($enrollmentCount); ?></p>
            </div>
            <div class="stat-card">
                <h4>Tỷ lệ Hoàn thành TB</h4>
                <p><?php echo $avgCompletionRate; ?>%</p>
                <div class="progress-bar-container">
                    <div class="progress-fill-avg" style="width: <?php echo $avgCompletionRate; ?>%;"></div>
                </div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <h4>Khóa học Chờ duyệt</h4>
                <p><?php echo $pendingCourses; ?></p>
            </div>
        </div>

        <h3><i class="fas fa-list-alt"></i> Hiệu suất theo từng Khóa học</h3>

        <?php if (!empty($courseSummary)): ?>
            <div style="overflow-x: auto;">
                <table class="progress-table">
                    <thead>
                        <tr>
                            <th>Khóa học</th>
                            <th>Tổng HV</th>
                            <th>Tỷ lệ Hoàn thành</th>
                            <th style="width: 250px;">Phân tích</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courseSummary as $course): ?>
                            <?php
                            // 1. KHẮC PHỤC LỖI CHIA CHO 0: Kiểm tra total_students
                            $totalStudentsCourse = $course['total_students'] ?? 0;
                            $completedCount = $course['completed_count'] ?? 0;

                            $completionRate = ($totalStudentsCourse > 0)
                                ? round(($completedCount / $totalStudentsCourse) * 100)
                                : 0;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['title']); ?></td>
                                <td><?php echo number_format($totalStudentsCourse); ?></td>
                                <td>
                                    <span class="badge-success"><?php echo $completionRate; ?>%</span>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill-avg" style="width: <?php echo $completionRate; ?>%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/enrollment/listStudents/<?php echo $course['id']; ?>"
                                        class="action-btn-small" title="Xem danh sách học viên khóa học này">
                                        <i class="fas fa-users"></i> Học viên
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-book-open"></i>
                <h3>Hãy bắt đầu giảng dạy!</h3>
                <p>Bạn chưa có khóa học nào được phê duyệt hoặc chưa có học viên nào đăng ký.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>