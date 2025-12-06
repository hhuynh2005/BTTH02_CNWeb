<?php
// Nhúng file Database để kết nối
require_once 'config/Database.php';

class Lesson
{
    private $conn;
    private $table = 'lessons';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. CREATE: Thêm bài học mới
    public function create(int $course_id, string $title, string $content, string $video_url, int $order)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (course_id, title, content, video_url, `order`) 
                  VALUES (:course_id, :title, :content, :video_url, :order)";

        $stmt = $this->conn->prepare($query);

        // Vệ sinh và Binding (Áp dụng FIX LỖI đã học!)
        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_content = htmlspecialchars(strip_tags($content));
        $clean_url = htmlspecialchars(strip_tags($video_url));

        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':content', $clean_content);
        $stmt->bindParam(':video_url', $clean_url);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // 2. READ: Lấy danh sách bài học theo ID Khóa học
    public function getAllByCourseId(int $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE course_id = :course_id ORDER BY `order` ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // 3. READ: Lấy chi tiết bài học theo ID
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 4. UPDATE: Chỉnh sửa bài học
    public function update(int $id, string $title, string $content, string $video_url, int $order)
    {
        $query = "UPDATE " . $this->table . "
                  SET title = :title, content = :content, video_url = :video_url, `order` = :order
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_content = htmlspecialchars(strip_tags($content));
        $clean_url = htmlspecialchars(strip_tags($video_url));

        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':content', $clean_content);
        $stmt->bindParam(':video_url', $clean_url);
        $stmt->bindParam(':order', $order, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 5. DELETE: Xóa bài học
    public function delete(int $id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
