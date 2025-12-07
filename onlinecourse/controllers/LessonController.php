<?php
// Nhúng các Model cần thiết
require_once 'models/Lesson.php';
require_once 'models/Course.php';

class LessonController
{
    private $lessonModel;
    private $courseModel;

    public function __construct()
    {
        // Khởi tạo các Model cần thiết
        $this->lessonModel = new Lesson();
        $this->courseModel = new Course();
    }

    /**
     * Phương thức tiện ích để tải View.
     * (Đây là hàm loadView thay thế cho việc thừa kế từ BaseController)
     */
    private function loadView(string $viewPath, array $data = [])
    {
        // Chuyển các key của mảng $data thành các biến cục bộ
        extract($data);

        // Xây dựng đường dẫn vật lý đến file View
        $filePath = 'views/' . $viewPath . '.php';

        if (file_exists($filePath)) {
            // Tải file View
            require_once $filePath;
        } else {
            die("Lỗi: Không tìm thấy file View tại đường dẫn: " . $filePath);
        }
    }


    /**
     * Kiểm tra quyền Giảng viên và quyền sở hữu Khóa học.
     */
    private function checkAccessAndOwnership(int $course_id)
    {
        // 1. Kiểm tra Giảng viên đã đăng nhập chưa (Giả sử $_SESSION đã được thiết lập)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
            header('Location: /login');
            exit();
        }
        $instructor_id = $_SESSION['user_id'];

        // 2. Kiểm tra Khóa học có tồn tại và thuộc sở hữu của Giảng viên này không
        $course = $this->courseModel->getById($course_id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: /instructor/course/manage?error=unauthorized_lesson_access');
            exit();
        }
        return ['course' => $course, 'instructor_id' => $instructor_id];
    }


    // ====================================================================
    // 1. READ (LIST): Hiển thị danh sách Bài học
    // URL: /instructor/lessons/manage/{course_id}
    // ====================================================================
    public function manage(int $course_id)
    {
        $data = $this->checkAccessAndOwnership($course_id);

        $lessons = $this->lessonModel->getAllByCourseId($course_id)->fetchAll(PDO::FETCH_ASSOC);

        $this->loadView('instructor/lessons/manage', [
            'course' => $data['course'],
            'lessons' => $lessons
        ]);
    }

    // ====================================================================
    // 2. CREATE: Xử lý Thêm Bài học
    // URL: /instructor/lessons/create/{course_id}
    // ====================================================================
    public function create(int $course_id)
    {
        $data = $this->checkAccessAndOwnership($course_id);
        $course = $data['course'];
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['title']) || empty($_POST['content']) || (int)$_POST['order'] <= 0) {
                $error = "Vui lòng điền đủ Tiêu đề, Nội dung và Thứ tự hợp lệ.";
            } else {
                $result = $this->lessonModel->create(
                    $course_id,
                    $_POST['title'],
                    $_POST['content'],
                    $_POST['video_url'] ?? '',
                    (int)$_POST['order']
                );

                if ($result) {
                    header('Location: /instructor/lessons/manage/' . $course_id . '?success=lesson_created');
                    exit();
                } else {
                    $error = "Tạo bài học thất bại. Vui lòng thử lại.";
                }
            }
        }

        $this->loadView('instructor/lessons/create', [
            'course' => $course,
            'error' => $error
        ]);
    }

    // ====================================================================
    // 3. UPDATE: Xử lý Chỉnh sửa Bài học
    // URL: /instructor/lessons/edit/{lesson_id}
    // ====================================================================
    public function edit(int $lesson_id)
    {
        $lesson = $this->lessonModel->getById($lesson_id);

        if (!$lesson) {
            header('Location: /error/404');
            exit();
        }

        // Kiểm tra quyền sở hữu Khóa học chứa Bài học này
        $data = $this->checkAccessAndOwnership($lesson['course_id']);
        $course = $data['course'];
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['title']) || empty($_POST['content']) || (int)$_POST['order'] <= 0) {
                $error = "Vui lòng điền đủ thông tin và Thứ tự hợp lệ.";
            } else {
                $result = $this->lessonModel->update(
                    $lesson_id,
                    $_POST['title'],
                    $_POST['content'],
                    $_POST['video_url'] ?? '',
                    (int)$_POST['order']
                );

                if ($result) {
                    header('Location: /instructor/lessons/manage/' . $course['id'] . '?success=lesson_updated');
                    exit();
                } else {
                    $error = "Cập nhật bài học thất bại. Vui lòng thử lại.";
                }
            }
        }

        // Tải lại dữ liệu bài học (sau khi submit lỗi hoặc lần đầu GET)
        $lesson = $this->lessonModel->getById($lesson_id);

        $this->loadView('instructor/lessons/edit', [
            'course' => $course,
            'lesson' => $lesson,
            'error' => $error
        ]);
    }

    // ====================================================================
    // 4. DELETE: Xử lý Xóa Bài học
    // URL: /instructor/lessons/delete/{lesson_id}
    // ====================================================================
    public function delete(int $lesson_id)
    {
        $lesson = $this->lessonModel->getById($lesson_id);

        if (!$lesson) {
            header('Location: /error/404');
            exit();
        }

        // Kiểm tra quyền sở hữu Khóa học chứa Bài học này
        $data = $this->checkAccessAndOwnership($lesson['course_id']);
        $course_id = $lesson['course_id']; // Lấy ID khóa học để redirect

        if ($this->lessonModel->delete($lesson_id)) {
            header('Location: /instructor/lessons/manage/' . $course_id . '?success=lesson_deleted');
            exit();
        } else {
            header('Location: /instructor/lessons/manage/' . $course_id . '?error=delete_failed');
            exit();
        }
    }
}
