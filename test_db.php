<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kiểm tra dữ liệu Database</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; margin-top: 40px; color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .empty { color: red; font-style: italic; }
        .success { color: green; font-weight: bold; font-size: 1.2em; }
    </style>
</head>
<body>

<?php
// 1. Kết nối CSDL
require_once 'config/Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "<div class='success'>✅ Kết nối CSDL thành công! Đang lấy dữ liệu...</div>";

    // 2. Lấy danh sách tất cả các bảng trong Database
    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($tables) == 0) {
        echo "<h3>CSDL hiện tại chưa có bảng nào!</h3>";
    }

    // 3. Lặp qua từng bảng để lấy dữ liệu
    foreach ($tables as $table_name) {
        echo "<h2>Bảng: $table_name</h2>";

        // Query lấy dữ liệu của bảng hiện tại
        $query = "SELECT * FROM $table_name";
        $stmt_data = $conn->prepare($query);
        $stmt_data->execute();
        
        // Lấy tất cả dòng dữ liệu và trả về mảng kết hợp (Associative Array)
        $rows = $stmt_data->fetchAll(PDO::FETCH_ASSOC);

        if (count($rows) > 0) {
            // Vẽ bảng HTML
            echo "<table>";
            
            // Vẽ tiêu đề cột (Lấy từ key của dòng đầu tiên)
            echo "<thead><tr>";
            foreach (array_keys($rows[0]) as $column_name) {
                echo "<th>$column_name</th>";
            }
            echo "</tr></thead>";

            // Vẽ nội dung (Body)
            echo "<tbody>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='empty'>Bảng này chưa có dữ liệu.</p>";
        }
    }

} catch (Exception $e) {
    echo "<h3 style='color:red'>Lỗi: " . $e->getMessage() . "</h3>";
}
?>

</body>
</html>



<!-- <?php // Kiểm tra xem có kết nối được với cơ sở dữ liệu chưa 
 
// Nhúng file cấu hình vào
require_once 'config/Database.php';

// Khởi tạo đối tượng Database
$db_obj = new Database();

// Gọi hàm kết nối thử
$conn = $db_obj->getConnection();

// Nếu code trong Database.php chạy đúng, nó sẽ tự echo "Kết nối thành công!"
// Nếu muốn chắc chắn hơn, in ra đối tượng connection
if ($conn) {
    echo "<br>Object PDO đã được tạo. Sẵn sàng truy vấn!";
}
?> -->