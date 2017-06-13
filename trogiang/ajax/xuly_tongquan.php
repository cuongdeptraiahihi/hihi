<?php
	ob_start();
	//session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	session_start();
    if(!$_SESSION['id']) {
        header('location: http://localhost/www/TDUONG/trogiang/dang-nhap/');
        exit();
    }
    $id=$_SESSION['id'];


	
	if(isset($_POST["face"]) && isset($_POST["monID"])) {
		$face=$_POST["face"];
		$monID=$_POST["monID"];
		if($face!="") {
			/*if(check_dangky_face($hsID,$monID)) {
				echo"no";
			} else {*/
				add_dangky_face($hsID,$face,$lmID);
				echo"ok";
			//}
		}
	}

	if(isset($_POST["date_in"]) && isset($_POST["date_start"])) {
	    $date_in=$_POST["date_in"];
        $date_start=$_POST["date_start"];

        $lido=array();
        $result5=get_all_lido2();
        while($data5=mysqli_fetch_assoc($result5)) {
            $lido[$data5["ID_LD"]]=$data5["name"];
        }

        $date=date_create($date_in);
        $query2="SELECT d.*,b.ngay FROM buoikt AS b
            LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID'
            WHERE b.ngay>'$date_start' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC";
        $result2=mysqli_query($db,$query2);
        while($data2=mysqli_fetch_assoc($result2)) {
            if(isset($data2["ID_DIEM"])) {
                $ngay = date_create($data2["ngay"]);
                if ($ngay < $date) {
                    echo "<td><span>Chưa học</span></td>";
                } else {
                    if($data2["loai"]==0 || $data2["loai"]==1 || $data2["loai"]==3) {
                        if ($data2["loai"] == 3 && $data2["note"] != 0) {
                            echo "<td><span>" . $lido[$data2["note"]] . "</span></td>";
                        } else if ($data2["loai"] == 1) {
                            echo "<td style='background:rgba(0,0,0,0.15);'><span>$data2[diem]<i class='fa fa-home venha' style='margin-left:5px;'></i></span></td>";
                        } else {
                            echo "<td><span>$data2[diem]</span></td>";
                        }
                    } else {
                        echo "<td><span>" . get_cmt_diem_loai2($data2["loai"]) . "</span></td>";
                    }
                }
            } else {
                echo "<td><span>Chưa có</span></td>";
            }
        }
    }

	if(isset($_POST["dulieu"])) {
	    $dulieu=$_POST["dulieu"];
	    if($dulieu=="lichhoc") {
            $lich_hoc = get_hs_lich_hoc2($hsID, $lmID, $monID);
            $lich_thi = get_hs_lich_thi($hsID, $monID);
            echo "<p style='margin-bottom:5px;margin-top:5px;width:83%'><a href='https://localhost/www/TDUONG/lich-hoc/' style='font-weight:600;font-size:14px;color:#FFF;'>Lịch học cố định:</a> $lich_hoc</p>
            <p><a href='https://localhost/www/TDUONG/lich-hoc/' style='font-weight:600;font-size:14px;color:#FFF;'>Lịch thi:</a> $lich_thi</p>";
            if (!check_more_lop_mon($hsID, $lmID, $monID)) {
                $lich_tc = get_hs_tang_cuong($hsID, $lmID, $monID);
                if ($lich_tc != "") {
                    echo "<p><a href='https://localhost/www/TDUONG/lich-hoc/' style='font-weight:600;font-size:14px;color:#FFF;'>Lịch tăng cường:</a> $lich_tc</p>";
                }
            }
            echo"<div class='clear'></div>";
        } else if($dulieu=="thongbaoall") {
            $result=get_thong_bao_all($lmID);
            if(mysqli_num_rows($result)!=0) {
                $data=mysqli_fetch_assoc($result);
                echo"<div class='main-div back animated bounceInUp' id='thongbao-all'>
                    <p>$data[content]</p>
                    <span>".get_past_time($data["datetime"])."</span>
                </div>";
            }
        } else if($dulieu=="navmon") {
            $tb_num=count_thong_bao_tro_giang($id);
            if($tb_num>=1) {
                echo"<li style='position:relative;background:#FFF;' id='tb-icon'>
					<a href='javascript:void(0)' style='color:yellow;' title='Thông báo'><i class='fa fa-bell'></i></a>
					<p style='position:absolute;right:35%;color:#FFF;background:red;border-radius:1000px;width:20px;height:20px;font-size:14px;line-height:22px;top:5%;'>$tb_num</p>";
            } else {
                echo"<li style='position:relative;background:#FFF;' id='tb-icon'><a href='javascript:void(0)' title='Thông báo' style='opacity:0.35;'><i class='fa fa-bell'></i></a>";
            }
            echo"</li>";
        }
    }
	
	if(isset($_POST["gender"])) {
		$gender=$_POST["gender"];
		if(is_numeric($gender) && ($gender==0 || $gender==1)) {
			update_hs_gender($hsID,$gender);
		}
	}
	
	if(isset($_POST["sdt"])) {
		$sdt=$_POST["sdt"];
		if(is_numeric($sdt) && $sdt>0) {
			update_hs_sdt($hsID,$sdt);
		}
	}
	
	if(isset($_POST["action"])) {
		$action=$_POST["action"];
		if($action=="lichsu-tinh") {
			$tinh=0;$dem=0;
			$query="SELECT d.is_tinh FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_LM='$lmID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID' AND d.is_kt='1'";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				if($data["is_tinh"]==1) {
					$tinh++;
				}
				$dem++;
			}
			echo"<p>Số lần tính đúng / Tổng số bài kiểm tra</p>
			<p>$tinh / $dem</p>";
			$query="SELECT d.is_tinh,d.is_kt,b.date FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_LM='$lmID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID' ORDER BY b.date DESC LIMIT 1";
			$result=mysqli_query($db,$query);
			$data=mysqli_fetch_assoc($result);
			echo"<p>Buổi học gần nhất ".format_dateup($data["date"]).": </p>";
			if($data["is_kt"]==1) {
				if($data["is_tinh"]==1) {
					echo"<p>Tính đúng <i class='fa fa-thumbs-o-up' style='font-size:22px;margin-left:5px;'></i></p>";
				} else {
					echo"<p>Tính sai <i class='fa fa-thumbs-o-down' style='font-size:22px;margin-left:5px;'></i></p>";
				}
			} else {
				echo"<p>Không kiểm tra đầu giờ</p>";
			}
		} else if($action=="lichsu-hoc") {
			$hoc=0;$dem=0;
			$query="SELECT d.is_hoc FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_LM='$lmID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID' AND d.is_kt='1'";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				if($data["is_hoc"]==1) {
					$hoc++;
				}
				$dem++;
			}
			echo"<p>Số lần học bài / Tổng số bài kiểm tra</p>
			<p>$hoc / $dem</p>";
			$query="SELECT d.is_hoc,d.is_kt,b.date FROM diemdanh AS d INNER JOIN diemdanh_buoi AS b ON b.ID_STT=d.ID_DD AND b.ID_LM='$lmID' AND b.ID_MON='$monID' WHERE d.ID_HS='$hsID' ORDER BY b.date DESC LIMIT 1";
			$result=mysqli_query($db,$query);
			$data=mysqli_fetch_assoc($result);
			echo"<p>Buổi học gần nhất ".format_dateup($data["date"]).": </p>";
			if($data["is_kt"]==1) {
				if($data["is_hoc"]==1) {
					echo"<p>Có học bài <i class='fa fa-thumbs-o-up' style='font-size:22px;margin-left:5px;'></i></p>";
				} else {
					echo"<p>Không học bài <i class='fa fa-thumbs-o-down' style='font-size:22px;margin-left:5px;'></i></p>";
				}
			} else {
				echo"<p>Không kiểm tra đầu giờ</p>";
			}
		} else {
			echo"";
		}
	}

    if(isset($_POST["hsID_kick"]) && isset($_POST["nID_kick"])) {
        $hsID = decode_data($_POST["hsID_kick"],$code);
        $nID = decode_data($_POST["nID_kick"],$code);
        if(valid_id($hsID) && valid_id($nID) ) {
            delete_hs_list_group($hsID,$nID);
        }
    }

    if(isset($_POST["hsID_in"]) && isset($_POST["nID_in"])) {
        $hsID = decode_data($_POST["hsID_in"],$code);
        $nID = decode_data($_POST["nID_in"],$code);
        if(valid_id($hsID) && valid_id($nID)) {
            $query="SELECT ID_N FROM game_group WHERE ID_N='$nID' AND (password='none' OR password='')";
            $result=mysqli_query($db,$query);
            if(mysqli_num_rows($result)!=0) {
                add_list_group($hsID,2,$nID);
                echo"ok";
            } else {
                echo"none";
            }
        } else {
            echo"none";
        }
    }

    if(isset($_POST["hsID_pass"]) && isset($_POST["nID_pass"]) && isset($_POST["pass"])) {
        $hsID = decode_data($_POST["hsID_pass"],$code);
        $nID = decode_data($_POST["nID_pass"],$code);
        $pass = addslashes($_POST["pass"]);
        if(valid_id($hsID) && valid_id($nID)) {
            $query="SELECT ID_N FROM game_group WHERE ID_N='$nID' AND password='$pass'";
            $result=mysqli_query($db,$query);
            if(mysqli_num_rows($result)!=0) {
                add_list_group($hsID,2,$nID);
                echo"ok";
            } else {
                echo"none";
            }
        } else {
            echo"none";
        }
    }

    if(isset($_POST["nID_list"])) {
        $nID=decode_data($_POST["nID_list"],$code);
        if(valid_id($nID)) {
            echo"<p>Danh sách nhóm</p>";
            $stt=1;
            $query = "SELECT h.cmt,h.fullname,l.level FROM list_group AS l
            INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS
            WHERE l.ID_N='$nID'
            ORDER BY l.level ASC";
            $result=mysqli_query($db,$query);
            while($data=mysqli_fetch_assoc($result)) {
                echo"<p>$data[fullname] - $data[cmt]";
                if($data["level"]==1) {
                    echo"<i class='fa fa-trophy' style='font-size:22px;margin-left:10px;color:yellow;'></i>";
                }
                echo"</p>";
                $stt++;
            }
        }
    }

    if(isset($_POST["name_edit"]) && isset($_POST["nID_edit"])) {
        $name=$_POST["name_edit"];
        $nID=decode_data($_POST["nID_edit"],$code);
        if(valid_id($nID)) {
            $query="UPDATE game_group SET name='$name' WHERE ID_N='$nID' AND state='bt'";
            mysqli_query($db,$query);
        }
    }

    if(isset($_POST["pass_edit"]) && isset($_POST["nID_edit"])) {
        $pass=$_POST["pass_edit"];
        $nID=decode_data($_POST["nID_edit"],$code);
        if(valid_id($nID)) {
            $query="UPDATE game_group SET password='$pass' WHERE ID_N='$nID' AND state='bt'";
            mysqli_query($db,$query);
        }
    }

    if(isset($_POST["maso"]) && isset($_POST["tien"])) {
        $maso=$_POST["maso"];
        $tien=$_POST["tien"];
        if(valid_maso($maso) && is_numeric($tien) && $tien>0) {
            if(get_tien_hs($hsID) >= $tien) {
                if($tien % 10000 == 0) {
                    $id = get_hs_id($maso);
                    if ($id != 0 && $id != $hsID) {
                        $ma = get_cmt_hs($hsID);
                        tru_tien_hs($hsID, $tien, "Chuyển tiên cho mã số $maso", "chuyen-tien", $id);
                        cong_tien_hs($id, $tien, "Nhận tiền từ mã số $ma", "nhan-tien", $hsID);
                        echo "ok";
                    } else {
                        echo "sai";
                    }
                } else {
                    echo"boi";
                }
            } else {
                echo"thieu";
            }
        } else {
            echo"none";
        }
    }

    if(isset($_POST["nID_captain"]) && isset($_POST["hsID_captain"])) {
        $nID=decode_data($_POST["nID_captain"],$code);
        $id=decode_data($_POST["hsID_captain"],$code);
        $query="UPDATE list_group SET level='2' WHERE ID_HS='$hsID' AND ID_N='$nID'";
        mysqli_query($db, $query);
        $query="UPDATE list_group SET level='1' WHERE ID_HS='$id' AND ID_N='$nID'";
        mysqli_query($db, $query);
        $query="UPDATE game_group SET ID_HS='$id' WHERE ID_N='$nID'";
        mysqli_query($db, $query);
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>