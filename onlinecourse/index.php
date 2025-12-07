<?php
// index.php

// ------------------------------------------------------------------
// 1. CẤU HÌNH MÔI TRƯỜNG & SESSION
// ------------------------------------------------------------------
// Bật hiển thị lỗi để dễ debug (Tắt khi deploy thực tế)
// developer: 1, user: 0
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Khởi động session (Bắt buộc cho chức năng Login/Logout)
session_start();

// ------------------------------------------------------------------
// 2. NHÚNG CÁC FILE CẦN THIẾT (INCLUDES)
// ------------------------------------------------------------------
// Nhúng kết nối CSDL và Models
require_once 'config/Database.php';
require_once 'models/User.php';

// Nhúng các Controllers
// Lưu ý: Đảm bảo các file này tồn tại trong thư mục controllers/
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';

// ------------------------------------------------------------------
// 3. ĐIỀU HƯỚNG (ROUTING)
// ------------------------------------------------------------------
// Lấy thông tin từ URL. Ví dụ: index.php?controller=auth&action=login
// Nếu không có, mặc định là controller='home' và action='index'
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action     = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($controller) {
    
    // --- CASE 1: TRANG CHỦ (HOME) ---
    case 'home':
        $home = new HomeController();
        // Bạn cần đảm bảo file controllers/HomeController.php có hàm index()
        $home->index(); 
        break;

    // --- CASE 2: XÁC THỰC (AUTH) - Đăng nhập/Đăng ký ---
    case 'auth':
        $auth = new AuthController();
        switch ($action) {
            case 'login':
                $auth->login();      // Hiển thị form đăng nhập
                break;
            case 'checkLogin':
                $auth->checkLogin(); // Xử lý kiểm tra đăng nhập
                break;
            case 'register':
                $auth->register();   // Hiển thị form đăng ký (Mới thêm)
                break;
            case 'store':
                $auth->store();      // Xử lý lưu đăng ký (Mới thêm)
                break;
            case 'logout':
                $auth->login();     // Xử lý đăng xuất
                break;
            default:
                $auth->login();      // Mặc định về trang login
                break;
        }
        break;

    // --- CASE 3: QUẢN TRỊ VIÊN (ADMIN) ---
    case 'admin':
        $admin = new AdminController();
        switch ($action) {
            case 'dashboard':
                $admin->dashboard(); // Trang tổng quan thống kê
                break;
            case 'listUsers':
                $admin->listUsers(); // Trang danh sách người dùng
                break;
            case 'deleteUser':
                $admin->deleteUser();// Chức năng xóa người dùng
                break;
            default:
                $admin->dashboard();
                break;
        }
        break;

    // --- CASE MẶC ĐỊNH: Lỗi 404 ---
    default:
        echo "<h1>404 Not Found</h1>";
        echo "<p>Trang bạn tìm kiếm không tồn tại.</p>";
        echo "<a href='index.php'>Quay về trang chủ</a>";
        break;
}
?>