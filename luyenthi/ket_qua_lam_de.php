
    <?php include_once "include/top_hoc_sinh.php"; ?>
    <?php
        $me = md5("123456");
        if(isset($_GET["type"])) {
            $type = $_GET["type"];
        } else {
            $type = "X";
        }
        $db = new Luyen_De();
        $db2 = new De_Thi();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Kết quả làm đề</h4>-->
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
                <form>
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Đề kiểm tra đã làm</h5>
                        </div>
                        <div class="panel panel-flat table-responsive noSwipe">
                            <table class="table list-danh-sach table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;" class="text-center">STT</th>
                                        <th class="text-center">Ngày kiểm tra</th>
                                        <th style='width: 25%;' class="text-center">Điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $de_arr = array();
                                    $de_arr[] = "'0'";
                                    $dem = 1;
                                    $result = $db->getLuyenDeHocSinhByMonType($hsID, $lmID, "kiem-tra");
                                    while($data=$result->fetch_assoc()) {
    //									if($dem == 1) {
    //										$dem++;
    //										continue;
    //									}
                                        echo"<tr style='cursor: pointer;' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/tong-quan-de/".encodeData($data["ID_DE"],$me)."/'\">
                                            <td class='text-center'>$dem</td>
                                            <td class='text-center'>".formatDateUp($db2->getNgayFromBuoi($data["object"],$monID))."</td>
                                            <td class='text-center'>$data[diem]</td>
                                        </tr>";
                                        $dem++;
                                        $de_arr[] = "'".$data["ID_DE"]."'";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Đề kiểm tra chưa làm</h5>
                        </div>
                        <div class="panel panel-flat table-responsive noSwipe">
                            <table class="table list-danh-sach table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;" class="text-center">STT</th>
                                        <th class="text-center">Ngày kiểm tra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $dem = 1;
                                $result = $db2->getNhomDeThiCustom($lmID, "kiem-tra", (new Hoc_Sinh())->getDeOfHocSinh($hsID, $lmID), implode(",", $de_arr));
                                while($data = $result->fetch_assoc()) {
                                    echo"<tr style='cursor: pointer;' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/tong-quan-de/".encodeData($data["ID_DE"],$me)."/'\">
                                            <td class='text-center'>$dem</td>
                                            <td class='text-center'>".formatDateUp($db2->getNgayFromBuoi($data["object"],$monID))."</td>
                                        </tr>";
                                    $dem++;
                                }
                                if($dem == 1) {
                                    echo"<tr>
                                        <td class='text-center' colspan='2'>Không có đề cũ nào</td>
                                    </tr>";
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

        <?php include_once "include/bottom_hoc_sinh.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
//                $("body").addClass("sidebar-xs");
//                $("#change-loai-de").change(function(){
//                    me = $(this).val();
//                    window.location.href="http://localhost/www/TDUONG/luyenthi/ket-qua-lam-de/" + me + "/";
//                });
            });
        </script>
