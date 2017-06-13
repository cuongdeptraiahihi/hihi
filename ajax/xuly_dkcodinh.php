<?php
	ob_start();
	//session_start();
	require("../model/open_db.php");
	require("../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	$hsID=$_SESSION["ID_HS"];
	$monID=$_SESSION["mon"];
	$code=$_SESSION["code"];
	$lopID=$_SESSION["lop"];
    $lmID=$_SESSION["lmID"];
	
	if (isset($_POST["caID1"])) {
		$caID=decode_data($_POST["caID1"],$code);
		$result=check_ca_full_codinh($caID, $monID);
		echo $result;
	}
	
	if(isset($_POST["action"])) {
		$action=$_POST["action"];
		if($action=="lichsu-ca") {
			$dem=0;
			$result=get_log_hs($hsID,"doi-ca-$lmID");
			while($data=mysqli_fetch_assoc($result)) {
				echo"<p>".format_datetime($data["datetime"])." - $data[content]</p>";
				if($dem==10) {
					break;
				}
				$dem++;
			}
			if($dem==0) {
				echo"<p>Không có lịch sử</p>";
			}
		}
	}
	
	/*if (isset($_POST["caID0"])) {
		$caID=decode_data($_POST["caID0"],$code);
		delete_hs_ca_codinh($caID, $hsID, get_ca_cum($caID, $cahoc_string), $ca_hientai_string, $ca_codinh_string);
	}*/
	
	if (isset($_POST["caID2"])) {
		$caID=decode_data($_POST["caID2"],$code);
		$caID=$caID+1-1;
		//if(check_unlock_ca_hs($hsID,$caID,$monID) || $cum==4) {
		$cum=get_ca_cum($caID);
		if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
			add_hs_to_ca_tam($caID, $hsID, $cum);
			$result=get_info_ca($caID);
			$data=mysqli_fetch_assoc($result);
			add_log($hsID,"Hủy ca tạm, trở về ca cố định ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$lmID");
			echo"Lịch học của bạn sẽ chở về ban đầu!";
		} else {
			echo"Lỗi dữ liệu!";
		}
	}
	
	if (isset($_POST["caID"]) && isset($_POST["check"])) {
		$caID=decode_data($_POST["caID"],$code);
		$caID=$caID+1-1;
		$check=$_POST["check"];
		//if(check_unlock_ca_hs($hsID,$caID,$monID) || $cum==4) {
		//if(get_tien_hs($hsID)>=20000) {
		$cum=get_ca_cum($caID);
		if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
			add_hs_to_ca_codinh($caID, $hsID, $cum);
			$result=get_info_ca($caID);
			$data=mysqli_fetch_assoc($result);
			add_log($hsID,"Đổi cố định sang ca ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$lmID");
			if(!check_on_catime($lmID)) {
				switch($check) {
					case "vang":
//						if(!check_cong_tien($hsID,$lmID)) {
//							cong_tien_hs($hsID, get_muctien("codinh_to_small"), "Chuyển cố định sang ca vắng","","");
//						}
						break;
					case "quatai":
					    if(!check_binh_voi2($hsID,$lmID)) {
                            tru_tien_hs($hsID, get_muctien("codinh_to_big"), "Chuyển cố định sang ca quá tải", "", "");
                        }
						break;
				}
			}
			echo"Bạn đã chuyển thành công!";
		} else {
			echo"Lỗi dữ liệu!";
		}
	}
	
	require("../model/close_db.php");
	ob_end_flush();
?>