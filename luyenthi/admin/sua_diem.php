<?php
    session_start();
    ini_set('max_execution_time', 900);
    require_once("../model/model.php");
    require_once("access_admin.php");

    $me = md5("123456");
    if(isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
        $nhom = $_GET["nhom"];
    } else {
        $nhom = 0;
    }
    $db = new De_Thi();
    $db3 = new Luyen_De();

    $result0 = $db->getNhomDeById($nhom);
    $data0 = $result0->fetch_assoc();

    $deID = $db->getDeThiMainByNhom($nhom);

    $pre_cau = 0;
    $num_all = 0;
    $result = $db->getCauHoiByDe($deID, true);
    while($data = $result->fetch_assoc()) {
        if($data["done"] == 0) {
            $pre_cau++;
        }
        $num_all++;
    }

    $diem_per = 10 / $num_all;

    $result = $db3->getKetQuaLuyenDeByNhom($nhom);
    while ($data = $result->fetch_assoc()) {
        $diem = formatDiem($diem_per * ($db3->countNumCauDung($data["ID_DE"],$data["ID_HS"]) + $pre_cau));
        if($data["diem"] != $diem && (int) $diem != 0) {
            $db3->updateLuyenDe($data["ID_DE"],$data["ID_HS"],$diem,$data["time"],$data0["ID_LM"]);
            if($data0["type"]=="kiem-tra") {
                $db3->updateLuyenDeBack($data0["object"],$data["ID_HS"],$diem,$data["maso"],$data0["ID_LM"]);
            }
            echo "$data[ID_DE] - $data[ID_HS] - $diem - $data[diem] - ok<br />";
        } else {
//            echo "$data[ID_DE] - $data[ID_HS] - $diem - $data[diem] - no<br />";
        }
    }

    header("location:http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$nhom/");
    exit();


?>