<!-- Đăng xuất người dùng 
    - Xóa session người dùng
    - Chuyển hướng về trang đăng nhập
    - Hiển thị thông báo đăng xuất thành công
    - Nếu người dùng chưa đăng nhập thì chuyển hướng về trang đăng nhập

  -->
    <?php
session_start();
session_unset();    // Xóa toàn bộ biến session
session_destroy();  // Hủy session

header("Location: index.php");  // Quay về trang chủ
exit();
?>
