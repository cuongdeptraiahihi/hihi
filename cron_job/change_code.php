<?php
	ob_start();
	ini_set('max_execution_time', 600);
	require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");
	
	// Code này chạy tạo mã bảo mật và gửi mail
	// Chạy hàng tuần vào sáng CN hàng tuần 1h30
	$code=rand_pass(8);
	insert_code_ad(md5($code));
	$html=file_get_contents("/home/nginx/bgo.edu.vn/public_html/email.html");
	$html=str_replace("%title%","Hệ thống tự động gửi bảo mật mới ".date("j")."/".date("m")."/".date("Y"),$html);
	$html=str_replace("%sub_title%","",$html);
	$html=str_replace("%content%","$code",$html);
	$html=str_replace("%ps%","",$html);
	send_email("noreply@bgo.edu.vn","mactavish124!@","dinhvankiet124@gmail.com","BGO.EDU.VN: Mã bảo mật!",$html,true);
	
	add_sync("Đã tạo và gửi mã bảo mật!");
	
	ob_end_flush();
	require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>