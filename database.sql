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
-- Đảm bảo giá tiền không âm
ALTER TABLE courses
ADD CONSTRAINT check_price CHECK (price >= 0);

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
-- Ngăn chặn việc một học viên đăng ký trùng khóa học
-- Đảm bảo tiến độ học chỉ từ 0 đến 100 (Nếu dùng MySQL 8.0.16 trở lên)
ALTER TABLE enrollments 
ADD CONSTRAINT unique_student_course UNIQUE (student_id, course_id),
ADD CONSTRAINT check_progress CHECK (progress >= 0 AND progress <= 100);


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
-- Đảm bảo thứ tự bài học không âm
ALTER TABLE lessons
ADD CONSTRAINT check_order CHECK (order_num > 0);

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
(1, 'PHP - Bài 1: Cài đặt Xampp', 'Hướng dẫn tải và cài đặt Xampp. Bật Apache và MySQL để tạo máy chủ ảo (Localhost). Tìm hiểu thư mục htdocs để lưu file web.', 'https://youtu.be/o_M4XsWzKRw', 1),
(1, 'PHP - Bài 2: Cú Pháp PHP', 'Quy tắc cú pháp cơ bản: thẻ mở <?php và thẻ đóng ?>. Cách tạo file index.php và chạy trên trình duyệt. Sử dụng lệnh echo để in nội dung.', 'https://youtu.be/HqR5Oq-x2tQ', 2),
(1, 'PHP - Bài 3: Bình luận trong PHP', 'Cách sử dụng Comment để ghi chú code. Comment một dòng dùng // hoặc #. Comment nhiều dòng dùng /* ... */.', 'https://youtu.be/hZLydBc9Kh8', 3),
(1, 'PHP - Bài 4: Chèn HTML vào PHP', 'Hướng dẫn cách lồng ghép các thẻ HTML (như h1, br) vào trong câu lệnh echo của PHP để định dạng văn bản hiển thị.', 'https://youtu.be/P_37QuHanVU', 4),
(1, 'PHP - Bài 5: Mẹo nhớ code & Tư duy', 'Phương pháp tư duy lập trình và cách sử dụng phần mềm Simple Mind Desktop để lưu trữ kiến thức, tạo "bộ não thứ hai" giúp ghi nhớ code.', 'https://youtu.be/IM1nl8w3vfs', 5),
(1, 'PHP - Bài 6: Biến trong PHP', 'Khái niệm về biến (Variable). Quy tắc đặt tên biến với dấu $. Các kiểu dữ liệu cơ bản (chuỗi, số) và cách gán giá trị.', 'https://youtu.be/l90_t3uPCJQ', 6),
(1, 'PHP - Bài 7: Hằng trong PHP', 'Tìm hiểu về Hằng (Constant) - loại biến không thể thay đổi giá trị. Cách sử dụng hàm define() để khai báo hằng.', 'https://youtu.be/0vfxulYDR7E', 7),
(1, 'PHP - Bài 8: Nháy đơn và Nháy kép', 'Sự khác biệt quan trọng: Dấu nháy kép ("") sẽ in giá trị của biến bên trong, còn dấu nháy đơn ('') sẽ in tên biến như một chuỗi văn bản.', 'https://youtu.be/ouE6yzLzcWo', 8),
(2, 'React - Bài 1: Tổng quan ES6', 'Các tính năng mới của ES6 cần cho React: Arrow Function, khai báo biến Let/Const, Template Literals (chuỗi mẫu), Destructuring.', 'https://youtu.be/9urfEfkdusc', 1),
(2, 'React - Bài 2: Xử lý mảng (Array)', 'Các hàm xử lý mảng quan trọng trong JS/React: forEach (duyệt), map (tạo mảng mới), filter (lọc), reduce (tích lũy).', 'https://youtu.be/lCkL-3ReUpw', 2),
(2, 'React - Bài 3: Tổng quan ReactJS', 'Giới thiệu ReactJS là gì? Tại sao nên học React? Các khái niệm cốt lõi: Component, JSX và Virtual DOM.', 'https://youtu.be/jzLupKff3gI', 3),
(2, 'React - Bài 4: Cài đặt ReactJS', 'Cài đặt môi trường: NodeJS, NPM. Hướng dẫn sử dụng lệnh create-react-app để khởi tạo dự án React mới và chạy lệnh npm start.', 'https://youtu.be/_xLS_vTJGeo', 4),
(2, 'React - Bài 5: Virtual DOM', 'Giải thích cơ chế Virtual DOM (DOM ảo): Cách React so sánh (Diffing) và chỉ cập nhật những phần thay đổi lên DOM thật để tối ưu hiệu năng.', 'https://youtu.be/jtVk89TL0pQ', 5),
(3, 'HTML - Bài 1: Tổng quan Web', 'Cách Internet vận hành: Mô hình Client-Server, địa chỉ IP, DNS. Vai trò của 3 thành phần chính: HTML (khung xương), CSS (giao diện), JS (hành động).', 'https://youtu.be/tVdaxMMW9q8', 1),
(3, 'HTML - Bài 2: Web Tĩnh & Động', 'Phân biệt Static Website (Web tĩnh) và Dynamic Website (Web động). Khái niệm Front-end (phía người dùng) và Back-end (phía máy chủ).', 'https://youtu.be/PNBZUyfV7iI', 2),
(3, 'HTML - Bài 3: Cài đặt VS Code', 'Hướng dẫn tải và cài đặt Visual Studio Code. Cách tạo file index.html đầu tiên và cấu trúc HTML tiêu chuẩn (! + Tab).', 'https://youtu.be/bD7Kl__Kfvo', 3);
-- ---------------------------------------------------------
-- 6. NẠP TÀI LIỆU (MATERIALS)
-- ---------------------------------------------------------
INSERT INTO materials (lesson_id, filename, file_path, file_type) VALUES 
(1, 'slide_php_1.pdf', 'assets/uploads/materials/slide_php_1.pdf', 'pdf'),
(1, 'bai_tap_php_2.zip', 'assets/uploads/materials/bai_tap_php_2.zip', 'zip'),
(1, 'db_sample.sql', 'assets/uploads/materials/db_sample.sql', 'sql'),
(2, 'react_cheatsheet.png', 'assets/uploads/materials/react_cheatsheet.png', 'image'),
(3, 'html_boilerplate.html', 'assets/uploads/materials/html_boilerplate.html', 'code');

-- Bật lại kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 1;