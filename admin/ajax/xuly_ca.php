<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
    $lmID=$_SESSION["lmID"];
	$monID=$_SESSION["mon"];
    $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");

    // Đã check
	if (isset($_POST["caID"])) {
		$caID=$_POST["caID"];
		delete_ca($caID);
	}

	// Đã check
	if (isset($_POST["caID0"]) && isset($_POST["siso"]) && isset($_POST["max"]) && isset($_POST["cum"]) && isset($_POST["ddID"])) {
		$caID=$_POST["caID0"];
		$siso=$_POST["siso"];
		$max=$_POST["max"];
		$cum=$_POST["cum"];
		$ddID=$_POST["ddID"];
		edit_ca($caID, $siso, $max, $cum, $ddID);
	}

	// Đã check
	if (isset($_POST["thu"]) && isset($_POST["siso"]) && isset($_POST["max"]) && isset($_POST["gio"]) && isset($_POST["cum"])) {
		$thu=$_POST["thu"];
		$siso=$_POST["siso"];
		$max=$_POST["max"];
		$gio=$_POST["gio"];
		$cum=$_POST["cum"];
		add_ca($thu, $siso, $max, $gio, $cum);
		$_SESSION["new_ca"]=mysqli_insert_id($db);
	}

	// Đã check
	if(isset($_POST["caID3"]) && isset($_POST["siso1"])) {
		$caID=$_POST["caID3"];
		$siso=$_POST["siso1"];
		edit_siso_ca($caID,$siso);
	}

	// Đã check
	if(isset($_POST["caID3"]) && isset($_POST["max1"])) {
		$caID=$_POST["caID3"];
		$max=$_POST["max1"];
		edit_max_ca($caID,$max);
	}

	// Đã check
	if(isset($_POST["hsID_array"]) && isset($_POST["caID1"])) {
		$caID=$_POST["caID1"];
		$cum=get_ca_cum($caID);
		$hsID_array=json_decode($_POST["hsID_array"]);
		for($i=0;$i<count($hsID_array);$i++) {
			add_hs_to_ca_codinh($caID, $hsID_array[$i], $cum);
            $result=get_info_ca($caID);
            $data=mysqli_fetch_assoc($result);
            add_log($hsID_array[$i],"Trợ giảng đổi cố định sang ca ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$lmID");
		}
	}

	// Đã check
	if(isset($_POST["hsID"]) && isset($_POST["caID2"])) {
		$hsID=$_POST["hsID"];
		$caID=$_POST["caID2"];
		delete_hs_ca_tam($caID, $hsID, get_ca_cum($caID));
	}

	// Đã check
    if(isset($_POST["hsID2"]) && isset($_POST["caID4"])) {
        $hsID=$_POST["hsID2"];
        $caID=$_POST["caID4"];
        remove_hs_ca($caID,$hsID,get_ca_cum($caID));
    }

    // Đã check
	if(isset($_POST["name"]) && isset($_POST["cum0"]) && isset($_POST["link"])) {
		$name=$_POST["name"];
		$cum=$_POST["cum0"];
		$link=$_POST["link"];
		edit_name_cum($name, $cum, $link, $lmID, $monID);
	}

	// Đã check
	if(isset($_POST["cum1"])) {
		$cum=$_POST["cum1"];
		$query="SELECT c.ID_CA FROM cahoc AS c
		INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID' 
		WHERE c.cum='$cum'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)==0) {
		    $result2=get_one_cum($cum,$lmID,$monID);
            $data2=mysqli_fetch_assoc($result2);
            if($data2["link"]!=0) {
                $query3="SELECT c.ID_CA FROM cahoc AS c 
                INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_LM!='0' AND g.ID_MON='$monID' 
                WHERE c.cum='$data2[link]'";
                $result3=mysqli_query($db,$query3);
                while($data3=mysqli_fetch_assoc($result3)) {
                    $query4="DELETE FROM ca_codinh AS c INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE c.ID_CA='$data3[ID_CA]' AND c.cum='$data2[link]'";
                    mysqli_query($db,$query4);
                    $query4="DELETE FROM ca_hientai AS c INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' WHERE c.ID_CA='$data3[ID_CA]' AND c.cum='$data2[link]'";
                    mysqli_query($db,$query4);
                }
            }
			delete_cum($cum, $lmID, $monID);
		}
	}

	// Đã check
	if(isset($_POST["lmID0"])) {
		$lmID=$_POST["lmID0"];
		add_cum($lmID, $monID);
	}
	
	if(isset($_POST["thu0"])) {
		$thu=$_POST["thu0"];
		echo"<tr style='background:#3E606F;' id='chon-thu'>
			<th style='width:10%;'><span><i class='fa fa-close close-bang' style='cursor:pointer'></i></span></th>";
		for($i=1;$i<=7;$i++) {
		    if($i==1) {
                echo"<th><span>Chủ nhật</span></th>";
            } else {
                echo"<th><span>Thứ $i</span></th>";
            }
		}
		echo"</tr>";
		$buoi_array=array("1S","2C","3T");
		for($i=0;$i<count($buoi_array);$i++) {
			$ca_hoc=array();
			$query4="SELECT ID_GIO,gio,thutu FROM cagio WHERE buoi='$buoi_array[$i]' AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY thutu ASC";
			$result4=mysqli_query($db,$query4);
			while($data4=mysqli_fetch_assoc($result4)) {
				$ca_hoc[]=array(
					"gioID" => $data4["ID_GIO"],
					"gio" => $data4["gio"],
					"thutu" => $data4["thutu"]
				);
			}
			echo"<tr>
				<th rowspan='2' style='background:#3E606F;'><span>$buoi_array[$i]</span></th>";
				for($j=1;$j<=7;$j++) {
					$result=check_has_ca($j,$ca_hoc[0]["gioID"]);
					if(mysqli_num_rows($result)!=0) {
						echo"<td class='done-ca'><span>".$ca_hoc[0]["gio"]."</span></td>";
					} else {
						if($thu==0 || $j==$thu) {
							echo"<td class='chon-ca' data-gioID='".$ca_hoc[0]["gioID"]."' data-thu='$j' style='cursor:pointer;'><span>".$ca_hoc[0]["gio"]."</span></td>";
						} else {
							echo"<td><span>".$ca_hoc[0]["gio"]."</span></td>";
						}
					}
					/*if(isset($ca_hoc[$j]) && $ca_hoc[$j]["thutu"]=="a") {
						echo"<td class='chon-ca' data-caID='".base64_encode($ca_hoc[$j]["caID"])."' style='cursor:pointer;'><span>".$ca_hoc[$j]["gio"]."</span></td>";
					} else {
						echo"<td class='chon-ca' style='cursor:pointer;'><span>".$ca_hoc[$j]["gio"]."</span></td>";
					}*/
				}
			echo"</tr>";
			echo"<tr>";
				for($j=1;$j<=7;$j++) {
					$result=check_has_ca($j,$ca_hoc[1]["gioID"]);
					if(mysqli_num_rows($result)!=0) {
						echo"<td class='done-ca'><span>".$ca_hoc[1]["gio"]."</span></td>";
					} else {
						if($thu==0 || $j==$thu) {
							echo"<td class='chon-ca' data-gioID='".$ca_hoc[1]["gioID"]."' data-thu='$j' style='cursor:pointer;'><span>".$ca_hoc[1]["gio"]."</span></td>";
						} else {
							echo"<td><span>".$ca_hoc[1]["gio"]."</span></td>";
						}
					}
				}
			echo"</tr>";
		}
	}

	// Đã check
	if(isset($_POST["data"])) {
		ini_set('max_execution_time', 120);
		$data=$_POST["data"];
		$data=json_decode($data, true);
		$n=count($data)-1;
		$ma1=$data[$n]["ma1"];
		$ma2=$data[$n]["ma2"];
		$lmID=$data[$n]["lmID"];
		if(valid_maso($ma1) && valid_maso($ma2) && is_numeric($lmID) && $lmID!=0) {
			$hs_array=array();
			$query2="SELECT h.ID_HS,n.ID_N FROM hocsinh AS h 
			INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
			LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' 
			WHERE h.cmt>='$ma1' AND h.cmt<='$ma2'";
			$result2=mysqli_query($db,$query2);
			while($data2=mysqli_fetch_assoc($result2)) {
				if($data2["ID_N"]) {
					// Do nothing	
				} else {
					$hs_array[]=$data2["ID_HS"];
				}
			}
			for($i=0;$i<$n;$i++) {
				$caID=$data[$i]["caID"];
				$cum=$data[$i]["cum"];
				//$query2="DELETE c.*,h.* FROM $ca_codinh_string AS c,$ca_hientai_string AS h WHERE c.ID_HS<='$ma1' AND c.ID_HS>='$ma2' AND c.cum='$cum' AND h.ID_HS<='$ma1' AND h.ID_HS>='$ma2' AND h.cum='$cum'";
				//mysqli_query($db,$query2);
				for($j=0;$j<count($hs_array);$j++) {
					add_hs_to_ca_codinh($caID,$hs_array[$j],$cum);
				}
			}
			echo"ok";
		} else {
			echo"Dữ liệu không chính xác!";
		}
	}
	
	if(isset($_POST["action"])) {
		$action=$_POST["action"];
		if($action=="lichsu") {
			$result=get_log(date("Y-m"),"doi-ca");
			while($data=mysqli_fetch_assoc($result)) {
				echo"$data[cmt] - $data[fullname] - $data[content] - ".format_datetime($data["datetime"])."\n";
			}
		}
	}

	// Đã check
	if(isset($_POST["ca"]) && isset($_POST["hs"]) && isset($_POST["lmID"])) {
	    $code=md5("123456");
        $hsID=$_POST["hs"];
        $lmID=$_POST["lmID"];
        $caID=decode_data($_POST["ca"],$code);
        $caID=$caID+1-1;
        $cum=get_ca_cum($caID);
        if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
            if(check_hs_in_codinh_cum($hsID,$cum)) {
                add_hs_to_ca_tam($caID, $hsID, $cum);
            } else {
                add_hs_to_ca_codinh($caID,$hsID,$cum);
            }
            $result=get_info_ca($caID);
            $data=mysqli_fetch_assoc($result);
            add_log($hsID,"Trợ giảng đổi tạm sang ca ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$lmID");
            echo"Bạn đã chuyển thành công!";
        } else {
            echo"Lỗi dữ liệu!";
        }
    }

    // Đã check
    if(isset($_POST["ca3"]) && isset($_POST["hs"]) && isset($_POST["lmID"])) {
        $code=md5("123456");
        $hsID=$_POST["hs"];
        $lmID=$_POST["lmID"];
        $caID=decode_data($_POST["ca3"],$code);
        $caID=$caID+1-1;
        $cum=get_ca_cum($caID);
        if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
            add_hs_to_ca_codinh($caID, $hsID, $cum);
            $result=get_info_ca($caID);
            $data=mysqli_fetch_assoc($result);
            add_log($hsID,"Trợ giảng đổi cố định sang ca ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$monID");
            echo"Bạn đã chuyển thành công!";
        } else {
            echo"Lỗi dữ liệu!";
        }
    }

    // Đã check
    if(isset($_POST["ca2"]) && isset($_POST["hs"]) && isset($_POST["lmID"])) {
        $code=md5("123456");
        $hsID=$_POST["hs"];
        $lmID=$_POST["lmID"];
        $caID=decode_data($_POST["ca2"],$code);
        $caID=$caID+1-1;
        $cum=get_ca_cum($caID);
        if(valid_id($caID) && valid_id($hsID) && valid_id($cum)) {
            add_hs_to_ca_tam($caID, $hsID, $cum);
            $result=get_info_ca($caID);
            $data=mysqli_fetch_assoc($result);
            add_log($hsID,"Trợ giảng hủy ca tạm, trở về ca cố định ".$thu_string[$data["thu"]-1].", $data[gio]","doi-ca-$monID");
            echo"Bạn đã chuyển thành công!";
        } else {
            echo"Lỗi dữ liệu!";
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>