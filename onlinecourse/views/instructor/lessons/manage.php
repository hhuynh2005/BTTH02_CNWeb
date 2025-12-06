<?php
// Dữ liệu được Controller truyền qua $data
$course = $data['course'] ?? [];
$lessons = $data['lessons'] ?? [];
?>

<h2>Quản Lý Bài Học cho Khóa Học: "<?php echo htmlspecialchars($course['title']); ?>"</h2>
<p>
    <a href="/instructor/course/manage">⬅️ Quay lại Quản lý Khóa học</a>
</p>

<?php
if (isset($_GET['success'])) {
    echo "<p style='color: green; font-weight: bold;'>Thao tác bài học thành công.</p>";
}
if (isset($_GET['error'])) {
    echo "<p style='color: red; font-weight: bold;'>Thao tác bài học thất bại.</p>";
}
?>

<p>
    <a href="/instructor/lessons/create/<?php echo $course['id']; ?>"
        style="padding: 8px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
        ➕ Thêm Bài Học Mới
    </a>
</p>

<?php if (empty($lessons)): ?>
    <p>Khóa học này chưa có bài học nào.</p>
<?php else: ?>
    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px;">Thứ Tự</th>
                <th style="padding: 10px;">Tiêu Đề Bài Học</th>
                <th style="padding: 10px;">URL Video</th>
                <th style="padding: 10px;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lessons as $lesson): ?>
                <tr>
                    <td style="padding: 10px; text-align: center;"><?php echo $lesson['order']; ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($lesson['title']); ?></td>
                    <td style="padding: 10px; word-break: break-all;">
                        <?php echo empty($lesson['video_url']) ? 'Chưa có' : htmlspecialchars($lesson['video_url']); ?>
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <a href="/instructor/lessons/edit/<?php echo $lesson['id']; ?>">Chỉnh Sửa</a> |
                        <a href="/instructor/lessons/delete/<?php echo $lesson['id']; ?>"
                            onclick="return confirm('Xác nhận xóa bài học: <?php echo htmlspecialchars($lesson['title']); ?>?')"
                            style="color: red;">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>