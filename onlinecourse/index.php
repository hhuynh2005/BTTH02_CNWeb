<?php
// Bật hiển thị lỗi PHP (chỉ nên dùng trong môi trường phát triển)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bắt đầu Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ----------------------------------------------------
// 1. Cấu hình & Định nghĩa Đường dẫn
// ----------------------------------------------------
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
    define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
    define('VIEWS_PATH', ROOT_PATH . '/views');
    define('MODELS_PATH', ROOT_PATH . '/models');
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('BASE_URL', '/cse485/BTTH02_CNWeb/onlinecourse');
}

// ----------------------------------------------------
// 2. Tự động nạp lớp
// ----------------------------------------------------
spl_autoload_register(function ($class) {
    $file = '';

    if (strpos($class, 'Controller') !== false) {
        $file = CONTROLLERS_PATH . '/' . $class . '.php';
    } else {
        $file = MODELS_PATH . '/' . $class . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

// ----------------------------------------------------
// 3. Phân tích URL (Routing) - FIXED
// ----------------------------------------------------
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Loại bỏ base path
$base_path_segments = trim(BASE_URL, '/');
if (strpos($uri, $base_path_segments) === 0) {
    $uri = substr($uri, strlen($base_path_segments));
}
$uri = trim($uri, '/');

// Loại bỏ index.php khỏi URI nếu có
$uri = preg_replace('/^index\.php\/?/', '', $uri);

// Parse segments
$segments = !empty($uri) ? explode('/', $uri) : [];

// Xác định controller và action
$controllerName = !empty($segments[0]) && $segments[0] !== ''
    ? ucfirst($segments[0]) . 'Controller'
    : 'HomeController';

$actionName = !empty($segments[1]) && $segments[1] !== ''
    ? $segments[1]
    : 'index';

// ----------------------------------------------------
// 4. Xử lý Request
// ----------------------------------------------------
$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    if (!class_exists($controllerName)) {
        require_once $controllerFile;
    }

    $controller = new $controllerName();

    if (method_exists($controller, $actionName)) {
        // Truyền params từ segment thứ 2 trở đi
        $params = array_slice($segments, 2);
        call_user_func_array([$controller, $actionName], $params);
    } else {
        http_response_code(404);
        echo "Lỗi 404: Action '{$actionName}' không tồn tại trong Controller '{$controllerName}'";
    }
} else {
    http_response_code(404);
    echo "Lỗi 404: Controller '{$controllerName}' không tồn tại.";
}
?>