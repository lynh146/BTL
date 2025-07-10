<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit;
}

// Duyệt & Xóa review
if (isset($_GET['approve_review'])) {
    $id = (int)$_GET['approve_review'];
    $stmt = $link->prepare("UPDATE reviews SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Cập nhật rating trung bình
        $stmt_rest = $link->prepare("SELECT restaurant_id FROM reviews WHERE id = ?");
        $stmt_rest->bind_param("i", $id);
        $stmt_rest->execute();
        $stmt_rest->bind_result($restaurant_id);
        $stmt_rest->fetch();
        $stmt_rest->close();
        $stmt_update = $link->prepare("
            UPDATE restaurants r
            LEFT JOIN (
                SELECT restaurant_id, ROUND(AVG(rating), 1) AS avg_rating
                FROM reviews
                WHERE is_approved = 1
                GROUP BY restaurant_id
            ) AS avg_r
            ON r.id = avg_r.restaurant_id
            SET r.rating = IFNULL(avg_r.avg_rating, 0)
            WHERE r.id = ?
        ");
        $stmt_update->bind_param("i", $restaurant_id);
        $stmt_update->execute();
        $stmt_update->close();
        echo "<script>alert('Duyệt đánh giá thành công!'); window.location.href='index_admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi duyệt đánh giá!');</script>";
    }
    $stmt->close();
}
if (isset($_GET['delete_review'])) {
    $id = (int)$_GET['delete_review'];
    $stmt = $link->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Xóa đánh giá thành công!'); window.location.href='index_admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa đánh giá!');</script>";
    }
    $stmt->close();
}

// Duyệt & Xóa quán chờ duyệt
if (isset($_GET['approve_pending'])) {
    $id = (int)$_GET['approve_pending'];

    $sql = "
        INSERT INTO restaurants 
        (name, location, price_level, rating, category_id, image_url, count)
        SELECT name, location, price_level, rating, category_id, image_url, 0
        FROM restaurants_pending WHERE id = $id
    ";

    if (mysqli_query($link, $sql)) {
        // Xóa bản ghi bên restaurants_pending
        mysqli_query($link, "DELETE FROM restaurants_pending WHERE id = $id");
    } else {
        echo "<script>alert('❌ Lỗi khi duyệt: " . mysqli_error($link) . "');</script>";
    }
}


// Dữ liệu
$restaurants = mysqli_query($link, "SELECT * FROM restaurants");
$users = mysqli_query($link, "SELECT * FROM users");
$reviews = mysqli_query($link, "SELECT r.id, r.content, r.rating, u.username, res.name AS restaurant_name FROM reviews r LEFT JOIN users u ON r.user_id = u.id LEFT JOIN restaurants res ON r.restaurant_id = res.id WHERE r.is_approved = 0");
$pending = mysqli_query($link, "SELECT p.*, c.name AS category_name FROM restaurants_pending p LEFT JOIN categories c ON p.category_id = c.id");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang quản trị</title>
  <link rel="stylesheet" href="assets/css/index_admin.css">
  <script src="assets/js/jquery-3.7.1.min.js"></script>

</head>
<body>
  <div class="sidebar">
 <div class="topbar">
  <div>👋 Xin chào, <strong><?= $_SESSION['admin_name'] ?? 'Admin' ?></strong></div>
  <div><a href="logout.php">Đăng xuất</a></div>
  </div>
    <h3>📋 Quản trị viên</h3>
    <a href="#" class="menu" data-target="restaurants">📌 Quán ăn</a>
    <a href="#" class="menu" data-target="users">👤 Người dùng</a>
    <a href="#" class="menu" data-target="reviews">💬 Đánh giá chờ duyệt</a>
    <a href="#" class="menu" data-target="pending">🕓 Quán ăn chờ duyệt</a>
  </div>

  <div class="content">
    <div id="restaurants" class="section hidden">
      <h2>📌 Danh sách quán ăn</h2>
      <table>
        <tr><th>ID</th><th>Tên</th><th>Địa chỉ</th><th>Giá</th><th>Sao</th><th>Lượt xem</th></tr>
        <?php while($r = mysqli_fetch_assoc($restaurants)) { ?>
        <tr>
          <td><?= $r['id'] ?></td>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['location']) ?></td>
          <td><?= $r['price_level'] ?></td>
          <td><?= $r['rating'] ?> ⭐</td>
          <td><?= $r['count'] ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>

    <div id="users" class="section hidden">
      <h2>👤 Danh sách người dùng</h2>
      <table>
        <tr><th>ID</th><th>Tên đăng nhập</th><th>Email</th></tr>
        <?php while($u = mysqli_fetch_assoc($users)) { ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>

    <div id="reviews" class="section hidden">
      <h2>💬 Đánh giá chưa duyệt</h2>
      <table>
        <tr><th>ID</th><th>Người dùng</th><th>Quán</th><th>Nội dung</th><th>Sao</th><th>Hành động</th></tr>
        <?php while($c = mysqli_fetch_assoc($reviews)) { ?>
        <tr>
          <td><?= $c['id'] ?></td>
          <td><?= htmlspecialchars($c['username']) ?></td>
          <td><?= htmlspecialchars($c['restaurant_name']) ?></td>
          <td><?= htmlspecialchars($c['content']) ?></td>
          <td><?= $c['rating'] ?> ⭐</td>
          <td>
            <a href="?approve_review=<?= $c['id'] ?>" class="approve">✅ Duyệt</a>
            <a href="?delete_review=<?= $c['id'] ?>" class="delete" onclick="return confirm('Xóa?')">🗑 Xóa</a>
          </td>
        </tr>
        <?php } ?>
      </table>
    </div>

    <div id="pending" class="section hidden">
      <h2>🕓 Quán ăn chờ duyệt</h2>
      <table>
        <tr><th>ID</th><th>Tên</th><th>Địa chỉ</th><th>Giá</th><th>Loại món</th><th>Đánh giá</th><th>Ảnh</th><th>Hành động</th></tr>
        <?php while($p = mysqli_fetch_assoc($pending)) { ?>
        <tr>
          <td><?= $p['id'] ?></td>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= htmlspecialchars($p['location']) ?></td>
          <td><?= $p['price_level'] ?></td>
          <td><?= $p['category_name'] ?></td>
          <td><?= $p['rating'] ?> ⭐</td>
          <td><img src="<?= $p['image_url'] ?>" alt="ảnh quán"></td>
          <td>
            <a href="?approve_pending=<?= $p['id'] ?>" class="approve">✅ Duyệt</a>
            <a href="?delete_pending=<?= $p['id'] ?>" class="delete" onclick="return confirm('Xóa quán này?')">🗑 Xóa</a>
          </td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('.menu').click(function(e){
        e.preventDefault();
        let target = $(this).data('target');
        $('.section').addClass('hidden');
        $('#' + target).removeClass('hidden');
      });

      // Mở mặc định mục đầu
      $('.menu').first().click();
    });
  </script>
</body>
</html>
