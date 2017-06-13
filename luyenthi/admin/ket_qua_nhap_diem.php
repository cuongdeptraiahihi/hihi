
    <?php include_once "../include/top.php"; ?>

    <?php
        ini_set('max_execution_time', 300);
        $me = md5("123456");
        if(isset($_GET["deID"]) && is_numeric($_GET["deID"]) && isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
            $hsID = $_GET["hsID"];
            $deID = $_GET["deID"];
        } else {
            $hsID = 0;
            $deID = 0;
        }
        $db2 = new De_Thi();
        $deID_old = $deID;

        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();

        $result1 = $db2->getNhomDeById($data0["nhom"]);
        $data1 = $result1->fetch_assoc();

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title text-center" style="padding-left: 36px;">-->
<!--                <h4>--><?php //echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><!--</h4>-->
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
                            <h5 class="panel-title"><?php echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="col-sm-12">
                                <table class="table bg-brown-400" id="list-thong-ke">
                                    <tbody>
                                    <tr>
                                        <td class="text-center" id="tong-diem"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div style="clear: both;"></div>
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col style="width:100%;" />
                                    <tr>
                                        <td class="text-center">Hãy kiểm tra bài thi của bạn với đáp án mà máy nhận được! Đáp án sẽ được mở khi các bạn làm bài ở nhà hoàn thành</td>
                                    </tr>
                                    <tr>
                                        <?php
                                        $img = $db2->getAnhKiemTraHS($hsID, $data1["object"], $data1["ID_LM"]);
                                        if($img == "none") {
                                            echo"<td class='text-center'>KHÔNG THẤY ẢNH</td>";
                                        } else {
                                            $monID = (new Mon_Hoc())->getMonOfLop($data1["ID_LM"]);
                                            echo"<td class='text-center'><img style='width: 100%;' src='https://localhost/www/TDUONG/hocsinh/kiemtra/$data1[object]/".$db2->getCaKiemTraHS($hsID,$db2->getNgayFromBuoi($data1["object"],$monID), $monID)."/$img' /></td>";
                                        }
                                        ?>
                                    </tr>
                                <?php
                                    $dapan_hs = array();
                                    $result = $db2->getHocSinhDapAnByDe($hsID,$deID);
                                    while($data = $result->fetch_assoc()) {
                                        $dapan_hs[$data["ID_C"]] = $data["ID_DA"];
                                    }

                                    $dapan_all = array();
                                    $result = $db2->getDapAnNganByDeAll($deID);
                                    while($data = $result->fetch_assoc()) {
                                        $dapan_all[$data["ID_C"]][] = $data["ID_DA"];
                                    }

                                    $debai = $dapan = array();
                                    $dem=1;
                                    $result = $db2->getCauHoiByDeWithTimeLimit($deID);
                                    while($data = $result->fetch_assoc()) {
                                        if(isset($dapan_hs[$data["ID_C"]])) {
                                            $daID_hs = $dapan_hs[$data["ID_C"]];
                                        } else {
                                            $daID_hs = 0;
                                        }
                                        $debai[$dem] = 0;
                                        $dem2=0;
                                        for($i = 0; $i < count($dapan_all[$data["ID_C"]]); $i++) {
                                            if($dapan_all[$data["ID_C"]][$i] == $daID_hs) {
                                                $dapan[$dem] = $da_arr[$dem2];
                                            }
                                            $dem2++;
                                        }
                                        $dem++;
                                    }
                                    $dem--;
                                    $db4 = new Luyen_De();
                                    $result = $db4->getKetQuaLuyenDeByDe($deID_old,$hsID);
                                    $data = $result->fetch_assoc();
                                    $diem = $data["diem"];
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <!-- /main content -->

        </div>

        <div style="height: 130px;"></div>

        <div class="container-fluid" style="position: fixed;bottom:-20px;left:0;z-index:99;width: 100%;padding: 0;margin: 0;">
            <div class="panel panel-flat">
                <div class="panel-heading" style="padding-bottom: 10px;padding-top: 10px;text-align: center;">
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: left;" id="submit-cau-left"><span class="icon-chevron-left"></span> Trước</button>
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: right;" id="submit-cau-right">Sau <span class="icon-chevron-right"></span></button>
                    <div style="clear: both;"></div>
                </div>
                <div class="panel-body" id="main-wapper" style="overflow-x: auto;padding: 0;">
                    <div></div>
                    <table class="table table-xs" id="list-tom-tat">
                        <thead>
                            <tr class="bg-brown-400">
                                <?php
                                $tomtat = "";
                                for($i=1;$i<=count($debai);$i++) {
                                    echo"<th style='min-width: 60px;cursor: pointer;' class='text-center dap-an-eye'>$i</th>";
                                    if(isset($dapan[$i])) {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>$dapan[$i]</td>";
                                    } else {
                                        $tomtat .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>_</td>";
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
            window.onload = function () {
                var myScroll = new IScroll('#main-wapper', {scrollX: true, scrollY: false, mouseWheel: false});
            }
            $(document).ready(function() {
                $("table#list-tom-tat tr .dap-an-eye").click(function() {
                    var cau = $(this).index() + 1;
                    $("body").animate({
                        scrollTop: $("table#list-bai-thi tr.de-bai-cau-" + cau).offset().top},"slow");
                });
                $("#tong-diem").html("<strong><code style='font-size: 34px;'><?php echo $diem; ?></code></strong>");
                $("table#list-bai-thi tr td button.view-detail-ok").click(function () {
                    $(this).closest("td").hide();
                    $(this).closest("tr").find("td.view-detail").fadeIn("fast");
                });
                var td_width = $("table#list-tom-tat").width() / <?php echo $dem; ?>;
                var scroll_pos = 0;
                $("button#submit-cau-left").click(function() {
                    $("table#list-tom-tat").closest("div").animate({scrollLeft:scroll_pos-300},250);
                    scroll_pos -= 300;
                    if(scroll_pos < 0)
                        scroll_pos = 0;
                });
                $("button#submit-cau-right").click(function() {
                    $("table#list-tom-tat").closest("div").animate({scrollLeft:scroll_pos+300},250);
                    scroll_pos += 300;
                    if(scroll_pos > $("table#list-tom-tat").width())
                        scroll_pos = $("table#list-tom-tat").width();
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
