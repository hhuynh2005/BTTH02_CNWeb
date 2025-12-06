<?php include 'views/layouts/header.php'; ?>

<div class="main-container" style="display: flex; gap: 20px; padding: 20px;">
    
    <div class="sidebar-column" style="width: 250px; flex-shrink: 0;">
        <?php include 'views/layouts/sidebar.php'; ?>
    </div>

    <div class="content-column" style="flex-grow: 1;">
        <div class="page-header" style="margin-bottom: 20px;">
            <h2>Theo dõi tiến độ học tập</h2>
            <p style="color: #666;">Thống kê chi tiết trạng thái các khóa học của bạn.</p>
        </div>

        <?php if (!empty($myCourses)): ?>
            
            <div class="stats-row" style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div class="stat-card" style="flex: 1; background: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 5px solid #2196f3;">
                    <h3 style="margin: 0; font-size: 24px; color: #2196f3;"><?php echo count($myCourses); ?></h3>
                    <p style="margin: 0; color: #555;">Tổng khóa học</p>
                </div>
                <div class="stat-card" style="flex: 1; background: #e8f5e9; padding: 15px; border-radius: 8px; border-left: 5px solid #4caf50;">
                    <?php 
                        $completed = array_filter($myCourses, function($c) { return $c['progress'] == 100; });
                    ?>
                    <h3 style="margin: 0; font-size: 24px; color: #4caf50;"><?php echo count($completed); ?></h3>
                    <p style="margin: 0; color: #555;">Đã hoàn thành</p>
                </div>
            </div>

            <div class="progress-list">
                <?php foreach ($myCourses as $course): ?>
                    <div class="progress-item" style="display: flex; align-items: center; gap: 20px; padding: 15px; border: 1px solid #eee; margin-bottom: 15px; border-radius: 8px; background: #fff;">
                        
                        <img src="assets/uploads/courses/<?php echo !empty($course['image']) ? $course['image'] : 'default.jpg'; ?>" 
                             style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                        
                        <div class="info" style="flex: 1;">
                            <h4 style="margin: 0 0 5px;">
                                <a href="index.php?url=course/detail/<?php echo $course['course_id'] ?? $course['id']; ?>"
                                style="text-decoration: none; color: #2196f3; font-weight: bold;">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </a>
                            </h4>
                            <span style="font-size: 12px; color: #888;">
                                Ngày đăng ký: <?php echo date('d/m/Y', strtotime($course['enrolled_date'])); ?>
                            </span>
                        </div>

                        <div class="status" style="width: 120px; text-align: center;">
                            <?php if ($course['progress'] == 100): ?>
                                <span style="padding: 5px 10px; background: #e8f5e9; color: #2e7d32; border-radius: 15px; font-size: 12px; font-weight: bold;">Hoàn thành</span>
                            <?php else: ?>
                                <span style="padding: 5px 10px; background: #fff3e0; color: #ef6c00; border-radius: 15px; font-size: 12px; font-weight: bold;">Đang học</span>
                            <?php endif; ?>
                        </div>

                        <div class="progress-bar-container" style="width: 150px;">
                            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 3px;">
                                <span><?php echo $course['progress']; ?>%</span>
                            </div>
                            <div style="background: #eee; height: 10px; border-radius: 5px; overflow: hidden;">
                                <div style="background: <?php echo ($course['progress'] == 100) ? '#4caf50' : '#2196f3'; ?>; height: 100%; width: <?php echo $course['progress']; ?>%;"></div>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p>Chưa có dữ liệu tiến độ.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>