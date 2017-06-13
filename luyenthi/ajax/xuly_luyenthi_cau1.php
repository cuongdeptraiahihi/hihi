<?php
    ob_start();
    session_start();
//    ini_set('max_execution_time', 300);
    require_once "../model/model.php";
    require_once "../access_hocsinh.php";
    $me = md5("123456");
    $noti = "none";

    if(isset($_GET["text"]) && isset($_GET["ca"]) && isset($_GET["deID"]) && isset($_GET["hsID"])) {
        $text = addslashes($_GET["text"]);
        $ca = addslashes($_GET["ca"]);
        $deID = $_GET["deID"];
        $hsID = $_GET["hsID"];
        if(validId($deID) && validId($hsID)) {
            (new Options())->addOptions("Ca thi: ".$ca."<br />".$text, "bao-de-sai", $hsID, $deID);
            $noti = "ok";
        } else {
            $noti = "none";
        }
    }

    if(isset($_GET["daID"]) && isset($_GET["dapankhac"]) && isset($_GET["hsID"]) && isset($_GET["deID"])) {
        $daID = $_GET["daID"];
        $content = base64_decode($_GET["dapankhac"]);
//            $hsID = decodeData($_GET["hsID"],$me);
        $hsID = $_GET["hsID"];
        $deID = $_GET["deID"] + 1 - 1;
        if(validId($daID) && validId($hsID) && validId($deID) && validText($content)) {
            $db = new Vao_Thi();
            $db->addNoteDapAnDeHocSinh($hsID,$daID,$deID,$content);
            $noti = "ok";
        } else {
            $noti = "none";
        }
    }

    if (isset($_GET["cID"]) && isset($_GET["content"]) && isset($_GET["hsID"]) && isset($_GET["lmID"]) && isset($_GET["all"])) {
//            $hsID = decodeData($_GET["hsID"],$me);
        $hsID = $_GET["hsID"];
        $cID = $_GET["cID"];
        $content = $_GET["content"];
        $lmID = $_GET["lmID"];
        $all = $_GET["all"];
        if (validId($cID) && validText($content) && validId($hsID)) {
            $db = new Binh_Luan();
            $db->addBinhLuan($cID, $hsID, $content, "text");
//                if($all == 1) {
//                    $result = $db->getBinhLuanCau($cID, $lmID, 20);
//                } else {
//                    $result = $db->getBinhLuanCauMe($hsID, $cID, $lmID);
//                }
//                $dem = 1;
//                while ($data = $result->fetch_assoc()) {
//                    echo "<tr>
//                    <td class='text-center'>$data[cmt]</td>";
//                    if($data["type"] == "image") {
//                        echo"<td><img src='http://localhost/www/TDUONG/luyenthi/upload/cauhoi/$data[content]' style='max-width: 100%;max-height: 300px;' /></td>";
//                    } else {
//                        echo"<td>$data[content]</td>";
//                    }
//                    echo"<td class='text-center'>" . formatDateTime($data["datetime"]) . "</td>
//                    </tr>";
//                    $dem++;
//                }
//                $dem--;
//                if($all != 1) {
//                    echo"<tr>
//                         <td colspan='3' class='text-center'>Có $dem người bình luận, nhưng bạn chỉ có thể thấy các bình luận của riêng bạn!</td>
//                    </tr>";
//                }
            $noti = "ok";
        } else {
            $noti = "none";
        }
    }

    if(isset($_GET["cID0"]) && isset($_GET["hsID0"]) && isset($_GET["time_new"]) && isset($_GET["deID0"])) {
        $cID = $_GET["cID0"];
//            $hsID = decodeData($_GET["hsID0"],$me);
        $hsID = $_GET["hsID0"];
        $time = $_GET["time_new"];
        $deID = $_GET["deID0"] + 1 - 1;
        if(validId($cID) && validId($hsID) && $time >= 0 && is_numeric($deID)) {
//                $db = new Vao_Thi();
//                $db->updateTimeCauLam($hsID, $cID, $deID, $time);
//                if(isset($_SESSION["de-thi-".$deID])) {
//                    if(isset($_SESSION["de-thi-".$deID][$cID])) {
//                        if($_SESSION["de-thi-".$deID][$cID]["ID_DA"]) {
//                            $_SESSION["de-thi-" . $deID][$cID]["time"] = $time;
//                        } else {
//                            $_SESSION["de-thi-".$deID][$cID] = array(
//                                "ID_DA" => 0,
//                                "time" => $time
//                            );
//                        }
//                    } else {
//                        $_SESSION["de-thi-".$deID][$cID] = array(
//                            "ID_DA" => 0,
//                            "time" => $time
//                        );
//                    }
//                }
            $noti = "ok";
        } else {
            $noti = "none";
        }
    }

    if (isset($_GET["da"]) && isset($_GET["time"]) && isset($_GET["cID"]) && isset($_GET["hsID"]) && isset($_GET["deID"])) {
//            $da_arr = $_SESSION["temp"];
//            $n = count($da_arr);
//            $hsID = decodeData($_GET["hsID"],$me);
        $hsID = $_GET["hsID"];
        $cID = $_GET["cID"];
        $daID = $_GET["da"];
        $time = $_GET["time"];
        $deID = $_GET["deID"] + 1 - 1;
        if (validId($daID) && validId($hsID) && validId($cID) && is_numeric($deID) && $time >= 0) {
            $db = new Vao_Thi();
            if($db->addCauLam($hsID, $cID, $deID, $time, $daID, "") != 0) {
                $noti = "ok";
            } else {
                if($db->updateCauLam($hsID, $cID, $deID, $time, $daID, "") != 0) {
                    $noti = "ok";
                } else {
                    $noti = "fuck";
                }
            }
//                if(isset($_SESSION["de-thi-$deID"])) {
//                    $_SESSION["de-thi-$deID"]["cau-hoi-$cID"] = array(
//                        "ID_DA" => $daID,
//                        "time" => $time,
//                        "note" => "",
//                        "datetime" => date("Y-m-d H:i:s")
//                    );
//                    $noti = "ok";
//                } else {
//                    $noti = "fuck";
//                }
        } else {
            $noti = "none";
        }
    }

    if (isset($_GET["da0"]) && isset($_GET["time0"]) && isset($_GET["cID"]) && isset($_GET["hsID"]) && isset($_GET["deID"])) {
//            $da_arr = $_SESSION["temp"];
//            $n = count($da_arr);
//            $hsID = decodeData($_GET["hsID"],$me);
        $hsID = $_GET["hsID"];
        $cID = $_GET["cID"];
        $daID = $_GET["da0"];
        $time = $_GET["time0"];
        $deID = $_GET["deID"] + 1 - 1;
        if (validId($daID) && validId($hsID) && validId($cID) && is_numeric($deID) && $time >= 0) {
            $db = new Vao_Thi();
            if($db->updateCauLam($hsID, $cID, $deID, $time, $daID, "") != 0) {
                $noti = "ok";
            } else {
                if($db->addCauLam($hsID, $cID, $deID, $time, $daID, "") != 0) {
                    $noti = "ok";
                } else {
                    $noti = "fuck";
                }
            }
//                if(isset($_SESSION["de-thi-$deID"])) {
//                    $_SESSION["de-thi-$deID"]["cau-hoi-$cID"] = array(
//                        "ID_DA" => $daID,
//                        "time" => $time,
//                        "note" => "",
//                        "datetime" => date("Y-m-d H:i:s")
//                    );
//                    $noti = "ok";
//                } else {
//                    $noti = "fuck";
//                }
        } else {
            $noti = "none";
        }
    }
    
    if(isset($_GET["ajax_cau"])) {
        $json = json_decode($_GET["ajax_cau"],true);
        $n = count($json) - 1;
        $hsID = $json[$n]["hsID"];
        $deID = $json[$n]["deID"];
        $check = false;
        if(validId($hsID) && validId($deID)) {
            $db = new Vao_Thi();
            for ($i = 0; $i < $n; $i++) {
                $cID = $json[$i]["cID"];
                $daID = $json[$i]["daID"];
                $time = $json[$i]["time"];
                if (validId($cID) && validId($daID) && $time >= 0) {
                    if($db->addCauLam($hsID, $cID, $deID, $time, $daID, "") != 0) {
                        $check = true;
                    } else {
                        if($db->updateCauLam($hsID, $cID, $deID, $time, $daID, "") != 0) {
                            $check = true;
                        }
                    }
                }
            }
        }
        if($check) {
            $noti = "ok";
        } else {
            $noti = "no";
        }
    }

    echo $noti;
//    ob_end_flush();
?>

