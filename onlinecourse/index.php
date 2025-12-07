<?php
// ============================================================
// 1. KHỞI ĐỘNG SESSION & CẤU HÌNH CƠ BẢN
// ============================================================
session_start();

// Định nghĩa ROOT_PATH để include file dễ dàng
define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/models/User.php';   // từ nhánh feature (cần cho Auth)


// ============================================================
// 2. KẾT NỐI CƠ SỞ DỮ LIỆU
// ============================================================
try {
    $db = new Database();
    $dbConnection = $db->getConnection();
} catch (Exception $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}


// ============================================================
// 3. ROUTING – CHUẨN THEO NHÁNH MAIN (/controller/action)
// ============================================================

// Nếu không có tham số => home/index
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';

// Chuẩn hóa
$url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
$urlArr = explode('/', $url);

// Controller name
$controllerName = ucfirst($urlArr[0]);
$controllerClass = $controllerName . 'Controller';

// Action name
$action = isset($urlArr[1]) ? $urlArr[1] : 'index';

// Params
unset($urlArr[0], $urlArr[1]);
$params = array_values($urlArr);


// ============================================================
// 4. LOAD CONTROLLER — KIỂM TRA TỒN TẠI
// ============================================================
$controllerPath = ROOT_PATH . '/controllers/' . $controllerClass . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;

    // Kiểm tra Class
    if (class_exists($controllerClass)) {
        $controllerObject = new $controllerClass();

        // Kiểm tra Action
        if (method_exists($controllerObject, $action)) {

            // Gọi controller/action
            call_user_func_array([$controllerObject, $action], $params);

        } else {
            echo "Lỗi 404: Action '{$action}' không tồn tại trong {$controllerClass}.";
        }
    } else {
        echo "Lỗi: Class '{$controllerClass}' không tồn tại.";
    }
} else {
    echo "Lỗi 404: Controller '{$controllerName}' không tồn tại.";
}

?>
<?php
// ============================================================
// 1. KHỞI ĐỘNG SESSION & CẤU HÌNH CƠ BẢN
// ============================================================
session_start();

// Định nghĩa ROOT_PATH để include file dễ dàng
define('ROOT_PATH', dirname(__FILE__));

require_once ROOT_PATH . '/config/Database.php';
require_once ROOT_PATH . '/models/User.php';   // từ nhánh feature (cần cho Auth)


// ============================================================
// 2. KẾT NỐI CƠ SỞ DỮ LIỆU
// ============================================================
try {
    $db = new Database();
    $dbConnection = $db->getConnection();
} catch (Exception $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}


// ============================================================
// 3. ROUTING – CHUẨN THEO NHÁNH MAIN (/controller/action)
// ============================================================

// Nếu không có tham số => home/index
$url = isset($_GET['url']) ? $_GET['url'] : 'home/index';

// Chuẩn hóa
$url = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
$urlArr = explode('/', $url);

// Controller name
$controllerName = ucfirst($urlArr[0]);
$controllerClass = $controllerName . 'Controller';

// Action name
$action = isset($urlArr[1]) ? $urlArr[1] : 'index';

// Params
unset($urlArr[0], $urlArr[1]);
$params = array_values($urlArr);


// ============================================================
// 4. LOAD CONTROLLER — KIỂM TRA TỒN TẠI
// ============================================================
$controllerPath = ROOT_PATH . '/controllers/' . $controllerClass . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;

    // Kiểm tra Class
    if (class_exists($controllerClass)) {
        $controllerObject = new $controllerClass();

        // Kiểm tra Action
        if (method_exists($controllerObject, $action)) {

            // Gọi controller/action
            call_user_func_array([$controllerObject, $action], $params);

        } else {
            echo "Lỗi 404: Action '{$action}' không tồn tại trong {$controllerClass}.";
        }
    } else {
        echo "Lỗi: Class '{$controllerClass}' không tồn tại.";
    }
} else {
    echo "Lỗi 404: Controller '{$controllerName}' không tồn tại.";
}

?>
