<?php
	ob_start();
	ini_set('max_execution_time', 1000);
//    require_once("../model/model.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/luyenthi/model/model.php");
	
	// Code thu bài
    // Chạy từng ngày, kiểm tra xem có đề nào đã đủ 5 ngày chưa để thu bài
    $now = date("Y-m-d");
    $db = new De_Thi();
    $db2 = new Vao_Thi();
    $db3 = new Luyen_De();
    $db4 = new Hoc_Sinh();
    $result = $db->getNhomDeOpen(0);
    $dem = 0;
    while($data = $result->fetch_assoc()) {
        if($data["date_open"] != "0000-00-00 00:00:00" && $data["type"] != "kiem-tra") {
//            $date_open = date_create($data["date_open"]);
            if($data["date_close"] != "0000-00-00") {
                $open = date_create($data["date_close"]);
            } else {
                $temp = explode(" ",$data["date_open"]);
                $open = date_create($temp[0]);
                date_add($open, date_interval_create_from_date_string("+6 days"));
            }
            $next = date_format($open, "Y-m-d");
//            echo "$data[date_open] - " . $next . "<br />";
            if(date_create($now) >= date_create($next)) {
                // Sẽ thu bài
                $nhom = $data["ID_N"];

                $pre_cau = 0;
                $num_all = 0;
                $result4 = $db->getCauHoiByDe($data["ID_DE"], true);
                while($data4 = $result4->fetch_assoc()) {
                    if($data4["done"] == 0) {
                        $pre_cau++;
                    }
                    $num_all++;
                }

                if($num_all != 0) {
                    $diem_per = 10 / $num_all;
                    $content = "";
                    $result4 = $db3->getKetQuaLuyenDeByNhomByHow($nhom, "in");
                    while ($data4 = $result4->fetch_assoc()) {
                        $diem = formatDiem($diem_per * ($db3->countNumCauDung($data4["ID_DE"], $data4["ID_HS"]) + $pre_cau));
                        $time = $db3->countTotalTimeDung($data4["ID_DE"], $data4["ID_HS"]);
//                        echo "$data4[ID_HS] - $data4[ID_DE] - $diem - $time<br />";
                        $db2->addHocSinhInDe($data4["ID_HS"], $data4["ID_DE"], $nhom, "out");
//                        $db3->newLuyenDe($data4["ID_DE"], $data4["ID_HS"], "lam-tai-lop", $diem, $time, $data["ID_LM"]);
                        $content .= ",('$data4[ID_DE]','$data4[ID_HS]','lam-tai-lop','$diem','$time',now(),'$data[ID_LM]')";
                    }
                    if($content != "") {
                        $content = substr($content, 1);
                        $db3->insertNewLuyenDe($content);
                    }
                }

                $db->updateNhomDeStatus($nhom, 2);

                $content = "";
                $result4 = $db3->getListHocSinhChuaLam($nhom, 0, $data["ID_LM"]);
                while ($data4 = $result4->fetch_assoc()) {
                    $db4->lockHocSinh($data4["ID_HS"]);
                    $content .= ",('$data4[ID_HS]','$data[ID_LM]',now(),'Không làm trắc nghiệm nhóm đề $data[mota]')";
                }
                if ($content != "") {
                    $content = substr($content, 1);
                    $db4->insertHocSinhLock($content);
                }
            }
        }
    }
	
	ob_end_flush();
?>