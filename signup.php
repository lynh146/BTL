<?php
session_start();
include('includes/config.php');

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password_input = $_POST['password'];

    $stmt = mysqli_prepare($link, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $msg = "Tên đăng nhập đã tồn tại.";
    } else {
        $insert = mysqli_prepare($link, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insert, "sss", $username, $email, $password_input);
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
?>

<?php include("includes/header.php"); ?>

<style>
  .form-container {
    max-width: 420px;
    margin: 60px auto;
    background: #fffaf5;
    padding: 40px;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    font-family: 'Inter', sans-serif;
  }

  .form-container h2 {
    text-align: center;
    color: #ff7043;
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 25px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #333;
  }

  .form-group input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s ease;
  }

  .form-group input:focus {
    border-color: #ff7043;
    outline: none;
    box-shadow: 0 0 0 2px rgba(255, 112, 67, 0.15);
  }

  button {
    width: 100%;
    background-color: #ff7043;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  button:hover {
    background-color: #e65100;
  }

  .error {
    color: #d32f2f;
    text-align: center;
    margin-bottom: 15px;
    font-weight: 500;
  }

  .link-note {
    text-align: center;
    margin-top: 20px;
    font-size: 0.95rem;
  }

  .link-note a {
    color: #ff7043;
    text-decoration: none;
    font-weight: 500;
  }

  .link-note a:hover {
    text-decoration: underline;
    color: #e65100;
  }
</style>

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
      <label for="password">Mật khẩu</label>
      <input type="password" name="password" required placeholder="Nhập mật khẩu">
    </div>

    <button type="submit">Đăng ký</button>
  </form>
</section>

<?php include("includes/footer.php"); ?>
