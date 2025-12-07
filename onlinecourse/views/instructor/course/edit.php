<?php
$course = $data['course'] ?? null;
$categories = $data['categories'] ?? [];
$error = $data['error'] ?? null;

if (!$course) {
    echo "<p style='color: red;'>Không tìm thấy khóa học.</p>";
    exit;
}
?>
<h2>Chỉnh Sửa Khóa Học: <?php echo htmlspecialchars($course['title']); ?></h2>

<?php if ($error): ?>
    <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="/instructor/course/edit/<?php echo $course['id']; ?>" method="POST" enctype="multipart/form-data"
    style="max-width: 600px;">

    <label for="title">Tên Khóa Học *</label>
    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($course['title']); ?>"
        style="width: 100%; padding: 8px; margin-bottom: 10px;">

    <label for="category_id">Danh Mục *</label>
    <select id="category_id" name="category_id" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>"
                <?php if ($category['id'] == $course['category_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($category['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="description">Mô Tả</label>
    <textarea id="description" name="description" rows="5"
        style="width: 100%; padding: 8px; margin-bottom: 10px;"><?php echo htmlspecialchars($course['description']); ?></textarea>

    <label for="price">Giá Tiền (VND) *</label>
    <input type="number" id="price" name="price" min="0" required value="<?php echo $course['price']; ?>"
        style="width: 100%; padding: 8px; margin-bottom: 10px;">

    <label for="level">Cấp Độ</label>
    <select id="level" name="level" style="width: 100%; padding: 8px; margin-bottom: 10px;">
        <?php $levels = ['Beginner', 'Intermediate', 'Advanced']; ?>
        <?php foreach ($levels as $level): ?>
            <option value="<?php echo $level; ?>" <?php if ($level == $course['level']) echo 'selected'; ?>>
                <?php echo $level; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Ảnh Đại Diện Hiện Tại:</label>
    <?php if (!empty($course['image'])): ?>
        <p><?php echo htmlspecialchars($course['image']); ?> (Để trống nếu không thay đổi)</p>
    <?php endif; ?>
    <label for="image">Thay Đổi Ảnh</label>
    <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 8px; margin-bottom: 20px;">

    <button type="submit"
        style="padding: 10px 20px; background-color: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer;">Cập
        Nhật Khóa Học</button>
    <a href="/instructor/course/manage" style="margin-left: 10px;">Quay lại</a>
</form>