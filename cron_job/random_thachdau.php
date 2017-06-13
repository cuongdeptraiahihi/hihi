<?php
	ob_start();
	ini_set('max_execution_time', 1200);
	require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");
	
	// Xác định ngày hôm nay
	$date=getdate(date("U"));
	$current=$date["wday"]+1;
	
	$now_month=date("Y-m");
	
	if($current==5) {
		$next_CN=get_next_CN();
		$tien=get_muctien("thach-dau");
		// Lấy ra các môn, lớp
		$query="SELECT * FROM lop_mon ORDER BY ID_LM ASC";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			if(!check_khoa($data["ID_LM"])) {
				$query2="SELECT h.ID_HS,h.fullname,m.de FROM hocsinh AS h 
				INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.de='G' AND m.ID_LM='$data[ID_LM]' 
				WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$data[ID_LM]') AND h.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE start<='$next_CN' AND end>='$next_CN' AND ID_LM='$data[ID_LM]') AND h.taikhoan>'$tien' ORDER BY rand()";
				$result2=mysqli_query($db,$query2);
				$me=array();
				$dem=0;
				while($data2=mysqli_fetch_assoc($result2)) {
					
					if(check_exited_thachdau3($data2["ID_HS"],$next_CN,$data["ID_LM"])) {
                        continue;
                    }
					
					$diemtb=0;
					if(count($me)==1) {
						
						$diemtb=tinh_diemtb_month2($data2["ID_HS"],$data["ID_LM"]);
						if($diemtb==0) {continue;}

						$chap=abs($diemtb-$me[0]["diemtb"]);
                        if($chap<=1) {
                            $chap3=0;
                        } else {
                            $chap2=$chap-1;
                            $chap3=floor($chap2)+get_diem_near($chap2-floor($chap2));
                        }

                        if($chap3>2) {
                            $chap3=2;
                        }
						
						if($diemtb>$me[0]["diemtb"]) {
							add_thachdau($data2["ID_HS"], $me[0]["ID_HS"], $next_CN, $chap3, $tien, $data["ID_LM"]);
							$id=mysqli_insert_id($db);
							tru_tien_hs($data2["ID_HS"], $tien, "[Ngẫu nhiên] Trừ tiền đi thách đấu cho ngày thi ".format_dateup($next_CN),"thach-dau","");
							
							accept_thachdau2($me[0]["ID_HS"], $id);
							tru_tien_hs($me[0]["ID_HS"], $tien, "[Ngẫu nhiên] Trừ tiền nhận thách đấu cho ngày thi ".format_dateup($next_CN),"thach-dau","");
							
							add_thong_bao_hs2($data2["ID_HS"],$id,"Bạn ($diemtb) đã được chọn ngẫu nhiên thách đấu với ".$me[0]["fullname"]." (".$me[0]["diemtb"].") vào ngày ".format_dateup($next_CN)." và bạn chấp $chap3 điểm","thach-dau",$data["ID_LM"]);
							add_thong_bao_hs2($me[0]["ID_HS"],$id,"Bạn (".$me[0]["diemtb"].") đã được chọn ngẫu nhiên thách đấu với ".$data2["fullname"]." ($diemtb) vào ngày ".format_dateup($next_CN)." và bạn được chấp $chap3 điểm","thach-dau",$data["ID_LM"]);
						} else {
							add_thachdau($me[0]["ID_HS"], $data2["ID_HS"], $next_CN, $chap3, $tien, $data["ID_LM"]);
							$id=mysqli_insert_id($db);
							tru_tien_hs($me[0]["ID_HS"], $tien, "[Ngẫu nhiên] Trừ tiền đi thách đấu cho ngày thi ".format_dateup($next_CN),"thach-dau","");
							
							accept_thachdau2($data2["ID_HS"], $id);
							tru_tien_hs($data2["ID_HS"], $tien, "[Ngẫu nhiên] Trừ tiền nhận thách đấu cho ngày thi ".format_dateup($next_CN),"thach-dau","");
							
							add_thong_bao_hs2($me[0]["ID_HS"],$id,"Bạn (".$me[0]["diemtb"].") đã được chọn ngẫu nhiên thách đấu với ".$data2["fullname"]." ($diemtb) vào ngày ".format_dateup($next_CN)." và bạn chấp $chap3 điểm","thach-dau",$data["ID_LM"]);
							add_thong_bao_hs2($data2["ID_HS"],$id,"Bạn ($diemtb) đã được chọn ngẫu nhiên thách đấu với ".$me[0]["fullname"]." (".$me[0]["diemtb"].") vào ngày ".format_dateup($next_CN)." và bạn được chấp $chap3 điểm","thach-dau",$data["ID_LM"]);
						}
						
						$string="$data2[ID_HS] ($diemtb - $data2[de]) thách đấu ".$me[0]["ID_HS"]." (".$me[0]["diemtb"]." - ".$me[0]["de"].") - $chap - $chap3";
						add_log(0,$string,"thach-dau");
                        echo $string."<br />";
						
						$dem++;
						$me=array();
					} else {
						$diemtb=tinh_diemtb_month2($data2["ID_HS"],$data["ID_LM"]);
						if($diemtb==0) {continue;}
						$me[]=array(
							"ID_HS" => $data2["ID_HS"],
							"fullname" => $data2["fullname"],
							"diemtb" => $diemtb,
							"de" => $data2["de"]
						);
					}
				}
			}
		}
			
		add_sync("Đã ramdom ra 6 cặp thách đâu cho mỗi môn!");
        $html = file_get_contents("/home/nginx/bgo.edu.vn/public_html/email.html");
        $html = str_replace("%title%", "Kết quả random thách đấu " . date("j") . "/" . date("m") . "/" . date("Y"), $html);
        $html = str_replace("%sub_title%", "", $html);
        $html = str_replace("%content%", "Đã ramdom ra 6 cặp thách đâu cho mỗi môn!", $html);
        $html = str_replace("%ps%", "@2016 Bgo.edu.vn", $html);
        send_email("noreply@bgo.edu.vn", "mactavish124!@", "dinhvankiet124@gmail.com", "BGO.EDU.VN: Kết quả random thách đấu!", $html, true);
	}
	
	ob_end_flush();
	require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>