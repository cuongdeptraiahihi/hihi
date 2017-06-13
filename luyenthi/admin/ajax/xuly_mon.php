<?php
    ob_start();
    session_start();
    require_once "../../model/model.php";
    require_once "../access_admin.php";

    if(isset($_POST["lmID"]) && is_numeric($_POST["lmID"]) && isset($_SESSION["my_id"])) {
        $lmID = $_POST["lmID"];
        $ID = $_SESSION["my_id"];
        $db = new Admin();
        $result = $db->getCheckAdmin($ID,(new Mon_Hoc())->getMonOfLop($lmID));
        if($result->num_rows != 0) {
            $_SESSION["my_mon"] = $lmID;
        }
    }

    if(isset($_POST["monID"]) && isset($_POST["is_use"])) {
        $monID = $_POST["monID"];
        $is_use = $_POST["is_use"];
        if($is_use == 1) {
            $is_use = true;
        } else {
            $is_use = false;
        }
        $db = new Mon_Hoc();
        $db->updateUseCt($monID,$is_use);
    }

    ob_end_flush();
?>

