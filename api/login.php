<?php
    ob_start();
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // json response array
    $response = array("error" => FALSE);

    if (isset($_POST['maso']) && isset($_POST['pass']) && isset($_POST['token'])) {

        // receiving the post params
        $maso = addslashes($_POST['maso']);
        $pass = addslashes($_POST['pass']);
        $token = $_POST['token'];

        if($db->validMaso($maso) && $maso!="" && $pass!="" && $token!="") {
            // get the user by email and password
            $user = $db->getUserByMasoAndPass($maso, $pass);

            if ($user != false) {

                $db->addFirebaseToken($user["ID_HS"],$token);

                // user is found
                $response["error"] = FALSE;
                $response["uid"] = $user["ID_HS"];
                $response["user"]["cmt"] = $user["cmt"];
                $response["user"]["vantay"] = $user["vantay"];
                $response["user"]["fullname"] = $user["fullname"];
                $response["user"]["avata"] = $user["avata"];
                $response["user"]["birth"] = $user["birth"];
                $response["user"]["gender"] = $user["gender"];
                $response["user"]["truong"] = $db->getTruongHs($user["truong"]);
                $response["user"]["sdt"] = $user["sdt"];
                $response["user"]["sdt_bo"] = $user["sdt_bo"];
                $response["user"]["sdt_me"] = $user["sdt_me"];
                $response["user"]["taikhoan"] = $user["taikhoan"];
                $response["user"]["lop"] = $db->getLopName($user["lop"]);
                $response["user"]["level"] = 1;

                $stmt = $db->getConfigMon($user["ID_HS"]);
                $result = $stmt->get_result();
                $stmt->close();
                if($result->num_rows > 0) {
                    while ($data = $result->fetch_assoc()) {
                        $response["mon"][] = array(
                            "id_mon" => $data["ID_LM"],
                            "name" => $data["name"],
                            "de" => $data["de"],
                            "rank" => $data["level"],
                            "date_in" => $data["date_in"],
                            "diemtb" => $db->getDiemTBHs($user["ID_HS"],$data["ID_LM"])
                        );
                    }
                } else {
                    $response = array();
                    $response["error"] = TRUE;
                    $response["error_msg"] = "5";
                }

                echo json_encode($response);
            } else {
                $user = $db->getUserByMasoAndSdt($maso, $pass);

                if($user != false) {
                    $response["error"] = FALSE;
                    $response["uid"] = $user["ID_HS"];
                    $response["user"]["cmt"] = $user["cmt"];
                    $response["user"]["vantay"] = $user["vantay"];
                    $response["user"]["fullname"] = $user["fullname"];
                    $response["user"]["avata"] = $user["avata"];
                    $response["user"]["birth"] = $user["birth"];
                    $response["user"]["gender"] = $user["gender"];
                    $response["user"]["truong"] = $db->getTruongHs($user["truong"]);
                    $response["user"]["sdt"] = $user["sdt"];
                    $response["user"]["sdt_bo"] = $user["sdt_bo"];
                    $response["user"]["sdt_me"] = $user["sdt_me"];
                    $response["user"]["taikhoan"] = $user["taikhoan"];
                    $response["user"]["lop"] = $db->getLopName($user["lop"]);
                    $response["user"]["level"] = 2;

                    $stmt = $db->getConfigMon($user["ID_HS"]);
                    $result = $stmt->get_result();
                    $stmt->close();
                    if($result->num_rows > 0) {
                        while ($data = $result->fetch_assoc()) {
                            $response["mon"][] = array(
                                "id_mon" => $data["ID_LM"],
                                "name" => $data["name"],
                                "de" => $data["de"],
                                "rank" => $data["level"],
                                "date_in" => $data["date_in"],
                                "diemtb" => $db->getDiemTBHs($user["ID_HS"],$data["ID_LM"])
                            );
                        }
                    } else {
                        $response = array();
                        $response["error"] = TRUE;
                        $response["error_msg"] = "5";
                    }
                } else {
                    // user is not found with the credentials
                    $response["error"] = TRUE;
                    // Mã số hoặc Mật khẩu không chính xác!!!
                    $response["error_msg"] = "1";
                }
                echo json_encode($response);
            }
        } else {
            $response["error"] = TRUE;
            // Mã số hoặc Mật khẩu không hợp lệ!!!
            $response["error_msg"] = "2";
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