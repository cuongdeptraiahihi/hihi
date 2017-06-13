<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if(isset($_POST["tlID0"])) {
		$tlID=$_POST["tlID0"];
		delete_tailieu($tlID);
	}
	
	if(isset($_POST["cID"])) {
		$cID=base64_decode($_POST["cID"]);
		delete_comment($cID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>