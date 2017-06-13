<?php
	ob_start();
	ini_set('max_execution_time', 1200);
	require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");
	
	// Code này để tính điểm TB tháng cho học sinh, phục vụ cho việc nhảy đề sau đó
	// Chạy đêm hàng ngày
	// Hủy bài tính là 0 điểm
	
	$now=date("j");
	$result0=count_lastCN();
	$next_fir=$result0[0];
	$cn=$result0[1];
	
	if($now<=5) {
		$time=get_last_time(date("Y"),date("m"));
	} else {
		$time=date("Y-m");
	}
	$temp=explode("-",$time);
	$nextm=get_next_time($temp[0],$temp[1]);
	
	$check=check_nhayde();
	if($check) {
		clean_new_nhayde();
	}
	
	if($next_fir==$now && !check_diemtb_thang($time)) {
		$monID=0;
		$query="SELECT hocsinh.ID_HS,hocsinh.lop,hocsinh_mon.de,hocsinh_mon.ID_MON FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.date_in<'$nextm-01' ORDER BY hocsinh_mon.ID_MON ASC,hocsinh.cmt ASC";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			if($monID!=$data["ID_MON"]) {
				$monID=$data["ID_MON"];
				$diem_string=get_diemkt_string($monID);
			}
			$diemtb=tinh_diemtb_month($data["ID_HS"], $time, $diem_string);
			if($diemtb!=NULL) {
				insert_diemtb_thang($data["ID_HS"], $diemtb, $data["de"], $data["lop"], $monID, $time);
                //echo $data["ID_HS"]." - ".$diemtb."<br />";
				if($diemtb>=8) {
					$new_de="G";
				} else if($diemtb<5){
					$new_de="B";
				} else {
					$new_de=$data["de"];
				}
				if($data["de"]!=$new_de && $check) {
					update_de_hs($data["ID_HS"], $new_de, $monID);
					insert_new_nhayde($data["ID_HS"], $new_de, $diemtb, $monID);
					if($new_de=="G") {
						add_thong_bao_hs2($data["ID_HS"],1,"Chúc mừng bạn đã chuyến sang đề G từ ".format_month($nextm),"nhay-de",$monID);
					} else {
						add_thong_bao_hs2($data["ID_HS"],0,"Rất tiếc bạn phải chuyển xuống làm đề B từ ".format_month($nextm),"nhay-de",$monID);
					}
				}
			}
		}
		
		if(!$check) {
			add_sync("Không nhảy đề tháng $month!");
		} else {
			add_sync("Đã tính điểm trung bình và xét nhảy đề tháng $month!");
		}
	}
	
	ob_end_flush();
	require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>