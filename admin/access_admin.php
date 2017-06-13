<?php

	if(is_ddos($_SERVER['REMOTE_ADDR'],date("Y-m-d"))) {
		header("location:https://localhost/www/TDUONG/ddos/");
		exit();
	}

	if (!isset($_SESSION["ID"]) || !isset($_SESSION["username"]) || !check_admin($_SESSION["ID"],$_SESSION["username"])) {
		header("location:http://localhost/www/TDUONG/admin/");
		exit();
	}

	$url=$_SERVER['REQUEST_URI'];
    if(stripos($url,"/diem-danh")===false &&
        stripos($url,"/hoc-sinh-thong-ke/")===false &&
        stripos($url,"/pre-hoc-sinh-thong-ke/")===false &&
        stripos($url,"/nhap-diem/")===false &&
        stripos($url,"/moi-chuyen-de/")===false &&
        stripos($url,"/xet-nhay-de/")===false &&
        stripos($url,"/hoc-sinh-tien-hoc/")===false &&
        stripos($url,"/hoc-sinh-ca-all/")===false &&
        stripos($url,"/hoc-sinh-khoa-ca/")===false &&
        stripos($url,"/thach-dau/")===false &&
        stripos($url,"/ngoi-sao/")===false &&
        stripos($url,"/thong-bao/")===false &&
        stripos($url,"/chuyen-de/")===false &&
        stripos($url,"/hoc-sinh-chi-tiet/")===false) {

    } else {
        if($_SESSION["lop"]==3) {
            $_SESSION["lop"]=get_lop_main();
        }
    }

	require_once("include/mobile.php");
?>