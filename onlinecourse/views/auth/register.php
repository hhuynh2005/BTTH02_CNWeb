<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center py-3">
                        <h4 class="mb-0">ĐĂNG KÝ TÀI KHOẢN</h4>
                    </div>
                    <div class="card-body p-4">

                        <?php if(isset($_GET['msg'])): ?>
                        <div class="alert alert-warning text-center">
                            <?php echo htmlspecialchars($_GET['msg']); ?>
                        </div>
                        <?php endif; ?>

                        <form action="index.php?controller=auth&action=store" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="fullname" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập (Username)</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>

                            <div class="d-grid gap-2 mt-3">
                                <button type="submit" class="btn btn-success btn-lg">Đăng Ký</button>
                            </div>
                        </form>

                        <hr>
                        <div class="text-center">
                            <p>Đã có tài khoản? <a href="index.php?controller=auth&action=login">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>