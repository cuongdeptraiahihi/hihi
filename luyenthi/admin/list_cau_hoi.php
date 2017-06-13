
    <?php include_once "../include/top.php"; ?>
    <?php
        if(isset($_GET["lmID"]) && is_numeric($_GET["lmID"])) {
            $lmID = $_GET["lmID"];
        }
        $monID = (new Mon_Hoc())->getMonOfLop($lmID);
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Câu hỏi</span> - Danh sách câu hỏi</h4>-->
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
                            <h5 class="panel-title">Danh sách câu hỏi</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table" id="list-search">
                                <tr>
                                    <td colspan="2" style="width: 85%;"><input type="text" id="search-cau-hoi" class="form-control" placeholder="Mã câu hỏi (H07-05a-1a, D08-06a-3c)" /></td>
                                    <td><a id="del-ok" class='btn btn-primary btn-xs bg-primary-400' style='color:#FFF;' href='javascript:void(0)'>Xóa hàng loạt</a></td>
                                </tr>
                            </table>
                            <table class="table datatable-basic datatable-show-all table-striped" id="list-danh-sach">
                                <thead>
                                <tr>
                                    <th class="text-center">STT</th>
                                    <th class="text-center" style="width: 15%;">Mã số</th>
                                    <th class="text-center">Ngày up</th>
                                    <th class="text-center">Đã check?</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $stt=1;
                                    $db = new Cau_Hoi();
                                    $result = $db->getCauHoiByMon($monID);
                                    while($data=$result->fetch_assoc()) {
                                        if($data["ready"] == 1) {
                                            echo"<tr>";
                                        } else {
                                            echo"<tr style='background-color: #ffffb2;'>";
                                        }
                                        echo"<td class='text-center'>$stt</td>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-cau-hoi/$data[ID_C]/' target='_blank'>$data[maso]</a></td>
                                            <td class='text-center'>".formatDateTime($data["ngay"])."</td>
                                            <td class='text-center'>".$db->formatDone($data["done"])."</td>
                                            <td class='text-center'>".formatStatus($data["ready"])."</td>
                                            <td class='text-center'>
                                                <ul class='icons-list'>
                                                    <li class='dropdown'>
                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                            <i class='icon-menu9'></i>
                                                        </a>
            
                                                        <ul class='dropdown-menu dropdown-menu-right'>";
                                                        if($data["done"] == 1) {
                                                            echo"<li class='uncheck-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-cross3'></i> Un Check</a></li>";
                                                        } else {
                                                            echo "<li class='check-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-check'></i> Check</a></li>";
                                                        }
                                                        echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$data[ID_C]/' target='_blank'><i class='icon-pencil3'></i> Sửa</a></li>";
                                                        if($data["ready"] == 1) {
                                                            echo"<li class='del-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-cross3'></i> Ẩn</a></li>";
                                                        } else {
                                                            echo"<li class='show-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-check'></i> Hiện</a></li>";
                                                        }
                                                        echo"<li class='delete-cau-hoi'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-trash'></i> Xóa</a></li>";
                                                        echo"</ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>";
                                        $stt++;
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
//                $("body").addClass("sidebar-xs");
                $("#del-ok").click(function () {
                    var maso = $("#search-cau-hoi").val().trim();
                    if(maso != "" && confirm("Bạn có chắn chắn? Xóa tất cả trong các đề!")) {
                        $.ajax({
                            async: false,
                            data: "maso_del=" + maso,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    }
                });
                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.uncheck-cau-hoi","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(confirm("Bạn có chắc chắn không? Dữ liệu đúng sai và thời gian làm câu này của học sinh sẽ đc reset!")) {
                        if (valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "cID0=" + id + "&action=show",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                                success: function (result) {
                                    cur_value = 100;
                                    del_tr.html(result);
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.check-cau-hoi","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "cID2=" + id + "&action=show",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                cur_value = 100;
                                del_tr.html(result);
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.show-cau-hoi","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "cID1=" + id + "&action=show",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                cur_value = 100;
                                del_tr.removeAttr("style").html(result);
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.del-cau-hoi","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "cID=" + id + "&action=show",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                cur_value = 100;
                                del_tr.css("background-color","#ffffb2").html(result);
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.delete-cau-hoi","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(confirm("Bạn có chắc chắn xóa câu hỏi này?")) {
                        if(valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "cID4=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                                success: function (result) {
                                    result = result.trim();
                                    if (result == "ok") {
                                        del_tr.remove();
                                        new PNotify({
                                            title: 'Câu hỏi',
                                            text: 'Đã xóa thành công!',
                                            icon: 'icon-menu6'
                                        });
                                    } else {
                                        new PNotify({
                                            title: 'Câu hỏi',
                                            text: 'Không thể xóa câu hỏi này vì nó đã được sử dụng!',
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
                $("#search-cau-hoi").typeWatch({
                    captureLength: 3,
                    callback: function (value) {
                        $.ajax({
                            async: true,
                            data: "search_ma_cau_hoi=" + value,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                $("table#list-search tr.tr-search").remove();
                                $("table#list-search").append(result);
                            }
                        });
                    }
                });
            });
        </script>
