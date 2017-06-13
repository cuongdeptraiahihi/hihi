<?php
    ob_start();
    session_start();
    require("../model/open_db.php");
    require("../model/model.php");
    $idnh= $_SESSION['id-nh'];


    if(isset($_POST["id"])) {
        $id = $_POST["id"];
        if(valid_id($id)) {
            deleteSanpham($id,$idnh);
            echo"ok";
        }
    }

    if(isset($_POST["id1"])) {
        $id = $_POST["id1"];
        if(valid_id($id)) {
            deleteDonhang($id,$idnh);
            echo"ok";
        }
    }

    if(isset($_POST["id2"])) {
        $id = $_POST["id2"];
        if(valid_id($id)) {
            addLichsuSanpham($id,$idnh) ;
            echo"ok";
        }
    }

    if(isset($_POST["id3"])) {
        $id = $_POST["id3"];
        if(valid_id($id)) {
            deleteLichsu($id,$idnh);
            echo"ok";
        }
    }

    if(isset($_POST["name"]) && isset($_POST["gia"]) && isset($_POST["giam"])) {
        $name = $_POST["name"];
        $gia = $_POST["gia"];
        $giam = $_POST["giam"];
        if(checksanpham($name,$gia,$giam,$idnh)) {
        if(valid_id($gia)) {
            addnewsanpham($name,$gia,$giam,$idnh);
            echo"ok";
            }
        }
    }

    require("../model/close_db.php");
    ob_end_flush();
?>