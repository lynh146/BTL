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
<!-- QUÁN NỔI BẬT -->
<section>
    <h2>Quán ăn nổi bật</h2>
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
