<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Danh sách người dùng</h2>
            <a href="index.php?controller=admin&action=dashboard" class="btn btn-secondary">Quay lại Dashboard</a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Vai trò</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($users) && is_array($users) && count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td>
                                <?php 
                                    if ($user['role'] == 2) echo '<span class="badge bg-danger">Admin</span>';
                                    elseif ($user['role'] == 1) echo '<span class="badge bg-warning text-dark">Giảng viên</span>';
                                    else echo '<span class="badge bg-primary">Học viên</span>';
                                    ?>
                            </td>
                            <td>
                                <a href="index.php?controller=admin&action=deleteUser&id=<?php echo $user['id']; ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                Chưa có người dùng nào trong hệ thống.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>