
    <?php include_once "include/top_hoc_sinh.php"; ?>

    <?php
        $me = md5("123456");
        $db2 = new De_Thi();
        $db3 = new Hoc_Sinh();
        $code = "";
        if (isset($_GET["deID"])) {
            $deID = decodeData($_GET["deID"], $me);
        } else {
            $deID = 0;
        }

        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();
        $nhom = $data0["nhom"];

//        if($data0["nhom"] == 0) {
//            if(!$db2->checkDeTuLuyenHs($hsID,$deID)) {
//                header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/1/");
//                exit();
//            }
//        }
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4>Tổng quan đề thi</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <?php
    $error_msg = "";
    $error = $need_in = false;
    $code = NULL;
    if(isset($_POST["vao-thi"])) {
        if($nhom == 0) {
            header("location:http://localhost/www/TDUONG/luyenthi/lam-bai-tai-lop/" . encodeData($deID, $me) . "/");
            exit();
        } else if($db2->checkDeOpenHocSinh($hsID, $nhom, $lmID)) {
            $result1 = $db2->getNhomDeById($nhom);
            $data1 = $result1->fetch_assoc();
            $de = NULL;
            if ($data1["type"] == "kiem-tra") {
                if ($db2->checkHocSinhLamKiemTra($hsID, $data1["object"], $lmID) == "none" || $db2->checkHocSinhDoneLam($hsID, $nhom) == 0) {
//                    if (!$db3->checkTruTien($hsID, "mang_bai_ve_nha_$lmID", $data1["object"])) {
                        //if($tien >= 20000) {
//                        $db3->truTienHocSinh($hsID, 20000, "Trừ tiền làm bài kiểm tra về nhà trên trang trắc nghiệm lúc " . date("H:i:s d/m/Y"), "mang_bai_ve_nha_$lmID", $data1["object"]);
                        $need_in = true;
                        //} else {
                        //$error = true;
                        //$error_msg = "Bạn không đủ 20.000đ trong tài khoản! Bạn đang có ".formatPrice($tien);
                        //}
//                    } else {
//                        $need_in = true;
//                    }
                    $de = $db3->getDeOfHocSinh($hsID, $lmID);
                } else {
                    $error = true;
                    $error_msg = "Bạn đã có điểm bài kiểm tra cuối tuần này!";
                }
            }
            if (!$error) {
                $deID = $db2->getRandDeThiByNhom($nhom, $de);
                header("location:http://localhost/www/TDUONG/luyenthi/lam-bai-tai-lop/" . encodeData($deID, $me) . "/");
                exit();
            }
		} else if($db2->checkHocSinhDoneLam($hsID, $nhom) != 0) {
            $error = true;
            $error_msg = "Bạn đã làm bài thi này!";
        } else {
            $error = true;
            $error_msg = "Bạn không có quyền truy cập đề thi này!";
        }
    }
    ?>

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "include/sidebar_hoc_sinh.php"; ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/xem-truoc/<?php echo $_GET["deID"]; ?>/" class="form-horizontal" method="post">
                    <!-- Main form -->
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title"><?php echo $data0["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <?php if($error_msg != "") { ?>
                                <div class="panel-body">
                                    <div class="alert alert-danger no-border" style="margin-bottom: 0;">
                                            <span class="text-semibold">
                                                <?php echo"<p>$error_msg</p>"; ?>
                                            </span>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="panel-body">
                                <div id="show-error">
                                    <p class="content-group">Môn: <strong><?php echo (new Mon_Hoc())->getNameMonLop($data0["ID_LM"]); ?></strong></p>
                                    <p class="content-group">Thời gian làm bài: <strong><?php echo $data0["time"]." phút"; ?></strong></p>
<!--                                    <p class="content-group">Số lượng câu: <strong>--><?php //echo $db2->countCauOnDe($deID); ?><!-- câu</strong></p>-->
                                    <br />
                                    <p class="content-group" style="text-decoration: underline;"><strong>LƯU Ý: </strong></p>
                                    <p class="content-group">+ Thời gian sẽ được <strong>đếm liên tục</strong>, kể cả bạn thoát ra không làm.</p>
                                    <p class="content-group">+ Nếu bị <strong>gián đoạn</strong>, bạn vẫn vào lại làm bài như bình thường</p>
    <!--                                <p class="content-group">+ <strong>Không làm bài thi</strong> trong những thời điểm chuyển giao như <strong>nửa đêm</strong> hoặc <strong>giữa trưa</strong></p>-->
<!--                                    <p class="content-group"><strong>+ Sau 24h</strong>, bạn sẽ có thể làm lại bài thi nếu muốn.</p>-->
    <!--                                <p class="content-group"><code style="font-size: 17px;">+ Khi chọn <strong>làm lại</strong>, kết quả cũ của bạn sẽ bị <strong>xóa</strong> và bạn coi như chưa làm đề này nên hãy <strong>làm lại ngay</strong> khi xóa</code></p>-->
                                </div>
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="submit" name="vao-thi" class="btn btn-primary btn-sm bg-danger-400" id="vao-thi">VÀO THI</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!-- /striped rows -->
                    </div>
                    <!-- /main form -->
                </form>
            </div>
        </div>

        </div>
        <!-- /page content -->

        <?php include_once "include/bottom_hoc_sinh.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("body").addClass("sidebar-xs");
                document.oncontextmenu = new Function("return false");
            });
        </script>
