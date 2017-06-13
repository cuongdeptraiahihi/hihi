<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	
	
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>