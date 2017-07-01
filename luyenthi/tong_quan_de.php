
    <?php include_once "include/top_hoc_sinh.php"; ?>
    <?php
        $me = md5("123456");
        if(isset($_GET["deID"])) {
            $deID = decodeData($_GET["deID"], $me);
        } else {
            $deID = 0;
        }
        $db = new De_Thi();
        $deID_big = $deID;

        $result1 = $db->getDeThiById($deID);
        $data1 = $result1->fetch_assoc();

        $result0 = $db->getNhomDeById($data1["nhom"]);
        $data0 = $result0->fetch_assoc();

        $khoang = 1;
        $db2 = new Luyen_De();
        $db3 = new Hoc_Sinh();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - --><?php //echo $data1["mota"]; ?><!--</h4>-->
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
                <form class="form-horizontal">
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title"><?php echo str_replace("ngày","<br />Ngày",$data1["mota"]); ?></h5>
                        </div>
                        <div class="panel panel-flat" id="my-dock">
                            <div class="panel-body">
                                <fieldset class="content-group" style="margin-bottom: 0 !important;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <?php
                                            $btn = "";
                                            $deID = $db->checkHocSinhDoneLam($hsID, $data1["nhom"]);
                                            $deID_encode = encodeData($deID, $me);
                                            if($data0["type"] == "kiem-tra") {
                                                if ($deID != 0) {
                                                    if ($data0["public"] == 1) {
                                                        $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-primary-400' style='color:#FFF;'>ĐÁP ÁN</a> ";
                                                        $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/ket-qua-nhap-diem/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-slate-400' style='color:#FFF;'>BÀI SCAN</a> ";
                                                        $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/xoa-ket-qua-thi/" . encodeData($hsID, $me) . "/" . $deID . "/' class='btn btn-primary btn-xs bg-brown-400 lam-lai' style='color:#FFF;'>LÀM LẠI</a> ";
                                                    } else {
                                                        $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/ket-qua-nhap-diem/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-slate-400' style='color:#FFF;'>BÀI SCAN</a> ";
                                                        $btn .= " <a href='javascript:void(0)' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>ĐỀ ĐANG KHÓA, SẼ ĐƯỢC MỞ KHI CÓ THÔNG BÁO TRÊN GROUP FB</a> ";
                                                    }
                                                } else if ($db3->checkHocSinhSpecial($hsID, $data1["nhom"]) || $data0["public"] == 1) {
                                                    $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($deID_big, $me) . "/' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>VÀO THI</a> ";
                                                } else {
                                                    $check = $db->checkHocSinhLamKiemTra($hsID, $data0["object"], $lmID);
                                                    if ($check == "none") {
                                                        if($data0["public"] == 1) {
                                                            $btn .= " <a href='javascript:void(0)' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>BẠN HÃY XIN NGHỈ PHÉP TẠI BGO ĐỂ LÀM BÀI</a> ";
                                                            $btn .= "<p class='content-group text-left' style='margin-top: 20px;'>
                                                                + Truy cập trang <strong>www.bgo.edu.vn</strong><br />
                                                                + Truy cập mục <strong>Đổi ca</strong>, chọn <strong>Xin nghỉ có phép</strong> (buổi kiểm tra)<br />
                                                                + Làm theo các bước và đề thi sẽ <strong>tự động mở</strong><br />
                                                                + <strong>Quay lại đây</strong> để làm bài!<br />
                                                            </p>";
                                                        } else {
                                                            $btn .= " <a href='javascript:void(0)' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>ĐỀ ĐANG KHÓA</a> ";
                                                        }
                                                    } else if ($check == "normal") {
                                                        $btn .= " <a href='javascript:void(0)' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>KẾT QUẢ SẼ SỚM ĐƯỢC CẬP NHẬT</a> ";
                                                    } else {
                                                        if ($data0["public"] == 1) {
                                                            $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/" . encodeData($deID_big, $me) . "/' class='btn btn-primary btn-xs bg-primary-400' style='color:#FFF;'>ĐÁP ÁN</a> ";
                                                        }
                                                        $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/ket-qua-nhap-diem/" . encodeData($deID_big, $me) . "/' class='btn btn-primary btn-xs bg-slate-400' style='color:#FFF;'>BÀI SCAN</a> ";
                                                        $btn .= " <a href='javascript:void(0)' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>BỊ HỦY BÀI</a> ";
                                                    }
                                                }
                                            } else {
                                                if($deID != 0) {
                                                    $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/" . $deID_encode . "/' class='btn btn-primary btn-xs bg-primary-400' style='color:#FFF;'>ĐÁP ÁN</a> ";
                                                    $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/xoa-ket-qua-thi/" . encodeData($hsID, $me) . "/" . $deID . "/' class='btn btn-primary btn-xs bg-brown-400 lam-lai' style='color:#FFF;'>LÀM LẠI</a> ";
                                                } else {
                                                    if ($data0["public"] == 1) {
                                                        $btn .= " <a href='http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($deID_big, $me) . "/' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>VÀO THI</a> ";
                                                    } else {
                                                        $btn .= " <a href='javascript:void(0)' class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;'>ĐANG KHÓA</a> ";
                                                    }
                                                }
                                            }
                                            echo $btn;
                                        ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
<!--                        <div class="panel-heading">-->
<!--                            <h5 class="panel-title">Biểu đồ phổ điểm</h5>-->
<!--                        </div>-->
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div class="table-responsive noSwipe" id="table-chart">
                                    <div id='chart-container' style='width: 100%;height:250px;'></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Kết quả làm bài</h5>
                        </div>
                        <div class="panel panel-flat table-responsive noSwipe" style="margin-bottom: 40px;">
                            <table class="table list-danh-sach table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style='width: 10%;' class="text-center small-hidden">STT</th>
                                        <th style='width: 40%;' class="text-center">Thời điểm làm</th>
                                        <th class="text-center">Điểm</th>
                                        <th style='width: 20%;' class="text-center small-hidden">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
										$diem = "";
										$color = "#69b42e";
                                        $dem = 1;
                                        $result = $db2->getKetQuaLuyenDeByDe($deID, $hsID);
                                        if($result->num_rows != 0) {
                                            $data = $result->fetch_assoc();
                                            $diem = $data["diem"];
                                            if ($diem <= 5.25) {
                                                $color = "#EF5350";
                                            } else if($diem >= 8) {
                                                $color = "#78909C";
                                            } else {
                                                $color = "#8D6E63";
                                            }
                                            echo "<tr>
                                                <td class='text-center small-hidden'>$dem</td>
                                                <td class='text-center'>" . formatDateTime($data["datetime"]) . "</td>
                                                <td class='text-center'>$data[diem]</td>
                                                <td class='text-center small-hidden'>" . formatTimeBack($data["time"] / 1000) . "</td>
                                            </tr>";
                                            $dem++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Lịch sử làm lại</h5>
                        </div>
                        <div class="panel panel-flat table-responsive noSwipe" style="margin-bottom: 40px;">
                            <table class="table list-danh-sach table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style='width: 10%;' class="text-center small-hidden">STT</th>
                                        <th style='width: 40%;' class="text-center">Thời điểm làm</th>
                                        <th class="text-center">Điểm</th>
                                        <th style='width: 20%;' class="text-center small-hidden">Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $result = $db2->getLamLaiByHocSinh($hsID, $data1["nhom"]);
                                    if($result->num_rows != 0) {
                                        while ($data = $result->fetch_assoc()) {
                                            if($dem == 1) {
                                                $diem = $data["diem"];
                                                if ($diem <= 5.25) {
                                                    $color = "#EF5350";
                                                } else if($diem >= 8) {
                                                    $color = "#78909C";
                                                } else {
                                                    $color = "#8D6E63";
                                                }
                                            }
                                            echo "<tr>
                                                <td class='text-center small-hidden'>$dem</td>
                                                <td class='text-center'>" . formatDateTime($data["datetime"]) . "</td>
                                                <td class='text-center'>$data[diem]</td>
                                                <td class='text-center small-hidden'>" . formatTimeBack($data["time"] / 1000) . "</td>
                                            </tr>";
                                            $dem++;
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                            $diem_arr = array();
                            $dem = 1;
                            $i_cur = 0;
                            $result = $db2->getKetQuaLuyenDe($data1["nhom"]);
                            while ($data = $result->fetch_assoc()) {
                                $dem++;
                                $temp = khoangNumber($data["diem"], $khoang, 0, 10);
                                if(!isset($diem_arr[0]["$temp"])) {
                                    $diem_arr[0]["$temp"] = 1;
                                } else {
                                    $diem_arr[0]["$temp"]++;
                                }
                                if($data["ID_HS"] == $hsID) {
                                    $i_cur = $temp;
                                }
                            }
                        ?>
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
            window.onload = function () {
                var default_w = 22;
                if($(window).width() <= 769) {
                    default_w = 15;
                }
                var chart = new CanvasJS.Chart("chart-container",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: true,
                            enabled: true
                        },
                        backgroundColor: "",
                        legend:{
                            fontFamily: "helvetica",
                            horizontalAlign: "center",
                            fontSize: 14,
                            enabled: false
                        },
                        dataPointWidth: default_w,
                        axisY: {
                            gridThickness: 1,
                            gridColor: "#ddd",
                            tickThickness: 0,
                            labelFontSize: 12
                        },
                        axisX: {
                            interval: 1,
                            labelAngle: 90,
                            tickThickness: 1,
                            tickColor: "#ddd",
                            labelFontSize: 12
                        },
                        data: [{
                            type: "column",
                            startAngle: -90,
                            showInLegend: false,
                            legendText: "{name}",
                            indexLabelFontFamily:"helvetica",
                            indexLabelFontColor: "#78909C",
                            indexLabelFontSize: 12,
                            indexLabelPlacement: "outside",
                            indexLabelFontWeight: "bold",
                            toolTipContent: "Điểm {label}: {y} hs",
                            dataPoints: [
                                <?php
                                    for($i = 0; $i <= 10; $i += $khoang) {
                                        if(isset($diem_arr[0]["$i"])) {
                                            if($i_cur == $i) {
                                                echo "{ y: " . $diem_arr[0]["$i"] . ", label : '$i', indexLabel : '$diem', color: '$color'},";
                                            } else {
                                                echo "{ y: " . $diem_arr[0]["$i"] . ", label : '$i', indexLabel : '', color: '#29B6F6'},";
                                            }
                                        } else {
                                            echo "{ y: 0, label : '$i', indexLabel : '', color: '#29B6F6'},";
                                        }
                                    }
                                ?>
                            ]
                        }]
                    });
                chart.render();
            }
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
//                $("body").addClass("sidebar-xs");
                $("#table-chart").scrollLeft(250);
                $("#chart-container").css("height","200px");
                $(".lam-lai").click(function () {
                    if(confirm("Kết quả làm bài cũ sẽ bị xóa! Bạn sẽ được ghi nhận là chưa làm bài trắc nghiệm này! Bạn có chắc chắn?")) {

                    } else {
                        return false;
                    }
                });
            });
        </script>
