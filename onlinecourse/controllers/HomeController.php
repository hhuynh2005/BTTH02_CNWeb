<!-- // Điều hướng cho trang chủ và các trang tĩnh

//     Lấy danh sách khóa học từ models/Course.php 
// và hiển thị trên views/home/index.php
// getNewestCourses(): Lấy 5 khóa học mới nhất để hiển thị trên trang chủ

// done -->

<?php
class HomeController {

    public function index() {
        // 1. Nạp Model Course
        // (Đường dẫn tính từ file index.php gốc)
        if (file_exists('models/Course.php')) {
            require_once 'models/Course.php';
        } else {
            die("Lỗi: Không tìm thấy file models/Course.php");
        }

        // 2. Khởi tạo đối tượng Course Model
        $courseModel = new Course();

        // 3. Lấy danh sách 5 khóa học mới nhất
        // Hàm getNewestCourses() sẽ được định nghĩa trong Model
        $newestCourses = $courseModel->getNewestCourses();

        // 4. Gửi dữ liệu sang View và hiển thị giao diện
        // Biến $newestCourses sẽ có hiệu lực bên trong file views/home/index.php
        require_once 'views/home/index.php';
    }
}
?>

// Luồng đã hoạt động, kết hợp với models/Course.php và views/home/index.php
// đã hiển thị 5 khóa học mới nhất trên trang chủ. (ảnh đang lỗi và chưa xem chi tiết được)