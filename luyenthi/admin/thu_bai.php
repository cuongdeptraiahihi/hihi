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
    $db2 = new Vao_Thi();
    $db3 = new Luyen_De();

    $result0 = $db->getNhomDeById($nhom);
    $data0 = $result0->fetch_assoc();

    $deID = $db->getDeThiMainByNhom($nhom);
    $result1 = $db->getDeThiById($deID);
    $data1 = $result1->fetch_assoc();

    $pre_cau = 0;
    $num_all = 0;
    $result = $db->getCauHoiByDe($deID, true);
    while($data = $result->fetch_assoc()) {
        if($data["done"] == 0) {
            $pre_cau++;
        }
        $num_all++;
    }

    if($num_all != 0) {
        $diem_per = 10 / $num_all;
        $content = "";
        $result = $db3->getKetQuaLuyenDeByNhomByHow($nhom, "in");
        while ($data = $result->fetch_assoc()) {
            $diem = formatDiem($diem_per * ($db3->countNumCauDung($data["ID_DE"], $data["ID_HS"]) + $pre_cau));
            $time = $db3->countTotalTimeDung($data["ID_DE"], $data["ID_HS"]);
//            echo "$data[ID_HS] - $data[ID_DE] - $diem - $time<br />";
            $db2->addHocSinhInDe($data["ID_HS"],$data["ID_DE"],$nhom,"out");
//            $db3->newLuyenDe($data["ID_DE"],$data["ID_HS"],"lam-tai-lop",$diem,$time,$data0["ID_LM"]);
            $content .= ",('$data[ID_DE]','$data[ID_HS]','lam-tai-lop','$diem','$time',now(),'$data0[ID_LM]')";
        }
        if($content != "") {
            $content = substr($content, 1);
            $db3->insertNewLuyenDe($content);
        }
    }

    $db->updateNhomDeStatus($nhom, 2);

    if($data0["type"] != "kiem-tra") {
        $db = new Hoc_Sinh();
        $content = "";
        $result4 = $db3->getListHocSinhChuaLam($nhom, $data1["loai"], $data0["ID_LM"]);
        while ($data4 = $result4->fetch_assoc()) {
            $db->lockHocSinh($data4["ID_HS"]);
            $content .= ",('$data4[ID_HS]','$data0[ID_LM]',now(),'Không làm trắc nghiệm nhóm đề $data1[mota]')";
        }
        if($content != "") {
            $content = substr($content, 1);
            $db->insertHocSinhLock($content);
        }
    }

    $_SESSION["new_de"] = $deID;
    header("location:http://localhost/www/TDUONG/luyenthi/admin/trang-chu/");
    exit();


?>