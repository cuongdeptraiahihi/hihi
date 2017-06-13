<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	
	/*$query="SELECT ngay FROM buoikt ORDER BY ngay DESC LIMIT 1";
	$result=mysqli_query($db,$query);
	$data=mysqli_fetch_assoc($result);
	$date=date_create($data["ngay"]);
	date_add($date,date_interval_create_from_date_string("7 days"));
	$next_date=date_format($date,"Y-m-d");*/
	if(isset($_POST["action"])) {
		$next_date=get_last_CN();
		insert_new_buoikt($next_date);
		
		add_sync("Thêm buổi kiểm tra tiếp theo: ".$next_date);
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>