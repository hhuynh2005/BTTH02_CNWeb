<?php include 'views/layouts/header.php'; ?>

<div class="main-container" style="display: flex; gap: 20px; padding: 20px;">
    
    <div class="course-content" style="flex: 2;">
        
        <div class="back-nav" style="margin-bottom: 15px;">
            <a href="index.php?url=course/index" style="text-decoration: none; color: #555; font-weight: 500;">
                ← Quay lại danh sách khóa học
            </a>
        </div>

        <div class="course-banner">
            <img src="assets/uploads/courses/<?php echo !empty($course['image']) ? $course['image'] : 'default.jpg'; ?>" 
                 alt="<?php echo htmlspecialchars($course['title']); ?>"
                 style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px;">
        </div>

        <h1 style="margin-top: 20px;"><?php echo htmlspecialchars($course['title']); ?></h1>
        
        <div class="course-meta" style="color: #666; margin-bottom: 20px;">
            <span><i class="icon-level"></i> Trình độ: <?php echo htmlspecialchars($course['level']); ?></span> | 
            <span><i class="icon-time"></i> Thời lượng: <?php echo $course['duration_weeks']; ?> tuần</span>
        </div>

        <div class="course-description" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px;">
            <h3>Mô tả khóa học</h3>
            <hr>
            <div>
                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
            </div>
        </div>

        <div class="course-curriculum" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <h3>Nội dung khóa học</h3>
            <hr>
            <?php if (!empty($lessons)): ?>
                <ul style="list-style: none; padding: 0;">
                    <?php foreach ($lessons as $lesson): ?>
                        <li style="padding: 10px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between;">
                            <span>
                                <i class="icon-play-circle"></i> 
                                Bài <?php echo $lesson['order_num']; ?>: <?php echo htmlspecialchars($lesson['title']); ?>
                            </span>
                            <span style="color: #888; font-size: 0.9em;"><i class="icon-lock"></i> Đăng ký để học</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nội dung đang được cập nhật...</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="course-sidebar" style="flex: 1; min-width: 300px;">
        <div class="card-buy" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; position: sticky; top: 20px;">
            
            <h2 style="color: #d9534f; text-align: center;"><?php echo number_format($course['price']); ?> VNĐ</h2>
            
            <div class="action-btn" style="text-align: center; margin-top: 20px;">
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="index.php?url=enrollment/register" method="POST">
                        <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                        <button type="submit" name="register_course" 
                                style="width: 100%; padding: 15px; background: #28a745; color: white; border: none; font-size: 18px; cursor: pointer; border-radius: 5px;"
                                onclick="return confirm('Bạn có chắc chắn muốn đăng ký khóa học này?');">
                            ĐĂNG KÝ HỌC NGAY
                        </button>
                    </form>
                <?php else: ?>
                    <a href="index.php?url=auth/login" 
                       style="display: block; width: 100%; padding: 15px; background: #007bff; color: white; text-align: center; text-decoration: none; border-radius: 5px;">
                        Đăng nhập để đăng ký
                    </a>
                <?php endif; ?>
            </div>

            <hr>
            <div class="instructor-info">
                <h4>Giảng viên</h4>
                <p><strong><?php echo isset($course['instructor_name']) ? $course['instructor_name'] : 'Đại học Thủy Lợi'; ?></strong></p>
            </div>
        </div>
    </div>

</div>

<?php include 'views/layouts/footer.php'; ?>