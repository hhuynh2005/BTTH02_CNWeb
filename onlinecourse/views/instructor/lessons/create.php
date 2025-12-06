<?php
$course = $data['course'] ?? [];
$error = $data['error'] ?? null;
?>

<h2>Thêm Bài Học Mới cho Khóa Học: "<?php echo htmlspecialchars($course['title']); ?>"</h2>

<?php if ($error): ?>
<p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="/instructor/lessons/create/<?php echo $course['id']; ?>" method="POST" style="max-width: 600px;">

    <label for="title" style="display: block; margin-top: 10px;">Tiêu Đề Bài Học *</label>
    <input type="text" id="title" name="title" required style="width: 100%; padding: 8px;">

    <label for="order" style="display: block; margin-top: 10px;">Thứ Tự Bài Học *</label>
    <input type="number" id="order" name="order" min="1" required value="1" style="width: 100%; padding: 8px;">

    <label for="content" style="display: block; margin-top: 10px;">Nội Dung Bài Học *</label>
    <textarea id="content" name="content" rows="8" required style="width: 100%; padding: 8px;"></textarea>

    <label for="video_url" style="display: block; margin-top: 10px;">URL Video (Tùy chọn)</label>
    <input type="url" id="video_url" name="video_url" style="width: 100%; padding: 8px; margin-bottom: 20px;">

    <button type="submit"
        style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Tạo Bài Học
    </button>
    <a href="/instructor/lessons/manage/<?php echo $course['id']; ?>" style="margin-left: 10px;">Quay lại</a>
</form>