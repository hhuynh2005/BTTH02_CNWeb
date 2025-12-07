<?php
$categories = $data['categories'] ?? [];
$error = $data['error'] ?? null;
?>

<h2>Tạo Khóa Học Mới</h2>

<?php if ($error): ?>
    <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="/instructor/course/create" method="POST" enctype="multipart/form-data" style="max-width: 600px;">

    <label for="title">Tên Khóa Học *</label>
    <input type="text" id="title" name="title" required style="width: 100%; padding: 8px; margin-bottom: 10px;">

    <label for="category_id">Danh Mục *</label>
    <select id="category_id" name="category_id" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo $category['id']; ?>">
                <?php echo htmlspecialchars($category['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="description">Mô Tả</label>
    <textarea id="description" name="description" rows="5"
        style="width: 100%; padding: 8px; margin-bottom: 10px;"></textarea>

    <label for="price">Giá Tiền (VND) *</label>
    <input type="number" id="price" name="price" min="0" required value="0"
        style="width: 100%; padding: 8px; margin-bottom: 10px;">

    <label for="level">Cấp Độ</label>
    <select id="level" name="level" style="width: 100%; padding: 8px; margin-bottom: 10px;">
        <option value="Beginner">Cơ Bản (Beginner)</option>
        <option value="Intermediate">Trung Cấp (Intermediate)</option>
        <option value="Advanced">Nâng Cao (Advanced)</option>
    </select>

    <label for="image">Ảnh Đại Diện Khóa Học</label>
    <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 8px; margin-bottom: 20px;">

    <button type="submit"
        style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Tạo
        Khóa Học</button>
    <a href="/instructor/course/manage" style="margin-left: 10px;">Quay lại</a>
</form>