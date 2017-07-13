<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if (isset($_POST["maso"]) && isset($_POST["date"]) && isset($_POST["lmID"])) {
		$maso=$_POST["maso"];
		$date=$_POST["date"];
		$lmID=$_POST["lmID"];
		$hsID=get_hs_id($maso);
		if($hsID) {
			$check=check_exited_nghihoc($hsID,$lmID);
			if($check) {
				echo"Học sinh này đã nghỉ học";
			} else {
				add_nghihoc2($hsID, $date, $lmID);
				remove_hs_all_ca($hsID,$lmID,get_mon_of_lop($lmID));
				echo"ok";
			}
		} else {
			echo"Không tồn tại học sinh này!";
		}
	}
	
	if (isset($_POST["nID"])) {
		$nID=$_POST["nID"];
		delete_nghihoc($nID);
	}
	
	if (isset($_POST["sttID"])) {
		$sttID=$_POST["sttID"];
		delete_nghidai($sttID);
	}

	// Đã check
    if (isset($_POST["hsID2"]) && isset($_POST["lmID2"])) {
        $hsID=$_POST["hsID2"];
        $lmID=$_POST["lmID2"];
        delete_nghidai2($hsID,$lmID);
    }

	// Đã check
	if (isset($_POST["hsID0"]) && isset($_POST["date_start"]) && isset($_POST["date_end"]) && isset($_POST["note"]) && isset($_POST["lmID0"])) {
		$hsID=$_POST["hsID0"];
		$start=format_date_o($_POST["date_start"]);
		$end=format_date_o($_POST["date_end"]);
		$note=$_POST["note"];
        $lmID=$_POST["lmID0"];
        $monID=get_mon_of_lop($lmID);
        if($end=="0000-00-00") {
            $loai=1;
            $query2="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE date>='$start' AND ID_LM='$lmID' AND ID_MON='$monID'";
        } else {
            $loai=0;
            $query2="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE date>='$start' AND date<='$end' AND ID_LM='$lmID' AND ID_MON='$monID'";
        }
		add_nghidai($hsID,$start,$end,$note,$loai,$lmID);
        $result2=mysqli_query($db,$query2);
        while($data2=mysqli_fetch_assoc($result2)) {
            $query3="DELETE FROM diemdanh WHERE ID_DD='$data2[ID_STT]' AND ID_HS='$hsID'";
            mysqli_query($db,$query3);
            $query3="DELETE FROM diemdanh_nghi WHERE ID_CUM='$data2[ID_CUM]' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
            mysqli_query($db,$query3);
            insert_diemdanh_nghi($data2["ID_CUM"],$hsID,1,$lmID,$monID);
        }
		echo"ok";
	}

	// Đã check
    if (isset($_POST["hsID0"]) && isset($_POST["date_thang"]) && isset($_POST["note"]) && isset($_POST["lmID0"])) {
        $hsID=$_POST["hsID0"];
        $date=format_date_o($_POST["date_thang"]);
        $note=$_POST["note"];
        $lmID=$_POST["lmID0"];
        add_nghidai($hsID,"$date-01","$date-".get_last_day($date),$note,0,$lmID);
        $monID=get_mon_of_lop($lmID);
        $query2="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE date>='$date-01' AND date<='$date-".get_last_day($date)."' AND ID_LM='$lmID' AND ID_MON='$monID'";
        $result2=mysqli_query($db,$query2);
        while($data2=mysqli_fetch_assoc($result2)) {
            $query3="DELETE FROM diemdanh_toan WHERE ID_DD='$data2[ID_STT]' AND ID_HS='$hsID'";
            mysqli_query($db,$query3);
            $query3="DELETE FROM diemdanh_nghi WHERE ID_CUM='$data2[ID_CUM]' AND ID_HS='$hsID' AND ID_LM='$lmID' AND ID_MON='$monID'";
            mysqli_query($db,$query3);
            insert_diemdanh_nghi($data2["ID_CUM"],$hsID,1,$lmID,$monID);
        }
        echo"ok";
    }
	
	if (isset($_POST["sttID0"]) && isset($_POST["date_start"]) && isset($_POST["date_end"]) && isset($_POST["note"])) {
		$sttID=$_POST["sttID0"];
		$start=format_date_o($_POST["date_start"]);
		$end=format_date_o($_POST["date_end"]);
		$note=$_POST["note"];
		edit_nghidai($sttID,$start,$end,$note);
        $query="SELECT ID_HS,ID_MON FROM nghi_temp WHERE ID_STT='$sttID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        $hsID=$data["ID_HS"];
        $monID=$data["ID_MON"];
        $lopID=get_lop_hs($hsID);
        $query2="SELECT ID_STT,ID_CUM FROM diemdanh_buoi WHERE date>='$start' AND date<='$end' AND ID_LOP='$lopID' AND ID_MON='$monID'";
        $result2=mysqli_query($db,$query2);
        while($data2=mysqli_fetch_assoc($result2)) {
            $query3="DELETE FROM diemdanh_toan WHERE ID_DD='$data2[ID_STT]' AND ID_HS='$hsID'";
            mysqli_query($db,$query3);
            $query3="DELETE FROM diemdanh_nghi WHERE ID_CUM='$data2[ID_CUM]' AND ID_HS='$hsID' AND ID_LOP='$lopID' AND ID_MON='$monID'";
            mysqli_query($db,$query3);
        }
	}

	// Đã check
    if(isset($_POST["hsID"]) && isset($_POST["nID0"]) && isset($_POST["date_out"]) && isset($_POST["note"])) {
        $hsID=$_POST["hsID"];
        $nID=$_POST["nID0"];
        $date_out=format_date_o($_POST["date_out"]);
        $note=$_POST["note"];
        edit_nghihoc($nID,$date_out);
        edit_note_hocsinh($hsID,$note);
        update_hot_hs($hsID,1);
        if($note != "" && $note != " " && $note != "-" && $note != "- ") {
            add_options2(date("Y-m-d H:i:s"), "cap-nhat-note", "", $hsID);
        }
    }

    // Đã check
    if(isset($_POST["hsID1"]) && isset($_POST["note1"])) {
        $hsID=$_POST["hsID1"];
        $note=$_POST["note1"];
        edit_note_hocsinh($hsID,$note);
        update_hot_hs($hsID,1);
        if($note != "" && $note != " " && $note != "-" && $note != "- ") {
            add_options2(date("Y-m-d H:i:s"), "cap-nhat-note", "", $hsID);
        }
    }

    // Đã check
    if(isset($_POST["hsID_his"]) && isset($_POST["lmID_his"]) && isset($_POST["monID_his"])) {
        $hsID=$_POST["hsID_his"];
        $lmID=$_POST["lmID_his"];
        $monID=$_POST["monID_his"];
        if($lmID==0) {$lmID=get_mon_first_hs($hsID,$monID);}
        echo get_history_nghi($hsID,get_date_in_hs($hsID,$lmID),$lmID,$monID);
    }

    // Đã check
    if(isset($_POST["sttID1"]) && isset($_POST["nhan"])) {
        $sttID=$_POST["sttID1"];
        $nhan=$_POST["nhan"];
        update_nhan_tin_nghi($sttID,$nhan);
        echo"ok";
    }

    // Đã check
    if(isset($_POST["sttID1"]) && isset($_POST["xacnhan"])) {
        $sttID=$_POST["sttID1"];
        $xacnhan=$_POST["xacnhan"];
        update_xac_nhan_nghi($sttID,$xacnhan);
        echo"ok";
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>