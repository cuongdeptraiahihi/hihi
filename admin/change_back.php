<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	$option=$image=$oID=NULL;
	
	if(isset($_GET["link"])) {
		if(isset($_POST["ok-anh"])) {
			if(isset($_POST["select-anh"])) {
				$option=$_POST["select-anh"];
				if($option==1) {
					if($_FILES["form-anh"]["error"]>0) {
					} else {
						$image=$_FILES["form-anh"]["name"];
						move_uploaded_file($_FILES["form-anh"]["tmp_name"],"../images/".$_FILES["form-anh"]["name"]);	
						up_background($image);
					}
				}
				if($option==2) {
					if(isset($_POST["chon-anh"])) {
						$oID=$_POST["chon-anh"];
						chose_background($oID);
					}
				}
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