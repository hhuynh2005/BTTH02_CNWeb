<?php include 'views/layouts/header.php'; ?>

<style>
    /* Định dạng chung cho link lọc */
    .filter-link {
        text-decoration: none;
        margin-right: 15px;
        transition: 0.3s;
    }
    
    /* Màu mặc định (khi chưa chọn và cả khi đã visited) */
    .filter-link {
        color: #333; /* Màu đen xám, bạn có thể đổi thành #007bff nếu thích màu xanh */
    }

    /* Khi di chuột vào */
    .filter-link:hover {
        color: #007bff; /* Đổi màu khi hover */
        text-decoration: underline;
    }

    /* Khi đang được chọn (Active) */
    .filter-link.active {
        color: red !important; /* Màu đỏ nổi bật */
        font-weight: bold;
    }
</style>

<div class="main-container" style="display: flex; gap: 20px; padding: 20px;">
    
    <div class="sidebar-column" style="width: 250px; flex-shrink: 0;">
        <?php include 'views/layouts/sidebar.php'; ?>
    </div>

    <div class="content-column" style="flex-grow: 1;">
        
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Danh sách khóa học</h2>
            
            <form action="index.php" method="GET" style="display: flex; gap: 10px;">
                <input type="hidden" name="url" value="course/search">
                <input type="text" name="keyword" placeholder="Tìm kiếm khóa học..." required style="padding: 5px;">
                <button type="submit">Tìm</button>
            </form>
        </div>

        <div class="category-filter" style="margin-bottom: 20px;">
            <strong>Lọc theo: </strong>
            
            <?php 
                $activeAll = !isset($_GET['category_id']) ? 'active' : '';
            ?>
            <a href="index.php?url=course/index" class="filter-link <?php echo $activeAll; ?>">
                Tất cả
            </a>

            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <?php 
                        // Kiểm tra xem danh mục này có đang được chọn không
                        $isActive = (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'active' : '';
                    ?>
                    <a href="index.php?url=course/index&category_id=<?php echo $cat['id']; ?>" 
                       class="filter-link <?php echo $isActive; ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="course-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-item" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                        <img src="assets/uploads/courses/<?php echo !empty($course['image']) ? $course['image'] : 'default.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>" 
                             style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                        
                        <h3 style="margin: 10px 0; font-size: 18px;"><?php echo htmlspecialchars($course['title']); ?></h3>
                        
                        <p style="color: #666; font-size: 14px;">
                            <?php echo substr(htmlspecialchars(strip_tags($course['description'])), 0, 80) . '...'; ?>
                        </p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                            <span style="color: #d9534f; font-weight: bold;">
                                <?php echo number_format($course['price']); ?> VNĐ
                            </span>
                            <a href="index.php?url=course/detail/<?php echo $course['id']; ?>" class="btn-detail" style="background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px;">Xem chi tiết</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không tìm thấy khóa học nào phù hợp.</p>
            <?php endif; ?>
        </div>
        
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>