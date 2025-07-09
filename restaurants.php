<?php include('includes/header.php'); ?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Top Quán Ăn Nổi Bật</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/restaurants.css">
  <script src="assets/js/jquery-3.7.1.min.js"></script>
</head>
<body>
   <?php include("includes/config.php"); ?>
<?php
$sql = "SELECT id, name, location, image_url,price_level,rating, count FROM restaurants";
mysqli_set_charset($link, "utf8");
$categories_res = mysqli_query($link, "SELECT id, name FROM categories");

// Xử lý filter
$where = [];
$order = "ORDER BY count DESC";

if (!empty($_GET['price_level'])) {
  $level = mysqli_real_escape_string($link, $_GET['price_level']);
  $where[] = "price_level = '$level'";
}

if (!empty($_GET['category'])) {
  $cat = (int)$_GET['category'];
$where[] = "category_id = $cat";
}


if (!empty($where)) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " $order LIMIT 10";
$result = mysqli_query($link, $sql);
?>

<h2>🔥 Top Quán Ăn Nổi Bật</h2>

<!-- Bộ lọc -->
<form method="get" class="filter-form" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">
  <select name="price_level">
  <option value="">💰 Giá</option>
  <option value="Rẻ" <?= ($_GET['price_level'] ?? '') == 'Rẻ' ? 'selected' : '' ?>>Rẻ</option>
  <option value="Trung bình" <?= ($_GET['price_level'] ?? '') == 'Trung bình' ? 'selected' : '' ?>>Trung bình</option>
  <option value="Đắt" <?= ($_GET['price_level'] ?? '') == 'Đắt' ? 'selected' : '' ?>>Đắt</option>
</select>

  <select name="category">
  <option value="">🍜 Loại món</option>
  <?php 
  while ($cat = mysqli_fetch_assoc($categories_res)) {
    $selected = ($_GET['category'] ?? '') == $cat['id'] ? "selected" : "";
    echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
  }
  ?>
</select>
  <button type="submit">🔍 Lọc</button>
</form>

<!-- Hiển thị danh sách -->
<div class="quan-list">
<?php 
$i = 0;
while ($row = mysqli_fetch_assoc($result)) { 
    $topClass = '';
    if ($i == 0) $topClass = 'top1';
    elseif ($i == 1) $topClass = 'top2';
    elseif ($i == 2) $topClass = 'top3';
?>
  <div class="quan-item <?php echo $topClass; ?>">
    <img src="<?php echo $row['image_url']; ?>" alt="Ảnh quán ăn">
    <h4>
     <?php $icons = ["🥇", "🥈", "🥉"]; if ($i < 3) echo $icons[$i] . " "; ?>
     <?php echo $row['name']; ?>
    </h4>
    <p>📍 <?php echo $row['location']; ?></p>
    <p>👁 <?php echo $row['count']; ?> lượt truy cập</p>
    <p>⭐ <?= $row['rating'] ? $row['rating'] . '/5' : 'Chưa có đánh giá' ?></p>

    <a href="#" class="xemchitiet" data-id="<?php echo $row['id']; ?>">Xem chi tiết</a>
</a>

  </div>
<?php $i++; } ?>
</div>

<!-- Hiệu ứng giật mình -->
<script>
$(document).ready(function(){
  $(".xemchitiet").click(function(e){
    e.preventDefault();
    const id = $(this).data("id");
    const box = $(this).closest(".quan-item");
    $(".quan-item").removeClass("jumpscare");
    box.addClass("jumpscare");

    setTimeout(function() {
      window.location.href = "restaurant_view.php?id=" + id;
    }, 300);

  });
});
</script>
</body>
</html>
<?php include('includes/footer.php'); ?>