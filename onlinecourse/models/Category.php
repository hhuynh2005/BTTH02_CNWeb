<!-- // File: models/Category.php -->
<?php


require_once 'config/Database.php';

class Category {
    private $conn;
    private $table = 'categories';

    public function __construct() {
        // Khởi tạo kết nối CSDL
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // 1. Lấy tất cả danh mục (Dùng cho Sidebar lọc và Dropdown select)
    public function getAll() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // Trả về mảng kết hợp (Associative Array) để dễ dùng trong View
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi lấy danh mục: " . $e->getMessage();
            return [];
        }
    }

    // 2. Lấy chi tiết 1 danh mục theo ID
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

    // 3. Tạo danh mục mới (Dành cho Admin)
    public function create($name, $description) {
        try {
            $query = "INSERT INTO " . $this->table . " (name, description, created_at) VALUES (:name, :description, NOW())";
            
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
    public function update($id, $name, $description) {
        try {
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
    public function delete($id) {
        try {
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