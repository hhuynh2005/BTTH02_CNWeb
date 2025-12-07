<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0 fw-bold">ĐĂNG NHẬP</h4>
                    </div>
                    <div class="card-body p-4">

                        <?php if(isset($_GET['msg'])): ?>
                        <div class="alert alert-danger text-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php echo htmlspecialchars($_GET['msg']); ?>
                        </div>
                        <?php endif; ?>

                        <form action="index.php?controller=auth&action=checkLogin" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email:</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="nhap@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Mật khẩu:</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="********" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                            </div>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-2 text-muted">Bạn chưa có tài khoản?</p>
                            <a href="index.php?controller=auth&action=register" class="btn btn-outline-success">
                                Tạo tài khoản mới ngay
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>