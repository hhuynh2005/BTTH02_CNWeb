<!-- // File: models/Lesson.php -->

<?php


require_once 'config/Database.php';

class Lesson {
    private $conn;
    private $table = 'lessons';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // =========================================================================
    // PHẦN 1: PUBLIC / STUDENT (Học viên xem bài học)
    // =========================================================================

    /**
     * Lấy danh sách bài học của một khóa học (Sắp xếp theo thứ tự)
     * Dùng cho trang: Chi tiết khóa học (Course Detail)
     */
    public function getByCourseId($course_id) {
        try {
            // Lưu ý: `order` là từ khóa SQL, cần bọc trong dấu huyền ``
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE course_id = :course_id 
                      ORDER BY order_num ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi lấy danh sách bài học: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Lấy chi tiết một bài học
     * Dùng cho trang: Học bài (Lesson View)
     */
    public function getById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    // =========================================================================
    // PHẦN 2: INSTRUCTOR (Giảng viên quản lý bài học)
    // =========================================================================

    /**
     * Tạo bài học mới
     */
    public function create($course_id, $title, $content, $video_url, $order) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (course_id, title, content, video_url, `order`, created_at) 
                      VALUES (:course_id, :title, :content, :video_url, :order, NOW())";

            $stmt = $this->conn->prepare($query);

            // Vệ sinh dữ liệu
            $title = htmlspecialchars(strip_tags($title));
            // Content có thể chứa HTML (WYSIWYG editor), cân nhắc strip_tags hoặc không tùy yêu cầu
            // Ở đây ta giữ nguyên content nhưng dùng bindParam để tránh SQL Injection
            $video_url = htmlspecialchars(strip_tags($video_url));
            
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content); // Cho phép lưu HTML
            $stmt->bindParam(':video_url', $video_url);
            $stmt->bindParam(':order', $order, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo "Lỗi tạo bài học: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Cập nhật bài học
     */
    public function update($id, $title, $content, $video_url, $order) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET title = :title, 
                          content = :content, 
                          video_url = :video_url, 
                          `order` = :order 
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $title = htmlspecialchars(strip_tags($title));
            $video_url = htmlspecialchars(strip_tags($video_url));

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':video_url', $video_url);
            $stmt->bindParam(':order', $order, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Xóa bài học
     */
    public function delete($id) {
        try {
            // Có thể cần xóa thêm tài liệu (materials) liên quan trước khi xóa bài học
            // Nhưng ở mức cơ bản, ta chỉ xóa bài học
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>