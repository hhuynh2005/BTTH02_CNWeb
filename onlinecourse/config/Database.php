<?php

class Database
{
    private $host = '127.0.0.1';
    private $db_name = 'onlinecourse';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";

        try {
            // Tạo kết nối PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Thiết lập chế độ lỗi
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Thiết lập charset
            $this->conn->exec("SET NAMES utf8");

        } catch (PDOException $exception) {
            die("Lỗi kết nối CSDL: " . $exception->getMessage());
        }

        return $this->conn;
    }
}

?>
