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
    $project_path = '/cse485/BTTH02/onlinecourse';
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
// 3. ROUTER - XỬ LÝ ROUTES
// ====================================================

// Lấy URI từ request
$requestUri = $_SERVER['REQUEST_URI'];

// Loại bỏ base path
$basePath = '/cse485/BTTH02/onlinecourse';
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Loại bỏ query string (Chỉ lấy phần PATH)
$requestUri = parse_url($requestUri, PHP_URL_PATH);

// Chuẩn hóa URI
$uri = trim($requestUri, '/');

// Khởi tạo biến routing
$controllerName = 'HomeController';
$actionName = 'index';
$params = [];

// ====================================================
// ĐỊNH NGHĨA ROUTES - WEB (Ưu tiên các route phức tạp trước)
// ====================================================

// Route Home
if ($uri === '' || $uri === 'home' || $uri === 'index') {
    $controllerName = 'HomeController';
    $actionName = 'index';
}

// Route Auth
elseif (strpos($uri, 'auth/') === 0) {
    $controllerName = 'AuthController';
    $parts = explode('/', substr($uri, 5));
    $actionName = $parts[0] ?? 'login';
    $params = array_slice($parts, 1);
}

// ====================================================
// STUDENT / ENROLLMENT ROUTES
// ====================================================

// [ROUTE QUAN TRỌNG]: Learning Interface (student/course/{id}) - PHẢI ĐẶT TRÊN courses/detail
// Regex khớp: student/course/3, student/course/3/
elseif (preg_match('/^student\/course\/(\d+)/', $uri, $matches)) {
    $controllerName = 'EnrollmentController';
    $actionName = 'learning';
    // $matches[1] là ID khóa học. Controller sẽ xử lý tham số query (?lesson_id=X).
    $params = [$matches[1]];
}

// Route Student Dashboard
elseif ($uri === 'student/dashboard') {
    $controllerName = 'EnrollmentController';
    $actionName = 'dashboard';
}
// Route My Courses
elseif ($uri === 'student/my-courses') {
    $controllerName = 'EnrollmentController';
    $actionName = 'myCourses';
}
// Route Student Progress
elseif ($uri === 'student/progress') {
    $controllerName = 'EnrollmentController';
    $actionName = 'progressList';
}
// Route Progress Detail (enrollment/progress/{course_id}/{student_id})
elseif (preg_match('/^enrollment\/progress\/(\d+)\/(\d+)$/', $uri, $matches)) {
    $controllerName = 'EnrollmentController';
    $actionName = 'progressDetail';
    $params = array_slice($matches, 1);
}
// Route Enrollment (General: register, completeLesson) - PHẢI ĐẶT SAU PROGRESS DETAIL
elseif (strpos($uri, 'enrollment/') === 0) {
    $controllerName = 'EnrollmentController';
    $parts = explode('/', substr($uri, 11));
    $actionName = $parts[0] ?? 'dashboard'; // enrollment/register, enrollment/completeLesson
    $params = array_slice($parts, 1);
}

// ====================================================
// PUBLIC COURSE ROUTES
// ====================================================

// Route Course Detail Public (courses/detail/{id}) - PHẢI ĐẶT TRÊN courses/index
elseif (preg_match('/^courses\/detail\/(\d+)$/', $uri, $matches)) {
    $controllerName = 'CourseController';
    $actionName = 'detail';
    $params = [$matches[1]];
}
// Route Courses Public (index, search, explore, etc.)
elseif (strpos($uri, 'courses') === 0 || strpos($uri, '/courses') === 0) {
    $controllerName = 'CourseController';
    $uri_parts = explode('/', trim($uri, '/'));

    // Nếu chỉ là /courses hoặc /courses/index, action là index.
    // Nếu là /courses/search hoặc /courses/explore, action là search hoặc explore.
    $actionName = $uri_parts[1] ?? 'index';
    $params = array_slice($uri_parts, 2);
}


// ====================================================
// INSTRUCTOR ROUTES
// ====================================================

// Route Instructor Dashboard
elseif ($uri === 'instructor/dashboard') {
    $controllerName = 'CourseController'; // Giả định dashboard của instructor nằm ở CourseController
    $actionName = 'dashboard';
}
// Route Course Management (Giảng viên)
elseif (strpos($uri, 'course/') === 0) {
    $controllerName = 'CourseController';
    $parts = explode('/', substr($uri, 7));
    $actionName = $parts[0] ?? 'manage'; // Ví dụ: course/manage/1 -> manage
    $params = array_slice($parts, 1);
}
// Route Lesson Management (Giảng viên)
elseif (strpos($uri, 'lesson/') === 0) {
    $controllerName = 'LessonController';
    $parts = explode('/', substr($uri, 7));
    $actionName = $parts[0] ?? 'manage';
    $params = array_slice($parts, 1);
}
// Route Instructor Enrollments/Progress (Legacy handling)
else {
    $parts = explode('/', $uri);

    if (strtolower($parts[0]) === 'instructor') {
        // Xử lý Instructor legacy routes (Giữ nguyên logic của bạn)
        if (!empty($parts[1]) && strtolower($parts[1]) === 'enrollments') {
            $controllerName = 'EnrollmentController';
            $actionName = empty(array_slice($parts, 2)) ? 'instructorEnrollmentsSummary' : 'listStudents';
            $params = array_slice($parts, 2);
        } elseif (!empty($parts[1]) && strtolower($parts[1]) === 'progress') {
            $controllerName = 'EnrollmentController';
            $actionName = empty(array_slice($parts, 2)) ? 'progressOverview' : 'progress';
            $params = array_slice($parts, 2);
        } else {
            // Trường hợp Instructor/courses/createcourse (legacy)
            if (!empty($parts[1])) {
                $actionName = $parts[1];
                $params = array_slice($parts, 2);
                $controllerName = 'CourseController'; // Giả định các action này nằm trong CourseController
            }
        }
    } else {
        // Default routing
        $controllerName = !empty($parts[0]) ? ucfirst($parts[0]) . 'Controller' : 'HomeController';
        $actionName = !empty($parts[1]) ? $parts[1] : 'index';
        $params = array_slice($parts, 2);
    }
}

// Chuyển đổi kebab-case sang camelCase
if (strpos($actionName, '-') !== false) {
    $actionName = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $actionName))));
}

// ====================================================
// 4. XỬ LÝ REQUEST
// ====================================================

$controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    try {
        require_once $controllerFile;

        if (class_exists($controllerName)) {
            $controller = new $controllerName();

            if (method_exists($controller, $actionName)) {
                call_user_func_array([$controller, $actionName], $params);
            } else {
                if (method_exists($controller, 'index')) {
                    $controller->index();
                } else {
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
    http_response_code(404);
    showError("Controller '{$controllerName}' không tồn tại.<br>File: {$controllerFile}");
}

// ====================================================
// 5. HÀM HIỂN THỊ LỖI
// ====================================================
function showError($message)
{
    // Cần định nghĩa lại BASE_URL trong hàm này vì nó nằm ngoài scope
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $project_path = '/cse485/BTTH02/onlinecourse';
    $BASE_URL = $protocol . '://' . $host . $project_path;

    // Cần thêm mã lỗi HTTP hiện tại (404, 500, v.v.)
    $http_code = http_response_code();
    $http_status = match ($http_code) {
        404 => 'Không tìm thấy trang (404)',
        500 => 'Lỗi máy chủ nội bộ (500)',
        default => "Lỗi HTTP ({$http_code})",
    };

    echo '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lỗi ' . $http_code . ' - Online Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: "Inter", sans-serif;
        }
        .error-container {
            margin-top: 100px;
        }
        .error-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: none;
        }
        .error-icon {
            font-size: 80px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .back-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s;
        }
        .back-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-outline-custom {
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            transform: translateY(-2px);
        }
        .error-code {
            font-family: "Courier New", monospace;
            background: #f9fafb;
            border-left: 4px solid #ef4444;
            padding: 1rem;
            border-radius: 4px;
            word-break: break-word;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container error-container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card error-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="error-icon">⚠️</div>
                            <h2 class="mt-3 mb-2 fw-bold" style="color: #374151;">' . $http_status . '</h2>
                            <p class="text-muted">Hệ thống gặp sự cố khi xử lý yêu cầu của bạn</p>
                        </div>
                        
                        <div class="alert alert-danger border-0" role="alert">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="alert-heading fw-bold mb-2">Thông tin chi tiết</h5>
                                    <div class="error-code">
                                        ' . nl2br(htmlspecialchars($message)) . '
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <a href="' . $BASE_URL . '" class="btn back-btn text-white w-100 py-3">
                                        <i class="fas fa-home me-2"></i>Trang chủ
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="' . $BASE_URL . '/courses" class="btn btn-success w-100 py-3 btn-outline-custom">
                                        <i class="fas fa-book me-2"></i>Khóa học
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="' . $BASE_URL . '/auth/login" class="btn btn-outline-primary w-100 py-3 btn-outline-custom">
                                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Nếu lỗi tiếp diễn, vui lòng liên hệ <strong>quản trị viên hệ thống</strong>
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <div class="text-white">
                        <small>
                            Online Course System &copy; ' . date('Y') . ' | 
                            <a href="' . $BASE_URL . '/admin" class="text-white text-decoration-underline">Admin</a> | 
                            <a href="' . $BASE_URL . '/instructor/dashboard" class="text-white text-decoration-underline">Instructor</a> |
                            <a href="' . $BASE_URL . '/student/dashboard" class="text-white text-decoration-underline">Student</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
    exit();
}