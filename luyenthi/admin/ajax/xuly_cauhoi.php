<?php
    ob_start();
    session_start();
    ini_set('max_execution_time', 900);
    require_once "../../model/model.php";
    require_once "../access_admin.php";

    if(isset($_POST["cID4"])) {
        $cID = $_POST["cID4"];
        if(validId($cID)) {
            $db = new Cau_Hoi();
            if($db->xoaCauHoi($cID)) {
                echo "ok";
            } else {
                echo "no";
            }
        }
    }

    if(isset($_POST["daID1"])) {
        $daID = $_POST["daID1"];
        if(validId($daID)) {
            $db = new Cau_Hoi();
            if($db->xoaDapAn($daID)) {
                echo "ok";
            } else {
                echo "no";
            }
        }
    }

    if(isset($_POST["cID"]) && isset($_POST["action"])) {
        $cID = $_POST["cID"];
        $action = $_POST["action"];
        if(validId($cID)) {
            $db = new Cau_Hoi();
            $db->configCauHoi($cID,false);
        }
        if($action == "show") {showNewCau($cID);}
    }

    if(isset($_POST["cID1"]) && isset($_POST["action"])) {
        $cID = $_POST["cID1"];
        $action = $_POST["action"];
        if(validId($cID)) {
            $db = new Cau_Hoi();
            $db->configCauHoi($cID,true);
        }
        if($action == "show") {showNewCau($cID);}
    }

    if(isset($_POST["cID0"]) && isset($_POST["action"])) {
        $cID = $_POST["cID0"];
        $action = $_POST["action"];
        if(validId($cID)) {
            $db = new Cau_Hoi();
            $db->checkCauHoi($cID,false);
        }
        if($action == "show") {showNewCau($cID);}
    }

    if(isset($_POST["cID2"]) && isset($_POST["action"])) {
        $cID = $_POST["cID2"];
        $action = $_POST["action"];
        if(validId($cID)) {
            $db = new Cau_Hoi();
            $db->checkCauHoi($cID,true);
        }
        if($action == "show") {showNewCau($cID);}
    }

    if(isset($_POST["cID5"]) && isset($_POST["action"])) {
        $cID = $_POST["cID5"];
        $action = $_POST["action"];
        if(validId($cID)) {
            $db = new Cau_Hoi();
            $db->delBinhLuan($cID);
        }
        if($action == "show") {showNewCau($cID);}
    }

    if(isset($_POST["daID"]) && isset($_POST["deID"])) {
        $daID = $_POST["daID"];
        $deID = $_POST["deID"];
        if(validId($daID) && is_numeric($deID)) {
            $db = new Thong_Ke();
            $dem = 1;
            $result = $db->getListChonDapAn($daID,$deID);
            while($data = $result->fetch_assoc()) {
                echo"<tr class='who-click'>
                    <td></td>
                    <td>$data[fullname]</td>
                    <td>$data[cmt]</td>
                </tr>";
                $dem++;
            }
            if($dem == 1) {
                echo"<tr class='who-click'>
                    <td colspan='3'>Không có dữ liệu</td>
                </tr>";
            }
        }
    }

    function showNewCau($cID) {
        $db = new Cau_Hoi();
        $result = $db->getCauHoiById($cID);
        $data = $result->fetch_assoc();
        echo"<td class='text-center'></td>
        <td class='text-center'>$data[maso]</td>
        <td class='text-center'>".formatDateTime($data["ngay"])."</td>
        <td class='text-center'>".$db->formatDone($data["done"])."</td>
        <td class='text-center'>".formatStatus($data["ready"])."</td>
        <td class='text-center'>
            <ul class='icons-list'>
                <li class='dropdown'>
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                        <i class='icon-menu9'></i>
                    </a>

                    <ul class='dropdown-menu dropdown-menu-right'>";
                        if($data["done"] == 1) {
                            echo"<li class='uncheck-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-cross3'></i> Un Check</a></li>";
                        } else {
                            echo "<li class='check-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-check'></i> Check</a></li>";
                        }
                        echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$data[ID_C]/'><i class='icon-pencil3'></i> Sửa</a></li>
                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-cau-hoi/$data[ID_C]/'><i class='icon-stats-dots'></i> Thống kê</a></li>";
                        if($data["ready"] == 1) {
                            echo"<li class='del-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-cross3'></i> Ẩn</a></li>";
                        } else {
                            echo"<li class='show-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-check'></i> Hiện</a></li>";
                        }
                    echo"</ul>
                    </li>
                </ul>
            </td>";
    }

    if(isset($_POST["ajax_data"]) && isset($_POST["monID"])) {
        $ajax = $_POST["ajax_data"];
        $monID = $_POST["monID"];
        $data = json_decode($ajax, true);
        $n = count($data)-1;
        $pre = $data[$n]["pre"];
        $check = $data[$n]["check"];
        $notecd = $data[$n]["notecd"];
//        $html = "";
        $db = new Cau_Hoi();
        $db2 = new Chuyen_De();
        $num = 1;
        $dem = 0;
        $cd_arr = array();
        $_SESSION["ketqua"] = array();
        for($i = 0; $i < $n; $i++) {
            $temp = $data[$i]["cau-".($i+1)][0];

            if($temp["de"] == "") {
                continue;
            }

            $cID = 0;
            $maDe = $temp["maDe"];
            $de = formatBack($temp["de"]);
            $deAnh = $temp["deAnh"];
            $chiTiet = formatBack($temp["chiTiet"]);
            $chiTietAnh = $temp["chiTietAnh"];
            $level = $temp["level"] + 1;
            $cdID = 0;
            $note = $temp["note"];
            if($notecd == 1) {
                $note = str_replace("8o8","",$note);
                if(isset($cd_arr[$note])) {
                    $chuyende = $cd_arr[$note]["name"];
                } else {
                    $result2 = $db2->getChuyenDeById($note);
                    $data2 = $result2->fetch_assoc();
                    $chuyende = $data2["name"];
                    $cd_arr[$note]["name"] = $chuyende;
                    $cd_arr[$note]["id"] = $data2["ID_CD"];
                }
                $cdID = $cd_arr[$note]["id"];
            } else {
                $result2 = $db2->getChuyenDeById($maDe);
                $data2 = $result2->fetch_assoc();
                $chuyende = $data2["name"];
                $cdID = $data2["ID_CD"];
                $note = nl2br($db->formatCT(formatBack($note)));
            }

            $main = 0;
            $temp3 = explode("-",$maDe);
            if(isset($temp3[2]) && stripos($temp3[2],"a") !== false) {
                $main = 1;
            }

            $cID_old = $db->checkIssetCauHoi($maDe);
            if($cID_old == 0) {
                $temp = $db->addCauHoi($maDe, $de, $deAnh, "trac-nghiem", $cdID, $level, 1, 0, $note, $check, $monID, $main);
                $cID = $temp[1];
                $_SESSION["ketqua"][$dem]["ID_C"] = $cID;
            } else {
                $db->editCauHoi($cID_old, $maDe, $de, $deAnh, "trac-nghiem", $cdID, $level, 1, 0, $note, false, $monID, $main);
                $_SESSION["ketqua"][$dem]["ID_C"] = $cID_old;
            }
            $_SESSION["ketqua"][$dem]["maso"] = $maDe;
//            $_SESSION["ketqua"][$dem]["content"] = $db->formatCT($de);
            $_SESSION["ketqua"][$dem]["anh"] = $deAnh;
            $_SESSION["ketqua"][$dem]["chuyende"] = $chuyende;
            $_SESSION["ketqua"][$dem]["note"] = $note;
            $_SESSION["ketqua"][$dem]["monID"] = $monID;

//            $html.="<br />Câu $num: $maDe<br />Chuyên đề: $cdID<br />Đề bài: ".nl2br($db->formatCT($de))."<br />";

            if($cID_old == 0) {
                $db->addDapAnDai($chiTiet, $chiTietAnh, $cID);
            } else {
                $db->editDapAnDai($cID_old, $chiTiet, $chiTietAnh, false);
            }
//            $_SESSION["ketqua"][$dem]["da_con"] = $db->formatCT($chiTiet);
            $_SESSION["ketqua"][$dem]["da_anh"] = $chiTietAnh;

//            $html.="<br />Đáp án:<br />".nl2br($db->formatCT($chiTiet))."<br /><br />";

            $temp2 = $data[$i]["cau-".($i+1)][1]["dapan"];
            $_SESSION["ketqua"][$dem]["da_arr"] = array();

            if($cID_old == 0) {
                $j = 0;
                while($j < $temp2[count($temp2)-1]["num"]) {
                    $dapan = formatBack($temp2[$j]["dapan"]);
                    $db->addDapAnNgan($dapan, $temp2[$j]["dapanType"], $temp2[$j]["dapanMain"], $cID, 1);
//                    if($temp2[$j]["dapanMain"] == 1) {
//                        $html .= "+) (Đúng) " . $db->formatCT($dapan) . "<br />";
//                    } else {
//                        $html .= "+) " . $db->formatCT($dapan) . "<br />";
//                    }
                    $_SESSION["ketqua"][$dem]["da_arr"][] = array(
                        "dapan" => $db->formatCT($dapan),
                        "dapanType" => $temp2[$j]["dapanType"],
                        "dapanMain" => $temp2[$j]["dapanMain"]
                    );
                    $j++;
                }
                $db->addDapAnNgan("Đáp án khác", "text", 0, $cID, 0);
                $db->addDapAnNgan("Em không làm được", "text", 0, $cID, 0);
//                $html.="+) Đáp án khác<br />";
//                $html.="+) Em không làm được<br />";
            } else {
                $j = 0;
                $result2 = $db->getDapAnNgan($cID_old,false);
                while($data2 = $result2->fetch_assoc()) {
                    if(!isset($temp2[$j]["dapan"])) {break;}
                    $dapan = formatBack($temp2[$j]["dapan"]);
                    $db->editDapAnNgan($data2["ID_DA"], $dapan, $temp2[$j]["dapanType"], $temp2[$j]["dapanMain"]);
//                    if($temp2[$j]["dapanMain"] == 1) {
//                        $html .= "+) (Đúng) " . $db->formatCT($dapan) . "<br />";
//                    } else {
//                        $html .= "+) " . $db->formatCT($dapan) . "<br />";
//                    }
                    $_SESSION["ketqua"][$dem]["da_arr"][] = array(
                        "dapan" => $db->formatCT($dapan),
                        "dapanType" => $temp2[$j]["dapanType"],
                        "dapanMain" => $temp2[$j]["dapanMain"]
                    );
                    $j++;
                }
            }

//            $html.="<br />Level: $level<br />";
//            $html.="Ghi chú: ".str_replace("8o8","",$note)."<br />";
//            $html.="--------------------------------------------------------------------------------------------------------------------------<br />";

            $num++;
            $dem++;
        }
        $num--;
        $_SESSION["ketqua"][$dem]["num"] = $num;
        $_SESSION["ketqua"][$dem]["notecd"] = $notecd;

//        $_SESSION["ketqua"] = $html;
//        echo $html;
    }

//    if(isset($_POST["ajax_data"]) && isset($_POST["monID"])) {
//        $ajax = $_POST["ajax_data"];
//        $monID = $_POST["monID"];
//        $data = json_decode($ajax, true);
//        $n = count($data)-1;
//        $pre = $data[$n]["pre"];
//        $check = $data[$n]["check"];
//        $notecd = $data[$n]["notecd"];
//        $html = "";
//        $db = new Cau_Hoi();
//        $db2 = new Chuyen_De();
//        $num = 1;
//        for($i = 0; $i < $n; $i++) {
//            $temp = $data[$i]["cau-".($i+1)][0];
//
//            if($temp["de"] == "") {
//                continue;
//            }
//
//            $cID = 0;
//            $maDe = $temp["maDe"];
//            $de = formatBack($temp["de"]);
//            $deAnh = $temp["deAnh"];
//            $chiTiet = formatBack($temp["chiTiet"]);
//            $chiTietAnh = $temp["chiTietAnh"];
//            $level = $temp["level"] + 1;
//            $cdID = 0;
//            $note = $temp["note"];
//            if($notecd == 1) {
//                $cdID = $db2->getChuyenDeIdByMaso(str_replace("8o8","",$note));
//            } else {
//                $cdID = $db2->getChuyenDeIdByMaso($maDe);
//                $note = nl2br($db->formatCT(formatBack($note)));
//            }
//
//            $main = 0;
//            $temp3 = explode("-",$maDe);
//            if(isset($temp3[2]) && stripos($temp3[2],"a") !== false) {
//                $main = 1;
//            }
//
//            $cID_old = $db->checkIssetCauHoi($maDe);
//            if($cID_old == 0) {
//                $temp = $db->addCauHoi($maDe, $de, $deAnh, "trac-nghiem", $cdID, $level, 1, 0, $note, $check, $monID, $main);
//                $cID = $temp[1];
//            } else {
//                $db->editCauHoi($cID_old, $maDe, $de, $deAnh, "trac-nghiem", $cdID, $level, 1, 0, $note, false, $monID, $main);
//            }
//
//            $html.="<br />Câu $num: $maDe<br />Chuyên đề: $cdID<br />Đề bài: ".nl2br($db->formatCT($de))."<br />";
//
//            if($cID_old == 0) {
//                $db->addDapAnDai($chiTiet, $chiTietAnh, $cID);
//            } else {
//                $db->editDapAnDai($cID_old, $chiTiet, $chiTietAnh, false);
//            }
//
//            $html.="<br />Đáp án:<br />".nl2br($db->formatCT($chiTiet))."<br /><br />";
//
//            $temp2 = $data[$i]["cau-".($i+1)][1]["dapan"];
//
//            if($cID_old == 0) {
//                $j = 0;
//                while($j < $temp2[count($temp2)-1]["num"]) {
//                    $dapan = formatBack($temp2[$j]["dapan"]);
//                    $db->addDapAnNgan($dapan, $temp2[$j]["dapanType"], $temp2[$j]["dapanMain"], $cID, 1);
//                    if($temp2[$j]["dapanMain"] == 1) {
//                        $html .= "+) (Đúng) " . $db->formatCT($dapan) . "<br />";
//                    } else {
//                        $html .= "+) " . $db->formatCT($dapan) . "<br />";
//                    }
//                    $j++;
//                }
//                $db->addDapAnNgan("Đáp án khác", "text", 0, $cID, 0);
//                $db->addDapAnNgan("Em không làm được", "text", 0, $cID, 0);
//                $html.="+) Đáp án khác<br />";
//                $html.="+) Em không làm được<br />";
//            } else {
//                $j = 0;
//                $result2 = $db->getDapAnNgan($cID_old,false);
//                while($data2 = $result2->fetch_assoc()) {
//                    if(!isset($temp2[$j]["dapan"])) {break;}
//                    $dapan = formatBack($temp2[$j]["dapan"]);
//                    $db->editDapAnNgan($data2["ID_DA"], $dapan, $temp2[$j]["dapanType"], $temp2[$j]["dapanMain"]);
//                    if($temp2[$j]["dapanMain"] == 1) {
//                        $html .= "+) (Đúng) " . $db->formatCT($dapan) . "<br />";
//                    } else {
//                        $html .= "+) " . $db->formatCT($dapan) . "<br />";
//                    }
//                    $j++;
//                }
//            }
//
//            $html.="<br />Level: $level<br />";
//            $html.="Ghi chú: ".str_replace("8o8","",$note)."<br />";
//            $html.="--------------------------------------------------------------------------------------------------------------------------<br />";
//
//            $num++;
//        }
//
//        $_SESSION["ketqua"] = $html;
//        echo $html;
//    }

    if(isset($_POST["cID3"]) && isset($_POST["nhom"])) {
        $cID = $_POST["cID3"];
        $nhom = $_POST["nhom"];
        (new Cau_Hoi())->cauHoiSai($cID);
        (new Nhom_Cau_Hoi())->saiNhomDe($nhom);
    }

    if(isset($_POST["search_ma_cau_hoi"])) {
        $search = $_POST["search_ma_cau_hoi"];
        $db = new Cau_Hoi();
        $result = $db->searchMaCauHoi($search);
        if(mysqli_num_rows($result) != 0) {
            while ($data = $result->fetch_assoc()) {
                echo "<tr class='tr-search'>
                    <td style='width: 15%;'>Câu $data[sort]</td>
                    <td>$data[mota]</td>
                    <td style='width: 20%;'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-cau-hoi/$data[ID_C]/' target='_blank'>Xem câu hỏi</a> | <a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$data[ID_DE]/' target='_blank'>Xem đề</a></td>
                </tr>";
            }
        } else {
            echo"<tr>
                <td colspan='3'>Không tìm thấy!</td>
            </tr>";
        }
    }

    if(isset($_POST["cID_edit"]) && isset($_POST["content"]) && isset($_POST["monID"])) {
        $cID = $_POST["cID_edit"];
        $content = $_POST["content"];
        $monID = $_POST["monID"];
        $db = new Cau_Hoi();
        $ct = str_replace("1o1","'",$content);
        $ct = str_replace("2o2","</",$ct);
        $ct = str_replace("3o3","<",$ct);
        $ct = str_replace("4o4",">",$ct);
        $ct = str_replace("5o5","+",$ct);
        $ct = str_replace("6o6","&",$ct);
        $content = str_replace("7o7","\\",$ct);
        echo imageToImg($monID,$db->editContentCauHoi($cID,$content),250);
    }

    if(isset($_POST["cID_edit"]) && isset($_POST["dapan"]) && isset($_POST["monID"])) {
        $cID = $_POST["cID_edit"];
        $content = $_POST["dapan"];
        $monID = $_POST["monID"];
        $db = new Cau_Hoi();
        $ct = str_replace("1o1","'",$content);
        $ct = str_replace("2o2","</",$ct);
        $ct = str_replace("3o3","<",$ct);
        $ct = str_replace("4o4",">",$ct);
        $ct = str_replace("5o5","+",$ct);
        $ct = str_replace("6o6","&",$ct);
        $content = str_replace("7o7","\\",$ct);
        echo imageToImg($monID,$db->editContentDapAn($cID,$content),300);
    }

    if(isset($_POST["maso_del"])) {
        $maso = $_POST["maso_del"];
        $db = new Cau_Hoi();
        $db->xoaCauHoiMulti($maso);
    }

    if(isset($_POST["hsID_the"])) {
        $hsID = $_POST["hsID_the"];
        $db = new Hoc_Sinh();
        $db->addLog($hsID, "Tặng thẻ miễn phạt phát hiện lỗi sai!", "the-mien-phat");
        $db->addThongBao("('$hsID','0','Bạn đã nhận được 1 thẻ miễn phạt do phát hiện lỗi sai ở đề','len-level','".(new Mon_Hoc())->getMonFirst($hsID)."',now(),'small','new')");
    }

    ob_end_flush();
?>


