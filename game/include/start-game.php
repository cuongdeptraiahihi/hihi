<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
if(!login_check() && stripos($_SERVER['REQUEST_URI'],"/dang-nhap/")===false) {
    header("location:http://localhost/www/TDUONG/game/dang-nhap/");
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>GAME</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <style>
        * {margin: 0;padding: 0;}
        body {font-family: Arial, Helvetica, sans-serif;width: 90%;height: 100%;margin: 5% auto;}
        a {text-decoration: none;display: block;}
        button {outline: none;background: #FFF;padding: 0 15px 0 15px;border-radius: 10px;border: 2px solid #000;cursor: pointer;}
        button, button a {color: #000;font-size: 14px;font-weight: 600;text-transform: capitalize;}
        button:hover {background: #000;}
        button:hover, button:hover a {color: #FFF;}
        li, ol, ul {list-style-type: none;}
        ul li {display: inline-block;text-align: center;height: 40px;margin: 0 5px 5px 5px;}
        ul li button {width: 100%;height: 100%;}
        h2 {text-align: center;font-size: 36px;margin-top: 10px;margin-bottom: 10px;text-transform: capitalize;}
        table {width: 90%;margin: 10px auto;}
        table tr td {text-align: center;padding: 5px 0 5px 0;}
        input[type="text"], input[type="password"] {width: 93%;padding: 10px;border: 2px solid #000;outline: none;border-radius: 10px;max-width: 300px;}
        p span {font-size: 14px;font-weight: 600;width: 100%;text-align: center;display: block;}
    </style>
</head>