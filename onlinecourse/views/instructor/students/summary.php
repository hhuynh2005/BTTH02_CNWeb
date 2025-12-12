<?php
// File: views/instructor/students/summary.php
// Hiển thị tổng quan TẤT CẢ học viên và tiến độ của họ trên TẤT CẢ các khóa học của Giảng viên.
// Dữ liệu cần: $allEnrollments (Tất cả đăng ký), $totalStudents (Tổng số học viên duy nhất)
// Giả định BASE_URL đã được định nghĩa
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan Học viên - Giảng viên</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Cơ bản - Sử dụng lại style từ các file trước */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 28px;
        }

        .stats-summary {
            background: #eef2ff;
            border-left: 5px solid #4f46e5;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stats-summary p {
            margin: 0;
            font-size: 16px;
            color: #1e293b;
            font-weight: 500;
        }

        /* Table Styles */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .summary-table th,
        .summary-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        .summary-table th {
            background-color: #f3f4f6;
            color: #1e293b;
            font-weight: 600;
            text-transform: uppercase;
        }

        .summary-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Progress Bar (Sử dụng lại từ list.php) */
        .progress-bar-container {
            height: 10px;
            background: #e2e8f0;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            height: 100%;
            background: #10b981;
            transition: width 0.5s ease;
        }

        /* Badges (Sử dụng lại từ list.php) */
        .badge {
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.75em;
            font-weight: 600;
            text-transform: capitalize;
        }

        .badge-success {
            background: #dcfce7;
            color: #047857;
        }

        .badge-warning {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-link {
            color: #4f46e5;
            text-decoration: none;
            transition: color 0.3s;
        }

        .action-link:hover {
            color: #3a0ca3;
        }

        /* No Data */
        .no-data {
            text-align: center;
            color: #6b7280;
            padding: 60px;
            border: 1px dashed #e2e8f0;
            border-radius: 8px;
            margin-top: 30px;
        }

        .no-data i {
            font-size: 3em;
            margin-bottom: 15px;
            color: #cbd5e1;
        }

        /* Cần nút Quay lại DashBoard */
        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #4f46e5;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">

        <a href="<?php echo BASE_URL; ?>/instructor/dashboard" class="btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại Dashboard
        </a>

        <h1><i class="fas fa-users"></i> Tổng quan Học viên</h1>

        <?php
        // Tính toán tổng số đăng ký và số học viên duy nhất (Nếu chưa làm trong Controller)
        $totalEnrollments = isset($allEnrollments) ? count($allEnrollments) : 0;
        $uniqueStudentIds = array_unique(array_column($allEnrollments, 'student_id'));
        $totalStudents = isset($totalStudents) ? $totalStudents : count($uniqueStudentIds);

        ?>
        <div class="stats-summary">
            <p>Tổng số đăng ký: **<?php echo $totalEnrollments; ?>**</p>
            <p>Tổng số học viên duy nhất: **<?php echo $totalStudents; ?>**</p>
        </div>


        <?php if (!empty($allEnrollments)): ?>
            <div style="overflow-x: auto;">
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>ID HV</th>
                            <th>Họ và Tên</th>
                            <th>Khóa học</th>
                            <th>Ngày đăng ký</th>
                            <th style="width: 150px;">Tiến độ</th>
                            <th>Trạng thái</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allEnrollments as $item): ?>
                            <tr>
                                <td><?php echo $item['student_id']; ?></td>
                                <td><?php echo htmlspecialchars($item['student_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/course/detail/<?php echo $item['course_id']; ?>"
                                        class="action-link" target="_blank">
                                        <?php echo htmlspecialchars($item['course_title'] ?? 'N/A'); ?>
                                    </a>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($item['enrolled_date'] ?? 'N/A')); ?></td>
                                <td>
                                    <?php $progress = $item['progress'] ?? 0; ?>
                                    <div class="progress-bar-container">
                                        <div class="progress-fill" style="width: <?php echo $progress; ?>%;"></div>
                                    </div>
                                    <small>**<?php echo $progress; ?>%**</small>
                                </td>
                                <td>
                                    <?php
                                    $status = $item['enrollment_status'] ?? 'active';
                                    $status_display = match ($status) {
                                        'completed' => 'Hoàn thành',
                                        'dropped' => 'Hủy khóa học',
                                        default => 'Đang học'
                                    };
                                    $badge_class = match ($status) {
                                        'completed' => 'badge-success',
                                        'dropped' => 'badge-danger',
                                        default => 'badge-warning'
                                    };
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars($status_display); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>/enrollment/progress/<?php echo $item['course_id']; ?>/<?php echo $item['student_id']; ?>"
                                        title="Xem chi tiết tiến độ" class="action-link action-btn">
                                        <i class="fas fa-chart-line"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-data">
                <i class="fas fa-user-slash"></i>
                <h3>Chưa có học viên nào</h3>
                <p>Hiện tại chưa có học viên nào đăng ký vào bất kỳ khóa học nào do bạn tạo.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>