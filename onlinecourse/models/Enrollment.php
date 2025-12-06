<?php
require_once 'config/Database.php';

class Enrollment
{
    private $conn;
    private $table = 'enrollments';
    private $course_table = 'courses';
    private $user_table = 'users';

    public function __construct()
    {
        // Khởi tạo kết nối CSDL
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. Kiểm tra xem học viên đã đăng ký khóa học này chưa
    // Trả về true nếu đã đăng ký, false nếu chưa
    public function isEnrolled($course_id, $student_id)
    {
        $query = "SELECT id FROM " . $this->table . " 
                  WHERE course_id = :course_id AND student_id = :student_id 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // 2. Đăng ký khóa học mới
    public function create($course_id, $student_id)
    {
        // Mặc định khi mới đăng ký: status = 'active', progress = 0
        $query = "INSERT INTO " . $this->table . " 
                  (course_id, student_id, enrolled_date, status, progress) 
                  VALUES (:course_id, :student_id, NOW(), 'active', 0)";

        $stmt = $this->conn->prepare($query);

        // Binding parameters
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. Lấy danh sách khóa học của một học viên (Trang 'Khóa học của tôi')
    // KẾT HỢP (JOIN) với bảng courses và users để lấy tên khóa học và tên giảng viên
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
                    u.fullname as instructor_name
                  FROM " . $this->table . " e
                  JOIN " . $this->course_table . " c ON e.course_id = c.id
                  JOIN " . $this->user_table . " u ON c.instructor_id = u.id
                  WHERE e.student_id = :student_id
                  ORDER BY e.enrolled_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        // SỬA Ở ĐÂY: Trả về fetchAll để lấy mảng dữ liệu
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Cập nhật tiến độ học tập (Dùng khi học viên xem xong bài học)
    public function updateProgress($course_id, $student_id, $progress)
    {
        $query = "UPDATE " . $this->table . " 
                  SET progress = :progress 
                  WHERE course_id = :course_id AND student_id = :student_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':progress', $progress, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 5. Hủy đăng ký (Nếu cần tính năng cho phép học viên tự rời khóa học)
    // Thường thì sẽ update status thành 'dropped' thay vì xóa hẳn
    public function dropCourse($course_id, $student_id)
    {
        $query = "UPDATE " . $this->table . " 
                  SET status = 'dropped' 
                  WHERE course_id = :course_id AND student_id = :student_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>