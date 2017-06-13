<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if (isset($_POST["action"])) {
		$action=$_POST["action"];
		$kq="";
		switch($action) {
			case "mon-hoc":

				$kq="ok";
				break;
			case "ca-hoc":
				$kq=fix_ca();
				break;
			case "ca-tam":
				fix_ca_tam();
				$kq="ok";
				break;
		}
		echo $kq;
	}
	
	if(isset($_POST["khoa0"])) {
		$oID=$_POST["khoa0"];
		turn_off_khoa($oID);
	}
	
	if(isset($_POST["khoa1"])) {
		$oID=$_POST["khoa1"];
		turn_on_khoa($oID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>