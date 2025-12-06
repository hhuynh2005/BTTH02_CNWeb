<?php
// Bật thông báo lỗi để dễ debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // [cite: 98]

// Include các file cần thiết
include_once 'controllers/AuthController.php';
include_once 'controllers/HomeController.php';

// Lấy controller và action từ URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($controller) {
    case 'auth':
        $auth = new AuthController();
        if ($action == 'login') $auth->login();
        elseif ($action == 'checkLogin') $auth->checkLogin();
        elseif ($action == 'logout') $auth->logout();
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