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

-- 1. Thêm dữ liệu bảng users (Giảng viên, Học viên, Admin)
-- Lưu ý: Password ở đây để demo là '123456'. Trong thực tế bạn cần mã hóa MD5 hoặc Bcrypt.
INSERT INTO users (username, email, password, fullname, role, status) VALUES 
('giangvien_a', 'gv.a@example.com', '123456', 'Nguyễn Văn Giảng', 1, 1),  -- ID 1
('hocvien_b', 'hv.b@example.com', '123456', 'Trần Học Viên', 0, 1),    -- ID 2
('admin_c', 'admin@example.com', '123456', 'Lê Quản Trị', 2, 1),       -- ID 3
('hocvien_d', 'hv.khoa@example.com', '123456', 'Phạm Bị Khóa', 0, 0);  -- ID 4

-- 2. Thêm dữ liệu bảng categories (Danh mục)
INSERT INTO categories (name, description) VALUES 
('Lập trình Web', 'Các khóa học về Front-end và Back-end (PHP, Nodejs, React...)'), -- ID 1
('Mobile App', 'Lập trình ứng dụng Android và iOS (Flutter, React Native)'),        -- ID 2
('Cơ sở dữ liệu', 'SQL Server, MySQL, MongoDB');                                    -- ID 3

-- 3. Thêm dữ liệu bảng courses (Khóa học)
-- instructor_id = 1 (Nguyễn Văn Giảng), category_id = 1 (Lập trình Web)
INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image, status) VALUES 
('Lập trình PHP & MySQL cơ bản', 'Khóa học dành cho người mới bắt đầu làm quen với Back-end', 1, 1, 500000, 8, 'Beginner', 'php-course.jpg', 'approved'),
('Fullstack với ReactJS và NodeJS', 'Xây dựng website trọn gói từ A-Z', 1, 1, 1200000, 12, 'Advanced', 'fullstack.jpg', 'pending'),
('SQL cho người mới', 'Học truy vấn dữ liệu cơ bản', 1, 3, 200000, 4, 'Beginner', 'sql.jpg', 'approved');

-- 4. Thêm dữ liệu bảng enrollments (Học viên đăng ký học)
-- Học viên ID 2 (Trần Học Viên) đăng ký khóa học ID 1 (PHP)
INSERT INTO enrollments (course_id, student_id, status, progress) VALUES 
(1, 2, 'active', 15),   -- Đang học, tiến độ 15%
(3, 2, 'completed', 100); -- Đã học xong khóa SQL

-- 5. Thêm dữ liệu bảng lessons (Bài học cho khóa PHP - Course ID 1)
INSERT INTO lessons (course_id, title, content, video_url, order_num) VALUES 
(1, 'Bài 1: Giới thiệu về PHP', 'Nội dung bài học giới thiệu lịch sử PHP...', 'https://youtu.be/video1', 1),
(1, 'Bài 2: Cài đặt môi trường XAMPP', 'Hướng dẫn cài đặt Apache và MySQL...', 'https://youtu.be/video2', 2),
(1, 'Bài 3: Biến và kiểu dữ liệu', 'Cách khai báo biến trong PHP...', 'https://youtu.be/video3', 3);

-- 6. Thêm dữ liệu bảng materials (Tài liệu cho bài học)
-- Tài liệu cho Bài 1 (Lesson ID 1)
INSERT INTO materials (lesson_id, filename, file_path, file_type) VALUES 
(1, 'slide_bai_1.pdf', 'uploads/materials/slide1.pdf', 'pdf'),
(1, 'source_code_bai_1.zip', 'uploads/materials/code1.zip', 'zip'),
(2, 'huong_dan_cai_dat.docx', 'uploads/materials/install.docx', 'doc');