<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");

    // Đã check
//	if(isset($_POST["hsID"]) && isset($_POST["de"]) && isset($_POST["lmID"])) {
//		$hsID=$_POST["hsID"];
//		$de=$_POST["de"];
//        $lmID=$_POST["lmID"];
//		if($de=="B") {
//			$old_de="G";
//		} else if($de==) {
//			$old_de="B";
//		}
//		back_new_nhayde($hsID, $old_de, $lmID);
//	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>