
    <?php include_once "include/top_hoc_sinh.php"; ?>

    <?php
        $me = md5("123456");
        if(isset($_GET["deID"])) {
            $cc = $_GET["deID"];
            $deID = decodeData($_GET["deID"],$me);
            if(!validId($deID)) {
                header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/");
                exit();
            }
        } else {
            $cc = 0;
            $deID = 0;
        }
        $db = new Vao_Thi();
        $db2 = new De_Thi();
        $deID = $deID + 1 - 1;
        $deID_old = $deID;

//        $deID = $db2->getDeThiMainByDe($deID);
        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();

//        if(!isset($_SESSION["de-thi-$deID"])) {
//            $_SESSION["de-thi-$deID"] = array();
//        }
//        $cau_lam = array();
//        $se_need = "de-thi-$deID";
//        foreach ($_SESSION as $se_key => $se_value) {
//            if(is_array($se_value) && stripos($se_key, "-") != false) {
//                $temp = explode("-", $se_key);
//                if(count($temp) == 3) {
//                    $temp[2] = (int) $temp[2];
//                    if($temp[0] == "de" && $temp[1] == "thi" && $temp[2] == $deID) {
////                        echo $temp[0] . " - " . $temp[1] . " - " . $temp[2] . "<br />";
//                        if(count($se_value) > 0) {
//                            $cau_lam = $se_value;
//                            break;
//                        }
//                    }
//                }
//            }
//        }

        $monID = 0;

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
        $db3 = new Cau_Hoi();

        $hsID_en = $hsID;
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title text-center" style="padding-left: 36px;">-->
<!--                <h4>--><?php //echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><!--</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "include/sidebar_hoc_sinh.php"; ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Main form -->
                    <div class="col-lg-12" style="padding: 0;">
<!--                        <div class="panel-heading text-center">-->
<!--                            <h5 class="panel-title">--><?php //echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><!--</h5>-->
<!--                        </div>-->
                        <div class="panel panel-flat">
<!--                            <div class="col-sm-6">-->
<!--                                <div id="chart-container" style="width: 100%;height: 270px;margin-top: 5px;"></div>-->
<!--                            </div>-->
<!--                            <div class="col-sm-6">-->
<!--                                <table class="table bg-brown-400" id="list-thong-ke">-->
<!--                                    <tbody>-->
<!--                                    <tr>-->
<!--                                        <td class="text-center" id="tong-diem"></td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <td class="text-center" id="tong-time">Tổng thời gian làm</td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <td class="text-center" id="dung-nhanh">Câu đúng làm nhanh nhất</td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <td class="text-center" id="dung-cham">Câu đúng làm chậm nhất</td>-->
<!--                                    </tr>-->
<!--                                    </tbody>-->
<!--                                </table>-->
<!--                            </div>-->
<!--                            <div style="clear: both;height: 20px;"></div>-->
                            <table class="table noSwipe" id="list-bai-thi">
                                <colgroup>
                                    <col style="width: 60px;">
                                    <col>
                                </colgroup>
                                <tbody>
                                <?php
                                    $dem = 0;
                                    echo"<tr class='de-bai-cau-big de-bai de-bai-cau-$dem' data-cau='$dem'>
                                        <td colspan='2'>$data0[mota]</td>
                                    </tr>
                                    <tr class='dap-an-cau-$dem'>
                                        <td colspan='2'>Mã đề: $data0[maso]</td>
                                    </tr>
                                    <tr class='dap-an-cau-$dem'>
                                        <td colspan='2' id='tong-diem'></td>
                                    </tr>
                                    <tr class='dap-an-cau-$dem'>
                                        <td colspan='2'><div id='chart-container' style='width: 100%;height: 270px;'></div></td>
                                    </tr>";
                                    $dapan_hs = array();
                                    $result = $db2->getHocSinhDapAnByDe($hsID, $deID);
                                    while ($data = $result->fetch_assoc()) {
//                                        if(isset($cau_lam["cau-hoi-$data[ID_C]"]) && $data["ID_DA"] != $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"]) {
//                                            $dapan_hs[$data["ID_C"]] = array(
//                                                "daID" => $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"],
//                                                "note" => $cau_lam["cau-hoi-$data[ID_C]"]["note"],
//                                                "time" => $cau_lam["cau-hoi-$data[ID_C]"]["time"],
//                                                "stamp" => $cau_lam["cau-hoi-$data[ID_C]"]["datetime"]
//                                            );
//                                            $db->updateCauLam($hsID, $data["ID_C"], $deID, $cau_lam["cau-hoi-$data[ID_C]"]["time"], $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"], $cau_lam["cau-hoi-$data[ID_C]"]["note"]);
//                                        } else {
                                            $dapan_hs[$data["ID_C"]] = array(
                                                "daID" => $data["ID_DA"],
                                                "note" => $data["note"],
                                                "time" => $data["time"],
                                                "stamp" => $data["datetime"]
                                            );
//                                        }
//                                        if(isset($cau_lam["cau-hoi-$data[ID_C]"])) {
//                                            $cau_lam["cau-hoi-$data[ID_C]"] = NULL;
//                                            unset($cau_lam["cau-hoi-$data[ID_C]"]);
//                                        }
                                    }

//                                    $content = array();
//                                    foreach ($cau_lam as $key => $value) {
//                                        if(!is_array($value) || stripos($key, "cau-hoi-") === false) {
//
//                                        } else {
//                                            $temp = explode("-", $key);
//                                            $cID = end($temp);
//                                            if(validId($cID)) {
//                                                $content[] = "('$hsID','$cID','" . $cau_lam["cau-hoi-$cID"]["ID_DA"] . "','0','" . $cau_lam["cau-hoi-$cID"]["time"] . "','','$deID')";
//                                            }
//                                        }
//                                    }
//                                    if(count($content) > 0) {
//                                        $content = implode(",",$content);
//                                        $db2->addHocSinhDapAnMulti($hsID, $deID, $content, false);
//                                    }

                                    $dapan_all = array();
                                    $result = $db2->getDapAnNganByDeAll($deID);
                                    while($data = $result->fetch_assoc()) {
                                        $dapan_all[$data["ID_C"]][] = array(
                                            "ID_DA" => $data["ID_DA"],
                                            "main" => $data["main"],
                                            "type" => $data["type"],
                                            "content" => $data["content"]
                                        );
                                    }

                                    $binhluan = "";
                                    $debai = $dapan = $cau_sai = array();
                                    $diem = $dung = $sai = $draw = 0;
                                    $dem++;
                                    $min_time = $data0["time"]*60*1000;
                                    $max_time = $tong_time = $min_cau = $max_cau = 0;
                                    $result = $db2->getCauHoiByDeWithTimeLimit($deID);
                                    $sum=$result->num_rows;
                                    if($sum != 0) {
                                        $diem_per = 10 / $sum;
                                    } else {
                                        $diem_per = 0;
                                    }
                                    while($data = $result->fetch_assoc()) {
                                        $monID = $data["ID_MON"];
//                                        if(isset($dapan_hs[$data["ID_C"]])) {
//                                            $data["ID_DA"] = $dapan_hs[$data["ID_C"]]["daID"];
//                                            $data["khac"] = $dapan_hs[$data["ID_C"]]["note"];
//                                            $data["time"] = $dapan_hs[$data["ID_C"]]["time"];
//                                        if(isset($cau_lam["cau-hoi-$data[ID_C]"])) {
//                                            $data["ID_DA"] = $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"];
//                                            $data["time"] = $cau_lam["cau-hoi-$data[ID_C]"]["time"];
//                                            $data["khac"] = "";
//                                            $content[] = "('$hsID','$data[ID_C]','$data[ID_DA]','0','$data[time]','','$deID')";
                                        if(isset($dapan_hs[$data["ID_C"]])) {
                                            $data["ID_DA"] = $dapan_hs[$data["ID_C"]]["daID"];
                                            $data["khac"] = $dapan_hs[$data["ID_C"]]["note"];
                                            $data["time"] = $dapan_hs[$data["ID_C"]]["time"];
                                        } else {
                                            $data["ID_DA"] = NULL;
                                            $data["time"] = 0;
                                            $data["khac"] = "";
                                        }

                                        $tong_time += $data["time"];
                                        if($data["done"] == 0) {
                                            $back="#ffffb2";
                                            $cau_sai[$dem] = 1;
                                        } else {
                                            $back="#D1DBBD";
                                        }
                                        echo"<tr class='de-bai-cau-big de-bai de-bai-cau-$dem' style='background:$back;display: none;' data-cau='$dem'>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <span class='label bg-brown-600' style='font-size:14px;'>Câu $data[sort]:</span> ";
//                                                echo"<span class='label bg-grey-400' style='font-size:14px;margin: 0 10px 0 5px;'>".formatTimeBack($data["time"]/1000)."</span>";
                                            if($data["content"]!="none") {
                                                echo imageToImg($data["ID_MON"],$data["content"],250);
                                            }
                                            if($data["anh"]!="none"){
                                                echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDe($data["ID_MON"],$data["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
//                                                echo"<button type='button' class='btn btn-primary btn-sm bg-slate-400' data-toggle='modal' data-target='#modal_default_$dem' data-cau='$dem' style='float:right;'>Báo sai</button>";
                                            echo"</td>
                                        </tr>";
                                        $binhluan .= "<div id='modal_default_$dem' class='modal fade binh-luan'>
                                            <div class='modal-dialog'>
                                                <div class='modal-content'>
                                                    <form action='http://localhost/www/TDUONG/luyenthi/upload_file.php' method='post' enctype='multipart/form-data'>
                                                        <div class='modal-body'>
                                                            <h6 class='text-semibold'>Bình luận</h6>
                                                            <p><input type='text' class='form-control submit-text-bl-$dem' placeholder='Nội dung bình luận' /><input type='hidden' value='$data[ID_C]' name='cID' /><input type='hidden' value='$hsID' name='hsID' /></p>

                                                            <hr>

                                                            <h6 class='text-semibold'>hoặc Gửi ảnh</h6>
                                                            <p><input type='file' name='file' class='btn btn-primary btn-sm bg-slate-400 submit-file-bl-$dem' style='width: 100%;' data-show-caption='false' data-show-upload='false'></p>
                                                        </div>

                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-link submit-dong-$dem' data-dismiss='modal'>Đóng</button>
                                                            <button type='submit' data-cID='$data[ID_C]' data-cau='$dem' class='btn btn-danger gui-ok'>Báo sai</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>";
//                                        echo"<tr class='dap-an-cau-$dem binh-luan-cau-$dem binh-luan second-step'>
//                                            <td colspan='2'>
//                                                <table class='table datatable-basic'>
//                                                    <thead>
//                                                        <tr>
//                                                            <th colspan='2' style='padding-left: 0;padding-right: 0;'><input type='text' class='form-control' placeholder='Nội dung bình luận' /><input type='hidden' value='$data[ID_C]' name='cID' /><input type='hidden' value='$hsID' name='hsID' /></th>
//                                                            <th style='width: 15%;' class='text-center'>
//                                                                <button type='button' class='btn btn-primary btn-sm bg-slate-400 gui-ok' data-cID='$data[ID_C]' data-cau='$dem'>Báo sai</button>
//                                                            </th>
//                                                        </tr>
//                                                    </thead>
//                                                </table>
//                                            </td>
//                                        </tr>";
                                        $dem2=0;
                                        if(isset($data["ID_DA"])) {
                                            $daID_hs = $data["ID_DA"];
                                        } else {
                                            $daID_hs = 0;
                                        }
                                        $dapan[$dem] = $daID_hs;
                                        $debai[$dem] = 0;
                                        if(isset($dapan_all[$data["ID_C"]])) {
                                            $n = count($dapan_all[$data["ID_C"]]);
                                            for ($i = 0; $i < $n; $i++) {
                                                if ($dapan_all[$data["ID_C"]][$i]["main"] == 1) {
                                                    $debai[$dem] = $dapan_all[$data["ID_C"]][$i]["ID_DA"];
                                                }
                                                if ($dapan_all[$data["ID_C"]][$i]["main"] == 1 && $dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                    echo "<tr style='background-color: #2196F3;color:#FFF;display: none;' class='dap-an-cau-$dem'>";
                                                    if ($data["time"] < $min_time && $data["time"] != 0) {
                                                        $min_time = $data["time"];
                                                        $min_cau = $dem;
                                                    }
                                                    if ($data["time"] > $max_time) {
                                                        $max_time = $data["time"];
                                                        $max_cau = $dem;
                                                    }
                                                    $diem += $diem_per;
                                                    $dung++;
                                                } else if ($dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                    echo "<tr class='dap-an-cau-$dem' style='display: none;'>";
                                                } else if ($dapan_all[$data["ID_C"]][$i]["main"] == 1) {
                                                    echo "<tr style='background-color: #2196F3;color:#FFF;display: none;' class='dap-an-cau-$dem'>";
                                                    $sai++;
                                                } else {
                                                    echo "<tr class='dap-an-cau-$dem' style='display: none;'>";
                                                }
                                                echo "<td>
                                                    <div class='radio'>
                                                        <label>
                                                            <input type='radio' disabled='disabled' name='radio-dap-an-$dem' ";
                                                if ($dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                    echo "checked='checked'";
                                                }
                                                echo " class='control-danger radio-dap-an' data-cau='$dem' />    
                                                            " . $da_arr[$dem2] . ".
                                                        </label>
                                                    </div></td>
                                                    <td>";
                                                //                                                $khac = false;
                                                if ($dapan_all[$data["ID_C"]][$i]["type"] == "text") {
                                                    $dapan_temp = imageToImgDapan($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"], 250);
                                                    //                                                    if(stripos(unicodeConvert($dapan_temp),"dap-an-khac") === false) {} else {$khac=true;}
                                                    echo $dapan_temp;
                                                } else {
                                                    echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/" . $db3->getUrlDapAn($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"]) . "' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                                                }
                                                if ($dapan_all[$data["ID_C"]][$i]["main"] == 1) {
                                                    echo "<strong style='margin-left:10px;'>Đáp án đúng</strong>";
                                                }
                                                //                                                if($khac && $data["khac"] != "") {
                                                //                                                    echo"<input type='text' class='form-control' style='float: right;width: 100%;' value='$data[khac]' disabled='disabled' />";
                                                //                                                }
                                                echo "</td>
                                                </tr>";
                                                $dem2++;
                                            }
                                        }
                                        echo"<tr class='dap-an-cau-$dem' style='display: none;'>
                                            <td colspan='2' style='line-height: 30px;'>";
                                                if($data["da_con"] != "none") {
                                                    echo imageToImg($data["ID_MON"],$data["da_con"],300);
                                                }
                                                if($data["da_anh"] != "none"){
                                                    echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDe($data["ID_MON"],$data["da_anh"])."' style='max-height:300px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                                }
                                            echo"</td>
                                        </tr>";
                                        $dem++;
                                    }
                                    $dem--;
                                    $db4 = new Luyen_De();
                                    $diem = formatDiem($diem);
                                    if($data0["nhom"] != 0) {
                                        $result = $db4->getKetQuaLuyenDeByDe($deID, $hsID);
                                        if($result->num_rows == 0) {
                                            $db->addHocSinhInDe($hsID, $deID, $data0["nhom"], "out");
                                            $db4->newLuyenDe($deID, $hsID, "lam-tai-lop", $diem, $tong_time, $data0["ID_LM"]);
                                            $result = $db4->getKetQuaLuyenDeByDe($deID, $hsID);
                                            $data = $result->fetch_assoc();
                                            $tong_time = $data["time"];
                                            $diem = $data["diem"];
                                            $tong_time = (strtotime($data["out_time"]) - strtotime($data["in_time"]))*1000;
                                            if($tong_time != $data["time"] && $tong_time <= $data0["time"]*60*1000) {
                                                $db4->updateLuyenDe($deID, $hsID, $diem, $tong_time, $data0["ID_LM"]);
                                            }
                                        } else {
                                            $data = $result->fetch_assoc();
                                            $tong_time = $data["time"];
                                            $diem = $data["diem"];
                                        }
                                    } else {
                                        $db->addHocSinhTuLuyen($hsID, $deID, $diem, $tong_time, "out");
                                        $result = $db4->getTuLuyenDeHocSinh($hsID, $lmID, $deID);
                                        $data = $result->fetch_assoc();
                                        $tong_time = $data["time"];
                                        $diem = $data["diem"];
                                        $tong_time = (strtotime($data["out_time"]) - strtotime($data["in_time"]))*1000;
                                        if($tong_time != $data["time"] && $tong_time <= $data0["time"]*60*1000) {
                                            $db->addHocSinhTuLuyen($hsID, $deID, $diem, $tong_time, "time");
                                        }
                                    }
                                    if($min_time == $data0["time"]*60*1000) {
                                        $min_time = 0;
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <!-- /main content -->

        </div>

        <div style="height: 120px;"></div>
        <?php echo $binhluan; ?>
        <button type="button" class="btn btn-primary btn-xs bg-danger-400 btn-fixed" id="submit-nop-bai" data-toggle="modal" data-target="#modal_default_loi">PHÚC KHẢO</button>
        <div id='modal_default_loi' class='modal fade'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-body'>
                        <h6 class='text-semibold'>Mô tả vấn đề của bạn</h6>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label style="margin-top: 10px;">Ca thi</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" id="submit-ca" placeholder="7h - 9h" class="form-control">
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                        <div class="form-group">
                            <textarea rows="4" cols="5" name="submit-mota" id="submit-mota" style="resize: vertical;" class="form-control" placeholder="Mô tả"></textarea>
                        </div>
                        <p>Chỉ phúc khảo khi <strong>chấm sai</strong> hoặc <strong>chấm thiếu nhiều câu</strong></p>
                    </div>

                    <div class='modal-footer'>
                        <button type='button' class='btn btn-link' data-dismiss='modal' id="dong-ok">Đóng</button>
                        <button type='button' class='btn btn-danger' data-dismiss='modal' id='gui-ok'>Gửi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="position: fixed;bottom:-20px;left:0;z-index:99;width: 100%;padding: 0;margin: 0;">
            <div class="panel panel-flat">
                <div class="panel-heading" style="padding: 5px;text-align: center;">
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: left;" id="submit-cau-left"><span class="icon-chevron-left"></span></button>
<!--                    <button type="button" class="btn btn-primary btn-xs bg-danger-400" data-toggle="modal" data-target="#modal_default_loi">BÁO BÀI LỖI</button>-->
                    <button type="button" class="btn btn-primary btn-xs bg-danger-400" data-toggle="modal" data-target="#modal_default_0" id="submit-bao-sai">BÁO SAI</button>
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: right;" id="submit-cau-right"><span class="icon-chevron-right"></span></button>
                    <div style="clear: both;"></div>
                </div>
                <div class="panel-body" id="main-wapper" style="overflow-x: auto;padding: 0;">
                    <div></div>
                    <table class="table table-xs noSwipe" id="list-tom-tat">
                        <tbody>
                            <tr class="bg-brown-400">
                                <td class='text-center dap-an-eye bg-slate-400' style="min-width: 60px;cursor: pointer;">-</td>
                                <?php
                                $tomtat = "";
                                $n = count($debai);
                                for($i=1;$i<=$n;$i++) {
                                    if(isset($cau_sai[$i])) {
                                        echo"<td style='min-width: 60px;cursor: pointer;background-color: #ffffb2;' class='text-center dap-an-eye'>$i</td>";
                                    } else {
                                        echo"<td style='min-width: 60px;cursor: pointer;' class='text-center dap-an-eye'>$i</td>";
                                    }
                                    if($debai[$i] == $dapan[$i]) {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;background-color: #2196F3;color: #FFF;'>Đ</td>";
                                    } else if($dapan[$i] == "0") {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>_</td>";
                                    } else {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;background-color: #EF5350;color: #FFF;'>S</td>";
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <td class='text-center tom-tat-0 dap-an-eye bg-slate-400' style="cursor: pointer;">-</td>
                                <?php echo $tomtat; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <?php include_once "include/bottom_hoc_sinh.php"; ?>
        <script type="text/javascript">
//            window.onload = function () {
//                var myScroll = new IScroll('#main-wapper', {scrollX: true, scrollY: false, mouseWheel: false});
//            }
            $(document).ready(function() {
                $("body").addClass("sidebar-xs");
                var window_width = $(window).width();
                $(".navbar-brand span").html("<code id='count-time' style='font-size: 26px;'><strong><?php echo formatTimeBack($tong_time/1000); ?></strong></code><span id='count-time-progress' style='display: none;'>");
                var border_min = parseInt(((window_width/$("table#list-tom-tat tr td").length)/2));
                var border_max = $("table#list-tom-tat tr td").length - border_min;
//                console.log("border-len: " + border_max);
                var border = "4px solid yellow";
                bo_khung(0, "none");
                var td_width = $("table#list-tom-tat").width() / <?php echo $dem; ?>;
                td_width = td_width > 60 ? td_width : 60;
                function bo_khung(dem, type) {
//                    $("table#list-tom-tat tr td").css("border", "none");
                    $("table#list-tom-tat tr td").removeClass("border-up").removeClass("border-down").removeClass("border-full");
                    $("table#list-tom-tat tr:first td:eq(" + (dem) + ")").addClass("border-up");
//                        $("table#list-tom-tat tr:first td:eq(" + (dem) + ")").css({
//                            "border-left": border,
//                            "border-top": border,
//                            "border-right": border
//                        });
                    $("table#list-tom-tat tr:last td:eq(" + (dem) + ")").addClass("border-down");
//                        $("table#list-tom-tat tr:last td:eq(" + (dem - 1) + ")").css({
//                            "border-left": border,
//                            "border-bottom": border,
//                            "border-right": border
//                        });
                    if(dem > border_min && type == "right") {
                        console.log(dem + " - " + type);
                        $("table#list-tom-tat").closest("div").animate({scrollLeft:'+=' + td_width},250);
                    } else if(dem < border_max && type == "left") {
                        $("table#list-tom-tat").closest("div").animate({scrollLeft:'-=' + td_width},250);
                    }
                }
                var dem_first = $("table#list-bai-thi tr.de-bai-cau-big:first").attr("data-cau");
                $("table#list-bai-thi tr.de-bai-cau-big:first").addClass("cau-active").show();
                $("table#list-bai-thi tr.dap-an-cau-" + dem_first).show();
                $("button#submit-cau-left").css("opacity","0");
                $("button#submit-cau-left").click(function() {
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    if(my_stt > 0) {
                        cur_cau.removeClass("cau-active").hide();
                        $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                        var now_stt = my_stt - 1;
                        $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                        $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                        $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
                        $("button#submit-cau-right").css("opacity","1");
                        if(now_stt == 0) {
                            $("button#submit-cau-left").css("opacity","0");
                        }
                        bo_khung(now_stt, "left");
                    }
                });
                $("button#submit-cau-right").click(function() {
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    if(my_stt < <?php echo $dem; ?>) {
                        cur_cau.removeClass("cau-active").hide();
                        $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                        var now_stt = my_stt + 1;
                        $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                        $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                        $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
                        $("button#submit-cau-left").css("opacity","1");
                        if(now_stt == <?php echo $dem; ?>) {
                            $("button#submit-cau-right").css("opacity","0");
                        }
                        bo_khung(now_stt, "right");
                    }
                });
                $("#tong-diem").html("<strong>Điểm: <code style='font-size: 26px;'><?php echo $diem; ?></code></strong>");

                $("table#list-tom-tat tr .dap-an-eye").click(function() {
                    var now_stt = $(this).index();
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    cur_cau.removeClass("cau-active").hide();
                    $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                    $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                    $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                    $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
                    if(now_stt == 0) {
                        $("button#submit-cau-left").css("opacity","0");
                    } else if(now_stt == <?php echo $dem; ?>) {
                        $("button#submit-cau-right").css("opacity","0");
                    } else {
                        $("button#submit-cau-left, button#submit-cau-right").css("opacity","1");
                    }
                    bo_khung(now_stt, "none");
                });
                $(".second-step").hide();
                $(".binh-luan form").ajaxForm({
                    beforeSend: function() {

                    },
                    complete: function(xhr) {
                        var res = xhr.responseText;
                        if(res == "none") {
                            console.log(res);
//                            new PNotify({
//                                text: 'Dữ liệu không đầy đủ!',
//                                addclass: 'bg-danger'
//                            });
                        } else if(res == "id") {
                            new PNotify({
                                text: 'Dữ liệu không chính xác!',
                                addclass: 'bg-danger'
                            });
                        } else if(res == "size-type") {
                            new PNotify({
                                text: 'Ảnh phải dưới 2MB và có định dạng JPG, JPEG, PNG!',
                                addclass: 'bg-danger'
                            });
                        } else {
                            new PNotify({
                                text: 'Upload thành công!',
                                addclass: 'bg-primary'
                            });
                            new PNotify({
                                text: '<img src=\"' + res + '\" style="max-width: 200px;height: auto;" />',
                                addclass: 'bg-primary'
                            });
                        }
                    }
                });
                $("button.gui-ok").click(function () {
                    var me = $(this);
                    var my_cau = $(this).attr("data-cau");
                    var cID = $(this).attr("data-cID");
                    var content = $("input.submit-text-bl-" + my_cau).val().trim().replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                    if (cID != "" && content != "" && valid_id(my_cau) && !me.hasClass("disabled")) {
                        me.addClass("disabled");
                        $.ajax({
                            async: true,
                            data: "cID=" + cID + "&content=" + content + "&hsID=<?php echo $hsID_en; ?>" + "&lmID=" + <?php echo $data0["ID_LM"]; ?> +"&all=0",
                            type: "get",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau/",
                            success: function (result) {
                                result = result.trim();
                                //$("table#list-bai-thi tr.binh-luan-cau-" + my_cau + " td:eq(0) table tbody").html(result);
                                //me.removeClass("disabled");
                                $("input.submit-text-bl-" + my_cau).val("").attr("placeholder", "Đã gửi");
                                if (result == "none") {
                                    new PNotify({
                                        text: 'Dữ liệu không chính xác!',
                                        addclass: 'bg-danger'
                                    });
                                } else if (result == "out") {
                                    new PNotify({
                                        text: 'Bạn đã hết phiên đăng nhập do xuất hiện gián đoạn Hãy refresh!',
                                        addclass: 'bg-danger'
                                    });
                                } else {
                                    new PNotify({
                                        text: 'Bạn đã bình luận thành công!',
                                        addclass: 'bg-primary'
                                    });
                                }
                                $(".submit-dong-" + my_cau).click();
                            }
                        });
                        return false;
                    } else {
                        $("input.submit-text-bl-" + my_cau).val("");
                        $(".submit-dong-" + my_cau).click();
                        console.log("binh luan none");
                    }
                });
                $("button#gui-ok").click(function () {
                    var ca = $("#submit-ca").val().trim().replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                    var content = $("#submit-mota").val().trim().replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                    if(content != "" && ca != "") {
                        $.ajax({
                            async: true,
                            data: "text=" + content + "&ca=" + ca + "&deID=<?php echo $deID; ?>&hsID=<?php echo $hsID; ?>",
                            type: "get",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau/",
                            success: function (result) {
                                result = result.trim();
                                if (result == "none") {
                                    new PNotify({
                                        text: 'Dữ liệu không chính xác!',
                                        addclass: 'bg-danger'
                                    });
                                } else {
                                    new PNotify({
                                        text: 'Bạn đã bình luận thành công!',
                                        addclass: 'bg-primary'
                                    });
                                }
                                $("button#dong-ok").click();
                            }
                        });
                    }
                });
                new PNotify({
                    text: 'Bạn nên ghi những câu sai ra một cuốn vở để làm lại và chắc chắn rằng sau gặp lại dạng đó sẽ không bị sai nữa!',
                    addclass: 'bg-primary'
                });
                document.oncontextmenu = new Function("return false");
            });
        </script>
        <script type="text/javascript">
            window.onload = function () {
                var chart = new CanvasJS.Chart("chart-container",
                    {
                        animationEnabled: false,
                        interactivityEnabled: false,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: true,
                            enabled: false,
                        },
                        backgroundColor: "",
                        legend:{
                            fontFamily: "helvetica",
                            horizontalAlign: "center",
                            fontSize: 14,
                            enabled: false,
                        },
                        axisY: {
                            interval: 1,
                        },
                        data: [{
                            type: "pie",
                            startAngle: -90,
                            innerRadius: "75%",
                            showInLegend: false,
                            legendText: "{name}",
                            indexLabelFontFamily:"helvetica",
                            indexLabelFontColor: "brown",
                            indexLabelFontSize: 16,
                            indexLabelPlacement: "outside",
                            indexLabelFontWeight: "bold",
                            toolTipContent: "<a href = {content}>{name}: {y}%</a>",
                            dataPoints: [
                                <?php
                                    $total_all = $dem;
                                    $total = $dung + $sai;
                                    if($total_all != 0) {
                                        echo "{ y: " . formatNumber(($dung / $total_all) * 100) . ", name : 'Số câu đúng', indexLabel : 'Đúng $dung câu ({y}%)', content : '#', color: '#2196F3', indexLabelFontColor: '#2196F3' },";
                                        echo "{ y: " . formatNumber(($sai / $total_all) * 100) . ", name : 'Số câu sai', indexLabel : 'Sai $sai câu ({y}%)', content : '#', color: '#8D6E63', indexLabelFontColor: '#8D6E63' },";
                                        if ($total != $total_all) {
                                            echo "{ y: " . formatNumber((($total_all - $total) / $total_all) * 100) . ", name : 'Ko có đáp án', indexLabel : 'Ko có đáp án " . ($total_all - $total) . " câu ({y}%)', content : '#', color: '#000', indexLabelFontColor: '#000' },";
                                        }
                                    }
                                ?>
                            ]
                        }]
                    });
                chart.render();
            }
        </script>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML-full"></script>
        <script>
            MathJax.Hub.Config({
                showProcessingMessages: false,
                messageStyle: "none",
                tex2jax: {
                    inlineMath: [["$", "$"], ["\\(", "\\)"], ["\\[", "\\]"]],
                    processEscapes: false
                },
                showMathMenu: false,
                displayAlign: "left",
                jax: ["input/TeX","output/NativeMML"],
                "fast-preview": {disabled: true},
                NativeMML: { linebreaks: { automatic: true }, minScaleAdjust: 110, scale: 110},
                TeX: { noErrors: { disabled: true } },
            });
        </script>

        <?php
//            $_SESSION["de-thi-$deID"] = NULL;
//            unset($_SESSION["de-thi-$deID"]);
        ?>
