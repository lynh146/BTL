<?php
session_start();
include('includes/config.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username       = trim($_POST['username']);
    $email          = trim($_POST['email']);
    $phone          = trim($_POST['phone']);
    $gender         = $_POST['gender'];
    $dob            = $_POST['dob'];
    $password_input = $_POST['password'];
    $re_password    = $_POST['re_password'];

    if ($password_input !== $re_password) {
        $msg = "❌ Mật khẩu nhập lại không khớp.";
    } else {
        // Kiểm tra username
        $stmt = mysqli_prepare($link, "SELECT id FROM users WHERE username = ?");
        if (!$stmt) die("❌ Lỗi SQL username: " . mysqli_error($link));
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $msg = "❌ Tên đăng nhập đã tồn tại.";
        } else {
            mysqli_stmt_close($stmt);

            // Kiểm tra email
            $stmt = mysqli_prepare($link, "SELECT id FROM users WHERE email = ?");
            if (!$stmt) die("❌ Lỗi SQL email: " . mysqli_error($link));
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $msg = "❌ Email đã được sử dụng.";
            } else {
                mysqli_stmt_close($stmt);

                // Kiểm tra phone
                $stmt = mysqli_prepare($link, "SELECT user_id FROM user_profiles WHERE phone = ?");
                if (!$stmt) die("❌ Lỗi SQL phone: " . mysqli_error($link));
                mysqli_stmt_bind_param($stmt, "s", $phone);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $msg = "❌ Số điện thoại đã được sử dụng.";
                } else {
                    mysqli_stmt_close($stmt);

                    // Hash mật khẩu
                    $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

                    // Chèn vào bảng users
                    $insert_users = mysqli_prepare($link, "INSERT INTO users (username, email, password, created_at) VALUES (?, ?, ?, NOW())");
                    if (!$insert_users) die("❌ Lỗi SQL users: " . mysqli_error($link));
                    mysqli_stmt_bind_param($insert_users, "sss", $username, $email, $hashed_password);

                    if (mysqli_stmt_execute($insert_users)) {
                        $user_id = mysqli_insert_id($link);
                        mysqli_stmt_close($insert_users);

                        // Chèn vào bảng user_profiles
                        $insert_profile = mysqli_prepare($link, "INSERT INTO user_profiles (user_id, email, phone, gender, birthday) VALUES (?, ?, ?, ?, ?)");
                        if (!$insert_profile) die("❌ Lỗi SQL user_profiles: " . mysqli_error($link));
                        mysqli_stmt_bind_param($insert_profile, "issss", $user_id, $email, $phone, $gender, $dob);

                        if (mysqli_stmt_execute($insert_profile)) {
                            $_SESSION['username'] = $username;
                            $_SESSION['user_id']  = $user_id;
                            header("Location: index.php");
                            exit;
                        } else {
                            $msg = "❌ Đăng ký thất bại khi lưu thông tin profile.";
                        }

                        mysqli_stmt_close($insert_profile);
                    } else {
                        $msg = "❌ Đăng ký thất bại khi lưu thông tin user.";
                    }
                }
            }
        }

        if (isset($stmt) && $stmt) mysqli_stmt_close($stmt);
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
