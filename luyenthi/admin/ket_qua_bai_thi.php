
    <?php include_once "../include/top.php"; ?>

    <?php
        if(isset($_GET["deID"]) && is_numeric($_GET["deID"]) && isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
            $hsID = $_GET["hsID"];
            $deID = $_GET["deID"];
        } else {
            $hsID = 0;
            $deID = 0;
        }
        $db2 = new De_Thi();
        $deID_old = $deID;

//        $deID = $db2->getDeThiMainByDe($deID);
        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();
        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
        $db = new Hoc_Sinh();
        $db3 = new Cau_Hoi();

        $result1 = $db->getHocSinhDetail($hsID);
        $data1 = $result1->fetch_assoc();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title text-center" style="padding-left: 36px;">-->
<!--                <h4>-->
<!--                    --><?php //echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><!--<br />-->
<!--                    <strong>--><?php //echo "<a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data1[cmt]/' target='_blank'>".$data1["cmt"]."</a> - <a href='".formatFacebook($data1["facebook"])."' target='_blank'>$data1[fullname]</a>"; ?><!--</strong>-->
<!--                </h4>-->
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
                    <div class="col-lg-12" style="padding: 0;">
                        <div class="panel-heading text-center">
                            <h5 class="panel-title">
                                <?php echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><br />
                                <strong><?php echo "<a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data1[cmt]/' target='_blank'>".$data1["cmt"]."</a> - <a href='".formatFacebook($data1["facebook"])."' target='_blank'>$data1[fullname]</a>"; ?></strong>
                            </h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="col-sm-12">
                                <table class="table bg-brown-400" id="list-thong-ke">
                                    <tbody>
                                    <tr>
                                        <td class="text-center" id="tong-diem">Điểm</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" id="tong-time">Tổng thời gian làm</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" id="dung-nhanh">Câu đúng làm nhanh nhất</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" id="dung-cham">Câu đúng làm chậm nhất</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="clear: both;height: 20px;"></div>
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col style="width:20%;" />
                                    <col style="width:80%;" />
                                <?php
                                    $dapan_hs = array();
                                    $result = $db2->getHocSinhDapAnByDe($hsID,$deID);
                                    while($data = $result->fetch_assoc()) {
                                        $dapan_hs[$data["ID_C"]] = array(
                                            "daID" => $data["ID_DA"],
                                            "note" => $data["note"],
                                            "time" => $data["time"]
                                        );
                                    }

                                    $dapan_all = array();
                                    $result = $db2->getDapAnNganByDeAll($deID);
                                    while($data = $result->fetch_assoc()) {
                                        $dapan_all[$data["ID_C"]][] = array(
                                            "ID_DA" => $data["ID_DA"],
                                            "main" => $data["main"],
                                            "type" => $data["type"],
                                            "content" => $data["content"]
                                        );
                                    }

                                    $debai = $dapan = $cau_sai = array();
                                    $diem = $dung = $sai = $draw = 0;
                                    $dem=1;
                                    $min_time = $data0["time"];
                                    $max_time = $tong_time = $min_cau = $max_cau = 0;
                                    $result = $db2->getCauHoiByDeWithTimeLimit($deID);
                                    $sum=$result->num_rows;
                                    if($sum != 0) {
                                        $diem_per = 10 / $sum;
                                    } else {
                                        $diem_per = 0;
                                    }
                                    while($data = $result->fetch_assoc()) {
                                        if(isset($dapan_hs[$data["ID_C"]])) {
                                            $data["ID_DA"] = $dapan_hs[$data["ID_C"]]["daID"];
                                            $data["khac"] = $dapan_hs[$data["ID_C"]]["note"];
                                            $data["time"] = $dapan_hs[$data["ID_C"]]["time"];
                                        } else {
                                            $data["ID_DA"] = NULL;
                                            $data["time"] = 0;
                                            $data["khac"] = "";
                                        }

                                        $tong_time += $data["time"];
                                        if($data["done"]==0) {
                                            $back="#ffffb2";
                                            $cau_sai[$dem] = 1;
                                        } else {
                                            $back="#D1DBBD";
                                        }
                                        echo"<tr class='de-bai-cau-$dem' style='background:$back;'>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <span class='label bg-brown-600' style='font-size:14px;'>Câu $data[sort]:</span>
                                                <span class='label bg-grey-400' style='font-size:14px;margin: 0 10px 0 5px;'>".formatTimeBack($data["time"]/1000)."</span>";
                                        if($data["content"]!="none") {
                                            echo imageToImg($data["ID_MON"],$data["content"],250);
                                        }
                                        if($data["anh"]!="none"){
                                            echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDe($data["ID_MON"],$data["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                        }
                                        echo"</td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'><button type='button' class='btn btn-primary btn-sm bg-primary-400 view-detail-ok'>Đáp án chi tiết</button></td>
                                            <td colspan='2' class='view-detail' style='display: none;line-height: 30px;'>";
                                        if($data["da_con"] != "none") {
                                            echo imageToImg($data["ID_MON"],$data["da_con"],300);
                                        }
                                        if($data["da_anh"] != "none"){
                                            echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db3->getUrlDe($data["ID_MON"],$data["da_anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                        }
                                        echo"</td>
                                        </tr>";
                                        $dem2=0;
                                        if(isset($data["ID_DA"]) && is_numeric($data["ID_DA"])) {
                                            $daID_hs = $data["ID_DA"];
                                        } else {
                                            $daID_hs = 0;
                                        }
                                        $dapan[$dem] = $daID_hs;
                                        $debai[$dem] = 0;
                                        for($i = 0; $i < count($dapan_all[$data["ID_C"]]); $i++) {
                                            if($dapan_all[$data["ID_C"]][$i]["main"] == 1) {
                                                $debai[$dem] = $dapan_all[$data["ID_C"]][$i]["ID_DA"];
                                            }
                                            if($dapan_all[$data["ID_C"]][$i]["main"] == 1 && $dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                echo "<tr style='background-color: #2196F3;color:#FFF;' class='dap-an-cau-$dem'>";
                                                if($data["time"] < $min_time) {
                                                    $min_time = $data["time"];
                                                    $min_cau = $dem;
                                                }
                                                if($data["time"] > $max_time) {
                                                    $max_time = $data["time"];
                                                    $max_cau = $dem;
                                                }
                                                $diem += $diem_per;
                                                $dung++;
                                            } else if ($dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                echo "<tr class='dap-an-cau-$dem'>";
                                            } else if ($dapan_all[$data["ID_C"]][$i]["main"] == 1) {
                                                echo "<tr style='background-color: #2196F3;color:#FFF;' class='dap-an-cau-$dem'>";
                                                $sai++;
                                            } else {
                                                echo"<tr class='dap-an-cau-$dem'>";
                                            }
                                            echo "<td>
                                                <div class='radio' style='left: 25%;'>
                                                    <label>
                                                        <input type='radio' disabled='disabled' name='radio-dap-an-$dem' "; if($dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs){echo"checked='checked'";} echo" class='control-danger radio-dap-an' data-cau='$dem' />    
                                                        ".$da_arr[$dem2].".
                                                    </label>
                                                </div></td>
                                                <td>";
                                            $khac = false;
                                            if ($dapan_all[$data["ID_C"]][$i]["type"] == "text") {
                                                $dapan_temp = imageToImgDapan($data["ID_MON"],$dapan_all[$data["ID_C"]][$i]["content"],250);
                                                if(stripos(unicodeConvert($dapan_temp),"dap-an-khac") === false) {} else {$khac=true;}
                                                echo $dapan_temp;
                                            } else {
                                                echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/" . $db3->getUrlDapAn($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"]) . "' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                                            }
                                            if($dapan_all[$data["ID_C"]][$i]["main"] == 1) {
                                                echo"<strong style='margin-left:10px;'>Đáp án đúng</strong>";
                                            }
                                            if($khac && $data["khac"] != "") {
                                                echo"<input type='text' class='form-control' style='float: right;width: 100%;' value='$data[khac]' disabled='disabled' />";
                                            }
                                            echo "</td>
                                            </tr>";
                                            $dem2++;
                                        }
                                        $dem++;
                                    }
                                    $dem--;
                                    $db4 = new Luyen_De();
                                    $result = $db4->getKetQuaLuyenDeByDe($deID_old,$hsID);
                                    $data = $result->fetch_assoc();
                                    $diem = $data["diem"];
                                    $tong_time = $data["time"];
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /striped rows -->
                    </div>
            </div>
            <!-- /main content -->

        </div>

        <div style="height: 70px;"></div>

        <div class="container-fluid" style="position: fixed;bottom:-20px;left:0;z-index:99;width: 100%;padding: 0;margin: 0;">
            <div class="panel panel-flat">
<!--                <div class="panel-heading" style="padding-bottom: 10px;padding-top: 10px;text-align: center;">-->
<!--                    <div class="panel-heading" style="padding-bottom: 0;padding-top: 0;">-->
<!--                        <h5 class="panel-title">Điểm <strong><code style="font-size: 26px;">--><?php //echo formatDiem($diem); ?><!--</code></strong></h5>-->
<!--                    </div>-->
<!--                    <div style="clear: both;"></div>-->
<!--                </div>-->
                <div class="panel-body" id="main-wapper" style="overflow-x: auto;padding: 0;">
                    <div></div>
                    <table class="table table-xs" id="list-tom-tat">
                        <thead>
                            <tr class="bg-brown-400">
                                <?php
                                $tomtat = "";
                                for($i=1;$i<=count($debai);$i++) {
                                    if(isset($cau_sai[$i])) {
                                        echo"<th style='min-width: 60px;cursor: pointer;background-color: #ffffb2;' class='text-center dap-an-eye'>$i</th>";
                                    } else {
                                        echo"<th style='min-width: 60px;cursor: pointer;' class='text-center dap-an-eye'>$i</th>";
                                    }
                                    if($debai[$i] == $dapan[$i]) {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;background-color: #2196F3;color: #FFF;'>Đ</td>";
                                    } else if($dapan[$i] == 0) {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>_</td>";
                                    } else {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;background-color: #EF5350;color: #FFF;'>S</td>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php echo $tomtat; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
//            window.onload = function () {
//                var myScroll = new IScroll('#main-wapper', {scrollX: true, scrollY: false, mouseWheel: false});
//            }
            $(document).ready(function() {
                $("table#list-tom-tat tr .dap-an-eye").click(function() {
                    var cau = $(this).index() + 1;
                    $("body").animate({
                        scrollTop: $("table#list-bai-thi tr.de-bai-cau-" + cau).offset().top},"slow");
                });
                $("#tong-diem").html("Điểm:<br /><strong><code style='font-size: 19px;'><?php echo $diem; ?></code></strong>");
                $("#tong-time").html("Tổng thời gian làm:<br /><strong><code style='font-size: 19px;'><?php echo formatTimeBack($tong_time/1000); ?></code></strong>");
                $("#dung-nhanh").html("Câu đúng làm nhanh nhất:<br /><strong><code style='font-size: 19px;'><?php echo "Câu $min_cau - ".formatTimeBack($min_time/1000); ?></code></strong>");
                $("#dung-cham").html("Câu đúng làm chậm nhất:<br /><strong><code style='font-size: 19px;'><?php echo "Câu $max_cau - ".formatTimeBack($max_time/1000); ?></code></strong>");
                $("table#list-bai-thi tr td button.view-detail-ok").click(function () {
                    $(this).closest("td").hide();
                    $(this).closest("tr").find("td.view-detail").fadeIn("fast");
                });
                //document.oncontextmenu = new Function("return false");
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
