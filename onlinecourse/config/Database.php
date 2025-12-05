// Quản lý kết nối tới cơ sở dữ liệu thông qua thư viện PDO 
// Được gọi trực tiếp trong các Model con 
<?php

class Database
{
    // Thuộc tính để lưu trữ đối tượng kết nối PDO
    public $conn;

    // Các thông số cấu hình
    private $host = '127.0.0.1';
    private $db_name = 'onlinecourse';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    // Phương thức khởi tạo kết nối
    public function getConnection()
    {
        $this->conn = null;
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

        try {
            // Tạo đối tượng PDO để kết nối CSDL
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Thiết lập chế độ báo lỗi (rất quan trọng)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "Kết nối thành công!";
        } catch (PDOException $exception) {
            // Dùng die() để dừng chương trình nếu kết nối thất bại
            die("LỖI KẾT NỐI CSDL: " . $exception->getMessage());
        }

        // Trả về đối tượng kết nối PDO
        return $this->conn;
    }
}