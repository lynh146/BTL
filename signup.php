<?php
session_start();
include('includes/config.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender']; 
    $dob = $_POST['dob'];
    $password_input = $_POST['password'];
    $re_password = $_POST['re_password'];

    if ($password_input !== $re_password) {
        $msg = "Mật khẩu nhập lại không khớp.";
    } else {
        $stmt = mysqli_prepare($link, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = "Tên đăng nhập đã tồn tại.";
        } else {
            $insert = mysqli_prepare($link, "INSERT INTO users (username, email, phone, gender, dob, password) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssssss", $username, $email, $phone, $gender, $dob, $password_input);
            if (mysqli_stmt_execute($insert)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = mysqli_insert_id($link);
                header("Location: index.php");
                exit;
            } else {
                $msg = "Đăng ký thất bại. Vui lòng thử lại.";
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<?php include("includes/header.php"); ?>

<link rel="stylesheet" href="assets/css/signup.css">

<section class="form-container">
  <h2>Đăng ký tài khoản</h2>
  <?php if ($msg) echo "<div class='error'>$msg</div>"; ?>
  <form method="POST">
    <div class="form-group">
      <label for="username">Tên đăng nhập</label>
      <input type="text" name="username" required placeholder="Nhập tên đăng nhập">
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" name="email" required placeholder="Nhập email">
    </div>

    <div class="form-group">
      <label for="phone">Số điện thoại</label>
      <input type="tel" name="phone" required placeholder="Nhập số điện thoại">
    </div>

    <div class="form-group">
      <label for="gender">Giới tính</label>
      <select name="gender" required>
        <option value="">-- Chọn giới tính --</option>
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
        <option value="Khác">Khác</option>
      </select>
    </div>

    <div class="form-group">
      <label for="dob">Ngày tháng năm sinh</label>
      <input type="date" name="dob" required>
    </div>

    <div class="form-group">
      <label for="password">Tạo mật khẩu</label>
      <input type="password" name="password" required placeholder="Nhập mật khẩu">
    </div>

    <div class="form-group">
      <label for="re_password">Nhập lại mật khẩu</label>
      <input type="password" name="re_password" required placeholder="Nhập lại mật khẩu">
    </div>

    <button type="submit">Đăng ký</button>
  </form>

  <div class="link-note">
    Đã có tài khoản? <a href="login.php">Đăng nhập</a>
  </div>
</section>

<?php include("includes/footer.php"); ?>
