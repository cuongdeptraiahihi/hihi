
    <?php include_once "../include/top.php"; ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cài đặt</span> - Danh sách môn học</h4>-->
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
                <form>
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Cài đặt môn học</h5>
                        </div>
                        <div class="panel panel-flat">

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center">Môn</th>
                                    <th class="text-center">Sử dụng CT</th>
                                    <th class="text-center">Số lượng chuyên đề</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $dem=1;
                                    $db = new Mon_Hoc();
                                    $result = $db->getAllMon();
                                    while($data=$result->fetch_assoc()) {
                                        echo"<tr>
                                            <td class='text-center'>$dem</td>
                                            <td class='text-center'>$data[name]</td>
                                            <td>
                                                <div class='checkbox'>
                                                    <label>";
                                                        if($data["ct"] == 1) {
                                                            echo"<input type='checkbox' checked='checked' class='control-primary change-ct-unuse' data-monID='$data[ID_MON]' name='submit-dap-an-e'>
                                                                Có";
                                                        } else {
                                                            echo"<input type='checkbox' class='control-primary change-ct-use' data-monID='$data[ID_MON]' name='submit-dap-an-e'>
                                                                Không";
                                                        }
                                                    echo"</label>
                                                </div>";
                                            echo"</td>
                                            <td class='text-center'>".$db->countChuyenDe($data["ID_MON"])."</td>
                                            <td class='text-center'>
                                                <ul class='icons-list'>
                                                    <li class='dropdown'>
                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                            <i class='icon-menu9'></i>
                                                        </a>
            
                                                        <ul class='dropdown-menu dropdown-menu-right'>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-cau-hoi/$data[ID_MON]/'><i class='icon-eye'></i> Xem câu hỏi</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
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

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {

                $("input.change-ct-use").click(function () {
                    var monID = $(this).attr("data-monID");
                    if(valid_id(monID)) {
                        $.ajax({
                            async: false,
                            data: "monID=" + monID + "&is_use=1",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-mon/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Môn học',
                                    text: 'Nội dung đã được cập nhật!',
                                    icon: 'icon-menu6'
                                });
                            }
                        });
                    }
                });

                $("input.change-ct-unuse").click(function () {
                    var monID = $(this).attr("data-monID");
                    if(valid_id(monID)) {
                        $.ajax({
                            async: false,
                            data: "monID=" + monID + "&is_use=0",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-mon/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Môn học',
                                    text: 'Nội dung đã được cập nhật!',
                                    icon: 'icon-menu6'
                                });
                            }
                        });
                    }
                });
            });
        </script>
