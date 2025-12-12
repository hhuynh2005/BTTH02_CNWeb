<?php
// models/User.php - Model xử lý dữ liệu cho bảng users

class User
{
    private $conn;
    private $table_name = "users";

    // Các thuộc tính user
    public $id;
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Kiểm tra xem email có tồn tại không (Dùng cho đăng nhập)
    public function emailExists()
    {
        $query = "SELECT id, username, password, fullname, role, status
                  FROM " . $this->table_name . " 
                  WHERE email = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->fullname = $row['fullname'];
            $this->role = $row['role'];
            $this->status = $row['status'];

            return true;
        }
        return false;
    }

    // Chức năng Đăng ký: Tạo user mới
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password, fullname, role) 
                  VALUES (:username, :email, :password, :fullname, :role)";

        $stmt = $this->conn->prepare($query);
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':role', $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lấy danh sách tất cả người dùng (Dùng cho Admin)
    public function getAllUsers()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lấy thông tin người dùng theo ID
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy thống kê người dùng theo Role (Dùng cho Dashboard Admin)
    public function getUserStatistics()
    {
        $query = "SELECT role, COUNT(*) as count FROM " . $this->table_name . " GROUP BY role";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái user (Kích hoạt/Vô hiệu hóa - Dùng cho Admin)
    public function updateStatus(int $id, int $status)
    {
        // ĐÃ SỬA LỖI: LOẠI BỎ 'updated_at = NOW()'
        $query = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Hàm xóa người dùng (Dùng cho Admin)
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute())
            return true;
        return false;
    }
}
?>