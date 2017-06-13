<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("access_hocsinh.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
    $lmID=$_SESSION["lmID"];
	$monID=$_SESSION["mon"];
	$me=md5("123456");

	$error_code = 0;
	$sl = 1;
	if(isset($_POST["mua-sach"])) {
	    if (isset($_POST["sl-sach"]) && is_numeric($_POST["sl-sach"]) && $_POST["sl-sach"] > 0) {
	        $sl = addslashes($_POST["sl-sach"]);
        }
	    if (isset($_POST["id-sach"])) {
	        $id = decode_data(addslashes($_POST["id-sach"]), $me);
	        $result=get_sach_detail($id);
	        if(mysqli_num_rows($result) != 0) {
                $data = mysqli_fetch_assoc($result);
                $gia = $data["tien"] - ($data["tien"]*$data["discount"]/100);
                $tien = get_tien_hs($hsID);
                if ($tien >= $gia*$sl) {
                    tru_tien_hs($hsID, $gia*$sl, "Trừ tiền mua sách $data[name]", "mua-sach", $id);
                    $idS = add_mua_sach($hsID, $id, $gia, $sl, $gia*$sl);
                    add_thong_bao_hs($hsID, $idS, "Bạn đã mua $sl cuốn sách $data[name], tổng cộng ".format_money_vnd($gia*$sl)." Hãy lên gặp thầy để nhận sách nhé! Mã đơn hàng #".$idS, "mua-sach", $lmID);
                    $error_code = 200;
                } else {
                    $error_code = 102;
                }
            } else {
	            $error_code = 103;
            }
        } else {
            $error_code = 100;
        }
    } else {
	    $error_code = 101;
    }

    header("location:https://localhost/www/TDUONG/shop/$error_code/");
	exit();
	
	ob_end_flush();
	require_once("model/close_db.php");

?>