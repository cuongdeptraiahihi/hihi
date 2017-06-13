
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
            $sum_sl = 30;
            if(isset($_POST["tao-de"])) {
                if(isset($_POST["submit-sl"]) && !empty($_POST["submit-sl"]) && is_numeric($_POST["submit-sl"]) && $_POST["submit-sl"] > 30) {
                    $sum_sl = $_POST["submit-sl"];
                }
                $sum_sl = $sum_sl > 50 ? 50 : $sum_sl;
                if(isset($_POST["submit-loai-de"]) && !empty($_POST["submit-loai-de"])) {
                    $loai_de = $_POST["submit-loai-de"];
                    if($loai_de == "decu" && isset($_POST["submit-de"]) && !empty($_POST["submit-de"])) {
                        $deID = "none";
                        if(validId($_POST["submit-de"]) && isset($_POST["submit-chuyen-de"]) && !empty($_POST["submit-chuyen-de"])) {
                            $deID = $_POST["submit-chuyen-de"];
                        } else {
                            $deID = decodeData($_POST["submit-de"], $me);
                        }

                        if($deID != "none") {
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
                        $db4 = new Cau_Hoi();
                        $num = 1;
                        if(isset($_POST["submit-num"]) && !empty($_POST["submit-num"])) {
                            $num = $_POST["submit-num"];
                        }
                        $num = $num > 10 ? 10 : $num;

                        $code = randMaso(3);
                        $deID_new = $db->taoDe($code, "Đề tự tạo mã $code, ngày ".date("d/m/Y"), $lmID, 0, ceil(1.8*$sum_sl), 0, 1, 0, 1);

                        $cau_str = $content_da = "";
                        $content = array();
                        $total_sl = 1;
                        for($i = 1; $i <= $num; $i++) {
                            if(isset($_POST["type-cau-$i"])) {
                                $type_cau = $_POST["type-cau-$i"];
                                $sl = 1;
                                if(isset($_POST["type-num-$i"]) && !empty($_POST["type-num-$i"]) && is_numeric($_POST["type-num-$i"])) {
                                    $sl = $_POST["type-num-$i"];
                                }
                                $sl = $sl >= 1 ? $sl : 1;
                                $cdID = 0;
                                if(isset($_POST["type-cd-$i"])) {
                                    $cdID = $_POST["type-cd-$i"];
                                }
                                if(substr($cau_str,0,1) == ",") {
                                    $cau_str = substr($cau_str,1);
                                }
                                $result = $db->taoDeNoiDungHs($type_cau, $cdID, $sl, $cau_str, $hsID, $monID);
                                while($data = $result->fetch_assoc()) {
                                    $content[] = "('$deID_new','$data[ID_C]','$total_sl')";
                                    $cau_str .= ",'$data[ID_C]'";
                                    if($total_sl == $sum_sl) {
                                        break;
                                    }
                                    $total_sl++;
                                }
                            }
                        }
                        if($total_sl < $sum_sl) {
                            if(substr($cau_str,0,1) == ",") {
                                $cau_str = substr($cau_str,1);
                            }
                            $result = $db->taoDeNoiDungHs("auto", 0, $sum_sl-$total_sl+1, $cau_str, $hsID, $monID);
                            while($data = $result->fetch_assoc()) {
                                $content[] = "('$deID_new','$data[ID_C]','$total_sl')";
                                $cau_str .= ",'$data[ID_C]'";
                                if($total_sl == $sum_sl) {
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
        ?>

        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Main form -->
            <form action="http://localhost/www/TDUONG/luyenthi/luyen-de/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="col-lg-12">
                    <div class="panel-heading">
                        <h5 class="panel-title">Tạo đề tùy chọn</h5>
                    </div>
                    <div class="panel panel-flat">
                        <div class="panel-body">
                            <fieldset class="content-group">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Loại đề thi</label>
                                    <div class="col-sm-3">
                                        <select name="submit-loai-de" id="submit-loai-de" class="form-control">
                                            <option value="decu">Đề thi cũ</option>
                                            <option value="detao">Đề tự tạo</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <select name="submit-de" id="submit-de" class="form-control">
                                            <option class='bg-blue-400'  value="">Hãy chọn một đề cũ</option>
                                            <?php
                                            echo"<option class='bg-brown-400'  value=''>Đề kiểm tra</option>";
                                            $result = $db->getNhomDeThiAllow($lmID, "kiem-tra");
                                            while($data = $result->fetch_assoc()) {
                                                echo"<option value='".encodeData($data["ID_DE"],$me)."' data-type='kiem-tra'>$data[mota]</option>";
                                            }
                                            echo"<option class='bg-brown-400'  value=''>Chuyên đề</option>";
                                            $result = $db3->getChuyenDeUnlockByMon($lmID, $monID);
                                            while($data = $result->fetch_assoc()) {
                                                echo"<option value='$data[ID_CD]' data-type='chuyen-de'>$data[name]</option>";
                                            }
//                                            $result = $db->getNhomDeThiAllow($lmID, "chuyen-de");
//                                            while($data = $result->fetch_assoc()) {
//                                                echo"<option value='".encodeData($data["ID_DE"],$me)."'>$data[mota]</option>";
//                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="show-chuyen-de" style="display: none;">
                                    <label class="control-label col-sm-2">Chọn đề</label>
                                    <div class="col-sm-10">
                                        <select name="submit-chuyen-de" id="submit-chuyen-de" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group" id="submit-sl" style="display: none;">
                                    <label class="control-label col-sm-2">Tổng số câu</label>
                                    <div class="col-sm-3">
                                        <input type="number" min="30" max="50" step="1" name="submit-sl" value="40" class="form-control" placeholder="Tổng số câu" />
                                    </div>
                                    <label class="control-label col-sm-7"><code id="tong-time">Tổng thời gian làm = 1.8 x Tổng số câu hỏi = <strong>72 phút</strong></code></label>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="col-sm-12 text-right">
                                        <button type="submit" class="btn btn-primary btn-sm bg-primary-400" name="tao-de" id="tao-de">Tạo đề</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="panel panel-flat" id="de-tu-tao" style="display: none;">
                        <div class="panel-body">
                            <fieldset class="content-group">
                                <?php
                                    $cd_str = "";
                                    $result = $db3->getChuyenDeUnlockByMon($lmID, $monID);
                                    $n = $result->num_rows;
                                    while($data=$result->fetch_assoc()) {
                                        $cd_str .= "<option value='$data[ID_CD]'>$data[name]</option>";
                                    }
                                ?>
                                <?php for($i = 1; $i <= $n; $i++) { ?>
                                <div id="form-chon-<?php echo $i; ?>" class="form-group" <?php if($i > 1){echo"style='display:none;'";} ?>>
                                    <div class="col-sm-3">
                                        <select name="type-cau-<?php echo $i; ?>" class="form-control type-cau">
                                            <option value="chuyende">Chuyên đề</option>
                                            <option value="sainhieu">Các câu sai nhiều</option>
                                            <option value="dungnhieu">Các câu đúng nhiều</option>
                                            <option value="lopsai">Các câu lớp hay sai</option>
                                            <option value="caukho">Các câu khó</option>
                                            <option value="caude">Các câu dễ</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-7">
                                        <select name="type-cd-<?php echo $i; ?>" class="form-control type-cd">
                                            <?php echo $cd_str; ?>
                                        </select>
                                        <label class="control-label" style="display: none;margin-top: 8px;"></label>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="number" min="1" step="1" name="type-num-<?php echo $i; ?>" class="form-control type-num" placeholder="SL tối đa" />
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <div class="col-sm-12 text-right">
                                        <input type="hidden" min="1" name="submit-num" id="submit-num" value="1" />
                                        <button type="button" class="btn btn-primary btn-sm bg-brown-400" id="them">Thêm</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <!-- /striped rows -->
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

            $("#them").click(function () {
                var num = $("#submit-num").val();
                num = parseInt(num) + 1;
                if($("#form-chon-" + num).length) {
                    $("#form-chon-" + num).fadeIn("fast");
                    $("#submit-num").val(num);
                }
                return false;
            });

            $("#submit-sl input").keyup(function(e) {
                var sl = parseInt($(this).val());
                $("#tong-time").html("Tổng thời gian làm = 1.8 x Tổng số câu hỏi = <strong>" + Math.ceil(1.8*sl) + " phút</strong>");
            });

            $("#submit-sl input").change(function(e) {
                var sl = parseInt($(this).val());
                $("#tong-time").html("Tổng thời gian làm = 1.8 x Tổng số câu hỏi = <strong>" + Math.ceil(1.8*sl) + " phút</strong>");
            });

            $(".type-cau").change(function () {
                var type_cau = $(this).find("option:selected").val();
                var div = $(this).closest("div.form-group");
                if(type_cau == "chuyende") {
                    div.find("div:eq(1) label").hide().html("");
                    div.find(".type-cd").show();
                } else if(type_cau == "sainhieu") {
                    div.find(".type-cd").hide();
                    div.find("div:eq(1) label").show().html("Các câu mà bạn bị <strong>sai nhiều</strong> sẽ được lựa chọn!");
                } else if(type_cau == "dungnhieu") {
                    div.find(".type-cd").hide();
                    div.find("div:eq(1) label").show().html("Các câu mà bạn <strong>đúng nhiều</strong> sẽ được lựa chọn!");
                } else if(type_cau == "lopsai") {
                    div.find(".type-cd").hide();
                    div.find("div:eq(1) label").show().html("Các câu mà lớp <strong>sai nhiều</strong> sẽ được lựa chọn!");
                } else if(type_cau == "caukho") {
                    div.find(".type-cd").hide();
                    div.find("div:eq(1) label").show().html("Các câu <strong>khó</strong> sẽ được lựa chọn!");
                } else if(type_cau == "caude") {
                    div.find(".type-cd").hide();
                    div.find("div:eq(1) label").show().html("Các câu <strong>dễ</strong> sẽ được lựa chọn!");
                }
            });

            $("#submit-loai-de").change(function () {
                var loai_de = $(this).find("option:selected").val();
                if(loai_de == "decu") {
                    $("#submit-de").show();
                    $("#de-tu-tao, #submit-sl").hide();
                } else {
                    $("#submit-de").hide();
                    $("#de-tu-tao, #submit-sl").show();
                }
            });

            $("#submit-de").change(function () {
                var option = $(this).find("option:selected");
                if(option.attr("data-type") == "chuyen-de") {
                    var idCD = option.val();
                    if($.isNumeric(idCD) && idCD > 0) {
                        $.ajax({
                            async: true,
                            data: "idCD=" + idCD + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-mon/",
                            success: function (result) {
                                $("#submit-chuyen-de").html(result);
                                $("#show-chuyen-de").fadeIn("fast");
                            }
                        });
                    }
                } else {
                    $("#show-chuyen-de").fadeOut("fast");
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
