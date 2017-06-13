<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	require("../access_admin.php");
	$monID=$_SESSION["mon"];
	
	if (isset($_POST["idRA"]) && isset($_POST["note"]) && isset($_POST["price"])) {
		$idRA=$_POST["idRA"];
		$note=$_POST["note"];
		$price=$_POST["price"];
        $price=str_replace( ".", "",$price);
        $price=str_replace( "đ", "",$price);
        $price=str_replace( "d", "",$price);
		edit_thuong($idRA, $note, $price);
	}
	
	if (isset($_POST["idRA2"])) {
		$idRA=$_POST["idRA2"];
		delete_thuong($idRA);
	}
	
	if (isset($_POST["idVAO"]) && isset($_POST["note"]) && isset($_POST["price"])) {
		$idVAO=$_POST["idVAO"];
		$note=$_POST["note"];
        $price=$_POST["price"];
        $price=str_replace( ".", "",$price);
        $price=str_replace( "đ", "",$price);
        $price=str_replace( "d", "",$price);
		edit_phat($idVAO, $note, $price);
	}
	
	if (isset($_POST["idVAO2"])) {
		$idVAO=$_POST["idVAO2"];
		delete_phat($idVAO);
	}
	
	if (isset($_POST["hsID"])) {
		$hsID=$_POST["hsID"];
		$thuong=get_thuong_hs($hsID);
		$phat=get_phat_hs($hsID);
		$con=$thuong-$phat;
		$result=get_hs_short_detail2($hsID);
		$data=mysqli_fetch_assoc($result);
		echo"<tr>
			<td class='big-td'><span>Họ và tên</span></td>
			<td colspan='2'><span>$data[fullname] - CMT: $data[cmt]</span></td>
			<td class='big-td'><span>Ngày sinh</span></td>
			<td colspan='2'><span>".format_dateup($data["birth"])."</span></td>
		</tr>
		<tr>
			<td class='big-td'><span>Số tiền cộng</span></td>
			<td><a href='http://localhost/www/TDUONG/admin/thuong/$hsID/'>Chi tiết</a></td>
			<td><span>".format_price($thuong)."</span></td>
			<td class='big-td'><span>Số tiền trừ</span></td>
			<td><a href='http://localhost/www/TDUONG/admin/phat/$hsID/'>Chi tiết</a></td>
			<td><span>".format_price($phat)."</span></td>
		</tr>
		<tr style='background:#3E606F'>
			<th colspan='6'><span>Tổng tài khoản</span></th>
		</tr>
		<tr>	
			<td colspan='6'><span>".format_price($con)."</span></td>
		</tr>";
	}

	if(isset($_POST["maso"]) && isset($_POST["content"])) {
	    $maso=$_POST["maso"];
        $content=$_POST["content"];
        $hsID=get_hs_id($maso);
        if(valid_maso($maso) && is_numeric($hsID) && $hsID!=0) {
            add_log($hsID,$content,"the-mien-phat");
        }
    }

    if(isset($_POST["sttID"])) {
        $sttID=$_POST["sttID"];
        delete_log2($sttID);
    }

    if(isset($_POST["hsID2"]) && isset($_POST["note2"]) && isset($_POST{"price2"}) && isset($_POST{"action"})) {
        $hsID=$_POST["hsID2"];
        $note=$_POST["note2"];
        $price=$_POST["price2"];
        $price=str_replace( ".", "",$price);
        $price=str_replace( "đ", "",$price);
        $price=str_replace( "d", "",$price);
        $action=$_POST["action"];
        if($price%10000==0) {
            if($action=="thuong") {
                cong_tien_hs($hsID,$price,$note,"ad-nap-tien",0);
            } else if($action=="phat") {
                tru_tien_hs($hsID,$price,$note,"ad-rut-tien",0);
            } else {

            }
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>