<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");
	
	if (isset($_POST["dmID"]) && isset($_POST["title"])) {
		$dmID=$_POST["dmID"];
		$title=$_POST["title"];
		edit_danhmuc($dmID, $title);
	}
	
	if (isset($_POST["dmID0"])) {
		$dmID=$_POST["dmID0"];
		if(count_tailieu_sl2($dmID)==0) {
			delete_danhmuc($dmID);
		}
	}
	
	if(isset($_POST["title0"]) && isset($_POST["lmID"]) && isset($_POST["monID"])) {
		$title=$_POST["title0"];
        $lmID=$_POST["lmID"];
		$monID=$_POST["monID"];
		add_danhmuc($title,$lmID,$monID);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>