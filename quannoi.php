<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Top Quán Ăn Nổi Bật</title>
  <link rel="stylesheet" href="style.css">
  <script src="js/jquery-3.7.1.min.js"></script>
</head>
<body>

<?php
$conn = mysqli_connect("localhost", "root", "", "food_review", 3308);
mysqli_set_charset($conn, "utf8");

$sql = "SELECT id, name, location, image_url, count FROM restaurants  ORDER BY count DESC LIMIT 10";
$result = mysqli_query($conn, $sql);
?>

<h2>🔥 Top 10 Quán Ăn Được Xem Nhiều Nhất</h2>

<div class="quan-list">
<?php 
$i = 0;
while ($row = mysqli_fetch_assoc($result)) { 
    $topClass = '';
    if ($i == 0) $topClass = 'top1';
    elseif ($i == 1) $topClass = 'top2';
    elseif ($i == 2) $topClass = 'top3';

    $encodedLocation = urlencode($row['location']);
?>
  <div class="quan-item <?php echo $topClass; ?>">
    <img src="<?php echo $row['image_url']; ?>" alt="Ảnh quán ăn">
    <h4>
     <?php $icons = ["🥇", "🥈", "🥉"];if ($i < 3) echo $icons[$i] . " ";?>
      <?php echo $row['name']; ?>
    </h4>
    <p>
      📍  <?php echo $row['location']; ?>

    </p>
    <p>👁 <?php echo $row['count']; ?> lượt truy cập</p>
    <a href="#" class="xemchitiet" data-id="<?php echo $row['id']; ?>">Xem chi tiết</a>
  </div>
<?php $i++; } ?>
</div>

<script>
$(document).ready(function(){
  $(".quan-item").hover(function(){
    $(this).css("background-color", "#f0f8ff");
  }, function(){
    $(this).css("background-color", "#fffefc");
  });
    $(".xemchitiet").click(function(e){
    e.preventDefault();
    const id = $(this).data("id");
    const box = $(this).closest(".quan-item");

    $(".quan-item").removeClass("jumpscare");
    box.addClass("jumpscare");

    setTimeout(function() {
      window.location.href = "chitiet.php?id=" + id;
    }, 200);
  });

});
</script>

</body>
</html>