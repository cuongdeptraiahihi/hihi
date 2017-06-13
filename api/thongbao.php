<?php
    ob_start();
    date_default_timezone_set("Asia/Ho_Chi_Minh");
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // json response array
    $response = array("error" => FALSE);

    if (isset($_POST['hsID']) && isset($_POST["json"]) && isset($_POST["token"])) {

        // receiving the post params
        $hsID = $_POST["hsID"];
        $json = $_POST["json"];
        $token = $_POST["token"];

        if(is_numeric($hsID) && $hsID > 0 && $token != "") {

            $db->updateToken($hsID,$token);

            if($json != "") {
                $id_arr = "";
                $temp = explode("+",$json);
                for($i=0;$i<count($temp);$i++) {
                    if($temp[$i] != 0) {
                        $id_arr .= ",'".$temp[$i]."'";
                    }
                }
                if($id_arr != "") {
                    $id_arr = substr($id_arr, 1);
                    $db->xemThongBaoId($id_arr);
                }
            }

            $response["error"] = FALSE;
            $response["uid"] = $hsID;
            $response["thongbao"] = array();

            $stmt = $db->getAllNotification($hsID);
            $result = $stmt->get_result();
            $stmt->close();
            if($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $response["thongbao"][] = array(
                    "id_tb" => $user["ID_TB"],
                    "object" => $user["object"],
                    "content" => $user["content"],
                    "danhmuc" => $user["danhmuc"],
                    "id_mon" => $user["ID_LM"],
                    "datetime" => $user["datetime"],
                    "loai" => $user["loai"],
                    "status" => $user["status"]
                );
            }

            // get the user by email and password
            $stmt = $db->getUserNotification($hsID);
            $result = $stmt->get_result();
            $stmt->close();
            while ($user = $result->fetch_assoc()) {
                $response["thongbao"][] = array(
                    "id_tb" => $user["ID_TB"],
                    "object" => $user["object"],
                    "content" => $user["content"],
                    "danhmuc" => $user["danhmuc"],
                    "id_mon" => $user["ID_LM"],
                    "datetime" => $user["datetime"],
                    "loai" => $user["loai"],
                    "status" => $user["status"]
                );
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