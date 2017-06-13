<?php
    session_start();
    session_destroy();
    header('location: http://localhost/www/TDUONG/nhahang/login-food.php');
?>