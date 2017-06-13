
    <?php include_once "include/top_hoc_sinh.php"; ?>

    <?php

    if(isset($_POST["super-nop-bai"]) && isset($_POST["super-de"])) {
        header("location:http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/".addslashes($_POST["super-de"])."/");
        exit();
    }
        $me = md5("123456");
        if (isset($_GET["deID"])) {
            $deID = decodeData($_GET["deID"], $me);
            if (!validId($deID)) {
                header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/2/");
                exit();
            }
        } else {
            $deID = 0;
        }
        $db = new Vao_Thi();
        $db2 = new De_Thi();
        $db3 = new Cau_Hoi();
        $deID = $deID + 1 - 1;

        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();

        if($data0["nhom"] != 0) {
            $deID = $db->addHocSinhInDe($hsID, $deID, $data0["nhom"], "in");
        } else {
            $db->addHocSinhTuLuyen($hsID, $deID, 0, 0, "in");
        }

        $deID_en = encodeData($deID,$me);
        //        $hsID_en = encodeData($hsID,$me);
        $hsID_en = $hsID;

        $check_done = false;
        if($db2->checkHocSinhDoneLam($hsID, $data0["nhom"])) {
            $check_done = true;
            header("location:http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/$deID_en/");
            exit();
        }

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
//    //                        echo $temp[0] . " - " . $temp[1] . " - " . $temp[2] . "<br />";
//                        if(count($se_value) > 0) {
//                            $cau_lam = $se_value;
//                            break;
//                        }
//                    }
//                }
//            }
//        }

        $vao_thi = $db->getTimeHocSinhInDe($hsID,$deID,$data0["nhom"]);
        $in_time = strtotime($vao_thi);

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
        $time = ($data0["time"]+5)*60;
        $now = time();

        $start_time = $now - $in_time;

        $url = 5;
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title text-center" style="padding-left: 36px;">-->
<!--                <h4></h4>-->
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
                <form action="http://localhost/www/TDUONG/luyenthi/lam-bai-tai-lop/<?php echo $deID_en; ?>/" class="form-horizontal" method="post">
                    <input type="hidden" value="<?php echo $deID_en; ?>" name="super-de" />
                    <button type="submit" class="hidden" id="super-nop-bai" name="super-nop-bai">Nộp bài</button>
                </form>
                    <div class="col-lg-12" style="padding: 0;">
<!--                        <div class="panel-heading text-center" id="thoi-gian">-->
<!--                            <h5 class="panel-title">--><?php //echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><!--</h5>-->
<!--                            <code id="count-time" style="font-size: 26px;"></code>-->
<!--                            <span id="count-time-progress" style="display: none;"></span>-->
<!--                        </div>-->
                        <div class="panel panel-flat">
                            <table class="table noSwipe" id="list-bai-thi">
                                <colgroup>
                                    <col style="width: 60px;">
                                    <col>
                                </colgroup>
                                <tbody>
                                <?php
                                    $dapan = array();
                                    $dem = 0;
                                    if(!($start_time > $time) || $check_done) {
                                        $dapan_hs = array();
                                        $result = $db2->getHocSinhDapAnByDe($hsID, $deID);
                                        while ($data = $result->fetch_assoc()) {
//                                            if(isset($cau_lam["cau-hoi-$data[ID_C]"]) && (date_create($cau_lam["cau-hoi-$data[ID_C]"]["datetime"]) > date_create($data["datetime"]) || $data["ID_DA"] != $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"])) {
//                                                $dapan_hs[$data["ID_C"]] = array(
//                                                    "daID" => $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"],
//                                                    "note" => $cau_lam["cau-hoi-$data[ID_C]"]["note"],
//                                                    "time" => $cau_lam["cau-hoi-$data[ID_C]"]["time"],
//                                                    "stamp" => $cau_lam["cau-hoi-$data[ID_C]"]["datetime"]
//                                                );
//                                                if($data["ID_DA"] != $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"]) {
//                                                    $db->updateCauLam($hsID, $data["ID_C"], $deID, $cau_lam["cau-hoi-$data[ID_C]"]["time"], $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"], $cau_lam["cau-hoi-$data[ID_C]"]["note"]);
//                                                }
//                                            } else {
                                                $dapan_hs[$data["ID_C"]] = array(
                                                    "daID" => $data["ID_DA"],
                                                    "note" => $data["note"],
                                                    "time" => $data["time"],
                                                    "stamp" => $data["datetime"]
                                                );
//                                            }
                                        }

                                        $dapan_all = array();
                                        $result = $db2->getDapAnNganByDeAll($deID);
                                        while ($data = $result->fetch_assoc()) {
                                            $dapan_all[$data["ID_C"]][] = array(
                                                "ID_DA" => $data["ID_DA"],
                                                "main" => $data["main"],
                                                "type" => $data["type"],
                                                "content" => $data["content"]
                                            );
                                        }

//                                        $binhluan = "";
                                        $dem = 1;
                                        $result = $db2->getCauHoiByDeDangLam($deID);
                                        $num = mysqli_num_rows($result);
                                        while ($data = $result->fetch_assoc()) {
                                            if (isset($dapan_hs[$data["ID_C"]])) {
                                                $data["ID_DA"] = $dapan_hs[$data["ID_C"]]["daID"];
                                                $data["khac"] = $dapan_hs[$data["ID_C"]]["note"];
                                                $data["time"] = $dapan_hs[$data["ID_C"]]["time"];
                                            } else {
                                                $data["ID_DA"] = NULL;
                                                $data["time"] = 0;
                                                $data["khac"] = "";
                                            }
                                            $daID_hs = $data["ID_DA"];
                                            $pre_time = 0;
                                            if (isset($daID_hs) && is_numeric($daID_hs)) {
                                                echo "<tr class='de-bai-cau-big de-bai-cau-$dem cau-edited' data-cID='$data[ID_C]' data-cau='$dem' style='display: none;background:#D1DBBD;'>";
                                                if (isset($data["time"]) && is_numeric($data["time"])) {
                                                    $pre_time = $data["time"];
                                                }
                                            } else {
                                                $daID_hs = 0;
                                                echo "<tr class='de-bai-cau-big de-bai-cau-$dem' data-cID='$data[ID_C]' data-cau='$dem' style='display: none;background:#D1DBBD;'>";
                                            }
                                            echo "<td colspan='2' style='line-height: 30px;'>";
                                            $maso = "";
                                            if ($data0["nhom"] == 0) {
                                                $maso = " (" . $data["maso"] . ")";
                                            }
                                            if ($dem == $num) {
                                                echo "<span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>Câu $data[sort]$maso (CÂU CUỐI):</span> ";
                                            } else {
                                                echo "<span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>Câu $data[sort]$maso:</span> ";
                                            }
                                            if ($data["content"] != "none") {
                                                echo imageToImg($data["ID_MON"], $data["content"], 250);
                                            }
                                            if ($data["anh"] != "none") {
                                                echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/" . $db3->getUrlDe($data["ID_MON"], $data["anh"]) . "' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
                                            echo "<span class='count-time-$dem' style='display: none;' data-pre='$pre_time'></span></td></tr>";
                                            $dem2 = 0;
                                            $n = count($dapan_all[$data["ID_C"]]);
                                            for ($i = 0; $i < $n; $i++) {
                                                if ($daID_hs == $dapan_all[$data["ID_C"]][$i]["ID_DA"]) {
                                                    echo "<tr style='background-color: #ffffb2;display: none;' class='dap-an-con dap-an-cau-$dem dapan-chon'>";
                                                    $dapan[$dem] = $da_arr[$dem2];
                                                } else {
                                                    echo "<tr class='dap-an-con dap-an-cau-$dem' style='display: none;'>";
                                                }
                                                echo "<td><div class='radio'>
                                                    <label>
                                                        <input type='radio' name='radio-dap-an-$dem' ";
                                                if ($dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                    echo "checked='checked'";
                                                }
                                                echo " class='control-primary radio-dap-an' data-temp='" . $dapan_all[$data["ID_C"]][$i]["ID_DA"] . "' data-cau='$dem' data-stt='$dem2'/>
                                                        " . $da_arr[$dem2] . ".
                                                    </label>
                                                </div></td>
                                                <td>";
//                                                    $khac = false;
                                                if ($dapan_all[$data["ID_C"]][$i]["type"] == "text") {
                                                    $dapan_temp = imageToImgDapan($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"], 250);
//                                                        if(stripos(unicodeConvert($dapan_temp),"dap-an-khac") === false) {} else {$khac=true;}
                                                    echo $dapan_temp;
                                                } else {
                                                    echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/" . $db3->getUrlDapAn($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"]) . "' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                                                }
//                                                    if($khac) {
//                                                        echo"<input type='text' class='form-control dap-an-khac-text' data-dakhac='".$dapan_all[$data["ID_C"]][$i]["ID_DA"]."' style='float: right;width: 100%;' placeholder='Ghi đáp án khác, không ghi sẽ bị trừ điểm' value='$data[khac]' />";
//                                                    }
                                                echo "</td>
                                            </tr>";
                                                $dem2++;
                                            }
//                                        if($data0["is_bl"] == 1) {
//                                            echo"<tr class='dap-an-cau-$dem  binh-luan-cau-$dem binh-luan' style='display: none;'>
//                                                <td colspan='2'>
//
//                                                </td>
//                                            </tr>";
//                                            $binhluan .= "<div id='modal_default_$dem' class='modal fade binh-luan'>
//                                                <div class='modal-dialog'>
//                                                    <div class='modal-content'>
//                                                        <form action='http://localhost/www/TDUONG/luyenthi/upload_file.php' method='post' enctype='multipart/form-data'>
//                                                            <div class='modal-body'>
//                                                                <h6 class='text-semibold'>Bình luận</h6>
//                                                                <p><input type='text' class='form-control submit-text-bl-$dem' placeholder='Nội dung bình luận' /><input type='hidden' value='$data[ID_C]' name='cID' /><input type='hidden' value='$hsID' name='hsID' /></p>
//
//                                                                <hr>
//
//                                                                <h6 class='text-semibold'>hoặc Gửi ảnh</h6>
//                                                                <p><input type='file' name='file' class='btn btn-primary btn-sm bg-slate-400 submit-file-bl-$dem' style='width: 100%;' data-show-caption='false' data-show-upload='false'></p>
//                                                            </div>
//
//                                                            <div class='modal-footer'>
//                                                                <button type='button' class='btn btn-link submit-dong-$dem' data-dismiss='modal'>Đóng</button>
//                                                                <button type='submit' data-cID='$data[ID_C]' data-cau='$dem' class='btn btn-danger gui-ok'>Báo sai</button>
//                                                            </div>
//                                                        </form>
//                                                    </div>
//                                                </div>
//                                            </div>";
//                                        }
                                            $dem++;
                                        }
                                        $dem--;
                                        if ($dem == 0) {
                                            echo "<tr>
                                                <th colspan='2'>Đề trống rỗng! :(</th>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                            <th colspan='2'>Hết thời gian làm bài!</th>
                                        </tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /striped rows -->
                    </div>
                <!-- /main form -->
            </div>
            <!-- /main content -->

        </div>

        <div style="height: 120px;"></div>
        <button type="button" class="btn btn-primary btn-xs bg-danger-400 btn-fixed" id="submit-nop-bai">NỘP BÀI</button>

        <div class="container-fluid" style="position: fixed;bottom:-20px;left:0;z-index:99;width: 100%;padding: 0;margin: 0;">
            <div class="panel panel-flat">
                <div class="panel-heading" style="padding: 5px;text-align: center;">
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: left;" id="submit-cau-left"><span class="icon-chevron-left"></span></button>
<!--                    <button type="button" class="btn btn-primary btn-xs bg-danger-400" data-toggle="modal" data-target="#modal_default_1" id="submit-bao-sai">BÁO SAI</button>-->
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: right;" id="submit-cau-right"><span class="icon-chevron-right"></span></button>
                    <div style="clear: both;"></div>
                </div>
                <div class="panel-body" style="overflow-x: auto;padding: 0;">
                    <table class="table table-xs noSwipe" id="list-tom-tat">
                        <tbody>
                            <tr class="bg-brown-400">
                            <?php
                                $tr_dapan = "";
                                for($i=1;$i<=$dem;$i++) {
                                    echo"<td style='min-width: 60px;cursor: pointer;' class='text-center dap-an-eye'>$i</td>";
                                    if(isset($dapan[$i])) {
                                        $tr_dapan .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>".$dapan[$i]."</td>";
                                    } else {
                                        $tr_dapan .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>_</td>";
                                    }
                                }
                            ?>
                            </tr>
                            <tr><?php echo $tr_dapan; ?></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <?php include_once "include/bottom_hoc_sinh.php"; ?>
        <script type="text/javascript">
//            var myEvent = window.attachEvent || window.addEventListener;
//            var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload'; /// make IE7, IE8 compatable

//            myEvent(chkevent, function(e) { // For >=IE7, Chrome, Firefox
//                var confirmationMessage = ' ';  // a space
//                (e || window.event).returnValue = confirmationMessage;
//                return confirmationMessage;
//            });

            $(document).ready(function() {
                var window_width = $(window).width();
                $(".navbar-brand span").html("<code id='count-time' style='font-size: 26px;'></code><span id='count-time-progress' style='display: none;'>");
                var border_min = parseInt(((window_width/$("table#list-tom-tat tr td").length)/2));
                var border_max = $("table#list-tom-tat tr td").length - border_min;
//                console.log("border-len: " + border_max);
                var border = "4px solid yellow";
                bo_khung(1, "none");
                var td_width = $("table#list-tom-tat").width() / <?php echo $dem; ?>;
                td_width = td_width > 60 ? td_width : 60;
                function bo_khung(dem, type) {
                    $("table#list-tom-tat tr td").removeClass("border-up").removeClass("border-down").removeClass("border-full");
                    $("table#list-tom-tat tr:first td:eq(" + (dem - 1) + ")").addClass("border-up");
                    $("table#list-tom-tat tr:last td:eq(" + (dem - 1) + ")").addClass("border-down");
                    if(dem > border_min && type == "right") {
                        $("table#list-tom-tat").closest("div").animate({scrollLeft:'+=' + td_width},250);
                    } else if(dem < border_max && type == "left") {
                        $("table#list-tom-tat").closest("div").animate({scrollLeft:'-=' + td_width},250);
                    }
                }
//                $(".second-step").hide();
//                $(".binh-luan form").ajaxForm({
//                    beforeSend: function() {
//
//                    },
//                    complete: function(xhr) {
//                        var res = xhr.responseText;
//                        if(res == "none") {
//                            console.log(res);
////                            new PNotify({
////                                text: 'Dữ liệu không đầy đủ!',
////                                addclass: 'bg-danger'
////                            });
//                        } else if(res == "id") {
//                            new PNotify({
//                                text: 'Dữ liệu không chính xác!',
//                                addclass: 'bg-danger'
//                            });
//                        } else if(res == "size-type") {
//                            new PNotify({
//                                text: 'Ảnh phải dưới 2MB và có định dạng JPG, JPEG, PNG!',
//                                addclass: 'bg-danger'
//                            });
//                        } else {
//                            new PNotify({
//                                text: 'Upload thành công!',
//                                addclass: 'bg-primary'
//                            });
//                            new PNotify({
//                                text: '<img src=\"' + res + '\" style="max-width: 200px;height: auto;" />',
//                                addclass: 'bg-primary'
//                            });
//                        }
//                    }
//                });

                <?php if($start_time > $time) { ?>
                    var str_thongbao = "Bạn vào thi từ thời điểm <?php echo formatDateTime($vao_thi); ?>, đến nay <?php echo date("H:i:s d/m/Y"); ?> đã hết thời gian làm bài <?php echo $data0["time"]; ?> phút nên sẽ tiến hành thu bài!";
//                    alert(str_thongbao);
                    new PNotify({
                        title: 'Nộp bài',
                        text: str_thongbao,
                        icon: 'icon-reload-alt'
                    });
                    $("#super-nop-bai").click();
//                    window.location.href="http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/<?php //echo $deID_en; ?>///";
                <?php } ?>

//                $("input.dap-an-khac-text").typeWatch({
//                    captureLength: 1,
//                    callback: function (value) {
//                        var daID = $(this).attr("data-dakhac");
//                        if(daID != "" && value != "") {
//                            $.ajax({
//                                async: true,
//                                data: "daID=" + daID + "&dapankhac=" + Base64.encode(value) + "&hsID=<?php //echo $hsID_en; ?>//" + "&deID=<?php //echo $deID; ?>//",
//                                type: "get",
//                                url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau/",
//                                success: function (result) {
//                                    console.log("Kết quả: " + result);
//                                }
//                            });
//                        }
//                    }
//                });

//                $("button.gui-ok").click(function () {
//                    var me = $(this);
//                    var my_cau = $(this).attr("data-cau");
//                    var cID = $(this).attr("data-cID");
//                    var content = Base64.encode($("input.submit-text-bl-" + my_cau).val().trim());
//                    if (cID != "" && content != "" && valid_id(my_cau) && !me.hasClass("disabled")) {
//                        me.addClass("disabled");
//                        $.ajax({
//                            async: true,
//                            data: "cID=" + cID + "&content=" + content + "&hsID=<?php //echo $hsID_en; ?>//" + "&lmID=" + <?php //echo $data0["ID_LM"]; ?>// +"&all=0",
//                            type: "get",
//                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau/",
//                            success: function (result) {
//                                result = result.trim();
//                                //$("table#list-bai-thi tr.binh-luan-cau-" + my_cau + " td:eq(0) table tbody").html(result);
//                                //me.removeClass("disabled");
//                                $("input.submit-text-bl-" + my_cau).val("").attr("placeholder", "Đã gửi");
//                                if (result == "none") {
//                                    new PNotify({
//                                        text: 'Dữ liệu không chính xác!',
//                                        addclass: 'bg-danger'
//                                    });
//                                } else if (result == "out") {
//                                    new PNotify({
//                                        text: 'Bạn đã hết phiên đăng nhập do xuất hiện gián đoạn Hãy refresh!',
//                                        addclass: 'bg-danger'
//                                    });
//                                } else {
//                                    new PNotify({
//                                        text: 'Bạn đã bình luận thành công!',
//                                        addclass: 'bg-primary'
//                                    });
//                                }
//                                $(".submit-dong-" + my_cau).click();
//                            }
//                        });
//                        return false;
//                    } else {
//                        $("input.submit-text-bl-" + my_cau).val("");
//                        $(".submit-dong-" + my_cau).click();
//                        console.log("binh luan none");
//                    }
//                });
                $("#count-time-progress").countTo({
                    from: <?php echo $start_time*1000; ?>,
                    to: <?php echo $time*1000; ?>,
                    speed: <?php echo $time*1000; ?>,
                    refreshInterval: 1 * 1000,
                    onUpdate: function (value) {
                        var seconds = (<?php echo $time*1000; ?> - value)/1000;
                        var days    = Math.floor(seconds / 86400);
                        var hours   = Math.floor((seconds - (days * 86400)) / 3600);
                        var minutes = Math.floor((seconds - (days * 86400) - (hours * 3600))/60);
                        seconds = Math.floor((seconds - (days * 86400) - (hours * 3600) - (minutes*60)));
                        $("#count-time").html("<strong></strong>");
                        $("#count-time").find("strong").html(formatZero(hours) + ":" + formatZero(minutes) + ":" + formatZero(seconds));
                    },
                    onComplete: function (value) {
                        $("button#submit-nop-bai").removeClass("disabled").show();
                        $("table#list-bai-thi").html("<tr><th>Đã hết thời gian làm bài!</th></tr>");
                        setTimeout(function() {
                            nop_bai();
                        },1000);
                    }
                });
                function formatZero(number) {
                    if(number < 10) {
                        return "0" + number;
                    } else {
                        return number;
                    }
                }
                var time_cau = [];
                var da_arr = ["A","B","C","D","E","F","G","H","I","K"];
//                var chosen = [];
                for(i = 1; i <= <?php echo $dem; ?>; i++) {
//                    chosen["" + i + ""] = 0;
                    time_cau["" + i + ""] = 0;
                    $("span.count-time-" + i).countTo({
                        from: parseInt($("span.count-time-" + i).attr("data-pre")),
                        to: 30 * 60 * 1000,
                        speed: 30 * 60 * 1000,
                        refreshInterval: 2 * 1000
                    });
                    if(i > 1) {
                        $("span.count-time-" + i).countTo("stop");
                    }
                }
                <?php
//                    for($i = 1; $i <= $dem; $i++) {
//                        if(isset($dapan[$i])) {
//                            echo"chosen[$i] = 1;\n";
//                        }
//                    }
                ?>
                // Mở chỗ xem kết quả
//                if(chosen.indexOf(0) == -1) {
//                    $("button#submit-nop-bai").removeClass("disabled").show();
//                }
                var dem_first = $("table#list-bai-thi tr.de-bai-cau-big:first").attr("data-cau");
                $("table#list-bai-thi tr.de-bai-cau-big:first").addClass("cau-active").show();
                $("table#list-bai-thi tr.dap-an-cau-" + dem_first).show();
                $("button#submit-cau-left").css("opacity","0");
                $("button#submit-cau-left").click(function() {
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    if(my_stt > 1) {
                        $("span.count-time-" + my_stt).countTo("stop");
                        var time = parseInt($("span.count-time-" + my_stt).html());
                        time_cau["" + my_stt + ""] = time;
                        cur_cau.removeClass("cau-active").hide();
                        $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                        var now_stt = my_stt - 1;
                        $("span.count-time-" + now_stt).countTo("start");
                        $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                        $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                        $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
//                        $("html,body").animate({scrollTop:170},250);
//                        $("table#list-tom-tat").closest("div").animate({scrollLeft:(now_stt-1)*td_width},250);
//                        update_time_lam(my_stt,time);
                        $("button#submit-cau-right").css("opacity","1");
                        if(now_stt == 1) {
                            $("button#submit-cau-left").css("opacity","0");
                        }
                        bo_khung(now_stt, "left");
                    }
                });
                $("button#submit-cau-right").click(function() {
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    if(my_stt < <?php echo $dem; ?>) {
                        $("span.count-time-" + my_stt).countTo("stop");
                        var time = parseInt($("span.count-time-" + my_stt).html());
                        time_cau["" + my_stt + ""] = time;
                        cur_cau.removeClass("cau-active").hide();
                        $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                        var now_stt = my_stt + 1;
                        $("span.count-time-" + now_stt).countTo("start");
                        $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                        $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                        $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
//                        $("html,body").animate({scrollTop:170},250);
//                        $("table#list-tom-tat").closest("div").animate({scrollLeft:(now_stt-1)*td_width},250);
//                        update_time_lam(my_stt,time);
                        $("button#submit-cau-left").css("opacity","1");
                        if(now_stt == <?php echo $dem; ?>) {
                            $("button#submit-cau-right").css("opacity","0");
                        }
                        bo_khung(now_stt, "right");
                    }
                });
                $("body").addClass("sidebar-xs");
                $("table#list-bai-thi tr.dap-an-con").click(function () {
                    temp = $(this).find("input.radio-dap-an");
                    click_dap_an(temp);
                });
//                $("input.radio-dap-an").click(function() {
//                    click_dap_an($(this));
//                });
                function click_dap_an(div) {
                    // Lấy dữ liệu
                    var dapan_cau = div.closest("tr");
                    var temp = div.attr("data-temp");
                    var dem = div.attr("data-cau");
                    var dem2 = div.attr("data-stt");
                    var main_cau = $("table#list-bai-thi tr.de-bai-cau-" + dem);
                    var cID = main_cau.attr("data-cID");

//                    chosen["" + dem + ""] = 1;
//                    time_cau["" + my_stt + ""] = parseInt($("span.count-time-" + dem).html());

                    // Dữ liệu gửi đi
                    //var da = parseInt(temp);
                    var time = parseInt($("span.count-time-" + dem).html());

                    if (main_cau.hasClass("cau-edited") && !dapan_cau.hasClass("cau-in") && !dapan_cau.hasClass("dapan-chon")) {
                        if (valid_id(dem) && $.isNumeric(time) && valid_id(cID) && valid_id(temp)) {
                            dapan_cau.addClass("cau-in");
                            update_view(dem, dapan_cau, dem2, null, div);
                            $.ajax({
                                async: true,
                                data: "da0=" + temp + "&time0=" + time + "&cID=" + cID + "&hsID=<?php echo $hsID_en; ?>" + "&deID=<?php echo $deID; ?>",
                                type: "get",
                                url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                                success: function (result) {
                                    result = result.trim();
                                    main_cau.addClass("cau-edited");
									$("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").removeClass("dapan-chon");
                                    dapan_cau.removeClass("cau-in").addClass("dapan-chon");
                                    if (result == "none") {
                                        new PNotify({
                                            text: 'Dữ liệu không chính xác!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "out") {
                                        new PNotify({
                                            text: 'Bạn đã hết phiên đăng nhập do xuất hiện gián đoạn Hãy refresh!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "fuck") {
                                        new PNotify({
                                            text: 'Không thể lưu đáp án! Đáp án sẽ lưu lúc nộp bài!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "ok") {
                                        main_cau.removeClass("cau-error");
                                    } else {
                                        main_cau.addClass("cau-error");
                                        new PNotify({
                                            text: 'Đã có lỗi xảy ra! Đáp án sẽ lưu lúc nộp bài! Lỗi: ' + result,
                                            addclass: 'bg-danger'
                                        });
                                    }
                                    console.log("Sửa: --" + result);
                                },
                                error: function(xhr, ajaxOpionns, thrownError) {
                                    new PNotify({
                                        text: 'Mất kết nối mạng! Đáp án sẽ lưu lúc nộp bài!',
                                        addclass: 'bg-danger'
                                    });
                                    main_cau.addClass("cau-edited");
                                    $("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").removeClass("dapan-chon");
                                    dapan_cau.removeClass("cau-in").addClass("dapan-chon");
                                    main_cau.addClass("cau-error");
                                }
                            });
                        } else {
                            console.log("Dữ liệu lỗi: " + dem + " - " + temp + " - " + time);
                        }
                    } else if(!dapan_cau.hasClass("cau-in") && !dapan_cau.hasClass("dapan-chon")) {
                        if (valid_id(dem) && $.isNumeric(time) && valid_id(temp) && valid_id(cID)) {
                            dapan_cau.addClass("cau-in");
                            update_view(dem, dapan_cau, dem2, null, div);
                            $.ajax({
                                async: true,
                                data: "da=" + temp + "&time=" + time + "&cID=" + cID + "&hsID=<?php echo $hsID_en; ?>" + "&deID=<?php echo $deID; ?>",
                                type: "get",
                                url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                                success: function (result) {
                                    result = result.trim();
                                    main_cau.addClass("cau-edited");
									$("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").removeClass("dapan-chon");
                                    dapan_cau.removeClass("cau-in").addClass("dapan-chon");
                                    if (result == "none") {
                                        new PNotify({
                                            text: 'Dữ liệu không chính xác!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "out") {
                                        new PNotify({
                                            text: 'Bạn đã hết phiên đăng nhập do xuất hiện gián đoạn Hãy refresh!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "fuck") {
                                        new PNotify({
                                            text: 'Không thể lưu đáp án! Đáp án sẽ lưu lúc nộp bài!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "ok") {
                                        main_cau.removeClass("cau-error");
                                    } else {
                                        main_cau.addClass("cau-error");
                                        new PNotify({
                                            text: 'Đã có lỗi xảy ra! Đáp án sẽ lưu lúc nộp bài! Lỗi: ' + result,
                                            addclass: 'bg-danger'
                                        });
                                    }
                                    console.log("Thêm: --" + result);
                                },
                                error: function(xhr, ajaxOpionns, thrownError) {
                                    new PNotify({
                                        text: 'Mất kết nối mạng! Đáp án sẽ lưu lúc nộp bài!',
                                        addclass: 'bg-danger'
                                    });
                                    main_cau.addClass("cau-edited");
                                    $("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").removeClass("dapan-chon");
                                    dapan_cau.removeClass("cau-in").addClass("dapan-chon");
                                    main_cau.addClass("cau-error");
                                }
                            });
                        } else {
                            console.log("Dữ liệu lỗi: " + dem + " - " + temp + " - " + time);
                        }
                    }
                }

                function update_view(dem, dapan_cau, dem2, chosen, div) {
                    // Đổi màu
					var tempn = $("table#list-bai-thi tr.dap-an-cau-" + dem);
                    tempn.removeAttr("style");
                    dapan_cau.css("background-color","#ffffb2");
                    tempn.find("td > div > label > div > span input.radio-dap-an").removeAttr("checked");
                    tempn.find("td > div > label > div > span").removeClass("checked");
                    div.attr("checked","checked");
                    div.closest("span").addClass("checked");

                    // Hiển thị đáp án đã chọn và đếm số câu đã làm
                    $("table#list-tom-tat tr td.tom-tat-" + dem).html(da_arr[dem2]);
//                    var stt = -1;
//                    $("#list-tom-tat tr").each(function(index, element) {
//                        if($(element).find("td:eq(1)").html() != "_" && da_arr[dem2]) {
//                            stt++;
//                        }
//                    });
//                    $("#dap-an-progress").html("Đáp án của bạn: " + stt + "/<?php //echo $dem; ?>//");

                    // Mở chỗ xem kết quả
//                    if(chosen.indexOf(0) == -1) {
//                        $("button#submit-nop-bai").removeClass("disabled").show();
//                    }
                }

                function update_time_lam(dem, time) {
                    var cID = $("table#list-bai-thi tr.de-bai-cau-" + dem).attr("data-cID");
                    if(cID != "" && $.isNumeric(time)) {
                        $.ajax({
                            async: true,
                            data: "time_new=" + time + "&cID0=" + cID + "&hsID0=<?php echo $hsID_en; ?>" + "&deID0=<?php echo $deID; ?>",
                            type: "get",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                            success: function (result) {
                                console.log("Update: --" + cID + " - : " + time);
                            }
                        });
                    } else {
                        console.log("Dữ liệu lỗi: " + cID + " - " + time);
                    }
                }

                $("table#list-tom-tat tr .dap-an-eye").click(function() {
                    var now_stt = $(this).index() + 1;
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    $("span.count-time-" + my_stt).countTo("stop");
                    $("span.count-time-" + now_stt).countTo("start");
                    var time = parseInt($("span.count-time-" + my_stt).html());
                    time_cau["" + my_stt + ""] = time;
                    cur_cau.removeClass("cau-active").hide();
                    $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                    $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                    $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                    $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
//                    $("html,body").animate({scrollTop:$("table#list-bai-thi").offset().top},250);
//                    update_time_lam(my_stt, time);
                    if(now_stt == 1) {
                        $("button#submit-cau-left").css("opacity","0");
                    } else if(now_stt == <?php echo $dem; ?>) {
                        $("button#submit-cau-right").css("opacity","0");
                    } else {
                        $("button#submit-cau-left, button#submit-cau-right").css("opacity","1");
                    }
                    bo_khung(now_stt, "none");
                });
                $("#submit-nop-bai").click(function () {
                     if(!$(this).hasClass("disabled")) {
                         if(confirm("Bạn có chắc chắn muốn nộp bài không?")) {
                             nop_bai();
                         }
                     }
                });

                function nop_bai() {
                    $("#count-time-progress").countTo("stop");
                    new PNotify({
                        title: 'Nộp bài',
                        text: 'Đang tiến hành nộp bài...',
                        icon: 'icon-reload-alt'
                    });
                    var ajax_cau = "[";
                    $("table#list-bai-thi tr.de-bai-cau-big.cau-error").each(function(index, element) {
                        var dem = $(element).attr("data-cau");
                        var da = $("table#list-bai-thi tr.dapan-chon.dap-an-cau-" + dem).find("input.radio-dap-an").attr("data-temp");
                        var cID = $(element).attr("data-cID");
                        var time = parseInt($("span.count-time-" + dem).html());
                        console.log(cID + " - " + da + " - " + time);
                        if($.isNumeric(cID) && $.isNumeric(da) && $.isNumeric(time)) {
                            ajax_cau += '{"cID":"' + cID + '","daID":"' + da + '","time":"' + time + '"},';
                        }
                    });
                    console.log("Ajax: " + ajax_cau);
                    if(ajax_cau != "[") {
                        ajax_cau += '{"hsID":"<?php echo $hsID_en ?>","deID":"<?php echo $deID; ?>"}';
                        ajax_cau += "]";
                        $.ajax({
                            async: true,
                            data: "ajax_cau=" + ajax_cau,
                            type: "get",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Nộp bài',
                                    text: 'Đã nộp bài thành công',
                                    icon: 'icon-checkmark'
                                });
                                console.log("Nộp bài: " + result);
                                $("#super-nop-bai").click();
                            },
                            error: function(xhr, ajaxOpionns, thrownError) {
                                new PNotify({
                                    text: 'Mất kết nối mạng! Không thể nộp bài!',
                                    addclass: 'bg-danger'
                                });
                            }
                        });
                    } else {
                        $("#super-nop-bai").click();
                    }
                }
                document.oncontextmenu = new Function("return false");
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
