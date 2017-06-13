<?php
	ob_start();
    session_start();
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie("my_monbig","",time() - 86400,"/");
            setcookie("my_mon", "", time() - 86400, "/");
            setcookie("is_ct", "", time() - 86400, "/");
            setcookie("my_id", "", time() - 86400, "/");
            setcookie("my_code", "", time() - 86400, "/");
            setcookie("is_app", "",time() - 86400,"/");
        }
    }
    $_COOKIE = array();
    $_SESSION = array();
    session_destroy();
    session_unset();
	header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/");
	exit();
	ob_end_flush();
?>