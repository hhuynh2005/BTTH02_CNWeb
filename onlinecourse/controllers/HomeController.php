<?php
// File: controllers/HomeController.php - ĐÃ KHẮC PHỤC LỖI KHỞI TẠO MODEL

// Load các Models và Config cần thiết (Sử dụng đường dẫn hằng số)
require_once MODELS_PATH . '/Course.php';
require_once CONFIG_PATH . '/Database.php'; // Cần thiết để khởi tạo kết nối

class HomeController
{
    private $courseModel;
    private $db; // Thuộc tính để lưu trữ kết nối DB

    public function __construct()
    {
        // 1. KHỞI TẠO KẾT NỐI DATABASE
        $database = new Database();
        $this->db = $database->getConnection();
        $db = $this->db;

        // 2. KHỞI TẠO MODEL VỚI KẾT NỐI ($db)
        $this->courseModel = new Course($db);

        // Khởi tạo session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Helper method để tải View (Nếu bạn chưa có)
    private function loadView($viewPath, $data = [])
    {
        extract($data);
        $fullPath = VIEWS_PATH . '/' . $viewPath;

        if (!file_exists($fullPath)) {
            die("View không tồn tại: {$fullPath}");
        }

        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        include $fullPath;
        ob_end_flush();
    }

    public function index()
    {
        // 1. Lấy 5 khóa học mới nhất (Model đã được khởi tạo trong constructor)
        $newestCourses = $this->courseModel->getNewestCourses();

        $data = [
            'courses' => $newestCourses,
            // Thêm các biến cần thiết khác nếu trang chủ cần (ví dụ: categories)
        ];

        // 2. Gọi view để hiển thị giao diện
        // Giả định 'views/home/index.php' là 'home/index.php'
        $this->loadView('home/index.php', $data);
    }
}
?>