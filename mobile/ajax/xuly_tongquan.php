<?php
	ob_start();
	//session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
	if(isset($_SESSION["lmID"]) && isset($_SESSION["mon"])) {
        $hsID = $_SESSION["ID_HS"];
        $lmID = $_SESSION["lmID"];
        $monID = $_SESSION["mon"];
        $code=$_SESSION["code"];
    } else {
        $hsID = 0;
        $lmID = 0;
        $monID = 0;
        $code=0;
    }

    if (isset($_POST["search_short"]) && isset($_POST["lmID1"])) {
        $search=$_POST["search_short"];
        $lmID=$_POST["lmID1"];
        $i=1;
        $result=search_hoc_sinh_limit($search, 10, $lmID);
        if($data=mysqli_num_rows($result)==0) {
            echo"<tr><td colspan='6' style='color:white;'>Không có học sinh này!</td></tr>";
        } else {
            while($data=mysqli_fetch_assoc($result)) {
                echo"<tr class='tr-me back tr-search'>
                    <td><span>$i</span></td>
                    <td><span>$data[cmt]</span></td>
                    <td><span><a style='color:#FFF;text-decoration: underline;' href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>Xem</span></td> 
                    <td><span><button class='submit invite-submit' data-hsID2='$data[ID_HS]'>Mời</button></span></td>
                </tr>";
                $i++;
            }
        }
    }

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

    if(isset($_POST["dulieu"])) {
        $dulieu = $_POST["dulieu"];
        if ($dulieu == "navmon") {
            echo"<li style='position:relative;background:#FFF;' id='menu-chon-mon' ><a href='javascript:void(0)' title='Menu'><span style='margin-right: 7px;'>".get_mon_name($monID)."</span><i class='fa fa-smile-o' style='font-size:14px;'></i></a></a>
                <ul style='background:#FFF;margin-top:-1px;padding:0 10px 0 10px;display:none;border-bottom-right-radius:10px;' id='menu-chon-xo'>";
            $queryx="SELECT l.ID_LM,l.name,m.ID_STT,n.ID_N FROM lop_mon AS l
                LEFT JOIN hocsinh_mon AS m ON m.ID_HS='$hsID' AND m.ID_LM=l.ID_LM
                LEFT JOIN hocsinh_nghi AS n ON n.ID_HS='$hsID' AND n.ID_LM=l.ID_LM ORDER BY l.ID_MON ASC,l.ID_LM ASC";
            $resultx=mysqli_query($db,$queryx);
            while($datax=mysqli_fetch_assoc($resultx)) {
                if(isset($datax["ID_STT"])) {
                    if(isset($datax["ID_N"])) {
                        echo"<ol style='border-top:1px solid rgba(0,0,0,0.15);' ";if($lmID!=$datax["ID_LM"]){echo" class='li-con'";} echo"><a href='javascript:void(0)' class='nghi_mon' title='Môn $datax[name]'>$datax[name]</a></ol>";
                    } else {
                        echo"<ol style='border-top:1px solid rgba(0,0,0,0.15);' ";if($lmID!=$datax["ID_LM"]){echo" class='li-con'";} echo"><a href='http://localhost/www/TDUONG/mobile/chon-mon/$datax[ID_LM]/' title='Môn $datax[name]'>$datax[name]</a></ol>";
                    }
                } else {
                    echo"<ol style='border-top:1px solid rgba(0,0,0,0.15);' ";if($lmID!=$datax["ID_LM"]){echo" class='li-con'";} echo"><a href='javascript:void(0)' class='access_mon' title='Môn $datax[name]'>$datax[name]</a></ol>";
                }
            }
            echo"</ul>
            </li>";
            $tb_num=count_thong_bao_hs($hsID,$lmID);
            if($tb_num>=1) {
                echo"<li style='position:relative;background:#FFF;'>
					<a href='http://localhost/www/TDUONG/mobile/thong-bao/' style='color:yellow' title='Thông báo'><i class='fa fa-bell' style='font-size:14px;'></i></a>
					<p style='position:absolute;right:35%;color:#FFF;background:red;border-radius:1000px;width:20px;height:20px;font-size:12px;line-height:22px;top:0;'>$tb_num</p>
				</li>";
            } else {
                echo"<li style='position:relative;background:#FFF;opacity:0.15;'><a href='http://localhost/www/TDUONG/mobile/thong-bao/' title='Thông báo'><i class='fa fa-bell' style='font-size:14px;'></i></a></li>";
            }
            echo"<li style='background:#FFF;' class='li-con'><a href='http://localhost/www/TDUONG/mobile/dang-xuat/' title='Đăng xuất'><i class='fa fa-sign-out' style='font-size:14px;'></i></a></li>";
        }
    }

    if(isset($_POST["date_in"]) && isset($_POST["date_start"]) && isset($_POST["dem_begin"])) {
        $date_in=$_POST["date_in"];
        $date_start=$_POST["date_start"];
        $dem_begin=$_POST["dem_begin"];

        $lido=array();
        $result5=get_all_lido2();
        while($data5=mysqli_fetch_assoc($result5)) {
            $lido[$data5["ID_LD"]]=$data5["name"];
        }

        echo"<tr id='table-head' class='back''>";
            $query9="SELECT * FROM buoikt WHERE ngay>'$date_start' AND ID_MON='$monID' ORDER BY ID_BUOI ASC lIMIT $dem_begin,10";
            $result9=mysqli_query($db,$query9);
            while($data9=mysqli_fetch_assoc($result9)) {
                echo"<th><span>".format_date($data9["ngay"])."</span></th>";
            }
        echo"</tr>
        <tr id='table-point' class='back'>";
            $date=date_create($date_in);
            $query2="SELECT d.*,b.ngay FROM buoikt AS b 
                LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID' 
                WHERE b.ngay>'$date_start' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC LIMIT $dem_begin,10";
            $result2=mysqli_query($db,$query2);
            while($data2=mysqli_fetch_assoc($result2)) {
                if(isset($data2["ID_DIEM"])) {
                    $ngay = date_create($data2["ngay"]);
                    if ($ngay < $date) {
                        echo "<td><span>Chưa học</span></td>";
                    } else {
                        if ($data2["loai"] == 0 || $data2["loai"] == 1 || $data2["loai"] == 3) {
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
        echo"</tr>";
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

	if(isset($_POST["monID0"]) && isset($_POST["lmID"])) {
	    $monID=$_POST["monID0"];
        $lmID=$_POST["lmID"];
        $buoi_arr="";
        $query="SELECT ID_BUOI FROM buoikt WHERE ID_MON='$monID' ORDER BY ID_BUOI DESC LIMIT 10";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $buoi_arr.=",'$data[ID_BUOI]'";
        }
        $buoi_arr=substr($buoi_arr,1);
        $count=0;
        $result=get_chuyende_dad2($lmID);
        $me2 = "";
        while($data=mysqli_fetch_assoc($result)) {
            $me2.="<div class='main-div hideme animated bounceInUp'>
						<div class='main-1 back'><h3>Tỉ lệ phần trăm kiến thức<br />nắm được phần $data[title]</h3></div>
						<div class='main-chart3 back'>
							<ul>";
            $temp_cd=0;
            if(!isset($_SESSION["chuyende2-".$data["ID_CD"]])) {
                $str="";
                $query5 = "SELECT c.ID_CD,c.title,d.diem FROM chuyende AS c
                        INNER JOIN chuyende_diem AS d ON d.ID_LM=c.ID_LM AND d.ID_BUOI IN ($buoi_arr) AND d.ID_CD=c.ID_CD AND d.ID_HS='$hsID' AND d.diem NOT LIKE 'X/%' AND d.diem NOT lIKE '%/0'
                        WHERE c.ID_LM='$lmID' AND c.dad='$data[ID_CD]' AND c.del='1'
                        ORDER BY c.ID_CD DESC,d.ID_STT ASC";
                $result5 = mysqli_query($db, $query5);
                $num = mysqli_num_rows($result5);
                $total = 0;
                $dem = 0;
                $num_count = 0;
                $me = $temp_name = "";
                while ($data5 = mysqli_fetch_assoc($result5)) {
                    if (($temp_cd != 0 && $temp_cd != $data5["ID_CD"]) || $num_count == $num - 1) {
                        if ($dem == 0) {
                            $diemtb = 0;
                        } else {
                            $diemtb = ($total / $dem) * 100;
                        }
                        $indexLabel = format_phantram($diemtb);
                        $str.= "<li><p><span style='width: 80%;float:left;'>$temp_name</span><span style='float:right;'>$indexLabel</span></p><div class='clear'></div></li>";
                        $total = 0;
                        $dem = 0;
                    } else {
                        $temp = explode("/", $data5["diem"]);
                        $total += $temp[0];
                        $dem += $temp[1];
                    }
                    $temp_cd = $data5["ID_CD"];
                    $temp_name = $data5["title"];
                    $num_count++;
                }
                $me2.=$str;
                $_SESSION["chuyende2-".$data["ID_CD"]] = $str;
            } else {
                $me2.= $_SESSION["chuyende2-".$data["ID_CD"]];
            }
            $me2.="</ul>
						</div>
					</div>";
            $count++;
        }
        echo $me2;
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
                echo"<p>$data[fullname]";
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

    if(isset($_POST["fb_edit"]) && isset($_POST["nID_edit"])) {
        $fb=$_POST["fb_edit"];
        $nID=decode_data($_POST["nID_edit"],$code);
        if(valid_id($nID)) {
            $query="UPDATE game_group SET facebook='$fb' WHERE ID_N='$nID'";
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
                    if ($id != 0) {
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

    if(isset($_POST["sttID"])) {
        $sttID=decode_data($_POST["sttID"],md5("123456"));
        $query="DELETE FROM diemdanh_nghi WHERE ID_STT='$sttID'";
        mysqli_query($db,$query);
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>