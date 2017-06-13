<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if (isset($_POST["tienID"]) && isset($_POST["mota"]) && isset($_POST["tien"])) {
		$tienID=$_POST["tienID"];
		$mota=$_POST["mota"];
		$tien=$_POST["tien"];
		edit_muctien($tienID, $mota, $tien);
		echo json_encode(
			array(
				"mota" => $mota,
				"tien" => $tien
			)	
		);
	}
	
	if (isset($_POST["string"]) && isset($_POST["mota"]) && isset($_POST["tien"])) {
		$string=$_POST["string"];
		$mota=$_POST["mota"];
		$tien=$_POST["tien"];
		if(check_string_muctien($string)) {
			echo "0";
		} else {
			add_muctien($string, $mota, $tien);
			echo get_latest_muctien();
		}
	}
	
	if(isset($_POST["lido"]) && isset($_POST["tien2"])) {
		$ldID=$_POST["lido"];
		$tien=$_POST["tien2"];
		$result=get_lido($ldID);
		$data=mysqli_fetch_assoc($result);
		if(check_string_muctien($data["string"])) {
			echo "0";
		} else {
			add_muctien($data["string"], $data["name"], $tien);
			echo get_latest_muctien();
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>