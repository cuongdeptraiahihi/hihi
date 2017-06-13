<?php
    if (isset($_SESSION["my_mon"]) && isset($_SESSION["my_monbig"]) && isset($_SESSION["my_id"]) && isset($_SESSION["is_ct"])) {
        global $lmID, $is_ct, $hsID, $code, $monID;
        $lmID = $_SESSION["my_mon"];
        $is_ct = $_SESSION["is_ct"];
        $hsID = $_SESSION["my_id"];
        $code = $_SESSION["my_code"];
        $monID = $_SESSION["my_monbig"];
    } else if (isset($_COOKIE["my_mon"]) && isset($_COOKIE["my_monbig"]) && isset($_COOKIE["my_id"]) && isset($_COOKIE["is_ct"])) {
        global $lmID, $is_ct, $hsID, $code, $monID;
        $lmID = $_COOKIE["my_mon"];
        $is_ct = $_COOKIE["is_ct"];
        $hsID = $_COOKIE["my_id"];
        $code = $_COOKIE["my_code"];
        $monID = $_COOKIE["my_monbig"];
        $_SESSION["my_mon"] = $lmID;
        $_SESSION["is_ct"] = $is_ct;
        $_SESSION["my_id"] = $hsID;
        $_SESSION["my_code"] = $code;
        $_SESSION["my_monbig"] = $monID;
    } else {
        $lmID = $is_ct = $hsID = $code = $monID = 0;
    }
?>