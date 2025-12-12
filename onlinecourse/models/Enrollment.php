<?php
// File: models/Enrollment.php - PHIÊN BẢN HOÀN CHỈNH

require_once 'config/Database.php';

class Enrollment
{
    private $conn;
    private $table = 'enrollments';
    private $course_table = 'courses';
    private $user_table = 'users';
    private $lesson_table = 'lessons';

    public function __construct($db = null)
    {
        // Hỗ trợ cả 2 cách: truyền $db từ Controller hoặc tự khởi tạo
        if ($db !== null) {
            $this->conn = $db;
        } else {
            $database = new Database();
            $this->conn = $database->getConnection();
        }
    }

    // =====================================================================
    // PHẦN 1: CHỨC NĂNG CƠ BẢN - ENROLLMENT MANAGEMENT
    // =====================================================================

    /**
     * Kiểm tra xem học viên đã đăng ký khóa học này chưa
     * @param int $course_id
     * @param int $student_id (hoặc user_id)
     * @return bool
     */
    public function isEnrolled($course_id, $student_id)
    {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE course_id = :course_id 
                  AND student_id = :student_id 
                  AND status != 'dropped'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Đăng ký khóa học mới (Create Enrollment)
     * @param int $course_id
     * @param int $student_id
     * @return bool
     */
    public function create($course_id, $student_id)
    {
        // Kiểm tra trùng lặp
        if ($this->isEnrolled($course_id, $student_id)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " 
                  (course_id, student_id, enrolled_date, status, progress) 
                  VALUES 
                  (:course_id, :student_id, NOW(), 'active', 0)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Lấy thông tin chi tiết một enrollment cụ thể
     * @param int $student_id
     * @param int $course_id
     * @return array|false
     */
    public function getEnrollment($student_id, $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE student_id = :student_id 
                  AND course_id = :course_id 
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =====================================================================
    // PHẦN 2: LẤY DANH SÁCH KHÓA HỌC ĐÃ ĐĂNG KÝ
    // =====================================================================

    /**
     * Lấy danh sách khóa học của một học viên (Dùng trong my_courses và dashboard)
     * @param int $student_id
     * @return array
     */
    public function getCoursesByStudent($student_id)
    {
        $query = "SELECT 
                    e.id as enrollment_id,
                    e.enrolled_date,
                    e.status as enrollment_status,
                    e.progress,
                    c.id as course_id,
                    c.title,
                    c.image,
                    c.description,
                    c.level,
                    c.price,
                    c.duration_weeks,
                    u.fullname as instructor_name,
                    (SELECT COUNT(*) FROM " . $this->lesson_table . " WHERE course_id = c.id) as lesson_count
                  FROM " . $this->table . " e
                  INNER JOIN " . $this->course_table . " c ON e.course_id = c.id
                  INNER JOIN " . $this->user_table . " u ON c.instructor_id = u.id
                  WHERE e.student_id = :student_id
                  AND e.status = 'active'
                  ORDER BY e.enrolled_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Alias của getCoursesByStudent - Để tương thích với Controller
     * @param int $studentId
     * @return array
     */
    public function getEnrolledCourses($studentId)
    {
        return $this->getCoursesByStudent($studentId);
    }

    /**
     * Lấy danh sách ID khóa học đã đăng ký (Dùng cho kiểm tra nhanh)
     * @param int $student_id
     * @return array Mảng các course_id
     */
    public function getEnrolledCourseIds($student_id)
    {
        $query = "SELECT course_id 
                  FROM " . $this->table . " 
                  WHERE student_id = :student_id 
                  AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_column($results, 'course_id');
    }

    // =====================================================================
    // PHẦN 3: CẬP NHẬT TIẾN ĐỘ & TRẠNG THÁI
    // =====================================================================

    /**
     * Cập nhật tiến độ học tập
     * @param int $student_id
     * @param int $course_id
     * @param int $progress (0-100)
     * @return bool
     */
    public function updateProgress($student_id, $course_id, $progress)
    {
        // Validate progress
        $progress = max(0, min(100, (int) $progress));

        // Nếu progress = 100, tự động cập nhật status = 'completed'
        $status = ($progress == 100) ? 'completed' : 'active';

        $query = "UPDATE " . $this->table . " 
                  SET progress = :progress,
                      status = :status,
                      updated_at = NOW()
                  WHERE course_id = :course_id 
                  AND student_id = :student_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':progress', $progress, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Hủy đăng ký khóa học
     * @param int $course_id
     * @param int $student_id
     * @return bool
     */
    public function dropCourse($course_id, $student_id)
    {
        $query = "UPDATE " . $this->table . " 
                  SET status = 'dropped',
                      updated_at = NOW()
                  WHERE course_id = :course_id 
                  AND student_id = :student_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // =====================================================================
    // PHẦN 4: CHỨC NĂNG CHO GIẢNG VIÊN (INSTRUCTOR)
    // =====================================================================

    /**
     * Lấy danh sách học viên đăng ký một khóa học cụ thể
     * @param int $course_id
     * @return array
     */
    public function getStudentsByCourseId($course_id)
    {
        $query = "SELECT 
                    e.id as enrollment_id,
                    e.student_id,
                    e.enrolled_date,
                    e.status,
                    e.progress,
                    u.fullname,
                    u.email,
                    u.phone
                  FROM " . $this->table . " e
                  INNER JOIN " . $this->user_table . " u ON e.student_id = u.id
                  WHERE e.course_id = :course_id
                  ORDER BY e.enrolled_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy tất cả học viên đăng ký các khóa học của một giảng viên
     * @param int $instructor_id
     * @return array
     */
    public function getAllStudentsByInstructor($instructor_id)
    {
        $query = "SELECT 
                    e.id as enrollment_id,
                    e.student_id,
                    e.enrolled_date,
                    e.status AS enrollment_status,
                    e.progress,
                    u.fullname AS student_name,
                    u.email AS student_email,
                    c.id AS course_id,
                    c.title AS course_title
                  FROM " . $this->table . " e
                  INNER JOIN " . $this->user_table . " u ON e.student_id = u.id
                  INNER JOIN " . $this->course_table . " c ON e.course_id = c.id
                  WHERE c.instructor_id = :instructor_id
                  ORDER BY c.title ASC, e.enrolled_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm số lượng học viên đăng ký cho một khóa học
     * @param int $course_id
     * @return int
     */
    public function countStudentsByCourse($course_id)
    {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table . " 
                  WHERE course_id = :course_id 
                  AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Lấy tiến độ trung bình của một khóa học
     * @param int $course_id
     * @return float
     */
    public function getAverageProgressByCourse($course_id)
    {
        $query = "SELECT AVG(progress) as avg_progress 
                  FROM " . $this->table . " 
                  WHERE course_id = :course_id 
                  AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round((float) ($result['avg_progress'] ?? 0), 2);
    }

    // =====================================================================
    // PHẦN 5: THỐNG KÊ & BÁO CÁO
    // =====================================================================

    /**
     * Lấy xu hướng đăng ký theo tháng (12 tháng gần nhất)
     * @return array
     */
    public function getEnrollmentTrends()
    {
        $query = "SELECT 
                    DATE_FORMAT(enrolled_date, '%Y-%m') AS month,
                    COUNT(*) AS count
                  FROM " . $this->table . "
                  WHERE enrolled_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  GROUP BY month
                  ORDER BY month DESC
                  LIMIT 12";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy thống kê tổng quan của học viên
     * @param int $student_id
     * @return array
     */
    public function getStudentStatistics($student_id)
    {
        $query = "SELECT 
                    COUNT(*) as total_courses,
                    SUM(CASE WHEN progress = 100 THEN 1 ELSE 0 END) as completed_courses,
                    SUM(CASE WHEN progress > 0 AND progress < 100 THEN 1 ELSE 0 END) as in_progress,
                    AVG(progress) as avg_progress
                  FROM " . $this->table . "
                  WHERE student_id = :student_id
                  AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_courses' => (int) ($result['total_courses'] ?? 0),
            'completed_courses' => (int) ($result['completed_courses'] ?? 0),
            'in_progress' => (int) ($result['in_progress'] ?? 0),
            'avg_progress' => round((float) ($result['avg_progress'] ?? 0), 2)
        ];
    }

    /**
     * Lấy top học viên có tiến độ cao nhất trong một khóa học
     * @param int $course_id
     * @param int $limit
     * @return array
     */
    public function getTopStudentsByCourse($course_id, $limit = 10)
    {
        $query = "SELECT 
                    e.student_id,
                    e.progress,
                    e.enrolled_date,
                    u.fullname,
                    u.email
                  FROM " . $this->table . " e
                  INNER JOIN " . $this->user_table . " u ON e.student_id = u.id
                  WHERE e.course_id = :course_id
                  AND e.status = 'active'
                  ORDER BY e.progress DESC, e.enrolled_date ASC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra xem enrollment có tồn tại không (bằng ID)
     * @param int $enrollment_id
     * @return bool
     */
    public function exists($enrollment_id)
    {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $enrollment_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    /**
     * Xóa enrollment (Admin only - Cẩn thận khi dùng)
     * @param int $enrollment_id
     * @return bool
     */
    public function delete($enrollment_id)
    {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $enrollment_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Lấy danh sách enrollment với phân trang
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getAllWithPagination($page = 1, $limit = 20)
    {
        $offset = ($page - 1) * $limit;

        $query = "SELECT 
                    e.*,
                    u.fullname as student_name,
                    c.title as course_title
                  FROM " . $this->table . " e
                  INNER JOIN " . $this->user_table . " u ON e.student_id = u.id
                  INNER JOIN " . $this->course_table . " c ON e.course_id = c.id
                  ORDER BY e.enrolled_date DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Đếm tổng số enrollment (Dùng cho phân trang)
     * @return int
     */
    public function getTotalCount()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
}