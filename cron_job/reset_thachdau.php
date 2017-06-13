<?php
	ob_start();
	ini_set('max_execution_time', 600);
	require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");
	
	// Code này kiểm tra và xóa các lời thách đấu vẫn còn đang trong trạng thái chờ
	// Chạy vào 23h00 ngày thứ 7 hàng tuần

	$query="SELECT ID_HS,tien,buoi FROM thachdau WHERE thachdau.status='pending'";
	$result=mysqli_query($db,$query);
	while($data=mysqli_fetch_assoc($result)) {
		cong_tien_hs($data["ID_HS"], $data["tien"], "Hoàn tiền do hết hạn chờ chấp nhận thách đấu cho ngày thi ".format_dateup($data["buoi"]),"","");
	}

	$query2="DELETE FROM thachdau WHERE status='pending' OR status='cancle'";
    mysqli_query($db,$query2);
	
	add_sync("Đã xóa và hoàn tiền các lời thách đấu ko được chấp nhận hoặc đã hủy!");
    $html = file_get_contents("/home/nginx/bgo.edu.vn/public_html/email.html");
    $html = str_replace("%title%", "Reset thách đấu " . date("j") . "/" . date("m") . "/" . date("Y"), $html);
    $html = str_replace("%sub_title%", "", $html);
    $html = str_replace("%content%", "Đã xóa và hoàn tiền các lời thách đấu ko được chấp nhận hoặc đã hủy!", $html);
    $html = str_replace("%ps%", "@2016 Bgo.edu.vn", $html);
    send_email("noreply@bgo.edu.vn", "mactavish124!@", "dinhvankiet124@gmail.com", "BGO.EDU.VN: Reset thách đấu!", $html, true);
	
	ob_end_flush();
	require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>