<?php
    session_start();
    require_once("model/model.php");
    require_once("access_hocsinh.php");
    $me = md5("123456");
    if(isset($_GET["hsID"]) && isset($_GET["deID"])) {
        $hsID = decodeData($_GET["hsID"],$me);
        $deID = $_GET["deID"];
        if(validId($hsID) && validId($deID)) {
            $db = new Thong_Ke();
            $db2 = new De_Thi();

            $nhom = $db2->getNhomDeByDe($deID);
            $result = $db2->getNhomDeById($nhom);
            $data = $result->fetch_assoc();

//            $check = $db2->checkHocSinhLamLai($hsID, $deID);
//            if(($check != false && $data["type"] != "kiem-tra") || ($check == "diem-0" && $data["type"] == "kiem-tra")) {
//                $db3->addLog($hsID, "Xóa kết quả và làm lại nhóm đề thi $code", "lam-lai-de");
                if ($db2->checkDeOpenHocSinh($hsID, $nhom, $lmID)) {
                    $db->cleanKetQuaDeThi($hsID, $deID, true);
//                    $deID = $db2->getRandDeThiByNhom($data["ID_N"], $db3->getDeOfHocSinh($hsID, $lmID));
//                    $deID = $deID + 1 - 1;
//                    $deID = (new Vao_Thi())->addHocSinhInDe($hsID, $deID, $nhom, "in");
                    header("location:http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($deID, $me) . "/");
                    exit();
                } else {
                    header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/1/");
                    exit();
                }
//            } else {
//                echo"Bạn không đủ điều kiện làm lại!";
//            }
        } else {
            echo"Lỗi dữ liệu!";
        }
    } else {
        echo"Lỗi dữ liệu!";
    }