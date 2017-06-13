<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	
	if (isset($_POST["backID"])) {
		$backID=$_POST["backID"];
		delete_background($backID);
	}
	
	if (isset($_POST["backID0"])) {
		$backID=$_POST["backID0"];
		chose_background($backID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>