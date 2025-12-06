<?php
include_once 'config/Database.php';
include_once 'models/User.php';

class AuthController {
    
    public function login() {
        include 'views/auth/login.php';
    }

    public function checkLogin() {
        $database = new Database();
        $db = $database->getConnection();
        $user = new User($db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user->email = $_POST['email'];
            $password = $_POST['password'];

            if ($user->emailExists()) {
                // Kiểm tra mật khẩu hash [cite: 99]
                if (password_verify($password, $user->password)) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['username'] = $user->username;
                    $_SESSION['role'] = $user->role;
                    $_SESSION['fullname'] = $user->fullname;

                    // Phân quyền [cite: 100]
                    if ($user->role == 2) {
                        // Nếu chưa có trang admin thì tạm về home
                         header("Location: index.php?controller=home"); 
                         // Sau này đổi thành: header("Location: index.php?controller=admin&action=dashboard");
                    } else {
                        header("Location: index.php?controller=home");
                    }
                } else {
                    header("Location: index.php?controller=auth&action=login&msg=Sai mật khẩu!");
                }
            } else {
                header("Location: index.php?controller=auth&action=login&msg=Email không tồn tại!");
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
    }
}
?>