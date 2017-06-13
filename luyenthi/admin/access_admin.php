<?php
    if (isset($_SESSION["my_mon"]) && isset($_SESSION["my_id"]) && isset($_SESSION["is_ct"])) {
        global $lmID, $is_ct, $ID;
        $lmID = $_SESSION["my_mon"];
        $is_ct = $_SESSION["is_ct"];
        $ID = $_SESSION["my_id"];
        $db = new Admin();
        $result = $db->getCheckAdmin($ID,(new Mon_Hoc())->getMonOfLop($lmID));
        if($result->num_rows == 0) {

        }
    } else {

    }
?>