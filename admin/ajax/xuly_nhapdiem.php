<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];

    // Đã check
	if(isset($_POST["hsID"]) && isset($_POST["buoiID"])) {
		$hsID=$_POST["hsID"];
		$buoiID=$_POST["buoiID"];
		if(check_exited_nghihoc($hsID, $lmID)) {
			echo json_encode(
				array(
					"action" => "nghi"
				)
			);
		} else {
			$query="SELECT * FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
			$result=mysqli_query($db,$query);
			$data=mysqli_fetch_assoc($result);
			$query0="SELECT h.fullname,h.birth,m.de FROM hocsinh AS h 
			INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
			WHERE h.ID_HS='$hsID'";
			$result0=mysqli_query($db,$query0);
			$data0=mysqli_fetch_assoc($result0);
			if(mysqli_num_rows($result)!=0) {
				$diem_cd=array();
				$diem_cd[]=array(
					"fullname" => $data0["fullname"],
					"birth" => format_dateup($data0["birth"]),
					"de" => $data0["de"],
					"diem" => $data["diem"],
					"dekt" => $data["de"],
					"loai" => $data["loai"],
					"note" => $data["note"],
					"action" => "old"
				);
				$query2="SELECT d.*,c.title FROM chuyende_diem AS d 
				INNER JOIN chuyende AS c ON c.ID_CD=d.ID_CD 
				WHERE d.ID_BUOI='$buoiID' AND d.ID_HS='$hsID' AND d.ID_LM='$lmID' ORDER BY d.cau ASC, d.y ASC";
				$result2=mysqli_query($db,$query2);
				while($data2=mysqli_fetch_assoc($result2)) {
					$temp=explode("/",$data2["diem"]);
					$diem_cd[]=array(
						"idCD" => $data2["ID_CD"],
						"nameCD" => $data2["title"],
						"totalCD" => $temp[1],
						"meCD" => $temp[0],
						"cau" => $data2["cau"],
						"y" => $data2["y"],
						"sttID" => $data2["ID_STT"]
					);
				}
				echo json_encode($diem_cd);
			} else {
				echo json_encode(
					array(
						"fullname" => $data0["fullname"],
						"birth" => format_dateup($data0["birth"]),
						"de" => $data0["de"],
						"action" => "new"
					)
				);
			}
		}
	}

	// Đã check
    if(isset($_POST["hsID8"]) && isset($_POST["buoiID8"]) && isset($_POST["made8"])) {
        $hsID=$_POST["hsID8"];
        $buoiID=$_POST["buoiID8"];
        $made=$_POST["made8"];
//        add_info_buoikt2($buoiID,$hsID,$made,$monID);
        $deID=get_de_id($made,$hsID,$buoiID,$lmID);
        $diem_cd=array();
        $diem_cd[]=array(
            "made" => $made,
            "deID" => $deID,
            "hsID" => $hsID,
            "buoiID" => $buoiID,
            "lmID" => $lmID,
            "action" => "new"
        );
        $query2="SELECT u.ID_CD,u.title,n.sort FROM de_noi_dung AS n 
                INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C
                INNER JOIN chuyen_de_con AS d ON d.ID_CD=c.ID_CD
                INNER JOIN chuyende AS u ON u.maso=d.maso AND u.ID_LM='$lmID'
                WHERE n.ID_DE='$deID'
                ORDER BY n.sort ASC";
        $result2=mysqli_query($db,$query2);
        while($data2=mysqli_fetch_assoc($result2)) {
            $diem_cd[]=array(
                "idCD" => $data2["ID_CD"],
                "nameCD" => $data2["title"],
                "cau" => $data2["sort"],
            );
        }
        echo json_encode($diem_cd);
    }

    // Đã check
    if(isset($_POST["hsID7"]) && isset($_POST["buoiID7"])) {
        $hsID=$_POST["hsID7"];
        $buoiID=$_POST["buoiID7"];
        if(check_exited_nghihoc($hsID, $lmID)) {
            $diem_cd=array();
            $diem_cd[]=array(
                "action" => "nghi"
            );
            echo json_encode($diem_cd);
        } else {
            $query="SELECT * FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_LM='$lmID'";
            $result=mysqli_query($db,$query);
            $data=mysqli_fetch_assoc($result);
            $query0="SELECT h.fullname,h.birth,m.de FROM hocsinh AS h 
                INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
                WHERE h.ID_HS='$hsID'";
            $result0=mysqli_query($db,$query0);
            $data0=mysqli_fetch_assoc($result0);
            if(mysqli_num_rows($result)!=0) {
                $diem_cd=array();
                $diem_cd[]=array(
                    "fullname" => $data0["fullname"],
                    "birth" => format_dateup($data0["birth"]),
                    "de" => $data0["de"],
                    "diem" => $data["diem"],
                    "dekt" => $data["de"],
                    "loai" => $data["loai"],
                    "note" => $data["note"],
                    "made" => $data["made"],
                    "action" => "old"
                );
                $query2="SELECT d.*,c.title FROM chuyende_diem AS d 
                    INNER JOIN chuyende AS c ON c.ID_CD=d.ID_CD 
                    WHERE d.ID_BUOI='$buoiID' AND d.ID_HS='$hsID' AND d.ID_LM='$lmID' ORDER BY d.cau ASC, d.y ASC";
                $result2=mysqli_query($db,$query2);
                while($data2=mysqli_fetch_assoc($result2)) {
                    $temp=explode("/",$data2["diem"]);
                    $diem_cd[]=array(
                        "idCD" => $data2["ID_CD"],
                        "nameCD" => $data2["title"],
                        "meCD" => $temp[0],
                        "cau" => $data2["cau"],
                        "y" => $data2["y"],
                        "note" => $data2["note"],
                        "sttID" => $data2["ID_STT"]
                    );
                }
                echo json_encode($diem_cd);
            } else {
                $query="SELECT made FROM info_buoikt WHERE ID_BUOI='$buoiID' AND ID_HS='$hsID' AND ID_MON='$monID'";
                $result=mysqli_query($db,$query);
                $data=mysqli_fetch_assoc($result);
                $diem_cd=array();
                $diem_cd[]=array(
                    "fullname" => $data0["fullname"],
                    "birth" => format_dateup($data0["birth"]),
                    "de" => $data0["de"],
                    "made" => $data["made"],
                    "action" => "new"
                );
                if(mysqli_num_rows($result)!=0) {
                    $query2 = "SELECT u.ID_CD,u.title,n.sort FROM de_noi_dung AS n 
                    INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C
                    INNER JOIN chuyen_de_con AS d ON d.ID_CD=c.ID_CD
                    INNER JOIN chuyende AS u ON u.maso=d.maso AND u.ID_LM='$lmID'
                    WHERE n.ID_DE=" . get_de_id($data["made"], $hsID, $buoiID, $lmID) . "
                    ORDER BY n.sort ASC";
                    $result2 = mysqli_query($db, $query2);
                    while ($data2 = mysqli_fetch_assoc($result2)) {
                        $diem_cd[] = array(
                            "idCD" => $data2["ID_CD"],
                            "nameCD" => $data2["title"],
                            "cau" => $data2["sort"]
                        );
                    }
                }
                echo json_encode($diem_cd);
            }
        }
    }

	// Đã check
	if(isset($_POST["buoiID4"]) && isset($_POST["dekt4"]) && isset($_POST["lmID4"])) {
		$buoiID=$_POST["buoiID4"];
		$de=$_POST["dekt4"];
		$lmID=$_POST["lmID4"];
		$diem_cd=array();
		$query="SELECT d.ID_HS FROM diemkt AS d 
		INNER JOIN chuyende_diem AS c ON c.ID_BUOI='$buoiID' AND c.diem IS NOT NULL AND c.ID_HS=d.ID_HS AND c.ID_LM='$lmID'
		WHERE d.ID_BUOI='$buoiID' AND d.de='$de' AND d.loai IN ('0','1') AND d.ID_LM='$lmID' ORDER BY d.ID_DIEM ASC LIMIT 1";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			$query2="SELECT d.*,c.title FROM chuyende_diem AS d 
			INNER JOIN chuyende AS c ON c.ID_CD=d.ID_CD WHERE d.ID_BUOI='$buoiID' AND d.ID_HS='$data[ID_HS]' AND d.ID_LM='$lmID' ORDER BY d.cau ASC, d.y ASC";
			$result2=mysqli_query($db,$query2);
			while($data2=mysqli_fetch_assoc($result2)) {
				$temp=explode("/",$data2["diem"]);
				$diem_cd[]=array(
					"idCD" => $data2["ID_CD"],
					"nameCD" => $data2["title"],
					"totalCD" => $temp[1],
					"meCD" => 0,
					"cau" => $data2["cau"],
					"y" => $data2["y"],
					"sttID" => 0
				);
			}
			echo json_encode($diem_cd);
		} else {
			echo"none";
		}
	}

	// Đã check
	if(isset($_POST["idDiem"]) && isset($_POST["buoiID3"]) && isset($_POST["hsID3"])) {
		$idDiem = $_POST["idDiem"];
		$buoiID = $_POST["buoiID3"];
		$hsID = $_POST["hsID3"];
		$data_arr = get_diem_hs3($hsID, $buoiID, $lmID);
        delete_phat_thuong($hsID, "mang_bai_ve_nha_$lmID",$buoiID);
        if($data_arr[1] != 3) {
            delete_phat_thuong($hsID, "kiemtra_" . $lmID, $buoiID);
        }
        delete_thongbao($hsID,$buoiID,"diem-thi",$lmID);
        delete_diem($idDiem, $buoiID, $hsID, $lmID);
	}

	// Đã check
	if(isset($_POST["buoiID2"]) && isset($_POST["lmID2"])) {
		
		$lido=$lido_mau=array();
		$result3=get_all_lido2();
		while($data3=mysqli_fetch_assoc($result3)) {
            $lido[$data3["ID_LD"]]=$data3["name"];
            $lido_mau[$data3["ID_LD"]]=$data3["mau"];
		}
		
		$buoiID=$_POST["buoiID2"];
		$ngay=get_ngay_buoikt($buoiID);
		$lmID=$_POST["lmID2"];
		$stt=$total=0;
//		$query="SELECT h.ID_HS,h.cmt,h.fullname,d.ID_DIEM,d.diem,d.loai,d.de AS dee,d.note,d.made,d.more,m.de,o.ID_O FROM hocsinh AS h
//		INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in<='$ngay'
//		LEFT JOIN diemkt AS d ON d.ID_BUOI='$buoiID' AND d.ID_HS=h.ID_HS AND d.ID_LM='$lmID'
//		LEFT JOIN options AS o ON o.content=h.ID_HS AND o.type='khong-lay-bai' AND o.note='$buoiID' AND o.note2='$monID'
//		WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') ORDER BY h.cmt ASC";
        $de_arr=array();
        $de_str="";
        $query="SELECT n.ID_N,l.name FROM nhom_de AS n
        INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
        INNER JOIN loai_de AS l ON l.ID_D=d.loai
        WHERE n.ID_LM='$lmID' AND n.object='$buoiID'";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $de_arr[$data["name"]]=$data["ID_N"];
            $de_str.=",'$data[ID_N]'";
        }
        if($de_str != "") {
            $de_str = substr($de_str, 1);
            $query = "SELECT h.ID_HS,h.cmt,h.fullname,d.ID_DIEM,d.diem,d.loai,d.de AS dee,d.note,d.made,d.more,m.de,p.ID_STT FROM hocsinh AS h 
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in<='$ngay' 
            LEFT JOIN diemkt AS d ON d.ID_BUOI='$buoiID' AND d.ID_HS=h.ID_HS AND d.ID_LM='$lmID' 
            LEFT JOIN hoc_sinh_special AS p ON p.ID_HS=h.ID_HS AND p.ID_N IN ($de_str)
            WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
            GROUP BY h.ID_HS 
            ORDER BY h.cmt ASC";
        } else {
            $query="SELECT h.ID_HS,h.cmt,h.fullname,d.ID_DIEM,d.diem,d.loai,d.de AS dee,d.note,d.made,d.more,m.de FROM hocsinh AS h
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in<='$ngay'
            LEFT JOIN diemkt AS d ON d.ID_BUOI='$buoiID' AND d.ID_HS=h.ID_HS AND d.ID_LM='$lmID'
            WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') ORDER BY h.cmt ASC";
        }
		$result=mysqli_query($db,$query);
        //$ngay=date_create($ngay);
		while($data=mysqli_fetch_assoc($result)) {
            echo"<tr>
                <td class='hidden'><span>".($total+1)."</span></td>
                <td><span>$data[cmt]</span></td>
                <td class='hidden'><span>$data[fullname]</span></td>
                <td><span>$data[de]</span></td>";
                if(isset($data["loai"]) && is_numeric($data["loai"])) {
                    switch($data["loai"]) {
                        case 0:
                            echo"<td><span>$data[diem] ($data[made]) - STT: $data[more]</span></td>
                            <td><span>Làm bài trên lớp</span></td>";
                            break;
                        case 1:
                            echo"<td style='background:#3E606F;'><span style='color:#FFF'>$data[diem] ($data[made]) - STT $data[more]</span></td>
                            <td><span>Làm bài ở nhà</span></td>";
                            break;
                        case 2:
                            echo"<td colspan='2'><span>Nghỉ học ($data[dee])</span></td>";
                            break;
                        case 3:
                            echo"<td colspan='2' style='background:".$lido_mau[$data["note"]].";'><span style='color:#FFF'>".$lido[$data["note"]]." ($data[dee])</span></td>";
                            break;
                        case 4:
                            echo"<td colspan='2'><span>Mất bài, nghỉ có phép ($data[dee])</span></td>";
                            break;
                        case 5:
                            echo"<td colspan='2' style='background:green;'><span style='color:#FFF;'>Không đi thi ($data[dee])</span></td>";
                            break;
                        default:
                            echo"<td colspan='2'><span></span></td>";
                            break;
                    }
                    echo"<td>";
//                        if($data["loai"]==0 || $data["loai"]==1 || $data["loai"]==3) {
//                            if(isset($data["ID_O"])) {
//                                echo "<input type='submit' class='submit kolay' data-oID='$data[ID_O]' data-hsID='$data[ID_HS]' style='background: red;' value='Ko lấy bài' />";
//                            } else {
//                                echo "<input type='submit' class='submit kolay' data-oID='0' data-hsID='$data[ID_HS]' value='Ko lấy bài' />";
//                            }
//                        }
                        echo"<input type='submit' class='submit delete' data-idDiem='$data[ID_DIEM]' data-hsID='$data[ID_HS]' value='Xóa điểm' />
                    </td>";
                    $stt++;
                } else {
                    if(isset($data["ID_STT"]) && is_numeric($data["ID_STT"])) {
                        echo "<td colspan='3' class='td-nghi' style='background:#ffffa5;'><span>Đã mở khóa</span></td>";
                    } else if(isset($de_arr[$data["de"]])) {
                        echo "<td colspan='3' class='td-nghi' style='background:#ffffa5;'><input type='submit' class='submit unlock' data-hsID='$data[ID_HS]' data-nID='" . $de_arr[$data["de"]] . "' value='Mở khóa trắc nghiệm' /></td>";
                    } else {
                        echo "<td colspan='3' style='background:#ffffa5;'></td>";
                    }
                }

            echo"</tr>";
            $total++;
		}
		if($total==$stt) {
			echo"<input type='hidden' value='$stt / $total' data-du='1' id='status-in' />";
		} else {
			echo"<input type='hidden' value='$stt / $total' data-du='0' id='status-in' />";
		}
	}

	if(isset($_POST["nID_unlock"]) && isset($_POST["hsID_unlock"])) {
	    $nID=$_POST["nID_unlock"];
        $hsID=$_POST["hsID_unlock"];
        $query="INSERT INTO hoc_sinh_special(ID_HS,ID_N) SELECT * FROM (SELECT '$hsID' AS hsID,'$nID' AS nID) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_special WHERE ID_HS='$hsID' AND ID_N='$nID') LIMIT 1";
        mysqli_query($db, $query);
    }

	// Đã check
	if(isset($_POST["data"])) {
		$data=$_POST["data"];
		$diem_cd=json_decode($data, true);
		$n=count($diem_cd)-1;
		$hsID=$diem_cd[$n]["hsID"];
		$buoiID=$diem_cd[$n]["buoiID"];
		$de=$diem_cd[$n]["de"];
		$diem=$diem_cd[$n]["diem"];
		$loai=$diem_cd[$n]["loai"];
		$note=$diem_cd[$n]["note"];
		$check=true;
		if(is_numeric($buoiID) && is_numeric($hsID) && (is_numeric($diem) || $diem=="X") && is_numeric($loai) && ($de=="B" || $de=="G" || $de=="Y")) {
			if($diem!="X") {
				for($i=0;$i<$n;$i++) {
					insert_chuyende_diem($buoiID, $hsID, $diem_cd[$i]["idCD"], $diem_cd[$i]["diemCD"], $diem_cd[$i]["cau"], $diem_cd[$i]["y"], $lmID);
					$temp=explode("/",$diem_cd[$i]["diemCD"]);
					if($temp[1]==0) {
						$check=false;
					}
				}
			}
			$diem_pre = get_auto_diem($monID);
			if($diem_pre != 0) {
			    $so_cau=$diem/format_diem2($diem_pre);
                $diem=$so_cau*$diem_pre;
            }
            $diem=format_diem($diem);
			insert_diem_hs2($hsID, $buoiID, $diem, $de, $loai, $note, $lmID);
            if($loai!=3) {
                $tb=get_cmt_diem_loai2($loai);
            } else {
                $result=get_lido($note);
                $data=mysqli_fetch_assoc($result);
                $tb=$data["name"];
            }
            $buoi=get_ngay_buoikt($buoiID);
            $is_phat = get_is_phat($monID);
            if($is_phat) {
//                delete_phat_thuong($hsID, "mang_bai_ve_nha_$lmID",$buoiID);
//                if($loai!=3) {
//
//                }
                if (!check_binh_voi($hsID, $buoiID, $lmID)) {
                    if ($diem < 5.25 && check_exited_thachdau4($hsID, $buoi, $lmID) && $loai!=3) {

                    } else {
                        get_phat_diemkt($hsID, $diem, $de, $loai, $note, $lmID, get_lop_mon_name($lmID), $buoiID, $buoi, true);
                    }
                }
            }
            if($loai==1 && $is_phat) {
                if(!check_tru_tien($hsID,"mang_bai_ve_nha_$lmID",$buoiID)) {
                    $tien=get_muctien("mang_bai_ve_nha");
                    tru_tien_hs($hsID, $tien, "Mang bài kiểm tra về nhà ngày " . format_dateup($buoi), "mang_bai_ve_nha_$lmID", $buoiID);
                }
            }
            add_thong_bao_hs($hsID,$buoiID, "Điểm thi ngày " . format_dateup($buoi) . " của bạn là $diem điểm ($de). $tb", "diem-thi", $lmID);
			if($check) {
				echo"ok";
			} else {
				echo"dulieu";
			}
		} else {
			echo"loi";
		}
	}

    // Đã check
    if(isset($_POST["data2"])) {
        $data=$_POST["data2"];
        $diem_cd=json_decode($data, true);
        $n=count($diem_cd)-1;
        $hsID=$diem_cd[$n]["hsID"];
        $buoiID=$diem_cd[$n]["buoiID"];
        $de=$diem_cd[$n]["de"];
        $diem=$diem_cd[$n]["diem"];
        $loai=$diem_cd[$n]["loai"];
        $note=$diem_cd[$n]["note"];
        $made=$diem_cd[$n]["made"];
        $how=$diem_cd[$n]["how"];
        if(is_numeric($buoiID) && is_numeric($hsID) && (is_numeric($diem) || $diem=="X") && is_numeric($loai) && ($de=="B" || $de=="G" || $de=="Y")) {
            if($made!="" && $diem!="X") {
                $deID=get_de_id($made,$hsID,$buoiID,$lmID);
                if(check_done_options($buoiID, "cap-nhat-diem-1", $lmID, $monID)) {
                    $how=2;
                    clean_ket_qua_de($hsID,$deID);
                }

                $query = "SELECT n.ID_C,n.ID_DE,n.sort AS csort,a.sort AS dsort,c.done FROM de_noi_dung AS n
                INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C
                INNER JOIN de_cau_dap_an AS a ON a.ID_DE=n.ID_DE
                INNER JOIN dap_an_ngan AS d ON d.ID_DA=a.ID_DA AND d.ID_C=n.ID_C AND d.main='1'
                WHERE n.ID_DE='$deID'
                ORDER BY n.sort ASC";
                $result = mysqli_query($db, $query);
                $num = mysqli_num_rows($result);
                if ($num != 0) {
                    $diem = $deID = 0;
                    $diem_each = format_diem(10 / $num);
                    $i=0;
                    $content1=$content2="";
                    while ($data = mysqli_fetch_assoc($result)) {
                        $deID=$data["ID_DE"];
                        if (isset($diem_cd[$i]["cau"])) {
                            if ((int) $diem_cd[$i]["cau"] == $data["csort"]) {
                                $noteda = base64_decode($diem_cd[$i]["noteda"]);
								if($data["done"]==0) {
									if($how==0 || $how==2) {
									    $content1.=",('$buoiID','".$diem_cd[$i]["idCD"]."','$hsID','$diem_each/$diem_each','".$diem_cd[$i]["cau"]."','".$diem_cd[$i]["diemCD"]."','$noteda','$lmID')";
//										insert_chuyende_diem2($buoiID, $hsID, $diem_cd[$i]["idCD"], $diem_each . "/" . $diem_each, $diem_cd[$i]["cau"], $diem_cd[$i]["diemCD"], $noteda, $lmID);
									}
//									if($how==1 || $how==2) {
//                                        $content2.=",('$hsID','$data[ID_C]','".get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"])."','0','0','$noteda','$data[ID_DE]',now())";
//									    $content2.=",('$hsID','$data[ID_C]','','0','0','$data[ID_DE]')";
//										insert_hoc_sinh_cau($hsID, $data["ID_C"], $data["ID_DE"], true);
//									}
									$diem += $diem_each;
//									if($how==1 || $how==2) {
//										insert_chon_dap_an($hsID, get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"]), $noteda, $data["ID_C"], false, $data["ID_DE"]);
//									}
								} else {
									if ((int) $diem_cd[$i]["diemCD"] == $data["dsort"]) {
										if($how==0 || $how==2) {
                                            $content1.=",('$buoiID','".$diem_cd[$i]["idCD"]."','$hsID','$diem_each/$diem_each','".$diem_cd[$i]["cau"]."','".$diem_cd[$i]["diemCD"]."','$noteda','$lmID')";
//                                            insert_chuyende_diem2($buoiID, $hsID, $diem_cd[$i]["idCD"], $diem_each . "/" . $diem_each, $diem_cd[$i]["cau"], $diem_cd[$i]["diemCD"], $noteda, $lmID);
										}
//										if($how==1 || $how==2) {
//                                            $content2.=",('$hsID','$data[ID_C]','".get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"])."','0','0','$noteda','$data[ID_DE]',now())";
//											insert_hoc_sinh_cau($hsID, $data["ID_C"], $data["ID_DE"], true);
//										}
										$diem += $diem_each;
									} else {
										if($how==0 || $how==2) {
                                            $content1.=",('$buoiID','".$diem_cd[$i]["idCD"]."','$hsID','0/$diem_each','".$diem_cd[$i]["cau"]."','".$diem_cd[$i]["diemCD"]."','$noteda','$lmID')";
//                                            insert_chuyende_diem2($buoiID, $hsID, $diem_cd[$i]["idCD"], "0/" . $diem_each, $diem_cd[$i]["cau"], $diem_cd[$i]["diemCD"], $noteda, $lmID);
										}
//										if($how==1 || $how==2) {
//                                            $content2.=",('$hsID','$data[ID_C]','0','0','$data[ID_DE]')";
//											insert_hoc_sinh_cau($hsID, $data["ID_C"], $data["ID_DE"], false);
//										}
									}
								}
                                if($how==1 || $how==2) {
                                    $content2.=",('$hsID','$data[ID_C]','".get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"])."','0','0','$noteda','$data[ID_DE]',now())";
//										insert_chon_dap_an($hsID, get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"]), $noteda, $data["ID_C"], false, $data["ID_DE"]);
                                }
                            }
                        }
                        $i++;
                    }
                    $content1=substr($content1,1);
                    if($how==0 || $how==2) {
                        $query="INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,note,ID_LM) VALUES $content1";
                        mysqli_query($db,$query);
                    }
                    $content2=substr($content2,1);
                    if($how==1 || $how==2) {
                        $query="INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE,datetime) VALUES $content2";
                        mysqli_query($db,$query);
                        insert_luyen_de($deID, $hsID, "lam-cuoi-tuan", $diem, 0, $lmID);
                    }
                } else {
                    echo"khong";
                }
            }
            $count = 0;
            if($how==0 || $how==2) {
                $count = insert_diem_hs($hsID, $buoiID, $diem, $de, $loai, $note, $made, $lmID);
                if ($loai != 3) {
                    $tb = get_cmt_diem_loai2($loai);
                } else {
                    $result = get_lido($note);
                    $data = mysqli_fetch_assoc($result);
                    $tb = $data["name"];
                }
//                $tb.=". Mã lấy bài là $count";
                $buoi = get_ngay_buoikt($buoiID);
                $is_phat = get_is_phat($monID);
                if ($is_phat) {
//                    delete_phat_thuong($hsID, "mang_bai_ve_nha_$lmID", $buoiID);
                    if($loai!=3) {
                        delete_phat_thuong($hsID, "kiemtra_" . $lmID, $buoiID);
                    }
                    if (!check_binh_voi($hsID, $buoiID, $lmID)) {
                        if ($diem < 5.25 && check_exited_thachdau4($hsID, $buoi, $lmID) && $loai != 3) {

                        } else {
                            if(get_last_loai($buoiID,$hsID,$lmID)!=3) {
                                delete_phat_thuong($hsID, "kiemtra_" . $lmID, $buoiID);
                            }
                            get_phat_diemkt($hsID, $diem, $de, $loai, $note, $lmID, get_lop_mon_name($lmID), $buoiID, $buoi, true);
                        }
                    }
                }
                if ($loai == 1 && $is_phat) {
                    if (!check_tru_tien($hsID, "mang_bai_ve_nha_$lmID", $buoiID)) {
                        $tien = get_muctien("mang_bai_ve_nha");
                        tru_tien_hs($hsID, $tien, "Mang bài kiểm tra về nhà ngày " . format_dateup($buoi), "mang_bai_ve_nha_$lmID", $buoiID);
                    }
                }
                add_thong_bao_hs($hsID, $buoiID, "Điểm thi ngày " . format_dateup($buoi) . " của bạn là $diem điểm ($de). $tb", "diem-thi", $lmID);
            }
            echo $diem." điểm - STT: ".$count;
        } else {
            echo"loi";
        }
    }
	
	if(isset($_POST["data_update"])) {
		$data=$_POST["data_update"];
		$diem_cd=json_decode($data, true);
		$n=count($diem_cd)-1;
		$hsID=$diem_cd[$n]["hsID"];
		$buoiID=$diem_cd[$n]["buoiID"];
		$de=$diem_cd[$n]["de"];
		$diem=$diem_cd[$n]["diem"];
		$loai=$diem_cd[$n]["loai"];
		$note=$diem_cd[$n]["note"];
		$check=true;
		if(is_numeric($buoiID) && is_numeric($hsID) && (is_numeric($diem) || $diem=="X") && is_numeric($loai) && ($de=="B" || $de=="G" || $de=="Y")) {
			if($diem!="X") {
				for($i=0;$i<$n;$i++) {
					update_chuyende_diem($buoiID, $hsID, $diem_cd[$i]["idCD"], $diem_cd[$i]["diemCD"], $diem_cd[$i]["cau"], $diem_cd[$i]["y"], $diem_cd[$i]["sttID"], $lmID);
					$temp=explode("/",$diem_cd[$i]["diemCD"]);
					if($temp[1]==0) {
						$check=false;
					}
				}
			}
            $diem_pre = get_auto_diem($monID);
            if($diem_pre != 0) {
                $so_cau=$diem/format_diem2($diem_pre);
                $diem=$so_cau*$diem_pre;
            }
            $diem=format_diem($diem);
			update_diem_hs2($hsID, $buoiID, $diem, $de, $loai, $note, $lmID);
			if($loai!=3) {
				$tb=get_cmt_diem_loai2($loai);
			} else {
				$result=get_lido($note);
				$data=mysqli_fetch_assoc($result);	
				$tb=$data["name"];
			}
            $buoi=get_ngay_buoikt($buoiID);
            $is_phat = get_is_phat($monID);
            if($is_phat) {
//                delete_phat_thuong($hsID, "mang_bai_ve_nha_$lmID",$buoiID);
//                if($loai!=3) {
//                    delete_phat_thuong($hsID, "kiemtra_" . $lmID, $buoiID);
//                }
                if (!check_binh_voi($hsID, $buoiID, $lmID)) {
                    if ($diem < 5.25 && check_exited_thachdau4($hsID, $buoi, $lmID) && $loai!=3) {

                    } else {
                        get_phat_diemkt($hsID, $diem, $de, $loai, $note, $lmID, get_lop_mon_name($lmID), $buoiID, $buoi, true);
                    }
                }
            }
            if($loai==1 && $is_phat) {
                if(!check_tru_tien($hsID,"mang_bai_ve_nha_$lmID",$buoiID)) {
                    $tien=get_muctien("mang_bai_ve_nha");
                    tru_tien_hs($hsID, $tien, "Mang bài kiểm tra về nhà ngày " . format_dateup($buoi), "mang_bai_ve_nha_$lmID", $buoiID);
                }
            }
            add_thong_bao_hs($hsID,$buoiID,"Điểm thi ngày ".format_dateup($buoi)." của bạn được sửa lại là $diem điểm ($de). $tb","diem-thi",$lmID);
            if($check) {
				echo"ok";
			} else {
				echo"dulieu";
			}
		} else {
			echo"loi";
		}
	}

    if(isset($_POST["data_update2"])) {
        $data=$_POST["data_update2"];
        $diem_cd=json_decode($data, true);
        $n=count($diem_cd)-1;
        $hsID=$diem_cd[$n]["hsID"];
        $buoiID=$diem_cd[$n]["buoiID"];
        $de=$diem_cd[$n]["de"];
        $diem=$diem_cd[$n]["diem"];
        $loai=$diem_cd[$n]["loai"];
        $note=$diem_cd[$n]["note"];
        $made=$diem_cd[$n]["made"];
        $how=$diem_cd[$n]["how"];
        if(is_numeric($buoiID) && is_numeric($hsID) && (is_numeric($diem) || $diem=="X") && is_numeric($loai) && ($de=="B" || $de=="G" || $de=="Y")) {
            if($made!="" && $diem!="X") {
                $deID=get_de_id($made,$hsID,$buoiID,$lmID);
                if(check_done_options($buoiID, "cap-nhat-diem-1", $lmID, $monID)) {
                    $how=2;
                }

                $query = "SELECT n.ID_C,n.ID_DE,n.sort AS csort,a.sort AS dsort,c.done FROM de_noi_dung AS n
                INNER JOIN cau_hoi AS c ON c.ID_C=n.ID_C
                INNER JOIN de_cau_dap_an AS a ON a.ID_DE=n.ID_DE
                INNER JOIN dap_an_ngan AS d ON d.ID_DA=a.ID_DA AND d.ID_C=n.ID_C AND d.main='1'
                WHERE n.ID_DE='$deID'
                ORDER BY n.sort ASC";
                $result = mysqli_query($db, $query);
                $num = mysqli_num_rows($result);
                if ($num != 0) {
                    $diem = $deID = 0;
                    $diem_each = format_diem(10 / $num);
                    $diem_dow = format_diem($diem_each / 2);
                    $i = 0;
                    $content2="";
                    while ($data = mysqli_fetch_assoc($result)) {
                        $deID = $data["ID_DE"];
                        if (isset($diem_cd[$i]["cau"])) {
                            if ((int)$diem_cd[$i]["cau"] == $data["csort"]) {
                                $noteda = base64_decode($diem_cd[$i]["noteda"]);
                                if($data["done"]==0) {
									if ($how == 0 || $how==2) {
										update_chuyende_diem2($buoiID, $hsID, $diem_cd[$i]["idCD"], $diem_each . "/" . $diem_each, $diem_cd[$i]["cau"], $diem_cd[$i]["diemCD"], $noteda, $diem_cd[$i]["sttID"], $lmID);
									}
									$diem += $diem_each;
								} else {
									if ((int)$diem_cd[$i]["diemCD"] == $data["dsort"]) {
										if ($how == 0 || $how==2) {
											update_chuyende_diem2($buoiID, $hsID, $diem_cd[$i]["idCD"], $diem_each . "/" . $diem_each, $diem_cd[$i]["cau"], $diem_cd[$i]["diemCD"], $noteda, $diem_cd[$i]["sttID"], $lmID);
										}
										$diem += $diem_each;
									} else {
										if ($how == 0 || $how==2) {
											update_chuyende_diem2($buoiID, $hsID, $diem_cd[$i]["idCD"], "0/" . $diem_each, $diem_cd[$i]["cau"], $diem_cd[$i]["diemCD"], $noteda, $diem_cd[$i]["sttID"], $lmID);
										}
									}
								}
                                if ($how==2) {
                                    $content2.=",('$hsID','$data[ID_C]','".get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"])."','0','0','$noteda','$data[ID_DE]',now())";
//                                    insert_chon_dap_an($hsID, get_dap_an_by_sort($data["ID_DE"], $data["ID_C"], $diem_cd[$i]["diemCD"]), $noteda, $data["ID_C"], true, $data["ID_DE"]);
                                }
                            }
                        }
                        $i++;
                    }
                    if ($how==2) {
                        $query="DELETE FROM hoc_sinh_cau WHERE ID_HS='$hsID' AND ID_DE='$deID'";
                        mysqli_query($db,$query);
                        $content2=substr($content2,1);
                        $query="INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE,datetime) VALUES $content2";
                        mysqli_query($db,$query);
                        update_luyen_de($deID, $hsID, "lam-cuoi-tuan", $diem, 0, $lmID);
                    }
                } else {
                    echo "khong";
                }
            }
            if($how==0 || $how==2) {
                update_diem_hs($hsID, $buoiID, $diem, $de, $loai, $note, $made, $lmID);
                if ($loai != 3) {
                    $tb = get_cmt_diem_loai2($loai);
                } else {
                    $result = get_lido($note);
                    $data = mysqli_fetch_assoc($result);
                    $tb = $data["name"];
                }
                $buoi = get_ngay_buoikt($buoiID);
                $is_phat = get_is_phat($monID);
                if ($is_phat) {
//                    delete_phat_thuong($hsID, "mang_bai_ve_nha_$lmID", $buoiID);
//                    if($loai!=3) {
//                        delete_phat_thuong($hsID, "kiemtra_" . $lmID, $buoiID);
//                    }
                    if (!check_binh_voi($hsID, $buoiID, $lmID)) {
                        if ($diem < 5.25 && check_exited_thachdau4($hsID, $buoi, $lmID) && $loai != 3) {

                        } else {
                            if(get_last_loai($buoiID,$hsID,$lmID)!=3) {
                                delete_phat_thuong($hsID, "kiemtra_" . $lmID, $buoiID);
                            }
                            get_phat_diemkt($hsID, $diem, $de, $loai, $note, $lmID, get_lop_mon_name($lmID), $buoiID, $buoi, true);
                        }
                    }
                }
                if ($loai == 1 && $is_phat) {
                    if (!check_tru_tien($hsID, "mang_bai_ve_nha_$lmID", $buoiID)) {
                        $tien = get_muctien("mang_bai_ve_nha");
                        tru_tien_hs($hsID, $tien, "Mang bài kiểm tra về nhà ngày " . format_dateup($buoi), "mang_bai_ve_nha_$lmID", $buoiID);
                    }
                }
                add_thong_bao_hs($hsID, $buoiID, "Điểm thi ngày " . format_dateup($buoi) . " của bạn được sửa lại là $diem điểm ($de). $tb", "diem-thi", $lmID);
            }
            echo $diem;
        } else {
            echo"loi";
        }
    }

	if(isset($_POST["oID"]) && isset($_POST["buoiID5"]) && isset($_POST["hsID5"])) {
	    $oID=$_POST["oID"];
        $buoiID=$_POST["buoiID5"];
        $hsID=$_POST["hsID5"];
        echo ko_lay_bai($oID,$buoiID,$hsID,$lmID);
    }

    if(isset($_POST["buoiID6"]) && isset($_POST["lmID6"])) {
        $buoiID=$_POST["buoiID6"];
        $lmID=$_POST["lmID6"];

    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>