<?php include 'views/layouts/header.php'; ?>

<style>
    /* CSS riêng cho nút Vào học để tránh lỗi khi viết inline */
    .btn-learning {
        display: block;
        text-align: center;
        background: #007bff;
        color: white;
        text-decoration: none;
        padding: 10px;
        border-radius: 4px;
        font-weight: 500;
        transition: background 0.2s;
    }
    .btn-learning:hover {
        background: #0056b3;
        color: white;
    }
</style>

<div class="main-container" style="display: flex; gap: 20px; padding: 20px;">
    
    <div class="sidebar-column" style="width: 250px; flex-shrink: 0;">
        <?php include 'views/layouts/sidebar.php'; ?>
    </div>

    <div class="content-column" style="flex-grow: 1;">
        <div class="page-header" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            <h2>Khóa học của tôi</h2>
        </div>

        <?php if (!empty($myCourses)): ?>
            <div class="course-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <?php foreach ($myCourses as $course): ?>
                    <div class="course-card" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        
                        <div class="card-img" style="height: 160px; overflow: hidden;">
                            <img src="assets/uploads/courses/<?php echo !empty($course['image']) ? $course['image'] : 'default.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($course['title']); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <div class="card-body" style="padding: 15px;">
                            <h3 style="font-size: 16px; margin: 0 0 10px; height: 40px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </h3>
                            
                            <p style="font-size: 13px; color: #666; margin-bottom: 15px;">
                                <i class="icon-user"></i> GV: <?php echo htmlspecialchars($course['instructor_name']); ?>
                            </p>

                            <div class="progress-wrapper" style="margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px;">
                                    <span>Tiến độ</span>
                                    <strong><?php echo $course['progress']; ?>%</strong>
                                </div>
                                <div style="background: #f0f0f0; height: 8px; border-radius: 4px; overflow: hidden;">
                                    <div style="background: #28a745; height: 100%; width: <?php echo $course['progress']; ?>%;"></div>
                                </div>
                            </div>

                            <a href="index.php?url=course/detail/<?php echo $course['course_id']; ?>" class="btn-learning">
                               Tiếp tục học
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state" style="text-align: center; padding: 40px; background: #f9f9f9; border-radius: 8px;">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty" style="width: 80px; opacity: 0.5; margin-bottom: 20px;">
                <p>Bạn chưa đăng ký khóa học nào.</p>
                <a href="index.php?url=course/index" style="color: #007bff; font-weight: bold;">Khám phá khóa học ngay</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>