<?php
// File: controllers/CourseController.php

// Load các Models cần thiết
if (file_exists('models/Course.php')) require_once 'models/Course.php';
if (file_exists('models/Category.php')) require_once 'models/Category.php';
if (file_exists('models/Lesson.php')) require_once 'models/Lesson.php';
if (file_exists('models/User.php')) require_once 'models/User.php';

class CourseController
{
    private $courseModel;
    private $categoryModel;
    private $lessonModel;

    public function __construct()
    {
        $this->courseModel = new Course();
        $this->categoryModel = new Category();
        $this->lessonModel = new Lesson();
    }

    // =====================================================================
    // PHẦN 1: PUBLIC / STUDENT (main)
    // =====================================================================

    // Hiển thị danh sách khóa học
    public function index()
    {
        $categories = $this->categoryModel->getAll();

        $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : null;

        if ($categoryId) {
            $courses = $this->courseModel->getByCategory($categoryId);
        } else {
            $courses = $this->courseModel->getAllPublic();
        }

        require_once 'views/courses/index.php';
    }

    // Tìm kiếm khóa học
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';

        $categories = $this->categoryModel->getAll();
        $courses = $this->courseModel->search($keyword);

        require_once 'views/courses/index.php';
    }

    // Hiển thị chi tiết khóa học + danh sách bài học
    public function detail($id)
    {
        $course = $this->courseModel->getById($id);

        if (!$course) {
            echo "Khóa học không tồn tại!";
            return;
        }

        $lessons = $this->lessonModel->getByCourseId($id);

        require_once 'views/courses/detail.php';
    }

    // =====================================================================
    // PHẦN 2: INSTRUCTOR CRUD (feature/course-lesson-crud)
    // =====================================================================

    private function checkInstructorAccess()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header('Location: /login');
            exit();
        }
        return $_SESSION['user_id'];
    }

    // Quản lý khóa học của giảng viên
    public function manage()
    {
        $instructor_id = $this->checkInstructorAccess();

        $courses = $this->courseModel
            ->getAllByInstructorId($instructor_id)
            ->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('instructor/course/manage', ['courses' => $courses]);
    }

    // Tạo khóa học
    public function create()
    {
        $instructor_id = $this->checkInstructorAccess();
        $categories = $this->categoryModel->getAll();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $image_name = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_name = basename($_FILES["image"]["name"]);
            }

            if (empty($_POST['title']) || empty($_POST['category_id']) || (float)$_POST['price'] < 0) {
                $error = "Vui lòng điền đủ Tiêu đề, Danh mục và Giá tiền không âm.";
            } else {
                $result = $this->courseModel->create(
                    $_POST['title'],
                    $_POST['description'] ?? '',
                    $instructor_id,
                    (int)$_POST['category_id'],
                    (float)$_POST['price'],
                    (int)$_POST['duration_weeks'],
                    $_POST['level'],
                    $image_name
                );

                if ($result) {
                    header('Location: /instructor/course/manage?success=created');
                    exit();
                } else {
                    $error = "Tạo khóa học thất bại. Vui lòng thử lại.";
                }
            }
        }

        $this->loadView('instructor/course/create', [
            'categories' => $categories,
            'error' => $error
        ]);
    }

    // Chỉnh sửa khóa học
    public function edit($id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);
        $categories = $this->categoryModel->getAll();

        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: /instructor/course/manage?error=unauthorized');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $image_name = $course['image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_name = basename($_FILES["image"]["name"]);
            }

            $result = $this->courseModel->update(
                $id,
                $_POST['title'],
                $_POST['description'] ?? '',
                (int)$_POST['category_id'],
                (float)$_POST['price'],
                (int)$_POST['duration_weeks'],
                $_POST['level'],
                $image_name
            );

            if ($result) {
                header('Location: /instructor/course/manage?success=updated');
                exit();
            } else {
                $error = "Cập nhật thất bại. Vui lòng kiểm tra lại dữ liệu.";
            }
        }

        $this->loadView('instructor/course/edit', [
            'course' => $course,
            'categories' => $categories,
            'error' => $error ?? null
        ]);
    }

    // Xóa khóa học
    public function delete($id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: /instructor/course/manage?error=unauthorized_delete');
            exit();
        }

        if ($this->courseModel->delete($id)) {
            header('Location: /instructor/course/manage?success=deleted');
            exit();
        } else {
            header('Location: /instructor/course/manage?error=delete_failed');
            exit();
        }
    }
}
?>
