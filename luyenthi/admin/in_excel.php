<?php
session_start();
require_once("../model/model.php");
include_once 'Sample_Header.php';

if(isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
    $nhom = $_GET["nhom"];
} else {
    $nhom = 0;
}

echo "Chưa được hỗ trợ!";

if (!CLI) {
    include_once 'Sample_Footer.php';
}
