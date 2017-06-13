<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	$opa=$mau=NULL;
	
	if(isset($_GET["link"])) {
		if(isset($_POST["ok-opa"])) {
			if(isset($_POST["range-opa"])) {
				$opa=$_POST["range-opa"];
			}
			if(isset($_POST["select-opa"])) {
				$mau=$_POST["select-opa"];
			}
			
			if($opa && $mau) {
				change_opa($opa,$mau);
			}
		}
		header("location:".$_GET["link"]);
		exit();
	} else {
		header("location:http://localhost/www/TDUONG/admin/home/");
		exit();
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>