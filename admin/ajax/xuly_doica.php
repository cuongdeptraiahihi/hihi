<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	if(isset($_POST["caID_cd"]) && isset($_POST["monID"]) && isset($_POST["hsID"])) {
		$caID=decode_data($_POST["caID_cd"],md5(123456));
		$caID=$caID+1-1;
		$monID=$_POST["monID"];
		$hsID=$_POST["hsID"];
		$result=get_mon_info($monID);
		$data=mysqli_fetch_assoc($result);
		$cum=get_ca_cum($caID,$data["ca"]);
		if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
			add_hs_to_ca_codinh($caID, $hsID, $cum, $data["ca_hientai"], $data["ca_codinh"]);  
			$result=get_info_ca($caID,$data["ca"]);
			$data=mysqli_fetch_assoc($result);
			add_log($hsID,"Trợ giảng đổi cố định sang ca ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$monID");
		}
	}
	
	if(isset($_POST["caID_tam"]) && isset($_POST["monID"]) && isset($_POST["hsID"])) {
		$caID=decode_data($_POST["caID_tam"],md5(123456));
		$caID=$caID+1-1;
		$monID=$_POST["monID"];
		$hsID=$_POST["hsID"];
		$result=get_mon_info($monID);
		$data=mysqli_fetch_assoc($result);
		$cum=get_ca_cum($caID,$data["ca"]);
		if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
			add_hs_to_ca_tam($caID, $hsID, $cum, $data["ca_hientai"]);
			$result=get_info_ca($caID,$data["ca"]);
			$data=mysqli_fetch_assoc($result);
			add_log($hsID,"Trợ giảng đổi tạm sang ca ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$monID");
		}
	}
	
	if(isset($_POST["caID_del"]) && isset($_POST["monID"]) && isset($_POST["hsID"])) {
		$caID=decode_data($_POST["caID_del"],md5(123456));
		$caID=$caID+1-1;
		$monID=$_POST["monID"];
		$hsID=$_POST["hsID"];
		$result=get_mon_info($monID);
		$data=mysqli_fetch_assoc($result);
		$cum=get_ca_cum($caID,$data["ca"]); 
		if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
			delete_hs_ca_tam($caID, $hsID, $cum, $data["ca_hientai"], $data["ca_codinh"]);
			$result=get_info_ca($caID,$data["ca"]);
			$data=mysqli_fetch_assoc($result);
			add_log($hsID,"Trợ giảng hủy ca tạm ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$monID");
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>