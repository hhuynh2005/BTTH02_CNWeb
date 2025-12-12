<?php
// controllers/AuthController.php
include_once CONFIG_PATH . '/Database.php';
include_once MODELS_PATH . '/User.php';

class AuthController
{
    // Hiển thị form đăng nhập
    public function login()
    {
        include VIEWS_PATH . '/auth/login.php';
    }

    // Hiển thị form đăng ký
    public function register()
    {
        include VIEWS_PATH . '/auth/register.php';
    }

    // Xử lý lưu dữ liệu đăng ký
    public function store()
    {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $user->fullname = $_POST['fullname'];
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Mặc định đăng ký là học viên (role = 0)
            $user->role = 0;

            // Kiểm tra mật khẩu nhập lại
            if ($password !== $confirm_password) {
                header("Location: " . BASE_URL . "/auth/register?msg=" . urlencode("Mật khẩu xác nhận không khớp!"));
                exit();
            }

            // Kiểm tra xem email đã tồn tại chưa
            $user->email = $_POST['email'];
            if ($user->emailExists()) {
                header("Location: " . BASE_URL . "/auth/register?msg=" . urlencode("Email này đã được sử dụng!"));
                exit();
            }

            // Gán mật khẩu để lưu (trong production nên dùng password_hash)
            $user->password = $password;

            // Gọi model để tạo mới
            if ($user->create()) {
                // Thành công -> Chuyển về trang đăng nhập
                header("Location: " . BASE_URL . "/auth/login?msg=" . urlencode("Đăng ký thành công! Vui lòng đăng nhập."));
                exit();
            } else {
                header("Location: " . BASE_URL . "/auth/register?msg=" . urlencode("Lỗi hệ thống, vui lòng thử lại sau."));
                exit();
            }
        }
    }

    // Xử lý kiểm tra đăng nhập
    public function checkLogin()
    {
        // 1. Kết nối CSDL
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        // 2. Lấy dữ liệu từ form gửi lên
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->email = $_POST['email'];
            $password_input = $_POST['password'];

            // 3. KIỂM TRA TÀI KHOẢN (EMAIL)
            if ($user->emailExists()) {
                // 4. KIỂM TRA MẬT KHẨU
                // TODO: Trong production, dùng password_verify($password_input, $user->password)
                // Hiện tại đang test với plain text password
                if ($password_input === $user->password) {
                    // 5. Đăng nhập thành công -> Lưu Session
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['fullname'] = $user->fullname;
                    $_SESSION['user_role'] = $user->role;

                    // 6. Chuyển hướng theo quyền (Role)
                    if ($user->role == 2) {
                        // Admin
                        header("Location: " . BASE_URL . "/admin/dashboard");
                    } elseif ($user->role == 1) {
                        // Instructor/Giảng viên
                        header("Location: " . BASE_URL . "/instructor/dashboard");
                    } else {
                        // Student/Học viên
                        header("Location: " . BASE_URL . "/student/dashboard");
                    }
                    exit();
                } else {
                    // Mật khẩu sai
                    header("Location: " . BASE_URL . "/auth/login?msg=" . urlencode("Mật khẩu không chính xác!"));
                    exit();
                }
            } else {
                // Email không tồn tại
                header("Location: " . BASE_URL . "/auth/login?msg=" . urlencode("Email này chưa được đăng ký!"));
                exit();
            }
        } else {
            // Không phải POST request
            header("Location: " . BASE_URL . "/auth/login");
            exit();
        }
    }

    // Đăng xuất
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa tất cả session
        session_unset();
        session_destroy();

        // Chuyển về trang đăng nhập
        header("Location: " . BASE_URL . "/auth/login?msg=" . urlencode("Đã đăng xuất thành công!"));
        exit();
    }
}
?>