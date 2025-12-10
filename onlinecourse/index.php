<?php
// ====================================================
// ONLINE COURSE MANAGEMENT SYSTEM
// Main Entry Point - MVC Router
// ====================================================

// Bật hiển thị lỗi (chỉ dùng trong development)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Bắt đầu Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ====================================================
// 1. CẤU HÌNH HẰNG SỐ & ĐƯỜNG DẪN
// ====================================================
if (!defined('ROOT_PATH')) {
    // Đường dẫn vật lý
    define('ROOT_PATH', __DIR__);
    define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
    define('VIEWS_PATH', ROOT_PATH . '/views');
    define('MODELS_PATH', ROOT_PATH . '/models');
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('ASSETS_PATH', ROOT_PATH . '/assets');

    // URL Base
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $project_path = '/cse485/BTTH02_CNWeb/onlinecourse';
    define('BASE_URL', $protocol . '://' . $host . $project_path);
}

// ====================================================
// 2. AUTOLOADER - TỰ ĐỘNG LOAD CLASSES
// ====================================================
spl_autoload_register(function ($className) {
    // Chuyển đổi namespace sang đường dẫn
    $className = str_replace('\\', '/', $className);

    // Các vị trí có thể chứa class
    $locations = [
        CONTROLLERS_PATH . '/' . $className . '.php',
        MODELS_PATH . '/' . $className . '.php',
        CONFIG_PATH . '/' . $className . '.php',
        ROOT_PATH . '/' . $className . '.php'
    ];

    foreach ($locations as $location) {
        if (file_exists($location)) {
            require_once $location;
            return;
        }
    }
});

// ====================================================
// 3. ROUTER ĐƠN GIẢN - XỬ LÝ ROUTE TỪNG TRƯỜNG HỢP
// ====================================================

// Lấy URI từ request
$requestUri = $_SERVER['REQUEST_URI'];

// Loại bỏ base path
$basePath = '/cse485/BTTH02_CNWeb/onlinecourse';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Loại bỏ query string (?param=value)
$requestUri = parse_url($requestUri, PHP_URL_PATH);

// Chuẩn hóa URI (loại bỏ / ở đầu và cuối)
$uri = trim($requestUri, '/');

// Xác định controller và action dựa trên URI
$controllerName = 'HomeController';
$actionName = 'index';
$params = [];

// ====================================================
// ĐỊNH NGHĨA ROUTES (ĐÃ CẬP NHẬT)
// ====================================================

// Route cho Home
if ($uri === '' || $uri === 'home' || $uri === 'index') {
    $controllerName = 'HomeController';
    $actionName = 'index';
}
// Route cho Auth
elseif (strpos($uri, 'auth/') === 0) {
    $controllerName = 'AuthController';
    $parts = explode('/', substr($uri, 5)); // Bỏ "auth/"
    $actionName = $parts[0] ?? 'login';
    $params = array_slice($parts, 1);
}
// Route cho Instructor Dashboard
elseif ($uri === 'instructor/dashboard') {
    $controllerName = 'CourseController';
    $actionName = 'dashboard';
}
// Route cho Course (Quản lý - Giảng viên)
elseif (strpos($uri, 'course/') === 0) {
    $controllerName = 'CourseController';
    $parts = explode('/', substr($uri, 7)); // Bỏ "course/"
    $actionName = $parts[0] ?? 'index';
    $params = array_slice($parts, 1);
}
// Route cho Lesson (Quản lý - Giảng viên)
elseif (strpos($uri, 'lesson/') === 0) {
    $controllerName = 'LessonController';
    $parts = explode('/', substr($uri, 7)); // Bỏ "lesson/"
    $actionName = $parts[0] ?? 'index';
    $params = array_slice($parts, 1);
}
// >>> KHẮC PHỤC LỖI InstructorController: Thêm rule cho Enrollment/Progress
elseif (strpos($uri, 'enrollment/') === 0) {
    $controllerName = 'EnrollmentController';
    $parts = explode('/', substr($uri, 11)); // Bỏ "enrollment/"
    $actionName = $parts[0] ?? 'dashboard';
    $params = array_slice($parts, 1);

    // Xử lý trường hợp URL có tham số thứ 2 (ví dụ: enrollment/listStudents/1)
    if (count($parts) > 1 && $parts[0] !== 'register' && $parts[0] !== 'my_courses' && $parts[0] !== 'dashboard') {
        $actionName = $parts[0];
        $params = array_slice($parts, 1);
    }
}
// Route cho Courses (public)
elseif ($uri === 'courses') {
    $controllerName = 'CourseController';
    $actionName = 'index';
}
// Route chi tiết khóa học (public)
elseif (preg_match('/^courses\/detail\/(\d+)$/', $uri, $matches)) {
    $controllerName = 'CourseController';
    $actionName = 'detail';
    $params = [$matches[1]];
}
// Route tìm kiếm (public)
elseif (strpos($uri, 'courses/search') === 0) {
    $controllerName = 'CourseController';
    $actionName = 'search';
}
// Route mặc định khác và xử lý alias/routes cũ
else {
    $parts = explode('/', $uri);

    // Trường hợp 1: Instructor enrollments/progress (đường dẫn alias cũ)
    if (strtolower($parts[0]) === 'instructor') {
        if (!empty($parts[1]) && strtolower($parts[1]) === 'enrollments') {
            // Chuyển sang EnrollmentController@listStudents (nếu có ID) hoặc EnrollmentController@instructorEnrollmentsList (nếu không có ID)
            $controllerName = 'EnrollmentController';
            $actionName = 'listStudents'; // Hoặc instructorEnrollmentsList nếu không có ID khóa học
            $params = array_slice($parts, 2);
            if (empty($params)) {
                $actionName = 'instructorEnrollmentsList';
            }
        } elseif (!empty($parts[1]) && strtolower($parts[1]) === 'progress') {
            $controllerName = 'EnrollmentController';
            $actionName = 'progress';
            $params = array_slice($parts, 2);
        }
    }

    // Trường hợp 2: Các alias cũ còn lại (instructor/courses, instructor/createCourse, v.v.)
    if (!isset($controllerName)) {
        $controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
        $actionName = !empty($parts[1]) ? $parts[1] : 'index';
        $params = array_slice($parts, 2);
    }

    // Xử lý các trường hợp URL cố gắng gọi instructor cũ: /instructor/courses, /instructor/createCourse, ...
    if (strtolower($parts[0]) === 'instructor') {
        if (!empty($parts[1]) && strtolower($parts[1]) === 'courses') {
            $controllerName = 'CourseController';
            $actionName = 'manage';
            $params = array_slice($parts, 2);
        } elseif (!empty($parts[1]) && strtolower($parts[1]) === 'createcourse') {
            $controllerName = 'CourseController';
            $actionName = 'create';
            $params = array_slice($parts, 2);
        } elseif (!empty($parts[1]) && strtolower($parts[1]) === 'editcourse') {
            $controllerName = 'CourseController';
            $actionName = 'edit';
            $params = array_slice($parts, 2);
        } elseif (!empty($parts[1]) && strtolower($parts[1]) === 'deletecourse') {
            $controllerName = 'CourseController';
            $actionName = 'delete';
            $params = array_slice($parts, 2);
        } elseif (!empty($parts[1]) && strtolower($parts[1]) === 'lessons') {
            $controllerName = 'LessonController';
            $actionName = 'manage';
            $params = array_slice($parts, 2);
        }
    }
}

// Chuyển đổi tên action từ kebab-case sang camelCase
if (strpos($actionName, '-') !== false) {
    $actionName = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $actionName))));
}

// ====================================================
// 4. XỬ LÝ REQUEST
// ====================================================

$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';

// Kiểm tra file controller tồn tại
if (file_exists($controllerFile)) {
    try {
        // Tải file controller
        require_once $controllerFile;

        // Kiểm tra class tồn tại
        if (class_exists($controllerName)) {
            // Tạo instance controller
            $controller = new $controllerName();

            // Kiểm tra method tồn tại
            if (method_exists($controller, $actionName)) {
                // Gọi action với các tham số
                call_user_func_array([$controller, $actionName], $params);
            } else {
                // Nếu không tìm thấy action, thử gọi index()
                if (method_exists($controller, 'index')) {
                    $controller->index();
                } else {
                    // Không tìm thấy action nào
                    http_response_code(404);
                    showError("Action '{$actionName}' không tồn tại trong Controller '{$controllerName}'");
                }
            }
        } else {
            http_response_code(500);
            showError("Class '{$controllerName}' không tồn tại trong file {$controllerFile}");
        }
    } catch (Exception $e) {
        http_response_code(500);
        showError("Lỗi: " . $e->getMessage() . "<br>File: " . $e->getFile() . ":" . $e->getLine());
    }
} else {
    // Controller không tồn tại
    http_response_code(404);
    showError("Controller '{$controllerName}' không tồn tại.<br>File: {$controllerFile}");
}

// ====================================================
// HÀM HIỂN THỊ LỖI
// ====================================================
function showError($message)
{
    // Nội dung hàm showError giữ nguyên (dùng cho debug và hiển thị lỗi)
    echo '
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lỗi - Online Course</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
            .error-container { margin-top: 100px; }
            .error-card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
            .back-btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
            .back-btn:hover { opacity: 0.9; transform: translateY(-2px); }
        </style>
    </head>
    <body>
        <div class="container error-container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card error-card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div style="font-size: 80px; color: #ef4444;">⚠️</div>
                                <h2 class="mt-3 mb-2" style="color: #374151;">Có lỗi xảy ra!</h2>
                                <p class="text-muted">Hệ thống gặp sự cố khi xử lý yêu cầu của bạn</p>
                            </div>
                            
                            <div class="alert alert-light border" style="background: #f9fafb;">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="alert-heading" style="color: #991b1b;">Thông báo lỗi</h5>
                                        <p style="color: #374151; font-family: monospace;">' . htmlspecialchars($message) . '</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <a href="' . BASE_URL . '" class="btn back-btn text-white w-100 py-3">
                                            <i class="fas fa-home me-2"></i>Về trang chủ
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="' . BASE_URL . '/auth/login" class="btn btn-outline-primary w-100 py-3">
                                            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="' . BASE_URL . '/courses" class="btn btn-success w-100 py-3">
                                            <i class="fas fa-book me-2"></i>Khóa học
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Nếu đây là lỗi hệ thống, vui lòng liên hệ quản trị viên hoặc thử lại sau.
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <div class="text-white">
                            <small>
                                Online Course System &copy; ' . date('Y') . ' | 
                                <a href="' . BASE_URL . '/admin" class="text-white text-decoration-underline">Admin</a> | 
                                <a href="' . BASE_URL . '/instructor/dashboard" class="text-white text-decoration-underline">Instructor</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    </body>
    </html>';
    exit();
}
?>