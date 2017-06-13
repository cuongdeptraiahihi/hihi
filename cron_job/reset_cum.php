<?php
	ob_start();
	ini_set('max_execution_time', 2000);
	require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	// Code này chạy vào đêm các ngày trong tuần từ đêm thứ 2 đến Chủ nhật
	// Chạy vào lúc 23h00 hàng ngày
	
	// Xác định ngày hôm nay
	$date=getdate(date("U"));
	$current=$date["wday"]+1;
	//echo"$current<br />";
	// Lấy ra các môn, lớp
    $string = "";
	$query="SELECT * FROM lop_mon ORDER BY ID_LM ASC";
	$result=mysqli_query($db,$query);
	while($data=mysqli_fetch_assoc($result)) {
		// Xác định các cụm thuộc ngày hôm nay
        $query1="SELECT DISTINCT MAX(thu) AS max_thu,cum FROM cahoc 
		WHERE cum IN (SELECT DISTINCT c.ID_CUM FROM cum AS c INNER JOIN cahoc AS a ON a.cum=c.ID_CUM AND a.thu='$current' WHERE (c.ID_LM='$data[ID_LM]' OR c.ID_LM='0') AND c.ID_MON='$data[ID_MON]' ORDER BY c.ID_CUM ASC) 
		ORDER BY thu DESC";
		//$query1="SELECT DISTINCT max(thu) AS max_thu,cum FROM cahoc
		//WHERE cum IN (SELECT DISTINCT c.cum FROM cahoc AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$data[ID_LM]'
		//WHERE c.thu='$current' ORDER BY c.cum ASC) ORDER BY thu DESC";
		$result1=mysqli_query($db,$query1);
		while($data1=mysqli_fetch_assoc($result1)) {
			//echo"$data[ID_LM] - $data1[max_thu] - $data1[cum]<br />";
            if($data1["max_thu"]==$current) {
                $query4 = "SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_LM = '$data[ID_LM]' AND ID_MON = '$data[ID_MON]' ORDER BY ID_CUM DESC,date DESC LIMIT 1";
                $result4 = mysqli_query($db, $query4);
                $data4=mysqli_fetch_assoc($result4);
                $cumID=$data4["ID_CUM"];
                if(!check_done_options($cumID,"diemdanh-nghi",$data["ID_LM"],$data["ID_MON"])) {
                    $ngay = get_cum_date($cumID, $data["ID_LM"], $data["ID_MON"]);
                    $date_in = date_create(get_cum_date2($cumID, $data["ID_LM"], $data["ID_MON"]));

                    $string .= "Chốt cụm $data[name] ".$thu_string[$current-1].": ";
                    $query4 = "SELECT h.ID_HS,h.cmt,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$data[ID_LM]' AND h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$data[ID_LM]') ORDER BY h.cmt ASC";
                    $result4 = mysqli_query($db, $query4);
                    while ($data4 = mysqli_fetch_assoc($result4)) {
                        $date_in2 = date_create($data4["date_in"]);
                        if ($date_in2 < $date_in) {
                            $result5 = check_di_hoc($data4["ID_HS"], $cumID, $data["ID_LM"], $data["ID_MON"]);
                            if ($result5 != false) {

                            } else {
                                $string .= "$data4[cmt] - ";
                                //delete_thongbao($data4["ID_HS"], $cumID, "nghi-hoc", $data["ID_LM"]);
                                insert_diemdanh_nghi($cumID, $data4["ID_HS"], 0, $data["ID_LM"], $data["ID_MON"]);
                                //add_thong_bao_hs($data4["ID_HS"], $cumID, "Bạn đã nghỉ học Ko phép cụm học ngày $ngay", "nghi-hoc", $data["ID_LM"]);
                                add_hs_note_only($data4["ID_HS"],get_last_cum_date($cumID, $data["ID_LM"], $data["ID_MON"]),"",0,$data["ID_LM"]);
                            }
                        }
                    }
                    $string .= "<br />";
                    add_options($cumID,"diemdanh-nghi",$data["ID_LM"], $data["ID_MON"]);
                }

                $dem=0;
				$query4="SELECT o.ID_CA,o.ID_HS,o.cum,c.thu FROM ca_codinh AS o 
				INNER JOIN cahoc AS c ON c.ID_CA=o.ID_CA 
				INNER JOIN cum AS u ON u.ID_CUM=c.cum AND (u.ID_LM='$data[ID_LM]' OR u.ID_LM='0') AND u.ID_MON='$data[ID_MON]' WHERE o.cum='$data1[cum]'";
				$result4=mysqli_query($db,$query4);
				while($data4=mysqli_fetch_assoc($result4)) {
					if(!check_hs_in_ca_hientai($data4["ID_CA"],$data4["ID_HS"])) {
						add_hs_to_ca_tam($data4["ID_CA"],$data4["ID_HS"],$data4["cum"]);
						$result2=get_info_ca($data4["ID_CA"]);
						$data2=mysqli_fetch_assoc($result2);
						add_log($data4["ID_HS"],"Đã reset ca tạm, trở về ca cố định ".$thu_string[$data2["thu"]-1].", lớp $data[name], $data2[gio]","doi-ca-$data[ID_LM]");
						add_thong_bao_hs($data4["ID_HS"],0,"Đã reset ca tạm, trở về ca cố định ".$thu_string[$data2["thu"]-1].", $data2[gio], lớp $data[name]","doi-ca",$data["ID_LM"]);
						echo"Đã reset ca em $data4[ID_HS] - $data4[cum] - $data4[thu]<br />";
						$dem++;
					}
                }
				echo"Tong = $dem<br />";
                $string .= "Đã reset ca Tạm môn $data[name] ngày ".$thu_string[$current-1].", tổng $dem em!<br />";
                add_sync("Đã reset ca Tạm môn $data[name] ngày ".$thu_string[$current-1].", tổng $dem em!");
			}
		}
	}

	if($string != "") {
        $html = file_get_contents("/home/nginx/bgo.edu.vn/public_html/email.html");
        $html = str_replace("%title%", "Kết quả reset ca Tạm " . date("j") . "/" . date("m") . "/" . date("Y"), $html);
        $html = str_replace("%sub_title%", "", $html);
        $html = str_replace("%content%", "$string", $html);
        $html = str_replace("%ps%", "@2016 Bgo.edu.vn", $html);
        send_email("noreply@bgo.edu.vn", "mactavish124!@", "dinhvankiet124@gmail.com", "BGO.EDU.VN: Kết quả reset ca Tạm!", $html, true);
    }
	
	ob_end_flush();
	require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>