
    <?php
        include_once "../include/top.php";
    ?>

    <?php
        if(isset($_GET["cdID"])) {
            $cdID=$_GET["cdID"];
        } else {
            $cdID = 0;
        }
        $db = new Chuyen_De();
        $result0 = $db->getChuyenDeById($cdID);
        $data0 = $result0->fetch_assoc();
        $db2 = new Cau_Hoi();

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Chuyên đề</span> - Danh sách câu hỏi chuyên đề --><?php //echo $data0["maso"]." - ".$data0["name"]; ?><!--</h4>-->
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
                            <h5 class="panel-title">Danh sách câu hỏi chuyên đề <?php echo $data0["maso"]." - ".$data0["name"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col style="width:20%;" />
                                    <col style="width:80%;" />
                                <?php

                                    $dem=1;
                                    $result = $db2->getCauHoiByChuyenDe($data0["maso"]);
                                    while($data = $result->fetch_assoc()) {
                                        echo"<tr class='de-bai-cau-big de-bai-cau-$dem' data-cau='$dem'>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>$data[maso]:</span> ";
                                        if($data["content"]!="none") {
                                            echo imageToImg($data["ID_MON"],$data["content"],250);
                                        }
                                        if($data["anh"]!="none"){
                                            echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data["ID_MON"],$data["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                        }
                                        echo"<a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-cau-hoi/$data[ID_C]/' style='float: right;' target='_blank'>Xem chi tiết</a>";
                                        echo"</td></tr>";
//                                        echo"<tr>
//                                            <td colspan='2'><button type='button' class='btn btn-primary btn-sm bg-primary-400 view-detail-ok'>Đáp án chi tiết</button></td>
//                                            <td colspan='2' class='view-detail' style='display: none;'>";
//                                        if($data["da_con"] != "none") {
//                                            echo imageToImg($data["ID_MON"],$data["da_con"],300);
//                                        }
//                                        if($data["da_anh"] != "none"){
//                                            echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data["ID_MON"],$data["da_anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
//                                        }
//                                        echo"</td>
//                                        </tr>";
//                                        $dem2=0;
//                                        $result2 = $db2->getDapAnNgan($data["ID_C"],false);
//                                        while($data2 = $result2->fetch_assoc()) {
//                                            $dapan_temp = imageToImg($data["ID_MON"],$data2["content"],250);
//                                            echo"<tr class='dap-an-cau-$dem'>
//                                            <td>
//                                                <div class='radio' style='left: 25%;'>
//                                                    <label>
//                                                        <input type='radio' data-id='$data2[ID_DA]' disabled='disabled' name='radio-dap-an-$dem' ";if($data2["main"]==1){echo"checked='checked'";}echo" class='control-danger radio-dap-an' data-cau='$dem' data-stt='$dem2'/>
//                                                        ".$da_arr[$dem2].".
//                                                    </label>
//                                                </div>
//                                            </td>
//                                            <td>";
//                                            if($data2["type"]=="text") {
//                                                echo $dapan_temp;
//                                            } else {
//                                                echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDapAn($data["ID_MON"],$data2["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
//                                            }
//                                            echo"</td>
//                                            </tr>";
//                                            $dem2++;
//                                        }
                                        $dem++;
                                    }
                                    $dem--;
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
            $(document).ready(function() {

                $("a.chuyen-dap-an").click(function () {
                    if(confirm("Bạn có chắc chắn? Các học sinh chọn đáp án này sẽ được chuyển sang chọn đáp án đúng!")) {
                        return true;
                    } else {
                        return false;
                    }
                });

                $("table#list-bai-thi tr.de-bai-cau-big").each(function(index,element) {
                    var dem = $(element).attr("data-cau");
                    var me = $("table#list-bai-thi tr.dap-an-cau-" + dem).length;
                    $(element).find("td.bieu-do-hin").attr("rowspan",me + 2);
                });

                $("table#list-bai-thi tr td button.view-detail-ok").click(function () {
                    $(this).closest("td").hide();
                    $(this).closest("tr").find("td.view-detail").fadeIn("fast");
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
