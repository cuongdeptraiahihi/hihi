<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if(isset($_POST["videoID_all"]) && isset($_POST["monID"]) && isset($_POST["lopID"]) && isset($_POST["action"])) {
		$videoID=$_POST["videoID_all"];
		$monID=$_POST["monID"];
		$lopID=$_POST["lopID"];
		$action=$_POST["action"];
		if($action=="on") {
			$result=get_all_hocsinh_id($lopID,$monID);
			while($data=mysqli_fetch_assoc($result)) {
				turn_on_video($data["ID_HS"], $videoID);
			}
		} else {
			$result=get_all_hocsinh_id($lopID,$monID);
			while($data=mysqli_fetch_assoc($result)) {
				turn_off_video($data["ID_HS"], $videoID);
			}
		}
	}
	
	if (isset($_GET["hsID0"]) && isset($_GET["cdID0"])) {
		$hsID=$_GET["hsID0"];
		$cdID=$_GET["cdID0"];
		$dem=0;
		$result=get_video_same_cdadmin($cdID);
		while($data=mysqli_fetch_assoc($result)) {
			if($dem%2!=0) {
				echo"<tr style='background:#D1DBBD'>";
			} else {
				echo"<tr>";
			}
			echo"
					<td><span>#$data[ID_VIDEO]</span></td>
					<td><span>$data[title]</span><br /><span class='dateup'>".format_dateup($data["dateup"])."</span></td>
					<td><span>".get_chuyende($data["ID_CD"])."</span></td>
                  	<td class='con-action'><span>";
					if(check_video_base_hs($hsID, $data["ID_VIDEO"])) {
						echo"<i class='fa fa-check-square-o' data-videoID='$data[ID_VIDEO]' data-hsID='$hsID'></i>";
					} else {
						echo"<i class='fa fa-square-o' data-videoID='$data[ID_VIDEO]' data-hsID='$hsID'></i>";
					}
					echo"</span></td>
				</tr>
			";
			$dem++;
		}
	}
	
	if (isset($_POST["videoID"]) && isset($_POST["hsID"]) && isset($_POST["action"])) {
		$videoID=$_POST["videoID"];
		$hsID=$_POST["hsID"];
		$action=$_POST["action"];
		if($videoID != 0 && $hsID != 0) {
			if($action=="on") {
				turn_on_video($hsID, $videoID);
			} else {
				turn_off_video($hsID, $videoID);
			}
			echo"true";
		} else {
			echo"false";
		}
	}
	
	if (isset($_POST["hsID"]) && isset($_POST["cdID"]) && isset($_POST["actionAll"])) {
		$hsID=$_POST["hsID"];
		$cdID=$_POST["cdID"];
		$action=$_POST["actionAll"];
		if($hsID != 0) {
			if($action=="on") {
				turn_on_all_video($hsID, $cdID);
			} else {
				turn_off_all_video($hsID);
			}
			echo"true";
		} else {
			echo"false";
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>