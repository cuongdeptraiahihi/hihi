<?php
	ob_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
    header("Content-type: application/json");

    $ketqua = array(
        "STT" => 0,
        "Content" => "EMPTY"
    );

    if(isset($_GET["userid"]) &&
        isset($_GET["accesscode"]) &&
        isset($_GET["stt"]) &&
        isset($_GET["maso"]) &&
        isset($_GET["caID"]) &&
        isset($_GET["ngay"]) &&
        isset($_GET["lmID"]) &&
        isset($_GET["monID"])) {
        $id = addslashes($_GET["userid"]);
        $access = addslashes($_GET["accesscode"]);
        $stt = addslashes(($_GET["stt"]));
        $maso = addslashes($_GET["maso"]);
        $caID = addslashes($_GET["caID"]);
        $ngay = addslashes($_GET["ngay"]);
        $lmID = addslashes($_GET["lmID"]);
        $monID = addslashes($_GET["monID"]);

        $check = false;
        if($stt % 20 == 0) {
            $result = get_admin($id);
            if (mysqli_num_rows($result) != 0) {
                $data = mysqli_fetch_assoc($result);
                if (md5($id . "|" . $data["password"]) == $access) {
                    $check = true;
                }
            }
        } else {
            $check = true;
        }


        if ($check) {
            $temp=check_exited_buoi(0, $caID, $ngay, $lmID, $monID);
            if(count($temp)==0) {
                $temp=add_diemdanh_buoi(0, $caID, get_ca_cum($caID), $ngay, $lmID, $monID);
                diemdanh_nghi_dai($temp[1],$ngay,$lmID,$monID);
                $query="UPDATE diemdanh_nghi SET ID_CUM='$temp[1]' WHERE ngay='$ngay' AND ID_LM='$lmID' AND ID_MON='$monID'";
                mysqli_query($db,$query);
            }
            $ddID=$temp[0];
            $cumID=$temp[1];
            $hsID = get_hs_id($maso);
            if($hsID != 0) {
                if (!check_exited_diemdanh($cumID, $hsID, $lmID, $monID)) {
                    insert_diemdanh($ddID, $hsID, 0, 1, 0, 1);
                    delete_thongbao($hsID, $cumID, "nghi-hoc", $lmID);
                    add_thong_bao_hs($hsID, $cumID, "Bạn đã được điểm danh cụm học ngày " . get_cum_date($cumID, $lmID, $monID) . ". $content", "nghi-hoc", $lmID);
                    $ketqua["STT"]++;
                }
                $ketqua["Content"] = "OK";
            } else {
                $ketqua["Content"] = "HS_FAIL";
            }
        } else {
            $ketqua["Content"] = "ACCESS_FAIL";
        }
    }

    echo json_encode($ketqua);

	ob_end_flush();
	require_once("../model/close_db.php");
?>