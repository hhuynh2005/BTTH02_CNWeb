<?php
// Bật hiển thị lỗi PHP (chỉ nên dùng trong môi trường phát triển)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bắt đầu Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----------------------------------------------------
// 1. Cấu hình & Định nghĩa Đường dẫn Tuyệt đối (ABSOLUTE PATHS)
// ----------------------------------------------------
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
    define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
    define('VIEWS_PATH', ROOT_PATH . '/views');
    define('MODELS_PATH', ROOT_PATH . '/models');
    define('CONFIG_PATH', ROOT_PATH . '/config');
    // ⚠️ ĐỊNH NGHĨA BASE_URL ĐỂ DÙNG TRONG HTML (QUAN TRỌNG CHO ASSETS/FORM ACTION)
    // Thay thế chuỗi này bằng đường dẫn gốc của bạn:
    define('BASE_URL', '/cse485/BTTH02_CNWeb/onlinecourse');
}

// ----------------------------------------------------
// 2. Tự động nạp lớp (Autoloading)
// ----------------------------------------------------
spl_autoload_register(function ($class) {
    $file = '';

    // Nạp Controller
    if (strpos($class, 'Controller') !== false) {
        $file = CONTROLLERS_PATH . '/' . $class . '.php';
    }
    // Nạp Model (Giả sử các lớp khác là Model)
    else {
        $file = MODELS_PATH . '/' . $class . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

// ----------------------------------------------------
// 3. Phân tích URL (Routing cơ bản)
// ----------------------------------------------------

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Cắt bỏ phần Base Path
$base_path_segments = trim(BASE_URL, '/');
if (strpos($uri, $base_path_segments) === 0) {
    $uri = substr($uri, strlen($base_path_segments));
}
$uri = trim($uri, '/'); // Loại bỏ dấu '/' còn sót

$segments = explode('/', $uri);

$controllerName = !empty($segments[0]) ? ucfirst($segments[0]) . 'Controller' : 'HomeController';
$actionName = !empty($segments[1]) ? $segments[1] : 'index';

// ----------------------------------------------------
// 4. Xử lý Yêu cầu (Dispatch)
// ----------------------------------------------------

$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    // Controller được nạp qua Autoload hoặc include nếu Autoload thất bại

    if (!class_exists($controllerName)) {
        require_once $controllerFile; // Đảm bảo lớp được định nghĩa
    }

    $controller = new $controllerName();

    if (method_exists($controller, $actionName)) {
        call_user_func_array([$controller, $actionName], array_slice($segments, 2));
    } else {
        // Lỗi 404 cho Action
        echo "Lỗi 404: Action '{$actionName}' không tồn tại trong Controller '{$controllerName}'";
    }
} else {
    // Lỗi 404 cho Controller
    echo "Lỗi 404: Controller '{$controllerName}' không tồn tại.";
}
?>