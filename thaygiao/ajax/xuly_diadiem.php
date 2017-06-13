<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if(isset($_POST["name"]) && isset($_POST["mota"])) {
		$name=$_POST["name"];
		$mota=$_POST["mota"];
		add_dia_diem($name,$mota);
	}
	
	if(isset($_POST["ddID"])) {
		$ddID=$_POST["ddID"];
		delete_dia_diem($ddID);
	}
	
	if(isset($_POST["ddID1"]) && isset($_POST["name1"]) && isset($_POST["mota1"])) {
		$ddID=$_POST["ddID1"];
		$name=$_POST["name1"];
		$mota=$_POST["mota1"];
		update_dia_diem($ddID,$name,$mota);
	}
	
	if(isset($_POST["ddID0"]) && isset($_POST["caID"]) && isset($_POST["ca"])) {
		$ddID=$_POST["ddID0"];
		$caID=$_POST["caID"];
		$ca=$_POST["ca"];
		update_dia_diem_ca($caID, $ddID, $ca);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>