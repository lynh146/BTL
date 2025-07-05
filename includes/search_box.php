<?php include_once("config.php"); ?>

<?php
$categories = mysqli_query($link, "SELECT * FROM categories");
?>

<form method="get" action="search_results.php">
<div class="search-box">
  <!-- Khu vực -->
  <select name="region" id="region">
    <option value="">Khu vực</option>
    <option value="TP.HCM">TP.HCM</option>
    <option value="Hà Nội">Hà Nội</option>
  </select>

  <!-- Quận/Huyện -->
  <select name="district" id="district">
    <option value="">Quận/Huyện</option>
  </select>

  <!-- Danh mục -->
  <select name="category">
    <option value="">Danh mục món</option>
    <?php while($c = mysqli_fetch_assoc($categories)): ?>
      <option value="<?=$c['id']?>"><?=$c['name']?></option>
    <?php endwhile; ?>
  </select>

  <!-- Từ khoá -->
  <input type="text" name="keyword" placeholder="Từ khoá…">

  <button type="submit">Tìm quán ngay</button>
</div>
</form>

<script>
document.getElementById('region').addEventListener('change', function () {
  const district = document.getElementById('district');
  district.innerHTML = '<option value="">Quận/Huyện</option>';
  let ds = [];
  if (this.value === 'TP.HCM') {
    ds = ['Quận 1', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Quận 8', 'Quận 10', 'Quận 11', 'Quận 12', 
    'Quận Phú Nhuận','Quận Bình Thạnh', 'Quận Gò Vấp', 'Quận Tân Bình', 'Quận Bình Tân',  'Quận Tân Phú',
    'Bình Chánh', 'Hóc Môn', 'Củ Chi', 'Cần Giờ', 'Nhà Bè'];
  } else if (this.value === 'Hà Nội') {
    ds = ['Hoàn Kiếm', 'Ba Đình', 'Cầu Giấy', 'Đống Đa'];
  }
  ds.forEach(function (q) {
    const opt = document.createElement('option');
    opt.value = q; opt.textContent = q;
    district.appendChild(opt);
  });
});
</script>
