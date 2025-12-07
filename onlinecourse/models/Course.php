<?php
// File: models/Course.php

require_once 'config/Database.php';

class Course
{
    private $conn;
    private $table = 'courses';
    private $category_table = 'categories';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
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
        $status = 'pending';

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
    public function update(int $id, string $title, string $desc, int $category_id, float $price, int $duration, string $level, string $image)
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
        $query = "SELECT c.*, u.fullname as instructor_name, cat.name as category_name 
                  FROM " . $this->table . " c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  LEFT JOIN " . $this->category_table . " cat ON c.category_id = cat.id
                  WHERE c.status = 'approved'";

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
        $sql = "SELECT * FROM courses ORDER BY created_at DESC LIMIT 5";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
