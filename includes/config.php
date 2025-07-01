<?php
    //kết nối đến server
    $link=@mysqli_connect("127.0.0.1", "root", "1234") or die("Kết nối đến server thất bại!");
    //lựa chọn database cần thao tác

    mysqli_select_db($link, "food_review") or die("DB không tồn tại");
?>