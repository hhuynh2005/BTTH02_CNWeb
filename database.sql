-- 1. Tạo CSDL
CREATE DATABASE IF NOT EXISTS onlinecourse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE onlinecourse;

-- 2. Bảng users
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255),
    avatar VARCHAR(255),
    role INT DEFAULT 0 COMMENT '0: học viên, 1: giảng viên, 2: quản trị viên',
    status TINYINT DEFAULT 1 COMMENT '1: hoạt động, 0: bị khóa',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Bảng categories
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Bảng courses
CREATE TABLE IF NOT EXISTS courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    instructor_id INT,
    category_id INT,
    price DECIMAL(10,2) DEFAULT 0,
    duration_weeks INT,
    level VARCHAR(50) COMMENT 'Beginner, Intermediate, Advanced',
    image VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending' COMMENT 'draft, pending, approved',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 5. Bảng enrollments (Đăng ký học)
CREATE TABLE IF NOT EXISTS enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    student_id INT,
    enrolled_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'active' COMMENT 'active, completed, dropped',
    progress INT DEFAULT 0 COMMENT 'Phần trăm hoàn thành (0-100)',
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 6. Bảng lessons (Bài học)
CREATE TABLE IF NOT EXISTS lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    video_url VARCHAR(255),
    order_num INT DEFAULT 0 COMMENT 'Thứ tự bài học',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- 7. Bảng materials (Tài liệu đính kèm)
CREATE TABLE IF NOT EXISTS materials (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lesson_id INT,
    filename VARCHAR(255),
    file_path VARCHAR(255),
    file_type VARCHAR(50) COMMENT 'pdf, doc, ppt, v.v.',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE
);
USE onlinecourse;
USE onlinecourse;

-- Tắt kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------
-- PHẦN SỬA LỖI: Dùng DELETE và Reset ID thay vì TRUNCATE
-- ---------------------------------------------------------
DELETE FROM materials;
ALTER TABLE materials AUTO_INCREMENT = 1;

DELETE FROM lessons;
ALTER TABLE lessons AUTO_INCREMENT = 1;

DELETE FROM enrollments;
ALTER TABLE enrollments AUTO_INCREMENT = 1;

DELETE FROM courses;
ALTER TABLE courses AUTO_INCREMENT = 1;

DELETE FROM categories;
ALTER TABLE categories AUTO_INCREMENT = 1;

DELETE FROM users;
ALTER TABLE users AUTO_INCREMENT = 1;

-- ---------------------------------------------------------
-- 1. NẠP 30 USER (5 Giảng viên, 2 Admin, 23 Học viên)
-- ---------------------------------------------------------
INSERT INTO users (username, email, password, fullname, role, status) VALUES 
('admin_main', 'admin1@sys.com', '123456', 'Super Admin', 2, 1),
('admin_support', 'admin2@sys.com', '123456', 'Support Lead', 2, 1),
('teacher_tung', 'tung@edu.vn', '123456', 'Nguyễn Thanh Tùng', 1, 1),
('teacher_hoa', 'hoa@edu.vn', '123456', 'Lê Thị Hoa', 1, 1),
('teacher_hung', 'hung@edu.vn', '123456', 'Phạm Văn Hùng', 1, 1),
('teacher_david', 'david@edu.us', '123456', 'David Miller', 1, 1),
('teacher_sarah', 'sarah@edu.us', '123456', 'Sarah Connor', 1, 1),
('student_nam', 'nam@st.com', '123456', 'Trần Văn Nam', 0, 1),
('student_linh', 'linh@st.com', '123456', 'Nguyễn Thùy Linh', 0, 1),
('student_huy', 'huy@st.com', '123456', 'Lê Quang Huy', 0, 1),
('student_mai', 'mai@st.com', '123456', 'Phạm Tuyết Mai', 0, 1),
('student_khiem', 'khiem@st.com', '123456', 'Hoàng Gia Khiêm', 0, 1),
('student_loan', 'loan@st.com', '123456', 'Vũ Thị Loan', 0, 1),
('student_duy', 'duy@st.com', '123456', 'Trịnh Bá Duy', 0, 1),
('student_phuong', 'phuong@st.com', '123456', 'Lý Lan Phương', 0, 1),
('student_toan', 'toan@st.com', '123456', 'Đặng Minh Toàn', 0, 1),
('student_thu', 'thu@st.com', '123456', 'Bùi Anh Thư', 0, 1),
('student_viet', 'viet@st.com', '123456', 'Ngô Quốc Việt', 0, 1),
('student_thao', 'thao@st.com', '123456', 'Đỗ Phương Thảo', 0, 1),
('student_quan', 'quan@st.com', '123456', 'Lại Hồng Quân', 0, 1),
('student_yen', 'yen@st.com', '123456', 'Dương Hải Yến', 0, 1),
('student_khoa', 'khoa@st.com', '123456', 'Mạc Đăng Khoa', 0, 1),
('student_nhung', 'nhung@st.com', '123456', 'Lê Hồng Nhung', 0, 1),
('student_tam', 'tam@st.com', '123456', 'Trần Thanh Tâm', 0, 1),
('student_phuc', 'phuc@st.com', '123456', 'Nguyễn Hoàng Phúc', 0, 1),
('student_ngoc', 'ngoc@st.com', '123456', 'Phạm Bảo Ngọc', 0, 1),
('student_son', 'son@st.com', '123456', 'Cao Thái Sơn', 0, 1),
('student_fake1', 'fake1@st.com', '123456', 'User Bị Khóa', 0, 0),
('student_fake2', 'fake2@st.com', '123456', 'User Vi Phạm', 0, 0),
('student_new', 'newbie@st.com', '123456', 'Newbie Member', 0, 1);

-- ---------------------------------------------------------
-- 2. NẠP 10 DANH MỤC (CATEGORIES)
-- ---------------------------------------------------------
INSERT INTO categories (name, description) VALUES 
('Lập trình Web', 'HTML, CSS, JS, PHP, Ruby, Java Web'),
('Lập trình Mobile', 'iOS, Android, Flutter, React Native'),
('Khoa học dữ liệu', 'Python, R, Machine Learning, AI'),
('Thiết kế đồ họa', 'Photoshop, AI, Figma, UI/UX'),
('Marketing Online', 'SEO, Facebook Ads, Google Ads, Content'),
('Kỹ năng mềm', 'Giao tiếp, Thuyết trình, Quản lý thời gian'),
('Ngoại ngữ', 'Tiếng Anh, Tiếng Nhật, Tiếng Hàn'),
('Tin học văn phòng', 'Excel, Word, Powerpoint nâng cao'),
('Quản trị mạng', 'Cisco, Linux, Cyber Security'),
('Blockchain', 'Solidity, Smart Contracts, Crypto');

-- ---------------------------------------------------------
-- 3. NẠP 20 KHÓA HỌC (COURSES)
-- ---------------------------------------------------------
INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, status) VALUES 
('Lập trình PHP toàn tập', 'Từ cơ bản đến nâng cao với Laravel', 3, 1, 800000, 10, 'Intermediate', 'approved'),
('ReactJS Masterclass', 'Xây dựng Single Page App', 3, 1, 1200000, 8, 'Advanced', 'approved'),
('HTML5 & CSS3 căn bản', 'Xây dựng giao diện web chuẩn SEO', 4, 1, 300000, 4, 'Beginner', 'approved'),
('NodeJS Restful API', 'Viết API hiệu năng cao', 3, 1, 900000, 6, 'Intermediate', 'pending'),
('Flutter & Dart', 'Viết ứng dụng đa nền tảng', 5, 2, 1500000, 12, 'Intermediate', 'approved'),
('Android Kotlin', 'Lập trình Android chính thống', 5, 2, 1000000, 10, 'Beginner', 'approved'),
('Python for Data Science', 'Phân tích dữ liệu với Pandas', 6, 3, 2000000, 14, 'Advanced', 'approved'),
('Machine Learning A-Z', 'Học máy cho người mới', 6, 3, 2500000, 16, 'Advanced', 'pending'),
('Photoshop cho người mới', 'Chỉnh sửa ảnh chuyên nghiệp', 7, 4, 400000, 4, 'Beginner', 'approved'),
('Figma UI/UX Design', 'Thiết kế giao diện App/Web', 7, 4, 600000, 6, 'Intermediate', 'approved'),
('SEO lên Top Google', 'Kỹ thuật SEO mũ trắng', 4, 5, 1200000, 8, 'Intermediate', 'approved'),
('Facebook Ads thực chiến', 'Chạy quảng cáo ra đơn', 4, 5, 900000, 5, 'Beginner', 'approved'),
('Tiếng Anh giao tiếp', 'Phản xạ tiếng anh công sở', 7, 7, 500000, 10, 'Beginner', 'approved'),
('Excel nâng cao', 'Xử lý hàm phức tạp', 4, 8, 300000, 3, 'Intermediate', 'approved'),
('Linux System Admin', 'Quản trị máy chủ Linux', 5, 9, 1500000, 10, 'Advanced', 'pending'),
('Blockchain Basic', 'Hiểu về chuỗi khối', 3, 10, 2000000, 8, 'Beginner', 'approved'),
('Javascript nâng cao', 'Closure, Async/Await', 3, 1, 700000, 5, 'Advanced', 'approved'),
('VueJS Framework', 'Framework frontend nhẹ nhàng', 3, 1, 800000, 6, 'Intermediate', 'draft'),
('Docker & Kubernetes', 'Deploy ứng dụng container', 5, 9, 2000000, 8, 'Advanced', 'approved'),
('Cyber Security Basic', 'Bảo mật thông tin cơ bản', 5, 9, 1200000, 6, 'Beginner', 'approved');

-- ---------------------------------------------------------
-- 4. NẠP ĐĂNG KÝ HỌC (ENROLLMENTS)
-- ---------------------------------------------------------
INSERT INTO enrollments (course_id, student_id, status, progress) VALUES 
(1, 8, 'active', 20), (1, 9, 'active', 50), (1, 10, 'completed', 100),
(2, 8, 'dropped', 10), (2, 11, 'active', 5), (2, 12, 'active', 0),
(3, 13, 'active', 90), (3, 14, 'completed', 100), (3, 15, 'active', 30),
(4, 16, 'active', 10), (4, 17, 'pending', 0),
(5, 8, 'active', 60), (5, 18, 'completed', 100),
(6, 19, 'active', 25), (6, 20, 'active', 40),
(7, 21, 'active', 10), (7, 22, 'active', 5),
(8, 23, 'active', 80), (8, 24, 'active', 70),
(9, 25, 'completed', 100), (9, 26, 'active', 10),
(10, 27, 'dropped', 0), (10, 28, 'active', 20),
(1, 29, 'active', 15), (2, 30, 'active', 10),
(3, 8, 'active', 100), (5, 9, 'active', 45);

-- ---------------------------------------------------------
-- 5. NẠP BÀI HỌC (LESSONS)
-- ---------------------------------------------------------
INSERT INTO lessons (course_id, title, content, video_url, order_num) VALUES 
(1, 'PHP - Bài 1: Cú pháp cơ bản', 'Nội dung bài 1...', 'link1', 1),
(1, 'PHP - Bài 2: Vòng lặp', 'Nội dung bài 2...', 'link2', 2),
(1, 'PHP - Bài 3: Hàm', 'Nội dung bài 3...', 'link3', 3),
(1, 'PHP - Bài 4: Mảng', 'Nội dung bài 4...', 'link4', 4),
(1, 'PHP - Bài 5: Xử lý chuỗi', 'Nội dung bài 5...', 'link5', 5),
(1, 'PHP - Bài 6: Form Handling', 'Nội dung bài 6...', 'link6', 6),
(1, 'PHP - Bài 7: Kết nối MySQL', 'Nội dung bài 7...', 'link7', 7),
(1, 'PHP - Bài 8: Session & Cookie', 'Nội dung bài 8...', 'link8', 8),
(2, 'React - Bài 1: JSX là gì?', 'Nội dung React 1...', 'link_r1', 1),
(2, 'React - Bài 2: Components', 'Nội dung React 2...', 'link_r2', 2),
(2, 'React - Bài 3: Props & State', 'Nội dung React 3...', 'link_r3', 3),
(2, 'React - Bài 4: Lifecycle', 'Nội dung React 4...', 'link_r4', 4),
(2, 'React - Bài 5: React Hooks', 'Nội dung React 5...', 'link_r5', 5),
(3, 'HTML - Bài 1: Cấu trúc DOM', 'Nội dung HTML 1...', 'link_h1', 1),
(3, 'HTML - Bài 2: Các thẻ Text', 'Nội dung HTML 2...', 'link_h2', 2),
(3, 'HTML - Bài 3: Form và Input', 'Nội dung HTML 3...', 'link_h3', 3);

-- ---------------------------------------------------------
-- 6. NẠP TÀI LIỆU (MATERIALS)
-- ---------------------------------------------------------
INSERT INTO materials (lesson_id, filename, file_path, file_type) VALUES 
(1, 'slide_php_1.pdf', 'path/to/slide1.pdf', 'pdf'),
(2, 'bai_tap_php_2.zip', 'path/to/code2.zip', 'zip'),
(7, 'db_sample.sql', 'path/to/db.sql', 'sql'),
(9, 'react_cheatsheet.png', 'path/to/img.png', 'image'),
(13, 'html_boilerplate.html', 'path/to/code.html', 'code');

-- Bật lại kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 1;