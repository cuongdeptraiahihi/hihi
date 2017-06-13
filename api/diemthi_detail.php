<?php
    ob_start();
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // json response array
    $response = array("error" => FALSE);

    if (isset($_POST['hsID']) && isset($_POST["buoiID"]) && isset($_POST["monID"])) {

        // receiving the post params
        $hsID = $_POST["hsID"];
        $buoiID = $_POST["buoiID"];
        $lmID = $_POST["monID"];

        if(is_numeric($hsID) && $hsID > 0 && is_numeric($buoiID) && $buoiID > 0 && is_numeric($lmID) && $lmID > 0) {

            $stmt = $db->getDiemDetail($hsID, $buoiID, $lmID);
            $result = $stmt->get_result();
            $stmt->close();
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();

                $response["error"] = FALSE;

                $response["id_diem"] = $data["ID_DIEM"];
                $response["de"] = $data["de"];
                if ($data["loai"] != 3) {
                    $response["loai"] = $db->getCmtLoai($data["loai"]);
                } else {
                    $response["loai"] = $db->getNoteMean($data["note"]);
                }
                $response["price"] = $db->getPhatDiemKt($hsID, $buoiID, $lmID);
                $response["diemtb"] = $db->getDiemTbLop($buoiID, $data["de"], $lmID);
                $response["ngay"] = $db->getNgayKt($buoiID);
                $response["diem"] = $data["diem"];

                $cau_arr = array("","a","b","c","d","e","f","g","h");
                $response["diemtp"] = array();
                $stmt = $db->getCdDiem($buoiID,$hsID,$lmID);
                $result2 = $stmt->get_result();
                $stmt->close();
                if($result2->num_rows != 0) {
                    while($data2 = $result2->fetch_assoc()) {
                        $cau = "Câu $data2[cau]".$cau_arr[$data2["y"]];
                        $response["diemtp"][] = array(
                            "cau" => $cau,
                            "title" => $data2["title"],
                            "diem_con" => $data2["diem"]
                        );
                    }
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