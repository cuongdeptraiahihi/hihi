<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	$cahoc_string=$_SESSION["cahoc_string"];
	$ca_codinh_string=$_SESSION["ca_codinh_string"];
	
	if (isset($_POST["name"]) && isset($_POST["mau"])) {
		$name=$_POST["name"];
		$mau=$_POST["mau"];
		add_lido($name, $mau);
	}
	
	if (isset($_POST["ldID"]) && isset($_POST["name2"]) && isset($_POST["mau2"])) {
		$ldID=$_POST["ldID"];
		$name=$_POST["name2"];
		$mau=$_POST["mau2"];
		edit_lido($ldID, $name, $mau);
		echo unicode_convert($name);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>