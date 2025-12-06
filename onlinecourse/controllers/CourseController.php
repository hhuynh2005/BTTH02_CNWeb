<!-- // CourseController.php
// Xử lý hiển thị khóa học cho người học và quản lý khóa học cho giảng viên -->

<!-- // Models liên quan:
// - models/Course.php: Quản lý dữ liệu khóa học
// - models/User.php: Quản lý dữ liệu người dùng (giảng viên, học viên)
// - models/Category.php: Quản lý danh mục khóa học
// - models/Lesson.php: Quản lý bài học trong khóa học

// Views liên quan:
//      Cho Public/Student:
// - views/courses/index.php: Hiển thị danh sách khóa học
// - views/courses/detail.php: Hiển thị chi tiết khóa học và bài học
//      Cho Instructor: 
// - views/instructor/courses/manage.php: Quản lý khóa học của giảng viên
// - views/instructor/courses/create.php: Tạo khóa học mới
// - views/instructor/courses/edit.php: Chỉnh sửa khóa học

// Logic nghiệp vụ: 
//      Public/Student:
// index(): Hiển thị tất cả khóa học, có phân trang và lọc theo danh mục
// search(): Tìm kiếm khóa học theo từ khóa và danh mục 
// show($id): Hiển thị chi tiết khóa học, danh sách bài học và thông tin giảng viên

//      Instructor:
// create()/ store(): Hiển thị form tạo khóa học và lưu khóa học mới vào DB
// edit($id)/ update($id): Hiển thị form chỉnh sửa và cập nhật khóa học trong DB
// delete($id): Xóa khóa học khỏi DB (và các bài học liên quan)
// manage(): Hiển thị danh sách khóa học của giảng viên để quản lý

// done -->

<?php
// File: controllers/CourseController.php

class CourseController {

    private $courseModel;
    private $categoryModel;
    private $lessonModel;

    public function __construct() {
        // 1. Load các Models cần thiết
        // Kiểm tra và require các file model
        if (file_exists('models/Course.php')) require_once 'models/Course.php';
        if (file_exists('models/Category.php')) require_once 'models/Category.php';
        if (file_exists('models/Lesson.php')) require_once 'models/Lesson.php';
        // User model có thể cần nếu muốn lấy chi tiết giảng viên
        if (file_exists('models/User.php')) require_once 'models/User.php';

        // 2. Khởi tạo đối tượng Model
        $this->courseModel = new Course();
        $this->categoryModel = new Category();
        $this->lessonModel = new Lesson();
    }

    // =========================================================================
    // PHẦN 1: PUBLIC / STUDENT (Học viên & Khách)
    // =========================================================================

    /**
     * index(): Hiển thị danh sách khóa học (có phân trang, lọc)
     * URL: index.php?url=course/index
     */
    public function index() {
        // Lấy danh mục để hiển thị bộ lọc
        $categories = $this->categoryModel->getAll();

        // Xử lý lọc theo danh mục
        $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;

        // Lấy danh sách khóa học (có thể thêm logic phân trang tại đây)
        if ($categoryId) {
            $courses = $this->courseModel->getByCategory($categoryId);
        } else {
            $courses = $this->courseModel->getAllPublic(); // Hàm này cần viết trong Model
        }

        // Gọi View
        require_once 'views/courses/index.php';
    }

    /**
     * search(): Tìm kiếm khóa học
     * URL: index.php?url=course/search&keyword=abc
     */
    public function search() {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        
        $categories = $this->categoryModel->getAll(); // Vẫn cần lấy danh mục cho sidebar
        $courses = $this->courseModel->search($keyword);

        // Tái sử dụng view index để hiển thị kết quả
        require_once 'views/courses/index.php';
    }

    /**
     * detail($id): Hiển thị chi tiết khóa học + bài học
     * URL: index.php?url=course/detail/5
     */
    public function detail($id) {
        // Lấy thông tin khóa học
        $course = $this->courseModel->getById($id);

        if (!$course) {
            echo "Khóa học không tồn tại!";
            return;
        }

        // Lấy danh sách bài học của khóa học này
        $lessons = $this->lessonModel->getByCourseId($id);

        // Lấy thông tin giảng viên (nếu cần hiển thị kỹ hơn)
        // $instructor = (new User())->getById($course['instructor_id']);

        require_once 'views/courses/detail.php';
    }
}
?>