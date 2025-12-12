<?php
// File: controllers/AdminController.php - ĐÃ KHẮC PHỤC LỖI ENROLLMENT MODEL

// Load Models & Config cần thiết
require_once CONFIG_PATH . '/Database.php';
require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Category.php';
require_once MODELS_PATH . '/Course.php';
require_once MODELS_PATH . '/Enrollment.php'; // <--- BỔ SUNG REQUIRE ENROLLMENT MODEL

class AdminController
{
    private $db;
    private $userModel;
    private $categoryModel;
    private $courseModel;
    private $enrollmentModel; // <--- KHAI BÁO THUỘC TÍNH BỊ THIẾU

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Kiểm tra đăng nhập và role = 2 (Admin)
        if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] ?? 0) != 2) {
            header("Location: " . BASE_URL . "/auth/login?msg=" . urlencode("Bạn không có quyền truy cập khu vực Admin!"));
            exit();
        }

        // 2. Khởi tạo kết nối DB và Models
        $database = new Database();
        $this->db = $database->getConnection();
        $db = $this->db;

        $this->userModel = new User($db);
        $this->categoryModel = new Category($db);
        $this->courseModel = new Course($db);
        $this->enrollmentModel = new Enrollment($db); // <--- KHỞI TẠO BỊ THIẾU
    }

    // --- Helper Methods ---
    private function loadView($viewPath, $data = [])
    {
        extract($data);
        $fullPath = VIEWS_PATH . '/' . $viewPath . '.php';

        if (file_exists($fullPath)) {
            // Fix buffer output để đảm bảo header không bị lỗi
            while (ob_get_level()) {
                ob_end_clean();
            }
            ob_start();
            include $fullPath;
            ob_end_flush();
        } else {
            die("View không tồn tại: {$fullPath}");
        }
    }

    private function redirect($url, $message = null, $type = 'success')
    {
        // Bắt đầu session nếu chưa được khởi tạo
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($message) {
            $_SESSION['message'] = $message;
            $_SESSION['message_type'] = $type;
        }
        header('Location: ' . $url);
        exit();
    }


    // --- Chức năng chính ---

    // [GET] Dashboard Admin - Hiển thị thống kê (Ánh xạ từ /admin/dashboard)
    public function dashboard()
    {
        // Lấy thống kê người dùng
        $stats = $this->userModel->getUserStatistics();
        $count = [
            'admin' => 0,
            'instructor' => 0,
            'student' => 0,
            'total' => 0
        ];

        if ($stats) {
            foreach ($stats as $row) {
                if ($row['role'] == 2)
                    $count['admin'] = $row['count'];
                if ($row['role'] == 1)
                    $count['instructor'] = $row['count'];
                if ($row['role'] == 0)
                    $count['student'] = $row['count'];
                $count['total'] += $row['count'];
            }
        }

        $data = ['count' => $count];
        $this->loadView('admin/dashboard', $data);
    }

    // =================================================================
    // QUẢN LÝ NGƯỜI DÙNG (USERS)
    // =================================================================
    public function users()
    {
        $stmt = $this->userModel->getAllUsers();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = ['users' => $users];
        $this->loadView('admin/users/manage', $data);
    }

    public function deleteUser($id)
    {
        if ($this->userModel->delete($id)) {
            $this->redirect(BASE_URL . "/admin/users", "✅ Đã xóa người dùng thành công!", 'success');
        } else {
            $this->redirect(BASE_URL . "/admin/users", "❌ Lỗi khi xóa người dùng.", 'error');
        }
    }

    public function toggleStatus($id)
    {
        if (!$id) {
            $this->redirect(BASE_URL . "/admin/users", "ID không hợp lệ", 'error');
        }

        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->redirect(BASE_URL . "/admin/users", "Người dùng không tồn tại", 'error');
        }

        $newStatus = ($user['status'] == 1) ? 0 : 1;

        if ($this->userModel->updateStatus($id, $newStatus)) {
            $msg = ($newStatus == 1) ? "✅ Đã kích hoạt thành công." : "✅ Đã vô hiệu hóa thành công.";
            $this->redirect(BASE_URL . "/admin/users", $msg, 'success');
        } else {
            $this->redirect(BASE_URL . "/admin/users", "❌ Lỗi khi cập nhật trạng thái", 'error');
        }
    }

    // =================================================================
    // QUẢN LÝ DANH MỤC (CATEGORIES)
    // =================================================================

    public function categories()
    {
        $categories = $this->categoryModel->getAll();
        $data = ['categories' => $categories];
        $this->loadView('admin/categories/list', $data);
    }

    public function createCategory()
    {
        $this->loadView('admin/categories/create');
    }

    public function storeCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . "/admin/categories", "Yêu cầu không hợp lệ.", 'error');
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($name)) {
            $this->redirect(BASE_URL . "/admin/createCategory", "Tên danh mục không được để trống.", 'error');
        }

        if ($this->categoryModel->create($name, $description)) {
            $this->redirect(BASE_URL . "/admin/categories", "✅ Tạo danh mục thành công!", 'success');
        } else {
            $this->redirect(BASE_URL . "/admin/createCategory", "❌ Lỗi khi tạo danh mục.", 'error');
        }
    }

    public function editCategory($id)
    {
        $category = $this->categoryModel->getById($id);

        if (!$category) {
            $this->redirect(BASE_URL . "/admin/categories", "Danh mục không tồn tại.", 'error');
        }

        $data = ['category' => $category];
        $this->loadView('admin/categories/edit', $data);
    }

    public function updateCategory($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . "/admin/categories", "Yêu cầu không hợp lệ.", 'error');
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($name)) {
            $this->redirect(BASE_URL . "/admin/editCategory/{$id}", "Tên danh mục không được để trống.", 'error');
        }

        if ($this->categoryModel->update($id, $name, $description)) {
            $this->redirect(BASE_URL . "/admin/categories", "✅ Cập nhật danh mục thành công!", 'success');
        } else {
            $this->redirect(BASE_URL . "/admin/editCategory/{$id}", "❌ Lỗi khi cập nhật danh mục.", 'error');
        }
    }

    public function deleteCategory($id)
    {
        if ($this->categoryModel->delete($id)) {
            $this->redirect(BASE_URL . "/admin/categories", "✅ Xóa danh mục thành công!", 'success');
        } else {
            $this->redirect(BASE_URL . "/admin/categories", "❌ Lỗi khi xóa danh mục. (Có thể do đang có khóa học sử dụng danh mục này)", 'error');
        }
    }

    // =================================================================
    // DUYỆT KHÓA HỌC (COURSE APPROVAL)
    // =================================================================
    public function courseApproval()
    {
        $pendingCourses = $this->courseModel->getPendingCourses();
        $data = ['pendingCourses' => $pendingCourses];
        $this->loadView('admin/course/approval', $data);
    }

    public function approveCourse($course_id)
    {
        if (!$course_id) {
            $this->redirect(BASE_URL . '/admin/courseApproval', 'ID Khóa học không hợp lệ.', 'error');
        }

        if ($this->courseModel->updateStatus($course_id, 1)) { // 1 = Approved
            $this->redirect(BASE_URL . '/admin/courseApproval', '✅ Đã chấp thuận khóa học thành công!', 'success');
        } else {
            $this->redirect(BASE_URL . '/admin/courseApproval', '❌ Lỗi khi chấp thuận khóa học.', 'error');
        }
    }

    public function rejectCourse($course_id)
    {
        if (!$course_id) {
            $this->redirect(BASE_URL . '/admin/courseApproval', 'ID Khóa học không hợp lệ.', 'error');
        }

        if ($this->courseModel->updateStatus($course_id, 2)) { // 2 = Rejected
            $this->redirect(BASE_URL . '/admin/courseApproval', '✅ Đã từ chối khóa học thành công!', 'success');
        } else {
            $this->redirect(BASE_URL . '/admin/courseApproval', '❌ Lỗi khi từ chối khóa học.', 'error');
        }
    }

    // =================================================================
    // THỐNG KÊ HỆ THỐNG (SYSTEM STATISTICS)
    // =================================================================
    public function systemStatistics()
    {
        // Thống kê chung (đã có trong dashboard, dùng lại)
        $userStats = $this->userModel->getUserStatistics();

        // Thống kê Khóa học
        // Lỗi 'getEnrollmentTrends on null' đã được khắc phục ở bước trước bằng cách thêm khởi tạo EnrollmentModel
        $courseStats = $this->courseModel->getCourseStatistics();

        // Thống kê Đăng ký
        $enrollmentTrends = $this->enrollmentModel->getEnrollmentTrends();

        // Chuẩn bị dữ liệu để truyền sang View
        $data = [
            'userStats' => $userStats,
            'courseStats' => $courseStats,
            'enrollmentTrends' => $enrollmentTrends,
        ];

        // Load View
        $this->loadView('admin/reports/statistics', $data);
    }
}
?>