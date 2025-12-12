<?php
// File: views/courses/search.php (Kết quả Tìm kiếm Khóa học công khai)
// Dữ liệu cần thiết: $searchResults, $searchQuery
// =========================================================================
$BASE_URL = $BASE_URL ?? '/base';
$searchQuery = $_GET['q'] ?? 'web development';
$searchResults = [
    ['id' => 201, 'title' => 'HTML/CSS Cơ bản', 'instructor' => 'Giảng viên Z', 'price' => 299000],
    ['id' => 101, 'title' => 'Lập Trình Web PHP từ A-Z', 'instructor' => 'Nguyễn Văn A', 'price' => 499000],
];
// =========================================================================
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f9fafb;
        }

        .search-result-item {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        .result-title {
            font-size: 1.25rem;
            color: #4f46e5;
        }

        .result-meta {
            font-size: 0.9rem;
            color: #64748b;
        }

        .price-tag {
            font-weight: 700;
            color: #10b981;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h1 class="mb-4">
            <i class="fas fa-search me-2"></i>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($searchQuery); ?>"
        </h1>
        <p class="mb-4 text-muted">Tìm thấy <?php echo count($searchResults); ?> khóa học phù hợp.</p>

        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $result): ?>
                <div class="search-result-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="result-title">
                            <a href="<?php echo BASE_URL; ?>/courses/detail?id=<?php echo $result['id']; ?>"
                                class="text-decoration-none">
                                <?php echo htmlspecialchars($result['title']); ?>
                            </a>
                        </h5>
                        <div class="result-meta">
                            <i class="fas fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($result['instructor']); ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="price-tag mb-2"><?php echo number_format($result['price'], 0, ',', '.'); ?>đ</div>
                        <a href="<?php echo BASE_URL; ?>/courses/detail?id=<?php echo $result['id']; ?>"
                            class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="p-5 text-center text-muted bg-white rounded-3 shadow-sm">
                <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                <p>Không tìm thấy khóa học nào khớp với từ khóa của bạn.</p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>