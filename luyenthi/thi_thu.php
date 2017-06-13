
    <?php include_once "include/top_hoc_sinh.php"; ?>
    <?php
        $me = md5("123456");
        $db = new De_Thi();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Đề thi thử</h4>-->
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
                            <h5 class="panel-title">Các đề thi thử</h5>
                        </div>
                        <div class='panel panel-flat table-responsive'>
                            <table class="table list-danh-sach table-bordered table-striped noSwipe">
                                <tbody>
                                    <?php
                                    $dem = 1;
                                    $result = $db->getNhomDeOpenType($hsID, "thi-thu", $lmID);
                                    while($data=$result->fetch_assoc()) {
                                        echo"<tr style='cursor: pointer;' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/tong-quan-de/".encodeData($data["ID_DE"],$me)."/'\">
                                            <td style='width:15%;' class='text-center'>$dem</td>
                                            <td>$data[mota]</td>
                                        </tr>";
                                        $dem++;
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
