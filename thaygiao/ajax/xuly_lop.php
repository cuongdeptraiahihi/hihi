<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");
	
	if(isset($_POST["lopID"]) && isset($_POST["name"]) && isset($_POST["time"])) {
		$lopID=$_POST["lopID"];
		$name=$_POST["name"];
		$time=$_POST["time"];
		edit_lop($lopID, $name);
		edit_thi_daihoc($lopID,$time);
	}
	
	if(isset($_POST["lopID0"])) {
		$lopID=$_POST["lopID0"];
		delete_lop($lopID);
	}
	
	if(isset($_POST["lop"])) {
		$lop=$_POST["lop"];
		add_lop($lop);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>