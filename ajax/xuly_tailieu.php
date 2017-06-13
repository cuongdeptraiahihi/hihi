<?php
	ob_start();
	//session_start();
	require("../model/open_db.php");
	require("../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
	$hsID=$_SESSION["ID_HS"];
	
	if(isset($_POST["tlID"]) && isset($_POST["text"])) {
		$tlID=base64_decode($_POST["tlID"]);
		$text=$_POST["text"];
		if(valid_text($text)) {
			add_tailieu_cmt($hsID, $tlID, $text);
			$result=get_lastest_cmt($tlID);
			$data=mysqli_fetch_assoc($result);
			echo"<li>
				<div class='cmt-img'><img src='https://localhost/www/TDUONG/images/".get_avata_hs($data["ID_HS"])."'/></div>
				<div class='cmt-con'><p>$data[content]</p></div>
				<div class='cmt-send'><span class='cmt-time'>".get_past_time($data["datetime"])."</span></div>
			</li>
			<div class='clear'></div>";
		} else {
			echo"fuck";
		}
	}
	
	if(isset($_POST["tlID0"])) {
		$tlID=base64_decode($_POST["tlID0"]);
		if(!check_tailieu_like($hsID,$tlID)) {
			add_tailieu_like($hsID,$tlID);
		}
		echo count_tailieu_like($tlID);
	}

    if(isset($_POST["sach_xoa"])) {
        $sach=decode_data($_POST["sach_xoa"], md5("123456"));
        if(valid_id($sach)) {
            $result = get_order_detail($sach);
            $data = mysqli_fetch_assoc($result);
            cong_tien_hs($data["ID_HS"], $data["total"], "Hoàn tiền đơn hàng mã #" . $sach, "mua-sach", $sach);
            $query = "DELETE FROM sach_mua WHERE ID_STT='$sach'";
            mysqli_query($db, $query);
            echo"ok";
        } else {
            echo"none";
        }
    }
	
	require("../model/close_db.php");
	ob_end_flush();
?>