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
            <p><strong>Nguyễn Văn A:</strong> "Nước dùng đậm đà, không gian sạch sẽ, phục vụ thân thiện. Sẽ quay lại!"
            </p>
            <p>⭐ 4.5 | 📍 Quận 1 | 💰 Trung bình</p>
        </div>
        <div class="review-card">
            <h3>🍚 Cơm Tấm Ba Ghiền</h3>
            <p><strong>Trần Thị B:</strong> "Sườn nướng rất ngon, giá sinh viên, nhưng hơi đông vào giờ cao điểm."</p>
            <p>⭐ 4.7 | 📍 Quận 3 | 💰 Rẻ</p>
        </div>
        <div class="review-card">
            <h3>🍣 Sushi Tí</h3>
            <p><strong>Nhận xét:</strong> "Từng cuốn tươi rói, thanh đạm, chấm cùng tương đậu phộng béo ngậy là hết
                sảy."</p>
            <p>⭐ 4.8 | 📍 41 Huỳnh Tịnh Của, P.19 | 💰 Đắt</p>
        </div>

        <div class="review-card">
            <h3>🍲 Lẩu Tomyum Nhà Mít</h3>
            <p><strong>Nhận xét:</strong> "Nước dùng trong veo, đậm đà, thơm lừng mùi hồi quế, thịt bò mềm tan trong
                miệng."</p>
            <p>⭐ 4.2 | 📍 175 Lê Đức Thọ | 💰 Trung bình</p>
        </div>

        <div class="review-card">
            <h3>🥤 Trà Sữa Nhà Làm</h3>
            <p><strong>Nhận xét:</strong> "Vị ngọt dịu của trà, béo ngậy của nước cốt dừa và dai dai của trân châu,
                món tráng miệng hoàn hảo."</p>
            <p>⭐ 4.6 | 📍 116/13 Dương Quảng Hàm, Gò Vấp | 💰 Rẻ</p>
        </div>

        <div class="review-card">
            <h3>🥞 Bánh Căn Đà Lạt</h3>
            <p><strong>Nhận xét:</strong> "Vỏ bánh giòn tan, vàng ươm, nhân tôm thịt giá đỗ đầy đặn, cuốn rau sống chấm
                nước mắm thật đã."</p>
            <p>⭐ 4.0 | 📍 448 Lê Văn Thọ | 💰 Trung bình</p>
        </div>

        <div class="review-card">
            <h3>🍦 Kem Saigon Gelato</h3>
            <p><strong>Nhận xét:</strong> "Kem mềm mịn, tan chảy ngay trong miệng, vị ngọt thanh mát rất dễ chịu."</p>
            <p>⭐ 4.9 | 📍 42/58 Hoàng Hoa Thám | 💰 Rẻ</p>
        </div>

        <div class="review-card">
            <h3>🍜 Mì Quảng Cô Lý</h3>
            <p><strong>Nhận xét:</strong> "Nước lèo ngọt thanh vị cua, riêu cua béo bùi, thêm chút mắm tôm đúng điệu."
            </p>
            <p>⭐ 4.3 | 📍 294/1 Bùi Đình Tuý, Bình Thạnh | 💰 Trung bình</p>
        </div>

        <div class="review-card">
            <h3>🍳 Tamago Omurice</h3>
            <p><strong>Nhận xét:</strong> "Món Tamago Omurice có lớp trứng mềm mượt, béo ngậy bao phủ cơm chiên, nước
                sốt cà chua đậm đà rất hợp vị."
            <p>⭐ 4.4 | 📍 333 Điện Biên Phủ | 💰 Đắt</p>
        </div>

        <div class="review-card">
            <h3>🍽️ Bánh ướt chồng đĩa</h3>
            <p><strong>Nhận xét:</strong> "Bánh ướt chồng đĩa mềm mỏng, dai ngon, ăn kèm chả lụa và nem chua, chấm nước
                mắm chua ngọt đậm đà rất cuốn."
            <p>⭐ 4.1 | 📍 710/2 Phan Văn Trị | 💰 Rẻ</p>
        </div>



    </div>
</section>

<?php include("includes/footer.php"); ?>
