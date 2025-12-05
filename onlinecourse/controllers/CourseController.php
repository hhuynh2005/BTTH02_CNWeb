// Xử lý hiển thị khóa học cho người học và quản lý khóa học cho giảng viên

// Models liên quan:
// - models/Course.php: Quản lý dữ liệu khóa học
// - models/User.php: Quản lý dữ liệu người dùng (giảng viên, học viên)
// - models/Category.php: Quản lý danh mục khóa học
// - models/Lesson.php: Quản lý bài học trong khóa học

// Views liên quan:
//      Cho Public/Student:
// - views/courses/index.php: Hiển thị danh sách khóa học
// - views/courses/detail.php: Hiển thị chi tiết khóa học và bài học
//      Cho Instructor: 
// - views/instructor/courses/manage.php: Quản lý khóa học của giảng viên
// - views/instructor/courses/create.php: Tạo khóa học mới
// - views/instructor/courses/edit.php: Chỉnh sửa khóa học

// Logic nghiệp vụ: 
//      Public/Student:
// index(): Hiển thị tất cả khóa học, có phân trang và lọc theo danh mục
// search(): Tìm kiếm khóa học theo từ khóa và danh mục 
// show($id): Hiển thị chi tiết khóa học, danh sách bài học và thông tin giảng viên

//      Instructor:
// create()/ store(): Hiển thị form tạo khóa học và lưu khóa học mới vào DB
// edit($id)/ update($id): Hiển thị form chỉnh sửa và cập nhật khóa học trong DB
// delete($id): Xóa khóa học khỏi DB (và các bài học liên quan)
// manage(): Hiển thị danh sách khóa học của giảng viên để quản lý

// done