<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Online Course</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/auth.css">
</head>

<body class="auth-page">
    <div class="auth-container">
        <!-- Left Side: Branding -->
        <div class="auth-left">
            <div class="auth-brand">
                <svg class="logo" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="80" height="80" rx="16" fill="white" fill-opacity="0.2" />
                    <path d="M40 25L25 32.5V47.5C25 55 31.25 61.75 40 63.75C48.75 61.75 55 55 55 47.5V32.5L40 25Z"
                        fill="white" />
                    <path d="M40 40L35 45L37.5 47.5L40 50L47.5 42.5L45 40L40 45L40 40Z" fill="#667eea" />
                </svg>
                <h1>Online Course</h1>
                <p>Nền tảng học tập trực tuyến hàng đầu Việt Nam</p>
            </div>

            <div class="auth-illustration">
                <svg width="300" height="250" viewBox="0 0 300 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="150" cy="125" r="100" fill="white" fill-opacity="0.1" />
                    <circle cx="150" cy="125" r="70" fill="white" fill-opacity="0.2" />
                    <rect x="100" y="90" width="100" height="70" rx="8" fill="white" />
                    <rect x="110" y="100" width="30" height="4" rx="2" fill="#667eea" />
                    <rect x="110" y="110" width="60" height="4" rx="2" fill="#667eea" fill-opacity="0.5" />
                    <rect x="110" y="120" width="50" height="4" rx="2" fill="#667eea" fill-opacity="0.5" />
                </svg>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Chào mừng trở lại!</h2>
                    <p>Đăng nhập để tiếp tục học tập</p>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-error">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                        </svg>
                        <?php echo htmlspecialchars($_GET['msg']); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/auth/checkLogin" method="POST" class="auth-form" id="loginForm">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="example@email.com" required autocomplete="email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="input-group">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" />
                            </svg>
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Nhập mật khẩu" required autocomplete="current-password">
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg class="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember">
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="<?php echo BASE_URL; ?>/auth/forgot_password" class="link">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <span>Đăng nhập</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>/auth/register">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>
</body>

</html>