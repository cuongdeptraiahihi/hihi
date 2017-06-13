<?php
	ob_start();
	ini_set('max_execution_time', 600);
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	
	// Code này xuất ra kết quả ngôi sao hy vọng
		
	if(isset($_GET["buoiID"]) && isset($_GET["lmID"])) {
		$buoiID=$_GET["buoiID"];
        $lmID=$_GET["lmID"];
        $mon_lop_name=get_lop_mon_name($lmID);
        $monID=get_mon_of_lop($lmID);
        $buoi=get_ngay_buoikt($buoiID);
		if(check_done_options($buoiID, "phu-diem",$lmID,$monID) && !check_done_options($buoiID, "kq-ngoi-sao", $lmID, $monID)) {
			$query="SELECT n.ID_STT,n.ID_HS,n.tien,d.diem,d.loai FROM ngoi_sao AS n 
			INNER JOIN diemkt AS d ON d.ID_BUOI='$buoiID' AND d.ID_HS=n.ID_HS AND d.ID_LM='$lmID' 
			WHERE n.buoi='$buoi' AND n.ketqua='Z' AND n.status='pending' AND n.ID_LM='$lmID'";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
			    if($data["loai"]==0) {
                    if ($data["diem"] >= 9 && is_numeric($data["diem"])) {
                        ketqua_ngoisao($data["ID_STT"], 1);
                        cong_tien_hs($data["ID_HS"], $data["tien"]*2, "Cộng tiền chiếm thắng ngôi sao hy vọng (x2 tiền cược) môn $mon_lop_name ngày " . format_dateup($buoi), "", "");
                        add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"Chúc mừng bạn đã chiến thắng ngôi sao hy vọng ngày ".format_dateup($buoi)." và nhận ".format_price($data["tien"]*2),"ngoi-sao",$lmID);
                    } else {
                        ketqua_ngoisao($data["ID_STT"], 0);
                        add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"Bạn đã thua ngôi sao hy vọng ngày ".format_dateup($buoi)." nên sẽ bị mất tiền cược!","ngoi-sao",$lmID);
                    }
                } else {
                    ketqua_ngoisao($data["ID_STT"], 0);
                    add_thong_bao_hs($data["ID_HS"],$data["ID_STT"],"Bạn đã bị xử thua ngôi sao hy vọng ngày ".format_dateup($buoi)." do không làm bài trên lớp! Bạn sẽ bị mất tiền cược!","ngoi-sao",$lmID);
                }
			}
            add_options($buoiID, "kq-ngoi-sao", $lmID, $monID);
			add_sync("Xét kết quả ngôi sao hy vọng cho môn $mon_lop_name ngày thi ".format_dateup($buoi));
		}
		header("location:http://localhost/www/TDUONG/thaygiao/ngoi-sao/");
		exit();
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>