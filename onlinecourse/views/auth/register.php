<?php
include_once VIEWS_PATH . '/layouts/header.php';

$message = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<div class="auth-container">
    <h2>Đăng Ký Tài Khoản Mới</h2>

    <?php if (!empty($message)): ?>
        <div class="alert-error">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/auth/store" method="POST" class="register-form">
        <button type="submit" class="btn btn-primary">Đăng Ký</button>

    </form>

    <p class="text-center">Đã có tài khoản? <a href="<?php echo BASE_URL; ?>/auth/login">Đăng nhập</a></p>
</div>

<?php
include_once VIEWS_PATH . '/layouts/footer.php';
?>