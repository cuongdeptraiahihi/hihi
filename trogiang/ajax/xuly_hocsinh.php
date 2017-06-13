<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
//    require_once("../vendor/autoload.php");
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	if(isset($_POST["cmt"])) {
		$cmt=$_POST["cmt"];
		if(check_maso_getID($cmt) != 0) {
			$result=get_hs_detail($cmt);
			$data=mysqli_fetch_assoc($result);
			$check_sdt=$check_sdt_bo=$check_sdt_me=0;
			if($data["sdt"]!="X" && $data["sdt"]!="") {
				$check_sdt = is_check_phone($data["ID_HS"], "$data[sdt]");
			}
			if($data["sdt_bo"]!="X" && $data["sdt_bo"]!="") {
				$check_sdt_bo = is_check_phone($data["ID_HS"], "$data[sdt_bo]");
			}
			if($data["sdt_me"]!="X" && $data["sdt_me"]!="") {
				$check_sdt_me = is_check_phone($data["ID_HS"], "$data[sdt_me]");
			}
			$check_hop=is_check_hop($data["ID_HS"]);
			$name_bo=$job_bo=$face_bo=$mail_bo=$name_me=$job_me=$face_me=$mail_me="";
			$result1=get_phuhuynh_hs($data["ID_HS"],1);
			if(mysqli_num_rows($result1)!=0) {
				$data1=mysqli_fetch_assoc($result1);
				$name_bo=$data1["name"];
				$job_bo=$data1["job"];
				$face_bo=$data1["face"];
				$mail_bo=$data1["mail"];
			}
			$result1=get_phuhuynh_hs($data["ID_HS"],0);
			if(mysqli_num_rows($result1)!=0) {
				$data1=mysqli_fetch_assoc($result1);
				$name_me=$data1["name"];
				$job_me=$data1["job"];
				$face_me=$data1["face"];
				$mail_me=$data1["mail"];
			}
			echo json_encode(
				array(
					"hsID" => $data["ID_HS"],
					"cmt" => $data["cmt"],
					"van" => $data["vantay"],
					"fullname" => $data["fullname"],
					"birth" => $data["birth"],
					"gender" => $data["gender"],
					"taikhoan" => format_price($data["taikhoan"]),
					"face" => str_replace("m.facebook.","www.facebook.",$data["facebook"]),
					"truong" => $data["truong"],
					"name_truong" => get_truong_hs($data["truong"]),
					"sdt" => $data["sdt"],
					"name_bo" => $name_bo,
					"job_bo" => $job_bo,
					"face_bo" => $face_bo,
					"sdt_bo" => $data["sdt_bo"],
					"mail_bo" => $mail_bo,
					"name_me" => $name_me,
					"job_me" => $job_me,
					"face_me" => $face_me,
					"sdt_me" => $data["sdt_me"],
					"mail_me" => $mail_me,
					"check_sdt" => "$check_sdt",
					"check_hop" => "$check_hop",
					"check_sdt_bo" => "$check_sdt_bo",
					"check_sdt_me" => "$check_sdt_me",
                    "hot" => $data["hot"]
				)
			);
		} else {
			echo"none";
		}
	}

	// Đã check
	if(isset($_POST["hsID"])) {
		$hsID=$_POST["hsID"];
		$cmt=get_cmt_hs($hsID);
		$cum_sl=0;

            echo "<tr class='tien-hoc'>
                <td colspan='2' style='text-align:center'><a href='javascript:void(0)' style='text-decoration:underline;color:white;' id='lich-su-mk' data-hsID='$hsID'>Lịch sử đổi mật khẩu</a></td>
                <td style='text-align:center'><a href='javascript:void(0)' style='text-decoration:underline;color:white;' id='lich-su-login' data-hsID='$hsID'>Lịch sử đăng nhập</a></td>
            </tr>";

//		echo"<tr class='tien-hoc'>
//            <td colspan='3'><textarea style='height:70px;resize: vertical;' data-hsID='$hsID' id='hs-note' class='input'>".str_replace("<br />","\n",get_note_hs($hsID))."</textarea></td>
//        </tr>";
        echo"<tr>   
            <th colspan='3'><a style='text-transform: uppercase;color:#FFF;text-decoration: underline;' href='http://localhost/www/TDUONG/trogiang/level-thuong/$hsID/' target='_blank'>Thẻ miễn phạt</a></th>
        </tr>";
        $query="SELECT l.ID_LM,l.name,l.ID_MON,m.de,m.date_in,n.date FROM lop_mon AS l LEFT JOIN hocsinh_mon AS m ON m.ID_HS='$hsID' AND m.ID_LM=l.ID_LM LEFT JOIN hocsinh_nghi AS n ON n.ID_HS='$hsID' AND n.ID_LM=l.ID_LM ORDER BY l.ID_LM ASC";
		$result=mysqli_query($db,$query);
		while($data=mysqli_fetch_assoc($result)) {
			$data_array=array();
			$result3=get_ca_mon_hs($hsID,$data["ID_LM"],$data["ID_MON"]);
			while($data3=mysqli_fetch_assoc($result3)) {
				$data_array[$data3["cum"]]=$data3["ID_CA"];
			}
				
			/*if($data["ID_MON"]==1) {
				$cum_sl=3;
			} else {
				$cum_sl=2;
			}*/
			$date_out="";
			if(isset($data["date_in"]) && !isset($data["date"])) {
				$date_in=format_dateup($data["date_in"]);
				$de=$data["de"];
				$class="fa fa-toggle-on";
				$style="style='display:table-row'";
			} else {
				if(isset($data["date"])) {
					$date_in=format_dateup($data["date_in"]);
                    $date_out=format_dateup($data["date"]);
					$de=$data["de"];
				} else {
					$date_in=date("d/m/Y");
					$de="B";
				}
				$class="fa fa-toggle-off";
				$style="";
			}
            $mon="<span>$data[name] ($de)</span>";
			echo"<tr class='mon-big' data-monID='$data[ID_LM]'>
				<td style='width:25%;'><input type='text' class='input date-in' value='$date_in' /><input type='text' style='background: rgba(255,0,0,0.35);color:#FFF;margin-top:5px;' placeholder='Ngày nghỉ hẳn' class='input date-out' value='$date_out' /></td>
				<td style='width:25%;'><a href='https://localhost/www/TDUONG/dang-nhap/$hsID/".get_sdt_phuhuynh($hsID)."/$data[ID_LM]/' target='_blank'>$mon<i class='fa fa-eye' style='margin-left:5px;font-size:150%;'></i></a></td>
				<td style='width:50%;'>
				    <span class='add-mon'><i class='$class' data-monID='$data[ID_LM]'></i></span>
				    <div style='float:right;'>";
                        if(check_nghi_dai(date("Y-m-d"),$hsID,$data["ID_LM"])) {
                            echo"<a href='http://localhost/www/TDUONG/admin/nghi-dai/$hsID/' target='_blank' style='color:red;font-weight:600;'>Đang nghỉ dài</a>";
                        }
                        echo"
                        <a href='javascript:void(0)' style='margin-top:5px;display:block;color:white;text-decoration:underline;' class='lich-su-doi-ca' data-monID='$data[ID_LM]' data-hsID='$hsID'>Lịch sử đổi ca</a>
                        <a href='http://localhost/www/TDUONG/admin/hoc-sinh-diem-danh/$data[ID_LM]/$data[ID_MON]/3/$hsID/' style='margin-top:5px;display:block;color:white;text-decoration:underline;' target='_blank' class='lich-su-doi-ca'>Điểm danh</a>
                        <a href='http://localhost/www/TDUONG/admin/hoc-sinh-lich-hoc/$hsID/$data[ID_LM]/' style='margin-top:5px;display:block;color:white;text-decoration:underline;' target='_blank'>Đổi ca</a></td>
                    </div>
                </td>
			</tr>";
			$result3=get_all_cum($data["ID_LM"],$data["ID_MON"]);
			//for($i=1;$i<=$cum_sl;$i++) {
			$dem=1;
			while($data3=mysqli_fetch_assoc($result3)) {
				echo"<tr class='mon-lich mon-lich_$data[ID_LM]' $style>
					<td><span>Buổi $dem</span></td>
					<td colspan='2'>";
					if($style=="") {
						echo"<select class='input chose-ca' style='height:auto;width:100%;padding:10px;'>
							<option value='0'>Chọn ca</option>";
							$result2=get_cahoc_cum_lop($data3["ID_CUM"], $data["ID_LM"], $data["ID_MON"]);
							while($data2=mysqli_fetch_assoc($result2)) {
								echo"<option value='$data2[ID_CA]'";if(count($data_array)>0 && isset($data_array[$data2["cum"]]) && $data2["ID_CA"]==$data_array[$data2["cum"]]) {echo" selected='selected'";}echo" data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio], sĩ số ".get_num_hs_ca_codinh($data2["ID_CA"])."</option>";
							}
						echo"</select>";
					} else {
						if(count($data_array)>0 && isset($data_array[$data3["ID_CUM"]])) {
							$result4=get_info_ca($data_array[$data3["ID_CUM"]]);
							$data4=mysqli_fetch_assoc($result4);
							echo"<span>".$thu_string[$data4["thu"]-1].", ca $data4[gio], sĩ số ".get_num_hs_ca_codinh($data_array[$data3["ID_CUM"]])."</span>";
						} else {
                            echo"<span>Vào trang cá nhân để xem ca</span>";
						}
					}
					echo"</td>
				</tr>";
				$dem++;
			}
			$result3=get_cum_kt($data["ID_MON"]);
			$data3=mysqli_fetch_assoc($result3);
			echo"<tr class='mon-lich mon-lich_$data[ID_LM]' $style>
				<td colspan='3'><span><b>Lịch thi cuối tuần: </b></span>";
				if($style=="") {
					echo"<select class='input chose-ca' style='height:auto;width:100%;padding:10px;'>
						<option value='0'>Chọn ca</option>";
						$result2=get_cakt_mon($data["ID_MON"]);
						while($data2=mysqli_fetch_assoc($result2)) {
							echo"<option value='$data2[ID_CA]'";if(count($data_array)>0 && isset($data_array[$data2["cum"]]) && $data2["ID_CA"]==$data_array[$data2["cum"]]) {echo" selected='selected'";}echo" data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio], sĩ số ".get_num_hs_ca_codinh($data2["ID_CA"])."</option>";
						}
					echo"</select>";
				} else {
					if(count($data_array)>0 && isset($data_array[$data3["ID_CUM"]])) {
						$result4=get_info_ca($data_array[$data3["ID_CUM"]]);
						$data4=mysqli_fetch_assoc($result4);
						echo"<span>".$thu_string[$data4["thu"]-1].", ca $data4[gio], sĩ số ".get_num_hs_ca_codinh($data_array[$data3["ID_CUM"]])."</span>";
					} else {
						echo"<span>Vào trang cá nhân để xem ca</span>";
					}
				}
				echo"</td>
			</tr>";
			if(isset($data["date_in"])) {
				$string="";
                $discount=get_discount_hs($hsID,$data["ID_MON"]);
                $temp=split_month(get_lop_mon_in($data["ID_LM"]));
                $nam=$temp[0];
                $thang=$temp[1];
                $temp2=split_month($data["date_in"]);
                $nam_in=$temp2[0];
                $thang_in=get_last_month($temp2[1]);
                if($thang_in==12) {
                    $nam_in--;
                }
                $first=true;
                $price0=get_muctien("dau_thang_sau_".unicode_convert(get_mon_name($data["ID_MON"])));
                for($i=1;$i<=24;$i++) {
                    $tien_hoc=check_dong_tien_hoc($hsID,$data["ID_LM"],"$nam-$thang");
                    if(count($tien_hoc)==0) {
                        if(($nam==$nam_in && $thang>=$thang_in) || ($nam>$nam_in)) {
                            $tinh=true;
                            if(check_nghi_dai_thang("$nam-$thang",$hsID,$data["ID_LM"])) {
                                $string.="<span>T " . format_month2("$nam-$thang") . " nghỉ dài 1 tháng ";
                                $tinh=false;
                            } else {
                                if($discount==100) {
                                    $string = "<span style='color:green;'>T " . format_month2("$nam-$thang") . " miễn 100% học phí ";
                                    $tinh=false;
                                } else {
                                    $string .= "<span style='color:red;'>T " . format_month2("$nam-$thang") . " CẦN đóng ";
                                }
                            }
                            $query1="SELECT ID_O,content FROM options WHERE type='edit-tien-hoc-$data[ID_LM]' AND note='$nam-$thang' AND note2='$hsID'";
                            $result1=mysqli_query($db,$query1);
                            if(mysqli_num_rows($result1)!=0) {
                                $data1 = mysqli_fetch_assoc($result1);
                                $string .= "<strong>" . format_price_sms($data1["content"] * 1000) . " (bắt buộc)</strong>";
//                                $string .= " <i class='btn-edit-price fa fa-pencil' style='cursor: pointer;'></i><input style='width: 60px;margin-left: 5px;padding: 5px;display: none;' class='input edit-price' data-thang='$nam-$thang' data-lmID='$data[ID_LM]' value='$data1[content]' /> <i class='del-edit-price fa fa-times' data-oID='$data1[ID_O]' style='cursor: pointer;'></i></span><br />";
                                $string .= " <i class='btn-edit-price fa fa-pencil' style='cursor: pointer;'></i><input style='width: 60px;margin-left: 5px;padding: 5px;display: none;' class='input edit-price' data-thang='$nam-$thang' data-lmID='$data[ID_LM]' value='$data1[content]' /></span><br />";
                                $first=false;
                            } else if($first) {
                                $string = "<span>Hỗ trợ tháng $thang/$nam? <i class='btn-edit-price fa fa-pencil' style='cursor: pointer;font-size:150%;'></i><input style='width: 60px;margin-left: 5px;padding: 5px;display: none;' class='input edit-price' data-thang='$nam-$thang' data-lmID='$data[ID_LM]' value='' /></span><br />";
                                $first=false;
                            } else if($tinh){
                                if (stripos($data["date_in"], "$nam-$thang") === false) {
                                    $price = du_kien_tien_hoc("$nam-$thang", date("Y-m-d"), $hsID, get_mon_name($data["ID_MON"]));
                                    $string .= "<strong>".format_price_sms($price)."</strong>";
                                } else {
                                    $lich = "";
                                    if (get_day_from_date($data["date_in"]) <= 7) {
                                        $price = $price0;
                                        $price = $price - ($price * $discount / 100);
                                    } else {
                                        $temp = du_kien_tien_hoc_buoi2("$nam-$thang", get_day_from_date($data["date_in"]), $hsID, $data["ID_LM"], $data["ID_MON"]);
                                        $price = $temp[0] - ($temp[0] * $discount / 100);
                                        $lich = $temp[1];
                                    }
                                    if ($price == 0) {
                                        $price = $price0;
                                        $price = $price - ($price * $discount / 100);
                                    }
                                    $string .= "<strong>".format_price_sms($price) . "</strong> (giữa chừng <i class='xem_lich fa fa-eye' data-lich='$lich'></i>)";
                                }
                                $string .= " <i class='btn-edit-price fa fa-pencil' style='cursor: pointer;'></i><input style='width: 60px;margin-left: 5px;padding: 5px;display: none;' class='input edit-price' data-thang='$nam-$thang' data-lmID='$data[ID_LM]' value='".($price/1000)."' /></span><br />";
                            } else {
                                $string .= " <i class='btn-edit-price fa fa-pencil' style='cursor: pointer;'></i><input style='width: 60px;margin-left: 5px;padding: 5px;display: none;' class='input edit-price' data-thang='$nam-$thang' data-lmID='$data[ID_LM]' value='' /></span><br />";
                            }
                        }
                    } else {
                        //if("$nam-$thang"==date("Y-m") || "$nam-$thang"==get_last_time(date("Y"),date("m")) || "$nam-$thang"==get_next_time(date("Y"),date("m"))) {
                        $query1="SELECT ID_O,content FROM options WHERE type='edit-tien-hoc-$data[ID_LM]' AND note='$nam-$thang' AND note2='$hsID'";
                        $result1=mysqli_query($db,$query1);
                        if(mysqli_num_rows($result1)!=0) {
                            $data1=mysqli_fetch_assoc($result1);
                            $price=format_price_sms($data1["content"]*1000);
                            if($price != $tien_hoc[0]) {
                                $string .= "<span>T " . format_month2("$nam-$thang") . " đóng $tien_hoc[0] ($tien_hoc[1]) - $tien_hoc[2] - <strong style='color:red;'>$price</strong> <i class='del-edit-price fa fa-times' data-oID='$data1[ID_O]' style='cursor: pointer;'></i></span><br />";
                            } else {
                                $string .= "<span>T " . format_month2("$nam-$thang") . " đóng $tien_hoc[0] ($tien_hoc[1]) - $tien_hoc[2]</span><br />";
                            }
                        } else {
                            $string .= "<span>T " . format_month2("$nam-$thang") . " đóng $tien_hoc[0] ($tien_hoc[1]) - $tien_hoc[2]</span><br />";
                        }
                        $first=false;
                        //}
                    }
                    if("$nam-$thang"==get_next_time(date("Y"),date("m"))) {
                        break;
                    }
                    $thang++;
                    if($thang==13) {
                        $thang="01";
                        $nam++;
                    } else {
                        if($thang<10) {
                            $thang="0".$thang;
                        } else {
                            $thang="$thang";
                        }
                    }
                }
				echo"<tr class='tien-hoc tien-hoc_$data[ID_LM]'>
					<td><span>Giảm giá</span><br /><input type='number' min='0' max='100' class='input giam-gia' style='height: auto;margin-top: 5px;float: right;width: 60px;' value='$discount' /></td>
					<td colspan='2'>$string</td>
				</tr>";
			}
		}
	}
	
	if(isset($_POST["data"])) {
		$data=$_POST["data"];
		$data=json_decode($data, true);
		$n=count($data)-1;
        $lop=$data[$n]["lop"];
		$cmt=$data[$n]["cmt"];
		$van=$data[$n]["van"];
		$name=$data[$n]["name"];
		$birth=$data[$n]["birth"];
		$gender=$data[$n]["gender"];
		if($data[$n]["pass"]!="") {
			$pass=md5($data[$n]["pass"]);
		} else {
			$pass="";
		}
		$face=base64_decode($data[$n]["face"]);
		$truong=$data[$n]["truong"];
		$sdt=$data[$n]["sdt"];
		$name_bo=$data[$n]["name_bo"];
		$job_bo=$data[$n]["job_bo"];
		$face_bo=$data[$n]["face_bo"];
		$sdt_bo=$data[$n]["sdt_bo"];
		$mail_bo=$data[$n]["mail_bo"];
		$name_me=$data[$n]["name_me"];
		$job_me=$data[$n]["job_me"];
		$face_me=$data[$n]["face_me"];
		$sdt_me=$data[$n]["sdt_me"];
		$mail_me=$data[$n]["mail_me"];
		//$pre=$data[$n]["pre"];
		$hsID=$data[$n]["hsID"];
		$check_sdt=$data[$n]["check_sdt"];
		$check_hop=$data[$n]["check_hop"];
		$check_sdt_bo=$data[$n]["check_sdt_bo"];
		$check_sdt_me=$data[$n]["check_sdt_me"];
		if($name!="" && $birth!="" && ($gender==0 || $gender==1)) {
			if($sdt=="") {
				$sdt="X";
			}
			if($sdt_bo=="") {
				$sdt_bo="X";
			}
			if($sdt_me=="") {
				$sdt_me="X";
			}
			if($hsID!=0) {
				edit_hs($hsID, $cmt, $van, $pass, $name, $birth, $gender, $face, $truong, $sdt, $sdt_bo, $sdt_me);
				if($name_bo!="" || $job_bo!="" || $mail_bo!="" || $face_bo!="") {
					if(check_phuhuynh($hsID,1)) {
						edit_phuhuynh2($hsID,$name_bo,$job_bo,$face_bo,$mail_bo,1);
					} else {
						add_phuhuynh2($hsID,$name_bo,$job_bo,$face_bo,$mail_bo,1);
					}
				}
				if($name_me!="" || $job_me!="" || $mail_me!="" || $face_me!="") {
					if(check_phuhuynh($hsID,0)) {
						edit_phuhuynh2($hsID,$name_me,$job_me,$face_me,$mail_me,0);
					} else {
						add_phuhuynh2($hsID,$name_me,$job_me,$face_me,$mail_me,0);
					}
				}
				if($check_sdt==1 && $sdt!= "" && is_numeric($sdt)) {
					check_phone($hsID, $sdt);
				} else {
					uncheck_phone($hsID, $sdt);
				}
				if($check_sdt_bo==1 && $sdt_bo!= "" && is_numeric($sdt_bo)) {
					check_phone($hsID, $sdt_bo);
				} else {
					uncheck_phone($hsID, $sdt_bo);
				}
				if($check_sdt_me==1 && $sdt_me!= "" && is_numeric($sdt_me)) {
					check_phone($hsID, $sdt_me);
				} else {
					uncheck_phone($hsID, $sdt_me);
				}
				if($check_hop==1) {
					check_hop($hsID);
				} else {
					uncheck_hop($hsID);
				}
				$lmID=0;
				for($i=0;$i<$n;$i++) {
					if($lmID != $data[$i]["lmID"] && is_numeric($data[$i]["lmID"]) && $data[$i]["lmID"]!=0 && $data[$i]["date_in"]!="" && is_numeric($data[$i]["discount"]) && $data[$i]["discount"]>=0 && $data[$i]["discount"]<=100) {
						$lmID=$data[$i]["lmID"];
                        $monID=get_mon_of_lop($lmID);
						$date_in=format_date_o($data[$i]["date_in"]);
						if($data[$i]["remove"]==1) {
						    if($data[$i]["date_out"]!="") {
                                if(check_access_mon($hsID,$lmID)) {
                                    nghi_hoc($hsID,format_date_o($data[$i]["date_out"]),$lmID);
                                    add_options2(date("Y-m-d H:i:s"),"cap-nhat-note","",$hsID);
                                }
                            } else {
                                if(check_access_mon($hsID,$lmID)) {
                                    nghi_hoc($hsID,date("Y-m-d"),$lmID);
                                    add_options2(date("Y-m-d H:i:s"),"cap-nhat-note","",$hsID);
                                }
                            }
                            remove_hs_all_ca($hsID,$lmID,$monID);
                            /*remove_hs_mon($hsID, $monID);*/
						} else {
							add_hs_mon($hsID, "B", $date_in, $lmID);
                            unlock_all_ca_hoc($hsID,$lmID);
                            if(check_hs_nghi($hsID,$lmID)) {
                                $start=get_date_nghi($hsID,$lmID);
                                add_nghidai($hsID,$start,date("Y-m-d"),"",0,$lmID);
                                $query="SELECT ID_CUM,ID_LM FROM diemdanh_buoi WHERE date>='$start' AND date<'".date("Y-m-d")."' AND (ID_LM='$lmID' OR ID_LM='0') AND ID_MON='$monID' ORDER BY ID_LM DESC,ID_CUM DESC";
                                $result=mysqli_query($db,$query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    insert_diemdanh_nghi2($data["ID_CUM"], $hsID, 1, $data["ID_LM"], $monID);
                                }
                                hoc_lai($hsID, $lmID);
                            }

						}
                        update_discount_hs($hsID,$monID,$data[$i]["discount"]);
					}
					if(isset($data[$i]["caID"]) && isset($data[$i]["cum"]) && $data[$i]["remove"]==0) {
						add_hs_to_ca_codinh($data[$i]["caID"], $hsID, $data[$i]["cum"]);
					}
				}
				echo"Sửa thành công!";
			} else {
                $pre_id = get_next_hs_lop($lop);
                $cmt=format_maso($pre_id, get_lop_name($lop));
                $pass = md5($cmt);
                $hsID = add_new_hs($cmt, $van, $pass, $name, $birth, $gender, $face, $truong, $sdt, $sdt_bo, $sdt_me, $lop);
                if ($name_bo != "" || $job_bo != "" || $mail_bo != "" || $face_bo != "") {
                    add_phuhuynh2($hsID, $name_bo, $job_bo, $face_bo, $mail_bo, 1);
                }
                if ($name_me != "" || $job_me != "" || $mail_me != "" || $face_me != "") {
                    add_phuhuynh2($hsID, $name_me, $job_me, $face_me, $mail_me, 0);
                }
                if ($check_sdt == 1 && $sdt != "" && is_numeric($sdt)) {
                    check_phone($hsID, $sdt);
                }
                if ($check_sdt_bo == 1 && $sdt_bo != "" && is_numeric($sdt_bo)) {
                    check_phone($hsID, $sdt_bo);
                }
                if ($check_sdt_me == 1 && $sdt_me != "" && is_numeric($sdt_me)) {
                    check_phone($hsID, $sdt_me);
                }
                if ($check_hop == 1) {
                    check_hop($hsID);
                }
                $lmID = 0;
                for ($i = 0; $i < $n; $i++) {
                    if ($lmID != $data[$i]["lmID"] && is_numeric($data[$i]["lmID"]) && $data[$i]["lmID"] != 0 && is_numeric($data[$i]["discount"]) && $data[$i]["discount"] >= 0 && $data[$i]["discount"] <= 100) {
                        $lmID = $data[$i]["lmID"];
                        $date_in = date("Y-m-d");
                        add_hs_mon($hsID, "B", $date_in, $lmID);
                        unlock_all_ca_hoc($hsID,$lmID);
                        update_discount_hs($hsID, get_mon_of_lop($lmID), $data[$i]["discount"]);
                        //hoc_lai($hsID, $monID);
                    }
                    if (isset($data[$i]["caID"]) && isset($data[$i]["cum"])) {
                        add_hs_to_ca_codinh($data[$i]["caID"], $hsID, $data[$i]["cum"]);
                    }
                }
                echo "Thêm thành công, mã số: $cmt";
			}
			if(!in_array($hsID,$_SESSION["hs_in"])) {
				$_SESSION["hs_in"][]=$hsID;
			}
		} else {
			echo"Lỗi dữ liệu";
		}
	}

	// Đã check
	if(isset($_POST["cmt0"])) {
		$cmt=$_POST["cmt0"];
		if(check_exited_hocsinh($cmt)) {
			$query0="SELECT ID_HS,fullname,birth FROM hocsinh WHERE cmt='$cmt'";
			$result0=mysqli_query($db,$query0);
			$data0=mysqli_fetch_assoc($result0);
			echo json_encode(
				array(
					"fullname" => $data0["fullname"],
					"birth" => format_dateup($data0["birth"])
				)
			);
		} else {
			echo"none";
		}
	}
	
	if(isset($_POST["hsID0"]) && isset($_POST["ngay"]) && isset($_POST["lmID"])) {
		$hsID=$_POST["hsID0"];
		$ngay=$_POST["ngay"];
		$lmID=$_POST["lmID"];
        $monID=get_mon_of_lop($lmID);
		$dekt="";
		$query2="SELECT d.de FROM diemkt AS d INNER JOIN buoikt AS b ON b.ID_BUOI=d.ID_BUOI AND b.ngay LIKE '$ngay-%' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID' AND d.ID_LM='$lmID' ORDER BY d.ID_BUOI ASC";
		$result2=mysqli_query($db,$query2);
		while($data2=mysqli_fetch_assoc($result2)) {
			$dekt.=", $data2[de]";
		}
		$dekt=substr($dekt,1);
		echo $dekt;
	}

	// Đã check
	if(isset($_POST["hsID2"]) && isset($_POST["date_in2"]) && isset($_POST["ngay3"]) && isset($_POST["de2"]) && isset($_POST["lmID2"])) {
		$hsID=$_POST["hsID2"];
		$date_in=$_POST["date_in2"];
        $thang=$_POST["ngay3"];
		$de=$_POST["de2"];
		$lmID=$_POST["lmID2"];
		update_de_hs2($hsID,$de,$lmID,$date_in);
        $diemtb=tinh_diemtb_month($hsID, $thang, $lmID);
        update_diemtb_thang($hsID, $diemtb, $de, $lmID, $thang);
	}

	// Đã check
	if(isset($_POST["hsID2"]) && isset($_POST["ngay2"]) && isset($_POST["de2"]) && isset($_POST["lmID2"])) {
		$hsID=$_POST["hsID2"];
		$ngay=$_POST["ngay2"];
		$de=$_POST["de2"];
		$lmID=$_POST["lmID2"];
        $monID=get_mon_of_lop($lmID);
		$query="UPDATE diemkt SET de='$de' WHERE ID_HS='$hsID' AND ID_LM='$lmID' AND ID_BUOI IN (SELECT ID_BUOI FROM buoikt WHERE ngay LIKE '$ngay-%' AND ID_MON='$monID')";
		mysqli_query($db,$query);
	}

	// Đã check
	if(isset($_POST["hsID3"])) {
		$hsID=$_POST["hsID3"];
		$result=get_log_hs($hsID,"login");
		while($data=mysqli_fetch_assoc($result)) {
			echo"<p>".format_datetime($data["datetime"])." - $data[content]</p>";
		}
	}

	// Đã check
	if(isset($_POST["hsID4"]) && isset($_POST["lmID4"])) {
		$hsID=$_POST["hsID4"];
		$lmID=$_POST["lmID4"];
		$result=get_log_hs($hsID,"doi-ca-$lmID");
		while($data=mysqli_fetch_assoc($result)) {
			echo"<p>".format_datetime($data["datetime"])." - $data[content]</p>";
		}
	}

	// Đã check
	if(isset($_POST["hsID5"])) {
		$hsID=$_POST["hsID5"];
		$result=get_log_hs($hsID,"doi-mat-khau");
		while($data=mysqli_fetch_assoc($result)) {
			echo"<p>".format_datetime($data["datetime"])." - $data[content]</p>";
		}
	}

	// Đã check
    if(isset($_POST["hsID_hot"]) && isset($_POST["is_hot"])) {
        $hsID=$_POST["hsID_hot"];
        $is_hot=$_POST["is_hot"];
        update_hot_hs($hsID,$is_hot);
        echo"ok";
    }

    // Đã check
    if(isset($_POST["nID_hot"]) && isset($_POST["is_hot"])) {
        $nID=$_POST["nID_hot"];
        $is_hot=$_POST["is_hot"];
        update_hs_note($nID,$is_hot);
        echo"ok";
    }

    // Đã check
    if(isset($_POST["maso"])) {
        $maso=$_POST["maso"];
        $query="SELECT cmt,fullname,sdt,sdt_bo,sdt_me FROM hocsinh WHERE cmt='$maso'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        echo"
            Mã số: $data[cmt]<br />
            Họ và tên: $data[fullname]<br />
            Số cá nhân: ".format_mobile_click($data["sdt"])."<br />
            Số bố: ".format_mobile_click($data["sdt_bo"])."<br />
            Số mẹ: ".format_mobile_click($data["sdt_me"]);
    }

    // Đã check
    if(isset($_POST["hsID_note"])) {
        $hsID=$_POST["hsID_note"];
        $result=get_special_note($hsID);
        $data=mysqli_fetch_assoc($result);
        echo"<tr class='tr-note'>
            <th colspan='3' id='open-dac-biet' style='cursor: pointer;'><span>Ghi chú đặc biệt</span></th>
        </tr>
        <tr class='tr-note'>
            <td colspan='3' style='position: relative;'><textarea style='resize:none;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' data-nID='$data[ID_N]' class='input' id='note-dac-biet'>".str_replace("<br />","\n",$data["content"])."</textarea><span style='position:absolute;z-index:9;top:0;right:0;font-size:22px;cursor:pointer;' data-hsID='$hsID' class='fa fa-check' id='note-done'></span></td>
        </tr>
        <tr class='tr-note'>
            <th colspan='3'><span>Ghi chú chính</span></th>
        </tr>
        <tr class='tr-note'>
            <td colspan='3'><span data-date='0'></span><textarea style='resize:none;padding:5px;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' data-hsID='$hsID' class='input note-day'>".str_replace("<br />","\n",get_note_hs($hsID))."</textarea></td>";
//            if(check_is_hot($hsID)) {
//                echo "<td><input type='submit' style='background:red;' class='submit check-hot is_chuy' data-hsID='$hsID' value='Lưu ý' /></td>";
//            } else {
//                echo "<td><input type='submit' style='background:cyan;border:none;opacity:0.4;' class='submit check-hot' data-hsID='$hsID' value='Lưu ý' /></td>";
//            }
        echo"</tr>
        <tr class='tr-note'>
            <th colspan='3' style='position: relative;'><span>Ghi chú nghỉ học</span><span class='note-count'></span></th>
        </tr>";
        $default=false;
        $result=get_hocsinh_note($hsID,null);
        while($data=mysqli_fetch_assoc($result)) {
            if($data["ngay"]!=date("Y-m-d") && !$default) {
                $thu = date("w",strtotime(date("Y-m-d"))) + 1;
                if($thu==1) {
                    $thu="CN";
                } else {
                    $thu = "T" . $thu;
                }
                echo"<tr class='tr-note'>
                    <td style='width: 15%;'><span data-ready='$data[ready]' data-date='".date("Y-m-d")."'>".date("d/m")." ($thu)</span></td>
                    <td colspan='2'><textarea style='resize:none;padding:5px;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' rows='1' data-hsID='$hsID' class='input note-day'></textarea></td>";
//                    <td>";
//                    if($data["hot"]==1) {
//                        echo "<input type='submit' data-nID='$data[ID_STT]' class='submit check-chuy is_chuy' style='background:red;' value='New' />";
//                    } else {
//                        echo "<input type='submit' data-nID='$data[ID_STT]' class='submit check-chuy' style='background:cyan;border:none;opacity:0.4;' value='Lưu ý' />";
//                    }
//                    echo"</td>
                echo"</tr>";
            }
            $thu = date("w",strtotime($data["ngay"])) + 1;
            if($thu==1) {
                $thu="CN";
            } else {
                $thu = "T" . $thu;
            }
            echo"<tr class='tr-note'>
                <td style='width: 15%;'><span data-ready='$data[ready]' data-date='$data[ngay]'>".format_date($data["ngay"])." ($thu)</span></td>
                <td><textarea style='resize:none;padding:5px;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' rows='1' data-hsID='$hsID' class='input note-day'>".str_replace("<br />","\n",$data["note"])."</textarea></td>";
//                <td>";
//                if($data["hot"]==1) {
//                    echo "<input type='submit' data-nID='$data[ID_STT]' class='submit check-chuy is_chuy' style='background:red;' value='New' />";
//                } else {
//                    echo "<input type='submit' data-nID='$data[ID_STT]' class='submit check-chuy' style='background:cyan;border:none;opacity:0.4;' value='Lưu ý' />";
//                }
//                echo"</td>
                echo"<td style='width: 20%;position:relative;cursor:pointer;'>";
                $mon_arr=get_all_mon_hocsinh($hsID);
                $n=count($mon_arr);
                for($i=0;$i<$n;$i++) {
                    if($mon_arr[$i]["lmID"]!=1 && $mon_arr[$i]["lmID"]!=2) {continue;}
                    $query2 = "SELECT d.ID_STT,d.ID_CUM,d.is_phep,d.nhan,d.confirm,d.ID_LM,d.ID_MON,l.name AS name1,m.name AS name2 FROM diemdanh_nghi AS d 
                    INNER JOIN diemdanh_buoi AS b ON b.ID_CUM=d.ID_CUM AND b.date='$data[ngay]' AND b.ID_LM=d.ID_LM AND b.ID_MON=d.ID_MON
                    LEFT JOIN lop_mon AS l ON l.ID_LM=d.ID_LM
                    INNER JOIN mon AS m ON m.ID_MON=d.ID_MON
                    WHERE d.ID_HS='$hsID' AND d.ID_LM='".$mon_arr[$i]["lmID"]."'";
                    $result2 = mysqli_query($db, $query2);
                    if (mysqli_num_rows($result2) != 0) {
                        $data2 = mysqli_fetch_assoc($result2);
                        echo "<span style='font-size:10px;display:block;' class='span-explain'>";
                        if ($data2["is_phep"] == 1) {
                            echo "<p class='note-phep tot' data-hsID='$hsID' data-cumID='$data2[ID_CUM]' data-isphep='0' data-lmID='$data2[ID_LM]' data-monID='$data2[ID_MON]'>Phép</p>";
                        } else {
                            echo "<p class='note-phep xau' data-hsID='$hsID' data-cumID='$data2[ID_CUM]' data-isphep='1' data-lmID='$data2[ID_LM]' data-monID='$data2[ID_MON]' style='text-decoration:line-through;'>Phép</p>";
                        }
                        if ($data2["nhan"] == 1) {
                            echo "<p class='note-tin tot' data-sttID='$data2[ID_STT]' data-nhan='0'>Đã nhắn tin</p>";
                        } else {
                            echo "<p class='note-tin xau' data-sttID='$data2[ID_STT]' data-nhan='1' style='text-decoration:line-through;'>Đã nhắn tin</p>";
                        }
                        if ($data2["confirm"] == 1) {
                            echo "<p class='note-xac-nhan tot' data-sttID='$data2[ID_STT]' data-nhan='0'>Đã xác nhận</p>";
                        } else {
                            echo "<p class='note-xac-nhan xau' data-sttID='$data2[ID_STT]' data-nhan='1' style='text-decoration:line-through;'>Đã xác nhận</p>";
                        }
//                        echo"<nav style='display:none;position:absolute;z-index:9;right:-40%;left:20%;background:#FFF;padding:5px;border:2px solid #3E606F;'><p style='font-size:10px;'>
//                            <strong>Môn ";
//                            if(isset($data2["name1"])) {
//                                echo $data2["name1"];
//                            } else {
//                                echo $data2["name2"];
//                            }
//                            echo"</strong><br />Chỗ này e đang làm dở thầy ạ :|
//                        </p></nav>";
                        echo "</span>";
                    } else {
                        echo "<span style='font-size:10px;display:block;'>Chưa chốt</span>";
                    }
//                    if($n>1) {
//                        echo "<span style='display: block;border:1px solid #3E606F;height:0;margin:2.5px 0px 2.5px 0;'><br /></span>";
//                    }
                }
                echo"</td>
            </tr>";
            $default=true;
        }
        if(!$default) {
            $thu = date("w",strtotime(date("Y-m-d"))) + 1;
            if($thu==1) {
                $thu="CN";
            } else {
                $thu = "T" . $thu;
            }
//            echo"<tr class='tr-note'>
//                <td><span data-ready='0' data-date='".date("Y-m-d")."'>".date("d/m")."</span></td>
//                <td><textarea style='resize:none;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' rows='1' data-hsID='$hsID' class='input note-day'></textarea></td>
//                <td><input type='submit' data-nID='0' class='submit check-chuy' style='background:cyan;border:none;opacity:0.4;' value='Lưu ý' /></td>
//            </tr>";
            echo"<tr class='tr-note'>
                <td style='width: 15%;'><span data-ready='0' data-date='".date("Y-m-d")."'>".date("d/m")." ($thu)</span></td>
                <td colspan='2'><textarea style='resize:none;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' rows='1' data-hsID='$hsID' class='input note-day'></textarea></td>
            </tr>";
        }
        echo"<tr class='tr-note'>
            <td colspan='3' style='text-align:right;'><input type='submit' data-hsID='$hsID' class='submit' id='them-note' value='Thêm' /></td>
        </tr>";
    }

    // Đã check
    if(isset($_POST["hsID1"]) && isset($_POST{"ngay1"}) && isset($_POST["note2"])) {
        $hsID=$_POST["hsID1"];
        $ngay=$_POST["ngay1"];
        $note=$_POST["note2"];
        $pre=add_hs_note($hsID,$ngay,$note);
        if($note != "" && $note != " " && $note != "-" && $note != "- ") {
            add_options2(date("Y-m-d H:i:s"),"cap-nhat-note",$pre,$hsID);
        }
        echo $pre;
    }

    // Đã check
    if(isset($_POST["hsID_all_note"])) {
        $hsID=$_POST["hsID_all_note"];
        echo"<table class='table' style='background: #FFF;'>
            <tr style='background:#3E606F;'>
                <th style='width: 15%;cursor: pointer;' class='th-close'><span><i class='fa fa-close' style='font-size:17px;'></i></span></th>
                <th style='text-align: left;padding-left: 15px;'><span style='color:#FFF;'>Note chính</span></th>
            </tr>
            <tr>
                <td colspan='2' style='text-align: left;padding-left: 15px;'><span>".nl2br(get_note_hs($hsID))."</span></td>
            </tr>
            <tr style='background:#3E606F;'>
                <th></th>
                <th style='text-align: left;padding-left: 15px;'><span style='color:#FFF;'>Note ngày</span></th>
            </tr>";
        $result=get_hocsinh_note($hsID,7);
        while($data=mysqli_fetch_assoc($result)) {
            echo"<tr>
                <td style='width: 15%;'><span>".format_date($data["ngay"])."</span></td>
                <td style='text-align: left;padding-left: 15px;'><span>".nl2br($data["note"])."</span></td>
            </tr>";
        }
        echo"</table>";
    }

    // Đã check
    if(isset($_POST["hsID_main_note"])) {
        $hsID=$_POST["hsID_main_note"];
        echo"<br /><span style='float:left;margin:5px 0 5px 2.5%;'>Note cũ:</span>
        <br /><textarea class='input note' rows='1' data-hsID='$hsID' data-ngay='0' style='resize:none;box-sizing: border-box;overflow: hidden;'>".str_replace("<br />","\n",get_note_hs($hsID))."</textarea>";
        $result=get_hocsinh_note($hsID,5);
        while($data=mysqli_fetch_assoc($result)) {
            echo"<span style='margin:5px 0 5px 2.5%;text-align: left;display: block;'><strong>(".format_date($data["ngay"]).")</strong> ".nl2br($data["note"])."</span>";
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>