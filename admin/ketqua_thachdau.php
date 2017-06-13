<?php
	ob_start();
	ini_set('max_execution_time', 600);
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	
	// Code này xuất ra kết quả các trận thách đấu
	
	if(isset($_GET["buoiID"]) && isset($_GET["lmID"])) {
		$buoiID=$_GET["buoiID"];
		$lmID=$_GET["lmID"];
		$mon_lop_name=get_lop_mon_name($lmID);
        $monID=get_mon_of_lop($lmID);
		$buoi=get_ngay_buoikt($buoiID);
		$tien=get_muctien("thach-dau");
		if(check_done_options($buoiID, "phu-diem",$lmID,$monID) && !check_done_options($buoiID, "kq-thach-dau",$lmID,$monID)) {
			$query="SELECT ID_STT,ID_HS,ID_HS2,chap FROM thachdau WHERE ketqua='Z' AND buoi='$buoi' AND status='accept' AND ID_LM='$lmID'";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				$chap=abs($data["chap"]);
				$temp1=get_diem_hs3($data["ID_HS"], $buoiID, $lmID);
				$temp2=get_diem_hs3($data["ID_HS2"], $buoiID, $lmID);
                if($data["chap"]<0) {
                    $diem1=$temp1[0]+$chap;
                    $diem2=$temp2[0];
                } else {
                    $diem1=$temp1[0];
                    $diem2=$temp2[0]+$chap;
                }
                if($diem1>10) {$diem1=10;}
                if($diem2>10) {$diem2=10;}
				if(is_numeric($diem1) && is_numeric($diem2) && $diem1>=0 && $diem2>=0) {
				    if($temp1[1]==0 && $temp2[1]==0) {
                        if($diem1==$diem2) {
                            ketqua_thachdau($data["ID_STT"], "X");
                            cong_tien_hs($data["ID_HS"], $tien, "Hoàn tiền thách đấu do kết quả hòa ngày ".format_dateup($buoi),"thach-dau","");
                            cong_tien_hs($data["ID_HS2"],$tien, "Hoàn tiền thách đấu do kết quả hòa ngày ".format_dateup($buoi),"thach-dau","");
                        } else if($diem1>$diem2) {
                            ketqua_thachdau($data["ID_STT"], $data["ID_HS"]);
                            cong_tien_hs($data["ID_HS"], 2*$tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày ".format_dateup($buoi),"thach-dau","");
                            add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"Chúc mừng bạn đã chiến thắng thách đấu ngày ".format_dateup($buoi)." và nhận ".format_price(2*$tien),"thach-dau",$lmID);
                            update_level($data["ID_HS"],$lmID,get_level($data["ID_HS"],$lmID));
                        } else {
                            ketqua_thachdau($data["ID_STT"], $data["ID_HS2"]);
                            cong_tien_hs($data["ID_HS2"], 2*$tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày ".format_dateup($buoi),"thach-dau","");
                            add_thong_bao_hs($data["ID_HS2"],$data["ID_STT"],"Chúc mừng bạn đã chiến thắng thách đấu ngày ".format_dateup($buoi)." và nhận ".format_price(2*$tien),"thach-dau",$lmID);
                            update_level($data["ID_HS2"],$lmID,get_level($data["ID_HS2"],$lmID));
                        }
                    } else {
                        if($temp1[1]==0 && $temp2[1]!=0) {
                            ketqua_thachdau($data["ID_STT"], $data["ID_HS"]);
                            cong_tien_hs($data["ID_HS"], 2*$tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày ".format_dateup($buoi),"thach-dau","");
                            update_level($data["ID_HS"],$lmID,get_level($data["ID_HS"],$lmID));
                            add_thong_bao_hs($data["ID_HS2"],$data["ID_STT"],"Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày ".format_dateup($buoi),"thach-dau",$lmID);
                        } else if($temp1[1]!=0 && $temp2[1]==0) {
                            ketqua_thachdau($data["ID_STT"], $data["ID_HS2"]);
                            cong_tien_hs($data["ID_HS2"], 2 * $tien, "Cộng tiền thắng (và hoàn tiền cọc) thách đấu ngày " . format_dateup($buoi), "thach-dau", "");
                            update_level($data["ID_HS2"],$lmID,get_level($data["ID_HS2"],$lmID));
                            add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày " . format_dateup($buoi), "thach-dau", $lmID);
                        } else {
                            ketqua_thachdau($data["ID_STT"],"X");
                            add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày " . format_dateup($buoi), "thach-dau", $lmID);
                            add_thong_bao_hs($data["ID_HS2"],$data["ID_STT"],"Bạn đã bị xử thua thách đấu do không làm bài trên lớp hoặc bị hủy bài ngày " . format_dateup($buoi), "thach-dau", $lmID);
                        }
                    }
				}
			}
			add_options($buoiID, "kq-thach-dau", $lmID, $monID);
			add_sync("Xét kết quả thách đấu cho môn $mon_lop_name, ngày thi ".format_dateup($buoi));
		}
		header("location:http://localhost/www/TDUONG/admin/thach-dau/");
		exit();
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>