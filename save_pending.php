<?php
include 'includes/config.php'; // chứa $link

$name = $_POST['name'];
$location = $_POST['location'];
$price = $_POST['price_level'];
$cat = (int)$_POST['category_id'];
$desc = $_POST['description'];
$img = $_POST['image_url'];
$user_id = 1; // tạm để 1 hoặc $_SESSION['user_id'] nếu có đăng nhập

$sql = "INSERT INTO restaurants_pending 
        (name, location, price_level, category_id, description, image_url, user_id) 
        VALUES 
        ('$name', '$location', '$price', $cat, '$desc', '$img', $user_id)";

if (mysqli_query($link, $sql)) {
    echo "✅ Gửi đề xuất thành công! Vui lòng chờ admin duyệt.";
} else {
    echo "❌ Lỗi: " . mysqli_error($link);
}
?>