
    <?php
        ini_set('max_execution_time', 300);
        include_once "../include/top.php";
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
    ?>

    <?php
        if(isset($_GET["deID"]) && is_numeric($_GET["deID"])) {
            $deID=$_GET["deID"];
        } else {
            $deID = 0;
        }
        $db = new De_Thi();
        $result0 = $db->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();
        $db2 = new Mon_Hoc();
        $monID = $db2->getMonOfLop($data0["ID_LM"]);
        $db2 = new Cau_Hoi();
        $de = (new Loai_De())->getNameLoaiDeById($data0["loai"]);
        $dokho = array();
        $db3 = new Do_Kho();
        $result3 = $db3->getDoKho();
        while($data3 = $result3->fetch_assoc()) {
            $dokho[$data3["ID_K"]] = array(
                "name" => $data3["name"],
                "muc" => $data3["muc"]
            );
        }

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Xem đề thi --><?php //echo $data0["maso"]; ?><!--</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "../include/sidebar.php"; ?>

            <?php
                $error = false;
                $error_msg = "";
                $sl = 0;
                if(isset($_POST["xuat-word"])) {
                    $isda = $isdetail = 0;
                    if(isset($_POST["submit-include-da"]) && !empty($_POST["submit-include-da"])) {
                        $isda = 1;
                    }
                    if(isset($_POST["submit-include-detail"]) && !empty($_POST["submit-include-detail"])) {
                        $isdetail = 1;
                    }
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/word-de/$deID/$isda/$isdetail/");
                    exit();
                }
                if(isset($_POST["xao-de"])) {
                    if(isset($_POST["submit-sl"]) && !empty($_POST["submit-sl"])) {
                        $sl = $_POST["submit-sl"];
                        if($sl > 1)
                            $sl--;
                    }
                    if($sl > 0 && is_numeric($sl)) {
                        $num = strlen($data0["maso"]);

                        $cau_arr = $dapan_arr = $khac_arr = array();
                        if($data0["form"]==2) {
                            $de_config = array();
                            $result = $db->getDePreNoiDung($deID);
                            while($data = $result->fetch_assoc()) {
                                $de_config[] = array(
                                    "cdID" => $data["ID_CD"],
                                    "sl" => $data["sl"],
                                    "loai" => $data["loai"],
                                    "kho" => $data["kho"],
                                    "option" => $data["options"],
                                    "option_no" => $data["options_no"],
                                    "dapan_e" => $data["dapan_e"],
                                    "is_bl" => $data["is_bl"],
                                    "goc" => $data["goc"],
                                    "nhan_ban" => $data["nhan_ban"],
                                    "luuygoc" => $data["luuygoc"],
                                    "isUnlock" => $data["isUnlock"],
                                    "isSort" => $data["isSort"]
                                );
                            }
                            if($db->countCauOnDe($deID) == 0) {
                                for($i = 0; $i < count($de_config); $i++) {
                                    $db->taoDeNoiDung($de_config[$i]["cdID"], $de_config[$i]["sl"], $de_config[$i]["loai"], $de_config[$i]["kho"], $de_config[$i]["option"], $de_config[$i]["option_no"], $deID, $de_config[$i]["dapan_e"], $de_config[$i]["is_bl"], $de_config[$i]["goc"], $de_config[$i]["nhan_ban"], $de_config[$i]["luuygoc"], $de_config[$i]["isUnlock"], $de_config[$i]["isSort"], $lmID, 1);
                                }
//                                $stt = 1;
//                                $result2 = $db->getCauHoiByDe($deID, true);
//                                while ($data2 = $result2->fetch_assoc()) {
//                                    $db->updateCauSortDeThi($deID, $data2["ID_C"], $stt);
//                                    $stt++;
//                                }
                            }
                        } else {
                            $de_config = array();
                            $result = $db->getCauHoiByDe($deID, true);
                            while ($data = $result->fetch_assoc()) {
                                $cau_arr[] = $data["ID_C"];
                                $dapan_arr[$data["ID_C"]] = $khac_arr[$data["ID_C"]] = array();
                                $khac = $dae = false;
                                $result2 = $db->getDapAnNganByDe($data["ID_C"], $deID, true);
                                while ($data2 = $result2->fetch_assoc()) {
                                    if ($data2["how"] == 0) {
                                        if (stripos(unicodeConvert($data2["content"]), "dap-an-khac") === false) {
                                        } else {
                                            $khac = true;
                                        }
                                        if (stripos(unicodeConvert($data2["content"]), "em-khong-lam-duoc") === false) {
                                        } else {
                                            $dae = true;
                                        }
                                        continue;
                                    }
                                    $dapan_arr[$data["ID_C"]][] = $data2["ID_DA"];
                                }
                                if ($khac) {
                                    $result2 = $db2->getDapAnKhac($data["ID_C"]);
                                    $data2 = $result2->fetch_assoc();
                                    $khac_arr[$data["ID_C"]][] = $data2["ID_DA"];
                                }
                                if ($dae) {
                                    $result2 = $db2->getDapAnKoLam($data["ID_C"]);
                                    $data2 = $result2->fetch_assoc();
                                    $khac_arr[$data["ID_C"]][] = $data2["ID_DA"];
                                }
                            }
                        }

                        $maso_arr = array();
                        $result1 = $db->getAllMasoNhomDe($data0["nhom"]);
                        while($data1 = $result1->fetch_assoc()) {
                            $maso_arr[$data1["maso"]] = 1;
                        }

                        for($i=1;$i<=$sl;$i++) {
//                            $maso = randMaso($num);
                            $maso = NULL;
//                            $count = 0;
                            while(true) {
                                $maso = randMaso($num);
                                if(!isset($maso_arr[$maso])) {
                                    $maso_arr[$maso] = 1;
                                    break;
                                }
//                                $count++;
//                                if($count == 9*9*9*9) {
//                                    $num++;
//                                }
                            }
                            if($maso) {
                                $sort = 1;
                                $deID_new = $db->taoDe($maso, $data0["mota"], $lmID, $data0["is_bl"], $data0["time"], $data0["nhom"], 0, $data0["loai"], $data0["form"]);
                                if($data0["form"] == 2) {
                                    for($j = 0; $j < count($de_config); $j++) {
                                        $db->taoDeNoiDung($de_config[$j]["cdID"], $de_config[$j]["sl"], $de_config[$j]["loai"], $de_config[$j]["kho"], $de_config[$j]["option"], $de_config[$j]["option_no"], $deID_new, $de_config[$j]["dapan_e"], $de_config[$j]["is_bl"], $de_config[$j]["goc"], $de_config[$j]["nhan_ban"], $de_config[$j]["luuygoc"], $de_config[$j]["isUnlock"], $de_config[$j]["isSort"], $lmID, 1);
                                    }
                                } else {
                                    shuffle($cau_arr);
                                    $content = "";
                                    $content_da = "";
                                    for ($j = 0; $j < count($cau_arr); $j++) {
                                        $content .= ",('$deID_new','$cau_arr[$j]','$sort')";
//                                        $db->addCauDeThi($deID_new, $cau_arr[$j], $sort);
                                        $dem = 1;
                                        shuffle($dapan_arr[$cau_arr[$j]]);
                                        for ($k = 0; $k < count($dapan_arr[$cau_arr[$j]]); $k++) {
                                            $content_da .= ",('$deID_new','" . $dapan_arr[$cau_arr[$j]][$k] . "','$dem')";
                                            $dem++;
                                        }
                                        for ($k = 0; $k < count($khac_arr[$cau_arr[$j]]); $k++) {
                                            $content_da .= ",('$deID_new','" . $khac_arr[$cau_arr[$j]][$k] . "','$dem')";
                                            $dem++;
                                        }
                                        $sort++;
                                    }
                                    $content = substr($content, 1);
                                    $db->addCauDeThiMulti($content);
                                    $content_da = substr($content_da, 1);
                                    $db->addDapAnDeThiMulti($content_da);
                                }
                            }
                        }
                    } else {
                        $error = true;
                        $error_msg = " Vui lòng nhập đầy đủ thông tin!";
                    }

                    if(!$error) {
                        header("location:http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data0[nhom]/");
                        exit();
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/xem-de/<?php echo $deID; ?>/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                    <div class="col-lg-8">
                        <div class="panel-heading">
                            <h5 class="panel-title">Đề thi <?php echo $data0["maso"]; ?></h5>
                        </div>

                            <?php if($data0["form"]==2) {
                                $dem=0;
                            ?>
                            <div class="panel panel-flat">
                                <div class="panel-body">
                                    <p class="content-group">Đây là <strong>Đề thi động</strong> nên bạn hãy ấn <strong>Xáo đề</strong> để tạo các câu hỏi cho đề thi này (và các đề thi được xáo)</p>
                                </div>
                            </div>
                            <?php } ?>
<!--                            <table class="table" id="list-search">-->
<!--                                <tr>-->
<!--                                    <td colspan="3"><input type="text" id="search-cau-hoi" class="form-control" placeholder="Mã câu hỏi (H07-05a-1a, D08-06a-3c)" /></td>-->
<!--                                </tr>-->
<!--                            </table>-->

                        <div class="panel panel-flat">
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col style="width:20%;" />
                                    <col style="width:80%;" />
                                <?php
                                    $mau_arr = array("#69b42e","blue","orange","red","brown","black");

                                    $dapan_all = $dacau_arr = array();
                                    $result = $db->getDapAnNganByDeAll($deID);
                                    while($data = $result->fetch_assoc()) {
                                        if(!isset($dapan_all[$data["ID_C"]])) {
                                            $dapan_all[$data["ID_C"]] = "";
                                            $dacau_arr[$data["ID_C"]] = 0;
                                        }
                                        $dem2 = $dacau_arr[$data["ID_C"]];
                                        $dapan_all[$data["ID_C"]] .= "
                                            <tr class='dap-an-cau-{dem}'>
                                                <td>
                                                <div class='radio' style='left: 25%;'>
                                                    <label>
                                                        <input type='radio' data-id='$data[ID_DA]' disabled='disabled' name='radio-dap-an-{dem}' ";if($data["main"]==1){$dapan_all[$data["ID_C"]] .="checked='checked'";}$dapan_all[$data["ID_C"]] .=" class='control-primary radio-dap-an' data-temp='$data[ID_DA]' data-cau='{dem}' data-stt='$dem2'/>
                                                        ".$da_arr[$dem2].".
                                                    </label>
                                                </div></td>
                                                <td>";
                                                    if($data["type"] == "text") {
                                                        $dapan_all[$data["ID_C"]] .= imageToImgDapan($monID,$data["content"],250);
                                                    } else {
                                                        $dapan_all[$data["ID_C"]] .="<img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDapAn($monID,$data["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' />";
                                                    }
                                                $dapan_all[$data["ID_C"]] .="</td>
                                            </tr> 
                                        ";
                                        $dacau_arr[$data["ID_C"]]++;
                                    }

                                    $dem=1;
                                    $result = $db->getCauHoiByDeWithTimeLimit($deID);
                                    while($data=$result->fetch_assoc()) {
                                        if($data["done"] == 0) {
                                            $back="#ffffb2";
                                        } else {
                                            $back="#D1DBBD";
                                        }
                                        echo"<tr class='de-bai-cau-big de-bai-cau-$dem' data-dem='$dem' style='background:$back;'>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <span class='label bg-brown-600' style='font-size:14px;margin-right: 5px;'>Câu $data[sort] ($data[maso]):</span><span class='label' style='background-color: ".$mau_arr[$dokho[$data["ID_K"]]["muc"]].";margin-right: 5px;'>".$dokho[$data["ID_K"]]["name"]."</span> ";
                                            if($data["content"]!="none") {
                                                echo imageToImg($data["ID_MON"],$data["content"],250);
                                            }
                                            if($data["anh"]!="none"){
                                                echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data["ID_MON"],$data["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
                                        echo"<button type='button' class='btn btn-primary btn-sm bg-danger-400 xoa-ok' data-cID='$data[ID_C]' data-kID='$data[ID_K]' style='float:right;'><i class='icon-cross3'></i></button>";
                                        echo"<a href='http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$data[ID_C]/' target='_blank' class='btn btn-primary btn-sm bg-slate-400' style='float:right;'><i class='icon-pencil3'></i></a>";
                                        echo"</td></tr>
                                        <tr>
                                            <td colspan='2' class='view-hide'><button type='button' class='btn btn-primary btn-sm bg-primary-400 view-detail-ok'>Đáp án chi tiết</button></td>
                                            <td colspan='2' class='view-detail' style='display:none;line-height: 30px;'>";
                                            if($data["da_con"] != "none") {
                                                echo imageToImg($data["ID_MON"],$data["da_con"],300);
                                            }
                                            if($data["da_anh"] != "none"){
                                                echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data["ID_MON"],$data["da_anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
                                            echo"</td>
                                        </tr>";
                                        echo str_replace("{dem}",$dem,$dapan_all[$data["ID_C"]]);
                                        $dem++;
                                    }
                                    $dem--;
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /striped rows -->
                    </div>
                    <button type="button" class="btn btn-primary btn-sm bg-slate-400 btn-fixed">Đề: <?php echo $de; ?></button>
                    <div class="col-lg-4" id="list-danh-sach">
                        <div class="panel-heading">
                            <h5 class="panel-title">Mô tả - Xáo đề</h5>
                        </div>
                        <div class="panel panel-flat">

                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-sm-12">Mã đề: <strong><?php echo $data0["maso"]; ?></strong> (<?php echo $dem; ?> câu)</label>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-sm-12">Ngày tạo: <?php echo formatDateTime($data0["ngay"]); ?></label>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-sm-12">Trạng thái: <?php echo formatStatus($data0["public"]); ?></label>
                                    </div>
                                    <?php
                                    if($data0["main"] == 1) {
                                        echo"<div class='form-group' style='margin-bottom: 0;'>
                                            <label class='control-label col-sm-12'><span class='label bg-primary-400'>Đề gốc</span></label>
                                        </div>";
                                    } else {
                                        echo"<div class='form-group' style='margin-bottom: 0;'>
                                            <label class='control-label col-sm-12'><span class='label bg-brown-400'>Đề xáo</span></label>
                                        </div>";
                                    }
                                    ?>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-sm-12">Thời gian làm: <strong><?php echo $data0["time"]; ?> phút</strong></label>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-12">Loại đề: <strong><?php echo $de; ?></strong></label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <textarea disabled="disabled" rows="4" cols="5" name="submit-mota" style="resize: vertical;" class="form-control"><?php echo $data0["mota"]; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-6">Số lượng đề</label>
                                        <div class="col-sm-6">
                                            <input type="number" name="submit-sl" class="form-control" min="1" value="1" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="button" name="xoa-de" class="btn btn-primary btn-sm bg-danger-400">Xóa</button>
                                            <button type="button" name="in-de" class="btn btn-primary btn-sm bg-blue-400" onclick="location.href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/web-de/<?php echo $deID; ?>/'">In đề</button>
                                            <?php if($data0["main"] == 1) { ?>
                                            <button type="submit" name="xao-de" class="btn btn-primary btn-sm bg-blue-400 import-cau-hoi">Xáo đề</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Tùy chỉnh</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-sm-6">Xóa level</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="chon-level">
                                            <?php
                                                foreach ($dokho as $key => $value) {
                                                    echo"<option value='$key'>$value[name]</option>";
                                                }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="button" id="xoa-level" class="btn btn-primary btn-sm bg-blue-400">Xóa câu</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Kiểm tra đề</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <p class="content-group" id="check-error"></p>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Xuất ra Word</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" id="submit-include-da" checked="checked" name="submit-include-da">
                                                    Bao gồm đáp án đúng
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" id="submit-include-detail" checked="checked" name="submit-include-detail">
                                                    Bao gồm đáp án chi tiết
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="submit" id="xuat-word" name="xuat-word" class="btn btn-primary btn-sm bg-blue-400">Xuất</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                <!-- /main form -->
                </form>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                var error_msg = "";
                $("table#list-bai-thi tr.de-bai-cau-big").each(function(index, element) {
                    var dem = $(element).attr("data-dem");
                    if($("table#list-bai-thi tr.dap-an-cau-" + dem).length != 4) {
                        error_msg += "+ Câu " + (index+1) + " bị lỗi số lượng đáp án!<br />";
                        $(element).css("background", "red");
                    }
                    var check_da = false;
                    $("table#list-bai-thi tr.dap-an-cau-" + dem).each(function(index, element) {
                        if($(element).find("td input").attr("checked") == "checked") {
                            check_da = true;
                        }
                    });
                    if(!check_da) {
                        error_msg += "+ Câu " + (index+1) + " bị lỗi đáp án đúng!<br />";
                        $(element).css("background", "red");
                    }
                });
                var count_anh = 0;
                $("table#list-bai-thi tr td img").each(function(index, element) {
                    var img_width = $(element).width();
                    var img_height = $(element).height();
                    if((img_width < 60 || img_height < 60) && img_width > 10 && img_height > 10) {
                        $(element).closest("tr").css("background", "red");
                        count_anh++;
                    }
                });
                if(count_anh != 0) {
                    error_msg += "+ Có " + count_anh + " ảnh lỗi!<br />";
                }
                console.log(error_msg);
                $("#check-error").html(error_msg);

                $("#xoa-level").click(function () {
                    if(confirm("Bạn có chắc chắn không? Hành động không thể hoàn tác!")) {
                        var dokho = $("#chon-level").find("option:selected").val();
                        if($.isNumeric(dokho) && dokho != 0) {
                            new PNotify({
                                title: 'Xóa câu hỏi theo Level',
                                text: 'Đang tiến hành xóa câu hỏi khỏi đề thi!',
                                icon: 'icon-menu6'
                            });
                            var ajax_data = "[";
                            $("button.xoa-ok").each(function (index, element) {
                                if($(element).attr("data-kID") == dokho) {
                                    ajax_data += '{"cID":"' + $(element).attr("data-cID") + '"},';
                                }
                            });
                            ajax_data = ajax_data.replace(/,*$/,"");
                            ajax_data += "]";
                            if(ajax_data != "[]") {
                                $.ajax({
                                    async: true,
                                    data: "xoa-level-cau=" + ajax_data + "&xoa-level-de=<?php echo $deID; ?>",
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                                    success: function (result) {
                                        new PNotify({
                                            title: 'Xóa câu hỏi theo Level',
                                            text: 'Xóa thành công!',
                                            icon: 'icon-menu6'
                                        });
                                        location.reload();
                                    }
                                });
                            }
                        }
                    } else {
                        return false;
                    }
                });

                $("#search-cau-hoi").typeWatch({
                    captureLength: 3,
                    callback: function (value) {
                        $.ajax({
                            async: true,
                            data: "search_ma_cau_hoi=" + value,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                $("table#list-search tr.tr-search").remove();
                                $("table#list-search").append(result);
                            }
                        });
                    }
                });

                $("table#list-bai-thi tr td button.view-detail-ok").click(function () {
                    $(this).closest("td").hide();
                    $(this).closest("tr").find("td.view-detail").fadeIn("fast");
                });

                $(".import-cau-hoi").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        return true;
                    } else {
                        return false;
                    }
                });

                $("button.xoa-ok").click(function () {
                    if(confirm("Bạn có chắc chắn xóa câu này khỏi đề thi?")) {
                        var cID = $(this).attr("data-cID");
                        if($.isNumeric(cID) && cID!=0) {
                            new PNotify({
                                title: 'Đề thi',
                                text: 'Đang tiến hành xóa câu hỏi khỏi đề thi!',
                                icon: 'icon-menu6'
                            });
                            $.ajax({
                                async: true,
                                data: "cID=" + cID + "&deID2=<?php echo $deID; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                                success: function (result) {
                                    new PNotify({
                                        title: 'Đề thi',
                                        text: 'Xóa thành công!',
                                        icon: 'icon-menu6'
                                    });
                                    //location.reload();
                                }
                            });
                        }
                    }
                });
            });
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
