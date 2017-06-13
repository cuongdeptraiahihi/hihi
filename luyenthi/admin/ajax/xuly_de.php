<?php
    ob_start();
    session_start();
    require_once "../../model/model.php";
    require_once "../access_admin.php";

    if(isset($_POST["oID"]) && isset($_POST["action"])) {
        $oID = $_POST["oID"];
        $action = $_POST["action"];
        (new Options)->delOptions($oID);
    }

    if(isset($_POST["cID"]) && isset($_POST["deID2"])) {
        $cID = $_POST["cID"];
        $deID = $_POST["deID2"];
        $db = new De_Thi();
        $db->deleteCauHoiInDe($cID, $deID);
    }

    if(isset($_POST["hsID_del"]) && isset($_POST["deID_del"])) {
        $hsID = $_POST["hsID_del"];
        $deID = $_POST["deID_del"];
        $db = new Thong_Ke();
        $db->cleanKetQuaDeThi($hsID, $deID, false);
    }

    if(isset($_POST["nID_count"]) && isset($_POST["lmID"]) && isset($_POST["loai"])) {
        $nID = $_POST["nID_count"];
        $lmID = $_POST["lmID"];
        $loai = $_POST["loai"];
        $db = new Thong_Ke();
        $num = $db->countHocSinhDoneLam($nID, $lmID);
        $num_ex = $db->countHocSinhChuaLam($nID, $loai, $lmID);
        echo"<a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$nID/' style='color:green;font-weight:600;'>".$num."</a> / <a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/chua-lam/$nID/' style='color:red;font-weight:600;'>".$num_ex."</a>";
    }

    if(isset($_POST["nIDcd_count"]) && isset($_POST["lmID"]) && isset($_POST["numall"])) {
        $nID = $_POST["nIDcd_count"];
        $lmID = $_POST["lmID"];
        $numall = $_POST["numall"];
        $db = new Thong_Ke();
        $num = $db->countHocSinhDoneLam($nID,$lmID);
        $num_ex = $numall - $num;
        echo"<a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$nID/' style='color:green;font-weight:600;'>".$num."</a> / <a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/chua-lam/$nID/' style='color:red;font-weight:600;'>".$num_ex."</a>";
    }

    if(isset($_POST["deID"])) {
        $deID = $_POST["deID"];
        if(validId($deID)) {
            (new De_Thi())->xoaDeThi($deID);
            echo"ok";
        }
    }

    if(isset($_POST["nID1"])) {
        $nID = $_POST["nID1"];
        if(validId($nID)) {
            $db = new De_Thi();
            $db->xoaNhomDeThi($nID);
            echo"ok";
        }
    }

    if(isset($_POST["nID2"])) {
        $nID = $_POST["nID2"];
        if(validId($nID)) {
            (new Nhom_Cau_Hoi())->saiNhomDe($nID);
            echo"ok";
        }
    }
    if(isset($_POST["nID3"])) {
        $nID = $_POST["nID3"];
        if(validId($nID)) {
            (new Nhom_Cau_Hoi())->kosaiNhomDe($nID);
            echo"ok";
        }
    }

    if(isset($_POST["nID"]) && isset($_POST["action"])) {
        $nID = $_POST["nID"];
        $action = $_POST["action"];
        if(validId($nID)) {
            $db = new De_Thi();
            switch ($action) {
                case "public":
                    $db->updateNhomDeStatus($nID, 1);
                    break;
                case "lock":
                    $db->updateNhomDeStatus($nID, 2);
                    break;
                case "hide":
                    $db->updateNhomDeStatus($nID, 0);
                    break;
                case "allow":
                    $db->updateNhomDeAllow($nID, 1);
                    break;
                case "notallow":
                    $db->updateNhomDeAllow($nID, 0);
                    break;
                default:
                    break;
            }
        }
//        showNewDe($nID);
    }

    function showNewDe($nID) {
        $me=md5("123456");
        $db = new De_Thi();
        $result = $db->getNhomDeById($nID);
        $data = $result->fetch_assoc();
        $deID = $db->getDeThiMainByNhom($data["ID_N"]);
        $result1 = $db->getDeThiById($deID);
        $data1 = $result1->fetch_assoc();
        echo"<td class='text-center'>$data[code]</td>
            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$deID/'>$data1[mota]</a></td>
            <td class='text-center'>" . $db->countDeNhom($data["ID_N"]) . "</td>
            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/chuyen-de/$deID/' target='_blank'><i class='icon-printer'></i> In</a></td>
            <td class='text-center'>" . formatStatus($data["public"]) . "</td>
            <td class='text-center'>
                <ul class='icons-list'>
                    <li class='dropdown'>
                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                            <i class='icon-menu9'></i>
                        </a>

                        <ul class='dropdown-menu dropdown-menu-right'>
                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/$data[ID_N]/'><i class='icon-printer'></i> Xuất / In</a></li>
                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data[ID_N]/'><i class='icon-eye'></i> Xem</a></li>
                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$deID/'><i class='icon-stats-dots'></i> Thống kê</a></li>
                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thu-bai/".$data["ID_N"]."/'><i class='icon-spell-check'></i> Thu bài</a></li>
                            <li class='public-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-check'></i> Public</a></li>
                            <li class='lock-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-lock'></i> Khóa</a></li>
                            <li class='hide-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-cross3'></i> Ẩn</a></li>
                        </ul>
                    </li>
                </ul>
            </td>";
    }

    if(isset($_POST["nhom_count"]) && isset($_POST["lmID_count"])) {
        $nhom = $_POST["nhom_count"];
        $lmID = $_POST["lmID_count"];
        $db4 = new Thong_Ke();
        $result_arr = array(
            "do" => $db4->countHocSinhDangLam($nhom),
            "done" => $db4->countHocSinhDoneLam($nhom,$lmID)
        );
        echo json_encode($result_arr);
    }

    if(isset($_POST["hsID"]) && isset($_POST["nhom"])) {
        $hsID = $_POST["hsID"];
        $nhom = $_POST["nhom"];
        $db = new Luyen_De();
        $dem = 0;
        $result = $db->getLamLaiByHocSinh($hsID, $nhom);
        while($data = $result->fetch_assoc()) {
            echo"<tr class='view-lam-lai' style='background-color: #ffffb2;'>
                <td class='text-center' colspan='3'>Lần ".($dem+1)." - ".formatDateTime($data["datetime"])."</td>
                <td class='text-center'>$data[diem]</td>
                <td class='text-center'>".formatTimeBack($data["time"]/1000)."</td>
                <td class='text-center' colspan='2'></td>
            </tr>";
            $dem++;
        }
    }

    if(isset($_POST["hsID4"]) && isset($_POST["nID4"])) {
        $hsID = $_POST["hsID4"];
        $nID = $_POST["nID4"];
        $db0 = new De_Thi();
        $deID = $db0->getDeThiMainByNhom($nID);
        $result = $db0->getDeThiById($deID);
        $data = $result->fetch_assoc();
        $db = new Hoc_Sinh();
        $db->addThongBao("('$hsID','$nID','Đã mở khóa bài thi $data[mota] cho bạn!','diem-thi','$data[ID_LM]',now(),'small','new')");
        echo $db->addHocSinhSpecial($hsID,$nID);
    }

    if(isset($_POST["sttID"])) {
        $sttID = $_POST["sttID"];
        (new Hoc_Sinh())->delHocSinhSpecial($sttID);
    }

    if(isset($_POST["deID_goc"]) && isset($_POST["de_arr"]) && isset($_POST["monID"])) {
        $deID = $_POST["deID_goc"];
        $de_arr = $_POST["de_arr"];
        $monID = $_POST["monID"];
        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
        $db = new De_Thi();
        $db2 = new Cau_Hoi();
        $result = $db->getDapAnByDeCount($deID,$de_arr);
        $dapan_arr = $dapan_con = $dapan_chart = $dapan_total = $dapan_count = array();
        while($data = $result->fetch_assoc()) {
            if(!isset($dapan_con[$data["ID_C"]])) {
                $dapan_con[$data["ID_C"]] = "";
                $dapan_total[$data["ID_C"]] = $dapan_count[$data["ID_C"]] = 0;
                $dapan_arr[] = $data["ID_C"];
            }
            $dem2 = $dapan_count[$data["ID_C"]];
            $num = $data["dem"];
            if($data["main"] == 1) {
                $dapan_con[$data["ID_C"]] .= "<tr class='dap-an-cau-$data[ID_C]' style='background-color: #2196F3;color:#FFF;cursor: pointer;'>";
                $dapan_chart[$data["ID_C"]][] = array(
                    "daID" => $data["ID_DA"],
                    "da" => $da_arr[$dem2],
                    "num" => $num,
                    "main" => true,
                    "mau" => "#2196F3"
                );
            } else {
                $dapan_con[$data["ID_C"]] .="<tr class='dap-an-cau-$data[ID_C]' style='cursor: pointer;'>";
                $mau = "#8D6E63";
                $dapan_chart[$data["ID_C"]][] = array(
                    "daID" => $data["ID_DA"],
                    "da" => $da_arr[$dem2],
                    "num" => $num,
                    "main" => false,
                    "mau" => $mau
                );
            }
                $dapan_con[$data["ID_C"]] .="<td>
                    <div class='radio' style='left: 25%;'>
                        <label>
                            ".$da_arr[$dem2].".
                        </label>
                    </div></td>
                    <td>($num) ";
                    if($data["type"]=="text") {
                        $dapan_con[$data["ID_C"]] .= imageToImgDapan($monID,$data["content"],250);
                    } else {
                        $dapan_con[$data["ID_C"]] .="<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDapAn($monID,$data["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                    }
                $dapan_con[$data["ID_C"]] .="</td>
            </tr>";
            $dapan_total[$data["ID_C"]] += $num;
            $dapan_count[$data["ID_C"]]++;
        }
        $result_arr = array();
        $n = count($dapan_arr);
        for($i = 0; $i < $n; $i++) {
            $cID = $dapan_arr[$i];
            $result_arr[$cID] = array(
                "content" => $dapan_con[$cID],
                "total" => $dapan_total[$cID],
                "chart" => $dapan_chart[$cID]
            );
        }
        echo json_encode($result_arr);
    }

    if(isset($_POST["to_excel"]) && isset($_POST["title"])) {
        $_SESSION["output"] = array($_POST["to_excel"], "hoc-sinh-out", $_POST["title"]);
        echo "ok";
    }

    if(isset($_POST["xoa-level-cau"]) && isset($_POST["xoa-level-de"])) {
        $xoa = $_POST["xoa-level-cau"];
        $deID = $_POST["xoa-level-de"];
        $xoa = json_decode($xoa, true);
        $n = count($xoa);
        $content = array();
        for($i = 0; $i < $n; $i++) {
            if(validId($xoa[$i]["cID"])) {
                $content[] = "'" . $xoa[$i]["cID"] . "'";
            }
        }
        $content = implode(",", $content);
        (new De_Thi())->deleteCauHoiInDeMulti($content, $deID);
    }

    if(isset($_POST["nhom_share"])) {
        $nhom = $_POST["nhom_share"];
        $db = new De_Thi();
        echo $db->shareDeThi($nhom);
    }

    if(isset($_POST["deID_more"]) && isset($_POST["hsID_more"]) && isset($_POST["phut"])) {
        $deID = $_POST["deID_more"];
        $hsID = $_POST["hsID_more"];
        $phut = $_POST["phut"];
        $db = new Thong_Ke();
        if($db->giaHanLamDe($hsID, $deID, $phut)) {
            echo "ok";
        } else {
            echo "no";
        }
    }

    if(isset($_POST["deID_ma"]) && isset($_POST["ma"]) && isset($_POST["nhom"])) {
        $deID = $_POST["deID_ma"];
        $maso = $_POST["ma"];
        $nhom = $_POST["nhom"];
        $db = new De_Thi();
        if(!$db->checkTonTaiMaso($maso, $nhom)) {
            $db->updateMasoDeThi($deID, $maso);
            echo "ok";
        } else {
            echo "no";
        }
    }

    ob_end_flush();
?>


