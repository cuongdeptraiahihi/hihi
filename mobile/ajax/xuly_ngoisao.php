<?php
	ob_start();
	//session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];

    if(isset($_POST["ngoisao"])) {
        $tien=$_POST["ngoisao"];
        if(is_numeric($tien) && $tien > 0 && $tien < 100000) {
            $taikhoan=get_tien_hs($hsID);
            if($taikhoan>=$tien) {
                $next_CN=get_next_CN();
                if(check_exited_ngoisao($hsID, $next_CN, $lmID)) {
                    echo"Bạn đã chọn ngôi sao hy vọng cho bài kiểm tra tới! Bạn chỉ có thể chọn 1 lần trong 1 tuần!";
                } else {
                    place_ngoisao_hs($hsID, $next_CN, $tien, $lmID);
                    $id=mysqli_insert_id($db);
                    add_thong_bao_hs($hsID,$id,"Bạn đã chọn ngôi sao hy vọng cho ngày ".format_dateup($next_CN).", với ".format_price($tien)." tiền cược","ngoi-sao",$lmID);
                    tru_tien_hs($hsID, $tien, "Trừ tiền cược chọn ngôi sao hy vọng cho buổi kiểm tra ngày ".format_dateup($next_CN),"","");
                    echo"Bạn đã chọn ngôi sao hy vọng thành công cho tuần tới!";
                }
            } else {
                echo"Tài khoản bạn có ít hơn ".$tien.", không đủ để chọn ngôi sao hy vọng!";
            }
        } else {
            echo"Số tiền không hợp lệ! Phải bé hơn hoặc bằng 100.000đ";
        }
    }
	
	/*if(isset($_POST["nsID"])) {
		$nsID=base64_decode($_POST["nsID"]);
		if(is_numeric($nsID)) {
			delete_ngoisao($hsID, $nsID);
			echo"";
		} else {
			echo"Oops";
		}
	}*/
	
	require("../../model/close_db.php");
	ob_end_flush();
?>