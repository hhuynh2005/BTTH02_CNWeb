<?php

class HomeController 
{
    public function index() 
    {
        // 1. Nạp model Course
        if (file_exists('models/Course.php')) {
            require_once 'models/Course.php';
        } else {
            die("Lỗi: Không tìm thấy file models/Course.php");
        }

        // 2. Khởi tạo model
        $courseModel = new Course();

        // 3. Lấy 5 khóa học mới nhất
        $newestCourses = $courseModel->getNewestCourses();

        // 4. Gọi view để hiển thị giao diện
        require_once 'views/home/index.php';
    }
}

?>
