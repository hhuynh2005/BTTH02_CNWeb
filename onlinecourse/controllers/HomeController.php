<?php
class HomeController {
    public function index() {
        echo "<h1>Trang chủ - Đăng nhập thành công!</h1>";
        echo '<a href="index.php?controller=auth&action=logout">Đăng xuất</a>';
    }
}
?>