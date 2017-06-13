<?php
    ob_start();
    session_start();
    require_once "../../model/model.php";
    require_once "../access_admin.php";

    if(isset($_POST["maso"]) && isset($_POST["name"]) && isset($_POST["monID"])) {
        $maso = $_POST["maso"];
        $name = $_POST["name"];
        $monID = $_POST["monID"];
        $db = new Chuyen_De();
        if(!$db->checkChuyenDeMaso($maso)) {
            $db->addChuyenDe($maso, $name, $monID);
            echo"ok";
        } else {
            echo"none";
        }
    }

    if(isset($_POST["maso"]) && isset($_POST["name"]) && isset($_POST["dadID"]) && isset($_POST["dadID2"])) {
        $maso = $_POST["maso"];
        $name = $_POST["name"];
        $dadID = $_POST["dadID"];
        $dadID2 = $_POST["dadID2"];
        $db = new Chuyen_De();
        if(!$db->checkChuyenDeMaso($maso)) {
            $db->addChuyenDeCon($maso, $name, $dadID, $dadID2);
            echo"ok";
        } else {
            echo"none";
        }
    }

    if(isset($_POST["maso0"]) && isset($_POST["name0"]) && isset($_POST["dadID0"])) {
        $maso = $_POST["maso0"];
        $name = $_POST["name0"];
        $dadID = $_POST["dadID0"];
        $db = new Chuyen_De();
        $db->editChuyenDe($maso, $name, $dadID);
        echo"ok";
    }

    if(isset($_POST["maso0"]) && isset($_POST["name0"]) && isset($_POST["cdID0"])) {
        $maso = $_POST["maso0"];
        $name = $_POST["name0"];
        $cdID = $_POST["cdID0"];
        $db = new Chuyen_De();
        $db->editChuyenDeCon($maso, $name, $cdID);
        echo"ok";
    }

    if(isset($_POST["dadID1"])) {
        $dadID = $_POST["dadID1"];
        $db = new Chuyen_De();
        if($db->countChuyenDeCon($dadID) == 0) {
            $db->delChuyenDeDad($dadID);
            echo"ok";
        } else {
            echo"none";
        }
    }

    if(isset($_POST["cdID1"])) {
        $cdID = $_POST["cdID1"];
        $db = new Chuyen_De();
        $db->delChuyenDeCon($cdID);
        echo"ok";
    }

    if(isset($_POST["dadID2"]) && isset($_POST["lmID"])) {
        $dadID = $_POST["dadID2"];
        $lmID = $_POST["lmID"];
        $db = new Chuyen_De();
        $result = $db->getChuyenDeConByDad($dadID);
        while($data = $result->fetch_assoc()) {
            $db->unlockChuyenDe($data["ID_CD"],$lmID);
        }
    }

    if(isset($_POST["dadID3"]) && isset($_POST["lmID"])) {
        $dadID = $_POST["dadID3"];
        $lmID = $_POST["lmID"];
        $db = new Chuyen_De();
        $result = $db->getChuyenDeConByDad($dadID);
        while($data = $result->fetch_assoc()) {
            $db->lockChuyenDe($data["ID_CD"],$lmID);
        }
    }

    if(isset($_POST["cdID2"]) && isset($_POST["lmID"])) {
        $cdID = $_POST["cdID2"];
        $lmID = $_POST["lmID"];
        $db = new Chuyen_De();
        $db->unlockChuyenDe($cdID,$lmID);
    }

    if(isset($_POST["cdID3"]) && isset($_POST["lmID"])) {
        $cdID = $_POST["cdID3"];
        $lmID = $_POST["lmID"];
        $db = new Chuyen_De();
        $db->lockChuyenDe($cdID,$lmID);
    }

    ob_end_flush();
?>


