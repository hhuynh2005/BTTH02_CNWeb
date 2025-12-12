<?php
// D:\xampp\htdocs\cse485\BTTH02_CNWeb\onlinecourse\models\Material.php

class Material
{
    private $conn;
    private $table_name = "materials";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả tài liệu đính kèm cho một Lesson cụ thể.
     */
    public function getByLessonId(int $lesson_id)
    {
        $query = "SELECT 
                    id, 
                    lesson_id, 
                    filename, 
                    file_path, 
                    file_type, 
                    uploaded_at
                  FROM " . $this->table_name . " 
                  WHERE lesson_id = :lesson_id
                  ORDER BY uploaded_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm bản ghi tài liệu mới vào cơ sở dữ liệu (Hàm cốt lõi cho chức năng Upload).
     */
    public function create(int $lesson_id, string $filename, string $file_path, string $file_type)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (lesson_id, filename, file_path, file_type) 
                  VALUES (:lesson_id, :filename, :file_path, :file_type)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $filename = htmlspecialchars(strip_tags($filename));
        $file_path = htmlspecialchars(strip_tags($file_path));
        $file_type = htmlspecialchars(strip_tags($file_type));

        // Gán tham số
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->bindParam(':filename', $filename);
        $stmt->bindParam(':file_path', $file_path);
        $stmt->bindParam(':file_type', $file_type);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Lấy chi tiết tài liệu theo ID.
     */
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Xóa tài liệu theo ID.
     */
    public function delete(int $id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    /**
     * Lấy TẤT CẢ tài liệu từ các khóa học mà một học viên đã đăng ký.
     * Phương thức này JOIN qua enrollments, courses, lessons và materials.
     */
    public function getAllMaterialsByStudent(int $studentId)
    {
        $query = "
        SELECT 
            m.id AS material_id, 
            m.filename, 
            m.file_path, 
            m.file_type,
            m.uploaded_at,
            c.title AS course_title,
            l.title AS lesson_title
        FROM 
            " . $this->table_name . " m
        JOIN 
            lessons l ON m.lesson_id = l.id
        JOIN 
            courses c ON l.course_id = c.id
        JOIN 
            enrollments e ON c.id = e.course_id
        WHERE 
            e.student_id = :studentId
        ORDER BY 
            m.uploaded_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>