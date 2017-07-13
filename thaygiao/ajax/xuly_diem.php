<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 600);
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
//	if (isset($_POST["diem"]) && isset($_POST["hsID"]) && isset($_POST["buoiID"]) && isset($_POST["de"])) {
//		$diem=$_POST["diem"];
//		$hsID=$_POST["hsID"];
//		$buoiID=$_POST["buoiID"];
//		$de=$_POST["de"];
//		update_diem_hs($hsID, $buoiID, $diem, $de, $diem_string);
//		echo "true";
//	}
//
//	if (isset($_POST["note"]) && isset($_POST["hsID"]) && isset($_POST["buoiID"])) {
//		$note=$_POST["note"];
//		$hsID=$_POST["hsID"];
//		$buoiID=$_POST["buoiID"];
//		update_note_hs($hsID, $buoiID, $note, $diem_string);
//		echo "true";
//	}
//
//	if (isset($_POST["take_home"]) && isset($_POST["hsID"]) && isset($_POST["buoiID"])) {
//		$take_home=$_POST["take_home"];
//		$hsID=$_POST["hsID"];
//		$buoiID=$_POST["buoiID"];
//		update_loai_diem($hsID, $buoiID, $take_home, $diem_string);
//		echo "true";
//	}

	// Đã check
	if (isset($_POST["lmID"]) && isset($_POST["thang"]) && isset($_POST["thongbao"]) && isset($_POST["nhayde"])) {
		$lmID=$_POST["lmID"];
		$thang=$_POST["thang"];
		$thongbao=$_POST["thongbao"];
		$nhayde=$_POST["nhayde"];
		$temp=explode("-",$thang);
		$nextm=get_next_time($temp[0],$temp[1]);

        $next_CN=get_next_CN();

        clean_new_nhayde2($lmID);
		$check=check_diemtb_thang($thang,$lmID);
		
		if(is_numeric($lmID) && $lmID!=0 && is_numeric($thongbao) && is_numeric($nhayde) && $thang!="") {
		    $content=array();
		    $monID=get_mon_of_lop($lmID);
            $tien = get_muctien("thach-dau");
			$query="SELECT ID_HS,de FROM hocsinh_mon WHERE ID_LM='$lmID' AND date_in<'$nextm-01' ORDER BY ID_HS ASC";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				$diemtb=tinh_diemtb_month($data["ID_HS"], $thang, $lmID);
				if($diemtb!=NULL) {
					if($check) {
						update_diemtb_thang($data["ID_HS"], $diemtb, $data["de"], $lmID, $thang);
					} else {
						insert_diemtb_thang($data["ID_HS"], $diemtb, $data["de"], $lmID, $thang);
					}
					$how=0;
					$is_nhayde = get_nhay_de($monID);
					if($is_nhayde != 0) {
					    if($diemtb >= $is_nhayde) {
					        $how=1;
					        if($data["de"] == "Y") {
					            $new_de = "B";
                            } else if($data["de"] == "B") {
                                $new_de = "G";
                            } else {
                                $new_de = $data["de"];
                            }
                        } else if($diemtb <= 5) {
                            $how=-1;
                            if($data["de"] == "G") {
                                $new_de = "B";
                            } else if($data["de"] == "B") {
                                $new_de = "Y";
                            } else {
                                $new_de = $data["de"];
                            }
                        } else {
                            $new_de = $data["de"];
                        }
                    } else {
                        $new_de = $data["de"];
                    }
					if($data["de"]!=$new_de) {
						if($nhayde==1 && !check_done_options(date("Y-m"),"len-de-mid",$data["ID_HS"],$lmID)) {
							update_de_hs($data["ID_HS"], $new_de, $lmID);
                            $result2=get_list_thachdau($data["ID_HS"],$next_CN,$lmID);
                            while($data2=mysqli_fetch_assoc($result2)) {
                                if($data2["status"]=="accept") {
                                    cong_tien_hs($data2["ID_HS"], $tien, "Hoàn tiền do Admin hủy thách đấu (do chuyển đề) cho ngày thi " . format_dateup($data2["buoi"]), "thach-dau", "");
                                    add_thong_bao_hs($data2["ID_HS"], $data2["ID_STT"], "Admin đã hủy trận thách đấu (do chuyển đề) của bạn vào ngày thi " . format_dateup($data2["buoi"]), "thach-dau", $lmID);
                                    cong_tien_hs($data2["ID_HS2"], $tien, "Hoàn tiền do Admin hủy thách đấu (do chuyển đề) cho ngày thi " . format_dateup($data2["buoi"]), "thach-dau", "");
                                    add_thong_bao_hs($data2["ID_HS2"], $data2["ID_STT"], "Admin đã hủy trận thách đấu (do chuyển đề) của bạn vào ngày thi " . format_dateup($data2["buoi"]), "thach-dau", $lmID);
                                    $query = "DELETE FROM thachdau WHERE ID_STT='$data2[ID_STT]'";
                                    mysqli_query($db, $query);
                                } else if($data2["status"]=="pending") {
                                    cong_tien_hs($data2["ID_HS"], $tien, "Hoàn tiền do Admin hủy thách đấu (do chuyển đề) cho ngày thi " . format_dateup($data2["buoi"]), "thach-dau", "");
                                    add_thong_bao_hs($data2["ID_HS"], $data2["ID_STT"], "Admin đã hủy trận thách đấu (do chuyển đề) của bạn vào ngày thi " . format_dateup($data2["buoi"]), "thach-dau", $lmID);
                                    $query = "DELETE FROM thachdau WHERE ID_STT='$data2[ID_STT]'";
                                    mysqli_query($db, $query);
                                } else {

                                }
                            }
                            if($how==1) {
                                $content[] = "('$data[ID_HS]','Thưởng thẻ miễn phạt lên đề $new_de tháng $temp[1]/$temp[0]','the-mien-phat',now())";
                                $content[] = "('$data[ID_HS]','Thưởng thẻ miễn phạt lên đề $new_de tháng $temp[1]/$temp[0]','the-mien-phat',now())";
                                $content[] = "('$data[ID_HS]','Thưởng thẻ miễn phạt lên đề $new_de tháng $temp[1]/$temp[0]','the-mien-phat',now())";
                            }
						}
						insert_new_nhayde($data["ID_HS"], $new_de, $diemtb, $lmID);
						if($thongbao==1) {
							if($how==1) {
								add_thong_bao_hs2($data["ID_HS"],1,"Chúc mừng bạn đã lên làm đề $new_de từ tháng ".format_month($nextm),"nhay-de",$lmID);
							} else if($how==-1) {
								add_thong_bao_hs2($data["ID_HS"],0,"Rất tiếc bạn phải chuyển xuống làm đề $new_de từ tháng ".format_month($nextm),"nhay-de",$lmID);
							}
						}
					}
				}
			}
			if(count($content)>0) {
                $content = implode(",", $content);
                $query = "INSERT INTO log(ID_HS,content,type,datetime) VALUES $content";
                mysqli_query($db, $query);
            }
			echo"ok";
		} else {
			echo"none";
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>