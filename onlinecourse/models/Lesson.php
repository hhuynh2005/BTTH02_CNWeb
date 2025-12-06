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
}
