
    <?php include_once "include/top_hoc_sinh.php"; ?>
    <?php
        $me = md5("123456");
        $db = new Vao_Thi();
        $db2 = new De_Thi();
    ?>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Lịch sử vào thi</h4>
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
                            <div class="panel-heading">
                                <h5 class="panel-title">Dữ liệu vào thi</h5>
                            </div>
                                <table class="table noSwipe" id="list-danh-sach">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Chuyên đề</th>
                                        <th class="text-center">Thời điểm vào</th>
                                        <th class="text-center">Thời điểm ra</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $dem = 1;
                                        $result = $db->getLishSuVaoThi($hsID, $lmID);
                                        while($data = $result->fetch_assoc()) {
                                            echo"<tr>
                                                <td class='text-center'>$data[mota]</td>
                                                <td class='text-center'>".formatDateTime($data["in_time"])."</td>";
                                                if($data["out_time"] == "0000-00-00 00:00:00") {
                                                    echo"<td class='text-center'>Bạn chưa nộp bài</td>";
                                                } else {
                                                    echo"<td class='text-center'>".formatDateTime($data["out_time"])."</td>";
                                                }
                                                echo"<td class='text-center'>".formatStatus($data["public"])."</td>
                                            </tr>";
                                            $dem++;
                                        }
                                    ?>
                                    </tbody>
                                </table>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">Lịch sử làm lại</h5>
                            </div>
                            <table class="table noSwipe" id="list-lam-lai">
                                <thead>
                                <tr>
                                    <th class="text-center">Chuyên đề</th>
                                    <th class="text-center">Điểm</th>
                                    <th class="text-center">Thời gian làm</th>
                                    <th class="text-center">Thời điểm làm lại</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $result = $db->getLishSuLamLai($hsID,$lmID);
                                    while($data = $result->fetch_assoc()) {
                                        echo"<tr>
                                            <td class='text-center'>$data[mota]</td>
                                            <td class='text-center'>$data[diem]</td>
                                            <td class='text-center'>".formatTimeBack($data["time"])."</td>
                                            <td class='text-center'>".formatDateTime($data["datetime"])."</td>
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
            });
        </script>
