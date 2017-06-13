<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if (isset($_POST["oID"]) && isset($_POST["face"]) && isset($_POST["lmID"])) {
		$oID=$_POST["oID"];
		$face=$_POST["face"];
		$lmID=$_POST["lmID"];
		edit_facebook($oID,$face,$lmID);
	}
	
	if(isset($_POST["oID2"])) {
		$oID=$_POST["oID2"];
		$result=get_options($oID);
		$data=mysqli_fetch_assoc($result);
		update_face_hs($data["content"],$data["note"]);
		delete_options($oID);
	}
	
	if(isset($_POST["oID3"])) {
		$oID=$_POST["oID3"];
		delete_options($oID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>