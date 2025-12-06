<?php include 'views/layouts/header.php'; ?>

<div class="main-container" style="display: flex; gap: 20px; padding: 20px;">
    
    <div class="sidebar-column" style="width: 250px; flex-shrink: 0;">
        <?php include 'views/layouts/sidebar.php'; ?>
    </div>

    <div class="content-column" style="flex-grow: 1;">
        <main>
            <h1>Khóa học mới nhất</h1>
            
            <div class="course-list">
                <?php if (!empty($newestCourses)): ?>
                    <?php foreach ($newestCourses as $course): ?>
                        
                        <div class="course-item">
                            <img src="assets/uploads/courses/<?php echo !empty($course['image']) ? htmlspecialchars($course['image']) : 'default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($course['title']); ?>"
                                 style="max-width: 100%; height: auto;">
                            
                            <h3><?php echo htmlspecialchars($course['title']); ?></h3>
                            
                            <p>Giá: <?php echo number_format($course['price']); ?> VNĐ</p>
                            
                            <a href="index.php?url=course/detail/<?php echo $course['id']; ?>" class="btn-detail">Xem chi tiết</a>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Chưa có khóa học nào được đăng tải.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

</div> <?php include 'views/layouts/footer.php'; ?>