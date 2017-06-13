
    <?php include_once "../include/top.php"; ?>
    <?php
        $sum_da = 6;
        $nhom = 0;
        $monID = (new Mon_Hoc())->getMonOfLop($lmID);
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Câu hỏi</span> - Thêm câu hỏi</h4>-->
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
                $error_msg = "";
                $da_arr = array();
                $error = false;
                $content = $anh_title = $da_chi_tiet = $anh_chi_tiet = $note = "none";
                $cdID=$loai=$do_kho=$nhom=$lop=0;
                $type_da = "trac-nghiem";
                $maso = $da_dien_tu = $nhom_name = "";
                if(isset($_POST["submit-add-ok"])) {
                    if($_FILES["submit-anh-title"]["error"]>0) {

                    } else {
                        $anh_title = addslashes($_FILES["submit-anh-title"]["name"]);
                    }
                    if(isset($_POST["submit-maso"]) && !empty($_POST["submit-maso"])) {
                        $maso = $_POST["submit-maso"];
                    }
                    if(isset($_POST["submit-content"]) && !empty($_POST["submit-content"])) {
                        $content = $_POST["submit-content"];
                    }
                    if(isset($_POST["submit-cd"]) && !empty($_POST["submit-cd"])) {
                        $cdID = $_POST["submit-cd"];
                    }
                    if(isset($_POST["submit-loai"]) && !empty($_POST["submit-loai"])) {
                        $loai = $_POST["submit-loai"];
                    }
                    if(isset($_POST["submit-type-da"]) && !empty($_POST["submit-type-da"])) {
                        $type_da = $_POST["submit-type-da"];
                    }
                    if(isset($_POST["submit-do-kho"]) && !empty($_POST["submit-do-kho"])) {
                        $do_kho = $_POST["submit-do-kho"];
                    }
                    if(isset($_POST["submit-da-chi-tiet"]) && !empty($_POST["submit-da-chi-tiet"])) {
                        $da_chi_tiet = $_POST["submit-da-chi-tiet"];
                    }
                    if($_FILES["submit-da-anh-chi-tiet"]["error"]>0) {

                    } else {
                        $anh_chi_tiet = addslashes($_FILES["submit-da-anh-chi-tiet"]["name"]);
                    }
                    if(isset($_POST["submit-note"]) && !empty($_POST["submit-note"])) {
                        $note = $_POST["submit-note"];
                    }
                    if(isset($_POST["submit-nhom-name"]) && !empty($_POST["submit-nhom-name"])) {
                        $nhom_name = $_POST["submit-nhom-name"];
                    } else if(isset($_POST["submit-nhom"])) {
                        $nhom = $_POST["submit-nhom"];
                    }
                    if(isset($_POST["submit-dien-tu"]) && !empty($_POST["submit-dien-tu"])) {
                        $type_da = "dien-tu";
                        $da_dien_tu = $_POST["submit-dien-tu"];
                    } else {
                        $type_da = "trac-nghiem";
                        if(isset($_POST["submit-check-da"]) && !empty($_POST["submit-check-da"])) {
                            $dap_an_dung = $_POST["submit-check-da"];
                        } else {
                            $dap_an_dung = 1;
                        }
                        $has_dung = false;
                        for ($i = 1; $i <= $sum_da; $i++) {
                            $is_skip = false;
                            if ($i == $dap_an_dung && !$has_dung) {
                                $is_dung = 1;
                            } else {
                                $is_dung = 0;
                            }
                            $da_content = $da_type = "none";
                            if (isset($_POST["submit-da-content-$i"]) && !empty($_POST["submit-da-content-$i"])) {
                                $da_content = $_POST["submit-da-content-$i"];
                                $da_type = "text";
                                if($is_dung == 1) {$has_dung = true;}
                            } else {
                                if ($_FILES["submit-da-anh-$i"]["error"] > 0) {
                                    $is_skip = true;
                                } else {
                                    $da_content = addslashes($_FILES["submit-da-anh-$i"]["name"]);
                                    $da_type = "image";
                                    if($is_dung == 1) {$has_dung = true;}
                                }
                            }
                            if (!$is_skip) {
                                $da_arr[] = array(
                                    "stt" => $i,
                                    "is_dung" => $is_dung,
                                    "da_content" => $da_content,
                                    "da_type" => $da_type
                                );
                            }
                        }
                        if(!$has_dung) {
                            $da_arr = array();
                        }
                    }
                    if(($anh_title!="none" || $content!="none") && (count($da_arr) >= 2 || $da_dien_tu != "")) {
                        if($nhom_name != "") {
                            $db = new Nhom_Cau_Hoi();
                            $nhom = $db->addNhom($nhom_name,$lmID);
                        }
                        $db = new Cau_Hoi();
                        $main = 0;
                        $temp3 = explode("-",$maso);
                        if(isset($temp3[2]) && stripos($temp3[2],"a") !== false) {
                            $main = 1;
                        }
                        $error_arr = $db->addCauHoi($maso,$content,$anh_title,$type_da,$cdID,$do_kho,$loai,$nhom,$note,0,$monID,$main);
                        $error_msg = $error_arr[0];
                        $cID = $error_arr[1];
                        if(!is_dir("http://localhost/www/TDUONG/luyenthi/upload/$monID")){
                            mkdir("http://localhost/www/TDUONG/luyenthi/upload/$monID");
                        }
                        if(!is_dir("http://localhost/www/TDUONG/luyenthi/upload/$monID/dapan")){
                            mkdir("http://localhost/www/TDUONG/luyenthi/upload/$monID/dapan");
                        }
                        move_uploaded_file($_FILES["submit-anh-title"]["tmp_name"],"http://localhost/www/TDUONG/luyenthi/".$db->getUrlDe($monID,$_FILES["submit-anh-title"]["name"]));
                        $db->addDapAnDai($da_chi_tiet,$anh_chi_tiet,$cID);
                        move_uploaded_file($_FILES["submit-da-anh-chi-tiet"]["tmp_name"],"http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($monID,$_FILES["submit-da-anh-chi-tiet"]["name"]));
                        if($type_da == "trac-nghiem") {
                            for ($i = 0; $i < count($da_arr); $i++) {
                                $db->addDapAnNgan($da_arr[$i]["da_content"], $da_arr[$i]["da_type"], $da_arr[$i]["is_dung"], $cID, 1);
                                if ($da_arr[$i]["da_type"] == "image") {
                                    move_uploaded_file($_FILES["submit-da-anh-" . $da_arr[$i]["stt"]]["tmp_name"], "http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($monID, $_FILES["submit-da-anh-" . $da_arr[$i]["stt"]]["name"]));
                                }
                            }
                            $db->addDapAnNgan("Em không làm được", "text", 0, $cID, 0);
                        } else if($type_da == "dien-tu") {
                            $db->delDapAnNganByCau($cID);
                            $db->addDapAnNgan($da_dien_tu, "text", 1, $cID, 1);
                        } else {

                        }
                        $error = false;
                        $error_msg .= " Bạn đã thêm câu hỏi thành công!";
                    } else {
                        $error = true;
                        $error_msg = " Vui lòng nhập đầy đủ thông tin!";
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/them-cau-hoi/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                <div class="form-cau-hoi">
                    <div class="col-lg-8">
                        <div class="panel-heading">
                            <h5 class="panel-title">Nội dung</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <?php if($error_msg != "") { ?>
                                    <div class="alert alert-danger no-border">
                                        <span class="text-semibold">Kết quả: </span>
                                        <?php
                                            echo $error_msg;
                                            if(!$error) {
                                                echo" <a href='http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$cID/'>Xem</a>";
                                            }
                                        ?>
                                    </div>
                                <?php } ?>
                                <p class="content-group">Nội dung câu hỏi và đáp án <strong>không được bao gồm</strong> các từ đặc biệt như <code>'</code>, <code>></code>, <code><</code>, <code>\[</code>, <code>\)</code>, <code>mml</code>, ...</p>
<!--                                <form class="form-horizontal" action="#">-->
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Đề bài + Ảnh (nếu có)</label>
                                        <div class="col-sm-9" style="text-align: right;">
                                            <input type="file" name="submit-anh-title" class="file-input chon-dang-anh" data-show-caption="false" data-show-upload="false">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php if($is_ct == 1) { ?>
                                        <div class="col-lg-6">
                                            <textarea rows="5" cols="5" name="submit-content" style="resize: vertical;" class="form-control chon-dang-text" placeholder="Nội dung câu hỏi"></textarea>
                                        </div>
                                        <div class="col-lg-6 panel-heading dap-an-show" style="display: none;padding-bottom: 0;"></div>
                                        <?php } else { ?>
                                        <div class="col-lg-12">
                                            <textarea rows="5" cols="5" name="submit-content" style="resize: vertical;" class="form-control chon-dang-text" placeholder="Nội dung câu hỏi"></textarea>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <button type="button" class="btn btn-primary btn-sm bg-brown-400 add-dap-an-chi-tiet none-chi-tiet">Đáp án chi tiết (nếu có)</button>
                                        </div>
                                        <div class="col-sm-9" style="text-align: right;">
                                            <input type="file" name="submit-da-anh-chi-tiet" class="file-input chon-anh-chi-tiet" data-show-caption="false" data-show-upload="false">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <?php if($is_ct == 1) { ?>
                                        <div class="col-lg-6">
                                            <textarea rows="5" cols="5" name="submit-da-chi-tiet" style="resize: vertical;" class="form-control chon-content-chi-tiet" placeholder="Nội dung đáp án chi tiết"></textarea>
                                        </div>
                                        <div class="col-lg-6 panel-heading dap-an-show" style="display: none;padding-bottom: 0;"></div>
                                        <?php } else { ?>
                                        <div class="col-lg-12">
                                            <textarea rows="5" cols="5" name="submit-da-chi-tiet" style="resize: vertical;" class="form-control chon-content-chi-tiet" placeholder="Nội dung đáp án chi tiết"></textarea>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-12"></label>
                                        <div class="col-lg-12 list-dap-an">
                                            <?php for($i=1;$i<=$sum_da;$i++) { ?>
                                                <div class="form-group <?php if($i>=$sum_da-1){echo"form-hide";} ?>">
                                                    <div class="radio col-xs-1">
                                                        <label>
                                                            <input type="radio" name="submit-check-da" value="<?php echo $i; ?>" class="control-primary"/>
                                                        </label>
                                                    </div>
                                                    <!--<div class="radio col-xs-1">
                                                        <label>
                                                            <input type="radio" name="radio-styled-color" class="control-primary" disabled="disabled">
                                                        </label>
                                                    </div>-->
                                                    <div class="col-xs-7"><input type="text" name="submit-da-content-<?php echo $i; ?>" class="form-control dap-an-content" /></div>
                                                    <div class="col-xs-3"><input type="file" name="submit-da-anh-<?php echo $i; ?>" class="file-input dap-an-anh" data-show-caption="false" data-show-upload="false"></div>
                                                    <div class="col-xs-1" style="padding-left: 4%;margin-top: 8px;">
                                                        <?php
                                                            if($i>=3) {
                                                                echo"<ul class='icons-list'>
                                                                    <li><a><i class='icon-cross3'></i></a></li>
                                                                </ul>";
                                                            }
                                                        ?>
                                                    </div>
                                                    <div class="col-lg-12 panel-heading dap-an-show" style="clear: both;display: none;padding-bottom: 0;"></div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="col-lg-12 list-dien-tu" style="display: none;">
                                            <div class="form-group">
                                                <div class="col-lg-12"><input type="text" name="submit-dien-tu" class="form-control dien-tu-content" placeholder="Đáp án đúng mà học sinh sẽ đánh vào" /></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <button type="button" class="btn btn-primary btn-sm bg-brown-400 add-dap-an">Thêm đáp án</button>
                                            <label class="control-label add-dap-an-error" style="display: none;">Hỗ trợ tối đa 6 đáp án!</label>
                                        </div>
                                        <div class="col-xs-6" style="text-align: right;">
                                            <!--<button type="button" class="btn btn-primary btn-sm bg-brown-400 copy-cau-hoi">Copy</button>-->
                                            <button type="submit" name="submit-add-ok" class="btn btn-primary btn-sm bg-blue-400 add-cau-hoi">Thêm</button>
                                        </div>
                                    </div>
                                </fieldset>
<!--                                </form>-->
                            </div>
                        </div>
<!--                        <div class="panel panel-flat">-->
<!--                            <div class="panel-heading">-->
<!--                                <h5 class="panel-title">Nhóm câu hỏi</h5>-->
<!--                            </div>-->
<!--                            <div class="panel-body">-->
<!--                                <fieldset class="content-group">-->
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-sm-3">Chọn các nhóm</label>-->
<!--                                        <div class="col-sm-9">-->
<!--                                            <select name="submit-nhom" class="form-control">-->
<!--                                                <option value="0">Chọn nhóm</option>-->
<!--                                                --><?php
//                                                $db = new Nhom_Cau_Hoi();
//                                                $result = $db->getNhomByMon($lmID);
//                                                while($data=$result->fetch_assoc()) {
//                                                    echo"<option value='$data[ID_N]'>$data[name]</option>";
//                                                }
//                                                ?>
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-sm-3">Thêm nhóm mới</label>-->
<!--                                        <div class="col-sm-9">-->
<!--                                            <input type="text" name="submit-nhom-name" class="form-control" placeholder="Điền tên nhóm mới!" />-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </fieldset>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>

                    <div class="col-lg-4">

                        <!-- Sales stats -->
                        <div class="panel-heading">
                            <h5 class="panel-title">Định dạng</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
<!--                                <form class="form-horizontal" action="#">-->
                                    <fieldset class="content-group">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input type="text" name="submit-maso" class="form-control" placeholder="Mã câu hỏi" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <select name="submit-cd" class="form-control">
                                                    <option value="0">Chọn chuyên đề</option>
                                                    <?php
                                                        $db = new Chuyen_De();
                                                        $result = $db->getChuyenDeByMon((new Mon_Hoc())->getMonOfLop($lmID));
                                                        while($data=$result->fetch_assoc()) {
                                                            echo"<option value='$data[ID_CD]'>$data[name]</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <select name="submit-loai" class="form-control">
                                                    <option value="0">Chọn phân loại</option>
                                                    <?php
                                                        $db = new Phan_Loai();
                                                        $result = $db->getPhanLoai();
                                                        while($data=$result->fetch_assoc()) {
                                                            echo"<option value='$data[ID_PL]'>$data[name]</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
<!--                                </form>-->
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h5 class="panel-title">Tùy chọn</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
<!--                                <form class="form-horizontal" action="#">-->
                                    <fieldset class="content-group">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <select name="submit-type-da" class="form-control chon-dang-dap-an">
                                                    <option value="trac-nghiem">Chọn 4 phương án A, B, C và D</option>
<!--                                                    <option value="dien-tu">Điền vào chỗ trống</option>-->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <select name="submit-do-kho" class="form-control">
                                                <?php
                                                    $db = new Do_Kho();
                                                    $result = $db->getDoKho();
                                                    while($data=$result->fetch_assoc()) {
                                                        echo"<option value='$data[ID_K]'>Độ khó: $data[name]</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <textarea rows="4" cols="5" name="submit-note" style="resize: vertical;" class="form-control" placeholder="Ghi chú"></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
<!--                                </form>-->
                            </div>
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
//                $("body").addClass("sidebar-xs");
                $("span.hidden-xs").remove();

                /*$(".chon-dang-anh").closest("div.file-input").show();
                $(".chon-dang-text").hide();
                $(".content-wrapper").delegate("div.form-cau-hoi .chon-dang","change",function() {
                    me = $(this).closest("div.form-group");
                    if($(this).val() == "image") {
                        me.find(".chon-dang-anh").closest("div.file-input").show();
                        me.find("> div.panel-heading").hide();
                        me.find("> div.dap-an-show").html("");
                        me.find(".chon-dang-text").hide();
                    } else {
                        me.find(".chon-dang-anh").closest("div.file-input").hide();
                        me.find(".chon-dang-text").show();
                    }
                });*/

                $(".chon-content-chi-tiet").hide();
                $(".chon-anh-chi-tiet").closest("div.file-input").hide();
                $(".add-dap-an-chi-tiet").click(function () {
                    if($(this).hasClass("none-chi-tiet")) {
                        $(this).closest("div.form-cau-hoi").find(".chon-content-chi-tiet").show();
                        $(this).closest("div.form-cau-hoi").find(".chon-anh-chi-tiet").closest("div.file-input").show();
                        $(this).removeClass("none-chi-tiet");
                    } else {
                        $(this).closest("div.form-cau-hoi").find(".chon-content-chi-tiet").hide();
                        $(this).closest("div.form-cau-hoi").find(".chon-anh-chi-tiet").closest("div.file-input").hide();
                        $(this).addClass("none-chi-tiet");
                    }
                });

                $(".form-hide").hide();
                $(".content-wrapper").delegate("div.form-cau-hoi .add-dap-an","click",function () {
                    me = $(this).closest("div.form-group").prev();
                    me.find(".form-hide").each(function(index, element) {
                        $(element).removeClass("form-hide").show();
                        return false;
                    });
                    if(me.find(".form-hide").length == 0) {
                        $(this).closest("div.form-group").find(".add-dap-an-error").show();
                    }
                });
                $(".content-wrapper").delegate("div.form-cau-hoi ul.icons-list > li > a > i.icon-cross3","click",function () {
                    $(this).closest("div.form-group").find("> div.checkbox span").removeClass("checked");
                    $(this).closest("div.form-group").find("> div.checkbox input").removeAttr("checked");
                    $(this).closest("div.form-group").find("> div input.dap-an-content").val("");
                    $(this).closest("div.form-group").addClass("form-hide").hide();
                    $(this).closest("div.list-dap-an").closest("div.form-group").next().find(".add-dap-an-error").hide();
                });
                $(".content-wrapper").delegate("div.form-cau-hoi li.form-close","click",function () {
                    if($("div.form-cau-hoi").length > 1) {
                        $(this).closest("div.form-cau-hoi").remove();
                    }
                });
                $(".content-wrapper").delegate("div.form-cau-hoi input.dap-an-anh","click",function() {
                    $(this).closest("div.form-group").find("> div input.dap-an-content").val("");
                    $(this).closest("div.form-group").find("> div.dap-an-show").html("");
                });
                $(".content-wrapper").delegate("div.form-cau-hoi .chon-dang-dap-an","change",function () {
                    me = $(this).closest("div.form-cau-hoi");
                    if($(this).val() == "dien-tu") {
                        me.find(".list-dap-an").hide();
                        me.find(".add-dap-an").hide();
                        me.find(".list-dien-tu").show();
                    } else {
                        me.find(".list-dien-tu").hide();
                        me.find(".list-dien-tu").find("input.dien-tu-content").val("");
                        me.find(".list-dap-an").show();
                        me.find(".add-dap-an").show();
                    }
                });
                <?php if($is_ct == 1) { ?>
                /*$(".content-wrapper").delegate("div.form-cau-hoi .list-dap-an div input.dap-an-content","mouseleave",function () {
                    $(this).closest("div.form-group").find("> div.panel-heading").hide();
                });
                $(".content-wrapper").delegate("div.form-cau-hoi .list-dap-an div input.dap-an-content","mouseenter",function () {
                    if($(this).val() != "") {
                        $(this).closest("div.form-group").find("> div.panel-heading").show();
                    }
                });*/
                $(".content-wrapper").delegate("div.form-cau-hoi textarea.chon-dang-text, div.form-cau-hoi textarea.chon-content-chi-tiet, div.form-cau-hoi .list-dap-an div input.dap-an-content","click",function () {
                    $(this).typeWatch({
                        captureLength: 3,
                        callback: function(me) {
                            var dm = $(this);
                            display_content(me, dm);
                        }
                    });
                });
                <?php } ?>
                $(".content-wrapper").delegate("div.form-cau-hoi button.copy-cau-hoi","click",function () {
                    var me = $(this).closest("div.form-cau-hoi").clone(true);
                    me.appendTo(".content-wrapper");
                });
                $(".content-wrapper").delegate("div.form-cau-hoi select","change",function () {
                    var val = $(this).val(); //get new value
                    $(this).find("option").each(function (index, element) {
                        if($(element).val() != val) {
                            $(element).removeAttr("selected");
                        } else {
                            $(element).attr("selected", "selected");
                        }
                    });
                });
                function display_content(me, dm) {
                    if (me != "") {
                        /*me = me.replace(/$\\/g,"\\(\\");
                        me = me.replace(/$/g,"\\)");
                        me = me.replace(/\[/g,"\(");
                        me = me.replace(/\]/g,"\)");*/
                        me = me.replace(/\n/g,"<br />");
                        me = me.replace(/'/g,"1o1");
                        me = me.replace(/<\//g,"2o2");
                        me = me.replace(/</g,"3o3");
                        me = me.replace(/>/g,"4o4");
                        me = me.replace(/\+/g,"5o5");
                        me = me.replace(/&/g,"6o6");
                        dm.closest("div.form-group").find("> div.dap-an-show").html("<iframe class='embed-responsive-item' style='height: 90px;width: 100%;border:0;' src='http://localhost/www/TDUONG/luyenthi/admin/ajax/formula.php?formula=" + me + "'></iframe>");
                        dm.closest("div.form-group").find("> div.panel-heading").show();
                    } else {
                        dm.closest("div.form-group").find("> div.panel-heading").hide();
                        dm.closest("div.form-group").find("> div.dap-an-show").html("");
                    }
                }
            });
        </script>
