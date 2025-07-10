<?php
session_start();
include('includes/config.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password_input = $_POST['password'];

    // 🔷 Thử đăng nhập admin trước
    $stmt = mysqli_prepare($link, "SELECT id, name, email, password FROM admins WHERE name = ?");
    if (!$stmt) die("Lỗi prepare (admin): " . mysqli_error($link));

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) === 1) {
        mysqli_stmt_bind_result($stmt, $id, $name, $email, $password_db);
        mysqli_stmt_fetch($stmt);

        if (password_verify($password_input, $password_db)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $email;

            header("Location: index_admin.php");
            exit;
        } else {
            $msg = "Sai mật khẩu (admin).";
        }
        mysqli_stmt_close($stmt);

    } else {
        mysqli_stmt_close($stmt);

        // 🔷 Thử đăng nhập user
        $stmt2 = mysqli_prepare($link, "
            SELECT users.id, users.password, user_profiles.avatar
            FROM users
            LEFT JOIN user_profiles ON users.id = user_profiles.user_id
            WHERE users.username = ?
        ");
        if (!$stmt2) die("Lỗi prepare (user): " . mysqli_error($link));

        mysqli_stmt_bind_param($stmt2, "s", $username);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_store_result($stmt2);

        if (mysqli_stmt_num_rows($stmt2) === 1) {
            mysqli_stmt_bind_result($stmt2, $id, $password_db, $avatar);
            mysqli_stmt_fetch($stmt2);

            if (password_verify($password_input, $password_db)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $id;
                $_SESSION['avatar'] = (!empty($avatar)) ? $avatar : 'default.png';

                header("Location: index.php");
                exit;
            } else {
                $msg = "Sai mật khẩu (user).";
            }
        } else {
            $msg = "Tài khoản không tồn tại.";
        }

        mysqli_stmt_close($stmt2);
    }
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