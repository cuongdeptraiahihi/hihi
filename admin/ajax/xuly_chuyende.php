<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	
	if (isset($_POST["cdID"]) && isset($_POST["title"]) && isset($_POST["maso"])) {
		$cdID=$_POST["cdID"];
		$title=$_POST["title"];
		$maso=$_POST["maso"];
		edit_chuyende($cdID, $title, $maso);
		echo $title;
	}
	
	if (isset($_POST["cdID0"])) {
		$cdID=$_POST["cdID0"];
		delete_chuyende($cdID);
		$_SESSION["undo_chuyende"]=$cdID;
	}
	
	if (isset($_POST["cdID1"])) {
		$cdID=$_POST["cdID1"];
		$result=undo_chuyende($cdID);
		$_SESSION["undo_chuyende"]=0;
		$data=mysqli_fetch_assoc($result);
		echo json_encode(
			array(
				"maso" => $data["maso"],
				"title" => $data["title"],
				"num" => count_chuyende_con($data["ID_CD"])
			)
		);
	}
	
	if(isset($_POST["dad0"]) && isset($_POST["lmIDnew"])) {
		$lmID=$_POST["lmIDnew"];
		$dad=$_POST["dad0"];
		echo add_chuyende("","",$dad,$lmID);
	}
	
	if (isset($_POST["maso0"]) && isset($_POST["title0"]) && isset($_POST["dad"]) && isset($_POST["lmID"]) && isset($_POST["monID"])) {
		$title=$_POST["title0"];
		$maso=$_POST["maso0"];
		$dad=$_POST["dad"];
		$lmID=$_POST["lmID"];
		$monID=$_POST["monID"];
		add_chuyende($maso, $title, $dad, $lmID);
		echo get_new_chuyende_id();
	}
	
	if(isset($_POST["monID3"])) {
		$monID=$_POST["monID3"];
		$result=get_all_chuyende_all($monID);
		echo"<option value='0'>Chọn chuyên đề</option>";
		while($data=mysqli_fetch_assoc($result)) {
			if($data["dad"]!=0) {
        		echo"<option value='$data[ID_CD]'>$data[name] - $data[maso] - $data[title]</option>";
     		}
		}
	}
	
	if(isset($_POST["lmID2"])) {
		$lmID=$_POST["lmID2"];
		$result=get_all_chuyende($lmID);
		echo"<option value='0'>Chọn chuyên đề</option>";
		while($data=mysqli_fetch_assoc($result)) {
			if($data["dad"]!=0) {
        		echo"<option value='$data[ID_CD]'>$data[maso] - $data[title]</option>";
     		}
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>