import os
def create_structure():
    root = "onlinecourse"
    
    # Danh sách các file cần tạo (kèm đường dẫn tương đối)
    files = [
        # Root files
        ".htaccess",
        "index.php",
        "README.md",
        
        # Config
        "config/Database.php",
        
        # Controllers
        "controllers/HomeController.php",
        "controllers/AuthController.php",
        "controllers/CourseController.php",
        "controllers/EnrollmentController.php",
        "controllers/LessonController.php",
        "controllers/AdminController.php",
        
        # Models
        "models/User.php",
        "models/Course.php",
        "models/Category.php",
        "models/Enrollment.php",
        "models/Lesson.php",
        "models/Material.php",
        
        # Views - Layouts
        "views/layouts/header.php",
        "views/layouts/footer.php",
        "views/layouts/sidebar.php",
        
        # Views - Home
        "views/home/index.php",
        
        # Views - Courses
        "views/courses/index.php",
        "views/courses/detail.php",
        "views/courses/search.php",
        
        # Views - Auth
        "views/auth/login.php",
        "views/auth/register.php",
        
        # Views - Student
        "views/student/dashboard.php",
        "views/student/my_courses.php",
        "views/student/course_progress.php",
        
        # Views - Instructor (Files nằm trực tiếp trong folder instructor)
        "views/instructor/dashboard.php",
        "views/instructor/my_courses.php",
        
        # Views - Instructor Subfolders
        "views/instructor/course/create.php",
        "views/instructor/course/edit.php",
        "views/instructor/course/manage.php",
        
        "views/instructor/lessons/create.php",
        "views/instructor/lessons/edit.php",
        "views/instructor/lessons/manage.php",
        
        "views/instructor/materials/upload.php",
        
        "views/instructor/students/list.php",
        
        # Views - Admin
        "views/admin/dashboard.php",
        "views/admin/users/manage.php",
        "views/admin/categories/create.php",
        "views/admin/categories/edit.php",
        "views/admin/categories/list.php",
        "views/admin/reports/statistics.php",
        
        # Assets
        "assets/css/style.css",
        "assets/js/script.js",
    ]

    # Danh sách các thư mục rỗng cần tạo (nơi không có file cụ thể nào trong sơ đồ)
    empty_dirs = [
        "assets/uploads/courses",
        "assets/uploads/materials",
        "assets/uploads/avatars"
    ]

    print(f"Đang khởi tạo dự án '{root}'...")

    # 1. Tạo các file và thư mục cha của chúng
    for file_path in files:
        full_path = os.path.join(root, file_path)
        # Tạo thư mục chứa file nếu chưa tồn tại
        os.makedirs(os.path.dirname(full_path), exist_ok=True)
        
        # Tạo file rỗng
        if not os.path.exists(full_path):
            with open(full_path, 'w', encoding='utf-8') as f:
                pass # Tạo file rỗng
            print(f"✔ Đã tạo file: {file_path}")
        else:
            print(f"⚠ Đã tồn tại: {file_path}")

    # 2. Tạo các thư mục rỗng cụ thể
    for dir_path in empty_dirs:
        full_path = os.path.join(root, dir_path)
        if not os.path.exists(full_path):
            os.makedirs(full_path, exist_ok=True)
            print(f"✔ Đã tạo thư mục: {dir_path}")
        else:
            print(f"⚠ Đã tồn tại thư mục: {dir_path}")

    print("\n------------------------------------------------")
    print(f"HOÀN TẤT! Cấu trúc dự án đã được tạo tại thư mục: {os.path.abspath(root)}")

if __name__ == "__main__":
    create_structure()