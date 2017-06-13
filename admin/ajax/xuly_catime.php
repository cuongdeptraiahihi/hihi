<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");
	
	if(isset($_POST["lmID"])) {
		$lmID=$_POST["lmID"];
		delete_catime($lmID);
	}
	
	if(isset($_POST["lmID0"]) && isset($_POST["start"]) && isset($_POST["end"])) {
		$lmID=$_POST["lmID0"];
		$start=$_POST["start"];
		$end=$_POST["end"];
		add_catime($lmID,$start,$end);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>