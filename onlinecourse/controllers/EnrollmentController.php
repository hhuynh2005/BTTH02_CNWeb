<?php
// File: controllers/EnrollmentController.php - HOÀN THIỆN CHỨC NĂNG VÀ KHẮC PHỤC LỖI KHỞI TẠO

// Load Models & Config cần thiết
// Cần đảm bảo các file này tồn tại và có class tương ứng
require_once MODELS_PATH . '/Enrollment.php';
require_once MODELS_PATH . '/Course.php';
require_once MODELS_PATH . '/Lesson.php';
require_once MODELS_PATH . '/User.php';
require_once CONFIG_PATH . '/Database.php'; // Bắt buộc phải có để khởi tạo $db

class EnrollmentController
{
    private $enrollmentModel;
    private $courseModel;
    private $userModel;
    private $lessonModel;
    private $db; // Thuộc tính để lưu trữ kết nối DB

    public function __construct()
    {
        // 1. KHỞI TẠO KẾT NỐI DATABASE
        $database = new Database();
        $this->db = $database->getConnection(); // LƯU KẾT NỐI VÀO THUỘC TÍNH
        $db = $this->db;

        // 2. KHỞI TẠO CÁC MODEL VÀ TRUYỀN KẾT NỐI ($db) - KHẮC PHỤC ArgumentCountError
        $this->enrollmentModel = new Enrollment($db);

        if (!class_exists('Course')) {
            require_once MODELS_PATH . '/Course.php';
        }
        $this->courseModel = new Course($db);

        if (!class_exists('User')) {
            require_once MODELS_PATH . '/User.php';
        }
        $this->userModel = new User($db);

        if (!class_exists('Lesson')) {
            require_once MODELS_PATH . '/Lesson.php';
        }
        $this->lessonModel = new Lesson($db);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Helper method: Load view với data (Đã sửa lỗi thiếu .php)
     */
    private function loadView($viewPath, $data = [])
    {
        extract($data);
        $fullPath = VIEWS_PATH . '/' . $viewPath;

        // SỬA: Đảm bảo có .php
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

    /**
     * Helper method: Kiểm tra quyền Học viên (role 0 hoặc cao hơn)
     */
    private function checkStudentAccess()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? -1) != 0) {
            // Chuyển hướng nếu chưa đăng nhập hoặc không phải học viên
            header('Location: ' . BASE_URL . '/auth/login?msg=' . urlencode('Bạn cần đăng nhập tài khoản Học viên để xem các khóa học đã đăng ký!'));
            exit();
        }
        return $_SESSION['user_id'];
    }

    /**
     * Helper method: Kiểm tra quyền Giảng viên (role 1)
     */
    private function checkInstructorAccess()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 0) != 1) {
            header('Location: ' . BASE_URL . '/auth/login?msg=' . urlencode('Bạn cần đăng nhập với quyền Giảng viên để thực hiện thao tác này!'));
            exit();
        }
        return $_SESSION['user_id'];
    }

    // =====================================================================
    // PHẦN 1: CHỨC NĂNG HỌC VIÊN (Student) - Code phần này Trường
    // =====================================================================

    /**
     * 1. Đăng ký khóa học (Xử lý POST từ form chi tiết khóa học)
     */
    public function register()
    {
        $studentId = $this->checkStudentAccess();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
            $courseId = $_POST['course_id'];

            if ($this->enrollmentModel->isEnrolled($courseId, $studentId)) {
                header('Location: ' . BASE_URL . '/student/my-courses?msg=' . urlencode('Bạn đã đăng ký khóa học này rồi!'));
                return;
            }

            if ($this->enrollmentModel->create($courseId, $studentId)) {
                header('Location: ' . BASE_URL . '/student/my-courses?msg=' . urlencode('✅ Đăng ký thành công!'));
            } else {
                header('Location: ' . BASE_URL . '/courses/detail/' . $courseId . '?msg=' . urlencode('❌ Đăng ký thất bại!'));
            }
        }
        exit();
    }

    /**
     * 2. Danh sách khóa học của tôi (My Courses) - CẬP NHẬT VIEW
     */
    public function myCourses()
    {
        $userId = $this->checkStudentAccess();
        // Lấy danh sách khóa học kèm thông tin chi tiết (giảng viên, tiến độ...)
        $enrolledCourses = $this->enrollmentModel->getEnrolledCourses($userId);

        $data = ['enrolledCourses' => $enrolledCourses];
        // Sử dụng view mới: student/my_courses.php
        $this->loadView('student/my_courses.php', $data);
    }

    /**
     * 3. Dashboard Học viên - CẬP NHẬT LOGIC THỐNG KÊ
     */
    public function dashboard()
    {
        $studentId = $this->checkStudentAccess();

        // Lấy dữ liệu cần thiết cho dashboard
        $enrolledCourses = $this->enrollmentModel->getEnrolledCourses($studentId);

        // Tính toán thống kê (Stats)
        $stats = [
            'total_courses' => count($enrolledCourses),
            'in_progress' => 0,
            'completed' => 0,
            'avg_progress' => 0
        ];

        $totalProgress = 0;
        foreach ($enrolledCourses as $course) {
            $prog = isset($course['progress']) ? $course['progress'] : 0;
            if ($prog == 100) {
                $stats['completed']++;
            } else {
                $stats['in_progress']++;
            }
            $totalProgress += $prog;
        }

        if ($stats['total_courses'] > 0) {
            $stats['avg_progress'] = round($totalProgress / $stats['total_courses']);
        }

        // Lấy bài học sắp tới (Logic giả lập: Lấy bài đầu tiên của khóa học mới nhất)
        $upcomingLessons = [];
        if (!empty($enrolledCourses)) {
            $firstCourse = $enrolledCourses[0];
            $lessons = $this->lessonModel->getLessonsByCourse($firstCourse['course_id'] ?? $firstCourse['id']);

            if (!empty($lessons)) {
                $upcomingLessons[] = [
                    'title' => $lessons[0]['title'],
                    'course_title' => $firstCourse['title'],
                    'start_time' => date('Y-m-d H:i:s') // Mock data
                ];
            }
        }

        $data = [
            'student' => $_SESSION,
            'stats' => $stats,
            'enrolledCourses' => $enrolledCourses,
            'upcomingLessons' => $upcomingLessons
        ];

        $this->loadView('student/dashboard.php', $data);
    }

    /**
     * 4. Theo dõi tiến độ chung (Progress Tracking List)
     * GIẢ ĐỊNH: Router ánh xạ URL /student/course_progress tới phương thức này.
     */
    public function progressList()
    {
        $studentId = $this->checkStudentAccess();

        // 1. Lấy danh sách khóa học kèm thông tin chi tiết tiến độ
        $myCourses = $this->enrollmentModel->getEnrolledCourses($studentId);

        $data = ['myCourses' => $myCourses];

        // 2. GỌI VIEW ĐÚNG: student/course_progress.php
        $this->loadView('student/course_progress.php', $data);
    }

    /**
     * 5. Màn hình học tập (Learning Interface)
     */
    public function learning($courseId)
    {
        $studentId = $this->checkStudentAccess();

        // Kiểm tra xem học viên có đăng ký khóa này không
        if (!$this->enrollmentModel->isEnrolled($courseId, $studentId)) {
            header('Location: ' . BASE_URL . '/courses/detail/' . $courseId);
            exit();
        }

        // Lấy thông tin khóa học
        $course = $this->courseModel->getById($courseId);
        // Lấy thông tin enrollment để hiển thị progress bar
        $enrollment = $this->enrollmentModel->getEnrollment($studentId, $courseId);

        // 1. Lấy danh sách bài học (CHỈ LÀ LIST)
        $lessonsResult = $this->lessonModel->getAllByCourseId($courseId);
        $lessons = [];
        if ($lessonsResult && $lessonsResult instanceof PDOStatement) {
            $lessons = $lessonsResult->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Nếu Model trả về mảng trực tiếp
            $lessons = $lessonsResult;
        }

        $currentLessonId = $_GET['lesson_id'] ?? null;
        $currentLesson = null;

        // 2. Xác định Lesson ID để lấy chi tiết
        if (!$currentLessonId && !empty($lessons)) {
            // Nếu không có lesson_id trên URL, sử dụng ID của bài học đầu tiên
            $currentLessonId = $lessons[0]['id'];
        }

        // 3. Lấy chi tiết bài học (đầy đủ CONTENT và VIDEO URL)
        if ($currentLessonId) {
            // Dùng hàm getById để lấy tất cả các trường cần thiết
            $currentLesson = $this->lessonModel->getById($currentLessonId);
        }

        // 4. Chuẩn bị dữ liệu
        $data = [
            'courseDetail' => array_merge($course, $enrollment),
            'lessons' => $lessons,
            'currentLesson' => $currentLesson,
            'progressDetail' => $enrollment['progress'] ?? 0,
        ];

        // Load view học tập
        $this->loadView('courses/detail.php', $data);
    }

    /**
     * 6. Xử lý hoàn thành bài học (AJAX hoặc Form POST) - MỚI BỔ SUNG
     */
    public function completeLesson()
    {
        $studentId = $this->checkStudentAccess();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $courseId = $_POST['course_id'];

            // Lấy tổng số bài học
            $lessons = $this->lessonModel->getAllByCourseId($courseId);
            if (is_object($lessons)) {
                $lessons = $lessons->fetchAll(PDO::FETCH_ASSOC);
            }
            $totalLessons = count($lessons);

            // Lấy progress hiện tại
            $enrollment = $this->enrollmentModel->getEnrollment($studentId, $courseId);
            $currentProgress = $enrollment['progress'];

            // Tính toán progress mới (giả sử mỗi lần click tăng 1 bài)
            $newProgress = min(100, $currentProgress + (100 / ($totalLessons > 0 ? $totalLessons : 1)));

            // Cập nhật vào DB
            $this->enrollmentModel->updateProgress($studentId, $courseId, $newProgress);

            // Redirect lại trang học
            header('Location: ' . BASE_URL . '/student/course/' . $courseId . '?msg=completed');
        }
    }

    // =====================================================================
    // PHẦN 2: CHỨC NĂNG GIẢNG VIÊN (Instructor)
    // =====================================================================

    /**
     * 5. Xem danh sách học viên đăng ký cho khóa học
     */
    public function listStudents($courseId)
    {
        $instructorId = $this->checkInstructorAccess();

        $course = $this->courseModel->getById($courseId);
        if (!$course || ($course['instructor_id'] ?? 0) != $instructorId) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('Không có quyền xem danh sách học viên của khóa học này!'));
            exit();
        }

        $students = $this->enrollmentModel->getStudentsByCourseId($courseId);

        $data = [
            'course' => $course,
            'students' => $students
        ];

        $this->loadView('instructor/students/list.php', $data);
    }

    /**
     * 6. Tổng quan học viên cho Giảng viên
     */
    public function instructorEnrollmentsSummary()
    {
        $instructor_id = $this->checkInstructorAccess();

        $allEnrollments = $this->enrollmentModel->getAllStudentsByInstructor($instructor_id);

        $data = [
            'allEnrollments' => $allEnrollments,
            'totalStudents' => count(array_unique(array_column($allEnrollments, 'student_id')))
        ];

        $this->loadView('instructor/students/summary.php', $data);
    }

    /**
     * 7. Tổng quan Tiến độ Khóa học
     */
    public function progressOverview()
    {
        $instructorId = $this->checkInstructorAccess();

        // 1. Lấy dữ liệu tổng quan hiệu suất khóa học
        $courseSummary = $this->courseModel->getPerformanceSummaryByInstructor($instructorId);

        // 2. Tính toán các chỉ số thống kê nhanh (KPIs)
        $enrollmentCount = 0;
        $totalCompleted = 0;
        $pendingCourses = 0;

        foreach ($courseSummary as $course) {
            $enrollmentCount += $course['total_students'];
            $totalCompleted += $course['completed_count'];

            if ($course['status'] === 'pending' || $course['status'] === 'draft') {
                $pendingCourses++;
            }
        }

        // Lấy số học viên duy nhất chính xác hơn
        $allEnrollments = $this->enrollmentModel->getAllStudentsByInstructor($instructorId);
        $totalStudents = count(array_unique(array_column($allEnrollments, 'student_id')));

        $data = [
            'totalStudents' => $totalStudents,
            'enrollmentCount' => $enrollmentCount,
            'pendingCourses' => $pendingCourses,
            'courseSummary' => $courseSummary,
            // Tính toán tỷ lệ hoàn thành trung bình
            'avgCompletionRate' => $enrollmentCount > 0 ? round(($totalCompleted / $enrollmentCount) * 100) : 0,
        ];

        // Tải View đã thiết kế
        $this->loadView('instructor/overview.php', $data);
    }

    /**
     * 8. Chi tiết tiến độ của TỪNG học viên
     */
    public function progressDetail($course_id, $student_id)
    {
        $instructorId = $this->checkInstructorAccess();

        // 1. Lấy thông tin Khóa học và kiểm tra quyền sở hữu
        $course = $this->courseModel->getById((int) $course_id);
        if (!$course || ($course['instructor_id'] ?? 0) != $instructorId) {
            header('Location: ' . BASE_URL . '/course/manage?msg=' . urlencode('Không có quyền xem chi tiết tiến độ này!'));
            exit();
        }

        // 2. Lấy thông tin Học viên & Tiến độ
        $studentInfo = $this->userModel->getById((int) $student_id);
        $lessonsProgress = $this->lessonModel->getLessonsWithProgress($course_id, $student_id);

        // 3. Chuẩn bị dữ liệu và tải View
        $data = [
            'course' => $course,
            'studentInfo' => $studentInfo,
            'lessonsProgress' => $lessonsProgress,
        ];

        $this->loadView('instructor/students/progress_detail.php', $data);
    }
}