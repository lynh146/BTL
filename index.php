<!-- index.php -->
<?php include("includes/header.php"); ?>
 <?php include("includes/config.php"); ?>
<link rel="stylesheet" href="assets/css/style.css">
<!-- <link rel="stylesheet" href="assets/css/restaurant.css"> -->
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
<!-- ĐÁNH GIÁ NỔI BẬT -->
<section>
    <h2>Đánh giá nổi bật</h2>
  <!-- 
    Dùng các hàm để tự n truy vấn hiển thị lên k được nhập tay từNG đánh giá
    khoảng 3-5 cái đánh giá nổi bật
    hình ảnh quán, đánh giá sao, thời gian đánh giá ...
    Mỗi đánh giá sẽ có, tên quán, tên người đánh giá, nội dung đánh giá
     khi nhấp vào sẽ dẫn tới trang featured_restaurants.php hiện phần đánh giá của quán đó
  -->
</section>

<?php include("includes/footer.php"); ?>
