<?php
// controllers/AdminController.php
include_once CONFIG_PATH . '/Database.php';
include_once MODELS_PATH . '/User.php';

class AdminController
{
    // Kiểm tra quyền Admin
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra đăng nhập và role = 2 (Admin)
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 2) {
            header("Location: " . BASE_URL . "/auth/login?msg=" . urlencode("Bạn không có quyền truy cập!"));
            exit();
        }
    }

    // Dashboard Admin - Hiển thị thống kê
    public function dashboard()
    {
        $database = new Database();
        $db = $database->getConnection();
        $userModel = new User($db);

        // Lấy thống kê người dùng
        $stats = $userModel->getUserStatistics();
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

        // Load view dashboard
        include VIEWS_PATH . '/admin/dashboard.php';
    }

    // Quản lý người dùng - Hiển thị danh sách
    public function users()
    {
        // Kết nối CSDL
        $database = new Database();
        $db = $database->getConnection();

        // Lấy danh sách user
        $userModel = new User($db);
        $stmt = $userModel->getAllUsers();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Load view
        if (file_exists(VIEWS_PATH . '/admin/users/manage.php')) {
            include VIEWS_PATH . '/admin/users/manage.php';
        } else {
            echo "Lỗi: Không tìm thấy file views/admin/users/manage.php";
        }
    }

    // Alias cho users() để tương thích
    public function listUsers()
    {
        $this->users();
    }

    // Xóa user
    public function deleteUser($id = null)
    {
        // Lấy ID từ parameter hoặc GET
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if ($id) {
            $database = new Database();
            $db = $database->getConnection();
            $userModel = new User($db);

            if ($userModel->delete($id)) {
                header("Location: " . BASE_URL . "/admin/users?msg=" . urlencode("Đã xóa thành công"));
            } else {
                header("Location: " . BASE_URL . "/admin/users?msg=" . urlencode("Lỗi khi xóa"));
            }
        } else {
            header("Location: " . BASE_URL . "/admin/users?msg=" . urlencode("ID không hợp lệ"));
        }
        exit();
    }

    // Quản lý danh mục khóa học
    public function categories()
    {
        // TODO: Implement category management
        include VIEWS_PATH . '/admin/categories/list.php';
    }

    // Quản lý khóa học
    public function courses()
    {
        // TODO: Implement course management
        echo "Quản lý khóa học (Coming soon)";
    }
}
?>