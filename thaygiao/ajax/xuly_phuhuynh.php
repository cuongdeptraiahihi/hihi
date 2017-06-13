<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if(isset($_POST["cmt"])) {
		$cmt=$_POST["cmt"];
		if(check_exited_hocsinh($cmt)) {
			$query0="SELECT ID_HS,fullname,birth,sdt_bo,sdt_me FROM hocsinh WHERE cmt='$cmt'";
			$result0=mysqli_query($db,$query0);
			if(mysqli_num_rows($result0)!=0) {
				$data0=mysqli_fetch_assoc($result0);
				$bo_name=$bo_phone=$bo_job=$bo_face=$bo_mail=$me_name=$me_phone=$me_job=$me_face=$me_mail=NULL;
				$has_bo=$has_me="none";
				$query="SELECT * FROM phuhuynh WHERE ID_HS='$data0[ID_HS]' ORDER BY gender DESC";
				$result=mysqli_query($db,$query);
				while($data=mysqli_fetch_assoc($result)) {
					if($data["gender"]==1) {
						$has_bo="ok";
						$bo_name=$data["name"];
						$bo_phone=$data0["sdt_bo"];
						$bo_job=$data["job"];
						$bo_face=$data["face"];
						$bo_mail=$data["mail"];
					}
					if($data["gender"]==0) {
						$has_me="ok";
						$me_name=$data["name"];
						$me_phone=$data0["sdt_me"];
						$me_job=$data["job"];
						$me_face=$data["face"];
						$me_mail=$data["mail"];
					}
				}
				if($has_bo=="ok" || $has_me=="ok") {
					echo json_encode(
						array(
							"cmt" => $cmt,
							"hsID" => $data0["ID_HS"],
							"fullname" => $data0["fullname"],
							"birth" => format_dateup($data0["birth"]),
							"has_bo" => $has_bo,
							"bo_name" => $bo_name,
							"bo_phone" => $bo_phone,
							"bo_job" => $bo_job,
							"bo_face" => $bo_face,
							"bo_mail" => $bo_mail,
							"has_me" => $has_me,
							"me_name" => $me_name,
							"me_phone" => $me_phone,
							"me_job" => $me_job,
							"me_face" => $me_face,
							"me_mail" => $me_mail
						)
					);
				} else {
					echo json_encode(
						array(
							"cmt" => $cmt,
							"hsID" => $data0["ID_HS"],
							"fullname" => $data0["fullname"],
							"birth" => format_dateup($data0["birth"]),
							"has_bo" => $has_bo,
							"has_me" => $has_me
						)
					);
				}
			} else {
				echo"none";
			}
		} else {
			echo"none";
		}
	}
	
	if(isset($_POST["hsID"]) && isset($_POST["bo_name"]) && isset($_POST["bo_phone"]) && isset($_POST["bo_job"]) && isset($_POST["bo_face"]) && isset($_POST["bo_mail"]) && isset($_POST["me_name"]) && isset($_POST["me_phone"]) && isset($_POST["me_job"]) && isset($_POST["me_face"]) && isset($_POST["me_mail"]) && isset($_POST["how_bo"]) && isset($_POST["how_me"])) {
		$hsID=$_POST["hsID"];
		$bo_name=$_POST["bo_name"];
		$bo_phone=$_POST["bo_phone"];
		$bo_job=$_POST["bo_job"];
		$bo_face=$_POST["bo_face"];
		$bo_mail=$_POST["bo_mail"];
		$me_name=$_POST["me_name"];
		$me_phone=$_POST["me_phone"];
		$me_job=$_POST["me_job"];
		$me_face=$_POST["me_face"];
		$me_mail=$_POST["me_mail"];
		$how_bo=$_POST["how_bo"];
		$how_me=$_POST["how_me"];
		if($hsID!=0 && is_numeric($hsID) && ($how_bo=="new" || $how_bo=="old") && ($how_me=="new" || $how_me=="old")) {
			if($bo_name!="" || $bo_phone!="" || $bo_job!="" || $bo_face!="" || $bo_mail!="") {
				if($how_bo=="new") {
					add_phuhuynh($hsID,$bo_name,$bo_phone,$bo_job,$bo_face,$bo_mail,1);
					echo"ok";
				} else {
					edit_phuhuynh($hsID,$bo_name,$bo_phone,$bo_job,$bo_face,$bo_mail,1);
					echo"edit";
				}
			} else {
				delete_phuhuynh($hsID,1);
				echo"edit";
			}
			if($me_name!="" || $me_phone!="" || $me_job!="" || $me_face!="" || $me_mail!="") {
				if($how_me=="new") {
					add_phuhuynh($hsID,$me_name,$me_phone,$me_job,$me_face,$me_mail,0);
					echo"ok";
				} else {
					edit_phuhuynh($hsID,$me_name,$me_phone,$me_job,$me_face,$me_mail,0);
					echo"edit";
				}
			} else {
				delete_phuhuynh($hsID,0);
				echo"edit";
			}
		} else {
			echo"none";
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>