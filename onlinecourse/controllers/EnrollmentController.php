<!-- // EnrollmentController.php
// Xử lý việc học viên tham gia khóa học   // Mua và học khóa học -->
<!-- 
// Models liên quan:
// Enrollment.php: Quản lý dữ liệu đăng ký khóa học
// Course.php: Quản lý dữ liệu khóa học
// User.php: Quản lý dữ liệu người dùng (học viên) 

// Views liên quan:
// - views/students/dashboard.php: Tổng quan tiến độ học tập của học viên
// - views/courses/my_courses.php: Hiển thị danh sách khóa học mà học viên đã đăng ký

// Logic nghiệp vụ:
// enroll($courseId, $userId): Đăng ký học viên vào khóa học
// myCourses($userId): Lấy danh sách khóa học mà học viên đã đăng ký
// progress($courseId, $userId): Lấy tiến độ học tập của học viên
// Kiểm tra quyền truy cập khóa học trước khi cho phép học viên xem nội dung khóa học

// done -->

<?php
require_once 'models/Enrollment.php';
require_once 'models/Course.php';

class EnrollmentController
{
    private $enrollmentModel;
    private $courseModel;

    public function __construct()
    {
        // Yêu cầu phải có Models
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- Helper: GIẢ LẬP ĐĂNG NHẬP (MOCK LOGIN) ---
    // Thay vì redirect, hàm này sẽ tự login cho 1 user ngẫu nhiên
    private function checkLogin() {
        // Nếu chưa có session user
        if (!isset($_SESSION['user'])) {
            
            // 1. Tạo kết nối CSDL tạm thời để lấy user
            // (Đảm bảo class Database đã được load từ index.php hoặc model)
            $db = new Database();
            $conn = $db->getConnection();

            // 2. Query lấy 1 user ngẫu nhiên (Ưu tiên lấy Học viên - role 0)
            // ORDER BY RAND() để lấy ngẫu nhiên mỗi lần F5 (hoặc bỏ đi để lấy cố định)
            $query = "SELECT * FROM users WHERE role = 0 ORDER BY RAND() LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Nếu không có học viên, lấy đại user bất kỳ
            if (!$user) {
                $query = "SELECT * FROM users ORDER BY RAND() LIMIT 1";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // 3. Gán thông tin vào Session
            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'], // Quan trọng để hiển thị Sidebar
                    'avatar' => isset($user['avatar']) ? $user['avatar'] : null
                ];
                
                // (Tùy chọn) Thông báo nhỏ để biết đang dùng user nào
                // echo "<div style='background:yellow; padding:5px; text-align:center'>Đang test với User: " . $user['fullname'] . " (ID: " . $user['id'] . ")</div>";
            } else {
                die("Lỗi: Bảng 'users' đang trống! Hãy vào Database thêm ít nhất 1 dòng dữ liệu.");
            }
        }
    }

    // // Helper: Kiểm tra đăng nhập
    // private function checkLogin() {
    //     if (!isset($_SESSION['user'])) {
    //         header("Location: index.php?url=auth/login");
    //         exit();
    //     }
    // }

    // 1. Xử lý đăng ký khóa học (POST)
    // URL: index.php?url=enrollment/register
    public function register() {
        $this->checkLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
            $courseId = $_POST['course_id'];
            $studentId = $_SESSION['user']['id'];

            // Kiểm tra xem đã đăng ký chưa
            if ($this->enrollmentModel->isEnrolled($courseId, $studentId)) {
                echo "<script>alert('Bạn đã đăng ký khóa học này rồi!'); window.location.href='index.php?url=enrollment/my_courses';</script>";
                return;
            }

            // Gọi Model tạo đăng ký mới
            if ($this->enrollmentModel->create($courseId, $studentId)) {
                echo "<script>alert('Đăng ký thành công!'); window.location.href='index.php?url=enrollment/my_courses';</script>";
            } else {
                echo "<script>alert('Đăng ký thất bại!'); history.back();</script>";
            }
        }
    }

    // 2. Hiển thị danh sách khóa học của tôi 
    // URL: index.php?url=enrollment/my_courses
    public function my_courses() {
        $this->checkLogin();
        $userId = $_SESSION['user']['id'];

        // Gọi đúng tên hàm trong Model: getCoursesByStudent
        $myCourses = $this->enrollmentModel->getCoursesByStudent($userId);

        // Gọi View hiển thị
        // Lưu ý: View vẫn nằm trong thư mục student như cũ
        require_once 'views/student/my_courses.php';
    }

    // 3. Dashboard học viên
    // URL: index.php?url=enrollment/dashboard
    public function dashboard() {
        $this->checkLogin();
        // Có thể bổ sung logic thống kê sau
        require_once 'views/student/dashboard.php';
    }

    // 4. Theo dõi tiến độ
    public function course_progress() {
        $this->checkLogin(); // 1. Kiểm tra đăng nhập
        $userId = $_SESSION['user']['id'];

        // 2. [QUAN TRỌNG] Gọi Model để lấy dữ liệu khóa học
        // Nếu thiếu dòng này, View sẽ không có biến $myCourses để hiển thị
        $myCourses = $this->enrollmentModel->getCoursesByStudent($userId);

        // 3. Gọi View
        require_once 'views/student/course_progress.php';
    }
}
?>