
    <?php
        ini_set('max_execution_time', 900);
        include_once "../include/top.php";
        if(isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
            $nhom = $_GET["nhom"];
        } else {
            $nhom = 0;
        }
        $db0 = new De_Thi();
        $result0 = $db0->getNhomDeById($nhom);
        $data0 = $result0->fetch_assoc();
        $deID = $db0->getDeThiMainByNhom($nhom);
        $result1 = $db0->getDeThiById($deID);
        $data1 = $result1->fetch_assoc();

        $db = new Mon_Hoc();
        $monID = $db->getMonOfLop($lmID);
        $lop_mon_name = $db->getNameMonLop($lmID);
        $next_date = $db0->getNgayFromBuoi($data0["object"], $monID);

        $result2 = $db0->getLinkShare($nhom);
        $data2 = $result2->fetch_assoc();
        $link = $data2["content"];
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Sửa --><?php //echo $data1["mota"]; ?><!--</h4>-->
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
                $date_close = "0000-00-00";
                $type = "none";
                $loai = 0;
                $time = 0;
                $object = 0;
                if(isset($_POST["tao-de"])) {
                    if(isset($_POST["submit-ngay-dong"]) && !empty($_POST["submit-ngay-dong"])) {
                        $date_close = trim($_POST["submit-ngay-dong"]);
                    }
                    if(isset($_POST["submit-mota"]) && !empty($_POST["submit-mota"])) {
                        $mota = $_POST["submit-mota"];
                    }
                    if(isset($_POST["submit-time"]) && !empty($_POST["submit-time"])) {
                        $time = $_POST["submit-time"];
                    }
                    if(isset($_POST["submit-loai-de"]) && !empty($_POST["submit-loai-de"])) {
                        $loai = $_POST["submit-loai-de"];
                    }
                    if(isset($_POST["submit-type-de"]) && !empty($_POST["submit-type-de"])) {
                        $type = $_POST["submit-type-de"];
                        if($type == "kiem-tra") {
                            $db0->addBuoiKiemTra($next_date, $monID);
                            $object = $db0->getBuoiKtFromNgay($next_date, $monID);
                        }
                    }
                    if($date_close!="" && $mota && $time > 0 && is_numeric($time) && validId($loai) && $type!="none") {
                        $db0->suaDe($date_close,$type,$object,$loai,$mota,$time,$nhom);
                    } else {
                        $error = true;
                        $error_msg = " Vui lòng nhập đầy đủ thông tin!";
                    }

                    if(!$error) {
                        header("location:http://localhost/www/TDUONG/luyenthi/admin/sua-de/$nhom/");
                        exit();
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/sua-de/<?php echo $nhom; ?>/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                    <div class="col-lg-4">
                        <!-- Sales stats -->
                        <div class="panel-heading">
                            <h5 class="panel-title"><?php echo $data1["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <p class="content-group"><code>Luôn đóng vào 3h sáng</code></p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Ngày đóng (nếu muốn)</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="submit-ngay-dong" id="ngay-dong" class="form-control" value="<?php if($data0["date_close"] != "0000-00-00"){echo $data0["date_close"];} ?>" placeholder="Click để chọn ngày đóng dự kiến" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-12">Tạo link chia sẻ <i class="icon-link" id="link-reload"></i></label>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-12">
                                            <input type="text" name="submit-link" id="link-share" onClick="SelectAll('link-share');" class="form-control" value="<?php echo $link; ?>" placeholder="Link chia sẻ" />
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel-heading">
                            <h5 class="panel-title">Kiểu đề thi</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <?php
                                if($data1["form"] == 1) {
                                    echo"<p class='content-group'>+ <strong>Đề thi tĩnh</strong> là đề thi mà số câu hỏi được tạo sẵn và cố định</p>";
                                } else if($data1["form"] == 2) {
                                    echo"<p class='content-group'>+ <strong>Đề thi động</strong> là đề thi mà số câu hỏi được tạo trong lúc xáo đề (các đề sẽ có các câu hỏi khác nhau)</p>
";
                                }
                                ?>
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-md-12">
                                            <select class="form-control">
                                                <?php
                                                if($data1["form"] == 1) {
                                                    echo"<option selected='selected'>Đề thi tĩnh</option>";
                                                } else if($data1["form"] == 2) {
                                                    echo"<option selected='selected'>Đề thi động</option>";
                                                }
                                                ?>
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
                                <p class="content-group">+ <strong>Đề kiểm tra</strong> là đề thi mà học sinh không được phép làm lại, điểm được đẩy về trang Bgo, cần tài khoản >= 70k và sẽ bị trừ đi 20k khi làm</p>
                                <p class="content-group">+ <strong>Đề chuyên đề</strong> là đề học sinh được làm lại sau 24h, điểm chỉ lưu trên trang trắc nghiệm, chỉ cần tài khoản >= 0k</p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <select name="submit-type-de" id="submit-type-de" class="form-control">
                                                <option value="kiem-tra" <?php if($data0["type"] == "kiem-tra"){echo"selected='selected'";} ?> data-ngay="<?php echo formatDateUp($next_date); ?>">Đề điểm tra (<?php echo formatDateUp($next_date); ?>)</option>
                                                <option value="chuyen-de" <?php if($data0["type"] == "chuyen-de"){echo"selected='selected'";} ?>>Đề chuyên đề</option>
                                                <option value="thi-thu" <?php if($data0["type"] == "thi-thu"){echo"selected='selected'";} ?>>Đề thi thử</option>
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
                                                    echo"<option value='$data[ID_D]' data-de='$data[name]'";if($data1["loai"] == $data["ID_D"]){echo"selected='selected'";}echo">Đề $data[name]</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="panel-heading">
                            <h5 class="panel-title">Thông tin</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <textarea rows="4" cols="5" name="submit-mota" id="submit-mota" style="resize: vertical;" class="form-control" placeholder="Tên đề thi *"><?php echo $data1["mota"]; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">Thời gian làm bài</label>
                                        <div class="col-md-6">
                                            <input type="text" name="submit-time" class="form-control" placeholder="(phút)" value="<?php echo $data1["time"]; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="submit" name="tao-de" class="btn btn-primary btn-sm bg-blue-400 tao-de-thi">Sửa đề</button>
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
            function SelectAll(id) {
                document.getElementById(id).focus();
                document.getElementById(id).select();
            }
            $(document).ready(function() {

                $("#link-reload").click(function () {
                    new PNotify({
                        title: 'Chia sẻ',
                        text: 'Đang chia sẻ',
                        icon: 'icon-menu6'
                    });
                    $("#link-share").val("");
                    $.ajax({
                        async: true,
                        data: "nhom_share=<?php echo $nhom; ?>",
                        type: "post",
                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                        success: function (result) {
                            $("#link-share").val(result);
                            new PNotify({
                                title: 'Chia sẻ',
                                text: 'Đã tạo link thành công!',
                                icon: 'icon-menu6'
                            });
                        }
                    });
                });

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
                    $("#form-chon-" + num).fadeIn("fast");
                    $("#submit-num").val(num);
                    return false;
                });

                $("#ngay-dong").datepicker({
                    dateFormat: 'yy-mm-dd',
                    defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
                });
            });
        </script>
