
<?php include_once "include/top_hoc_sinh.php"; ?>
<?php
    $me = md5("123456");
    if(isset($_GET["type"])) {
        $type = $_GET["type"];
    } else {
        $type = "X";
    }
    $db2 = new Luyen_De();
    $db3 = new Chuyen_De();
    $db = new De_Thi();

    $chuyende_default = array();
    $chuyende_get = array();
    $result = $db3->getChuyenDeUnlockByMon($lmID, $monID);
    while($data=$result->fetch_assoc()) {
        $chuyende_default[] = array(
            "name" => $data["name"],
            "id" => $data["ID_CD"]
        );
        $chuyende_get[] = "'".$data["ID_CD"]."'";
    }
?>

<!-- Page header -->
<!--<div class="page-header">-->
<!--    <div class="page-header-content">-->
<!--        <div class="page-title">-->
<!--            <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Tự luyện đề</h4>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!-- /page header -->

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <?php include_once "include/sidebar_hoc_sinh.php"; ?>

        <?php
            $error = false;
            $error_msg = "";
            $loai_de = NULL;
            $sum_sl = 20;
            if(isset($_POST["tao-de"])) {
                if(isset($_POST["submit-sl"]) && !empty($_POST["submit-sl"]) && is_numeric($_POST["submit-sl"]) && $_POST["submit-sl"] > 20) {
                    $sum_sl = $_POST["submit-sl"];
                }
                $sum_sl = $sum_sl > 50 ? 50 : $sum_sl;
                if(isset($_POST["submit-loai-de"]) && !empty($_POST["submit-loai-de"])) {
                    $loai_de = $_POST["submit-loai-de"];
                    if($loai_de == "decu" && isset($_POST["submit-de"]) && !empty($_POST["submit-de"])) {
                        $deID = "none";
                        if(isset($_POST["submit-dethi"]) && !empty($_POST["submit-dethi"])) {
                            $deID = $_POST["submit-dethi"];
                        }

                        if($deID != "none" && validId($deID)) {
                            $result = $db->getDeThiById($deID);
                            $data = $result->fetch_assoc();

                            $deID_new = $db->taoDe($data["nhom"], $data["mota"], $data["ID_LM"], 0, $data["time"], 0, 0, $data["loai"], $data["form"]);

                            $content = $content_da = "";
                            $da_arr = array();
                            $result1 = $db->getDapAnNganByDeAllUse($deID, true);
                            while ($data1 = $result1->fetch_assoc()) {
                                if (!isset($da_arr[$data1["ID_C"]])) {
                                    $da_arr[$data1["ID_C"]] = 1;
                                }
                                $content_da .= ",('$deID_new','$data1[ID_DA]','" . $da_arr[$data1["ID_C"]] . "')";
                                $da_arr[$data1["ID_C"]]++;
                            }
                            $sort = 1;
                            $result1 = $db->getCauHoiByDe($deID, true);
                            while ($data1 = $result1->fetch_assoc()) {
                                $content .= ",('$deID_new','$data1[ID_C]','$sort')";
                                $sort++;
                            }
                            if ($content != "") {
                                $content = substr($content, 1);
                                $db->addCauDeThiMulti($content);
                            }
                            if ($content_da != "") {
                                $content_da = substr($content_da, 1);
                                $db->addDapAnDeThiMulti($content_da);
                            }

                            (new Vao_Thi())->addHocSinhTuLuyen($hsID, $deID_new, 0, 0, "create");

                            header("location:http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($deID_new, $me) . "/");
                            exit();
                        } else {
                            $error = true;
                            $error_msg = "Dữ liệu không chính xác!";
                        }
                    } else if($loai_de == "detao") {
                        $num = 20;
                        if(isset($_POST["submit-sl"]) && !empty($_POST["submit-sl"])) {
                            $num = $_POST["submit-sl"];
                        }
                        $num = $num > 50 ? 50 : $num;
                        $num = $num < 20 ? 20 : $num;

                        $code = randMaso(3);
                        $deID_new = $db->taoDe($code, "Đề tự tạo mã $code, ngày ".date("d/m/Y"), $lmID, 0, ceil(1.8*$sum_sl), 0, 1, 0, 1);

                        $options = array();
                        if(!empty($_POST["submit-options"])) {
                            foreach ($_POST["submit-options"] as $check) {
                                $options[] = $check;
                            }
                        }

                        $chuyende = array();
                        if(!empty($_POST["submit-chuyende"])) {
                            foreach ($_POST["submit-chuyende"] as $chuyende_temp) {
                                $chuyende[] = "'".$chuyende_temp."'";
                            }
                        }

                        $cau_str = $content_da = "";
                        $content = array();
                        $total_sl = 1;
                        if(count($chuyende) != 0) {
                            $chuyende = implode(",", $chuyende);
                        } else {
                            $chuyende = implode(",", $chuyende_get);
                        }
                        if(count($options) != 0) {
                            $num_cau = $num / count($options);
                            $cau_str = "";
                            foreach ($options as $opt) {
                                if(substr($cau_str,0,1) == ",") {
                                    $cau_str = substr($cau_str,1);
                                }
                                $result = $db->taoDeNoiDungHs($opt, $chuyende, $num_cau, $cau_str, $hsID, $monID);
                                while($data = $result->fetch_assoc()) {
                                    $content[] = "('$deID_new','$data[ID_C]','$total_sl')";
                                    $cau_str .= ",'$data[ID_C]'";
                                    if($total_sl == $num) {
                                        break;
                                    }
                                    $total_sl++;
                                }
                            }
                        } else {
                            if(substr($cau_str,0,1) == ",") {
                                $cau_str = substr($cau_str,1);
                            }
                            $result = $db->taoDeNoiDungHs(NULL, $chuyende, $num, $cau_str, $hsID, $monID);
                            while($data = $result->fetch_assoc()) {
                                $content[] = "('$deID_new','$data[ID_C]','$total_sl')";
                                $cau_str .= ",'$data[ID_C]'";
                                if($total_sl == $num) {
                                    break;
                                }
                                $total_sl++;
                            }
                        }

                        if($total_sl < $num) {
                            $chuyende = implode(",", $chuyende_get);
                            if(substr($cau_str,0,1) == ",") {
                                $cau_str = substr($cau_str,1);
                            }
                            $result = $db->taoDeNoiDungHs(NULL, $chuyende, $num-$total_sl+1, $cau_str, $hsID, $monID);
                            while($data = $result->fetch_assoc()) {
                                $content[] = "('$deID_new','$data[ID_C]','$total_sl')";
                                $cau_str .= ",'$data[ID_C]'";
                                if($total_sl == $num) {
                                    break;
                                }
                                $total_sl++;
                            }
                        }

                        if(count($content) != 0) {
                            shuffle($content);
                            $content = implode(",",$content);
                            $db->addCauDeThiMulti($content);
                        }

                        $cau_arr = array();
                        if(substr($cau_str,0,1) == ",") {
                            $cau_str = substr($cau_str,1);
                        }
                        $db4 = new Cau_Hoi();
                        $result = $db4->getDapAnByCauArr($cau_str);
                        while($data = $result->fetch_assoc()) {
                            if(!isset($cau_arr[$data["ID_C"]])) {
                                $cau_arr[$data["ID_C"]] = 1;
                            }
                            $content_da .= ",('$deID_new','$data[ID_DA]','".$cau_arr[$data["ID_C"]]."')";
                            $cau_arr[$data["ID_C"]]++;
                        }

                        if($content_da != "") {
                            $content_da = substr($content_da, 1);
                            $db->addDapAnDeThiMulti($content_da);
                        }

                        (new Vao_Thi())->addHocSinhTuLuyen($hsID, $deID_new, 0, 0, "create");

                        header("location:http://localhost/www/TDUONG/luyenthi/xem-truoc/".encodeData($deID_new,$me)."/");
                        exit();
                    } else {
                        $error = true;
                        $error_msg = "Dữ liệu không chính xác!";
                    }
                }
            }
            echo $error_msg;
        ?>

        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Main form -->
            <form action="http://localhost/www/TDUONG/luyenthi/luyen-de/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="col-lg-4">
                    <div class="panel-heading">
                        <h5 class="panel-title"><code>B1.</code> Loại đề thi *</h5>
                    </div>
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <fieldset class="content-group">
                                <div class="form-group" style="display: none;">
                                    <div class="col-sm-12">
                                        <select name="submit-loai-de" id="submit-loai-de" class="form-control">
                                            <option value="detao" selected="selected">Đề tự tạo</option>
                                            <option value="decu">Đề thi cũ</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group opt opt-detao">
                                    <div class="col-sm-12">
                                        <label class="control-label col-lg-4">Số câu</label>
                                        <div class="col-lg-8">
                                            <input type="number" min="20" max="50" step="1" name="submit-sl" id="submit-sl" value="40" class="form-control" placeholder="Tổng số câu" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group opt opt-detao">
                                    <div class="col-sm-12">
                                        <label class="control-label"><code id="tong-time">Tổng thời gian làm = 1.8 x Tổng số câu hỏi = <strong>72 phút</strong></code></label>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-sm bg-primary-400" name="tao-de" id="tao-de">Tạo đề</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 opt opt-detao">
                    <div class="panel-heading">
                        <h5 class="panel-title"><code>B2.</code> Tùy chọn</h5>
                    </div>
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <p class="content-group">Bạn có thể bỏ trống!</p>
                            <fieldset class="content-group">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="control-primary" name="submit-options[]" value="sainhieu">
                                                Các câu sai nhiều
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="control-primary" name="submit-options[]" value="lopsai">
                                                Các câu lớp hay sai
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="control-primary" name="submit-options[]" value="caukho">
                                                Các câu khó
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="control-primary" name="submit-options[]" value="caude">
                                                Các câu dễ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 opt opt-detao">
                    <div class="panel-heading">
                        <h5 class="panel-title"><code>B3.</code> Chuyên đề</h5>
                    </div>
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <p class="content-group">Bạn có thể bỏ trống!</p>
                            <fieldset class="content-group">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <?php
                                        foreach ($chuyende_default as $key => $value) {
                                            echo"<div class='checkbox'>
                                                <label>
                                                    <input type='checkbox' class='control-primary' name='submit-chuyende[]' value='$value[id]'>
                                                    $value[name]
                                                </label>
                                            </div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 opt opt-decu" style="display: none;">
                    <div class="panel-heading">
                        <h5 class="panel-title"><code>B2.</code> Chọn đề cũ *</h5>
                    </div>
                    <div class="panel panel-flat table-responsive">
                        <div class="panel-body">
                            <fieldset class="content-group">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="col-sm-12">
                                        <select name="submit-de" id="submit-de" class="form-control">
                                            <?php
                                            echo"<option value='kiemtra'>Đề kiểm tra</option>";
                                            $result = $db3->getChuyenDeUnlockByMon($lmID, $monID);
                                            while($data = $result->fetch_assoc()) {
                                                echo"<option value='$data[ID_CD]'>$data[name]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
<!--                                <div class="form-group" id="show-chuyen-de" style="display: none;">-->
<!--                                    <label class="control-label col-sm-2">Chọn đề</label>-->
<!--                                    <div class="col-sm-10">-->
<!--                                        <select name="submit-chuyen-de" id="submit-chuyen-de" class="form-control">-->
<!--                                        </select>-->
<!--                                    </div>-->
<!--                                </div>-->
                            </fieldset>
                            <table class="table datatable-basic table-striped" id="show-kiem-tra">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Ngày up</th>
                                        <th class="text-center">Đề thi</th>
                                        <th class="text-center">Làm bài</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $stt = 1;
                                        $result = $db->getNhomDeThiAllow($lmID, "kiem-tra");
                                        while($data = $result->fetch_assoc()) {
                                            echo"<tr>
                                                <td class='text-center'>$stt</td>
                                                <td class='text-center'>".formatDateTime($data["ngay"])."</td>
                                                <td class='text-center'>$data[mota]</td>
                                                <td class='text-center'>
                                                    <button type='button' class='btn btn-primary btn-sm bg-primary-400 chon-de' data-deID='$data[ID_DE]'>Chọn</button>
                                                </td>
                                            </tr>";
                                            $stt++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                            <table class="table datatable-basic table-striped" id="show-chuyen-de" style="display: none;">
                                <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Ngày up</th>
                                    <th class="text-center">Đề thi</th>
                                    <th class="text-center">Làm bài</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <input type="hidden" value="" name="submit-dethi" id="pre-chon-de" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="panel-heading">
                        <h5 class="panel-title">Kết quả tự luyện đề</h5>
                    </div>
                    <div class="panel panel-flat table-responsive noSwipe">
                        <table class="table list-danh-sach table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Hoàn thành</th>
                                <th class="text-center">Đề thi</th>
                                <th class="text-center">Điểm</th>
                                <th class="text-center">Thời gian</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $dem = 1;
                            $result = $db2->getTuLuyenDeHocSinh($hsID, $lmID);
                            while($data=$result->fetch_assoc()) {
                                $deID_encode = encodeData($data["ID_DE"], $me);
                                echo"<tr>
                                    <td class='text-center'>";
                                        if($data["out_time"] != "0000-00-00 00:00:00") {
                                            echo "<a href='http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-primary-400' style='color:#FFF;'>ĐÁP ÁN</a> ";
                                        } else if($data["in_time"] != "0000-00-00 00:00:00") {
                                            echo "<a href='http://localhost/www/TDUONG/luyenthi/xem-truoc/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>LÀM TIẾP</a> ";
                                        } else {
                                            echo "<a href='http://localhost/www/TDUONG/luyenthi/xem-truoc/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>LÀM BÀI</a> ";
                                        }
                                    echo"</td>";
                                        if($data["out_time"] == "0000-00-00 00:00:00") {
                                            echo"<td class='text-center'>Chưa hoàn thành</td>";
                                        } else {
                                            echo "<td class='text-center'>" . formatDateTime($data["out_time"]) . "</td>";
                                        }
                                    echo"<td class='text-center'>$data[mota]</td>
                                        <td class='text-center'>$data[diem]</td>
                                        <td class='text-center'>".formatTimeBack($data["time"]/1000)."</td>
                                    </tr>";
                                $dem++;
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /striped rows -->
                </div>
            </form>
            <!-- /main form -->
        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

    <?php include_once "include/bottom_hoc_sinh.php"; ?>
    <script type="text/javascript">
        $(document).ready(function() {
//            $("body").addClass("sidebar-xs");

            $("#submit-sl").keyup(function(e) {
                var sl = parseInt($(this).val());
                if(sl) {
                    $("#tong-time").html("Tổng thời gian làm = 1.8 x Tổng số câu hỏi = <strong>" + Math.ceil(1.8 * sl) + " phút</strong>");
                }
            });

            $("#submit-sl").change(function(e) {
                var sl = parseInt($(this).val());
                if(sl) {
                    $("#tong-time").html("Tổng thời gian làm = 1.8 x Tổng số câu hỏi = <strong>" + Math.ceil(1.8 * sl) + " phút</strong>");
                }
            });

            $("#submit-loai-de").change(function () {
                var loai_de = $(this).find("option:selected").val();
                if(loai_de == "decu") {
                    $("div.opt").hide();
                    $("div.opt-" + loai_de).show();
                } else if(loai_de == "detao") {
                    $("div.opt").hide();
                    $("div.opt-" + loai_de).show();
                }
            });

            $("#submit-de").change(function () {
                var option = $(this).find("option:selected");
                if(option.val() != "kiemtra") {
                    var idCD = option.val();
                    if($.isNumeric(idCD) && idCD > 0) {
                        $.ajax({
                            async: true,
                            data: "idCD=" + idCD + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-mon/",
                            success: function (result) {
                                $("#show-chuyen-de tbody").html(result);
                                $("#show-kiem-tra").hide();
                                $("#show-chuyen-de").fadeIn("fast");
                            }
                        });
                    }
                } else {
                    $("#show-chuyen-de").hide();
                    $("#show-kiem-tra").fadeIn("fast");
                }
            });

            $("table").delegate("tr td:last-child button.chon-de","click",function() {
                if($(this).hasClass("active")) {
                    $("button.chon-de").removeClass("active").removeAttr("disabled").css("opacity", 1);
                    $("#pre-chon-de").val("");
                } else {
                    var deID = $(this).attr("data-deID");
                    $("#pre-chon-de").val(deID);
                    $("button.chon-de").removeClass("active").prop("disabled",true).css("opacity", 0);
                    $(this).addClass("active").removeAttr("disabled").css("opacity", 1);
                    $("#tao-de").click();
//                    new PNotify({
//                        title: 'Đề tự luyện',
//                        text: 'Bạn hãy chọn Tạo đề'
//                    });
//                    $("html,body").animate({scrollTop:0},250);
                }
            });

            $("#tao-de").click(function () {
                new PNotify({
                    title: 'Tạo đề tự luyện',
                    text: 'Đang tiến hành tạo đề...',
                    icon: 'icon-reload-alt'
                });
                $(this).val("Đang tạo đề...");
            });
        });
    </script>
