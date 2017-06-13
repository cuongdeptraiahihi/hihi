<?php
    ob_start();
    session_start();
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie("my_mon", "", time() - 3600, "/");
            setcookie("is_ct", "", time() - 3600, "/");
            setcookie("my_id", "", time() - 3600, "/");
            setcookie("my_code", "", time() - 3600, "/");
        }
    }
    $_COOKIE = array();
    $_SESSION = array();
    session_destroy();
    session_unset();
    header("location:http://localhost/www/TDUONG/luyenthi/admin/dang-nhap/");
    exit();
    ob_end_flush();
?>