<?php include 'views/layouts/header.php'; ?>

<div class="main-container" style="display: flex; gap: 20px; padding: 20px;">
    
    <div class="sidebar-column" style="width: 250px; flex-shrink: 0;">
        <?php if (isset($_SESSION['user'])) include 'views/layouts/sidebar.php'; ?>
    </div>

    <div class="content-column" style="flex-grow: 1;">
        
        <div class="search-header" style="margin-bottom: 20px;">
            <h2>Kết quả tìm kiếm</h2>
            <p>Từ khóa: "<strong><?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?></strong>"</p>
            <a href="index.php?url=course/index">← Quay lại danh sách tất cả</a>
        </div>

        <div class="course-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-item" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                        <img src="assets/uploads/courses/<?php echo !empty($course['image']) ? $course['image'] : 'default.jpg'; ?>" 
                             style="width: 100%; height: 150px; object-fit: cover;">
                        
                        <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p style="color: red; font-weight: bold;"><?php echo number_format($course['price']); ?> VNĐ</p>
                        
                        <a href="index.php?url=course/detail/<?php echo $course['id']; ?>" class="btn-detail">Xem chi tiết</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    Không tìm thấy khóa học nào khớp với từ khóa của bạn.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>