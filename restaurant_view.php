<?php include('includes/header.php'); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?php echo $quan['name']; ?> - Chi tiết quán ăn</title>
  <link rel="stylesheet" href="assets/css/restaurant_view.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
<?php
    include("includes/config.php");
    mysqli_set_charset($link, "utf8");

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    mysqli_query($link, "UPDATE restaurants SET count = count + 1 WHERE id = $id");

    $sql = "SELECT r.*, c.name AS category_name 
            FROM restaurants r 
            LEFT JOIN categories c ON r.category_id = c.id 
            WHERE r.id = $id";
    $result = mysqli_query($link, $sql);
    $quan = mysqli_fetch_assoc($result);

    if (!$quan) {
        echo "<h2>❌ Không tìm thấy quán ăn!</h2>";
        exit;
    }

    $locationLink = "https://www.google.com/maps/search/?api=1&query=" . urlencode($quan['location']);
?>

<div class="container">
  <div class="header">
    <h2><?php echo $quan['name']; ?></h2>
  </div>

  <div class="image-box">
    <img src="<?php echo $quan['image_url']; ?>" alt="Ảnh quán ăn">
  </div>

  <div style="text-align: center; margin: 15px 0;">
    <button id="scroll-hours">🕒 Mấy giờ thì cửa mở nhỉ</button>
    <button id="scroll-review">💬 Xem đánh giá</button>
    <button onclick="window.location.href='review.php?id=<?php echo $quan['id']; ?>'">✍️ Viết đánh giá</button>
  </div>

  <div class="info-box">
    <p>📍 <strong>Địa chỉ:</strong> 
      <a href="<?php echo $locationLink; ?>" target="_blank"><?php echo $quan['location']; ?></a>
    </p>
    <p>📂 <strong>Danh mục:</strong> <?php echo $quan['category_name']; ?></p>
    <p>⭐ <strong>Đánh giá trung bình:</strong> <?php echo $quan['rating']; ?> / 5</p>
    <p>👁 <strong>Lượt xem:</strong> <?php echo $quan['count'] + 1; ?></p>
  </div>

  <div class="hours-box" id="hours-section">
    <h3>🕒 Giờ mở cửa</h3>
    <ul>
      <?php
      $hours = mysqli_query($link, "SELECT * FROM opening_hours WHERE restaurant_id = $id");
      while ($h = mysqli_fetch_assoc($hours)) {
          echo "<li><strong>{$h['day_of_week']}:</strong> {$h['open_time']} - {$h['close_time']}</li>";
      }
      ?>
    </ul>
  </div>

  <div class="reviews-box" id="reviews-section">
    <h3>💬 Đánh giá từ người dùng</h3>
    <?php
    $reviews = mysqli_query($link, "
      SELECT r.id AS review_id, r.content, r.rating, u.username, r.created_at 
      FROM reviews r 
      LEFT JOIN users u ON r.user_id = u.id 
      WHERE r.restaurant_id = $id AND r.is_approved = 1
      ORDER BY r.created_at DESC
    ");

    if (mysqli_num_rows($reviews) > 0) {
        while ($rev = mysqli_fetch_assoc($reviews)) {
            echo "<div class='review-item'>";
            echo "<strong>{$rev['username']}</strong> ({$rev['rating']}★): {$rev['content']}<br>";

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
    } else {
        echo "<p>Chưa có đánh giá nào cho quán này.</p>";
    }
    ?>
  </div>

  <div class="back-link">
    <a href="restaurants.php">← Quay lại danh sách quán nổi bật</a>
  </div>
</div>

<!-- Scroll mượt đến section -->
<script>
$(document).ready(function () {
  $('#scroll-hours').click(function () {
    const target = document.getElementById('hours-section');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });

  $('#scroll-review').click(function () {
    const target = document.getElementById('reviews-section');
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});
</script>

</body>
</html>

<?php include('includes/footer.php'); ?>
