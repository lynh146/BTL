<?php
session_start();
include("includes/header.php");
include "includes/config.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=review.php");
    exit();}
// Lấy danh sách loại món
$cats = mysqli_query($link, "SELECT id, name FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $price_level = $_POST['price_level'];
    $category_id = (int)$_POST['category_id'];
    $rating = (int)$_POST['rating'];
    $description = $_POST['description'];

    // Upload ảnh
    $image = $_FILES['image']['name'];
    $target = "assets/img/uploads/" . basename($image);
    if (!is_dir("assets/img/uploads")) mkdir("assets/img/uploads", 0777, true);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        // Thêm dữ liệu vào bảng restaurants_pending
        $sql = "INSERT INTO restaurants_pending 
        (name, location, price_level, category_id, rating, description, image_url)
        VALUES 
        ('$name', '$location', '$price_level', $category_id, $rating, '$description', '$target')";

        $ok = mysqli_query($link, $sql);

        if ($ok) {
            $message = "<p style='color: green;'>🎉 Thêm quán thành công, chờ admin duyệt!</p>";
        } else {
            $message = "<p style='color: red;'>❌ Lỗi SQL: " . mysqli_error($link) . "</p>";
        }
    } else {
        $message = "<p style='color: red;'>❌ Lỗi upload ảnh!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thêm Quán Ăn</title>
  <link rel="stylesheet" href="assets/css/submit_restaurant.css">
  <script src="assets/js/jquery-3.7.1.min.js"></script>

</head>
<body>
  <h2 style="text-align:center;">➕ Đề xuất thêm quán ăn mới</h2>
  <?php if (!empty($message)) echo $message; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Tên quán:</label>
    <input type="text" name="name" required>

    <label>Địa chỉ:</label>
    <input type="text" name="location" required>

    <label>Loại món:</label>
    <select name="category_id" required>
      <option value="">-- Chọn loại --</option>
      <?php while ($c = mysqli_fetch_assoc($cats)) {
        echo "<option value='{$c['id']}'>{$c['name']}</option>";
      } ?>
    </select>

    <label>Mức giá:</label>
    <select name="price_level" required>
      <option value="">-- Chọn giá --</option>
      <option value="Rẻ">Rẻ</option>
      <option value="Trung bình">Trung bình</option>
      <option value="Đắt">Đắt</option>
    </select>

    <label>Ảnh quán:</label>
    <input type="file" name="image" required>

    <label>Đánh giá sao:</label>
    <div class="star-rating" id="ratingStars">
      <span class="star" data-star="1">★</span>
      <span class="star" data-star="2">★</span>
      <span class="star" data-star="3">★</span>
      <span class="star" data-star="4">★</span>
      <span class="star" data-star="5">★</span>
    </div>
    <input type="hidden" name="rating" id="rating" value="0">

    <label>Mô tả:</label>
    <textarea name="description" rows="5" placeholder="Mô tả chi tiết..."></textarea>

    <br><br>
    <button type="submit">📤 Gửi đề xuất</button>
  </form>

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
</body>
</html>
<?php include('includes/footer.php'); ?>
