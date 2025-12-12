<?php
// File: models/Course.php - HOÀN THIỆN VÀ KHẮC PHỤC LỖI SQL

// KHÔNG cần require Database.php nếu bạn dùng Autoloading hoặc file được require trong Controller
// Nếu bạn chưa dùng Autoloading, hãy đảm bảo Database.php được include ở đâu đó trước khi chạy.

class Course
{
    private $conn;
    private $table = 'courses';
    private $category_table = 'categories';
    private $enrollment_table = 'enrollments'; // <-- THÊM BẢNG ENROLLMENTS

    // Đã sửa để nhận kết nối DB, không tự khởi tạo
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * ============================
     * CRUD DÀNH CHO GIẢNG VIÊN
     * ============================
     */

    // Lấy tất cả danh mục (dùng cho form tạo/sửa)
    public function getAllCategories()
    {
        $query = "SELECT id, name FROM " . $this->category_table . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo khóa học mới
    public function create(string $title, string $desc, int $instructor_id, int $category_id, float $price, int $duration, string $level, string $image)
    {
        // Giả định status 0 = pending/draft
        $status = 0;

        $query = "INSERT INTO " . $this->table . " 
                  (title, description, instructor_id, category_id, price, duration_weeks, level, image, status) 
                  VALUES (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image, :status)";

        $stmt = $this->conn->prepare($query);

        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_desc = htmlspecialchars(strip_tags($desc));
        $clean_level = htmlspecialchars(strip_tags($level));
        $clean_image = htmlspecialchars(strip_tags($image));

        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':description', $clean_desc);
        $stmt->bindParam(':instructor_id', $instructor_id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $duration);
        $stmt->bindParam(':level', $clean_level);
        $stmt->bindParam(':image', $clean_image);
        $stmt->bindParam(':status', $status);

        return $stmt->execute();
    }

    // Lấy danh sách khóa học theo giảng viên
    public function getAllByInstructorId(int $instructor_id)
    {
        $query = "SELECT c.*, cat.name AS category_name 
                  FROM " . $this->table . " c
                  JOIN " . $this->category_table . " cat ON c.category_id = cat.id
                  WHERE c.instructor_id = :instructor_id
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':instructor_id', $instructor_id);
        $stmt->execute();
        return $stmt;
    }

    // Lấy 1 khóa học theo ID
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật khóa học
    public function update(int $id, string $title, string $desc, int $category_id, float $price, int $duration, string $level, string $image = null)
    {
        $set_image_clause = $image ? ", image = :image" : "";

        $query = "UPDATE " . $this->table . "
                  SET title = :title,
                      description = :description,
                      category_id = :category_id,
                      price = :price,
                      duration_weeks = :duration_weeks,
                      level = :level
                      $set_image_clause
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_desc = htmlspecialchars(strip_tags($desc));
        $clean_level = htmlspecialchars(strip_tags($level));

        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':description', $clean_desc);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $duration);
        $stmt->bindParam(':level', $clean_level);
        $stmt->bindParam(':id', $id);

        if ($image) {
            $clean_image = htmlspecialchars(strip_tags($image));
            $stmt->bindParam(':image', $clean_image);
        }

        return $stmt->execute();
    }

    // Xóa khóa học
    public function delete(int $id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    /*
     * ====================================
     * PHÂN HỆ STUDENT VIEW (nhóm Trường)
     * ====================================
     */

    // Lấy danh sách khóa học công khai
    public function getAllPublic($keyword = null, $category_id = null)
    {
        // Đã sửa status thành 1 (Approved/Published) thay vì 'approved'
        $query = "SELECT c.*, u.fullname as instructor_name, cat.name as category_name 
                  FROM " . $this->table . " c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  LEFT JOIN " . $this->category_table . " cat ON c.category_id = cat.id
                  WHERE c.status = 1"; // Sửa 'approved' thành 1

        if ($keyword) {
            $query .= " AND (c.title LIKE :keyword OR c.description LIKE :keyword)";
        }
        if ($category_id) {
            $query .= " AND c.category_id = :category_id";
        }

        $query .= " ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);

        if ($keyword) {
            $keyword = "%{$keyword}%";
            $stmt->bindParam(':keyword', $keyword);
        }

        if ($category_id) {
            $stmt->bindParam(':category_id', $category_id);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm
    public function search($keyword)
    {
        return $this->getAllPublic($keyword, null);
    }

    // Lọc theo danh mục
    public function getByCategory($category_id)
    {
        return $this->getAllPublic(null, $category_id);
    }

    // Lấy 5 khóa học mới nhất
    public function getNewestCourses()
    {
        $sql = "SELECT * FROM courses WHERE status = 1 ORDER BY created_at DESC LIMIT 5";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách khóa học của Giảng viên cùng với dữ liệu tóm tắt hiệu suất.
    public function getPerformanceSummaryByInstructor(int $instructor_id)
    {
        $query = "SELECT 
                c.id,
                c.title,
                c.status,
                COUNT(e.student_id) AS total_students,
                SUM(CASE WHEN e.progress = 100 THEN 1 ELSE 0 END) AS completed_count 
              FROM courses c
              LEFT JOIN enrollments e ON c.id = e.course_id
              WHERE c.instructor_id = :instructor_id
              GROUP BY c.id, c.title, c.status
              ORDER BY total_students DESC, c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách khóa học đang chờ duyệt (Dùng cho Admin)
    public function getPendingCourses()
    {
        // status 0 = pending/draft
        $query = "SELECT c.*, u.fullname AS instructor_name
                  FROM courses c
                  JOIN users u ON c.instructor_id = u.id
                  WHERE c.status = 0 
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái khóa học (0=Pending, 1=Approved, 2=Rejected)
    public function updateStatus(int $course_id, int $status)
    {
        $query = "UPDATE courses SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $course_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // HÀM ĐÃ SỬA: Lấy thống kê Khóa học (Dùng cho Admin Statistics)
    public function getCourseStatistics()
    {
        // Loại bỏ AVG(enrollment_count) vì cột đó không tồn tại
        $query = "SELECT COUNT(*) as total_courses,
                          SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as approved_courses,
                          SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending_courses,
                          0 as avg_enrollments_placeholder
                   FROM courses";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Thêm logic tính toán đăng ký trung bình thủ công (nếu cần)
        $enrollment_query = "SELECT COUNT(*) as total_enrollments FROM enrollments";
        $enrollment_stmt = $this->conn->prepare($enrollment_query);
        $enrollment_stmt->execute();
        $total_enrollments = $enrollment_stmt->fetchColumn();

        if ($stats['total_courses'] > 0) {
            $stats['avg_enrollments'] = $total_enrollments / $stats['total_courses'];
        } else {
            $stats['avg_enrollments'] = 0;
        }

        return $stats;
    }

    // Lấy chi tiết khóa học công khai theo ID (bao gồm tên giảng viên và danh mục)
    public function getPublicCourseDetailById(int $id)
    {
        // Cần đảm bảo khóa học được công khai (status = 1)
        $query = "SELECT c.*, u.fullname as instructor_name, cat.name as category_name 
              FROM " . $this->table . " c
              LEFT JOIN users u ON c.instructor_id = u.id
              LEFT JOIN " . $this->category_table . " cat ON c.category_id = cat.id
              WHERE c.id = :id AND c.status = 1 LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra xem học viên đã đăng ký khóa học này chưa
     * @param int $course_id ID của khóa học
     * @param int $student_id ID của học viên
     * @return bool True nếu đã đăng ký, False nếu chưa
     */
    public function isEnrolled(int $course_id, int $student_id): bool
    {
        $query = "SELECT COUNT(*) FROM " . $this->enrollment_table . " 
                  WHERE course_id = :course_id AND student_id = :student_id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        // Trả về true nếu tìm thấy ít nhất 1 bản ghi (tức là đã đăng ký)
        return $stmt->fetchColumn() > 0;
    }

    public function getUnenrolledCourses($studentId, $categoryId = null, $keyword = null)
    {
        // Cần đảm bảo table 'enrollments' có sẵn trong scope, nếu không có, cần khai báo
        $enrollment_table = 'enrollments';
        $user_table = 'users'; // Để lấy tên giảng viên

        $query = "
        SELECT 
            c.*, 
            u.fullname AS instructor_name,
            (SELECT COUNT(id) FROM lessons WHERE course_id = c.id) AS lesson_count
        FROM " . $this->table . " c
        INNER JOIN {$user_table} u ON c.instructor_id = u.id
        WHERE c.status = 1 /* <--- ĐÃ SỬA: Sử dụng số nguyên 1 cho trạng thái đã duyệt */
        AND c.id NOT IN (
            SELECT course_id 
            FROM {$enrollment_table} 
            WHERE student_id = :studentId AND status = 'active'
        )
    ";

        // 1. Thêm điều kiện lọc theo Category
        if ($categoryId) {
            $query .= " AND c.category_id = :categoryId";
        }

        // 2. Thêm điều kiện tìm kiếm theo Keyword
        if ($keyword) {
            $query .= " AND (c.title LIKE :keyword OR c.description LIKE :keyword)";
        }

        $query .= " ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);

        if ($categoryId) {
            $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        }

        if ($keyword) {
            $searchKeyword = "%{$keyword}%";
            $stmt->bindParam(':keyword', $searchKeyword, PDO::PARAM_STR);
        }

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
