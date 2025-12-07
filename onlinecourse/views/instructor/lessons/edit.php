<?php
$course = $data['course'] ?? [];
$lesson = $data['lesson'] ?? [];
$error = $data['error'] ?? null;
?>

<h2>Chỉnh Sửa Bài Học: "<?php echo htmlspecialchars($lesson['title'] ?? 'N/A'); ?>"</h2>
<p>Thuộc Khóa Học: **<?php echo htmlspecialchars($course['title'] ?? 'N/A'); ?>**</p>

<?php if ($error): ?>
    <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
<?php endif; ?>

<form action="/instructor/lessons/edit/<?php echo $lesson['id']; ?>" method="POST" style="max-width: 600px;">

    <label for="title" style="display: block; margin-top: 10px;">Tiêu Đề Bài Học *</label>
    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($lesson['title'] ?? ''); ?>"
        style="width: 100%; padding: 8px;">

    <label for="order" style="display: block; margin-top: 10px;">Thứ Tự Bài Học *</label>
    <input type="number" id="order" name="order" min="1" required value="<?php echo $lesson['order'] ?? 1; ?>"
        style="width: 100%; padding: 8px;">

    <label for="content" style="display: block; margin-top: 10px;">Nội Dung Bài Học *</label>
    <textarea id="content" name="content" rows="8" required
        style="width: 100%; padding: 8px;"><?php echo htmlspecialchars($lesson['content'] ?? ''); ?></textarea>

    <label for="video_url" style="display: block; margin-top: 10px;">URL Video (Tùy chọn)</label>
    <input type="url" id="video_url" name="video_url"
        value="<?php echo htmlspecialchars($lesson['video_url'] ?? ''); ?>"
        style="width: 100%; padding: 8px; margin-bottom: 20px;">

    <button type="submit"
        style="padding: 10px 20px; background-color: #ffc107; color: black; border: none; border-radius: 4px; cursor: pointer;">
        Cập Nhật Bài Học
    </button>
    <a href="/instructor/lessons/manage/<?php echo $course['id']; ?>" style="margin-left: 10px;">Quay lại</a>
</form>