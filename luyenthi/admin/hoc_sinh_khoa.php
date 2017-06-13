
    <?php include_once "../include/top.php"; ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Học sinh</span> - Danh sách học sinh bị khóa trắc nghiệm</h4>-->
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
                            <h5 class="panel-title">Danh sách học sinh bị khóa trắc nghiệm</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table datatable-basic datatable-show-all table-striped" id="list-danh-sach">
                                <thead>
                                <tr>
                                    <th class='text-center'>STT</th>
                                    <th class='text-center'>Mã số</th>
                                    <th class='text-center'>Họ tên</th>
                                    <th style="width:30%;" class='text-center'>Lý do</th>
                                    <th class='text-center'>Ngày khóa</th>
                                    <th class='text-center'></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $dem = 1;
                                    $db = new Hoc_Sinh();
                                    $result = $db->getListHocSinhKhoa($lmID);
                                    while ($data = $result->fetch_assoc()) {
                                        echo"<tr>
                                            <td class='text-center' style='width: 10%;'>" . $dem . "</td>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data[cmt]/' target='_blank'>$data[cmt]</a></td>
                                            <td class='text-center'><a href='" . formatFacebook($data["facebook"]) . "' target='_blank'>$data[fullname]</a></td>
                                            <td class='text-center'>$data[mota]</td>
                                            <td class='text-center'>".formatDateTime($data["date_lock"])."</td>
                                            <td class='text-center'><a href='javascript:void(0)' class='btn btn-primary btn-xs bg-blue-400 mo-khoa' data-hsID='$data[ID_HS]'>Mở khóa</a></td>
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
                $("table#list-danh-sach").delegate("a.mo-khoa","click",function() {
                    if(confirm("Bạn có chắc chắn mở khóa cho học sinh này?")) {
                        var me = $(this).closest("tr");
                        var hsID = $(this).attr("data-hsID");
                        if ($.isNumeric(hsID) && hsID != 0) {
                            $.ajax({
                                async: false,
                                data: "hsID=" + hsID + "&lmID=<?php echo $lmID; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-hoc-sinh/",
                                success: function (result) {
                                    new PNotify({
                                        title: 'Khóa trắc nghiệm',
                                        text: 'Đã mở khóa cho học sinh này!',
                                        icon: 'icon-menu6'
                                    });
                                    me.remove();
                                }
                            });
                        }
                    }
                });
            });
        </script>
