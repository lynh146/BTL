<!-- index.php -->
<?php include("includes/header.php"); ?>
 <?php include("includes/config.php"); ?>
<link rel="stylesheet" href="assets/css/style.css">
<!-- <link rel="stylesheet" href="assets/css/restaurant.css"> -->
<div class="hero">

<?php include("includes/search_box.php"); ?>


</div>
<!-- QUÁN NỔI BẬT -->
<section>
    <h2>Quán Ăn Nổi Bật</h2>
    <?php
    mysqli_set_charset($link, "utf8");

    $sql = "SELECT id, name, location, price_level, image_url, rating
            FROM restaurants
            ORDER BY count DESC
            LIMIT 6"; // lấy 6 quán
    $result = mysqli_query($link, $sql);
    ?>

    <div class="featured-wrapper">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div class="box-noibat">
        <div class="box-header">
          <a href="restaurant_view.php?id=<?php echo $row['id']; ?>" style="text-decoration: none; color: #e74c3c;">
            🔥 <?php echo htmlspecialchars($row['name']); ?>
          </a>
        </div>
        <ul class="box-list">
          <li class="item">
            <a href="restaurant_view.php?id=<?php echo $row['id']; ?>">
              <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="thumb" alt="Ảnh">
            </a>
            <div class="info">
              <a href="restaurant_view.php?id=<?php echo $row['id']; ?>" class="title"><?php echo htmlspecialchars($row['name']); ?></a>
              <p class="meta">📍 <?php echo htmlspecialchars($row['location']); ?> | 💰 <?php echo htmlspecialchars($row['price_level']); ?></p>
              <p class="rating">⭐ <?php echo number_format($row['rating'], 1); ?></p>
            </div>
          </li>
        </ul>
      </div>
    <?php } ?>
    <div style="clear: both;"></div>
    </div>
</section>
    <!-- 
    Dùng các hàm để tự n truy vấn hiển thị lên k được nhập tay từng quán, yêu cầu 3-6 quán hiển thị danh sách
    Tên quán
    Ảnh đại diện
    Vị trí / giá
    Số sao / đánh giá tổng.

    khi nhấp vào tên quán sẽ dẫn đến tới quán đó trong trang featured_restaurants.php )
    ví dụ:
    quán 1 | quán 2 | quán 3
    quán 4 | quán 5 | quán 6
    -->
</section>
<section>
    <h2> Đánh Giá Nổi Bật </h2>
<?php
    $sql_reviews = "SELECT r.id, r.restaurant_id, r.content, r.rating, r.created_at, 
                           res.name AS restaurant_name, u.username
                    FROM reviews r
                    JOIN restaurants res ON r.restaurant_id = res.id
                    JOIN users u ON r.user_id = u.id
                    WHERE r.is_approved = 1
                    ORDER BY r.created_at DESC
                    LIMIT 5";
    $result_reviews = mysqli_query($link, $sql_reviews);
    ?>
    <div class="box-reviews">
        <div class="box-header">
            <a href="restaurants.php" style="text-decoration: none; color: #e74c3c;"></a>
        </div>
        <ul class="box-list">
            <?php while ($row = mysqli_fetch_assoc($result_reviews)) { 
                // Fetch restaurant image
                $restaurant_id = $row['restaurant_id'];
                $sql_image = "SELECT image_url FROM images WHERE restaurant_id = $restaurant_id LIMIT 1";
                $image_result = mysqli_query($link, $sql_image);
                $image = mysqli_fetch_assoc($image_result);
                $image_url = $image ? $image['image_url'] : 'https://via.placeholder.com/150';
            ?>
            <li class="item">
                <a href="restaurant_view.php?id=<?php echo $row['restaurant_id']; ?>#review-<?php echo $row['id']; ?>">
                    <img src="<?php echo $image_url; ?>" class="thumb" alt="Ảnh quán">
                </a>
                <div class="info">
                    <a href="restaurant_view.php?id=<?php echo $row['restaurant_id']; ?>#review-<?php echo $row['id']; ?>"
                        class="title"><?php echo $row['restaurant_name']; ?></a>
                    <p class="meta">👤 <?php echo $row['username']; ?> | 🕒
                        <?php echo date('d/m/Y', strtotime($row['created_at'])); ?></p>
                    <p class="rating">⭐ <?php echo number_format($row['rating'], 1); ?></p>
                    <p class="content"><?php echo htmlspecialchars($row['content']); ?></p>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>

<?php include("includes/footer.php"); ?>
