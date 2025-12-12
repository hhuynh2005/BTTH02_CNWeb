<?php
// File: controllers/CourseController.php - PHIÊN BẢN HOÀN CHỈNH VÀ KHẮC PHỤC LỖI KHỞI TẠO

// Load các Models và Config cần thiết
require_once MODELS_PATH . '/Course.php';
require_once MODELS_PATH . '/Category.php';
require_once MODELS_PATH . '/Lesson.php';
require_once MODELS_PATH . '/User.php';
require_once CONFIG_PATH . '/Database.php'; // <--- CẦN LOAD DATABASE

class CourseController
{
    private $courseModel;
    private $categoryModel;
    private $lessonModel;
    private $db; // Thuộc tính để lưu trữ kết nối DB

    public function __construct()
    {
        // 1. KHỞI TẠO KẾT NỐI DATABASE
        $database = new Database();
        $this->db = $database->getConnection();
        $db = $this->db;

        // 2. KHỞI TẠO CÁC MODEL VỚI KẾT NỐI ($db) - KHẮC PHỤC ArgumentCountError
        $this->courseModel = new Course($db);
        $this->categoryModel = new Category($db);
        $this->lessonModel = new Lesson($db);

        // Khởi tạo session (nếu chưa có trong Helper)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Helper method: Kiểm tra quyền Instructor
     */
    private function checkInstructorAccess()
    {
        // Session đã được start trong __construct
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 0) != 1) {
            header('Location: ' . BASE_URL . '/auth/login?msg=' . urlencode('Bạn cần đăng nhập với quyền Giảng viên!'));
            exit();
        }
        return $_SESSION['user_id'];
    }

    /**
     * Helper method: Load view với data
     */
    private function loadView($viewPath, $data = [])
    {
        extract($data);
        // Giả định VIEWS_PATH và hằng số PHP khác đã được định nghĩa
        $fullPath = VIEWS_PATH . '/' . $viewPath;

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
    // PHẦN 1: PUBLIC / STUDENT (main)
    // =====================================================================

    // Hiển thị danh sách khóa học (Công khai/Mặc định)
    public function index()
    {
        // Giả định categoryModel->getAll() trả về mảng/array
        $categories = $this->categoryModel->getAll();
        $categoryId = $_GET['category_id'] ?? null;

        if ($categoryId) {
            // Lấy khóa học theo danh mục
            $courses = $this->courseModel->getByCategory($categoryId);
        } else {
            // Lấy tất cả khóa học công khai
            $courses = $this->courseModel->getAllPublic();
        }

        $data = [
            'categories' => $categories,
            'courses' => $courses,
            'categoryId' => $categoryId
        ];
        // Giả định courses/index.php là đường dẫn đúng
        $this->loadView('courses/index.php', $data);
    }

    // Tìm kiếm khóa học


    /**
     * PHƯƠNG THỨC MỚI: Hiển thị danh sách khóa học mà Học viên CHƯA đăng ký
     * URL gợi ý: /courses/explore (hoặc /student/find-courses nếu dùng EnrollmentController)
     * Đây là hàm sẽ được gọi khi học viên click "Đăng ký khóa học mới"
     */
    public function explore()
    {
        // 1. Kiểm tra phải là Học viên đã đăng nhập (role 0)
        $studentId = ($_SESSION['user_role'] ?? -1) == 0 ? ($_SESSION['user_id'] ?? null) : null;

        if (!$studentId) {
            $this->index();
            return;
        }

        $categories = $this->categoryModel->getAll();
        $categoryId = $_GET['category_id'] ?? null;
        $keyword = $_GET['keyword'] ?? null; // <--- THÊM DÒNG NÀY ĐỂ LẤY KEYWORD

        // BẠN CẦN ĐẢM BẢO CourseModel có phương thức getUnenrolledCourses(studentId, categoryId, keyword)
        // SỬA: Thêm tham số $keyword vào lệnh gọi hàm Model
        $courses = $this->courseModel->getUnenrolledCourses($studentId, $categoryId, $keyword); // <--- SỬA TẠI ĐÂY

        // ... (Các phần còn lại giữ nguyên)

        $data = [
            'categories' => $categories,
            'courses' => $courses,
            'categoryId' => $categoryId,
            'keyword' => $keyword // <--- THÊM KEYWORD VÀO DATA ĐỂ VIEW HIỂN THỊ TRÊN THANH SEARCH
        ];

        $this->loadView('courses/index.php', $data);
    }

    // Hiển thị chi tiết khóa học + danh sách bài học (Pre-enrollment)
    public function detail($courseId = null)
    {
        if (empty($courseId)) {
            header('Location: ' . BASE_URL . '/courses');
            exit();
        }

        // SỬ DỤNG MODEL ĐÃ ĐƯỢC KHỞI TẠO TỪ CONSTRUCTOR
        $course = $this->courseModel->getPublicCourseDetailById($courseId);

        // Tùy chọn: Lấy danh sách bài học để hiển thị trong trang chi tiết
        $lessons = $this->lessonModel->getByCourseId($courseId);

        if (!$course) {
            http_response_code(404);
            echo "Lỗi 404: Không tìm thấy khóa học này hoặc khóa học chưa được phê duyệt.";
            exit();
        }

        // Truyền dữ liệu và Load View (Sử dụng $this->loadView cho nhất quán)
        $data = [
            'course' => $course,
            'lessons' => $lessons,
        ];

        $this->loadView('courses/detail.php', $data);
    }

    /**
     * PHƯƠNG THỨC: Hiển thị trang học (Xem bài học) cho Học viên
     * @param int $id là course_id
     */
    public function learn($id)
    {
        // 1. Kiểm tra đăng nhập và quyền (chỉ học viên mới được xem nội dung học)
        // Chú ý: Cần kiểm tra lại role Học viên, giả định là 0.
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 2) != 0) {
            header('Location: ' . BASE_URL . '/auth/login?msg=' . urlencode('Vui lòng đăng nhập với quyền Học viên để xem khóa học!'));
            exit();
        }
        $student_id = $_SESSION['user_id'];

        // 2. Lấy thông tin khóa học
        $course = $this->courseModel->getById($id);

        if (!$course) {
            http_response_code(404);
            echo "Lỗi 404: Khóa học không tồn tại!";
            return;
        }

        // =======================================================================
        // CẬP NHẬT QUAN TRỌNG: KIỂM TRA ĐĂNG KÝ (ENROLLMENT CHECK)
        // =======================================================================
        // BẠN CẦN ĐẢM BẢO: $this->courseModel->isEnrolled($id, $student_id) tồn tại
        // và trả về TRUE nếu học viên đã đăng ký, FALSE nếu chưa.
        // Thường hàm này nằm trong EnrollmentModel hoặc CourseModel có join với enrollment.
        if (!$this->courseModel->isEnrolled($id, $student_id)) {
            // Chuyển hướng về trang chi tiết công khai để học viên đăng ký
            header('Location: ' . BASE_URL . '/courses/detail/' . $id . '?msg=' . urlencode('Bạn chưa đăng ký khóa học này. Vui lòng đăng ký trước khi học!'));
            exit();
        }
        // =======================================================================


        // 3. Lấy danh sách các bài học (Lessons)
        $lessons = $this->lessonModel->getByCourseId($id);

        // 4. Xác định bài học hiện tại để hiển thị nội dung
        // Lấy lesson_id từ URL (?lesson_id=X), nếu không có, lấy bài học đầu tiên
        $current_lesson_id = $_GET['lesson_id'] ?? ($lessons[0]['id'] ?? null);
        $current_lesson = null;
        $lesson_content = 'Không có nội dung bài học.';

        if ($current_lesson_id) {
            $current_lesson = $this->lessonModel->getById($current_lesson_id);
            if ($current_lesson) {
                $lesson_content = $current_lesson['content'] ?? 'Không có nội dung bài học.';

                // TODO: (TIẾN ĐỘ) Gọi hàm đánh dấu bài học này là đã xem/đang xem.
                // Ví dụ: $this->enrollmentModel->markLessonCompleted($current_lesson_id, $student_id);
            }
        }

        $data = [
            'course' => $course,
            'lessons' => $lessons,
            'current_lesson' => $current_lesson,
            'lesson_content' => $lesson_content
        ];

        // Giả định view học tập là student/course/learn.php
        $this->loadView('student/course/learn.php', $data);
    }


    // =====================================================================
    // PHẦN 2: INSTRUCTOR DASHBOARD & CRUD
    // =====================================================================

    // Dashboard Giảng viên
    public function dashboard()
    {
        $instructor_id = $this->checkInstructorAccess();

        // Giả định courseModel->getAllByInstructorId trả về PDOStatement
        $courses_stmt = $this->courseModel->getAllByInstructorId($instructor_id);

        // Kiểm tra kết quả trước khi fetch
        if ($courses_stmt && $courses_stmt instanceof PDOStatement) {
            $courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $courses = [];
        }

        $totalCourses = count($courses);

        $pendingCourses = 0;
        $approvedCourses = 0;

        foreach ($courses as $course) {
            // Chú ý: Cần đảm bảo cột status trong DB là string 'pending'/'approved' hoặc INT 0/1.
            // Nếu DB là INT (0/1), cần sửa logic so sánh ở đây.
            if ($course['status'] == 'pending' || $course['status'] == 0)
                $pendingCourses++;
            if ($course['status'] == 'approved' || $course['status'] == 1)
                $approvedCourses++;
        }

        $data = [
            'courses' => $courses,
            'totalCourses' => $totalCourses,
            'pendingCourses' => $pendingCourses,
            'approvedCourses' => $approvedCourses
        ];

        $this->loadView('instructor/dashboard.php', $data);
    }

    // Quản lý khóa học của giảng viên
    public function manage()
    {
        $instructor_id = $this->checkInstructorAccess();

        // Lấy danh sách khóa học
        $courses_result = $this->courseModel->getAllByInstructorId($instructor_id);

        if ($courses_result && $courses_result instanceof PDOStatement) {
            $courses = $courses_result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $courses = [];
        }

        $data = ['courses' => $courses];
        $this->loadView('instructor/course/manage.php', $data);
    }

    // Tạo khóa học - GET REQUEST
    public function create()
    {
        $this->checkInstructorAccess();

        // Giả định categoryModel->getAll() trả về mảng/array
        $categories = $this->categoryModel->getAll();
        $error = $_GET['msg'] ?? null;

        $data = [
            'categories' => $categories,
            'error' => $error
        ];

        $this->loadView('instructor/course/create.php', $data);
    }

    // Lưu khóa học - POST REQUEST
    public function store()
    {
        $instructor_id = $this->checkInstructorAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/course/create');
            exit();
        }

        // Validate
        $errors = [];

        if (empty($_POST['title']) || strlen(trim($_POST['title'])) < 5) {
            $errors[] = "Tiêu đề phải có ít nhất 5 ký tự";
        }

        if (empty($_POST['description']) || strlen(trim($_POST['description'])) < 20) {
            $errors[] = "Mô tả phải có ít nhất 20 ký tự";
        }

        if (empty($_POST['category_id'])) {
            $errors[] = "Vui lòng chọn danh mục";
        }

        if (empty($_POST['level'])) {
            $errors[] = "Vui lòng chọn cấp độ";
        }

        $price_input = $_POST['price'] ?? '';
        $price = str_replace(['.', ',', ' '], '', $price_input);
        $price = floatval($price);

        if ($price_input === '') {
            $errors[] = "Vui lòng nhập giá";
        } else if ($price < 0) {
            $errors[] = "Giá không được âm";
        }

        if (empty($_POST['duration_weeks']) || $_POST['duration_weeks'] < 1 || $_POST['duration_weeks'] > 52) {
            $errors[] = "Thời lượng phải từ 1-52 tuần";
        }

        if (!empty($errors)) {
            $error_msg = implode(", ", $errors);
            header('Location: ' . BASE_URL . '/course/create?msg=' . urlencode($error_msg));
            exit();
        }

        // Xử lý upload ảnh
        $image_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            // Giả định ROOT_PATH đã được định nghĩa
            $target_dir = ROOT_PATH . "/assets/uploads/courses/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $file_type = $_FILES['image']['type'];
            $file_size = $_FILES['image']['size'];

            if (!in_array($file_type, $allowed_types)) {
                header('Location: ' . BASE_URL . '/course/create?msg=' . urlencode('Chỉ chấp nhận file ảnh (JPG, PNG, GIF)!'));
                exit();
            }

            if ($file_size > 2 * 1024 * 1024) {
                header('Location: ' . BASE_URL . '/course/create?msg=' . urlencode('File quá lớn (tối đa 2MB)!'));
                exit();
            }

            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $image_name = time() . '_' . uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $image_name;

            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_name = '';
            }
        }

        // Tạo khóa học
        try {
            $result = $this->courseModel->create(
                trim($_POST['title']),
                trim($_POST['description']),
                $instructor_id,
                (int) $_POST['category_id'],
                $price,
                (int) $_POST['duration_weeks'],
                $_POST['level'],
                $image_name
            );

            if ($result) {
                header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('✅ Tạo khóa học thành công!'));
            } else {
                header('Location: ' . BASE_URL . '/course/create?msg=' . urlencode('❌ Tạo khóa học thất bại! Vui lòng thử lại.'));
            }
        } catch (Exception $e) {
            header('Location: ' . BASE_URL . '/course/create?msg=' . urlencode('❌ Lỗi hệ thống: ' . $e->getMessage()));
        }
        exit();
    }

    // Chỉnh sửa khóa học
    public function edit($id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('Không có quyền chỉnh sửa!'));
            exit();
        }

        $categories = $this->categoryModel->getAll();
        $error = $_GET['msg'] ?? null;

        $data = [
            'course' => $course,
            'categories' => $categories,
            'error' => $error
        ];
        $this->loadView('instructor/course/edit.php', $data);
    }

    // Cập nhật khóa học
    public function update($id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('Không có quyền!'));
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/course/edit/' . $id);
            exit();
        }

        $image_name = $course['image'];

        // Upload ảnh mới
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = ROOT_PATH . "/assets/uploads/courses/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $file_type = $_FILES['image']['type'];

            if (in_array($file_type, $allowed_types) && $_FILES['image']['size'] <= 2 * 1024 * 1024) {
                // Xóa ảnh cũ
                if ($course['image'] && file_exists($target_dir . $course['image'])) {
                    unlink($target_dir . $course['image']);
                }

                $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                $image_name = time() . '_' . uniqid() . '.' . $file_extension;
                $target_file = $target_dir . $image_name;

                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            } else {
                header('Location: ' . BASE_URL . '/course/edit/' . $id . '?msg=' . urlencode('File ảnh không hợp lệ hoặc quá lớn!'));
                exit();
            }
        }

        // Xóa ảnh nếu có flag
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            if ($course['image'] && file_exists(ROOT_PATH . "/assets/uploads/courses/" . $course['image'])) {
                unlink(ROOT_PATH . "/assets/uploads/courses/" . $course['image']);
            }
            $image_name = '';
        }

        // Xử lý giá
        $price = str_replace(['.', ',', ' '], '', $_POST['price']);
        $price = floatval($price);

        // Validate
        $errors = [];
        if (empty($_POST['title']) || strlen(trim($_POST['title'])) < 5) {
            $errors[] = "Tiêu đề phải có ít nhất 5 ký tự";
        }
        if (empty($_POST['description']) || strlen(trim($_POST['description'])) < 20) {
            $errors[] = "Mô tả phải có ít nhất 20 ký tự";
        }
        if (empty($_POST['category_id'])) {
            $errors[] = "Vui lòng chọn danh mục";
        }
        if (empty($_POST['duration_weeks']) || $_POST['duration_weeks'] < 1 || $_POST['duration_weeks'] > 52) {
            $errors[] = "Thời lượng phải từ 1-52 tuần";
        }

        if (!empty($errors)) {
            $error_msg = implode(", ", $errors);
            header('Location: ' . BASE_URL . '/course/edit/' . $id . '?msg=' . urlencode($error_msg));
            exit();
        }

        $result = $this->courseModel->update(
            $id,
            trim($_POST['title']),
            trim($_POST['description']),
            (int) $_POST['category_id'],
            $price,
            (int) $_POST['duration_weeks'],
            $_POST['level'],
            $image_name
        );

        if ($result) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('✅ Cập nhật thành công!'));
        } else {
            header('Location: ' . BASE_URL . '/course/edit/' . $id . '?msg=' . urlencode('❌ Cập nhật thất bại!'));
        }
        exit();
    }

    // Xóa khóa học
    public function delete($id)
    {
        $instructor_id = $this->checkInstructorAccess();
        $course = $this->courseModel->getById($id);

        if (!$course || $course['instructor_id'] != $instructor_id) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('Không có quyền xóa!'));
            exit();
        }

        // Xóa ảnh
        if ($course['image']) {
            $image_path = ROOT_PATH . "/assets/uploads/courses/" . $course['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        if ($this->courseModel->delete($id)) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('✅ Xóa khóa học thành công!'));
        } else {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('❌ Xóa thất bại!'));
        }
        exit();
    }

    // =====================================================================
    // PHẦN 3: CHUYỂN HƯỚNG CÁC ROUTE CŨ (Đảm bảo logic header/exit)
    // =====================================================================

    // ... (Giữ nguyên tất cả các hàm chuyển hướng của bạn)

    // Hàm chuyển hướng mẫu (cần đảm bảo logic header/exit được giữ nguyên)
    public function lessons($course_id)
    {
        header('Location: ' . BASE_URL . '/lesson/manage/' . $course_id);
        exit();
    }

    // ... (Tất cả các hàm chuyển hướng khác giữ nguyên)
    public function createLesson($course_id)
    {
        header('Location: ' . BASE_URL . '/lesson/create/' . $course_id);
        exit();
    }
    public function editLesson($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/edit/' . $lesson_id);
        exit();
    }
    public function deleteLesson($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/delete/' . $lesson_id);
        exit();
    }
    public function updateLesson($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/update/' . $lesson_id);
        exit();
    }
    public function storeLesson($course_id)
    {
        header('Location: ' . BASE_URL . '/lesson/store/' . $course_id);
        exit();
    }
    public function lessonMaterials($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/materials/' . $lesson_id);
        exit();
    }
    public function lessonPreview($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/preview/' . $lesson_id);
        exit();
    }
    public function moveUp($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/moveUp/' . $lesson_id);
        exit();
    }
    public function moveDown($lesson_id)
    {
        header('Location: ' . BASE_URL . '/lesson/moveDown/' . $lesson_id);
        exit();
    }
    public function reorder($course_id)
    {
        header('Location: ' . BASE_URL . '/lesson/reorder/' . $course_id);
        exit();
    }
    public function export($course_id)
    {
        header('Location: ' . BASE_URL . '/lesson/export/' . $course_id);
        exit();
    }
    // Các alias cho phương thức chính
    public function storeCourse()
    {
        $this->store();
    }
    public function updateCourse($id)
    {
        $this->update($id);
    }
    public function deleteCourse($id)
    {
        $this->delete($id);
    }
    // Thêm các phương thức chuyển hướng cho instructor cũ
    public function instructorDashboard()
    {
        header('Location: ' . BASE_URL . '/instructor/dashboard');
        exit();
    }
    public function instructorCourses()
    {
        header('Location: ' . BASE_URL . '/course/manage');
        exit();
    }
    public function instructorCreateCourse()
    {
        header('Location: ' . BASE_URL . '/course/create');
        exit();
    }
    public function instructorEditCourse($id)
    {
        header('Location: ' . BASE_URL . '/course/edit/' . $id);
        exit();
    }
    public function instructorDeleteCourse($id)
    {
        header('Location: ' . BASE_URL . '/course/delete/' . $id);
        exit();
    }
    // Chuyển hướng cho enrollment và progress
    public function enrollments($course_id = null)
    {
        if ($course_id) {
            header('Location: ' . BASE_URL . '/instructor/enrollments/' . $course_id);
        } else {
            header('Location: ' . BASE_URL . '/instructor/enrollments');
        }
        exit();
    }
    public function progress($course_id = null)
    {
        if ($course_id) {
            header('Location: ' . BASE_URL . '/instructor/progress/' . $course_id);
        } else {
            header('Location: ' . BASE_URL . '/instructor/progress');
        }
        exit();
    }


    /**
     * CẬP NHẬT: Tìm kiếm khóa học (Có hỗ trợ enrolled courses)
     */
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = $_GET['category_id'] ?? null;

        $categories = $this->categoryModel->getAll();

        // Lấy danh sách ID khóa học đã đăng ký
        $enrolledCourseIds = [];
        $isStudentLoggedIn = isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? -1) == 0;

        if ($isStudentLoggedIn) {
            if (!isset($this->enrollmentModel)) {
                require_once MODELS_PATH . '/Enrollment.php';
                $this->enrollmentModel = new Enrollment($this->db);
            }

            $enrollments = $this->enrollmentModel->getEnrolledCourses($_SESSION['user_id']);
            $enrolledCourseIds = array_column($enrollments, 'course_id');
        }

        // Tìm kiếm với bộ lọc
        if ($keyword && $categoryId) {
            $courses = $this->courseModel->searchWithCategory($keyword, $categoryId);
        } elseif ($keyword) {
            $courses = $this->courseModel->search($keyword);
        } elseif ($categoryId) {
            $courses = $this->courseModel->getByCategory($categoryId);
        } else {
            $courses = $this->courseModel->getAllPublic();
        }

        $data = [
            'categories' => $categories,
            'courses' => $courses,
            'keyword' => $keyword,
            'categoryId' => $categoryId,
            'enrolledCourseIds' => $enrolledCourseIds // THÊM BIẾN NÀY
        ];

        $this->loadView('courses/index.php', $data);
    }
}
