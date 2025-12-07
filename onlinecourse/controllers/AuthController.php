<?php
// controllers/AuthController.php
include_once 'config/Database.php';
include_once 'models/User.php';

class AuthController {
    
    // Hiển thị form đăng nhập
    public function login() {
        include 'views/auth/login.php';
    }

    // Thêm vào class AuthController (controllers/AuthController.php)

    // 1. Hiển thị form đăng ký
    public function register() {
        include 'views/auth/register.php';
    }

    // 2. Xử lý lưu dữ liệu đăng ký
    public function store() {
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
                header("Location: index.php?controller=auth&action=register&msg=Mật khẩu xác nhận không khớp!");
                exit();
            }

            // Kiểm tra xem email đã tồn tại chưa
            // (Chúng ta tái sử dụng hàm emailExists đã có ở phần Login)
            $user->email = $_POST['email']; 
            if ($user->emailExists()) {
                header("Location: index.php?controller=auth&action=register&msg=Email này đã được sử dụng!");
                exit();
            }

            // Gán mật khẩu để lưu
            $user->password = $password;

            // Gọi model để tạo mới
            if ($user->create()) {
                // Thành công -> Chuyển về trang đăng nhập
                header("Location: index.php?controller=auth&action=login&msg=Đăng ký thành công! Vui lòng đăng nhập.");
            } else {
                header("Location: index.php?controller=auth&action=register&msg=Lỗi hệ thống, vui lòng thử lại sau.");
            }
        }
    }

    // Xử lý kiểm tra đăng nhập
    public function checkLogin() {
        // 1. Kết nối CSDL
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        // 2. Lấy dữ liệu từ form gửi lên
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->email = $_POST['email'];
            $password_input = $_POST['password']; // Mật khẩu người dùng nhập

            // 3. KIỂM TRA TÀI KHOẢN (EMAIL)
            if ($user->emailExists()) {
                
                // 4. KIỂM TRA MẬT KHẨU
                if (password_verify($password_input, $user->password)) {
                    
                    // 5. Đăng nhập thành công -> Lưu Session
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['fullname'] = $user->fullname;
                    $_SESSION['role'] = $user->role;

                    // 6. Chuyển hướng theo quyền (Role)
                    if ($user->role == 2) {
                        header("Location: index.php?controller=admin&action=dashboard");
                    } elseif ($user->role == 1) {
                        header("Location: index.php?controller=instructor&action=dashboard");
                    } else {
                        header("Location: index.php?controller=home&action=index");
                    }
                    exit();

                } else {
                    // Mật khẩu sai
                    header("Location: index.php?controller=auth&action=login&msg=Mật khẩu không chính xác!");
                }
            } else {
                // Email không tồn tại
                header("Location: index.php?controller=auth&action=login&msg=Email này chưa được đăng ký!");
            }
        }
    }
}
?>