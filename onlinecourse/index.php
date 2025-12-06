<!-- 
// - Khởi động session, nạp file cấu hình 
// - Kết nối cơ sở dữ liệu
// - Xử lý Routing -->

<?php
// File: index.php

// 1. KHỞI ĐỘNG SESSION & CẤU HÌNH
session_start();

// Định nghĩa đường dẫn gốc để dễ include file
define('ROOT_PATH', dirname(__FILE__));

// Nạp file cấu hình Database
// Lưu ý: Đường dẫn dựa trên cây thư mục bạn cung cấp (config/Database.php)
require_once ROOT_PATH . '/config/Database.php';

// 2. KẾT NỐI CƠ SỞ DỮ LIỆU
try {
    $db = new Database();
    $dbConnection = $db->getConnection(); 
} catch (Exception $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}

// 3. XỬ LÝ ROUTING (ĐIỀU HƯỚNG MVC)

// Lấy tham số url từ .htaccess truyền sang (mặc định là home/index)
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';

// Chuẩn hóa url: loại bỏ ký tự lạ và cắt theo dấu gạch chéo "/"
$url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
$urlArr = explode('/', $url);

// --- Xác định Controller ---
// Ví dụ: url = course/detail => Controller là Course
$controllerName = isset($urlArr[0]) ? ucfirst($urlArr[0]) : 'Home';
$controllerClass = $controllerName . 'Controller'; // Tên class: CourseController

// --- Xác định Action (Hàm) ---
// Ví dụ: url = course/detail => Action là detail
$action = isset($urlArr[1]) ? $urlArr[1] : 'index';

// --- Xác định Tham số (Params) ---
// Các phần còn lại của URL sẽ là tham số (ví dụ ID khóa học)
unset($urlArr[0]);
unset($urlArr[1]);
$params = array_values($urlArr);

// --- Kiểm tra và gọi Controller ---
$controllerPath = ROOT_PATH . '/controllers/' . $controllerClass . '.php';

if (file_exists($controllerPath)) {
    // Nhúng file controller vào
    require_once $controllerPath;
    
    // Khởi tạo đối tượng Controller
    // Kiểm tra xem class có tồn tại không trước khi new
    if (class_exists($controllerClass)) {
        $controllerObject = new $controllerClass();
        
        // Kiểm tra xem method (action) có tồn tại trong Controller không
        if (method_exists($controllerObject, $action)) {
            // Gọi hàm và truyền tham số (nếu có)
            call_user_func_array([$controllerObject, $action], $params);
        } else {
            // Action không tồn tại -> Gọi trang lỗi hoặc về trang chủ
            // require_once ROOT_PATH . '/views/errors/404.php';
            echo "Lỗi 404: Action '{$action}' không tồn tại trong {$controllerClass}.";
        }
    } else {
        echo "Lỗi: Class '{$controllerClass}' không được tìm thấy.";
    }
} else {
    // Controller không tồn tại -> Xử lý lỗi 404
    echo "Lỗi 404: Controller '{$controllerName}' không tồn tại.";
}
?>