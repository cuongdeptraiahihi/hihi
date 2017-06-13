<?php
    ob_start();
    session_start();
    require_once "../../model/model.php";
    require_once "../access_admin.php";

    if(isset($_POST["hsID"]) && isset($_POST["lmID"])) {
        $hsID = $_POST["hsID"];
        $lmID = $_POST["lmID"];
        $db = new Hoc_Sinh();
        $db->unlockHocSinh($hsID,$lmID);
    }

    ob_end_flush();
?>

