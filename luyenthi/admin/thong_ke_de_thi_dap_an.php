
    <?php
        include_once "../include/top.php";
    ?>

    <?php
        if(isset($_GET["daID"]) && is_numeric($_GET["daID"]) && isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
            $daID = $_GET["daID"];
            $nhom = $_GET["nhom"];
        } else {
            $daID = 0;
            $nhom = 0;
        }
        $db = new De_Thi();
        $result0 = $db->getNhomDeById($nhom);
        $data0 = $result0->fetch_assoc();
        $deID = $db->getDeThiMainByNhom($nhom);
        $db3 = new Cau_Hoi();
        $result1 = $db3->getCauHoiById($db3->getCauHoiByDapAn($daID));
        $data1 = $result1->fetch_assoc();

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Thống kê nhóm đề thi mã --><?php //echo $data0["code"]; ?><!--</h4>-->
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
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Câu hỏi</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col class='bieu-do-hin' style="width:30%;" />
                                    <col style="width:20%;" />
                                    <col style="width:80%;" />
                                <?php
                                $dem = 1;
                                $dapan = array();
                                $dapan_tb = array();
                                echo"<tr class='de-bai-cau-$dem'>
                                    <td rowspan='8' class='bieu-do-hin' style='width: 30%;min-width: 150px;'>
                                        <div id='chart-container-$dem' style='width: 100%;height: 270px;margin-top: 10px;'></div>
                                    </td>
                                    <td colspan='2' style='line-height: 30px;'>
                                        <span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>Đề bài ($data1[maso]):</span> ";
                                if($data1["content"]!="none") {
                                    echo imageToImg($data1["ID_MON"],$data1["content"],250);
                                }
                                if($data1["anh"]!="none"){
                                    echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDe($data1["ID_MON"],$data1["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                }
                                echo"</td></tr>";
                                $result2 = $db3->getDapAnDai($data1["ID_C"]);
                                $data2=$result2->fetch_assoc();
                                echo"<tr>
                                <td colspan='2'>";
                                    if($data2["content"] != "none") {
                                        echo imageToImg($data1["ID_MON"],$data2["content"],250);
                                    }
                                    if($data2["anh"] != "none"){
                                        echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDe($data1["ID_MON"],$data2["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                    }
                                echo"</td>
                                </tr>";
                                $dem2=0;
                                $total = 0;
                                $main = 0;
                                $result2 = $db->getDapAnNganByDe($data1["ID_C"],$deID,false);
                                while($data2=$result2->fetch_assoc()) {
                                    $num = $db->getChonDapAnByNhom($data2["ID_DA"],$nhom);
                                    $total += $num;
                                    $dapan_temp = imageToImg($data1["ID_MON"],$data2["content"],250);
                                    if($data2["main"] == 1) {
                                        echo"<tr class='dap-an-cau-$dem' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/$data2[ID_DA]/$nhom/'\" style='background-color: #2196F3;color:#FFF;cursor: pointer;'>";
                                        $dapan[$dem][$dem2] = array(
                                            "daID" => $data2["ID_DA"],
                                            "da" => $da_arr[$dem2],
                                            "num" => $num,
                                            "main" => true,
                                            "mau" => "#2196F3"
                                        );
                                        $main = $data2["ID_DA"];
                                    } else {
                                        echo"<tr class='dap-an-cau-$dem' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/$data2[ID_DA]/$nhom/'\" style='cursor: pointer;'>";
                                        $mau = "#8D6E63";
                                        if(stripos(unicodeConvert($dapan_temp),"dap-an-khac") ===false) {

                                        } else {
                                            $mau = "#EF5350";
                                        }
                                        $dapan[$dem][$dem2] = array(
                                            "daID" => $data2["ID_DA"],
                                            "da" => $da_arr[$dem2],
                                            "num" => $num,
                                            "main" => false,
                                            "mau" => $mau
                                        );
                                    }
                                    echo"<td>
                                        <div class='radio' style='left: 25%;'>
                                            <label>
                                                <input type='radio' data-id='$data2[ID_DA]' disabled='disabled' name='radio-dap-an-$dem' ";if($data2["main"]==1){echo"checked='checked'";}echo" class='control-danger radio-dap-an' data-cau='$dem' data-stt='$dem2'/>
                                                ".$da_arr[$dem2]." ($num).
                                            </label>
                                        </div></td>
                                        <td>";
                                    if($data2["type"]=="text") {
                                        echo $dapan_temp;
                                    } else {
                                        echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDapAn($data1["ID_MON"],$data2["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                                    }
                                    if($daID == $data2["ID_DA"]) {
                                        echo"<strong style='margin-left:10px;'>Đang ở đây</strong>";
                                    }
                                    echo"</td>
                                        </tr>";
                                    $dem2++;
                                }
                                $dapan_tb[$dem] = $total;
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Danh sách học sinh chọn đáp án này</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 10%:" class='text-center'>STT</th>
                                        <th style="width: 10%:" class='text-center'>Mã số</th>
                                        <th style="width: 15%:" class='text-center'>Họ và tên</th>
                                        <th class='text-center'>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $db2 = new Thong_Ke();
                                    $dem3 = 1;
                                    $result = $db2->getListChonDapAn($daID,$nhom);
                                    while($data = $result->fetch_assoc()) {
                                        echo"<tr>
                                            <td class='text-center'>$dem3</td>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                            <td class='text-center'><a href='".formatFacebook($data["facebook"])."' target='_blank'>$data[fullname]</a></td>
                                            <td class='text-center'>";
//                                                if($data["note"]!="") {
//                                                    echo"$data[note] | <a href='javascript:void(0)' class='lam-dung' data-hsID='$data[ID_HS]' data-deID='$data[ID_DE]'>Đúng</a>";
//                                                }
                                            echo"</td>
                                        </tr>";
                                        $dem3++;
                                    }
                                 ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Bình luận</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th style="width: 10%:" class='text-center'>STT</th>
                                    <th style="width: 10%:" class='text-center'>Mã số</th>
                                    <th style="width: 15%:" class='text-center'>Họ và tên</th>
                                    <th class='text-center'>Nội dung</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $db5 = new Binh_Luan();
                                $dem3 = 1;
                                $result3 = $db5->getBinhLuanCau($data1["ID_C"],$data1["ID_MON"],100);
                                while($data3=$result3->fetch_assoc()) {
                                    echo"<tr>
                                        <td class='text-center'>$dem3</td>
                                        <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                        <td class='text-center'><a href='".formatFacebook($data["facebook"])."' target='_blank'>$data[fullname]</a></td>
                                        <td class='text-center'>$data[content]</td>
                                     </tr>";
                                    $dem3++;
                                }
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
            window.onload = function () {
                <?php for($i = 1;$i <= $dem;$i++) { ?>

                var chart_<?php echo $i; ?> = new CanvasJS.Chart("chart-container-<?php echo $i; ?>",
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
                                window.location.href=e.dataPoint.content;
                            },
                            toolTipContent: "{name}: {y}%",
                            dataPoints: [
                                <?php
                                for($j = 0;$j < count($dapan[$i]);$j++) {
                                    if($dapan_tb[$i] != 0) {
                                        if ($dapan[$i][$j]["main"]) {
                                            echo "{ y: " . formatNumber($dapan[$i][$j]["num"] * 100 / $dapan_tb[$i]) . ", name : '" . $dapan[$i][$j]["da"] . "', indexLabel : '" . $dapan[$i][$j]["da"] . ": {y}%', content : 'http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/" . $dapan[$i][$j]["daID"] . "/$nhom/', color: '#2196F3', indexLabelFontColor: '#FFF' },";
                                        } else {
                                            echo "{ y: " . formatNumber($dapan[$i][$j]["num"] * 100 / $dapan_tb[$i]) . ", name : '" . $dapan[$i][$j]["da"] . "', indexLabel : '', content : 'http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/dap-an/" . $dapan[$i][$j]["daID"] . "/$nhom/', color: '#8D6E63', indexLabelFontColor: '#FFF' },";
                                        }
                                    }
                                }
                                ?>
                            ]
                        }]
                    });
                chart_<?php echo $i; ?>.render();

                <?php } ?>
            }
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
