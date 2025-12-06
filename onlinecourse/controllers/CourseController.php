<?php
// Sử dụng cấu trúc thư mục từ tài liệu hướng dẫn 
require_once 'models/Course.php';
// Giả sử có hàm loadView để tải file view

class CourseController
{
    private $courseModel;

    public function __construct()
    {
        $this->courseModel = new Course();
    }

    // Hàm kiểm tra quyền Giảng viên (Role = 1 [cite: 28])
    private function checkInstructorAccess()
    {
        // LƯU Ý: Phải có Session sau khi Đăng nhập (Công việc của Nhóm 1)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header('Location: /login');
            exit();
        }
        return $_SESSION['user_id'];
    }

    // 1. Quản lý Khóa học (READ List)
    public function manage()
    {
        $instructor_id = $this->checkInstructorAccess();

        // Lấy danh sách khóa học của giảng viên này
        $courses = $this->courseModel->getAllByInstructorId($instructor_id)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('instructor/course/manage', ['courses' => $courses]);
    }

    // 2. Xử lý Tạo Khóa học (GET: hiển thị form, POST: xử lý data)
    public function create()
    {
        $instructor_id = $this->checkInstructorAccess();
        $categories = $this->courseModel->getAllCategories()->fetchAll(PDO::FETCH_ASSOC);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy tên ảnh (Chức năng Upload chi tiết thuộc về Nhóm 4 [cite: 96])
            $image_name = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_name = basename($_FILES["image"]["name"]);
                // ... Xử lý upload file ở đây ...
            }

            // Kiểm tra dữ liệu bắt buộc (Validation cơ bản)
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

        // Tải View hiển thị Form CREATE
        $this->loadView('instructor/course/create', ['categories' => $categories, 'error' => $error]);
    }

    // 3. Chỉnh sửa Khóa học (GET: hiển thị form, POST: xử lý update)
    public function edit(int $id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);
        $categories = $this->courseModel->getAllCategories()->fetchAll(PDO::FETCH_ASSOC);

        // Kiểm tra quyền sở hữu [cite: 100]
        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: /instructor/course/manage?error=unauthorized');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_name = $course['image']; // Giữ ảnh cũ

            // Nếu có upload ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0 && !empty($_FILES['image']['name'])) {
                $image_name = basename($_FILES["image"]["name"]);
                // ... Xử lý upload file mới ...
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

        // Tải View hiển thị Form EDIT
        $this->loadView('instructor/course/edit', ['course' => $course, 'categories' => $categories, 'error' => $error ?? null]);
    }

    // 4. Xóa Khóa học (DELETE)
    public function delete(int $id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);

        // Kiểm tra quyền sở hữu [cite: 100]
        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: /instructor/course/manage?error=unauthorized_delete');
            exit();
        }

        if ($this->courseModel->delete($id)) {
            // (Thêm logic xóa file ảnh đại diện khỏi server nếu cần)
            header('Location: /instructor/course/manage?success=deleted');
            exit();
        } else {
            header('Location: /instructor/course/manage?error=delete_failed');
            exit();
        }
    }
}
