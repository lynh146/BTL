<?php
session_start();
include("includes/header.php"); 
include('includes/config.php'); // Gọi file kết nối CSDL

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=review.php");
    exit();
}

// Lấy danh sách quán từ DB
$sql = "SELECT id, name, location FROM restaurants";
$result = $link->query($sql);
$restaurants = [];
while ($rev = mysqli_fetch_assoc($reviews)) {
    echo "<div class='review-item'>";
    echo "<strong>{$rev['username']}</strong> ({$rev['rating']}★): {$rev['content']}<br>";

    // Truy vấn ảnh dựa theo review_id
    $review_id = $rev['review_id'];
    $img_sql = "SELECT image_url FROM images WHERE review_id = $review_id LIMIT 1";
    $img_result = mysqli_query($link, $img_sql);
    $img = mysqli_fetch_assoc($img_result);

    if ($img && !empty($img['image_url'])) {
        echo "<img src='" . htmlspecialchars($img['image_url']) . "' alt='Ảnh đánh giá' 
                 style='max-width: 300px; margin-top: 8px; display: block; border-radius: 8px;'>";
    }

    echo "<em>{$rev['created_at']}</em>";
    echo "</div>";
}


// Xử lý khi người dùng gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_name = trim($_POST['restaurant_name']);
    $restaurant_location = trim($_POST['restaurant_location']);
    $price_level = $_POST['price_level'];
    $rating = (float)$_POST['rating'];
    $review_content = trim($_POST['review_content']);
    $user_id = $_SESSION['user_id'];

    // Kiểm tra xem quán đã tồn tại chưa
    $restaurant_id = null;
    foreach ($restaurants as $res) {
        if (strtolower($res['name']) === strtolower($restaurant_name)) {
            $restaurant_id = $res['id'];
            $restaurant_location = $res['location'];
            break;
        }
    }

    // Nếu quán chưa tồn tại → thêm mới
    if (!$restaurant_id) {
        $stmt = $link->prepare("INSERT INTO restaurants (name, location, price_level) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $restaurant_name, $restaurant_location, $price_level);
        if ($stmt->execute()) {
            $restaurant_id = $stmt->insert_id;
        } else {
            echo "<p style='color:red'>Lỗi khi thêm quán mới: " . $stmt->error . "</p>";
            exit();
        }
    }

    // ✅ BƯỚC 1: Thêm đánh giá trước
    $stmt = $link->prepare("INSERT INTO reviews (restaurant_id, user_id, content, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisd", $restaurant_id, $user_id, $review_content, $rating);
    if ($stmt->execute()) {
        $review_id = $stmt->insert_id; // ✅ lấy id review mới thêm

        // ✅ BƯỚC 2: Lưu ảnh nếu có, và gắn vào review_id
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $filename = uniqid() . '_' . basename($_FILES['image']['name']);
            $image_path = $upload_dir . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

            // Lưu vào bảng images (có cả review_id)
            $stmt = $link->prepare("INSERT INTO images (restaurant_id, review_id, image_url, uploaded_by) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iisi", $restaurant_id, $review_id, $image_path, $user_id);
            $stmt->execute();
        }

        echo "<script>alert('Gửi đánh giá thành công!'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<p style='color:red'>Lỗi khi gửi đánh giá: " . $stmt->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đánh giá quán ăn</title>
    <link rel="stylesheet" href="assets/css/review.css">
</head>

<body>
    <form method="POST" enctype="multipart/form-data">
        <h2>Viết đánh giá quán ăn</h2>

        <label for="restaurant_name">Tên quán:</label>
        <input list="restaurant-list" name="restaurant_name" required>
        <datalist id="restaurant-list">
            <?php foreach ($restaurants as $res): ?>
            <option value="<?= htmlspecialchars($res['name']) ?>">
                <?php endforeach; ?>
        </datalist>

        <label for="restaurant_location">Địa chỉ quán:</label>
        <input type="text" name="restaurant_location" required>

        <label for="image">Hình ảnh quán:</label>
        <input type="file" name="image" accept="image/*">

        <label for="price_level">Mức giá:</label>
        <select name="price_level" required>
            <option value="Rẻ">Rẻ</option>
            <option value="Trung bình">Trung bình</option>
            <option value="Đắt">Đắt</option>
        </select>

        <label for="rating">Đánh giá sao:</label>
        <select name="rating" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>"><?= $i ?> sao</option>
            <?php endfor; ?>
        </select>

        <label for="review_content">Nội dung đánh giá:</label>
        <textarea name="review_content" rows="5" required></textarea>

        <button type="submit">Gửi đánh giá</button>
    </form>
</body>
</html>
<?php include("includes/footer.php"); ?>