
    <?php include_once "include/top_hoc_sinh.php"; ?>

    <?php
        if(isset($_GET["error"]) && is_numeric($_GET["error"])) {
            $error_code = $_GET["error"];
            switch ($error_code) {
                case 1:
                    $error_msg = "+ Bạn không có quyền truy cập đề thi này hoặc đã làm bài thi này!<br />";
                    break;
                case 2:
                    $error_msg = "+ Dữ liệu không chính xác!<br />";
                    break;
                case 3:
                    $error_msg = "+ Bạn đã hết thời gian làm bài!<br />";
                    break;
                case 4:
                    $error_msg = "+ Không thể truy cập đề thi này!<br />";
                    break;
                default:
                    $error_msg = "";
                    break;
            }
        } else {
            $error_msg = "";
        }
        $me = md5("123456");
        $db2 = new Mon_Hoc();
        $db = new De_Thi();
        $db3 = new Hoc_Sinh();
        $db4 = new Luyen_De();
        $date_in = $db3->getHocSinhDateIn($hsID, $lmID);
//        $tien = $db3->getTienHocSinh($hsID);
//        $result2 = $db3->checkHocSinhKhoa($hsID);
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4>Danh sách đề kiểm tra</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "include/sidebar_hoc_sinh.php"; ?>

            <?php

//            if(isset($_POST["submit-unlock"])) {
//                $num = $db3->getHocSinhLock($hsID);
//                $need_tien = 10 * $num * 1000;
//                if($tien >= $need_tien) {
//                    if($result2 != false) {
//                        $db3->unlockHocSinh($hsID, $lmID);
//                        $db3->truTienHocSinh($hsID, $need_tien, "Trừ " . formatPrice($need_tien) . " tiền mở khóa trắc ngiệm lần $num", "trac_nghiem_unlock_$lmID", $num);
//                    }
//                    header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/");
//                    exit();
//                } else {
//                    $error = true;
//                    $error_msg = "Tài khoản của bạn không đủ tiền: ".formatPrice($tien);
//                }
//            }
//            if($tien < 20000) {
//                $error = true;
//                $error_msg .= "+ Bạn cần phải có <strong>ít nhất 20.000đ</strong> để làm bài KIỂM TRA ở nhà! Hiện bạn chỉ có <strong>".formatPrice($tien)."</strong>";
//            }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/trang-chu/" class="form-horizontal" method="post">
                    <!-- /main form -->
                        <div class="col-lg-12">
                            <div class="panel-heading">
                                <h5 class="panel-title">Nhắc nhở</h5>
                            </div>
                            <div class='panel panel-flat'>
<!--                                --><?php //if($error_msg != "") { ?>
<!--                                    <div class="panel-body">-->
<!--                                        <div class="alert alert-danger no-border" style="margin-bottom: 0;">-->
<!--                                            <span class="text-semibold">-->
<!--                                                --><?php //echo"<p>$error_msg</p>"; ?>
<!--                                            </span>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                --><?php //} ?>
                                <table class='table table-bordered noSwipe'>
                                    <tbody>
                                        <?php
                                        $now = date("Y-m-d");
                                        $result = $db->getNhomDeOpenType($hsID, "kiem-tra", $lmID);
                                        while($data = $result->fetch_assoc()) {
                                            $ngay = $db->getNgayFromBuoi($data["object"], $monID);
                                            if(date_create($now) < date_create($ngay) || date_create($date_in) > date_create($ngay)) {
                                                continue;
                                            }

                                            $deID = $db->checkHocSinhDoneLam($hsID, $data["ID_N"]);
                                            $deID_encode = encodeData($deID, $me);

                                            if ($deID != 0) {
//                                                $result2 = $db4->getKetQuaLuyenDeByDe($deID, $hsID);
//                                                $data2 = $result2->fetch_assoc();
//                                                $diem = $data2["diem"];
//
//                                                $third_td = "<td>" . $diem . "đ</td>";
                                                $third_td = "<td>Đã có điểm, bạn hãy vào xem bài SCAN</td>";
                                            } else {
                                                if ((isset($data["ID_STT"]) && is_numeric($data["ID_STT"])) || $data["public"] == 1) {
                                                    $third_td = "<td>Bạn chưa làm bài kiểm tra này!!!</td>";
                                                } else {
                                                    $check = $db->checkHocSinhLamKiemTra($hsID, $data["object"], $lmID);
                                                    if ($check == "none") {
                                                        $third_td = "<td>Click vào để xem hướng dẫn làm bài</td>";
                                                    } else if ($check == "normal") {
                                                        $third_td = "<td>Kết quả bài làm sẽ sớm được cập nhật</td>";
                                                    } else if ($check == "huy") {
                                                        $third_td = "<td>Bạn đã bị hủy bài</td>";
                                                    } else {
                                                        $third_td = "<td>Bị lỗi</td>";
                                                    }
                                                }
                                                $deID_encode = encodeData($data["ID_DE"], $me);
                                            }
                                            echo"<tr style='cursor: pointer;' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/tong-quan-de/".$deID_encode."/'\">
                                                <td style='width: 15%;min-width: 100px;' class='text-center'>" . formatDateUp($ngay) . "</td>";
                                                echo $third_td;
                                            echo "</tr>";
                                        }
                                        $result = $db->getNhomDeOpenType($hsID, "thi-thu", $lmID);
                                        while($data = $result->fetch_assoc()) {

                                            $deID = $db->checkHocSinhDoneLam($hsID, $data["ID_N"]);
                                            $deID_encode = encodeData($deID, $me);

                                            if ($deID != 0) {
                                                continue;
                                            } else {
                                                $third_td = "<td>Bạn chưa làm đề thi này!!!</td>";
                                                $deID_encode = encodeData($data["ID_DE"], $me);
                                            }
                                            echo"<tr style='cursor: pointer;' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/tong-quan-de/".$deID_encode."/'\">
                                                <td style='width: 15%;min-width: 100px;' class='text-center'>$data[mota]</td>";
                                            echo $third_td;
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </form>
            </div>
        </div>

        </div>
        <!-- /page content -->

        <?php include_once "include/bottom_hoc_sinh.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
//                $("body").addClass("sidebar-xs");
//                $(".lam-lai").click(function () {
//                    if(confirm("Kết quả làm bài cũ sẽ bị xóa! Bạn sẽ được ghi nhận là chưa làm bài trắc nghiệm này! Bạn có chắc chắn?")) {
//                        window.location.href="http://localhost/www/TDUONG/luyenthi/xoa-ket-qua-thi/" + $(this).attr("data-need") + "/";
//                    } else {
//                        return false;
//                    }
//                });
                $("#submit-unlock").click(function () {
                    if(confirm("Bạn sẽ bị trừ tiền trong tài khoản! Bạn có chắc chắn?")) {
                        return true;
                    } else {
                        return false;
                    }
                });
//                if($(window).width() > 770) {
//                    $("#tooltip-menu").hide();
//                }
//                $("#vao-thi").click(function () {
//                    if($("#submit-code").val() != "") {
//                        return true;
//                    } else {
//                        $("#show-error").fadeOut("fast").html("<span class='text-semibold'>Thông báo: </span>Xin vui lòng nhập mã đề thi!").fadeIn("fast");
//                        return false;
//                    }
//                });
//                $("a.made-code").click(function() {
//                    $("#submit-code").val($(this).attr("data-ma"));
//                    $("#vao-thi").closest("div").find("strong").remove();
//                    $("#vao-thi").closest("div").prepend("<strong>Click để làm bài</strong>");
//                    $("html,body").animate({scrollTop:0},250);
//                });
                //document.oncontextmenu = new Function("return false");
            });
        </script>
