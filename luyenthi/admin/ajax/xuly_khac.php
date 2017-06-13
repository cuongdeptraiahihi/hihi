<?php
    ob_start();
    session_start();
    require_once "../../model/model.php";
    require_once "../access_admin.php";

    if(isset($_POST["name"]) && isset($_POST["mota"])) {
        $name = $_POST["name"];
        $mota = $_POST["mota"];
        (new Loai_De())->addLoaiDe($name,$mota);
    }

    if(isset($_POST["name2"]) && isset($_POST["mota2"]) && isset($_POST["dID2"])) {
        $name = $_POST["name2"];
        $mota = $_POST["mota2"];
        $dID = $_POST["dID2"];
        (new Loai_De())->editLoaiDe($name,$mota,$dID);
    }

    if(isset($_POST["dID"])) {
        $dID = $_POST["dID"];
        (new Loai_De())->delLoaiDe($dID);
    }

    ob_end_flush();
?>


