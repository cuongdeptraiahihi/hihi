<?php
	//mở kết nối CSDL
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
    $db_name = "tduong";

    $db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    $connect_error = mysqli_connect_error();
    if ($connect_error != null) {
        echo"<p>Lỗi kết nối đến cơ sở dữ liệu</p>";
    }
?>
