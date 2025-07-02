<?php
session_start();
require_once "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Xử lý cập nhật
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES['avatar'])) {
        $target_dir = "assets/img/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        $file_name = basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . time() . "_" . $file_name;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $filename_only = basename($target_file);
            $update_avatar = mysqli_prepare($link, "UPDATE user_profiles SET avatar = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($update_avatar, "si", $filename_only, $user_id);
            mysqli_stmt_execute($update_avatar);
            $_SESSION['avatar'] = $filename_only;
        }
    }

    $fields = ['email', 'phone', 'gender', 'birthday', 'address', 'bio'];
    $values = [];
    foreach ($fields as $field) {
        $values[$field] = trim($_POST[$field] ?? '');
    }

    $update_info = mysqli_prepare($link, "UPDATE user_profiles SET email=?, phone=?, gender=?, birthday=?, address=?, bio=? WHERE user_id=?");
    mysqli_stmt_bind_param($update_info, "ssssssi", $values['email'], $values['phone'], $values['gender'], $values['birthday'], $values['address'], $values['bio'], $user_id);
    mysqli_stmt_execute($update_info);
}

// Lấy thông tin người dùng
$sql = "SELECT u.username, up.email, up.phone, up.avatar, up.gender, up.birthday, up.address, up.bio
        FROM users u
        LEFT JOIN user_profiles up ON u.id = up.user_id
        WHERE u.id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
?>

<?php include("includes/header.php"); ?>
<link rel="stylesheet" href="assets/css/profile.css">

<div class="profile-container">
  <div class="profile-card">
    <div class="profile-avatar">
      <?php
        $avatar_file = $user['avatar'] ?? 'default.png';
        $avatar_path = "assets/img/" . $avatar_file;
      ?>
      <img src="<?= htmlspecialchars($avatar_path) ?>" class="avatar" alt="Avatar">
      <form method="POST" enctype="multipart/form-data">
        <label>Đổi ảnh đại diện:</label>
        <input type="file" name="avatar" accept="image/*">
        <button type="submit">Tải lên</button>
      </form>
    </div>

    <div class="profile-details">
      <h2><?= htmlspecialchars($user['username']) ?></h2>
      <form method="POST">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>

        <label>Số điện thoại</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>

        <label>Giới tính</label>
        <select name="gender" required>
          <option value="Nam" <?= $user['gender'] === 'Nam' ? 'selected' : '' ?>>Nam</option>
          <option value="Nữ" <?= $user['gender'] === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
          <option value="Khác" <?= $user['gender'] === 'Khác' ? 'selected' : '' ?>>Khác</option>
        </select>

        <label>Ngày sinh</label>
        <input type="date" name="birthday" value="<?= htmlspecialchars($user['birthday'] ?? '') ?>">

        <label>Địa chỉ</label>
        <textarea name="address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

        <label>Giới thiệu bản thân</label>
        <textarea name="bio"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

        <button type="submit">Cập nhật thông tin</button>
      </form>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
