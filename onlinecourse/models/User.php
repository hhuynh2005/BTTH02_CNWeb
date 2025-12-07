<?php
// models/User.php
class User {
    private $conn;
    private $table_name = "users";

    // Các thuộc tính user
    public $id;
    public $username;
    public $email;
    public $password; // Chứa mật khẩu đã mã hóa (hashed)
    public $fullname;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra xem email có tồn tại không
    public function emailExists() {
        // Query chỉ lấy những trường cần thiết để xác thực
        $query = "SELECT id, username, password, fullname, role
                  FROM " . $this->table_name . " 
                  WHERE email = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu input
        $this->email = htmlspecialchars(strip_tags($this->email));
        
        // Gán tham số
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        // Nếu tìm thấy 1 dòng dữ liệu
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Gán dữ liệu vào object để Controller sử dụng
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password']; // Mật khẩu hash từ DB
            $this->fullname = $row['fullname'];
            $this->role = $row['role'];
            
            return true; // Email có tồn tại
        }
        return false; // Email không tồn tại
    }

    // Thêm vào class User trong models/User.php

    // Lấy danh sách tất cả người dùng
    // Trong file models/User.php
    
    public function getAllUsers() {
        // Query lấy toàn bộ người dùng
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Hàm xóa người dùng (cho chức năng quản lý)
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) return true;
        return false;
    }
    
    // Thêm đoạn này vào trong class User (models/User.php)

    // Chức năng Đăng ký: Tạo user mới
    public function create() {
        // Query insert dữ liệu
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password, fullname, role) 
                  VALUES (:username, :email, :password, :fullname, :role)";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu (sanitize)
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // --- LƯU Ý QUAN TRỌNG ---
        // Vì bạn đang test mật khẩu thô, nên ở đây ta gán trực tiếp.
        // Khi nộp bài, bạn nhớ đổi lại thành: 
        
        // $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $this->password); 
        
        // Gán các tham số còn lại
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Thêm vào trong class User (models/User.php)

    public function getUserStatistics() {
        // Query đếm số lượng user theo từng role
        // GROUP BY role sẽ gom nhóm: 0 (HV), 1 (GV), 2 (Admin)
        $query = "SELECT role, COUNT(*) as count FROM " . $this->table_name . " GROUP BY role";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>