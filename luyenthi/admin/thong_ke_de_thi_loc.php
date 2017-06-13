
    <?php include_once "../include/top.php"; ?>
    <?php
        if(isset($_GET["number"]) && isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
            $number = $_GET["number"];
            $nhom = $_GET["nhom"];
        } else {
            $number = -1;
            $nhom = 0;
        }
        if(isset($_GET["khoang"]) && is_numeric($_GET["khoang"])) {
            $khoang = $_GET["khoang"];
        } else {
            $khoang = 0.25;
        }
        $db = new De_Thi();
        $result0 = $db->getNhomDeById($nhom);
        $data0 = $result0->fetch_assoc();
        if($data0["type"] == "kiem-tra") {
            $ngay = $db->getNgayFromBuoi($data0["object"], (new Mon_Hoc())->getMonOfLop($data0["ID_LM"]));
            $show = "Kiểm tra ngày ".formatDateUp($ngay);
        } else {
            $ngay = "none";
            $result1 = $db->getDeThiById($db->getDeThiMainByNhom($nhom));
            $data1 = $result1->fetch_assoc();
            $show = $data1["mota"];
        }
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Kết quả làm nhóm đề --><?php //echo $show; ?><!--</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "../include/sidebar.php"; ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Main form -->
                <form>
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Kết quả làm nhóm đề <?php echo $show; ?></h5>
                        </div>
                        <div class="panel panel-flat">

                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Chia khoảng</label>
                                        <div class="col-sm-2">
                                            <input type="text" id="submit-khoang" class="form-control" placeholder="0.25, 0.5, 5,.." value="<?php echo $khoang; ?>" />
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <table class="table" id="bieu-do-thong-ke">
                                <tr>
                                    <td><div id='chart-container' style='width: 100%;height:300px;'></div></td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table datatable-basic datatable-show-all table-striped" id="list-danh-sach">
                                <thead>
                                <tr>
                                    <th class='text-center'>STT</th>
                                    <th class='text-center'>Mã số</th>
                                    <th class='text-center'>Họ tên</th>
                                    <th class='text-center'>Điểm</th>
                                    <th class='text-center'>Thời gian làm</th>
                                    <th class='text-center'>Số lần làm lại</th>
                                    <th class='text-center'></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $diem_arr = array();
                                    $dem = 1;
                                    $db = new Luyen_De();
                                    $result = $db->getKetQuaLuyenDe($nhom);
                                    while ($data = $result->fetch_assoc()) {
                                        $temp = khoangNumber($data["diem"], $khoang, 0, 10);
                                        if($number == $temp) {
                                            echo "<tr>
                                                <td class='text-center' style='width: 10%;'>" . $dem . "</td>
                                                <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                                <td class='text-center'><a href='" . formatFacebook($data["facebook"]) . "' target='_blank'>$data[fullname]</a></td>
                                                <td class='text-center'>$data[diem]</td>
                                                <td class='text-center'>" . formatTimeBack($data["time"] / 1000) . "</td>";
                                                if($data0["type"] != "kiem-tra") {
                                                    echo"<td class='text-center'><a href='javascript:void(0)' class='xem-lam' data-hsID='$data[ID_HS]'>" . $db->countSoLanLam($data["ID_HS"],$nhom) . "</a></td>";
                                                } else {
                                                    echo"<td class='text-center'></td>";
                                                }
                                                echo"<td class='text-center'>
                                                    <ul class='icons-list'>
                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/ket-qua-nhap-diem-hs/$data[ID_DE]/$data[ID_HS]/' target='_blank'><i class='icon-copy'></i></a></li>
                                                        <li> | </li>
                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/ket-qua-lam-de-hs/$data[ID_DE]/$data[ID_HS]/' target='_blank'><i class='icon-eye'></i></a></li>
                                                        <li> | </li>
                                                        <li><a href='javascript:void(0)' class='delete-ketqua' target='_blank' data-deID='$data[ID_DE]' data-hsID='$data[ID_HS]'><i class='icon-trash'></i></a></li>
                                                    </ul>
                                                </td>
                                            </tr>";
                                            $dem++;
                                        }
                                        if(!isset($diem_arr[0]["$temp"])) {
                                            $diem_arr[0]["$temp"] = 1;
                                        } else {
                                            $diem_arr[0]["$temp"]++;
                                        }
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

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            window.onload = function () {
                var chart = new CanvasJS.Chart("chart-container",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: true
                        },
                        backgroundColor: "",
                        legend:{
                            fontFamily: "helvetica",
                            horizontalAlign: "center",
                            fontSize: 14,
                            enabled: false,
                        },
                        dataPointWidth: 22,
                        axisY: {
                            gridThickness: 1,
                            tickThickness: 0,
                        },
                        axisX: {
                            interval: 1,
                            labelAngle: 90,
                            tickThickness: 1,
                        },
                        data: [{
                            type: "column",
                            startAngle: -90,
                            showInLegend: false,
                            legendText: "{name}",
                            indexLabelFontFamily:"helvetica",
                            indexLabelFontColor: "#FFF",
                            indexLabelFontSize: 0,
                            indexLabelPlacement: "outside",
                            indexLabelFontWeight: "normal",
                            toolTipContent: "Điểm {label}: {y} em",
                            click: function(e) {
                                window.location.href = e.dataPoint.content;
                            },
                            dataPoints: [
                                <?php
                                    for($i = 0; $i <= 10; $i += $khoang) {
                                        if(isset($diem_arr[0]["$i"])) {
                                            echo "{ y: " . $diem_arr[0]["$i"] . ", label : '$i', indexLabel : '', content: 'http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/loc/$nhom/$i/$khoang/', color: '#29B6F6'},";
                                        } else {
                                            echo "{ y: 0, label : '$i', indexLabel : '', content: 'http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/loc/$nhom/$i/$khoang/', color: '#29B6F6'},";
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

                $("input#submit-khoang").typeWatch({
                    captureLength: 1,
                    callback: function (value) {
                        if($.isNumeric(value) && value!=0) {
                            window.location.href = "http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/loc/<?php echo $nhom; ?>/<?php echo $number; ?>/" + value + "/";
                        }
                    }
                });

                $("a.xem-lam").click(function() {
                    var me = $(this);
                    var hsID = $(this).attr("data-hsID");
                    if(valid_id(hsID)) {
                        $.ajax({
                            async: false,
                            data: "hsID=" + hsID + "&nhom=<?php echo $nhom; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                $("table#list-danh-sach tr").removeAttr("style");
                                me.closest("tr").css("background-color","#ffffb2");
                                $("table#list-danh-sach tr.view-lam-lai").remove();
                                $(result).insertAfter(me.closest("tr"));
                            }
                        });
                    }
                });

                $("a.delete-ketqua").click(function() {
                    var del_tr = $(this).closest("tr");
                    var deID = $(this).attr("data-deID");
                    var hsID = $(this).attr("data-hsID");
                    if(confirm("Bạn có chắc chắn xóa kết quả học sinh này không?")) {
                        if (valid_id(deID) && valid_id(hsID)) {
                            $.ajax({
                                async: false,
                                data: "hsID_del=" + hsID + "&deID_del=" + deID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                                success: function (result) {
                                    del_tr.remove();
                                    new PNotify({
                                        title: 'Kết quả làm bài',
                                        text: 'Xóa thành công!',
                                        icon: 'icon-checkmark'
                                    });
                                }
                            });
                        }
                    }
                });
            });
        </script>
