<?php
// File: models/Lesson.php

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


    /* =========================================================================
     *  PHẦN 1 — STUDENT VIEW (Học viên xem bài học)
     * =========================================================================
    */

    // Lấy danh sách bài học của 1 khóa học (sắp theo thứ tự)
    public function getByCourseId(int $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id
                  ORDER BY order_num ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết bài học theo ID
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /* =========================================================================
     *  PHẦN 2 — INSTRUCTOR CRUD
     * =========================================================================
    */

    // CREATE
    public function create(int $course_id, string $title, string $content, string $video_url, int $order)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (course_id, title, content, video_url, order_num, created_at)
                  VALUES (:course_id, :title, :content, :video_url, :order_num, NOW())";

        $stmt = $this->conn->prepare($query);

        $clean_title   = htmlspecialchars(strip_tags($title));
        $clean_video   = htmlspecialchars(strip_tags($video_url));

        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':content', $content); // content có thể chứa HTML
        $stmt->bindParam(':video_url', $clean_video);
        $stmt->bindParam(':order_num', $order, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // READ: tất cả bài học theo course_id (dùng cho trang instructor)
    public function getAllByCourseId(int $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id 
                  ORDER BY order_num ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    // UPDATE
    public function update(int $id, string $title, string $content, string $video_url, int $order)
    {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title,
                      content = :content,
                      video_url = :video_url,
                      order_num = :order_num
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_video = htmlspecialchars(strip_tags($video_url));

        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':video_url', $clean_video);
        $stmt->bindParam(':order_num', $order, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // DELETE
    public function delete(int $id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
