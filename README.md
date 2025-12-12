# 📚 DỰ ÁN WEBSITE QUẢN LÝ KHÓA HỌC ONLINE

# Môn học: Công nghệ Web - CSE485

---

## 🚀 GIỚI THIỆU CHUNG

Dự án **Website Quản lý Khóa học Online** được xây dựng nhằm đáp ứng các yêu cầu của Bài tập Thực hành số 02 (2025 – K65), Khoa CNTT, Trường Đại học Thủy Lợi. Website áp dụng mô hình kiến trúc **MVC (Model - View - Controller)** và sử dụng ngôn ngữ **PHP** kết hợp với **MySQL** để quản lý các khóa học, bài học, tài liệu, và tiến độ học tập của học viên.

### 🌟 Mục tiêu chính của dự án:

- Áp dụng thành thạo lập trình Hướng đối tượng (OOP) trong PHP.
- Hiểu và triển khai mô hình MVC trong tổ chức mã nguồn.
- Xây dựng hệ thống với hai vai trò chính: Học viên và Giảng viên/Quản trị viên.

## 🛠️ CÔNG NGHỆ SỬ DỤNG

| Công nghệ             | Mô tả                                                            |
| :-------------------- | :--------------------------------------------------------------- |
| **Backend**           | PHP (OOP), MySQL, PDO (để kết nối CSDL an toàn)                  |
| **Kiến trúc**         | Mô hình MVC (Model-View-Controller)                              |
| **Frontend**          | HTML5, CSS3, JavaScript, Bootstrap 5.x, FontAwesome              |
| **Quản lý phiên bản** | Git & GitHub (sử dụng branching, pull request cho làm việc nhóm) |
| **Thiết kế**          | Figma (phác họa giao diện)                                       |

## 👥 THÀNH VIÊN NHÓM VÀ PHÂN CHIA CÔNG VIỆC

**Giảng viên hướng dẫn:** **ThS. Tạ Chí Hiếu**

Nhóm gồm 4 thành viên, thực hiện phân chia công việc theo yêu cầu bài tập:

| STT | Họ và Tên         | Mã số sinh viên (MSSV) | Phân công chính                                                                       |
| :-- | :---------------- | :--------------------- | :------------------------------------------------------------------------------------ |
| 1   | Phạm Quang Anh    | 2351170573             | **Nhóm 1:** Đăng nhập, Đăng ký, Quản lý tài khoản (Controller, Model, View)           |
| 2   | Nguyễn Văn Trường | 2351170625             | **Nhóm 2:** Tạo/Sửa/Xóa Khóa học, Quản lý Bài học cho Giảng viên                      |
| 3   | Nguyễn Tùng Dương | 2351170588             | **Nhóm 3:** Hiển thị DS Khóa học, Tìm kiếm, Đăng ký & Theo dõi Tiến độ                |
| 4   | Nguyễn Văn Huỳnh  | 2351170599             | **Nhóm 4:** Thiết kế giao diện (HTML/CSS/JS), Tích hợp Upload File (Tài liệu, Avatar) |

_(Lưu ý: Công việc chung bao gồm Thiết kế CSDL, Cấu trúc MVC, và Quy tắc bảo mật.)_

## 🏛️ CẤU TRÚC THƯ MỤC DỰ ÁN

Dự án tuân theo cấu trúc thư mục MVC chuẩn như sau:

## 💾 THIẾT KẾ CƠ SỞ DỮ LIỆU (CSDL)

Dự án sử dụng CSDL **onlinecourse** với các bảng chính sau (Được tạo bằng MySQL Workbench / phpMyAdmin):

| Bảng          | Chức năng                                                      | Khóa ngoại (FK)                                             |
| :------------ | :------------------------------------------------------------- | :---------------------------------------------------------- |
| `users`       | Lưu thông tin người dùng (Học viên, Giảng viên, Quản trị viên) | -                                                           |
| `categories`  | Lưu danh mục khóa học                                          | -                                                           |
| `courses`     | Lưu thông tin khóa học                                         | `instructor_id` (FK: users), `category_id` (FK: categories) |
| `enrollments` | Ghi nhận việc học viên đăng ký khóa học                        | `course_id` (FK: courses), `student_id` (FK: users)         |
| `lessons`     | Lưu danh sách bài học trong từng khóa học                      | `course_id` (FK: courses)                                   |
| `materials`   | Lưu các file tài liệu đính kèm bài học                         | `lesson_id` (FK: lessons)                                   |

## ⚙️ HƯỚNG DẪN CÀI ĐẶT VÀ CHẠY DỰ ÁN

1.  **Môi trường:** Cài đặt môi trường máy chủ cục bộ (XAMPP/WAMP/Laragon) hỗ trợ **PHP** và **MySQL**.
2.  **Clone Repository:**
    ```bash
    git clone [URL_REPOSITORY_CỦA_NHÓM]
    cd onlinecourse
    ```
3.  **Cấu hình CSDL:**
    - Tạo CSDL tên là `onlinecourse` trong phpMyAdmin hoặc MySQL Workbench.
    - Sử dụng file SQL đính kèm (nếu có) để tạo cấu trúc bảng (`users`, `courses`, `enrollments`, v.v.).
    - Cập nhật thông tin kết nối CSDL trong file `config/Database.php`.
4.  **Chạy ứng dụng:**
    - Khởi động Apache và MySQL.
    - Truy cập dự án qua trình duyệt: `http://localhost/onlinecourse/` (hoặc cấu hình Virtual Host).

## 🔒 LƯU Ý BẢO MẬT & CHẤT LƯỢNG

- **Bảo mật mật khẩu:** Sử dụng `password hashing` (bcrypt/argon2) cho tất cả mật khẩu người dùng.
- **Bảo mật CSDL:** Luôn sử dụng `Prepared Statements` (qua PDO) để ngăn chặn tấn công SQL Injection.
- **Phân quyền (RBAC):** Triển khai kiểm tra quyền truy cập (`role-based access control`) cho từng trang (Ví dụ: Giảng viên role `1`, Học viên role `0`).

---

_Dự án được phát triển bởi nhóm sinh viên K65 theo hướng dẫn của khoa CNTT, Đại học Thủy Lợi._
