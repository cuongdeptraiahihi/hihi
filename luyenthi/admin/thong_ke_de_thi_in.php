
    <?php include_once "../include/top.php"; ?>
    <?php
        if(isset($_GET["page"]) && isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
            $page = $_GET["page"];
            $nhom = $_GET["nhom"];
        } else {
            $page = "";
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
        $monID = (new Mon_Hoc())->getMonOfLop($lmID);

        $result1 = $db->getDeThiById($db->getDeThiMainByNhom($nhom));
        $data1 = $result1->fetch_assoc();
        if($data0["type"] == "kiem-tra") {
            $ngay = $db->getNgayFromBuoi($data0["object"], $monID);
        } else {
            $ngay = "none";
        }
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Kết quả --><?php //echo $data1["mota"]; ?><!--</h4>-->
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
                <form class="form-horizontal">
                    <div class="col-lg-12">
                        <?php if($page=="out") { ?>
                        <div class="panel-heading">
                            <h5 class="panel-title">Kết quả <?php echo $data1["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-sm-2">Chia khoảng</label>
                                        <div class="col-sm-2">
                                            <select class="form-control" id="submit-khoang">
                                                <option value="0.25" <?php if($khoang==0.25) {echo"selected='selected'";} ?>>0.25</option>
                                                <option value="0.5" <?php if($khoang==0.5) {echo"selected='selected'";} ?>>0.5</option>
                                                <option value="1" <?php if($khoang==1) {echo"selected='selected'";} ?>>1</option>
                                            </select>
<!--                                            <input type="text" id="submit-khoang" class="form-control" placeholder="0.25, 0.5, 5,.." value="--><?php //echo $khoang; ?><!--" />-->
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="table-responsive">
                                <table class="table" id="bieu-do-thong-ke">
                                    <tr>
                                        <td><div id='chart-container' style='width: 100%;height:300px;min-width: 1200px;'></div></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="panel panel-flat table-responsive">
                            <?php if($page=="out") { ?>
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sm-2">
                                            <input type="number" id="submit-a" class="form-control" placeholder="Điểm bắt đầu" />
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="number" id="submit-b" class="form-control" placeholder="Điểm kết thúc" />
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" id="submit-loc" class="btn btn-primary btn-sm bg-blue-400">Lọc</button>
                                            <button type="button" id="submit-none" style="display: none;" class="btn btn-primary btn-sm bg-danger-400">X</button>
                                        </div>
                                        <?php if($page == "out") { ?>
                                        <div class="col-sm-6" style="text-align: right;">
                                            <button type="button" id="submit-xuat" class="btn btn-primary btn-sm bg-brown-400"><i class="icon-file-excel"></i> Xuất Excel</button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </fieldset>
                            </div>
                            <?php } ?>
                            <table class="table" id="list-danh-sach">
                                <thead>
                                <tr>
                                    <th class='text-center'>STT</th>
                                    <th class='text-center'>Mã số</th>
                                    <th class='text-center'>Họ tên</th>
                                    <th class='text-center'>Điểm</th>
                                    <th class='text-center'>Thời gian làm</th>
                                    <th class='text-center'>Vào thi - Nộp bài</th>
                                    <th class='text-center'>Số lần làm lại</th>
                                    <th class='text-center'></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $diem_arr = array();
                                    $dem = 1;
                                    $db2 = new Luyen_De();
                                    switch ($page) {
                                        case "in": {
                                                $result = $db2->getListHocSinhDangLam($nhom);
                                                while ($data = $result->fetch_assoc()) {
                                                    echo"<tr>
                                                            <td class='text-center' style='width: 10%;'>" . $dem . "</td>
                                                            <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                                            <td class='text-center'><a href='" . formatFacebook($data["facebook"]) . "' target='_blank'>$data[fullname]</a></td>
                                                            <td class='text-center'></td>
                                                            <td class='text-center'></td>
                                                            <td class='text-center'>" . formatDateTime($data["in_time"]) . "</td>
                                                            <td class='text-center'></td>
                                                            <td class='text-center'></td>
                                                        </tr>";
                                                    $dem++;
                                                }
                                            }
                                            break;
                                        case "out": {
                                                $result = $db2->getKetQuaLuyenDe($nhom);
                                                while ($data = $result->fetch_assoc()) {
                                                    echo "<tr class='tr-dulieu'>
                                                        <td class='text-center' style='width: 10%;'>" . $dem . "</td>
                                                        <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                                        <td class='text-center'><a href='" . formatFacebook($data["facebook"]) . "' target='_blank'>$data[fullname]</a></td>
                                                        <td class='text-center td-diem'>$data[diem]</td>
                                                        <td class='text-center'>" . formatTimeBack($data["time"] / 1000) . "</td>";
                                                        if(isset($data["in_time"]) && isset($data["out_time"])) {
                                                            echo "<td class='text-center'>" . formatDateTime($data["in_time"]) . " - " . formatDateTime($data["out_time"]) . "</td>";
                                                        } else {
                                                            echo "<td class='text-center'></td>";
                                                        }
                                                        if($data0["type"] != "kiem-tra") {
                                                            echo"<td class='text-center'><a href='javascript:void(0)' class='xem-lam' data-hsID='$data[ID_HS]'>" . $db2->countSoLanLam($data["ID_HS"],$nhom) . "</a></td>";
                                                        } else {
                                                            echo"<td class='text-center'></td>";
                                                        }
                                                        echo"<td class='text-center'>
                                                            <ul class='icons-list'>
                                                                <li><a href='http://localhost/www/TDUONG/luyenthi/admin/ket-qua-nhap-diem-hs/$data[ID_DE]/$data[ID_HS]/' target='_blank'><i class='icon-copy'></i></a></li>
                                                                <li> | </li>
                                                                <li><a href='http://localhost/www/TDUONG/luyenthi/admin/ket-qua-lam-de-hs/$data[ID_DE]/$data[ID_HS]/' target='_blank'><i class='icon-eye'></i></a></li>
                                                                <li> | </li>
                                                                <li><a href='javascript:void(0)' class='giahan' data-toggle='modal' data-target='#modal_default_giahan' data-deID='$data[ID_DE]' data-hsID='$data[ID_HS]'><i class='icon-esc'></i></a></li>
                                                                <li> | </li>
                                                                <li><a href='javascript:void(0)' class='delete-ketqua' data-deID='$data[ID_DE]' data-hsID='$data[ID_HS]'><i class='icon-trash'></i></a></li>
                                                            </ul>
                                                        </td>
                                                    </tr>";
                                                    $dem++;
                                                    $temp = khoangNumber($data["diem"], $khoang, 0, 10);
                                                    if(!isset($diem_arr[0]["$temp"])) {
                                                        $diem_arr[0]["$temp"] = 1;
                                                    } else {
                                                        $diem_arr[0]["$temp"]++;
                                                    }
                                                }
                                            }
                                            break;
                                        case "none": {
                                                $result = $db2->getListHocSinhChuaLam($nhom, $data1["loai"], $data0["ID_LM"]);
                                                while ($data = $result->fetch_assoc()) {
                                                    echo"<tr>
                                                        <td class='text-center' style='width: 10%;'>" . $dem . "</td>
                                                        <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                                        <td class='text-center'><a href='" . formatFacebook($data["facebook"]) . "' target='_blank'>$data[fullname]</a></td>
                                                        <td colspan='5' class='text-center'>";
                                                            if($data0["type"]=="kiem-tra") {
                                                                if(isset($data["ID_STT"]) && is_numeric($data["ID_STT"])) {
                                                                    echo "<button type='button' class='btn btn-primary btn-sm bg-brown-400 lock' data-hsID='$data[ID_HS]' data-sttID='$data[ID_STT]'>Hủy mở khóa</button>";
                                                                } else {
                                                                    echo "<button type='button' class='btn btn-primary btn-sm bg-blue-400 unlock' data-hsID='$data[ID_HS]'>Mở khóa</button>";
                                                                }
                                                            }
                                                        echo"</td>
                                                    </tr>";
                                                    $dem++;
                                                }
                                            }
                                            break;
                                        default:
                                            break;
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

        <div id='modal_default_giahan' class='modal fade'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-body'>
                        <h6 class='text-semibold'>Gia hạn thời gian làm</h6>
                        <p><input type="text" id="add-time" class="form-control" value="30" placeholder="Thời gian tăng thêm (đơn vị phút)" /></p>
                        <p>Nhập số phút tăng thêm (20, 30,..)</p>
                    </div>

                    <div class='modal-footer'>
                        <button type='button' class='btn btn-link' data-dismiss='modal'>Đóng</button>
                        <button type='button' class='btn btn-danger' data-dismiss='modal' id='add-ok'>Xác nhận</button>
                    </div>
                </div>
            </div>
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
                            labelFontSize: 12
                        },
                        axisX: {
                            interval: 1,
                            labelAngle: 90,
                            tickThickness: 1,
                            labelFontSize: 12
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
                $(".giahan").click(function () {
                    var deID = $(this).attr("data-deID");
                    var hsID = $(this).attr("data-hsID");
                    if($.isNumeric(deID) && $.isNumeric(hsID)) {
                        $("#add-time").val("30");
                        $("#add-ok").attr("data-deID", deID).attr("data-hsID", hsID);
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("#add-ok").click(function () {
                    var deID = $(this).attr("data-deID");
                    var hsID = $(this).attr("data-hsID");
                    var phut = $("#add-time").val().trim();
                    if($.isNumeric(deID) && $.isNumeric(hsID) && $.isNumeric(phut)) {
                        $.ajax({
                            async: true,
                            data: "deID_more=" + deID + "&hsID_more=" + hsID + "&phut=" + phut,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function(result) {
                                result = result.trim();
                                console.log(result);
                                if(result == "ok") {
                                    new PNotify({
                                        title: 'Gia hạn',
                                        text: 'Đã gia hạn thêm ' + phut + ' phút!',
                                        icon: 'icon-checkmark'
                                    });
                                } else {
                                    alert("Không thể gia hạn! " + result);
                                }
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("#bieu-do-thong-ke").closest("div").scrollLeft(1000);
                $("#submit-xuat").click(function() {
                    new PNotify({
                        title: 'Xuất excel',
                        text: 'Đang lấy dữ liệu...',
                        icon: 'icon-rotate-cw3'
                    });
                    var ajax_data = "[";
                    $("table#list-danh-sach tr.tr-dulieu").each(function(index, element) {
                        var stt = $(element).find("td:eq(0)").text();
                        var maso = $(element).find("td:eq(1)").text();
                        var name = $(element).find("td:eq(2)").text();
                        var diem = $(element).find("td:eq(3)").text();
                        var time = $(element).find("td:eq(4)").text();
                        ajax_data += '{"stt":"'+stt+'","maso":"'+maso+'","name":"'+name+'","diem":"'+diem+'","time":"'+time+'"},';
                    });
                    ajax_data = ajax_data.replace(/,*$/,"");
                    ajax_data += "]";
                    if(ajax_data != "[]") {
                        new PNotify({
                            title: 'Xuất excel',
                            text: 'Đang xuất...',
                            icon: 'icon-rotate-cw3'
                        });
                        $.ajax({
                            async: true,
                            data: "to_excel=" + ajax_data + "&title=<?php echo $data1["mota"]; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function(result) {
                                new PNotify({
                                    title: 'Xuất excel',
                                    text: 'Thành công!',
                                    icon: 'icon-checkmark'
                                });
                                window.open("http://localhost/www/TDUONG/luyenthi/admin/toexcel/","_blank")
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("select#submit-khoang").change(function() {
                    var value = $(this).find("option:selected").val();
                    if($.isNumeric(value) && value!=0) {
                        window.location.href = "http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/<?php echo $nhom; ?>/" + value + "/";
                    }
                });

                $("table#list-danh-sach").delegate("tr td button.unlock","click",function () {
                    var me = $(this);
                    var hsID = $(this).attr("data-hsID");
                    if(valid_id(hsID)) {
                        $.ajax({
                            async: false,
                            data: "hsID4=" + hsID + "&nID4=<?php echo $nhom; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                me.attr("data-sttID",result).removeAttr("data-hsID");
                                me.removeClass("bg-blue-400").removeClass("unlock").addClass("bg-brown-400").addClass("lock");
                                me.html("Hủy mở khóa");
                                new PNotify({
                                    title: 'Học sinh đặc biệt',
                                    text: 'Đã mở khóa!',
                                    icon: 'icon-checkmark'
                                });
                            }
                        });
                    }
                });

                $("table#list-danh-sach").delegate("tr td button.lock","click",function () {
                    var me = $(this);
                    var sttID = $(this).attr("data-sttID");
                    if(valid_id(sttID)) {
                        $.ajax({
                            async: false,
                            data: "sttID=" + sttID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                me.removeClass("bg-brown-400").removeClass("lock").addClass("bg-blue-400").addClass("unlock");
                                me.html("Mở khóa");
                                new PNotify({
                                    title: 'Học sinh đặc biệt',
                                    text: 'Đã hủy mở khóa!',
                                    icon: 'icon-checkmark'
                                });
                            }
                        });
                    }
                });

//                $("input#submit-khoang").typeWatch({
//                    captureLength: 1,
//                    callback: function (value) {
//                        if($.isNumeric(value) && value!=0) {
//                            window.location.href = "http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/<?php //echo $page; ?>///<?php //echo $nhom; ?>///" + value + "/";
//                        }
//                    }
//                });

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

                $("#submit-loc").click(function() {
                    var a = $("#submit-a").val();
                    var b = $("#submit-b").val();
                    if($.isNumeric(a) && $.isNumeric(b)) {
                        var stt = 1;
                        $("table#list-danh-sach tr").each(function(index, element) {
                            var me = $(element).find("td.td-diem").text();
                            if(me >= a && me <= b) {
                                $(element).find("td:first-child").html(stt);
                                stt++;
                            } else if(index > 0) {
                                $(element).removeClass("tr-dulieu").hide();
                            }
                        });
                        $("#submit-none").fadeIn("fast");
                    }
                });

                $("#submit-none").click(function() {
                    var stt = 1;
                    $("table#list-danh-sach tr").each(function(index, element) {
                        if(index > 0) {
                            $(element).addClass("tr-dulieu").show();
                            $(element).find("td:first-child").html(stt);
                        }
                        stt++;
                    });
                    $(this).fadeOut("fast");
                    $("#submit-a").val("");
                    $("#submit-b").val("");
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
            });
        </script>
