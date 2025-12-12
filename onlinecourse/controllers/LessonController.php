<?php
// File: controllers/LessonController.php - HOÀN CHỈNH VÀ ĐÃ KHẮC PHỤC LỖI KHỞI TẠO

// Load Models & Config cần thiết
require_once MODELS_PATH . '/Lesson.php';
require_once MODELS_PATH . '/Course.php';
require_once MODELS_PATH . '/Material.php';
require_once CONFIG_PATH . '/Database.php';

class LessonController
{
    private $lessonModel;
    private $courseModel;
    private $materialModel;
    private $db; // Thuộc tính để lưu trữ kết nối DB

    public function __construct()
    {
        // 1. KHỞI TẠO KẾT NỐI DATABASE
        $database = new Database();
        $this->db = $database->getConnection();
        $db = $this->db;

        // 2. KHỞI TẠO CÁC MODEL VỚI KẾT NỐI ($db) - KHẮC PHỤC ArgumentCountError
        $this->lessonModel = new Lesson($db);
        $this->courseModel = new Course($db);
        $this->materialModel = new Material($db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // --- Helper Methods ---
    private function checkInstructorAccess()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 0) != 1) {
            header('Location: ' . BASE_URL . '/auth/login?msg=' . urlencode('Bạn cần đăng nhập với quyền Giảng viên!'));
            exit();
        }
        return $_SESSION['user_id'];
    }

    private function redirect($url, $message = null, $type = 'success')
    {
        if ($message) {
            $_SESSION['message'] = $message;
            $_SESSION['message_type'] = $type;
        }
        header('Location: ' . $url);
        exit();
    }

    private function loadView($viewPath, $data = [])
    {
        extract($data);
        $fullPath = VIEWS_PATH . '/' . $viewPath;
        if (pathinfo($fullPath, PATHINFO_EXTENSION) !== 'php') {
            $fullPath .= '.php';
        }

        if (!file_exists($fullPath)) {
            die("View không tồn tại: {$fullPath}");
        }

        while (ob_get_level()) {
            ob_end_clean();
        }
        ob_start();
        include $fullPath;
        ob_end_flush();
    }

    // =====================================================================
    // PHẦN 1: QUẢN LÝ BÀI HỌC (CRUD)
    // =====================================================================

    public function manage($course_id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($course_id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền quản lý khóa học này.', 'error');
        }

        $lessons = $this->lessonModel->getByCourseId($course_id);
        $data = ['course' => $course, 'lessons' => $lessons];
        $this->loadView('instructor/lessons/manage.php', $data);
    }

    public function create($course_id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($course_id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        $lessons = $this->lessonModel->getByCourseId($course_id);
        $total_lessons = count($lessons);

        $data = ['course' => $course, 'total_lessons' => $total_lessons];
        $this->loadView('instructor/lessons/create.php', $data);
    }

    public function store($course_id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($course_id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/lesson/create/' . $course_id);
        }

        // Validate
        $errors = [];
        if (empty($_POST['title']) || strlen(trim($_POST['title'])) < 3) {
            $errors[] = "Tiêu đề phải có ít nhất 3 ký tự";
        }
        if (empty($_POST['content'])) {
            $errors[] = "Nội dung không được để trống";
        }
        if (empty($_POST['order_num']) || $_POST['order_num'] < 1) {
            $errors[] = "Thứ tự bài học không hợp lệ";
        }

        if (!empty($errors)) {
            $error_msg = implode(", ", $errors);
            $this->redirect(BASE_URL . '/lesson/create/' . $course_id . '?msg=' . urlencode($error_msg), null, 'error');
        }

        $result = $this->lessonModel->create(
            $course_id,
            trim($_POST['title']),
            trim($_POST['content']),
            trim($_POST['video_url'] ?? ''),
            (int) $_POST['order_num']
        );

        if ($result) {
            $this->redirect(BASE_URL . '/lesson/manage/' . $course_id, '✅ Tạo bài học thành công!', 'success');
        } else {
            $this->redirect(BASE_URL . '/lesson/create/' . $course_id, '❌ Tạo bài học thất bại!', 'error');
        }
    }

    public function edit($lesson_id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $lesson = $this->lessonModel->getById($lesson_id);

        if (!$lesson) {
            $this->redirect(BASE_URL . '/course/manage', 'Không tìm thấy bài học!', 'error');
        }

        $course = $this->courseModel->getById($lesson['course_id']);
        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        $data = ['course' => $course, 'lesson' => $lesson];
        $this->loadView('instructor/lessons/edit.php', $data);
    }

    public function update($lesson_id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $lesson = $this->lessonModel->getById($lesson_id);

        if (!$lesson) {
            $this->redirect(BASE_URL . '/course/manage', 'Không tìm thấy bài học!', 'error');
        }

        $course = $this->courseModel->getById($lesson['course_id']);
        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/lesson/edit/' . $lesson_id);
        }

        // Validate
        $errors = [];
        if (empty($_POST['title']) || strlen(trim($_POST['title'])) < 3) {
            $errors[] = "Tiêu đề phải có ít nhất 3 ký tự";
        }
        if (empty($_POST['content'])) {
            $errors[] = "Nội dung không được để trống";
        }
        if (empty($_POST['order_num']) || $_POST['order_num'] < 1) {
            $errors[] = "Thứ tự bài học không hợp lệ";
        }

        if (!empty($errors)) {
            $error_msg = implode(", ", $errors);
            $this->redirect(BASE_URL . '/lesson/edit/' . $lesson_id . '?msg=' . urlencode($error_msg), null, 'error');
        }

        $result = $this->lessonModel->update(
            $lesson_id,
            trim($_POST['title']),
            trim($_POST['content']),
            trim($_POST['video_url'] ?? ''),
            (int) $_POST['order_num']
        );

        if ($result) {
            $this->redirect(BASE_URL . '/lesson/manage/' . $course['id'], '✅ Cập nhật thành công!', 'success');
        } else {
            $this->redirect(BASE_URL . '/lesson/edit/' . $lesson_id, '❌ Cập nhật bài học thất bại!', 'error');
        }
    }

    public function delete($lesson_id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $lesson = $this->lessonModel->getById($lesson_id);

        if (!$lesson) {
            $this->redirect(BASE_URL . '/course/manage', 'Không tìm thấy bài học!', 'error');
        }

        $course = $this->courseModel->getById($lesson['course_id']);
        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        $course_id = $lesson['course_id'];

        if ($this->lessonModel->delete($lesson_id)) {
            $this->redirect(BASE_URL . '/lesson/manage/' . $course_id, '✅ Xóa bài học thành công!', 'success');
        } else {
            $this->redirect(BASE_URL . '/lesson/manage/' . $course_id, '❌ Xóa bài học thất bại!', 'error');
        }
    }

    // ... (Thêm các hàm preview, moveUp, moveDown, reorder, export cũ của bạn vào đây nếu cần)

    // =====================================================================
    // UPLOAD VÀ QUẢN LÝ TÀI LIỆU (Material Management)
    // =====================================================================

    /**
     * [GET] Hiển thị Danh sách Tài liệu đính kèm cho một Bài học.
     * URL: /lesson/materials/{lesson_id}
     */
    public function materials(int $lesson_id)
    {
        $instructor_id = $this->checkInstructorAccess();

        // 1. Lấy thông tin Bài học và kiểm tra quyền
        // Cần đảm bảo LessonModel có hàm getLessonDetailsById (đã thảo luận ở bước trước)
        $lesson = $this->lessonModel->getLessonDetailsById($lesson_id);
        if (!$lesson) {
            $this->redirect(BASE_URL . '/course/manage', 'Bài học không tồn tại!', 'error');
        }

        $course = $this->courseModel->getById($lesson['course_id']);
        if (!$course || $course['instructor_id'] != $instructor_id) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        $materials = $this->materialModel->getByLessonId($lesson_id);
        $data = ['course' => $course, 'lesson' => $lesson, 'materials' => $materials];

        // QUYẾT ĐỊNH QUAN TRỌNG: 
        // Nếu bạn muốn hiển thị DANH SÁCH tài liệu, dùng: 
        // $this->loadView('instructor/lesson/materials_list', $data); 

        // NẾU BẠN MUỐN CHUYỂN THẲNG ĐẾN TRANG UPLOAD, DÙNG:
        $this->loadView('instructor/materials/upload', $data); // Trỏ thẳng đến form upload
    }
    /**
     * [GET] Hiển thị Form đăng tải Tài liệu
     * URL: /lesson/uploadMaterialForm/{lesson_id}
     */
    public function uploadMaterialForm(int $lesson_id)
    {
        $instructorId = $this->checkInstructorAccess();
        $lesson = $this->lessonModel->getLessonDetailsById($lesson_id);
        if (!$lesson) {
            $this->redirect(BASE_URL . '/course/manage', 'Bài học không tồn tại!', 'error');
        }
        $course = $this->courseModel->getById($lesson['course_id']);
        if (!$course || ($course['instructor_id'] ?? 0) != $instructorId) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền!', 'error');
        }

        // BỔ SUNG LẤY DANH SÁCH TÀI LIỆU Ở ĐÂY
        $materials = $this->materialModel->getByLessonId($lesson_id); // Dòng này là bắt buộc

        $data = [
            'lesson' => $lesson,
            'course' => $course,
            'materials' => $materials // TRUYỀN BIẾN DANH SÁCH VÀO VIEW
        ];

        $this->loadView('instructor/materials/upload.php', $data);
    }


    /**
     * [POST] Xử lý việc đăng tải file và lưu vào database
     * URL: /lesson/uploadMaterial
     */
    public function uploadMaterial()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/instructor/dashboard', 'Truy cập không hợp lệ.', 'error');
        }

        $instructorId = $this->checkInstructorAccess();

        $lesson_id = $_POST['lesson_id'] ?? 0;
        $material_name = $_POST['material_name'] ?? '';

        // 1. Xác thực Lesson và Quyền sở hữu
        $lesson = $this->lessonModel->getLessonDetailsById($lesson_id);
        if (!$lesson) {
            $this->redirect(BASE_URL . '/course/manage', 'Bài học không tồn tại.', 'error');
        }
        $course = $this->courseModel->getById($lesson['course_id']);
        if (!$course || ($course['instructor_id'] ?? 0) != $instructorId) {
            $this->redirect(BASE_URL . '/course/manage', 'Không có quyền.', 'error');
        }

        // 2. Xử lý File Upload
        if (empty($_FILES['file_upload']['name'])) {
            $this->redirect(BASE_URL . "/lesson /materials/$lesson_id", 'Vui lòng chọn tệp để tải lên.', 'error');
        }

        $file = $_FILES['file_upload'];
        $uploadDir = 'assets/uploads/materials/';

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {

            // 3. Lưu thông tin vào Database (Sử dụng Material Model)
            $result = $this->materialModel->create(
                $lesson_id,
                $material_name,
                $targetFile,
                $fileExtension
            );

            if ($result) {
                $this->redirect(BASE_URL . "/lesson/materials/$lesson_id", 'Đã đăng tải tài liệu thành công!', 'success');
            } else {
                // Nếu lưu DB lỗi, xóa file đã upload
                unlink($targetFile);
                $this->redirect(BASE_URL . "/lesson/uploadMaterialForm/$lesson_id", 'Lỗi: Không thể lưu thông tin vào CSDL.', 'error');
            }
        } else {
            $this->redirect(BASE_URL . "/lesson/uploadMaterialForm/$lesson_id", 'Lỗi: Tải tệp lên không thành công.', 'error');
        }
    }
}