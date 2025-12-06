<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra email có tồn tại không
    public function emailExists() {
        // Sử dụng prepared statement để tránh SQL Injection [cite: 101]
        $query = "SELECT id, password, fullname, role, username 
                  FROM " . $this->table_name . " 
                  WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->username = $row['username'];
            $this->fullname = $row['fullname'];
            $this->password = $row['password']; // Hash mật khẩu
            $this->role = $row['role'];
            return true;
        }
        return false;
    }
}
?>