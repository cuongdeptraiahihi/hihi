<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	
	if (isset($_GET["search"])) {
		$search=unicode_convert($_GET["search"]);
		$result=search_video($search, $_SESSION["mon"]);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có video này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='http://localhost/www/TDUONG/admin/video/$data[ID_VIDEO]/'>$data[title]<span>$data[dateup]</span></a></li>";
			}
		}
	}
	
	if (isset($_GET["search2"]) && isset($_GET["cdID"])) {
		$cdID=$_GET["cdID"];
		$search=unicode_convert($_GET["search2"]);
		$result=search_video_cd($search, $cdID);
		if(mysqli_num_rows($result)==0) {
			echo"<li><p>Không có video này!</p></li>";
		} else {
			while($data=mysqli_fetch_assoc($result)) {
				echo"<li><a href='http://localhost/www/TDUONG/admin/video/$data[ID_VIDEO]/'>$data[title]<span>$data[dateup]</span></a></li>";
			}
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>