<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if(isset($_POST["caID_all"]) && isset($_POST["lmID"]) && isset($_POST["action"])) {
		$caID=$_POST["caID_all"];
		$lmID=$_POST["lmID"];
		$action=$_POST["action"];
		if($action=="on") {
			$result=get_all_hocsinh_id($lmID);
			while($data=mysqli_fetch_assoc($result)) {
				turn_on_ca($data["ID_HS"], $caID);
			}
		} else {
			$cum=get_ca_cum($caID);
			$result=get_all_hocsinh_id($lmID);
			while($data=mysqli_fetch_assoc($result)) {
				turn_off_ca($data["ID_HS"], $caID);
				remove_hs_ca($caID,$data["ID_HS"],$cum);
			}
		}
	}
	
	if (isset($_POST["caID"]) && isset($_POST["hsID"]) && isset($_POST["action"])) {
		$caID=$_POST["caID"];
		$hsID=$_POST["hsID"];
		$action=$_POST["action"];
		if($caID != 0 && $hsID != 0) {
			if($action=="on") {
				turn_on_ca($hsID, $caID);
			} else {
				$cum=get_ca_cum($caID);
				turn_off_ca($hsID, $caID);
				remove_hs_ca($caID,$hsID,$cum);
			}
			echo"true";
		} else {
			echo"false";
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>