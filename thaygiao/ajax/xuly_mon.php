<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");

    // Đã check
	if (isset($_POST["lmID"])) {
		$lmID=$_POST["lmID"];
		$_SESSION["lmID"]=$lmID;
        $monID=get_mon_of_lop($lmID);
        $_SESSION["mon"]=$monID;
		$result=get_mon_info($monID);
		$data=mysqli_fetch_assoc($result);
		$_SESSION["thang"]=$data["thang"];
	}
	
	if(isset($_POST["monID2"]) && isset($_POST["lmID2"]) && isset($_POST["ngay"])) {
		$monID=$_POST["monID2"];
		$lmID=$_POST["lmID2"];
		$ngay=$_POST["ngay"];
		$thu=date("w", strtotime($ngay))+1;
		$result2=get_ca_base_thu2($thu,$lmID,$monID);
		$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
		echo"<option value='0'>Chọn ca hiện hành</option>";
		while($data2=mysqli_fetch_assoc($result2)) {
			if($data2["link"]!=0) {
				//echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]' data-quyen='0'>".$thu_string[$thu-1].", ca $data2[gio] (Tăng cường)</option>";
			} else {
				echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]' data-quyen='1'>".$thu_string[$thu-1].", ca $data2[gio]</option>";
			}
		}
	}
	
	if(isset($_POST["monID0"]) && isset($_POST["name"]) && isset($_POST["thang"]) && isset($_POST["tien"]) && isset($_POST["tienID"]) && isset($_POST["is_phat"]) && isset($_POST["is_tinh"]) && isset($_POST["nhayde"]) && isset($_POST["auto"])) {
		$monID=$_POST["monID0"];
		$name=$_POST["name"];
		$thang=$_POST["thang"];
		$tien=$_POST["tien"];
		$tienID=$_POST["tienID"];
        $is_phat=$_POST["is_phat"];
        $is_tinh=$_POST["is_tinh"];
        $nhayde=$_POST["nhayde"];
        $auto=$_POST["auto"];
		edit_mon($monID, $name, $thang, $is_phat, $is_tinh, $nhayde, $auto);
		$name_temp=unicode_convert($name);
		if($tienID==0) {
			add_muctien("tien_hoc_".$name_temp, "Mức tiền môn $name", $tien);
		} else {
			edit_muctien($tienID, "", $tien);
		}
	}

    if(isset($_POST["lmID0"]) && isset($_POST["name"]) && isset($_POST["date_in"])) {
        $lmID=$_POST["lmID0"];
        $name=$_POST["name"];
        $date_in=$_POST["date_in"];
        edit_lop_mon($lmID, $name, $date_in);
    }
	
	if(isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["name"]) && isset($_POST["loai"]) && isset($_POST["hs"])) {
		$title=$_POST["title"];
	    $content=$_POST["content"];
		$name=$_POST["name"];
        $loai=$_POST["loai"];
        $hs=$_POST["hs"];
        if($hs=="") {
            add_note($title,$content,$name,0,$loai,0);
        } else {
            $hsID=get_hs_id($hs);
            add_note($title,$content,$name,0,$loai,$hsID);
        }
	}

    if(isset($_POST["title3"]) && isset($_POST["content3"]) && isset($_POST["name3"])) {
        $title=$_POST["title3"];
        $content=$_POST["content3"];
        $name=$_POST["name3"];
        add_options3($content,"idea",$title,$name);
    }

	if(isset($_POST["content2"]) && isset($_POST["hsID2"]) && isset($_POST["nID2"])) {
	    $content=$_POST["content2"];
        $hsID=$_POST["hsID2"];
        $nID=$_POST["nID2"];
        if($nID!=0) {
            update_note_all($nID, $content);
        } else {
            $nID = add_note("", $content, "Thầy Dương", 0, 0, $hsID);
        }
        echo $nID;
    }

    if(isset($_POST["hsID_special"])) {
        $hsID=$_POST["hsID_special"];
        $result=get_special_note($hsID);
        $data=mysqli_fetch_assoc($result);
        $note=get_note_hs($hsID);
        if($data["content"]!="" && $data["content"]!=" ") {
            $note .= "<br />- " . date("d/m/Y") . ": " . $data["content"];
            edit_note_hocsinh($hsID, $note);
        }
        $query="UPDATE note SET status='1' WHERE ID_HS='$hsID'";
        mysqli_query($db,$query);
    }
	
	if(isset($_POST["nID"]) && isset($_POST["status"])) {
		$nID=$_POST["nID"];
		$status=$_POST["status"];
		update_note($nID,$status);
	}
	
	if(isset($_POST["nID0"])) {
		$nID=$_POST["nID0"];
		delete_note($nID);
	}
	
	if(isset($_POST["action"])) {
		$action=$_POST["action"];
		if($action=="note-list") {
			$result=get_note(0,10);
			$dem=0;
			while($data=mysqli_fetch_assoc($result)) {
			    if($data["ID_HS"]!=0) {
			        $hs = "<strong>".get_cmt_hs($data["ID_HS"])."</strong>";
                } else {
                    $hs = "";
                }
			    if($data["level"]==1) {
			        echo"<tr style='background:#D1DBBD;'>";
                } else {
                    echo"<tr>";
                }
				echo"<td style='text-align:left;' data-nID='$data[ID_N]'><span><strong>$data[title]</strong><br />".nl2br($data["content"])."</span></td>
					<td><span>$data[name]</span></td>";
					if($data["status"]==1) {
						echo"<td><span><i class='fa fa-check-square-o' data-nID='$data[ID_N]'></i></span></td>";
					} else {
						echo"<td><span><i class='fa fa-square-o' data-nID='$data[ID_N]'></i></span></td>";
					}
				echo"</tr>";
				$dem++;
			}
			if($dem==0) {
				echo"<tr><td colspan='3'><span>Bạn đã hoàn thành hết các công việc!</span></td></tr>";
			}
		} else if($action=="idea-list") {
		    $result=get_options_all("idea",3);
            $dem=0;
            while($data=mysqli_fetch_assoc($result)) {
                echo"<tr>
                    <td style='text-align:left;' data-oID='$data[ID_O]'><strong>$data[note]</strong><br /><span>".nl2br($data["content"])."</span></td>
                    <td><span><i class='fa fa-close' data-oID='$data[ID_O]'></i></span></td>
                </tr>";
                $dem++;
            }
            if($dem==0) {
                echo"<tr><td colspan='2'><span>Bạn không có ý tưởng gì cả!</span></td></tr>";
            }
        }
	}

	if(isset($_POST["oID0"])) {
	    $oID=$_POST["oID0"];
        delete_options($oID);
    }

	if(isset($_POST["content"]) && isset($_POST["lmID3"])) {
	    $content=$_POST["content"];
        $lmID=$_POST["lmID3"];
        add_thong_bao_hs(0,0,$content,"all",$lmID);
        echo"ok";
    }

    if(isset($_POST["tbID"])) {
        $tbID=$_POST["tbID"];
        delete_thongbao2($tbID);
    }

    if(isset($_POST["tbID1"]) && $_POST["content"]) {
        $tbID=$_POST["tbID1"];
        $content=base64_decode($_POST["content"]);
        edit_thongbao($tbID,$content);
    }

    if(isset($_POST["tbID2"]) && isset($_POST["how"])) {
        $tbID=$_POST["tbID2"];
        $status=$_POST["how"];
        change_status_thongbao($tbID,$status);
    }

    if(isset($_POST["lmID4"])) {
        $lmID=$_POST["lmID4"];
        $dem=0;
        $result=get_hoc_sinh_kem($lmID);
        for($i=0;$i<count($result);$i++) {
            echo"<tr>
                <th style='background: #3E606F;width:5%;' class='hidden'><span>".($i+1)."</span></th>
                <th style='background: #EF5350;width:10%;min-width:70px;'><span><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/ma/".$result[$i]["cmt"]."/' target='_blank'>".$result[$i]["cmt"]."</a></span></th>
                <th style='background: #EF5350;width:15%;min-width:150px;'><span><a href='".$result[$i]["facebook"]."' target='_blank'>".$result[$i]["fullname"]."</a></th>
                <td><span>".$result[$i]["content"]."</span></td>
            </tr>";
            $dem++;
            if($dem==20) {break;}
        }
        echo"<tr></tr>";
    }

    if(isset($_POST["note_load"])) {
        $dem=$_POST["note_load"];
        $lmID=$_SESSION["lmID"];
        $result=get_list_note($dem,10);
        while($data=mysqli_fetch_assoc($result)) {
            if($data["has"]!="") {
                $day="<b>(".format_date($data["has"]).")</b>";
            } else {
                $day="";
            }
            echo "<tr>
                <th style='background: #3E606F;width:5%;' class='hidden'><span>" . ($dem + 1) . "</span></th>
                <th style='background: #EF5350;width:10%;'><span><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/ma/$data[maso]/' target='_blank'>$data[maso]</a></span></td>
                <th style='background: #EF5350;width:15%;'><span><a href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>$data[fullname]</a></span></td>
                <td style='text-align:left;padding-left:15px;position:relative;cursor:pointer;' class='view-all' data-hsID='$data[ID_HS]'>
                    <span>$day " . nl2br($data["note"]) . "</span>
                    <div class='td-popup'>
                        
                    </div>";
            if ($data["hot"] == 1) {
                if($data["has"]!="") {
                    echo "<span class='note-count check-chuy is_chuy' data-nID='$data[ID]'>NEW</span>";
                } else {
                    echo "<span class='note-count check-hot is_chuy' data-hsID='$data[ID]'>NEW</span>";
                }
            }
            echo"</td>";
            echo"</tr>";

//            if($data["hot"]==1) {
//                echo "<tr style='background:orange;'>
//                    <th class='hidden'><span>" . ($dem + 1) . "</span></th>
//                    <th><span><a href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>$data[fullname]</a><br /><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/ma/$data[maso]/' target='_blank'>$data[maso]</a></span></th>
//                    <th style='text-align:left;padding-left:15px;'><span>$day " . nl2br($data["note"]) . "</span></th>";
//                if($data["has"]!="") {
//                    echo"<th><input type='submit' style='background:red;' class='submit check-chuy is_chuy' data-nID='$data[ID]' value='Lưu ý' /></th>";
//                } else {
//                    echo"<th><input type='submit' style='background:red;' class='submit check-hot is_chuy' data-hsID='$data[ID]' value='Lưu ý' /></th>";
//                }
//                echo"</tr>";
//            } else {
//                echo "<tr>
//                    <td class='hidden'><span>" . ($dem + 1) . "</span></td>
//                    <td><span><a href='" . formatFacebook($data["facebook"]) . "' target='_blank' class='link-face'>$data[fullname]</a><br /><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/ma/$data[maso]/' target='_blank'>$data[maso]</a></span></td>
//                    <td style='text-align:left;padding-left:15px;'><span>$day " . nl2br($data["note"]) . "</span></td>";
//                if($data["has"]!="") {
//                    echo"<th><input type='submit' style='background:cyan;border:none;opacity:0.4' class='submit check-chuy' data-nID='$data[ID]' value='Lưu ý' /></th>";
//                } else {
//                    echo"<th><input type='submit' style='background:cyan;border:none;opacity:0.4' class='submit check-hot' data-hsID='$data[ID]' value='Lưu ý' /></th>";
//                }
//                echo"</tr>";
//            }
            $dem++;
        }
        if($dem == $_POST["note_load"]) {
            echo"none";
        }
    }

    if(isset($_POST["note_edit"]) && isset($_POST["nID3"])) {
        $content=$_POST["note_edit"];
        $nID=$_POST["nID3"];
        update_note_all($nID, $content);
        echo nl2br($content);
    }

    if(isset($_POST["idea_edit"]) && isset($_POST["oID3"])) {
        $content=$_POST["idea_edit"];
        $oID=$_POST["oID3"];
        update_options($content,$oID);
        echo nl2br($content);
    }

    if(isset($_POST["anh_flickr"])) {
        $anh=$_POST["anh_flickr"];
        require_once("../../model/Flick.php");
        $result_data = "";
        $f = new Flick("bc196e763e5476b218d50fc79fd7f278", "c73a724daf1b18fa", "http://localhost/www/TDUONG/game/callback.php");
        $photo_respones = $f->request("flickr.photos.getSizes", array("photo_id" => $anh));
        $photo_respones = json_decode($photo_respones, true);
        if(isset($photo_respones["stat"]) && $photo_respones["stat"] == "ok") {
            $photo = $photo_respones["sizes"]["size"];
            $m = count($photo);
            for($j = 0; $j < $m; $j++) {
                if($photo[$j]["label"] == "Original") {
                    $result_data = "<img style='width:100%;max-width:500px;' src='".$photo[$j]["source"]."' />";
                    break;
                }
            }
        }
        echo $result_data;
    }

    if(isset($_POST["vong_show"])) {
        $id = $_POST["vong_show"];
        $query="UPDATE game_level SET status='1' WHERE ID_STT='$id'";
        mysqli_query($db, $query);
    }

    if(isset($_POST["vong_hide"])) {
        $id = $_POST["vong_hide"];
        $query="UPDATE game_level SET status='0' WHERE ID_STT='$id'";
        mysqli_query($db, $query);
    }

    if(isset($_POST["vong_delete"])) {
        $id = $_POST["vong_delete"];
        $query="DELETE FROM game_level WHERE ID_STT='$id'";
        mysqli_query($db, $query);
    }

    if(isset($_POST["vong_reset"])) {
        $query="DELETE FROM game_unlock";
        mysqli_query($db, $query);
    }

    if(isset($_POST["table_order"])) {
        $ajax=$_POST["table_order"];
        $ajax=json_decode($ajax,true);
        $n=count($ajax);
        for($i=0;$i<$n;$i++) {
            $query="UPDATE game_level SET level='".$ajax[$i]["stt"]."' WHERE ID_STT='".$ajax[$i]["id"]."'";
            mysqli_query($db, $query);
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>