<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Online Course</title>

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
                <p>Bắt đầu hành trình học tập của bạn</p>
            </div>

            <div class="auth-illustration">
                <svg width="300" height="250" viewBox="0 0 300 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="150" cy="125" r="100" fill="white" fill-opacity="0.1" />
                    <circle cx="150" cy="125" r="70" fill="white" fill-opacity="0.2" />
                    <path d="M120 110L140 130L180 90" stroke="white" stroke-width="8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="auth-right">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Tạo tài khoản mới</h2>
                    <p>Điền thông tin để bắt đầu học tập</p>
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

                <form action="<?php echo BASE_URL; ?>/auth/store" method="POST" class="auth-form" id="registerForm">
                    <div class="form-group">
                        <label for="fullname">Họ và tên</label>
                        <div class="input-group">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                            <input type="text" id="fullname" name="fullname" class="form-control"
                                placeholder="Nguyễn Văn A" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <div class="input-group">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" />
                            </svg>
                            <input type="text" id="username" name="username" class="form-control" placeholder="username"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="example@email.com" required>
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
                                placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg class="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu</label>
                        <div class="input-group">
                            <svg class="input-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" />
                            </svg>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                placeholder="Nhập lại mật khẩu" required>
                            <button type="button" class="toggle-confirm-password" onclick="toggleConfirmPassword()">
                                <svg class="eye-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <span>Đăng ký</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" />
                        </svg>
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Đã có tài khoản? <a href="<?php echo BASE_URL; ?>/auth/login">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>
</body>

</html>