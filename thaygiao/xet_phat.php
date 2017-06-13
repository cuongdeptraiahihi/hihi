<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	
	if(isset($_GET["buoiID"]) && isset($_GET["lopID"]) && isset($_GET["monID"])) {
		$buoiID=$_GET["buoiID"];
		$lopID=$_GET["lopID"];
		$monID=$_GET["monID"];
		$diem_string=get_diemkt_string($monID);
		$mon_name=get_mon_name($monID);
		$lop_name=get_lop_name($lopID);
		$buoi=get_ngay_buoikt($buoiID);
		if(check_done_diem($buoiID,$lopID,$monID,$diem_string)) {
			$query="SELECT d.*,m.date_in FROM diemkt AS d INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_MON='$monID' INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS AND h.lop='$lopID' WHERE d.ID_BUOI='$buoiID' AND d.loai IN ('0','1','3','5')";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				$date=date_create($data["date_in"]);
				if(date_create($buoi)<$date) {
					// Thời điểm này chưa vào học
				} else {
					if(!check_binh_voi($data["ID_HS"],$buoiID,$diem_string)) {
					    if($data["diem"]<5.25 && check_exited_thachdau4($data["ID_HS"],$buoi,$lopID,$monID)) {
                            // Không sao cả
                        } else {
                            $temp = get_phat_diemkt($data["ID_HS"], $data["diem"], $data["de"], $data["loai"], $data["note"], $monID, $mon_name, $buoiID, $buoi, true);
                        }
                    }
				}
			}
			add_sync("Xét phạt tiền điểm kiểm tra cho môn $mon_name, lớp $lop_name ngày thi ".format_dateup($buoi));
		}
		header("location:http://localhost/www/TDUONG/thaygiao/nhap-diem/");
		exit();
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>