<?php
    ob_start();
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // json response array
    $response = array("error" => FALSE);

    if (isset($_POST['hsID']) && isset($_POST["tdID"])) {

        // receiving the post params
        $hsID = $_POST["hsID"];
        $tdID = $_POST["tdID"];

        if(is_numeric($hsID) && $hsID > 0 && is_numeric($tdID) && $tdID > 0) {

            $stmt = $db->getThachdau($hsID,$tdID);
            $result = $stmt->get_result();
            $stmt->close();
            if ($result->num_rows != 0) {
                $data = $result->fetch_assoc();

                $lmID = $data["ID_LM"];

                $stmt = $db->getInfoHs($data["ID_HS"]);
                $result = $stmt->get_result();
                $stmt->close();
                $data2 = $result->fetch_assoc();

                $stmt = $db->getInfoHs($data["ID_HS2"]);
                $result = $stmt->get_result();
                $stmt->close();
                $data3 = $result->fetch_assoc();

                $buoiID = $db->getBuoiID($data["buoi"]);

                $response["error"] = FALSE;
                $response["id_td"] = $data["ID_STT"];
                $response["chap"] = $data["chap"];
                $response["buoi"] = $data["buoi"];
                $response["status"] = $data["status"];

                $stmt = $db->getDiemDetail($data["ID_HS"],$buoiID,$lmID);
                $result = $stmt->get_result();
                $stmt->close();

                $response["hsID"]["id_hs"] = $data["ID_HS"];
                $response["hsID"]["maso"] = $data2["cmt"];
                $response["hsID"]["ava"] = $data2["avata"];
                if($result->num_rows != 0) {
                    $data4 = $result->fetch_assoc();
                    $response["hsID"]["diem"] = $data4["diem"];
                    if ($data4["loai"] != 3) {
                        $response["hsID"]["loai"] = $db->getCmtLoai($data4["loai"]);
                    } else {
                        $response["hsID"]["loai"] = $db->getNoteMean($data4["note"]);
                    }
                } else {
                    $response["hsID"]["diem"] = "X";
                    $response["hsID"]["loai"] = "X";
                }

                $stmt = $db->getDiemDetail($data["ID_HS2"],$buoiID,$lmID);
                $result = $stmt->get_result();
                $stmt->close();

                $response["hsID2"]["id_hs"] = $data["ID_HS2"];
                $response["hsID2"]["maso"] = $data3["cmt"];
                $response["hsID2"]["ava"] = $data3["avata"];
                if($result->num_rows != 0) {
                    $data5 = $result->fetch_assoc();
                    $response["hsID2"]["diem"] = $data5["diem"];
                    if ($data5["loai"] != 3) {
                        $response["hsID2"]["loai"] = $db->getCmtLoai($data5["loai"]);
                    } else {
                        $response["hsID2"]["loai"] = $db->getNoteMean($data5["note"]);
                    }
                } else {
                    $response["hsID2"]["diem"] = "X";
                    $response["hsID2"]["loai"] = "X";
                }

                if($data["ketqua"] == $data["ID_HS"]) {
                    $response["hsID"]["ketqua"] = 1;
                    $response["hsID2"]["ketqua"] = 0;
                } else if($data["ketqua"] == $data["ID_HS2"]) {
                    $response["hsID"]["ketqua"] = 0;
                    $response["hsID2"]["ketqua"] = 1;
                } else if($data["ketqua"] == "X") {
                    $response["hsID"]["ketqua"] = 1;
                    $response["hsID2"]["ketqua"] = 1;
                } else {
                    $response["hsID"]["ketqua"] = 0;
                    $response["hsID2"]["ketqua"] = 0;
                }
            } else {
                $response["error"] = TRUE;
                $response["error_msg"] = "6";
            }
            echo json_encode($response);
        } else {
            $response["error"] = TRUE;
            $response["error_msg"] = "4";
            echo json_encode($response);
        }
    } else {
        // required post params is missing
        $response["error"] = TRUE;
        // Không có dữ liệu được gửi đến!!!
        $response["error_msg"] = "3";
        echo json_encode($response);
    }
    ob_end_flush();
?>