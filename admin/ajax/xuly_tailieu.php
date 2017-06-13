<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	
	if(isset($_POST["tlID0"])) {
		$tlID=$_POST["tlID0"];
		delete_tailieu($tlID);
	}
	
	if(isset($_POST["cID"])) {
		$cID=base64_decode($_POST["cID"]);
		delete_comment($cID);
	}

	if(isset($_POST["sach_done"])) {
	    $sach=$_POST["sach_done"];
	    $result=get_order_detail($sach);
	    $data=mysqli_fetch_assoc($result);
	    add_thong_bao_hs($data["ID_HS"], $sach,"Đơn hàng mã #".$sach." của bạn đã hoàn thành!", "mua-sach", $data["ID_LM"]);
	    $query="UPDATE sach_mua SET status='1' WHERE ID_STT='$sach'";
	    mysqli_query($db,$query);
    }

    if(isset($_POST["sach_back"])) {
        $sach=$_POST["sach_back"];
        $result=get_order_detail($sach);
        $data=mysqli_fetch_assoc($result);
        add_thong_bao_hs($data["ID_HS"], $sach,"Đơn hàng mã #".$sach." của bạn đã chuyển trạng thái về chưa hoàn thành!", "mua-sach", $data["ID_LM"]);
        $query="UPDATE sach_mua SET status='0' WHERE ID_STT='$sach'";
        mysqli_query($db,$query);
    }

    if(isset($_POST["sach_xoa"])) {
        $sach=$_POST["sach_xoa"];
        $result=get_order_detail($sach);
        $data=mysqli_fetch_assoc($result);
        cong_tien_hs($data["ID_HS"], $data["total"], "Hoàn tiền đơn hàng mã #".$sach, "mua-sach", $sach);
        $query="DELETE FROM sach_mua WHERE ID_STT='$sach'";
        mysqli_query($db,$query);
        add_thong_bao_hs($data["ID_HS"], $sach,"Đơn hàng mã #".$sach." của bạn đã bị hủy và hoàn lại tiền!", "mua-sach", $data["ID_LM"]);
    }

	require("../../model/close_db.php");
	ob_end_flush();
?>