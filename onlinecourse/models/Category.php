<?php
// File: models/Category.php - Model xử lý dữ liệu cho bảng categories

// Chú ý: KHÔNG cần require_once 'config/Database.php' ở đây
// vì kết nối Database được truyền từ Controller qua hàm __construct

class Category
{
    private $conn;
    private $table = 'categories';

    /**
     * Hàm tạo, nhận kết nối DB từ Controller.
     */
    public function __construct($db)
    {
        // Gán kết nối DB đã được khởi tạo bên ngoài
        $this->conn = $db;
    }

    // 1. Lấy tất cả danh mục (Dùng cho Sidebar lọc và Dropdown select)
    public function getAll()
    {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Trong môi trường production, bạn nên log lỗi thay vì echo
            return [];
        }
    }

    // 2. Lấy chi tiết 1 danh mục theo ID
    public function getById($id)
    {
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

    // 3. Tạo danh mục mới (Dành cho Admin)
    public function create($name, $description)
    {
        try {
            // Giả định bảng categories có cột created_at (DATETIME DEFAULT CURRENT_TIMESTAMP)
            $query = "INSERT INTO " . $this->table . " (name, description) VALUES (:name, :description)";

            $stmt = $this->conn->prepare($query);

            // Vệ sinh dữ liệu
            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // 4. Cập nhật danh mục (Dành cho Admin)
    public function update($id, $name, $description)
    {
        try {
            // Nếu có cột updated_at, nó sẽ tự động cập nhật nếu định nghĩa trong DB
            $query = "UPDATE " . $this->table . " 
                      SET name = :name, description = :description 
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $name = htmlspecialchars(strip_tags($name));
            $description = htmlspecialchars(strip_tags($description));

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // 5. Xóa danh mục (Dành cho Admin)
    public function delete($id)
    {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Chú ý: Nếu khóa ngoại trong bảng courses được đặt là RESTRICT,
            // việc xóa sẽ thất bại nếu có khóa học sử dụng danh mục này.
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            // Nếu lỗi do khóa ngoại, e.getMessage() sẽ có thông tin chi tiết
            return false;
        }
    }
}
?>