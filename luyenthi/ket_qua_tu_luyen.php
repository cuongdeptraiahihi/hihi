
    <?php include_once "include/top_hoc_sinh.php"; ?>
    <?php
        $me = md5("123456");
        $db = new Luyen_De();
        $db2 = new De_Thi();
    ?>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Kết quả tự luyện đề</h4>
            </div>
        </div>
    </div>
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
                        <div class="panel panel-flat table-responsive">
                            <table class="table list-danh-sach noSwipe">
                                <thead>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center">Hoàn thành</th>
                                    <th class="text-center">Đề thi</th>
                                    <th class="text-center">Điểm</th>
                                    <th class="text-center">Thời gian</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $dem = 1;
                                $result = $db->getTuLuyenDeHocSinh($hsID, $lmID);
                                while($data=$result->fetch_assoc()) {
                                    echo"<tr>
                                        <td class='text-center'>
                                             <ul class='icons-list'>";
                                                if($data["out_time"] != "0000-00-00 00:00:00") {
                                                    echo "<li><button type='button' style='text-transform:uppercase;' class='btn btn-primary btn-xs bg-primary-400' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/ket-qua-tai-lop/" . encodeData($data["ID_DE"], $me) . "/'\">Xem</button></li>";
                                                } else if($data["in_time"] != "0000-00-00 00:00:00") {
                                                    echo "<li><button type='button' style='text-transform:uppercase;' class='btn btn-primary btn-xs bg-danger-400' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($data["ID_DE"], $me) . "/XXX/'\">Làm tiếp</button></li>";
                                                } else {
                                                    echo "<li><button type='button' style='text-transform:uppercase;' class='btn btn-primary btn-xs bg-danger-400' onclick=\"location.href='http://localhost/www/TDUONG/luyenthi/xem-truoc/" . encodeData($data["ID_DE"], $me) . "/XXX/'\">Làm bài</button></li>";
                                                }
                                             echo"</ul>
                                        </td>";
                                        if($data["out_time"] == "0000-00-00 00:00:00") {
                                            echo"<td class='text-center'>Chưa hoàn thành</td>";
                                        } else {
                                            echo "<td class='text-center'>" . formatDateTime($data["out_time"]) . "</td>";
                                        }
                                        echo"<td class='text-center'>$data[mota]</td>
                                        <td class='text-center'>$data[diem]</td>
                                        <td class='text-center'>".formatTimeBack($data["time"]/1000)."</td>
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
