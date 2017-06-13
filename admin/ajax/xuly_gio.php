<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");

    // Đã check
	if (isset($_POST["gioID0"]) && isset($_POST["gio"]) && isset($_POST["buoi"]) && isset($_POST["thutu"])) {
		$gioID=$_POST["gioID0"];
		$gio=$_POST["gio"];
		$buoi=$_POST["buoi"];
		$thutu=$_POST["thutu"];
		edit_cagio($gioID, $gio, $buoi, $thutu);
	}

	// Đã check
	if (isset($_POST["gio"]) && isset($_POST["mon"]) && isset($_POST["lm"]) && isset($_POST["buoi"]) && isset($_POST["thutu"])) {
		$gio=$_POST["gio"];
		$mon=$_POST["mon"];
		$lm=$_POST["lm"];
		$buoi=$_POST["buoi"];
		$thutu=$_POST["thutu"];
		add_cagio($gio, $mon, $lm, $buoi, $thutu);
		$_SESSION["new_gio"]=mysqli_insert_id($db);
	}

	// Đã check
	if(isset($_POST["gioID1"])) {
		$gioID=$_POST["gioID1"];
		delete_cagio($gioID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>