
    <?php include_once "../include/top.php"; ?>
    <?php
        $me=md5("123456");
        if(isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
            $nhom = $_GET["nhom"];
        } else {
            $nhom = 0;
        }
        $db = new De_Thi();
        $deID = $db->getDeThiMainByNhom($nhom);
        $result0 = $db->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Danh sách đề thi</h4>-->
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
                            <h5 class="panel-title"><?php echo $data0["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table datatable-basic datatable-show-all table-striped" id="list-danh-sach">
                                <thead>
                                <tr>
                                    <th class='text-center'>Ngày</th>
                                    <th class='text-center'>Mã đề</th>
                                    <th class='text-center'>Thời gian (ph)</th>
                                    <th class='text-center'>Đề</th>
                                    <th class='text-center'>SL câu</th>
                                    <th class='text-center'>SL HS</th>
                                    <th class='text-center'>Trạng thái</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $num_arr = array();
                                    $result = $db->getDeThiByNhomDemCau($nhom);
                                    while($data = $result->fetch_assoc()) {
                                        $num_arr[$data["ID_DE"]] = $data["dem"];
                                    }

                                    $result = $db->getDeThiByNhomDemHocSinh($nhom);
                                    while($data = $result->fetch_assoc()) {
                                        if($data["main"] == 0) {
                                            echo"<tr>";
                                        } else {
                                            echo"<tr style='background-color: #ffffb2;'>";
                                        }
                                        echo"<td class='text-center'>".formatDateTime($data["ngay"])."</td>
                                            <td class='text-center'>$data[maso]</td>
                                            <td class='text-center'>$data[time]</td>
                                            <td class='text-center'>$data[name]</td>
                                            <td class='text-center'>".$num_arr[$data["ID_DE"]]."</td>
                                            <td class='text-center'>$data[dem2]</td>
                                            <td class='text-center'>" . formatStatus($data["public"]) ."</td>
                                            <td class='text-center'>
                                                <ul class='icons-list'>
                                                    <li class='dropdown'>
                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                            <i class='icon-menu9'></i>
                                                        </a>
            
                                                        <ul class='dropdown-menu dropdown-menu-right'>";
                                                            echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$data[ID_DE]/'><i class='icon-eye'></i> Xem nhanh</a></li>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$data[ID_DE]/'><i class='icon-chart'></i> Thống kê chi tiết</a></li>
                                                            <li class='ma-de' data-toggle='modal' data-target='#modal_default_made'><a href='javascript:void(0)' data-deID='$data[ID_DE]'><i class='icon-pencil'></i> Sửa mã đề</a></li>
                                                            <li class='del-de'><a href='javascript:void(0)' data-deID='$data[ID_DE]'><i class='icon-trash'></i> Xóa</a></li>";
//                                                        if($data["public"] == 1) {
//                                                            echo"<li class='uncheck-de'><a href='javascript:void(0)' data-deID='$data[ID_DE]'><i class='icon-cross3'></i> Ẩn</a></li>";
//                                                        } else {
//                                                            echo "<li class='check-de'><a href='javascript:void(0)' data-deID='$data[ID_DE]'><i class='icon-check'></i> Public</a></li>";
//                                                        }
                                                        echo"</ul>
                                                    </li>
                                                </ul>
                                            </td>
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

        <div id='modal_default_made' class='modal fade'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-body'>
                        <h6 class='text-semibold'>Sửa mã đề</h6>
                        <p><input type="text" id="add-ma" class="form-control" value="30" placeholder="Nhập 1 số" /></p>
                        <p>Mã đề sẽ được kiểm tra xem đã tồn tại hay chưa?</p>
                    </div>

                    <div class='modal-footer'>
                        <button type='button' class='btn btn-link' data-dismiss='modal'>Đóng</button>
                        <button type='button' class='btn btn-danger' data-dismiss='modal' id='add-ok'>Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.del-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-deID");
                    if(confirm("Bạn có chắc chắn xóa câu hỏi này?")) {
                        if(valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "deID=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                                success: function (result) {
                                    result = result.trim();
                                    if (result == "ok") {
                                        del_tr.remove();
                                        new PNotify({
                                            title: 'Đề thi',
                                            text: 'Đã xóa thành công!',
                                            icon: 'icon-menu6'
                                        });
                                    } else {
                                        new PNotify({
                                            title: 'Đề thi',
                                            text: 'Không thể xóa đề thi này!',
                                            icon: 'icon-menu6'
                                        });
                                    }
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });

                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.ma-de","click",function() {
                    var id = $(this).find("> a").attr("data-deID");
                    var made = $(this).closest("tr").find("td:eq(1)").text();
                    if(valid_id(id) && valid_id(made)) {
                        $("#add-ma").val(made);
                        $("#add-ok").attr("data-deID", id);
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("#add-ok").click(function () {
                    var deID = $(this).attr("data-deID");
                    var ma = $("#add-ma").val().trim();
                    if($.isNumeric(deID) && $.isNumeric(ma)) {
                        $.ajax({
                            async: true,
                            data: "deID_ma=" + deID + "&ma=" + ma + "&nhom=<?php echo $nhom; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function(result) {
                                result = result.trim();
                                console.log(result);
                                if(result == "ok") {
                                    new PNotify({
                                        title: 'Sửa mã đề',
                                        text: 'Đã sửa thành ' + ma + ". Reload để cập nhật!",
                                        icon: 'icon-checkmark'
                                    });
                                } else {
                                    alert("Đã có mã đề trùng! " + result);
                                }
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
            });
        </script>
