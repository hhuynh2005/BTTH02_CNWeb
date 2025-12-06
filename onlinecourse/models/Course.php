<!-- // File: models/Course.php -->
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

        // 1. Vệ sinh dữ liệu
        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_desc = htmlspecialchars(strip_tags($desc));
        $clean_level = htmlspecialchars(strip_tags($level));
        $clean_image = htmlspecialchars(strip_tags($image));
        $status = 'pending'; // Giữ nguyên biến status

        // 2. Binding Parameters
        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':description', $clean_desc);
        $stmt->bindParam(':instructor_id', $instructor_id, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $duration, PDO::PARAM_INT);
        $stmt->bindParam(':level', $clean_level);
        $stmt->bindParam(':image', $clean_image);
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



    // 3. Chỉnh sửa Khóa học
    public function update(int $id, string $title, string $desc, int $category_id, float $price, int $duration, string $level, string $image)
    {
        // Xây dựng câu query linh hoạt, nếu có ảnh mới thì cập nhật thêm trường image
        $set_image_clause = $image ? ", image = :image" : "";

        $query = "UPDATE " . $this->table . "
                  SET title = :title, 
                      description = :description, 
                      category_id = :category_id, 
                      price = :price, 
                      duration_weeks = :duration_weeks, 
                      level = :level
                      {$set_image_clause}
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Vệ sinh dữ liệu và lưu vào biến cục bộ
        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_desc = htmlspecialchars(strip_tags($desc));
        $clean_level = htmlspecialchars(strip_tags($level));

        // Binding Parameters (sử dụng các biến đã được vệ sinh)
        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':description', $clean_desc);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT); // Các loại int/float không cần strip_tags/htmlspecialchars
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration_weeks', $duration, PDO::PARAM_INT);
        $stmt->bindParam(':level', $clean_level);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($image) {
            $clean_image = htmlspecialchars(strip_tags($image));
            $stmt->bindParam(':image', $clean_image);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Phương thức Xóa Khóa học
    public function delete(int $id)
    {
        // Câu lệnh SQL: Xóa dòng có ID tương ứng
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        // Chuẩn bị câu lệnh (Prepared Statement)
        $stmt = $this->conn->prepare($query);

        // Binding Parameter: Truyền giá trị ID vào câu lệnh một cách an toàn
        // (Sử dụng PDO::PARAM_INT để chỉ rõ đây là số nguyên)
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true; // Xóa thành công
        }
        return false; // Xóa thất bại
    }

    // -----------------------------------------------
    // sourse: // code from Trường 
    // -----------------------------------------------
    // Phần thêm cho nhóm 3 (student view)
    // -----------------------------------------------

    // 4. Lấy danh sách tất cả khóa học (có phân trang & tìm kiếm) - Dùng cho trang chủ/danh sách
    public function getAllPublic($keyword = null, $category_id = null)
    {
        $query = "SELECT c.*, u.fullname as instructor_name, cat.name as category_name 
                  FROM " . $this->table . " c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  LEFT JOIN " . $this->category_table . " cat ON c.category_id = cat.id
                  WHERE c.status = 'approved'"; // LƯU Ý: Trong hàm create bạn để status là 'pending', nên ở đây phải lấy 'pending' hoặc bạn phải đổi status trong DB thành 'active'

        // Logic nối chuỗi query
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
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        
        // QUAN TRỌNG: Phải trả về mảng kết hợp để View sử dụng được $course['price']
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }

    /**
     * Tìm kiếm khóa học theo từ khóa
     * Hàm này tái sử dụng logic của getAllPublic để tránh lặp code SQL
     */
    public function search($keyword)
    {
        // Gọi hàm getAllPublic với tham số:
        // $keyword = từ khóa người dùng nhập
        // $category_id = null (không lọc theo danh mục khi đang search text)
        return $this->getAllPublic($keyword, null);
    }

    /**
     * Lấy danh sách khóa học theo Danh Mục
     * Hàm này được gọi khi người dùng bấm vào link danh mục trên Sidebar
     */
    public function getByCategory($category_id)
    {
        // Tái sử dụng logic của getAllPublic để tránh viết lại câu SQL dài dòng
        // Tham số 1 (keyword): null
        // Tham số 2 (category_id): $category_id truyền vào
        return $this->getAllPublic(null, $category_id);
    }

    // Yêu cầu: Lấy 5 khóa học mới nhất
    public function getNewestCourses() {
        try {
            // Câu lệnh SQL: Sắp xếp theo ngày tạo giảm dần (mới nhất lên đầu) và lấy 5 dòng
            $sql = "SELECT * FROM courses ORDER BY created_at DESC LIMIT 5";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            // Trả về mảng các object hoặc mảng kết hợp
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi lấy danh sách khóa học: " . $e->getMessage();
            return [];
        }
    }
}

