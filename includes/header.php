<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Ăn Gì Hôm Nay?</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/header_user.css">

</head>
<body>
  <header>
    <div class="left-header">
      <div class="logo">Ăn Gì Hôm Nay?</div>
      <nav class="nav-left">
        <a href="index.php">Trang chủ</a>
        <a href="#">Quán nổi bật</a>
        <a href="#">Viết đánh giá</a>
      </nav>
    </div>
    <nav class="nav-right">
      <?php if (isset($_SESSION['username'])): ?>
        <!-- Thông báo -->
        <div class="notification">
          🔔
          <div class="notif-dropdown">
            📢 Bạn chưa có thông báo mới!
          </div>
        </div>

        <!-- Avatar + Dropdown -->
        <div class="user-menu">
          <!-- sửa dòng 39 -->
        <?php
          $avatar = isset($_SESSION['avatar']) && !empty($_SESSION['avatar']) 
                    ? htmlspecialchars($_SESSION['avatar']) 
                    : 'default.png';
        ?>
        <img src="assets/img/<?= htmlspecialchars($_SESSION['avatar']) ?>" class="avatar" alt="User Avatar">

        <div class="dropdown">
          <a href="profile.php">Trang cá nhân</a>
          <a href="#">Đổi mật khẩu</a>
          <a href="#">Quán yêu thích</a>
          <a href="#">Đánh giá của tôi</a>
          <a href="#">Đánh giá đã báo cáo</a>
          <a href="logout.php">Đăng xuất</a>
        </div>
      </div>
    <?php else: ?>
      <a href="login.php">Đăng nhập</a>
      <a href="signup.php">Đăng ký</a>
    <?php endif; ?>
  </nav>
</header>
<script src="assets/js/header_user.js"></script>

</body>
</html>