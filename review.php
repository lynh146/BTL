<?php
session_start();
include("includes/header.php");
include("includes/config.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=review.php");
    exit();
}

// Lấy ID từ URL nếu có (viết đánh giá cho quán cụ thể)
$restaurant_id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$restaurant_name = '';
$restaurant_location = '';

// Nếu có ID thì lấy tên & địa chỉ quán đó để đổ sẵn vào form
if ($restaurant_id) {
    $stmt = $link->prepare("SELECT name, location FROM restaurants WHERE id = ?");
    $stmt->bind_param("i", $restaurant_id);
    $stmt->execute();
    $stmt->bind_result($restaurant_name, $restaurant_location);
    $stmt->fetch();
    $stmt->close();
}

// Lấy tất cả quán để hiện datalist (cho phép tạo mới nếu muốn)
$sql = "SELECT id, name, location FROM restaurants";
$result = $link->query($sql);
$restaurants = [];
while ($row = mysqli_fetch_assoc($result)) {
    $restaurants[] = $row;
}

// Gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restaurant_name = trim($_POST['restaurant_name']);
    $restaurant_location = trim($_POST['restaurant_location']);
    $price_level = $_POST['price_level'];
    $rating = (float)$_POST['rating'];
    $review_content = trim($_POST['review_content']);
    $user_id = $_SESSION['user_id'];

    // Nếu chưa có ID → kiểm tra quán đó đã tồn tại chưa
    if (!$restaurant_id) {
        foreach ($restaurants as $res) {
            if (strtolower($res['name']) === strtolower($restaurant_name)) {
                $restaurant_id = $res['id'];
                $restaurant_location = $res['location'];
                break;
            }
        }
    }

    // Nếu quán chưa tồn tại → thêm vào bảng chờ duyệt restaurants_pending
    if (!$restaurant_id) {
        $stmt = $link->prepare("INSERT INTO restaurants_pending (name, location, price_level, rating, category_id, image_url) VALUES (?, ?, ?, 0, 1, '')");
        $stmt->bind_param("sss", $restaurant_name, $restaurant_location, $price_level);
        $stmt->execute();
        $restaurant_id = $stmt->insert_id;
    }

    // Thêm đánh giá vào bảng reviews (chờ duyệt)
    $stmt = $link->prepare("INSERT INTO reviews (restaurant_id, user_id, content, rating, is_approved) VALUES (?, ?, ?, ?, 0)");
    $stmt->bind_param("iisd", $restaurant_id, $user_id, $review_content, $rating);
    $stmt->execute();
    $review_id = $stmt->insert_id;

    // Upload ảnh nếu có
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $image_path = $upload_dir . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        $stmt = $link->prepare("INSERT INTO images (restaurant_id, review_id, image_url, uploaded_by) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $restaurant_id, $review_id, $image_path, $user_id);
        $stmt->execute();
    }

    // Không cập nhật rating trung bình vì đánh giá chưa được duyệt
    echo "<script>alert('Gửi đánh giá thành công! Chờ admin duyệt.'); window.location.href='index.php';</script>";
    exit();
}

    // Cập nhật rating trung bình
// $sql = "
//     UPDATE restaurants r
//     LEFT JOIN (
//         SELECT restaurant_id, ROUND(AVG(rating), 1) AS avg_rating
//         FROM reviews
//         GROUP BY restaurant_id
//     ) AS avg_r
//     ON r.id = avg_r.restaurant_id
//     SET r.rating = IFNULL(avg_r.avg_rating, 0)
// ";

// if (mysqli_query($link, $sql)) {
//     echo "✅ Đã cập nhật rating trung bình cho tất cả nhà hàng.";
// } else {
//     echo "❌ Lỗi: " . mysqli_error($link);
// }
//     echo "<script>alert('Gửi đánh giá thành công!'); window.location.href='index.php';</script>";
//     exit();
// }
// ?>

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
        <input list="restaurant-list" name="restaurant_name" id="restaurant_name"
            value="<?= htmlspecialchars($restaurant_name) ?>" <?= $restaurant_id ? 'readonly' : '' ?> required>
        <datalist id="restaurant-list">
            <?php foreach ($restaurants as $res): ?>
            <option value="<?= htmlspecialchars($res['name']) ?>"></option>
            <?php endforeach; ?>
        </datalist>

        <label for="restaurant_location">Địa chỉ quán:</label>
        <input type="text" name="restaurant_location" id="restaurant_location"
            value="<?= htmlspecialchars($restaurant_location) ?>" <?= $restaurant_id ? 'readonly' : '' ?>>

        <label for="image">Hình ảnh:</label>
        <input type="file" name="image" accept="image/*">

        <label for="price_level">Mức giá:</label>
        <select name="price_level" required>
            <option value="">-- Chọn giá --</option>
            <option value="Rẻ">Rẻ</option>
            <option value="Trung bình">Trung bình</option>
            <option value="Đắt">Đắt</option>
        </select>

        <label for="rating">Đánh giá sao:</label>
        <div class="star-rating" id="ratingStars">
            <span class="star" data-star="1">★</span>
            <span class="star" data-star="2">★</span>
            <span class="star" data-star="3">★</span>
            <span class="star" data-star="4">★</span>
            <span class="star" data-star="5">★</span>
        </div>
        <input type="hidden" name="rating" id="rating" value="0">

        <label for="review_content">Nội dung đánh giá:</label>
        <textarea name="review_content" rows="5" required></textarea>

        <button type="submit">Gửi đánh giá</button>
    </form>
</body>
<script>
const stars = document.querySelectorAll(".star");
const ratingInput = document.getElementById("rating");

stars.forEach((star, index) => {
    star.addEventListener("mouseover", () => {
        stars.forEach((s, i) => {
            s.classList.toggle("hover", i <= index);
        });
    });

    star.addEventListener("mouseout", () => {
        stars.forEach(s => s.classList.remove("hover"));
    });

    star.addEventListener("click", () => {
        ratingInput.value = index + 1;
        stars.forEach((s, i) => {
            s.classList.toggle("selected", i <= index);
        });
    });
});
</script>

</html>

<?php include("includes/footer.php"); ?>