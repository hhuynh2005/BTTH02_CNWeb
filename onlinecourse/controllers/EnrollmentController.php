// Xử lý việc học viên tham gia khóa học   // Mua và học khóa học

// Models liên quan:
// Enrollment.php: Quản lý dữ liệu đăng ký khóa học
// Course.php: Quản lý dữ liệu khóa học
// User.php: Quản lý dữ liệu người dùng (học viên) 

// Views liên quan:
// - views/students/dashboard.php: Tổng quan tiến độ học tập của học viên
// - views/courses/my_courses.php: Hiển thị danh sách khóa học mà học viên đã đăng ký

// Logic nghiệp vụ:
// enroll($courseId, $userId): Đăng ký học viên vào khóa học
// myCourses($userId): Lấy danh sách khóa học mà học viên đã đăng ký
// progress($courseId, $userId): Lấy tiến độ học tập của học viên
// Kiểm tra quyền truy cập khóa học trước khi cho phép học viên xem nội dung khóa học

// done
