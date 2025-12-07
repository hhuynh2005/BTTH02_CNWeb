<?php
// Bật thông báo lỗi để dễ debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

// Include các file cần thiết
include_once 'controllers/AuthController.php';
include_once 'controllers/HomeController.php';

// Lấy controller và action từ URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($controller) {
    case 'auth':
        $auth = new AuthController();
        
        // Thêm các dòng kiểm tra cho register và store
        if ($action == 'login') {
            $auth->login();
        } elseif ($action == 'checkLogin') {
            $auth->checkLogin();
        } elseif ($action == 'register') {
            $auth->register(); // <--- Dòng này quan trọng để hiện form đăng ký
        } elseif ($action == 'store') {
            $auth->store();    // <--- Dòng này quan trọng để xử lý lưu
        } elseif ($action == 'logout') {
            $auth->login();
        } else {
            // Mặc định về trang login nếu action không đúng
            $auth->login();
        }
        break;
        
    case 'home':
        $home = new HomeController();
        $home->index();
        break;        
        
    default:
        echo "Lỗi: Controller không tồn tại.";
        break;
}
?>