<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	
	if (isset($_POST["videoID"])) {
		$videoID=$_POST["videoID"];
		kill_video($videoID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>