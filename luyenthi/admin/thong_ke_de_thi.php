
    <?php
        $start = microtime(true);
        ini_set('max_execution_time', 300);
        include_once "../include/top.php";
    ?>

    <?php
        $me = md5("123456");
        if(isset($_GET["deID"]) && is_numeric($_GET["deID"])) {
            $deID=$_GET["deID"];
        } else {
            $deID = 0;
        }
        $deID = $deID + 1 - 1;
        $db = new De_Thi();
        $result0 = $db->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();
        $nhom = $data0["nhom"];
        $db2 = new Cau_Hoi();
//        $monID = (new Mon_Hoc())->getMonOfLop($data0["ID_LM"]);
        $monID = 0;

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Thống kê --><?php //echo $data0["mota"]; ?><!--</h4>-->
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
                <form class="form-horizontal">
                <!-- Main form -->
                    <button type="button" class="btn btn-primary btn-sm bg-slate-400 btn-fixed">Đề: <?php echo (new Loai_De())->getNameLoaiDeById($data0["loai"]); ?></button>
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Thống kê <?php echo $data0["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Đang làm bài</th>
                                        <th class="text-center bieu-do-hin">Tổng quan</th>
                                        <th class="text-center">Đã kết thúc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><a id="show-do" href="http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dang-lam/<?php echo $nhom; ?>/" target="_blank" style="font-size:80px;font-weight: 600;"></a></td>
                                        <td class="text-center bieu-do-hin">Thời gian làm bài: <?php echo $data0["time"]; ?> phút</td>
                                        <td class="text-center"><a id="show-done" href="http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/<?php echo $nhom; ?>/" target="_blank" style="font-size:80px;font-weight: 600;"></a></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="cursor: pointer;" colspan="3" id="btn-refresh"><i class="icon-reload-alt"></i> Làm mới</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
<!--                        <div class="panel panel-flat">-->
<!--                            <table class="table" id="list-search">-->
<!--                                <tr>-->
<!--                                    <td colspan="3"><input type="text" id="search-cau-hoi" class="form-control" placeholder="Mã câu hỏi (H07-05a-1a, D08-06a-3c)" /></td>-->
<!--                                </tr>-->
<!--                            </table>-->
<!--                        </div>-->
                        <div class="panel panel-flat">
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col class='bieu-do-hin' style="width:30%;" />
                                    <col style="width:20%;" />
                                    <col style="width:80%;" />
                                <?php
                                    $mau_arr = array("#69b42e","blue","orange","red","brown","black");
//                                    $db5 = new Binh_Luan();
                                    $de_arr = "";
                                    $result = $db->getDeThiByNhom($nhom);
                                    while($data = $result->fetch_assoc()) {
                                        $de_arr .= ",'$data[ID_DE]'";
                                    }
                                    $de_arr = substr($de_arr,1);

//                                    $total = 0;
//                                    $dapan_all = $dacau_arr = $dapan_tb = $dapan = array();
//                                    $result = $db->getDapAnByDeCount($deID,$de_arr);
//                                    while($data = $result->fetch_assoc()) {
//                                        if(!isset($dapan_all[$data["ID_C"]])) {
//                                            $dapan_all[$data["ID_C"]] = "";
//                                        }
//                                        if(!isset($dacau_arr[$data["ID_C"]])) {
//                                            $dacau_arr[$data["ID_C"]] = 0;
//                                        }
//                                        if(!isset($dapan_tb[$data["ID_C"]])) {
//                                            $dapan_tb[$data["ID_C"]] = 0;
//                                        }
//                                        $dem2 = $dacau_arr[$data["ID_C"]];
//                                        $num = $data["dem"];
//                                        $dapan_temp = imageToImgDapan($monID,$data["content"],250);
//                                        if($data["main"] == 1) {
//                                            $dapan_all[$data["ID_C"]] .="<tr class='dap-an-cau-$data[ID_C]' style='background-color: #2196F3;color:#FFF;cursor: pointer;'>";
//                                            $dapan[$data["ID_C"]][$dem2] = array(
//                                                "daID" => $data["ID_DA"],
//                                                "da" => $da_arr[$dem2],
//                                                "num" => $num,
//                                                "main" => true,
//                                                "mau" => "#2196F3"
//                                            );
//                                        } else {
//                                            $dapan_all[$data["ID_C"]] .="<tr class='dap-an-cau-$data[ID_C]' style='cursor: pointer;'>";
//                                            $mau = "#8D6E63";
////                                            if(stripos(unicodeConvert($dapan_temp),"dap-an-khac") ===false) {
////
////                                            } else {
////                                                $mau = "#EF5350";
////                                            }
//                                            $dapan[$data["ID_C"]][$dem2] = array(
//                                                "daID" => $data["ID_DA"],
//                                                "da" => $da_arr[$dem2],
//                                                "num" => $num,
//                                                "main" => false,
//                                                "mau" => $mau
//                                            );
//                                        }
//                                        $dapan_all[$data["ID_C"]] .="<td>
//                                            <div class='radio' style='left: 25%;'>
//                                                <label>
//                                                    <input type='radio' disabled='disabled' ";if($data["main"]==1){$dapan_all[$data["ID_C"]] .="checked='checked'";}$dapan_all[$data["ID_C"]] .=" class='control-danger radio-dap-an' />
//                                                    ".$da_arr[$dem2].".
//                                                </label>
//                                            </div></td>
//                                            <td>($num) ";
//                                            if($data["type"]=="text") {
//                                                $dapan_all[$data["ID_C"]] .= $dapan_temp;
//                                            } else {
//                                                $dapan_all[$data["ID_C"]] .="<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDapAn($monID,$data["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
//                                            }
//                                        $dapan_all[$data["ID_C"]] .="</td>
//                                        </tr>";
//                                        $dacau_arr[$data["ID_C"]]++;
//                                        $dapan_tb[$data["ID_C"]] += $num;
//                                    }

                                    $dem=1;
                                    $dapan_arr = array();
                                    $dapan2 = $dapan_tb2 = array();
                                    $result = $db->getCauHoiByDeWithDiemKem($deID, $de_arr);
                                    while($data = $result->fetch_assoc()) {
                                        $dapan_arr[] = $data["ID_C"];
                                        $monID = $data["ID_MON"];
                                        echo"<tr class='de-bai-cau-big de-bai-cau-$data[ID_C]' data-cau='$data[ID_C]'>
                                            <td rowspan='' class='bieu-do-hin' style='width: 30%;min-width: 150px;'>
                                                <div id='chart-container-$data[ID_C]' style='width: 100%;height: 270px;margin-top: 10px;'></div>
                                            </td>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>Câu $data[sort] ($data[maso]):</span><span class='label' style='background-color: ".$mau_arr[$data["muc"]].";margin-right: 5px;'>$data[name]</span>  ";
                                            if($data["content"]!="none") {
                                                echo imageToImg($data["ID_MON"],$data["content"],250);
                                            }
                                            if($data["anh"]!="none"){
                                                echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data["ID_MON"],$data["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
//                                            echo"<button type='button' class='btn btn-primary btn-sm bg-primary-400 bg-danger-400 sai-de-ok' data-cID='$data[ID_C]' style='float:right;'>Sai đề?</button>";
                                            echo"<a href='http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$data[ID_C]/' target='_blank' class='btn btn-primary btn-sm bg-slate-400' style='float:right;'><i class='icon-pencil3'></i></a>";
                                        echo"</td></tr>";
                                        echo"<tr class='dap-an-dai-cau-$data[ID_C]'>
                                            <td colspan='2'><button type='button' class='btn btn-primary btn-sm bg-primary-400 view-detail-ok'>Đáp án chi tiết</button></td>
                                            <td colspan='2' class='view-detail' style='display: none;line-height: 30px;'>";
                                            if($data["da_con"] != "none") {
                                                echo imageToImg($data["ID_MON"],$data["da_con"],300);
                                            }
                                            if($data["da_anh"] != "none"){
                                                echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data["ID_MON"],$data["da_anh"])."' style='max-height:300px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
                                            echo"</td>
                                        </tr>";
                                        if(isset($dapan_all[$data["ID_C"]])) {
                                            echo $dapan_all[$data["ID_C"]];
                                        }
                                        if(isset($dapan_tb[$data["ID_C"]])) {
                                            $dapan_tb2[$dem] = $dapan_tb[$data["ID_C"]];
                                        } else {
                                            $dapan_tb2[$dem] = 0;
                                        }
                                        if(isset($dapan[$data["ID_C"]])) {
                                            $dapan2[$dem] = $dapan[$data["ID_C"]];
                                        } else {
                                            $dapan2[$dem] = 0;
                                        }
                                        $dem++;
                                    }
                                    if($dem == 1) {
                                        echo"<tr><td colspan='3'>Học sinh chưa làm câu nào!</td></tr>";
                                    }
                                    $dem--;
                                    $time_elapsed_secs = formatDiem(microtime(true) - $start);
                                    echo"<tr><td colspan='3'>Thời gian chạy: ".$time_elapsed_secs."s</td></tr>";
//                                    arsort($dapans_count);
//                                    echo json_encode($dapans_count);
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /striped rows -->
                    </div>
                <!-- /main form -->
                </form>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            var DPS = [];
//            window.onload = function () {
                <?php for($i = 0;$i < $dem;$i++) { ?>
                if(DPS["<?php echo $dapan_arr[$i]; ?>"]) {

                } else {
                    DPS["<?php echo $dapan_arr[$i]; ?>"] = [];
                }
                var chart_<?php echo $dapan_arr[$i]; ?> = new CanvasJS.Chart("chart-container-<?php echo $dapan_arr[$i]; ?>",
                    {
                        animationEnabled: false,
                        interactivityEnabled: true,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: false,
                            enabled: true,
                        },
                        backgroundColor: "",
                        legend:{
                            fontFamily: "helvetica",
                            horizontalAlign: "center",
                            fontSize: 14,
                            enabled: false,
                        },
                        axisY: {
                            interval: 1,
                        },
                        data: [{
                            type: "pie",
                            startAngle: -90,
                            innerRadius: "75%",
                            showInLegend: false,
                            legendText: "{name}",
                            indexLabelFontFamily:"helvetica",
                            indexLabelFontColor: "brown",
                            indexLabelFontSize: 16,
                            indexLabelPlacement: "inside",
                            indexLabelFontWeight: "bold",
                            click: function(e) {
                                window.open(e.dataPoint.content,'_blank');
                            },
                            toolTipContent: "{name}: {y}%",
                            dataPoints: DPS["<?php echo $dapan_arr[$i]; ?>"]
                            <?php
                            //                                for($j = 0;$j < count($dapan2[$i]);$j++) {
                            //                                    if($dapan_tb2[$i] != 0) {
                            //                                        if ($dapan2[$i][$j]["main"]) {
                            //                                            echo "{ y: " . formatNumber($dapan2[$i][$j]["num"] * 100 / $dapan_tb2[$i]) . ", name : '" . $dapan2[$i][$j]["da"] . "', indexLabel : '" . $dapan2[$i][$j]["da"] . ": {y}%', content : 'http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/" . $dapan2[$i][$j]["daID"] . "/$nhom/', color: '" . $dapan2[$i][$j]["mau"] . "', indexLabelFontColor: '#FFF' },";
                            //                                        } else {
                            //                                            echo "{ y: " . formatNumber($dapan2[$i][$j]["num"] * 100 / $dapan_tb2[$i]) . ", name : '" . $dapan2[$i][$j]["da"] . "', indexLabel : '', content : 'http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/" . $dapan2[$i][$j]["daID"] . "/$nhom/', color: '" . $dapan2[$i][$j]["mau"] . "', indexLabelFontColor: '#FFF' },";
                            //                                        }
                            //                                    }
                            //                                }
                            ?>
                        }]
                    });

                <?php } ?>
//            }
            $(document).ready(function() {

                $(".import-cau-hoi").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        return true;
                    } else {
                        return false;
                    }
                });

<!--                --><?php //foreach($dapans_count as $key => $value) { ?>
//                    //$("table#list-bai-thi tr.de-bai-cau-big:eq(<?php //echo ($key-1); ?>//)").insertBefore($("table#list-bai-thi tr.de-bai-cau-big:first-child"));
//                <?php //} ?>

                $("#search-cau-hoi").typeWatch({
                    captureLength: 3,
                    callback: function (value) {
                        $.ajax({
                            async: true,
                            data: "search_ma_cau_hoi=" + value,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                $("table#list-search tr.tr-search").remove();
                                $("table#list-search").append(result);
                            }
                        });
                    }
                });

                setTimeout(function () {
                    refresh();
                },500);

                $("#btn-refresh").click(function () {
                    refresh();
                });

                function refresh() {
                    $.ajax({
                        async: true,
                        data: "nhom_count=<?php echo $nhom; ?>&lmID_count=<?php echo $data0["ID_LM"]; ?>",
                        type: "post",
                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                        success: function (result) {
                            console.log(result);
                            var obj = jQuery.parseJSON(result);
                            $("#show-do").html(obj.do);
                            $("#show-done").html(obj.done);
                        }
                    });
                }

                setTimeout(function() {
                    new PNotify({
                        title: 'Thống kê',
                        text: 'Đang tải dữ liệu...',
                        icon: 'icon-reload-alt'
                    });
                    $.ajax({
                        async: true,
                        data: "deID_goc=<?php echo $deID; ?>&de_arr=<?php echo $de_arr; ?>&monID=<?php echo $monID; ?>",
                        type: "post",
                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                        success: function (result) {
//                            console.log(result);
                            new PNotify({
                                title: 'Thống kê',
                                text: 'Tải thành công!',
                                icon: 'icon-menu6'
                            });
                            var obj = jQuery.parseJSON(result);
                            $("table#list-bai-thi tr.de-bai-cau-big").each(function(index, element) {
                                var cID = $(element).attr("data-cau");
                                $(obj[cID].content).insertAfter($("table#list-bai-thi tr.dap-an-dai-cau-" + cID));
                                var me = $("table#list-bai-thi tr.dap-an-cau-" + cID).length;
                                $(element).find("td.bieu-do-hin").attr("rowspan",me + 2);

                                var total = obj[cID].total;
                                var chart = obj[cID].chart;
                                for(i = 0; i < chart.length; i++) {
                                    if(chart[i].main) {
                                        DPS[cID][i] = {y: Math.floor((chart[i].num / total)*100), name: chart[i].da, indexLabel: chart[i].da + ": {y}%", content: "http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/" + chart[i].daID + "/<?php echo $nhom; ?>/", color: chart[i].mau, indexLabelFontColor: "#FFF"};
                                    } else {
                                        DPS[cID][i] = {y: Math.floor((chart[i].num / total)*100), name: chart[i].da, indexLabel: "", content: "http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/" + chart[i].daID + "/<?php echo $nhom; ?>/", color: chart[i].mau, indexLabelFontColor: "#FFF"};
                                    }
//                                    console.log(chart[i]);
                                }
                            });
                            MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                            <?php
                                for($i = 0;$i < $dem;$i++) {
                                    echo"chart_".$dapan_arr[$i].".render();";
                                }
                            ?>
                        }
                    });
                },1500);

                $("a.chuyen-dap-an").click(function () {
                    if(confirm("Bạn có chắc chắn? Các học sinh chọn đáp án này sẽ được chuyển sang chọn đáp án đúng!")) {
                        return true;
                    } else {
                        return false;
                    }
                });

                $("table#list-bai-thi tr td button.view-detail-ok").click(function () {
                    $(this).closest("td").hide();
                    $(this).closest("tr").find("td.view-detail").fadeIn("fast");
                });

                $("button.sai-de-ok").click(function () {
                    if(confirm("Bạn có chắc chắn không? Các dữ liệu học sinh làm câu này sẽ bị xóa!")) {
                        var cID = $(this).attr("data-cID");
                        if($.isNumeric(cID) && cID!=0) {
                            $.ajax({
                                async: true,
                                data: "cID3=" + cID + "&nhom=<?php echo $nhom; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                                success: function (result) {
                                    location.reload();
                                }
                            });
                        }
                    }
                });
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
