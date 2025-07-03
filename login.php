<?php
session_start();
include('includes/config.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password_input = $_POST['password'];
// sửa dòng 11
$stmt = mysqli_prepare($link, "
    SELECT users.id, users.password, user_profiles.avatar
    FROM users
    LEFT JOIN user_profiles ON users.id = user_profiles.user_id
    WHERE users.username = ?
");

if (!$stmt) {
    die("Lỗi prepare: " . mysqli_error($link));
}
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
      // sửa dòng 19
        mysqli_stmt_bind_result($stmt, $id, $password_db, $avatar);
        mysqli_stmt_fetch($stmt);

        if ($password_input === $password_db) {
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $id;
            $_SESSION['avatar'] = (!empty($avatar)) ? $avatar : 'default.png'; // swradongf 33
            header("Location: index.php");
            exit;
        } else {
            $msg = "Sai mật khẩu.";
        }
    } else {
        $msg = "Tài khoản không tồn tại.";
    }

    mysqli_stmt_close($stmt);
}
?>

<?php include("includes/header.php"); ?>

<link rel="stylesheet" href="assets/css/login.css">

<section class="form-container">
  <h2>Đăng nhập</h2>
  <?php if ($msg) echo "<div class='error'>$msg</div>"; ?>
  <form method="POST">
    <div class="form-group">
      <label for="username">Tên đăng nhập</label>
      <input type="text" name="username" required placeholder="Nhập tên đăng nhập">
    </div>
    <div class="form-group">
      <label for="password">Mật khẩu</label>
      <input type="password" name="password" required placeholder="Nhập mật khẩu">
    </div>
    <button type="submit">Đăng nhập</button>
  </form>
  <div class="link-note">
    <p>Chưa có tài khoản? <a href="signup.php">Đăng ký ngay</a></p>
  </div>
</section>

<?php include("includes/footer.php"); ?>
