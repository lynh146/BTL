<?php
include 'includes/config.php';

echo "<h3>🔐 Đang mã hóa mật khẩu admin...</h3>";

$sql = "SELECT id, password FROM admins";
$result = mysqli_query($link, $sql);

if (!$result) {
    die("❌ Lỗi SQL: " . mysqli_error($link));
}

$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $plain = $row['password'];
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    $update = mysqli_query($link, "UPDATE admins SET password='$hash' WHERE id=$id");
    if ($update) {
        echo "✅ Admin ID $id: Đã mã hóa.<br>";
        $count++;
    } else {
        echo "❌ Admin ID $id: Lỗi khi cập nhật: " . mysqli_error($link) . "<br>";
    }
}

echo "<p>🎉 Đã mã hóa $count tài khoản admin.</p>";
