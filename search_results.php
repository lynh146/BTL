<?php include("includes/header.php"); ?>
<?php include("includes/config.php"); ?>

<?php
$region   = $_GET['region'] ?? '';
$district = $_GET['district'] ?? '';
$category = $_GET['category'] ?? '';
$keyword  = $_GET['keyword'] ?? '';

$conds = [];

if ($keyword) {
    $kw = mysqli_real_escape_string($link, $keyword);
    $conds[] = "(name LIKE '%$kw%' OR location LIKE '%$kw%')";
}

if ($region) {
    $rg = mysqli_real_escape_string($link, $region);
    $conds[] = "location LIKE '%$rg%'";
}

if ($district) {
    $ds = mysqli_real_escape_string($link, $district);
    $conds[] = "location LIKE '%$ds%'";
}

if ($category) {
    $cat = intval($category);
    $conds[] = "category_id = $cat";
}

// ghép các điều kiện bằng OR
if (!empty($conds)) {
    $sql = "SELECT * FROM restaurants WHERE " . implode(" OR ", $conds);
} else {
    $sql = "SELECT * FROM restaurants";
}

$result = mysqli_query($link, $sql);

?>

<section>
  <h2>Kết quả tìm kiếm</h2>

  <div class="restaurant-grid">
  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
      <div class="restaurant-card">
        <img src="<?=htmlspecialchars($row['image_url'])?>" alt="<?=htmlspecialchars($row['name'])?>">
        <div class="info">
          <h3><a href="restaurant_view.php?id=<?=$row['id']?>"><?=htmlspecialchars($row['name'])?></a></h3>
          <p><?=htmlspecialchars($row['location'])?> | 💰 <?=htmlspecialchars($row['price_level'])?> | ⭐ <?=number_format($row['rating'],1)?></p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>Không tìm thấy quán nào phù hợp.</p>
  <?php endif; ?>
  </div>
</section>

<?php include("includes/footer.php"); ?>
