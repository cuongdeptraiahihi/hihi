<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];

    // Đã check
    if(isset($_POST["new_date"]) && isset($_POST["lmID"]) && isset($_POST["monID"])) {
        $date=$_POST["new_date"];
        $lmID=$_POST["lmID"];
        $monID=$_POST["monID"];
        $thu=date("w", strtotime($date))+1;
        $result=get_ca_base_thu2($thu,$lmID,$monID);
        $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
        while($data=mysqli_fetch_assoc($result)) {
            if($data["link"]!=0) {
                //echo"<option value='$data[ID_CA]' data-cum='$data[cum]' data-quyen='0'>".$thu_string[$thu-1].", ca $data[gio] (Tăng cường)</option>";
            } else {
                echo"<option value='$data[ID_CA]' data-cum='$data[cum]' data-quyen='1'>".$thu_string[$thu-1].", ca $data[gio]</option>";
            }
        }
    }

    // Đã check
    if(isset($_POST["cumID"]) && isset($_POST["hsID"]) && isset($_POST["is_phep"]) && isset($_POST["lmID"]) && isset($_POST["monID"]) && isset($_POST["is_bao"])) {
        $cumID=$_POST["cumID"];
        $hsID=$_POST["hsID"];
        $is_phep=$_POST["is_phep"];
        $lmID=$_POST["lmID"];
        $monID=$_POST["monID"];
        $is_bao=$_POST["is_bao"];
        if(is_numeric($cumID) && $cumID!=0 && is_numeric($hsID) && $hsID!=0 && ($is_phep==1 || $is_phep==0)) {
            insert_diemdanh_nghi($cumID,$hsID,$is_phep,$lmID,$monID);
            delete_thongbao($hsID, $cumID, "nghi-hoc", $lmID);
            if($lmID==0 && $is_phep == 1) {
                $buoiID = get_id_buoikt(get_cum_date2($cumID, $lmID, $monID), $monID);
                $result0=get_mon_unknow($hsID, $monID);
                while($data0=mysqli_fetch_assoc($result0)) {
                    $query = "SELECT n.ID_N FROM nhom_de AS n
                    INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
                    INNER JOIN loai_de AS l ON l.ID_D=d.loai AND l.name='$data0[de]'
                    WHERE n.object='$buoiID' AND n.ID_LM='$data0[ID_LM]'";
                    $result = mysqli_query($db, $query);
                    if (mysqli_num_rows($result) != 0) {
                        $data = mysqli_fetch_assoc($result);
                        $nID = $data["ID_N"];
                        $query = "INSERT INTO hoc_sinh_special(ID_HS,ID_N) SELECT * FROM (SELECT '$hsID' AS hsID,'$nID' AS nID) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_special WHERE ID_HS='$hsID' AND ID_N='$nID') LIMIT 1";
                        mysqli_query($db, $query);
                    }
                }
            }
            if($is_bao == 1) {
                if ($is_phep == 1) {
                    add_thong_bao_hs($hsID, $cumID, "Bạn đã nghỉ học Có phép cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $lmID);
                } else {
                    add_thong_bao_hs($hsID, $cumID, "Bạn đã nghỉ học Ko phép cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $lmID);
                }
            }
            echo"ok";
        } else {
            echo"none";
        }
    }

    // Đã check
    if(isset($_POST["cumID0"]) && isset($_POST["hsID0"]) && isset($_POST["lmID"]) && isset($_POST["monID"])) {
        $cumID=$_POST["cumID0"];
        $hsID=$_POST["hsID0"];
        $lmID=$_POST["lmID"];
        $monID=$_POST["monID"];
        if(is_numeric($cumID) && $cumID!=0 && is_numeric($hsID) && $hsID!=0) {
            delete_thongbao($hsID,$cumID,"nghi-hoc",$lmID);
            del_diemdanh_nghi($cumID,$hsID,$lmID,$monID);
            echo"ok";
        } else {
            echo"none";
        }
    }

    // Đã check
	if (isset($_POST["ngay"]) && isset($_POST["monID"]) && isset($_POST["lmID"]) && isset($_POST["caID"]) && isset($_POST["cum0"]) && isset($_POST["cdID"]) && isset($_POST["hsID"]) && isset($_POST["is_hoc"]) && isset($_POST["is_tinh"]) && isset($_POST["is_kt"]) && isset($_POST["caCheck"])) {
		$ngay=$_POST["ngay"];
		if(stripos($ngay,"/")===false) {
		} else {
			$ngay=format_date_o($ngay);
		}
		$monID=$_POST["monID"];
		$lmID=$_POST["lmID"];
		$caID=$_POST["caID"];
		$cum=$_POST["cum0"];
		$cdID=$_POST["cdID"];
		$hsID=$_POST["hsID"];
		$is_tinh=$_POST["is_tinh"];
		$is_hoc=$_POST["is_hoc"];
		$is_kt=$_POST["is_kt"];
		$caCheck=$_POST["caCheck"];
		$temp=check_exited_buoi($cdID, $caID, $ngay, $lmID, $monID);
		if(count($temp)==0) {
			$temp=add_diemdanh_buoi($cdID, $caID, $cum, $ngay,$lmID,$monID);
			diemdanh_nghi_dai($temp[1],$ngay,$lmID,$monID);
            $query="UPDATE diemdanh_nghi SET ID_CUM='$temp[1]' WHERE ngay='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
            mysqli_query($db,$query);
		} 
		$ddID=$temp[0];
		$cumID=$temp[1];
        $is_ok=true;
		if(check_exited_diemdanh($cumID, $hsID, $lmID, $monID)) {
			echo"Học sinh này đã được điểm danh!";
		} else {
			if($lmID==0 && isset($_POST["is_code3"])) {
			    $oID="none";
//			    $lmID=get_mon_first_hs($hsID,$monID);
                $code_arr="";
                $temp=explode(",",$_POST["is_code3"]);
                for($i=0;$i<count($temp);$i++) {
                    $code_arr.=",'".md5($temp[$i])."'";
                }
                $code_arr=substr($code_arr,1);
                $query="SELECT ID_O,note FROM options WHERE content IN ($code_arr) AND type='tro-giang-code'";
                $result=mysqli_query($db,$query);
                if(mysqli_num_rows($result)!=0) {
                    $oID="";
                    while($data = mysqli_fetch_assoc($result)) {
                        $oID .= ",$data[ID_O],";
                    }
                }
                if($_POST["is_du3"]==0) {
                    if($oID=="none") {
                        $is_ok=false;
                    } else {
                        add_info_buoikt($_POST["buoiID3"],$hsID,$_POST["nhap3"],$_POST["giay3"],$_POST["is_de3"],$_POST["is_du3"],$_POST["is_huy3"],$oID,$_POST["made3"],$monID);
//                        $de=get_de_hs($hsID,$lmID);
//                        insert_diem_hs2($hsID,$_POST["buoiID3"],0,$de,3,10,$lmID);
//                        $result=get_lido(10);
//                        $data=mysqli_fetch_assoc($result);
//                        $tb=$data["name"];
//                        add_thong_bao_hs($hsID,$_POST["buoiID3"],"Điểm thi ngày ".format_dateup($ngay)." của bạn là 0 điểm ($de). $tb, ngay tại buổi kiểm tra!","diem-thi",$lmID);
//                        if(get_is_phat($monID)) {
//                            delete_phat_thuong($hsID, "kiemtra_" . $lmID, $_POST["buoiID3"]);
//                            if (!check_binh_voi($hsID, $_POST["buoiID3"], $lmID)) {
//                                get_phat_diemkt($hsID, 0, $de, 3, 10, $lmID, get_lop_mon_name($lmID), $_POST["buoiID3"], $ngay, true);
//                            }
//                        }
                    }
                } else if($_POST["is_huy3"]==1) {
                    if($oID=="none") {
                        $is_ok=false;
                    } else {
                        add_info_buoikt($_POST["buoiID3"],$hsID,$_POST["nhap3"],$_POST["giay3"],$_POST["is_de3"],$_POST["is_du3"],$_POST["is_huy3"],$oID,$_POST["made3"],$monID);
//                        $de=get_de_hs($hsID,$monID);
//                        insert_diem_hs2($hsID,$_POST["buoiID3"],0,$de,3,1,$lmID);
//                        $result=get_lido(1);
//                        $data=mysqli_fetch_assoc($result);
//                        $tb=$data["name"];
//                        add_thong_bao_hs($hsID,$_POST["buoiID3"],"Điểm thi ngày ".format_dateup($ngay)." của bạn là 0 điểm ($de). $tb, ngay tại buổi kiểm tra!","diem-thi",$lmID);
//                        if(get_is_phat($monID)) {
//                            delete_phat_thuong($hsID, "kiemtra_" . $lmID, $_POST["buoiID3"]);
//                            if (!check_binh_voi($hsID, $_POST["buoiID3"], $lmID)) {
//                                get_phat_diemkt($hsID, 0, $de, 3, 1, $lmID, get_lop_mon_name($lmID), $_POST["buoiID3"], $ngay, true);
//                            }
//                        }
                    }
                } else {
                    add_info_buoikt($_POST["buoiID3"],$hsID,$_POST["nhap3"],$_POST["giay3"],$_POST["is_de3"],$_POST["is_du3"],$_POST["is_huy3"],$oID,$_POST["made3"],$monID);
                }
			}
			if($is_ok) {
                insert_diemdanh($ddID, $hsID, $is_tinh, $is_hoc, $is_kt, $caCheck);
                del_diemdanh_nghi($cumID, $hsID, $lmID, $monID);
                delete_thongbao($hsID, $cumID, "nghi-hoc", $lmID);
                if ($lmID == 0) {
                    $content = "";
                } else {
                    if ($is_kt == 1) {
                        if ($is_hoc == 1 && $is_tinh == 1) {
                            $content = "Có học và tính đúng";
                        } else if ($is_hoc == 1 && $is_tinh == 0) {
                            $content = "Có học và tính sai";
                        } else if ($is_hoc == 0 && $is_tinh == 0) {
                            $content = "Không học bài";
                        } else {
                            $content = "Dữ liệu lỗi";
                        }
                    } else {
                        $content = "Không kiểm tra đầu giờ!";
                    }
                }
                if($caCheck==0 && $monID==1 && $lmID==0) {
                    if(!check_binh_voi2($hsID, $lmID)) {
                        $content .= ". Bạn đi học sai ca và bị trừ 20k";
                        tru_tien_hs($hsID, 20000, "Trừ 20k tiền đi học sai ca ngày " . date("H:i d/m/y"), "sai-ca-$lmID", $ddID);
                    }
                }
                add_thong_bao_hs($hsID, $cumID, "Bạn đã được điểm danh cụm học ngày " . get_cum_date($cumID, $lmID, $monID) . ". $content", "nghi-hoc", $lmID);
                echo "ok";
            } else {
                echo "Mã trợ giảng không tồn tại!";
            }
		}
	}

	// Đã check
	if (isset($_POST["ngay"]) && isset($_POST["hsID3"]) && isset($_POST["is_hoc3"]) && isset($_POST["is_tinh3"]) && isset($_POST["is_kt3"]) && isset($_POST["sttID3"]) && isset($_POST["cum0"]) && isset($_POST["monID"]) && isset($_POST["lmID"])) {
        $ngay=$_POST["ngay"];
        if(stripos($ngay,"/")===false) {
        } else {
            $ngay=format_date_o($ngay);
        }
	    $hsID=$_POST["hsID3"];
		$is_tinh=$_POST["is_tinh3"];
		$is_hoc=$_POST["is_hoc3"];
		$is_kt=$_POST["is_kt3"];
		$sttID=$_POST["sttID3"];
        $cum=$_POST["cum0"];
		$lmID=$_POST["lmID"];
        $monID=$_POST["monID"];
		edit_diemdanh($sttID, $hsID, $is_hoc, $is_tinh, $is_kt);
        $is_ok=true;
		if($lmID==0 && isset($_POST["is_code3"])) {
            $oID="none";
//            $lmID=get_mon_first_hs($hsID,$monID);
            $code_arr="";
            $temp=explode(",",$_POST["is_code3"]);
            for($i=0;$i<count($temp);$i++) {
                $code_arr.=",'".md5($temp[$i])."'";
            }
            $code_arr=substr($code_arr,1);
            $query="SELECT ID_O,note FROM options WHERE content IN ($code_arr) AND type='tro-giang-code'";
            $result=mysqli_query($db,$query);
            if(mysqli_num_rows($result)!=0) {
                $oID="";
                while($data = mysqli_fetch_assoc($result)) {
                    $oID .= ",$data[ID_O],";
                }
            }
			if($_POST["is_du3"]==0) {
                if($oID=="none") {
                    $is_ok=false;
                } else {
                    add_info_buoikt($_POST["buoiID3"],$hsID,$_POST["nhap3"],$_POST["giay3"],$_POST["is_de3"],$_POST["is_du3"],$_POST["is_huy3"],$oID,$_POST["made3"],$monID);
//                    $de=get_de_hs($hsID,$lmID);
//                    if(check_hs_diem($hsID,$_POST["buoiID3"],$lmID)) {
//                        update_diem_hs2($hsID,$_POST["buoiID3"],0,$de,3,10,$lmID);
//                    }
                }
			} else if($_POST["is_huy3"]==1) {
                if($oID=="none") {
                    $is_ok=false;
                } else {
                    add_info_buoikt($_POST["buoiID3"],$hsID,$_POST["nhap3"],$_POST["giay3"],$_POST["is_de3"],$_POST["is_du3"],$_POST["is_huy3"],$oID,$_POST["made3"],$monID);
//                    $de = get_de_hs($hsID, $lmID);
//                    if (check_hs_diem($hsID, $_POST["buoiID3"], $lmID)) {
//                        update_diem_hs2($hsID, $_POST["buoiID3"], 0, $de, 3, 1, $lmID);
//                    }
                }
			} else {
                add_info_buoikt($_POST["buoiID3"],$hsID,$_POST["nhap3"],$_POST["giay3"],$_POST["is_de3"],$_POST["is_du3"],$_POST["is_huy3"],$oID,$_POST["made3"],$monID);
            }
		}
        if($is_ok) {
            $cumID = get_cum_buoi(0, $ngay, $lmID, $monID, $cum);
            del_diemdanh_nghi($cumID, $hsID, $lmID, $monID);
            delete_thongbao($hsID, $cumID, "nghi-hoc", $lmID);
            if ($lmID == 0) {
                $content = "";
            } else {
                if ($is_kt == 1) {
                    if ($is_hoc == 1 && $is_tinh == 1) {
                        $content = "Có học và tính đúng";
                    } else if ($is_hoc == 1 && $is_tinh == 0) {
                        $content = "Có học và tính sai";
                    } else if ($is_hoc == 0 && $is_tinh == 0) {
                        $content = "Không học bài";
                    } else {
                        $content = "Dữ liệu lỗi";
                    }
                } else {
                    $content = "Không kiểm tra đầu giờ!";
                }
            }
            add_thong_bao_hs($hsID, $cumID, "Bạn đã được điểm danh cụm học ngày " . get_cum_date($cumID, $lmID, $monID) . ". $content", "nghi-hoc", $lmID);
            echo"ok";
        } else {
            echo "Mã trợ giảng không tồn tại! $code_arr";
        }
	}

	// Đã check
	if(isset($_POST["ngay"]) && isset($_POST["lmID"]) && isset($_POST["mon"]) && isset($_POST["ca"]) && isset($_POST["cd"])) {
		$ngay=$_POST["ngay"];
		if(strpos($ngay,"/")===false) {
		} else {
			$ngay=format_date_o($ngay);
		}
		$mon=$_POST["mon"];
		$lmID=$_POST["lmID"];
		$ca=$_POST["ca"];
		$cd=$_POST["cd"];
		
		if($lmID==0) {
			$buoiID=get_id_buoikt($ngay,$mon);
		} else {
			$buoiID=0;
		}
		
		//$total=get_num_hs_ca_hientai($ca,$ca_hientai_string);
		
		$query="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE ID_CD='$cd' AND ID_CA='$ca' AND date='$ngay' AND ID_LM='$lmID' AND ID_MON='$mon'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
        $query2="SELECT ID_STT FROM diemdanh_buoi WHERE ID_CUM='$data[ID_CUM]' AND ID_CD='$cd' AND ID_LM='$lmID' AND ID_MON='$mon'";
        $result2=mysqli_query($db,$query2);
        $idstt=array();
        while($data2=mysqli_fetch_assoc($result2)) {
            $idstt[]="'$data2[ID_STT]'";
        }
        $query2=array();
        if($lmID!=0) {
            $query2[] = "SELECT c.ID_HS,h.cmt,h.vantay,h.fullname,h.birth,d.ID_STT,d.ID_DD,d.ca_check,d.is_hoc,d.is_tinh,d.is_kt,n.ID_STT AS ID_N,n.is_phep,m.ID_LM FROM ca_hientai AS c 
		INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS 
		INNER JOIN hocsinh_mon AS m ON m.ID_HS=c.ID_HS AND m.ID_LM='$lmID' AND m.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
		LEFT JOIN diemdanh AS d ON d.ID_HS=c.ID_HS AND d.ID_DD IN (".implode(",", $idstt).") 
		LEFT JOIN diemdanh_nghi AS n ON n.ID_HS=c.ID_HS AND n.ID_CUM='$data[ID_CUM]' AND n.ID_LM='$lmID' AND n.ID_MON='$mon' 
		WHERE c.ID_CA='$ca' 		
		UNION SELECT d.ID_HS,h.cmt,h.vantay,h.fullname,h.birth,d.ID_STT,d.ID_DD,d.ca_check,d.is_hoc,d.is_tinh,d.is_kt,n.ID_STT AS ID_N,n.is_phep,m.ID_LM FROM diemdanh AS d 
		INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS 
		INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID'
		LEFT JOIN diemdanh_nghi AS n ON n.ID_HS=d.ID_HS AND n.ID_CUM='$data[ID_CUM]' AND n.ID_LM='$lmID' AND n.ID_MON='$mon' 
		WHERE d.ID_DD='$data[ID_STT]' ORDER BY cmt ASC";
            $query2[] = "SELECT d.ID_HS,h.cmt,h.vantay,h.fullname,h.birth,d.ID_STT,d.ID_DD,d.ca_check,d.is_hoc,d.is_tinh,d.is_kt,m.ID_LM FROM diemdanh AS d 
		INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS 
		INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM IN (SELECT ID_LM FROM lop_mon WHERE ID_MON='$monID' AND ID_LM!='$lmID') AND m.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM=m.ID_LM)
		WHERE d.ID_DD='$data[ID_STT]' ORDER BY cmt ASC";
        } else {
            $query2[] = "SELECT c.ID_HS,h.cmt,h.vantay,h.fullname,h.birth,d.ID_STT,d.ID_DD,d.ca_check,d.is_hoc,d.is_tinh,d.is_kt,n.ID_STT AS ID_N,n.is_phep FROM ca_hientai AS c 
		INNER JOIN hocsinh AS h ON h.ID_HS=c.ID_HS 
		LEFT JOIN diemdanh AS d ON d.ID_HS=c.ID_HS AND d.ID_DD IN (".implode(",", $idstt).") 
		LEFT JOIN diemdanh_nghi AS n ON n.ID_HS=c.ID_HS AND n.ID_CUM='$data[ID_CUM]' AND n.ID_MON='$mon' 
		WHERE c.ID_CA='$ca' 
		UNION SELECT d.ID_HS,h.cmt,h.vantay,h.fullname,h.birth,d.ID_STT,d.ID_DD,d.ca_check,d.is_hoc,d.is_tinh,d.is_kt,n.ID_STT AS ID_N,n.is_phep FROM diemdanh AS d 
		INNER JOIN hocsinh AS h ON h.ID_HS=d.ID_HS 
		LEFT JOIN diemdanh_nghi AS n ON n.ID_HS=d.ID_HS AND n.ID_CUM='$data[ID_CUM]' AND n.ID_MON='$mon' 
		WHERE d.ID_DD='$data[ID_STT]' ORDER BY cmt ASC";
        }
        $stt = $stt2 = 0;
        for($i = 0; $i < count($query2); $i++) {
            $result2 = mysqli_query($db, $query2[$i]);
            $now = explode("-", $ngay);
//            echo "<tr>
//                <tr><td colspan='7'><span>Để nhập lý do học sinh nghỉ, bạn cần phải điểm danh ít nhất 1 học sinh đi học</span></td></tr>
//            </tr>";
            while ($data2 = mysqli_fetch_assoc($result2)) {
                $style = "";
                if (isset($data2["ID_STT"])) {
                    if ($data2["ca_check"] == 1) {
                        echo "<tr class='tr-ok tr-dug' style='$style'>";
                    } else {
                        echo "<tr class='tr-ok' style='$style'>";
                    }
                    if ($data2["ID_DD"] == $data["ID_STT"]) {
                        echo "<td class='hidden'><span>" . ($stt2 + 1) . "</span></td>";
                    } else {
                        echo "<td class='hidden'><span></span></td>";
                    }
                    if (stripos($data2["birth"], "-$now[1]-") === false) {
                        $cake = "";
                    } else {
                        $cake = "(<i class='fa fa-birthday-cake' style='font-size:18px;'></i>)";
                    }
                    if ($data2["ID_LM"] != $lmID) {
                        $style2 = "background:cyan;";
                    } else {
                        $style2 = "";
                    }
                    echo "<td class='hidden'><span>$data2[fullname] ($data2[vantay]) $cake</span></td>
					<td style='$style2'><span>$data2[cmt]</span></td>";
                    if ($data2["is_kt"] == 1) {
                        echo "<td><span>";
                        if ($data2["is_hoc"] == 1) {
                            echo "Học bài";
                        } else {
                            echo "Ko học bài";
                        }
                        echo "</span></td>
						<td><span>";
                        if ($data2["is_tinh"] == 1) {
                            echo "Tính đúng";
                        } else {
                            echo "Tính sai";
                        }
                        echo "</span></td>";
                    } else {
                        if ($lmID == 0) {
                            $query3 = "SELECT * FROM info_buoikt WHERE ID_BUOI='$buoiID' AND ID_HS='$data2[ID_HS]' AND ID_MON='$mon'";
                            $result3 = mysqli_query($db, $query3);
                            $data3 = mysqli_fetch_assoc($result3);
                            $huy_msg = "";
                            if ($data3["is_du"] == 0 && isset($data3["is_du"])) {
                                $huy_msg .= ", Nộp thiếu";
                            }
                            if ($data3["is_huy"] == 1 && isset($data3["is_huy"])) {
                                $huy_msg .= ", Trao đổi bài";
                            }
                            echo "<td><span>$data3[nhap] / $data3[giay] ($data3[made])</span></td>
							<td><span>" . substr($huy_msg, 2) . "</span></td>";
                        } else {
                            echo "<td colspan='2'></span>Không kiểm tra đầu giờ</span></td>";
                        }
                    }
                    echo "<td>";
                    if ($data2["ID_DD"] == $data["ID_STT"]) {
                        if ($data2["ca_check"] == 1) {
                            echo "<p style='background:#3E606F;' class='ca-check'>Đúng ca</p>";
                        } else {
                            echo "<p style='background:red' class='ca-check'>Sai ca</p>";
                        }
                    } else {
                        echo "<p>Đã điểm danh ở ca khác</p>";
                        $stt--;
                        $stt2--;
                    }
                    echo "</td>
					<td><input type='submit' class='delete submit' data-sttID='$data2[ID_STT]' value='Xóa' /></td>
				</tr>";
                    $stt++;
                } else {
                    if (isset($data2["ID_N"])) {
                        echo "<tr style='$style;' class='tr-dug'>";
                    } else {
                        echo "<tr style='background:#ffffa5;$style;' class='tr-dug'>";
                    }
                    if (stripos($data2["birth"], "-$now[1]-") === false) {
                        $cake = "";
                    } else {
                        $cake = "(<i class='fa fa-birthday-cake' style='font-size:18px;'></i>)";
                    }
                    echo "<td class='hidden'><span>" . ($stt2 + 1) . "</span></td>
					<td class='hidden'><span>$data2[fullname] ($data2[vantay]) $cake</span></td>
					<td><span>$data2[cmt]</span></td>";
                    if (isset($data2["ID_N"]) && isset($data2["is_phep"])) {
                        echo "<td colspan='3'>
							<select class='input' style='height:auto;'>
								<option value='0' ";
                        if ($data2["is_phep"] == 0) {
                            echo "selected='selected'";
                        }
                        echo ">Không phép</option>
								<option value='1' ";
                        if ($data2["is_phep"] == 1) {
                            echo "selected='selected'";
                        }
                        echo ">Có phép</option>
							</select>
						</td>
						<td>";
                        if (isset($data["ID_STT"])) {
                            echo "<input type='submit' class='submit del_only_nghi' data-hsID='$data2[ID_HS]' data-cumID='$data[ID_CUM]' value='Xóa' />
							<input type='submit' class='submit add_only_nghi' data-hsID='$data2[ID_HS]' data-cumID='$data[ID_CUM]' value='Sửa' />";
                        }
                        echo "</td>";
                    } else {
                        echo "<td colspan='3'>
							<select class='input' style='height:auto;'>
								<option value='0' selected='selected'>Không phép</option>
								<option value='1'>Có phép</option>
							</select>
						</td><td>";
                        if (isset($data["ID_STT"])) {
                            echo "<input type='submit' class='submit add_only_nghi' data-hsID='$data2[ID_HS]' data-cumID='$data[ID_CUM]' value='Nhập' />";
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                $stt2++;
            }
        }
		if($stt==0) {
			echo"<tr><td colspan='7'><span>Chưa em nào được điểm danh</span></td></tr>";
		} else {
			echo"<tr><td colspan='7'><span id='so-luong'>Có $stt2 học sinh</span></td></tr>";
		}
		if($stt2==$stt) {
			echo"<input type='hidden' value='$stt / $stt2' data-du='1' id='status-in' />";
		} else {
			echo"<input type='hidden' value='$stt / $stt2' data-du='0' id='status-in' />";
		}
	}

	// Đã check
	if(isset($_POST["sttIDx"])) {
		$sttID=$_POST["sttIDx"];
        $result=get_diemdanh_detail($sttID);
        $data=mysqli_fetch_assoc($result);
        delete_phat_thuong($data["ID_HS"],"sai-ca-$data[ID_LM]",$data["ID_DD"]);
        delete_thongbao($data["ID_HS"],$data["ID_CUM"],"nghi-hoc",$data["ID_LM"]);
		delete_diemdanh($sttID);
	}

	// Đã check
	if(isset($_POST["hsID0"]) && isset($_POST["cum"]) && isset($_POST["ngay2"]) && isset($_POST["monID"]) && isset($_POST["lmID"]) && isset($_POST["ca2"]) && isset($_POST["cd2"]) && isset($_POST["is_quyen"])) {
		$hsID=$_POST["hsID0"];
		$cum=$_POST["cum"];
		$ngay=$_POST["ngay2"];
		if(strpos($ngay,"/")!=false) {
			$ngay=format_date_o($ngay);
		}
		$monID=$_POST["monID"];
		$lmID=$_POST["lmID"];
		$ca=$_POST["ca2"];
		$cd=$_POST["cd2"];
		$is_quyen=$_POST["is_quyen"];
		$temp=check_exited_buoi($cd, $ca, $ngay, $lmID, $monID);
		if(count($temp)==2) {
			$ddID=$temp[0];
			$cumID=$temp[1];
		} else {
		    $ddID=$cumID=0;
        }
		// TEMP
		//$has_hientai=1;
		//$ca_in=$cathu_in=$cagio_in=2;
		$check_quyen=true;
		$has_hientai=0;
		$ca_in=$cathu_in=$cagio_in=NULL;
//		if(check_unlock_ca_hs($hsID,$ca) || $is_quyen==0) {
			$query="SELECT h.ID_CA,c.thu,g.gio FROM ca_hientai AS h 
			INNER JOIN cahoc AS c ON c.ID_CA=h.ID_CA 
			INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID'
			WHERE h.ID_HS='$hsID' AND h.cum='$cum'";
			$result=mysqli_query($db,$query);
			if(mysqli_num_rows($result)!=0) {
				$data=mysqli_fetch_assoc($result);
				$ca_in=$data["ID_CA"];
				$cathu_in=$data["thu"];
				$cagio_in=$data["gio"];
				$has_hientai=1;
			}
//		} else {
//			$check_quyen=false;
//		}
		if($check_quyen) {
			if($ca_in && $cathu_in && $cagio_in && $has_hientai==1) {
			    if($lmID!=0) {
                    $result3=get_hs_short_detail($hsID,$lmID);
                } else {
                    $result3=get_hs_short_detail($hsID,get_mon_first_hs($hsID,$monID));
                }
				$data3=mysqli_fetch_assoc($result3);
				$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
				$hientai_string=$thu_string[$cathu_in-1].", ca $cagio_in";
				//$hientai_string="";
                $special_note=get_hs_special_note($hsID);
                if(!check_done_options($hsID,"sinh-nhat",0,0)) {
                    $need_birth=1;
                } else {
                    $need_birth=0;
                }
				if(count($temp)==0) {
					echo json_encode(
						array(
							"caID_send" => $ca_in,
							"fullname" => $data3["fullname"],
                            "van" => $data3["vantay"],
							"birth" => format_dateup($data3["birth"]),
                            "de" => $data3["de"],
                            "sdt" => $data3["sdt"],
                            "sdt_bo" => $data3["sdt_bo"],
                            "sdt_me" => $data3["sdt_me"],
                            "lichsu_nghi" => $need_birth,
							"ca_hientai" => $hientai_string,
							"ca_check" => "none",
							"is_hoc" => "none",
							"is_tinh" => "none",
							"is_kt" => "1",
                            "special_note" => $special_note,
							"action" => "new"
						)
					);
				} else {
					if(check_exited_diemdanh2($ddID, $hsID)) {
						$query0="SELECT * FROM diemdanh WHERE ID_DD='$ddID' AND ID_HS='$hsID'";
						$result0=mysqli_query($db,$query0);
						$data0=mysqli_fetch_assoc($result0);
						echo json_encode(
							array(
								"caID_send" => $data0["ID_STT"],
								"fullname" => $data3["fullname"],
                                "van" => $data3["vantay"],
								"birth" => format_dateup($data3["birth"]),
                                "de" => $data3["de"],
                                "sdt" => $data3["sdt"],
                                "sdt_bo" => $data3["sdt_bo"],
                                "sdt_me" => $data3["sdt_me"],
                                "lichsu_nghi" => $need_birth,
								"ca_hientai" => $hientai_string,
								"ca_check" => $data0["ca_check"],
								"is_hoc" => $data0["is_hoc"],
								"is_tinh" => $data0["is_tinh"],
								"is_kt" => $data0["is_kt"],
                                "special_note" => $special_note,
								"action" => "edit"
							)
						);
					} else {
						echo json_encode(
							array(
								"caID_send" => $ca_in,
								"fullname" => $data3["fullname"],
                                "van" => $data3["vantay"],
								"birth" => format_dateup($data3["birth"]),
                                "de" => $data3["de"],
                                "sdt" => $data3["sdt"],
                                "sdt_bo" => $data3["sdt_bo"],
                                "sdt_me" => $data3["sdt_me"],
                                "lichsu_nghi" => $need_birth,
								"ca_hientai" => $hientai_string,
								"ca_check" => "none",
								"is_hoc" => "none",
								"is_tinh" => "none",
								"is_kt" => "1",
                                "special_note" => $special_note,
								"action" => "new"
							)
						);
					}
				}
			} else {
				echo"none";
			}
		} else {
			echo"quyen";
		}
		
	}
	
	if(isset($_POST["data"]) && isset($_POST["is_bao"])) {
		$data=$_POST["data"];
        $is_bao=$_POST["is_bao"];
		$data=json_decode($data, true);
		$n=count($data)-1;
		$cumID=$data[$n]["cumID"];
		$lmID=$data[$n]["lmID"];
        $monID=$data[$n]["monID"];
		if(is_numeric($cumID) && is_numeric($monID) && is_numeric($lmID) && $cumID!=0 && $monID!=0 && ($is_bao==0 || $is_bao==1)) {
			for($i=0;$i<$n;$i++) {
				if(check_di_hoc($data[$i]["hsID"],$cumID,$lmID,$monID)==false) {
					insert_diemdanh_nghi($cumID,$data[$i]["hsID"],$data[$i]["is_phep"],$lmID,$monID);
					if($is_bao==1) {
					    delete_thongbao($data[$i]["hsID"],$cumID,"nghi-hoc",$lmID);
                        if ($data[$i]["is_phep"] == 1) {
                            add_thong_bao_hs($data[$i]["hsID"], $cumID, "Bạn đã nghỉ học Có phép cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $lmID);
                        } else {
                            add_thong_bao_hs($data[$i]["hsID"], $cumID, "Bạn đã nghỉ học Ko phép cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $lmID);
                        }
                    }
				}
			}
			add_options($cumID,"diemdanh-nghi",$lmID,$monID);
			echo"ok";
		} else {
			echo"none";
		}
	}

	// Đã check
	if(isset($_POST["hsID6"]) && isset($_POST["buoiID6"]) && isset($_POST["monID6"])) {
		$hsID=$_POST["hsID6"];
		$buoiID=$_POST["buoiID6"];
        $monID=$_POST["monID6"];
		$query="SELECT * FROM info_buoikt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_MON='$monID'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
            $is_du=$data["is_du"];
            $is_huy=$data["is_huy"];
			echo"<tr class='info-buoikt'>
				<td class='hidden'><span>Mã đề</span></td>
				<td><input type='text' class='input' value='$data[made]' id='hs-made' placeholder='Nhập mã đề' /></td>
			</tr>
            <tr class='info-buoikt'>
				<td class='hidden'><span>Nháp</span></td>
				<td><input type='number' min='1' step='1' style='width:15%;' value='$data[nhap]' class='input' id='hs-nhap' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Giấy thi</span></td>
				<td><input type='number' min='1' step='1' style='width:15%;' value='$data[giay]' class='input' id='hs-giay' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Nộp đề?</span></td>
				<td><input type='checkbox' class='check' ";if($data["is_de"]==1){echo"checked='checked'";}echo" id='hs-isde' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Nộp đủ giấy?</span></td>
				<td><input type='checkbox' class='check' ";if($is_du==1){echo"checked='checked'";}echo" id='is-du' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Trao đổi bài?</span></td>
				<td><input type='checkbox' class='check' ";if($is_huy==1){echo"checked='checked'";}echo" id='is-huy' /></td>
			</tr>";
            if($is_huy==1 || $is_du==0) {
                $oID="";
                $temp=explode(",",$data["note"]);
                for($i=0;$i<count($temp);$i++) {
                    if(is_numeric($temp[$i])) {
                        $oID.=",'$temp[$i]'";
                    }
                }
                echo"<tr class='info-buoikt'>
                    <td class='hidden'><span>";
                        $query2="SELECT note FROM options WHERE ID_O IN (".substr($oID,1).")";
                        $result2=mysqli_query($db,$query2);
                        while($data2=mysqli_fetch_assoc($result2)) {
                            echo"<strong style='margin: 0 2.5px 0 2.5px'>$data2[note]</strong>";
                        }
                    echo"hủy</span></td>
				    <td><input type='password' id='code-huy' class='input' placeholder='Mã trợ giảng thay thế' /></td>
                </tr>";
            } else {
                echo"<tr class='info-buoikt'>
                    <td class='hidden'><span>Mã trợ giảng</span></td>
				    <td><input type='password' id='code-huy' class='input' placeholder='(ngăn cách dấu phẩy)' /></td>
                </tr>";
            }
			/*<tr class='info-buoikt'>
				<td style='text-align:center'><input type='submit' class='submit' id='is-du' data-du='$data[is_du]' ";if($data["is_du"]==1){echo"value='Nộp đủ giấy' style='background:blue;'";}else{echo"value='Ko nộp đủ giấy' style='background:red;'";}echo" /></td>
				<td style='text-align:center'><input type='submit' class='submit' id='is-huy' data-huy='$data[is_huy]' ";if($data["is_huy"]==1){echo"value='Bị hủy trao đổi' style='background:red;'";}else{echo"value='Ko trao đổi' style='background:blue;'";}echo" /></td>
			</tr>";*/
		} else {
			echo"<tr class='info-buoikt'>
				<td class='hidden'><span>Mã đề</span></td>
				<td><input type='text' class='input' id='hs-made' placeholder='Nhập mã đề' /></td>
			</tr>
            <tr class='info-buoikt'>
				<td class='hidden'><span>Nháp</span></td>
				<td><input type='number' min='1' step='1' style='width:15%;' value='1' class='input' id='hs-nhap' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Giấy thi</span></td>
				<td><input type='number' min='1' step='1' style='width:15%;' value='2' class='input' id='hs-giay' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Nộp đề?</span></td>
				<td><input type='checkbox' class='check' checked='checked' id='hs-isde' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Nộp đủ giấy?</span></td>
				<td><input type='checkbox' class='check' checked='checked' id='is-du' /></td>
			</tr>
			<tr class='info-buoikt'>
				<td class='hidden'><span>Trao đổi bài?</span></td>
				<td><input type='checkbox' class='check' id='is-huy' /></td>
			</tr>
			<tr class='info-buoikt'>
			    <td class='hidden'><span>Mã trợ giảng</span></td>
			    <td><input type='password' id='code-huy' class='input' placeholder='(ngăn cách dấu phẩy)' /></td>
            </tr>";
		}
	}

	if(isset($_POST["cumID_chot"]) && isset($_POST["lmID_chot"]) && isset($_POST["code"])) {
	    $cumID=$_POST["cumID_chot"];
        $lmID=$_POST["lmID_chot"];
        $code=$_POST["code"];
        $monID=get_mon_of_lop($lmID);
        if($lmID==0) {$monID=$_SESSION["mon"];}
        if(!check_done_options($cumID,"diemdanh-nghi",$lmID,$monID) && check_done_options($code,"super-admin-code","","")) {
            $ngay = get_cum_date($cumID, $lmID, $monID);
            $date_in = date_create(get_cum_date2($cumID, $lmID, $monID));
            $last_date = get_last_cum_date($cumID, $lmID, $monID);

            $string="";
            if($lmID!=0) {
                $query4 = "SELECT h.ID_HS,h.cmt,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') AND h.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE start<='$last_date' AND ((end>='$last_date' AND loai='0') OR (end='0000-00-00' AND loai='1'))) ORDER BY h.cmt ASC";
            } else {
                $me="";
                $result2=get_all_lop_mon2($monID);
                while($data2=mysqli_fetch_assoc($result2)) {
                    $me.=",'$data2[ID_LM]'";
                }
                $query4 = "SELECT h.ID_HS,h.cmt,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM IN (".substr($me,1).") WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM IN (".substr($me,1).")) AND h.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE start<='$last_date' AND ((end>='$last_date' AND loai='0') OR (end='0000-00-00' AND loai='1'))) ORDER BY h.cmt ASC";
            }
            $result4 = mysqli_query($db, $query4);
            while ($data4 = mysqli_fetch_assoc($result4)) {
                $date_in2 = date_create($data4["date_in"]);
                if ($date_in2 < $date_in) {
                    $result5 = check_di_hoc($data4["ID_HS"], $cumID, $lmID, $monID);
                    if ($result5 != false) {

                    } else {
                        $string.="$data4[cmt] - ";
//                        delete_thongbao($data4["ID_HS"], $cumID, "nghi-hoc", $lmID);
                        insert_diemdanh_nghi2($cumID, $data4["ID_HS"], 0, $lmID, $monID);
//                        add_thong_bao_hs($data4["ID_HS"], $cumID, "Bạn đã nghỉ học Ko phép cụm học ngày $ngay", "nghi-hoc", $lmID);
                        add_hs_note_only($data4["ID_HS"],$last_date,"",0);
                    }
                }
            }
            add_options($cumID,"diemdanh-nghi",$lmID,$monID);
            echo $string;
        } else {
            echo"none";
        }
    }

    if(isset($_POST["cumID_out"]) && isset($_POST["hsID_out"]) && isset($_POST["lmID_out"]) && isset($_POST["monID_out"])) {
        $cumID=$_POST["cumID_out"];
        $hsID=$_POST["hsID_out"];
        $lmID=$_POST["lmID_out"];
        $monID=$_POST["monID_out"];
        del_diemdanh_nghi($cumID,$hsID,$lmID,$monID);
        delete_thongbao($hsID,$cumID,"nghi-hoc",$lmID);
    }

    if(isset($_POST["hsID7"]) && isset($_POST["tang"])) {
        $hsID=$_POST["hsID7"];
        $tang=$_POST["tang"];
        if($tang==1) {
            add_options($hsID, "sinh-nhat", 0, 0);
        } else {
            delete_options2($hsID, "sinh-nhat", 0, 0);
        }
    }

//    if(isset($_POST["ddID_dd"]) && isset($_POST["hsID_dd"]) && isset($_POST["cumID_dd"]) && isset($_POST["lmID_dd"]) && isset($_POST["monID_dd"])) {
//        $ddID=$_POST["ddID_dd"];
//        $cumID=$_POST["cumID_dd"];
//        $hsID=$_POST["hsID_dd"];
//        $lmID=$_POST["lmID_dd"];
//        $monID=$_POST["monID_dd"];
//        insert_diemdanh($ddID,$hsID,0,0,0,1);
//        del_diemdanh_nghi($cumID,$hsID,$lmID,$monID);
//        delete_thongbao($hsID,$cumID,"nghi-hoc",$lmID);
//        add_thong_bao_hs($hsID, $cumID, "Bạn đã được điểm danh cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $lmID);
//        echo "ok";
//    }

    if(isset($_POST["loai_dd"]) && isset($_POST["ddID_dd"]) && isset($_POST["hsID_dd"]) && isset($_POST["cumID_dd"]) && isset($_POST["lmID_dd"]) && isset($_POST["monID_dd"])) {
        $loai=$_POST["loai_dd"];
        $ddID=$_POST["ddID_dd"];
        $hsID=$_POST["hsID_dd"];
        $cumID=$_POST["cumID_dd"];
        $lmID=$_POST["lmID_dd"];
        $monID=$_POST["monID_dd"];
        $check="none";
        $noti="cc";
        $result0=get_mon_unknow($hsID,$monID);
        $data0=mysqli_fetch_assoc($result0);
        switch ($loai) {
            case "dihoc-tinhdung":
                $is_tinh=1;
                $is_hoc=1;
                $is_kt=1;
                $check="dihoc";
                break;
            case "dihoc-tinhsai":
                $is_tinh=0;
                $is_hoc=1;
                $is_kt=1;
                $check="dihoc";
                break;
            case "dimuon-kokt":
                $is_tinh=0;
                $is_hoc=0;
                $is_kt=0;
                $check="dihoc";
                break;
            case "nghicophep":
                $is_phep=1;
                $check="nghi";
                break;
            case "nghikophep":
                $is_phep=0;
                $check="nghi";
                break;
            default:
                break;
        }
        if($check=="dihoc") {
            delete_diemdanh2($cumID,$hsID,$lmID,$monID);
            insert_diemdanh($ddID,$hsID,$is_tinh,$is_hoc,$is_kt,1);
            del_diemdanh_nghi($cumID,$hsID,$lmID,$monID);
            delete_thongbao($hsID,$cumID,"nghi-hoc",$lmID);
            add_thong_bao_hs($hsID, $cumID, "Bạn đã được điểm danh cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $data0["ID_LM"]);
            $noti="ok-1";
        } else if($check=="nghi") {
            delete_diemdanh2($cumID,$hsID,$lmID,$monID);
            insert_diemdanh_nghi($cumID,$hsID,$is_phep,$lmID,$monID);
            delete_thongbao($hsID,$cumID,"nghi-hoc",$lmID);
            if ($is_phep == 1) {
                add_thong_bao_hs($hsID, $cumID, "Bạn đã nghỉ học Có phép cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $data0["ID_LM"]);
            } else {
                add_thong_bao_hs($hsID, $cumID, "Bạn đã nghỉ học Ko phép cụm học ngày " . get_cum_date($cumID, $lmID, $monID), "nghi-hoc", $data0["ID_LM"]);
            }
            $noti="ok-2";
        } else {
            $noti = "no";
        }
        echo $noti;
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>