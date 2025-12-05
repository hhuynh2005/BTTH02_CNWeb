<?php

require_once 'config/Database.php';

class Course
{
    private $conn;
    private $table = 'courses';
    private $category_table = 'categories';

    public function __construct()
    {
        // Khởi tạo kết nối CSDL khi đối tượng Course được tạo ra
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Phương thức lấy tất cả danh mục (READ: Dùng cho dropdown khi Tạo/Sửa Khóa học)
    public function getAllCategories()
    {
        $query = "SELECT id, name FROM " . $this->category_table . " ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt; // Trả về PDOStatement chứa dữ liệu
    }


    // Phương thức Thêm Khóa học mới
    public function create(string $title, string $desc, int $instructor_id, int $category_id, float $price, int $duration, string $level, string $image)
    {
        // Trạng thái ban đầu có thể là 'draft' (nháp) hoặc 'pending' (chờ duyệt)
        $status = 'pending';

        $query = "INSERT INTO " . $this->table . " 
                  (title, description, instructor_id, category_id, price, duration_weeks, level, image, status) 
                  VALUES (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image, :status)";

        $stmt = $this->conn->prepare($query);

        // 1. Vệ sinh dữ liệu (Sanitize) - Giúp chống XSS
        $title = htmlspecialchars(strip_tags($title));
        $desc = htmlspecialchars(strip_tags($desc));
        $level = htmlspecialchars(strip_tags($level));
        $image = htmlspecialchars(strip_tags($image));

        // 2. Binding Parameters - Giúp chống SQL Injection
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $desc);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $duration, PDO::PARAM_INT);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }



    // 1. Lấy danh sách khóa học theo ID Giảng viên (Dùng cho trang quản lý)
    public function getAllByInstructorId(int $instructor_id)
    {
        $query = "SELECT c.*, cat.name AS category_name 
                  FROM " . $this->table . " c
                  JOIN " . $this->category_table . " cat ON c.category_id = cat.id
                  WHERE c.instructor_id = :instructor_id
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // 2. Lấy chi tiết khóa học theo ID (Dùng cho form Chỉnh sửa)
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về mảng dữ liệu khóa học
    }
}