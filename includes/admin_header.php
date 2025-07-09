<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin - Ăn Gì Hôm Nay?</title>
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<header class="admin-header">
    <h1>Admin Dashboard - Ăn Gì Hôm Nay?</h1>
</header>
<div class="admin-container">
<aside class="admin-sidebar">
    <nav>
        <a href="admin_dashboard.php">🏠 Dashboard</a>
        <a href="users_manage.php">👥 Người dùng</a>
        <a href="restaurants_manage.php">🍽️ Quán ăn</a>
        <a href="reviews_manage.php">📝 Đánh giá</a>
        <a href="categories_manage.php">📂 Danh mục</a>
        <a href="logout.php">🚪 Đăng xuất</a>
    </nav>
</aside>
<main class="admin-main">
