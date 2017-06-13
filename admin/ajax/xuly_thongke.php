<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if (isset($_POST["hsID"]) && isset($_POST["de"]) && isset($_POST["monID"])) {
		$hsID=$_POST["hsID"];
		$de=$_POST["de"];
		$monID=$_POST["monID"];
		update_de_hs($hsID, $de, $monID);
	}
	
	if(isset($_POST["buoi"]) && isset($_POST["monID"])) {
		$buoi=$_POST["buoi"];
        $monID=$_POST["monID"];
		insert_new_buoikt($buoi,$monID);
	}
	
	if(isset($_POST["bieudo"])) {
		if(!isset($_SESSION["bieudo"]) || $_SESSION["bieudo"]==1) {
			$_SESSION["bieudo"]=0;
		} else {
			$_SESSION["bieudo"]=1;
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>