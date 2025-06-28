<!-- index.php -->
<?php include("includes/header.php"); ?>

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

  <section>
    <h2>Quán ăn nổi bật</h2>
    <div class="restaurant-grid">
      <div class="restaurant-card">
        <img src="https://images.unsplash.com/photo-1600891964901-e6bcdac94b8f" alt="Bún bò Huế">
        <div class="info">
          <h3>Bún Bò Huế 1980</h3>
          <p>📍 Quận 1 | 💰 Trung bình | ⭐ 4.5</p>
        </div>
      </div>
      <div class="restaurant-card">
        <img src="https://images.unsplash.com/photo-1621986326244-45e1f993b88b" alt="Cơm tấm">
        <div class="info">
          <h3>Cơm Tấm Ba Ghiền</h3>
          <p>📍 Quận 3 | 💰 Rẻ | ⭐ 4.7</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <h2>Đánh giá nổi bật</h2>
    <div class="review-grid">
      <div class="review-card">
        <h3>🍜 Bún Bò Huế 1980</h3>
        <p><strong>Nguyễn Văn A:</strong> "Nước dùng đậm đà, không gian sạch sẽ, phục vụ thân thiện. Sẽ quay lại!"</p>
        <p>⭐ 4.5 | 📍 Quận 1 | 💰 Trung bình</p>
      </div>
      <div class="review-card">
        <h3>🍚 Cơm Tấm Ba Ghiền</h3>
        <p><strong>Trần Thị B:</strong> "Sườn nướng rất ngon, giá sinh viên, nhưng hơi đông vào giờ cao điểm."</p>
        <p>⭐ 4.7 | 📍 Quận 3 | 💰 Rẻ</p>
      </div>
    </div>
  </section>

<?php include("includes/footer.php"); ?>
