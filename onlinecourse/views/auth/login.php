<?php
include_once VIEWS_PATH . '/layouts/header.php';

$message = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<div class="auth-container">
    <h2>Đăng Nhập Hệ Thống</h2>

    <?php if (!empty($message)): ?>
        <div class="alert-error">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/auth/checkLogin" method="POST" class="login-form">
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
    </form>

    <p class="text-center">Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>/auth/register">Đăng ký ngay</a></p>
</div>

<?php
include_once VIEWS_PATH . '/layouts/footer.php';
?>