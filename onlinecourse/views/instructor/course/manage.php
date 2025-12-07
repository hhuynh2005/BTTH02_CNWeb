<?php
// Tên biến $courses được truyền từ Controller qua hàm loadView()
$courses = $data['courses'] ?? [];
?>

<h2>Quản Lý Khóa Học Của Tôi</h2>

<?php
if (isset($_GET['success'])) {
    $msg = "Thao tác thành công.";
    if ($_GET['success'] == 'created') $msg = "Đã tạo khóa học mới thành công.";
    if ($_GET['success'] == 'updated') $msg = "Đã cập nhật khóa học thành công.";
    if ($_GET['success'] == 'deleted') $msg = "Đã xóa khóa học thành công.";
    echo "<p style='color: green; font-weight: bold;'>$msg</p>";
}
if (isset($_GET['error'])) {
    $error_msg = "Lỗi. Thao tác không được phép.";
    echo "<p style='color: red; font-weight: bold;'>$error_msg</p>";
}
?>

<p>
    <a href="/instructor/course/create"
        style="padding: 8px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
        ➕ Tạo Khóa Học Mới
    </a>
</p>

<?php if (empty($courses)): ?>
    <p>Bạn chưa tạo khóa học nào.</p>
<?php else: ?>
    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 10px;">ID</th>
                <th style="padding: 10px;">Tên Khóa Học</th>
                <th style="padding: 10px;">Danh Mục</th>
                <th style="padding: 10px;">Giá</th>
                <th style="padding: 10px;">Trạng Thái</th>
                <th style="padding: 10px;">Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td style="padding: 10px; text-align: center;"><?php echo $course['id']; ?></td>
                    <td style="padding: 10px;"><?php echo htmlspecialchars($course['title']); ?></td>
                    <td style="padding: 10px; text-align: center;"><?php echo htmlspecialchars($course['category_name']); ?>
                    </td>
                    <td style="padding: 10px; text-align: right;"><?php echo number_format($course['price']); ?> VND</td>
                    <td style="padding: 10px; text-align: center;">
                        <span
                            style="font-weight: bold; color: <?php echo ($course['status'] == 'published') ? 'green' : 'orange'; ?>;">
                            <?php echo ucfirst($course['status']); ?>
                        </span>
                    </td>
                    <td style="padding: 10px; text-align: center;">
                        <a href="/instructor/course/edit/<?php echo $course['id']; ?>">Chỉnh Sửa</a> |
                        <a href="/instructor/lessons/manage/<?php echo $course['id']; ?>">Bài Học</a> |
                        <a href="/instructor/course/delete/<?php echo $course['id']; ?>"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa khóa học <?php echo htmlspecialchars($course['title']); ?>?')"
                            style="color: red;">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>