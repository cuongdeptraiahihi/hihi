
    <?php include_once "../include/top.php"; ?>

    <?php
        if(isset($_GET["cID"]) && is_numeric($_GET["cID"])) {
            $cID = $_GET["cID"];
        } else {
            $cID = 0;
        }
        $db = new Cau_Hoi();
        $db2 = new Thong_Ke();
        $result = $db->getCauHoiById($cID);
        $data = $result->fetch_assoc();
        $mau_arr = array("#69b42e","#246E2C","red","blue","#000066","orange","#999999","#00a854");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Câu hỏi</span> - Thống kê câu hỏi --><?php //echo $data["maso"]; ?><!--</h4>-->
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
                <div class="col-lg-12">
                <?php
                    $da_arr = array();
                    $result3 = $db->getDapAnByMain($data["maso"]);
                    while($data3 = $result3->fetch_assoc()) {
                        $da_arr[$data3["ID_C"]][] = array(
                            "ID_DA" => $data3["ID_DA"],
                            "content" => $data3["content"],
                            "main" => $data3["main"],
                            "type" => $data3["type"]
                        );
                    }

                    $dad_arr = array();
                    $count = 0;
                    $m = count($mau_arr);
                    $result4 = $db->getCauHoiByMain($data["maso"]);
                    while($data4 = $result4->fetch_assoc()) {
                ?>
                    <div class="panel-heading">
                        <h5 class="panel-title">Thống kê <?php echo $data4["maso"]; ?></h5>
                    </div>
                    <div class="panel panel-flat table-responsive">
                        <table class="table" id="list-dap-an">
                            <tbody>
                            <?php
                                echo"<tr>
                                        <td colspan='2' class='text-center'>
                                            <button type='button' class='btn btn-primary btn-xs bg-primary-400' onclick=\"location . href = 'http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$data4[ID_C]/'\">Sửa</button> ";
                                        if($data4["done"] == 1) {
                                            echo"<button type='button' class='btn btn-primary btn-xs bg-slate-400 uncheck-cau-hoi' data-cID='$data4[ID_C]'>Un check</button> ";
                                        } else {
                                            echo"<button type='button' class='btn btn-primary btn-xs bg-slate-400 check-cau-hoi' data-cID='$data4[ID_C]'>Check</button> ";
                                        }
                                        echo"<button type='button' class='btn btn-primary btn-xs bg-danger-400 xoa-binh-luan' data-cID='$data4[ID_C]'>Xóa bình luận</button>
                                        </td>
                                    </tr>";
                                echo"<tr style='display: none;'>
                                    <td colspan='2'><div id='chart-container-$count' style='width: 100%;height:300px;'></div></td>
                                </tr>
                                <tr style='background-color: #D1DBBD;'>
                                    <td colspan='2' style='font-weight: 600;'>";
                                        if($data4["content"]!="none") {
                                            echo imageToImg($data4["ID_MON"],$data4["content"],250);
                                        }
                                        if($data4["anh"]!="none"){
                                            echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db->getUrlDe($data4["ID_MON"],$data4["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                        }
                                    echo"</td>
                                </tr>";
                                echo"<tr>
                                    <td colspan='2'>";
                                    if($data4["da_con"] != "none") {
                                        echo imageToImg($data4["ID_MON"],$data4["da_con"],250);
                                    }
                                    if($data4["da_anh"] != "none"){
                                        echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db->getUrlDe($data4["ID_MON"],$data4["da_anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                    }
                                    echo"</td>
                                </tr>";
                                $me_arr = array();
                                $dem = 1;
                                $n = count($da_arr[$data4["ID_C"]]);
                                for($i = 0; $i < $n; $i++) {
                                    if($da_arr[$data4["ID_C"]][$i]["main"] == 1) {
                                        echo"<tr style='background-color: #2196F3;color:#FFF;' class='view-who-click' data-daID='".$da_arr[$data4["ID_C"]][$i]["ID_DA"]."'>";
                                    } else {
                                        echo"<tr class='view-who-click' data-daID='".$da_arr[$data4["ID_C"]][$i]["ID_DA"]."'>";
                                    }
                                    echo"<td style='width: 10%;' class='text-center'>".$dem."</td>
                                    <td>";
                                    if($da_arr[$data4["ID_C"]][$i]["type"] == "text") {
                                        echo imageToImg($data4["ID_MON"],$da_arr[$data4["ID_C"]][$i]["content"],250);
                                    } else {
                                        echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($data4["ID_MON"],$da_arr[$data4["ID_C"]][$i]["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                                    }
                                    $dem++;
                                    echo"</td>
                                    </tr>";
                                }
    //                                $dad_arr[$count] = array(
    //                                    "total" => $total,
    //                                    "dem" => $dem,
    //                                    "me_arr" => $me_arr
    //                                );
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-heading">
                        <h5 class="panel-title">Bình luận</h5>
                    </div>
                    <div class="panel panel-flat">
                        <?php
                        echo"<table class='table'>
                            <thead>
                            <tr>
                                <th style='width: 10%;' class='text-center'>STT</th>
                                <th style='width: 10%;' class='text-center'>Mã số</th>
                                <th style='width: 20%;' class='text-center'>Họ và tên</th>
                                <th class='text-center'>Nội dung</th>
                                <th style='width: 15%;' class='text-center'></th>
                                <th style='width: 15%;' class='text-center'></th>
                            </tr>
                            </thead>
                            <tbody>";
                                $db3 = new Binh_Luan();
                                $result3 = $db3->getBinhLuanCau($data4["ID_C"],$data["ID_MON"],100);
                                $dem_bl = 1;
                                while($data3=$result3->fetch_assoc()) {
                                    echo"<tr>
                                        <td class='text-center'>$dem_bl</td>
                                        <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data3[cmt]/' target='_blank'>$data3[cmt]</a></td>
                                        <td class='text-center'><a href='" . formatFacebook($data3["facebook"]) . "' target='_blank'>$data3[fullname]</a></td>";
                                        if($data3["type"] == "image") {
                                            echo"<td><img src='http://localhost/www/TDUONG/luyenthi/upload/cauhoi/$data3[content]' style='max-width: 100%;max-height: 300px;' /></td>";
                                        } else {
                                            echo"<td>$data3[content]</td>";
                                        }
                                        echo"<td class='text-center'>".formatDateTime($data3["datetime"])."</td>
                                        <td class='text-center'><button type='button' class='btn btn-primary btn-xs bg-primary-400 the-mien-phat' data-hsID='$data3[ID_HS]'>Thẻ miễn phạt</button></td>
                                    </tr>";
                                    $dem_bl++;
                                }
                            echo"</tbody>
                        </table>";
                        ?>
                    </div>
                    <?php
                        $count++;
                    }
                    $count--;
                    ?>
                    <!-- /striped rows -->
                </div>
            </div>
            <!-- /main form -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

<!--<!--        <script type="text/javascript">-->
<!--<!--            window.onload = function () {-->
<!--<!--                --><?php ////for($j=0;$j<=$count;$j++) { ?>
<!--//                var chart--><?php ////echo $j; ?><!--// = new CanvasJS.Chart("chart-container---><?php ////echo $j; ?><!--//",-->
<!--//                    {-->
<!--//                        animationEnabled: true,-->
<!--//                        interactivityEnabled: true,-->
<!--//                        legendText: "{name}",-->
<!--//                        theme: "theme2",-->
<!--//                        toolTip: {-->
<!--//                            shared: true,-->
<!--//                        },-->
<!--//                        backgroundColor: "",-->
<!--//                        legend:{-->
<!--//                            fontFamily: "helvetica",-->
<!--//                            horizontalAlign: "center",-->
<!--//                            fontSize: 14,-->
<!--//                            enabled: false,-->
<!--//                        },-->
<!--//                        axisY: {-->
<!--//                            interval: 1,-->
<!--//                        },-->
<!--//                        data: [{-->
<!--//                            type: "pie",-->
<!--//                            startAngle: -90,-->
<!--//                            innerRadius: "75%",-->
<!--//                            showInLegend: false,-->
<!--//                            legendText: "{name}",-->
<!--//                            indexLabelFontFamily:"helvetica",-->
<!--//                            indexLabelFontColor: "#000",-->
<!--//                            indexLabelFontSize: 16,-->
<!--//                            indexLabelPlacement: "inside",-->
<!--//                            indexLabelFontWeight: "bold",-->
<!--//                            toolTipContent: "<a href = {content}>{name}: {y}%</a>",-->
<!--//                            dataPoints: [-->
<!--//                                --><?php
////                                if($dad_arr[$j]["total"] != 0) {
////                                    for ($i = 1; $i < $dad_arr[$j]["dem"]; $i++) {
////                                        $num = formatDiem(($dad_arr[$j]["me_arr"][$i] / $dad_arr[$j]["total"]) * 100);
////                                        echo "{ y: $num, name : 'Đáp án $i', indexLabel : '{y}%', content : '#', color: '" . $mau_arr[$i - 1] . "' },";
////                                    }
////                                }
////                                ?>
<!--//                            ]-->
<!--//                        }]-->
<!--//                    });-->
<!--//                chart--><?php ////echo $j; ?><!--//.render();-->
<!--//                --><?php ////} ?>
<!--//            }-->
<!--//        </script>-->
        <script type="text/javascript">
            $(document).ready(function() {
                $(".the-mien-phat").click(function () {
                    var hsID = $(this).attr("data-hsID");
                    $(this).remove();
                    if (valid_id(hsID)) {
                        $.ajax({
                            async: true,
                            data: "hsID_the=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Thẻ miễn phạt',
                                    text: 'Đã phát thẻ miễn phạt!',
                                    icon: 'icon-checkmark'
                                });
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $(".uncheck-cau-hoi").click(function() {
                    var id = $(this).attr("data-cID");
                    if(confirm("Bạn có chắc chắn không? Dữ liệu đúng sai và thời gian làm câu này của học sinh sẽ đc reset!")) {
                        if (valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "cID0=" + id + "&action=show",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                                success: function (result) {
                                    location.reload();
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
                $(".check-cau-hoi").click(function() {
                    var id = $(this).attr("data-cID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "cID2=" + id + "&action=show",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $(".xoa-binh-luan").click(function() {
                    var id = $(this).attr("data-cID");
                    if(confirm("Bạn có chắc chắn, hành động không thể hoàn tác!")) {
                        if (valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "cID5=" + id + "&action=xoa-binh-luan",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                                success: function (result) {
                                    location.reload();
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
//                $("table#list-dap-an tr.view-who-click").click(function() {
//                    var me = $(this);
//                    var daID = $(this).attr("data-daID");
//                    if(valid_id(daID)) {
//                        $.ajax({
//                            async: true,
//                            data: "daID=" + daID + "&deID=0",
//                            type: "post",
//                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
//                            success: function (result) {
//                                $("table#list-dap-an tr.who-click").remove();
//                                $(result).insertAfter(me);
//                                $("table#list-dap-an tr.who-click").attr("style",me.attr("style"));
//                            }
//                        });
//                    }
//                });
            });
        </script>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML-full"></script>
        <script>
            MathJax.Hub.Config({
                showProcessingMessages: false,
                messageStyle: "none",
                tex2jax: {
                    inlineMath: [["$", "$"], ["\\(", "\\)"], ["\\[", "\\]"]],
                    processEscapes: false
                },
                showMathMenu: false,
                displayAlign: "left",
                jax: ["input/TeX","output/NativeMML"],
                "fast-preview": {disabled: true},
                NativeMML: { linebreaks: { automatic: true }, minScaleAdjust: 110, scale: 110},
                TeX: { noErrors: { disabled: true } },
            });
        </script>

        <?php include_once "../include/bottom.php"; ?>