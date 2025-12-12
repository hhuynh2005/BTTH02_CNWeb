<?php
// File: models/Lesson.php

require_once 'config/Database.php';

class Lesson
{
    private $conn;
    private $table = 'lessons';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    /* =========================================================================
     * PHẦN 1 — STUDENT VIEW (Học viên xem bài học)
     * =========================================================================
     */

    // Lấy danh sách bài học của 1 khóa học (sắp theo thứ tự)
    public function getByCourseId(int $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id
                  ORDER BY order_num ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // [BỔ SUNG] Khắc phục lỗi: Call to undefined method Lesson::getLessonsByCourse()
    // Phương thức này được Controller gọi để lấy danh sách bài học theo Khóa học ID.
    public function getLessonsByCourse(int $course_id)
    {
        // Sử dụng lại logic đã có: Lấy danh sách bài học theo ID khóa học
        return $this->getByCourseId($course_id);
    }

    // [BỔ SUNG] Bí danh cho phương thức đếm bài học
    // Controller có thể dùng tên này để tính tiến độ
    public function countLessonsByCourse(int $course_id)
    {
        // Sử dụng lại logic đã có: Đếm số bài học trong khóa học
        return $this->countByCourseId($course_id);
    }

    // Lấy chi tiết bài học theo ID
    public function getById(int $id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy bài học đầu tiên của khóa học
    public function getFirstLesson(int $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id
                  ORDER BY order_num ASC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết bài học (join thêm thông tin khóa học)
    public function getLessonDetailsById(int $lesson_id)
    {
        // Giả định có bảng lessons (l) và courses (c)
        $query = "SELECT 
                    l.id, 
                    l.course_id, 
                    l.title AS lesson_title,
                    l.content,
                    l.video_url,
                    l.order_num,
                    c.title AS course_title,
                    c.instructor_id
                  FROM lessons l
                  JOIN courses c ON l.course_id = c.id
                  WHERE l.id = :lesson_id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách bài học kèm trạng thái tiến độ (Giả lập nếu chưa có bảng progress)
    public function getLessonsWithProgress($course_id, $student_id)
    {
        // TRUY VẤN TẠM THỜI (TẮT JOIN VỚI lesson_progress nếu chưa có)
        // CẦN BỔ SUNG LOGIC JOIN VỚI BẢNG lesson_progress NẾU CÓ.
        $query = "SELECT 
                    l.id AS lesson_id, 
                    l.title AS lesson_title,
                    -- l.content_type, 
                    l.order_num,
                    -- Lấy trạng thái từ bảng lesson_progress (nếu có)
                    IFNULL(lp.completion_date, NULL) AS completion_date,
                    IF(lp.id IS NOT NULL, 1, 0) AS is_completed 
                  FROM " . $this->table . " l
                  LEFT JOIN lesson_progress lp 
                       ON l.id = lp.lesson_id AND lp.student_id = :student_id
                  WHERE l.course_id = :course_id
                  ORDER BY l.order_num ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /* =========================================================================
     * PHẦN 2 — INSTRUCTOR CRUD (Thêm, Sửa, Xóa Bài học)
     * =========================================================================
     */

    // CREATE (Thêm mới bài học)
    public function create(int $course_id, string $title, string $content, string $video_url, int $order)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (course_id, title, content, video_url, order_num, created_at)
                  VALUES (:course_id, :title, :content, :video_url, :order_num, NOW())";

        $stmt = $this->conn->prepare($query);

        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_video = htmlspecialchars(strip_tags($video_url));

        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':content', $content); // content có thể chứa HTML
        $stmt->bindParam(':video_url', $clean_video);
        $stmt->bindParam(':order_num', $order, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // READ: tất cả bài học theo course_id (dùng cho trang instructor, trả về PDOStatement)
    public function getAllByCourseId(int $course_id)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id 
                  ORDER BY order_num ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    // UPDATE (Cập nhật bài học)
    public function update(int $id, string $title, string $content, string $video_url, int $order)
    {
        $query = "UPDATE " . $this->table . " 
                  SET title = :title,
                      content = :content,
                      video_url = :video_url,
                      order_num = :order_num
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $clean_title = htmlspecialchars(strip_tags($title));
        $clean_video = htmlspecialchars(strip_tags($video_url));

        $stmt->bindParam(':title', $clean_title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':video_url', $clean_video);
        $stmt->bindParam(':order_num', $order, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // DELETE (Xóa một bài học)
    public function delete(int $id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xóa tất cả bài học của khóa học (Dùng khi xóa khóa học)
    public function deleteAllByCourseId(int $course_id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE course_id = :course_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Kiểm tra bài học có tồn tại
    public function exists(int $id)
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }


    /* =========================================================================
     * PHẦN 3 — HÀM MỞ RỘNG CHO GIẢNG VIÊN (Quản lý thứ tự, thống kê)
     * =========================================================================
     */

    // Đếm số bài học trong khóa học
    public function countByCourseId(int $course_id)
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE course_id = :course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Lấy order lớn nhất trong khóa học
    public function getMaxOrder(int $course_id)
    {
        $query = "SELECT MAX(order_num) as max_order FROM " . $this->table . " 
                  WHERE course_id = :course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Trả về 0 nếu không có bài học nào
        return $result['max_order'] ?: 0;
    }

    // Cập nhật chỉ thứ tự bài học
    public function updateOrder(int $id, int $order)
    {
        $query = "UPDATE " . $this->table . " 
                  SET order_num = :order_num 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_num', $order, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Hoán đổi thứ tự bài học
    public function swapOrder(int $id1, int $id2)
    {
        $lesson1 = $this->getById($id1);
        $lesson2 = $this->getById($id2);

        if (!$lesson1 || !$lesson2) {
            return false;
        }

        // Hoán đổi order_num
        $tempOrder = $lesson1['order_num'];
        $this->updateOrder($id1, $lesson2['order_num']);
        $this->updateOrder($id2, $tempOrder);

        return true;
    }

    // Di chuyển bài học lên (hoán đổi với bài học ngay trước đó)
    public function moveUp(int $id)
    {
        $lesson = $this->getById($id);
        if (!$lesson) {
            return false;
        }

        // Tìm bài học có order_num nhỏ hơn (ngay trước nó)
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id AND order_num < :order_num
                  ORDER BY order_num DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $lesson['course_id'], PDO::PARAM_INT);
        $stmt->bindParam(':order_num', $lesson['order_num'], PDO::PARAM_INT);
        $stmt->execute();

        $previousLesson = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($previousLesson) {
            return $this->swapOrder($id, $previousLesson['id']);
        }

        return false;
    }

    // Di chuyển bài học xuống (hoán đổi với bài học ngay sau đó)
    public function moveDown(int $id)
    {
        $lesson = $this->getById($id);
        if (!$lesson) {
            return false;
        }

        // Tìm bài học có order_num lớn hơn (ngay sau nó)
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id AND order_num > :order_num
                  ORDER BY order_num ASC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $lesson['course_id'], PDO::PARAM_INT);
        $stmt->bindParam(':order_num', $lesson['order_num'], PDO::PARAM_INT);
        $stmt->execute();

        $nextLesson = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($nextLesson) {
            return $this->swapOrder($id, $nextLesson['id']);
        }

        return false;
    }

    // Kiểm tra quyền sở hữu bài học (dùng cho instructor)
    public function isOwnedByInstructor(int $lesson_id, int $instructor_id)
    {
        $query = "SELECT c.instructor_id 
                  FROM " . $this->table . " l
                  JOIN courses c ON l.course_id = c.id
                  WHERE l.id = :lesson_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['instructor_id'] == $instructor_id;
    }

    // Lấy thống kê bài học (dùng cho instructor dashboard)
    public function getStatistics(int $course_id)
    {
        $query = "SELECT 
                    COUNT(*) as total_lessons,
                    SUM(CASE WHEN video_url IS NOT NULL AND video_url != '' THEN 1 ELSE 0 END) as videos_count,
                    AVG(LENGTH(content)) as avg_content_length,
                    MIN(created_at) as first_created,
                    MAX(created_at) as last_updated
                  FROM " . $this->table . " 
                  WHERE course_id = :course_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm bài học trong khóa học
    public function searchInCourse(int $course_id, string $keyword)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE course_id = :course_id 
                  AND (title LIKE :keyword OR content LIKE :keyword)
                  ORDER BY order_num ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
