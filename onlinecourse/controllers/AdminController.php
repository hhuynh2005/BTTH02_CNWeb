<?php
// controllers/AdminController.php
include_once 'config/Database.php';
include_once 'models/User.php';

class AdminController {
    
    // 1. Kiểm tra quyền Admin khi khởi tạo
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Nếu không phải admin (role != 2) thì chuyển về trang login
        if (!isset($_SESSION['role']) || $_SESSION['role'] != 2) {
            header("Location: index.php?controller=auth&action=login&msg=Bạn không có quyền truy cập!");
            exit();
        }
    }

    // 2. Action: Dashboard (Chỉ hiện thống kê)
    public function dashboard() {
        $database = new Database();
        $db = $database->getConnection();
        $userModel = new User($db);
        
        // Lấy thống kê cho Dashboard
        $stats = $userModel->getUserStatistics();
        $count = ['admin' => 0, 'instructor' => 0, 'student' => 0, 'total' => 0];
        
        if ($stats) {
            foreach ($stats as $row) {
                if ($row['role'] == 2) $count['admin'] = $row['count'];
                if ($row['role'] == 1) $count['instructor'] = $row['count'];
                if ($row['role'] == 0) $count['student'] = $row['count'];
                $count['total'] += $row['count'];
            }
        }

        // Gọi View Dashboard
        include 'views/admin/dashboard.php';
    }

    // 3. Action: List Users (Lấy danh sách người dùng và hiển thị bảng)
    public function listUsers() {
        // A. Kết nối CSDL
        $database = new Database();
        $db = $database->getConnection();
        
        // B. Gọi Model lấy tất cả user
        $userModel = new User($db);
        $stmt = $userModel->getAllUsers();
        
        // C. QUAN TRỌNG: Gán dữ liệu vào biến $users để View sử dụng
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // D. Gọi View hiển thị bảng (manage.php)
        // Lúc này biến $users đã có dữ liệu, nên View sẽ không báo lỗi nữa
        if (file_exists('views/admin/users/manage.php')) {
            include 'views/admin/users/manage.php';
        } else {
            echo "Lỗi: Không tìm thấy file views/admin/users/manage.php";
        }
    }
    
    // 4. Action: Xóa User
    public function deleteUser() {
        if (isset($_GET['id'])) {
            $database = new Database();
            $db = $database->getConnection();
            $userModel = new User($db);
            
            if ($userModel->delete($_GET['id'])) {
                header("Location: index.php?controller=admin&action=listUsers&msg=Đã xóa thành công");
            } else {
                header("Location: index.php?controller=admin&action=listUsers&msg=Lỗi khi xóa");
            }
        }
    }
}
?>