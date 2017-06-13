
    <?php
        ini_set('max_execution_time', 900);
        include_once "../include/top.php";
        $db = new Mon_Hoc();
        $monID = $db->getMonOfLop($lmID);
        $lop_mon_name = $db->getNameMonLop($lmID);
        $next_date = getNextCN();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Tạo đề thi</h4>-->
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
                $mota = NULL;
                $ketqua = array();
                $deID = 0;
                $num = 1;
                $dapan_e = 0;
                $time = 0;
                $khac = 0;
                $goc = 0;
                $loai = 0;
                $isUnlock = 0;
                $isSort = 0;
                $deForm = 0;
                $code_nhom = "XXXXXX";
                $type = "none";
                $object = 0;
                $date_close = "0000-00-00";
                if(isset($_POST["tao-de"])) {
                    $db = new De_Thi();
                    if(isset($_POST["submit-ngay-dong"]) && !empty($_POST["submit-ngay-dong"])) {
                        $date_close = trim($_POST["submit-ngay-dong"]);
                    }
                    if(isset($_POST["submit-mota"]) && !empty($_POST["submit-mota"])) {
                        $mota = $_POST["submit-mota"];
                    }
                    if(isset($_POST["submit-num"]) && !empty($_POST["submit-num"])) {
                        $num = $_POST["submit-num"];
                    }
                    if(isset($_POST["submit-dap-an-e"]) && !empty($_POST["submit-dap-an-e"])) {
                        $dapan_e = 1;
                    }
                    if(isset($_POST["submit-khac"]) && !empty($_POST["submit-khac"])) {
                        $khac = 1;
                    }
                    if(isset($_POST["submit-goc"]) && !empty($_POST["submit-goc"])) {
                        $goc = 1;
                    }
                    if(isset($_POST["submit-time"]) && !empty($_POST["submit-time"])) {
                        $time = $_POST["submit-time"];
                    }
                    if(isset($_POST["submit-loai-de"]) && !empty($_POST["submit-loai-de"])) {
                        $loai = $_POST["submit-loai-de"];
                    }
                    if(isset($_POST["submit-chuyen-de-unlock"]) && !empty($_POST["submit-chuyen-de-unlock"])) {
                        $isUnlock = 1;
                    }
                    if(isset($_POST["submit-sort"]) && !empty($_POST["submit-sort"])) {
                        $isSort = 1;
                    }
                    if(isset($_POST["submit-de-form"]) && !empty($_POST["submit-de-form"])) {
                        $deForm = $_POST["submit-de-form"];
                    }
                    if(isset($_POST["submit-type-de"]) && !empty($_POST["submit-type-de"])) {
                        $type = $_POST["submit-type-de"];
                        if($type == "kiem-tra") {
                            $db->addBuoiKiemTra($next_date, $monID);
                            $object = $db->getBuoiKtFromNgay($next_date, $monID);
                        }
                    }
                    if(($deForm==1 || $deForm==2) && $date_close!="" && $mota && $num > 0 && is_numeric($num) && is_numeric($dapan_e) && $time > 0 && is_numeric($time) && is_numeric($khac) && is_numeric($goc) && validId($loai) && $type!="none") {
                        $maso = randMaso(3);
                        if($code_nhom != "XXXXXX" && validText($code_nhom) && strlen($code_nhom)>=4) {
                            $code = $code_nhom;
                        } else {
                            $code = "";
                            while(true) {
                                $code = randMasoCode(6);
                                if(!$db->checkTonTaiMasoNhom($code)) {
                                    break;
                                }
                            }
                        }
                        if(!$db->checkTonTaiMasoNhom($code)) {
                            $nhom = $db->addNhomDe($code, $type, $date_close, $lmID, $object);
                            $deID = $db->taoDe($maso, $mota, $lmID, $khac, $time, $nhom, 1, $loai, $deForm);
                            for ($i = 1; $i <= $num; $i++) {
                                $cdID = $sl = $loai = $kho = $nhan_ban = $luuygoc = 0;
                                $option = $option_note = "";
                                if (isset($_POST["submit-cd-$i"]) && !empty($_POST["submit-cd-$i"])) {
                                    $cdID = $_POST["submit-cd-$i"];
                                }
                                if (isset($_POST["submit-sl-$i"]) && !empty($_POST["submit-sl-$i"])) {
                                    $sl = $_POST["submit-sl-$i"];
                                }
                                if (isset($_POST["submit-loai-$i"]) && !empty($_POST["submit-loai-$i"])) {
                                    $loai = $_POST["submit-loai-$i"];
                                }
                                if (isset($_POST["submit-kho-$i"]) && !empty($_POST["submit-kho-$i"])) {
                                    $kho = $_POST["submit-kho-$i"];
                                }
                                if (isset($_POST["submit-option-$i"]) && !empty($_POST["submit-option-$i"])) {
                                    $option = $_POST["submit-option-$i"].",";
                                }
                                if (isset($_POST["submit-note-$i"]) && !empty($_POST["submit-note-$i"])) {
                                    $option_note = $_POST["submit-note-$i"].",";
                                }
                                if(isset($_POST["submit-nhan-ban-$i"]) && !empty($_POST["submit-nhan-ban-$i"])) {
                                    $nhan_ban = 1;
                                }
                                if(isset($_POST["submit-luuy-goc-$i"]) && !empty($_POST["submit-luuy-goc-$i"])) {
                                    $luuygoc = 1;
                                }
                                $db->taoDeNoiDung($cdID, $sl, $loai, $kho, $option, $option_note, $deID, $dapan_e, $khac, $goc, $nhan_ban, $luuygoc, $isUnlock, $isSort, $lmID, $deForm);
                            }
                        } else {
                            $error = true;
                            $error_msg = " Đã tồn tại nhóm đề có mã số này $code!";
                        }
                    } else {
                        $error = true;
                        $error_msg = " Vui lòng nhập đầy đủ thông tin!";
                    }

                    if(!$error) {
                        header("location:http://localhost/www/TDUONG/luyenthi/admin/xem-de/$deID/");
                        exit();
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/tao-de-thi/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                    <div class="col-lg-8">
                        <div class="panel-heading">
                            <h5 class="panel-title">Tùy chọn</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body" style="padding-bottom: 0;">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Ngày đóng (nếu muốn)</label>
                                        <div class="col-sm-6">
                                            <input type="text" name="submit-ngay-dong" id="ngay-dong" class="form-control" placeholder="Click để chọn ngày đóng dự kiến" />
                                        </div>
                                        <label class="control-label col-sm-3"><code>Luôn đóng vào 3h sáng</code></label>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6">
<!--                                            <div class="checkbox">-->
<!--                                                <label>-->
<!--                                                    <input type="checkbox" class="control-primary" name="submit-dap-an-e">-->
<!--                                                    Đáp án Em không làm được-->
<!--                                                </label>-->
<!--                                            </div>-->
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" id="submit-goc" checked="checked" name="submit-goc">
                                                    Chỉ lấy câu gốc (có dạng D05-06a-1<strong>a</strong>3, H07-04b-2<strong>a</strong>)
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" name="submit-khac">
                                                    Đáp án Khác
                                                </label>
                                            </div>
                                        </div>
                                    </div>
<!--                                    <div class="form-group">-->
<!--                                        <div class="col-sm-6">-->
<!--                                            <div class="checkbox">-->
<!--                                                <label>-->
<!--                                                    <input type="checkbox" class="control-primary" id="submit-goc" checked="checked" name="submit-goc">-->
<!--                                                    Chỉ lấy câu gốc (có dạng D05-06a-1<strong>a</strong>3, H07-04b-2<strong>a</strong>)-->
<!--                                                </label>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" name="submit-chuyen-de-unlock">
                                                    Chỉ lấy các chuyên đề đã unlock
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" name="submit-sort">
                                                    Lấy theo mã số câu tăng dần
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <?php for($i=1;$i<=60;$i++) {
                            if($i==1) {
                                $style = "";
                            } else {
                                $style = "display:none;";
                            }
                        ?>
                        <div class="panel panel-flat form-chon" id="form-chon-<?php echo $i; ?>" style="<?php echo $style; ?>">
                            <div class="panel-heading">
                                <h5 class="panel-title">#<?php echo $i; ?></h5>
                                <?php if($i > 1) { ?>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <li class="form-close"><a><i class='icon-cross3'></i></a></li>
                                    </ul>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="panel-body">
                                <?php if($i == 1 && $error_msg != "" && $error) { ?>
                                    <div class="alert alert-danger no-border">
                                        <span class="text-semibold">Kết quả: </span>
                                        <?php echo $error_msg; ?>
                                    </div>
                                <?php } ?>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Số câu max</label>
<!--                                        <div class="col-sm-6">-->
<!--                                            <select name="submit-cd---><?php //echo $i; ?><!--" class="form-control">-->
<!--                                                <option value="0">Chọn chuyên đề</option>-->
<!--                                            --><?php
//                                                $db = new Chuyen_De();
//                                                $result = $db->getChuyenDeUnlockByMon($lmID,$monID);
//                                                while($data=$result->fetch_assoc()) {
//                                                    echo"<option value='$data[ID_CD]'>$data[maso] - $data[name]</option>";
//                                                }
//                                            ?>
<!--                                            </select>-->
<!--                                        </div>-->
                                        <div class="col-sm-3">
                                            <input type="number" name="submit-sl-<?php echo $i; ?>" class="form-control" min="1" value="50" />
                                        </div>
                                        <label class="control-label col-sm-3">Độ khó</label>
                                        <div class="col-sm-3">
                                            <select name="submit-kho-<?php echo $i; ?>" class="form-control">
                                                <option value="0">Chọn độ khó</option>
                                                <?php
                                                $db = new Do_Kho();
                                                $result = $db->getDoKho();
                                                while($data=$result->fetch_assoc()) {
                                                    echo"<option value='$data[ID_K]'>$data[name]</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-sm-3">Phân loại / Độ khó</label>-->
<!--                                        <div class="col-sm-6">-->
<!--                                            <select name="submit-loai---><?php //echo $i; ?><!--" class="form-control">-->
<!--                                                <option value="0">Chọn phân loại</option>-->
<!--                                                --><?php
//                                                $db = new Phan_Loai();
//                                                $result = $db->getPhanLoaiWithNum();
//                                                while($data=$result->fetch_assoc()) {
//                                                    echo"<option value='$data[ID_PL]'>$data[name] ($data[num])</option>";
//                                                }
//                                                ?>
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                        <div class="col-sm-3">-->
<!--                                            <select name="submit-kho---><?php //echo $i; ?><!--" class="form-control">-->
<!--                                                <option value="0">Chọn độ khó</option>-->
<!--                                                --><?php
//                                                $db = new Do_Kho();
//                                                $result = $db->getDoKho();
//                                                while($data=$result->fetch_assoc()) {
//                                                    echo"<option value='$data[ID_K]'>$data[name]</option>";
//                                                }
//                                                ?>
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Tùy chọn câu cụ thể</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="submit-option-<?php echo $i; ?>" class="form-control" placeholder="D01a, D03c, H07-3a, .. cách nhau bằng dấu phẩy" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Hoặc note có chứa</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="submit-note-<?php echo $i; ?>" class="form-control" placeholder="D01, D07,.." />
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary submit-luuy-goc" name="submit-luuy-goc-<?php echo $i; ?>">
                                                    Ưu tiên lấy câu gốc
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary submit-nhan-ban" name="submit-nhan-ban-<?php echo $i; ?>">
                                                    Chỉ lấy câu nhân bản (b)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div class="col-lg-12" style="text-align: right;">
                                    <input type="hidden" min="1" name="submit-num" id="submit-num" value="1" />
                                    <button type="button" class="btn btn-primary btn-sm bg-brown-400 add-form">Thêm</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-heading">
                            <h5 class="panel-title">Kiểu đề thi</h5>
                        </div>
                        <!-- Sales stats -->
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <p class="content-group">+ <strong>Đề thi tĩnh</strong> là đề thi mà số câu hỏi được tạo sẵn và cố định</p>
                                <p class="content-group">+ <strong>Đề thi động</strong> là đề thi mà số câu hỏi được tạo trong lúc xáo đề (các đề sẽ có các câu hỏi khác nhau)</p>
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-md-12">
                                            <select name="submit-de-form" class="form-control">
                                                <option value="0">Chọn kiểu đề thi</option>
                                                <option value="1">Đề thi tĩnh</option>
                                                <option value="2">Đề thi động</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Loại đề thi</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <p class="content-group">+ <strong>Đề kiểm tra</strong> là đề thi mà học sinh không được phép làm lại, điểm được đẩy về trang Bgo, cần tài khoản >= 20k và sẽ bị trừ đi 20k khi làm</p>
                                <p class="content-group">+ <strong>Đề chuyên đề</strong> là đề học sinh được làm lại sau 24h, điểm chỉ lưu trên trang trắc nghiệm, chỉ cần tài khoản >= 0k</p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <select name="submit-type-de" id="submit-type-de" class="form-control">
                                                <option value="none">Chọn loại đề thi</option>
                                                <option value="kiem-tra" data-ngay="<?php echo formatDateUp($next_date); ?>">Đề điểm tra (<?php echo formatDateUp($next_date); ?>)</option>
                                                <option value="chuyen-de">Đề chuyên đề</option>
                                                <option value="thi-thu">Đề thi thử</option>
                                            </select>
                                            <input type="hidden" value="<?php echo $next_date; ?>" name="submit-buoi-id" />
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-md-6">Loại đề</label>
                                        <div class="col-md-6">
                                            <select name="submit-loai-de" id="submit-loai-de" class="form-control">
                                                <?php
                                                $db = new Loai_De();
                                                $result = $db->getLoaiDe();
                                                while($data=$result->fetch_assoc()) {
                                                    echo"<option value='$data[ID_D]' data-de='$data[name]'>Đề $data[name]</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <textarea rows="4" cols="5" name="submit-mota" id="submit-mota" style="resize: vertical;" class="form-control" placeholder="Tên đề thi *"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">Thời gian làm bài</label>
                                        <div class="col-md-6">
                                            <input type="text" name="submit-time" class="form-control" placeholder="(phút)" value="90" />
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="submit" name="tao-de" class="btn btn-primary btn-sm bg-blue-400 tao-de-thi">Tạo đề</button>
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

                $("#submit-mota").click(function() {
                    var type_de = $("#submit-type-de option:selected").val();
                    var de = $("#submit-loai-de option:selected").attr("data-de");
                    if(type_de == "kiem-tra" && (de == "B" || de == "G" || de == "Y")) {
                        var ngay = $("#submit-type-de option:selected").attr("data-ngay");
                        $(this).html("Đề kiểm tra <?php echo $lop_mon_name; ?> ngày " + ngay + " (" + de + ")");
                    }
                });

                $(".tao-de-thi").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        return true;
                    } else {
                        return false;
                    }
                });

                $("#submit-goc").click(function() {
                    if($(this).is(":checked")) {
                        $(".submit-luuy-goc, .submit-nhan-ban").prop("checked",false).closest("span").removeClass("checked");
                    }
                });

                $(".submit-luuy-goc, .submit-nhan-ban").click(function() {
                    if ($(this).is(":checked")) {
                        $("#submit-goc").prop("checked",false).closest("span").removeClass("checked");
                    }
                });

                $(".content-wrapper").delegate("div.form-chon li.form-close","click",function () {
                    if($("div.form-chon").length > 1) {
                        $(this).closest("div.form-chon").fadeOut("fast");
                        var num = $("#submit-num").val();
                        num = parseInt(num) - 1;
                        $("#form-chon-" + num).fadeIn("fast");
                        $("#submit-num").val(num);
                    }
                });

                $(".add-form").click(function() {
                    var num = $("#submit-num").val();
                    num = parseInt(num) + 1;
                    if($("#form-chon-" + num).length) {
                        $("#form-chon-" + num).fadeIn("fast");
                        $("#submit-num").val(num);
                    }
                    return false;
                });

                $("#ngay-dong").datepicker({
                    dateFormat: 'yy-mm-dd',
                    defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
                });
            });
        </script>
