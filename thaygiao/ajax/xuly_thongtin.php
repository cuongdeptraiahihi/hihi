<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");
	
	if (isset($_POST["hsID"]) && isset($_POST["lmID"])) {
		$hsID=$_POST["hsID"];
		$lmID=$_POST["lmID"];
		hoc_lai($hsID, $lmID);
	}
	
	if (isset($_POST["hsID2"]) && isset($_POST["lmID2"])) {
		$hsID=$_POST["hsID2"];
		$lmID=$_POST["lmID2"];
		nghi_hoc($hsID, $lmID);
		remove_hs_all_ca($hsID,$lmID,get_mon_of_lop($lmID));
	}

    if(isset($_POST["hsID6"]) && isset($_POST["sdt"])) {
        $hsID=$_POST["hsID6"];
        $sdt=$_POST["sdt"];
        if(is_check_phone($hsID,$sdt)) {
            uncheck_phone($hsID, $sdt);
        } else {
            check_phone($hsID, $sdt);
        }
    }
	
	if(isset($_POST["hsID1"]) && isset($_POST["sdt"])) {
		$hsID=$_POST["hsID1"];
		$sdt=$_POST["sdt"];
		check_phone($hsID, $sdt);
	}
	
	if(isset($_POST["hsID3"]) && isset($_POST["sdt"])) {
		$hsID=$_POST["hsID3"];
		$sdt=$_POST["sdt"];
		uncheck_phone($hsID, $sdt);
	}
	
	if(isset($_POST["hsID4"])) {
		$hsID=$_POST["hsID4"];
		uncheck_hop($hsID);
	}
	
	if(isset($_POST["hsID5"])) {
		$hsID=$_POST["hsID5"];
		check_hop($hsID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>