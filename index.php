<?php include("includes/header.php"); ?>
<?php include("includes/config.php"); ?>
<link rel="stylesheet" href="assets/css/style.css">
<div class="hero">
    <div class="search-box">
        <input type="text" placeholder="Bạn muốn ăn gì?">
        <select>
            <option>Khu vực</option>
            <option>Quận 1</option>
            <option>Quận 3</option>
        </select>
        <select>
            <option>Mức giá</option>
            <option>Rẻ</option>
            <option>Trung bình</option>
            <option>Đắt</option>
        </select>
        <select>
            <option>Loại món</option>
            <option>Phở</option>
            <option>Bún</option>
            <option>Ăn vặt</option>
        </select>
        <button>Tìm quán ngay</button>
    </div>
</div>
<!-- QUÁN NỔI BẬT -->
<section>
    <h2>Quán ăn nổi bật</h2>
    <?php
    mysqli_set_charset($link, "utf8");

    $sql = "SELECT id, name, location, price_level, image_url, rating
            FROM restaurants
            ORDER BY count DESC
            LIMIT 5";
    $result = mysqli_query($link, $sql);
    ?>
    <div class="box-noibat">
        <div class="box-header">
            <a href="restaurants.php" style="text-decoration: none; color: #e74c3c;">
                🔥 Quán ăn nổi bật
            </a>
        </div>
        <ul class="box-list">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <li class="item">
                <a href="restaurant_view.php?id=<?php echo $row['id']; ?>">
                    <img src="<?php echo $row['image_url']; ?>" class="thumb" alt="Ảnh">
                </a>
                <div class="info">
                    <a href="restaurant_view.php?id=<?php echo $row['id']; ?>"
                        class="title"><?php echo $row['name']; ?></a>
                    <p class="meta">📍 <?php echo $row['location']; ?> | 💰 <?php echo $row['price_level']; ?></p>
                    <p class="rating">⭐ <?php echo number_format($row['rating'], 1); ?></p>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>
<!-- ĐÁNH GIÁ NỔI BẬT -->
<section>
    <h2>Đánh giá nổi bật</h2>
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
            <a href="restaurants.php" style="text-decoration: none; color: #e74c3c;">
                🌟 Đánh giá nổi bật
            </a>
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
