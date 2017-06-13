
    <?php include_once "../include/top.php"; ?>
    <?php
        $me = md5("123456");
        if(isset($_GET["type"])) {
            $type = $_GET["type"];
        } else {
            $type = "X";
        }
        $db = new De_Thi();
        $db2 = new Thong_Ke();
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
                            <h5 class="panel-title">Danh sách đề <?php echo $db->formatNhomDe($type); ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table datatable-basic datatable-show-all table-striped list-danh-sach">
                                <thead>
                                <tr>
                                    <th class='text-center'>STT</th>
                                    <th class='text-center'>Chuyên đề</th>
                                    <th class='text-center'>SL câu</th>
                                    <th class="text-center">Hoàn thành / Chưa làm</th>
                                    <th class='text-center'>Trạng thái</th>
                                    <th class='text-center'>Tự luyện</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $dem=1;
                                    $result = $db->getNhomDeThiByMon($lmID, $type);
                                    while($data=$result->fetch_assoc()) {
                                        $num = "Đã làm";
                                        if($dem <= 3) {
                                            $num = $db2->countHocSinhDoneLam($data["ID_N"], $lmID);
                                        }
                                        echo"<tr>
                                            <td class='text-center'>$dem</td>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$data[ID_DE]/'>$data[mota]</a></td>
                                            <td class='text-center'>$data[cau]</td>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$data[ID_N]/' style='color:green;font-weight:600;'>".$num."</a> / <a class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;' href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/chua-lam/$data[ID_N]/'>Xem</a></td>
                                            <td class='text-center'>" . formatStatus($data["public"]);
                                                if($data["public"]==1) {
                                                    echo"<a href='javascript:void(0)' class='lock-de' title='Ấn để khóa đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-unlocked2'></i></a>";
                                                } else {
                                                    echo"<a href='javascript:void(0)' class='public-de' title='Ấn để mở đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-lock5'></i></a>";
                                                }
                                            echo"</td>
                                            <td class='text-center'>";
                                                if($data["allow"]==1) {
                                                    echo"<a href='javascript:void(0)' class='notallow-de' title='Ấn để không cho phép' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-checkmark3'></i></a>";
                                                } else {
                                                    echo"<a href='javascript:void(0)' class='allow-de' title='Ấn để cho phép' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-question7'></i></a>";
                                                }
                                            echo"</td>
                                            <td class='text-center'>
                                                <ul class='icons-list'>
                                                    <li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-de/".$data["ID_N"]."/' title='Sửa đề'><i class='icon-pencil'></i></a></li>
                                                    <li style='margin: 0 10px 0 10px;'><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/$data[ID_N]/'><i class='icon-printer'></i></a></li>
                                                    <li class='dropdown'>
                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                            <i class='icon-menu9'></i>
                                                        </a>
            
                                                        <ul class='dropdown-menu dropdown-menu-right'>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$data[ID_DE]/' target='_blank' title='Thống kê đề thi'><i class='icon-chart'></i> Thống kê</a></li>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data[ID_N]/' title='Danh sách đề'><i class='icon-calendar'></i> Danh sách đề</a></li>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thu-bai/".$data["ID_N"]."/' target='_blank' class='thu-bai' title='Ấn để khóa đề'><i class='icon-unlocked2'></i> Khóa và thu bài</a></li>";
//                                                            if($data["is_sai"]==1) {
//                                                                echo "<li class='unerror-de'><a href='javascript:void(0)' class='bg-danger-600' style='color:#FFF;' data-nID='$data[ID_N]'><i class='icon-bug2' ></i> Đề bị lỗi</a></li>";
//                                                            } else {
//                                                                echo"<li class='error-de'><a href ='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-bug2'></i >Đề bị lỗi</a ></li >";
                                                                echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' target='_blank' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
//                                                            }
//                                                            echo"<li class='hide-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-cross3'></i> Ẩn</a></li>";
                                                            echo"<li class='del-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-trash'></i> Xóa</a></li>
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
<!--                        <div class="panel panel-flat">-->
<!--                            <div class="panel-heading">-->
<!--                                <h5 class="panel-title">Danh sách đề chuyên đề</h5>-->
<!--                            </div>-->
<!--                            <table class="table datatable-basic datatable-show-all table-striped list-danh-sach">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th class='text-center'>STT</th>-->
<!--                                    <th class='text-center'>Ngày tạo</th>-->
<!--                                    <th class='text-center'>Chuyên đề</th>-->
<!--                                    <th class='text-center'>SL câu</th>-->
<!--                                    <th class='text-center'>Còn lại</th>-->
<!--                                    <th class='text-center'>Trạng thái</th>-->
<!--                                    <th class='text-center'>Tự luyện</th>-->
<!--                                    <th class="text-center"></th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                --><?php
//                                $dem=1;
//                                $result = $db->getNhomDeThiByMon($lmID, "chuyen-de");
//                                while($data=$result->fetch_assoc()) {
//                                    echo"<tr>
//                                            <td class='text-center'>$dem</td>
//                                            <td class='text-center'>".formatDateFromTime($data["datetime"])."</td>
//                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$data[ID_DE]/'>$data[mota]</a></td>
//                                            <td class='text-center'>$data[cau]</td>";
//                                    if($data["type"] != "kiem-tra") {
//                                        echo "<td class='text-center'>" . $db->getTimeConLai($data["date_open"], $data["date_close"], $data["public"]) . "</td>";
//                                    } else {
//                                        echo "<td class='text-center'>-</td>";
//                                    }
//                                    echo"<td class='text-center'>" . formatStatus($data["public"]);
//                                    if($data["public"]==1) {
//                                        echo"<a href='javascript:void(0)' class='lock-de' title='Ấn để khóa đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-unlocked2'></i></a>";
//                                    } else {
//                                        echo"<a href='javascript:void(0)' class='public-de' title='Ấn để mở đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-lock5'></i></a>";
//                                    }
//                                    echo"</td>
//                                            <td class='text-center'>";
//                                    if($data["allow"]==1) {
//                                        echo"<a href='javascript:void(0)' class='notallow-de' title='Ấn để không cho phép' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-checkmark3'></i></a>";
//                                    } else {
//                                        echo"<a href='javascript:void(0)' class='allow-de' title='Ấn để cho phép' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-question7'></i></a>";
//                                    }
//                                    echo"</td>
//                                            <td class='text-center'>
//                                                <ul class='icons-list'>
//                                                    <li class='dropdown'>
//                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
//                                                            <i class='icon-menu9'></i>
//                                                        </a>
//
//                                                        <ul class='dropdown-menu dropdown-menu-right'>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$data[ID_N]/' title='Kết quả làm đê'><i class='icon-table'></i> Kết quả làm đề</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-de/".$data["ID_N"]."/' title='Sửa đề'><i class='icon-pencil'></i> Sửa đề</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data[ID_N]/' title='Danh sách đề'><i class='icon-calendar'></i> Danh sách đề</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$data[ID_DE]/' target='_blank' title='Thống kê đề thi'><i class='icon-chart'></i> Thống kê</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/$data[ID_N]/'><i class='icon-printer'></i> Xuất / In</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thu-bai/".$data["ID_N"]."/' target='_blank' class='thu-bai' title='Ấn để khóa đề'><i class='icon-unlocked2'></i> Khóa và thu bài</a></li>";
////                                                            if($data["is_sai"]==1) {
////                                                                echo "<li class='unerror-de'><a href='javascript:void(0)' class='bg-danger-600' style='color:#FFF;' data-nID='$data[ID_N]'><i class='icon-bug2' ></i> Đề bị lỗi</a></li>";
////                                                            } else {
////                                                                echo"<li class='error-de'><a href ='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-bug2'></i >Đề bị lỗi</a ></li >";
//                                                            echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' target='_blank' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
////                                                            }
////                                                            echo"<li class='hide-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-cross3'></i> Ẩn</a></li>";
//                                                            echo"<li class='del-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-trash'></i> Xóa</a></li>
//                                                        </ul>
//                                                    </li>
//                                                </ul>
//                                            </td>
//                                        </tr>";
//                                    $dem++;
//                                }
//                                ?>
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                        <div class="panel panel-flat">-->
<!--                            <div class="panel-heading">-->
<!--                                <h5 class="panel-title">Danh sách đề thi thử</h5>-->
<!--                            </div>-->
<!--                            <table class="table datatable-basic datatable-show-all table-striped list-danh-sach">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th class='text-center'>STT</th>-->
<!--                                    <th class='text-center'>Ngày tạo</th>-->
<!--                                    <th class='text-center'>Đề thi</th>-->
<!--                                    <th class='text-center'>SL câu</th>-->
<!--                                    <th class='text-center'>Trạng thái</th>-->
<!--                                    <th class="text-center"></th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                --><?php
//                                $dem=1;
//                                $result = $db->getNhomDeThiByMon($lmID, "thi-thu");
//                                while($data=$result->fetch_assoc()) {
//                                    echo"<tr>
//                                            <td class='text-center'>$dem</td>
//                                            <td class='text-center'>".formatDateFromTime($data["datetime"])."</td>
//                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$data[ID_DE]/'>$data[mota]</a></td>
//                                            <td class='text-center'>$data[cau]</td>";
//                                            echo"<td class='text-center'>" . formatStatus($data["public"]);
//                                            if($data["public"]==1) {
//                                                echo"<a href='javascript:void(0)' class='lock-de' title='Ấn để khóa đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-unlocked2'></i></a>";
//                                            } else {
//                                                echo"<a href='javascript:void(0)' class='public-de' title='Ấn để mở đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-lock5'></i></a>";
//                                            }
//                                            echo"</td>
//                                            <td class='text-center'>
//                                                <ul class='icons-list'>
//                                                    <li class='dropdown'>
//                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
//                                                            <i class='icon-menu9'></i>
//                                                        </a>
//
//                                                        <ul class='dropdown-menu dropdown-menu-right'>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$data[ID_N]/' title='Kết quả làm đê'><i class='icon-table'></i> Kết quả làm đề</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-de/".$data["ID_N"]."/' title='Sửa đề'><i class='icon-pencil'></i> Sửa đề</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data[ID_N]/' title='Danh sách đề'><i class='icon-calendar'></i> Danh sách đề</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$data[ID_DE]/' target='_blank' title='Thống kê đề thi'><i class='icon-chart'></i> Thống kê</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/$data[ID_N]/'><i class='icon-printer'></i> Xuất / In</a></li>
//                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thu-bai/".$data["ID_N"]."/' target='_blank' class='thu-bai' title='Ấn để khóa đề'><i class='icon-unlocked2'></i> Khóa và thu bài</a></li>";
////                                                            if($data["is_sai"]==1) {
////                                                                echo "<li class='unerror-de'><a href='javascript:void(0)' class='bg-danger-600' style='color:#FFF;' data-nID='$data[ID_N]'><i class='icon-bug2' ></i> Đề bị lỗi</a></li>";
////                                                            } else {
////                                                                echo"<li class='error-de'><a href ='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-bug2'></i >Đề bị lỗi</a ></li >";
//                                                                echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' target='_blank' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
////                                                            }
////                                                            echo"<li class='hide-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-cross3'></i> Ẩn</a></li>";
//                                                                echo"<li class='del-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-trash'></i> Xóa</a></li>
//                                                        </ul>
//                                                    </li>
//                                                </ul>
//                                            </td>
//                                        </tr>";
//                                    $dem++;
//                                }
//                                ?>
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
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
                $(".list-danh-sach tbody").delegate("tr td ul.dropdown-menu li a.thu-bai","click",function() {
                    if(confirm("Bạn có chắc chắn khóa đề và thu bài của tất cả các học sinh?")) {
                        new PNotify({
                            title: 'Đề thi',
                            text: 'Đang xử lý!',
                            icon: 'icon-menu6'
                        });
                        return true;
                    } else {
                        return false;
                    }
                });
                $(".list-danh-sach tbody").delegate("tr td ul.dropdown-menu li a.sua-diem","click",function() {
                    if(confirm("Bạn có chắc chắn cập nhật lại điểm của các em học sinh đã nộp bài?")) {
                        new PNotify({
                            title: 'Đề thi',
                            text: 'Đang xử lý!',
                            icon: 'icon-menu6'
                        });
                        return true;
                    } else {
                        return false;
                    }
                });
                $(".list-danh-sach tbody").delegate("tr td a.public-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).attr("data-nID");
                    ajax_code(id, "public", del_tr);
                });
                $(".list-danh-sach tbody").delegate("tr td a.lock-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).attr("data-nID");
                    ajax_code(id, "lock", del_tr);
                });
                $(".list-danh-sach tbody").delegate("tr td a.allow-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).attr("data-nID");
                    ajax_code(id, "allow", del_tr);
                });
                $(".list-danh-sach tbody").delegate("tr td a.notallow-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).attr("data-nID");
                    ajax_code(id, "notallow", del_tr);
                });
                $(".list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.hide-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-nID");
                    ajax_code(id, "hide", del_tr);
                });
                $(".list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.del-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-nID");
                    if(confirm("Bạn có chắc chắn xóa câu hỏi này?")) {
                        if(valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "nID1=" + id,
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
                $(".list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.error-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-nID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "nID2=" + id,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    }
                });
                $(".list-danh-sach tbody").delegate("tr td ul.dropdown-menu li.unerror-de","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-nID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "nID3=" + id,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    }
                });

                function ajax_code(id, action, del_tr) {
                    if (valid_id(id)) {
                        new PNotify({
                            title: 'Đề thi',
                            text: 'Đang xử lý!',
                            icon: 'icon-menu6'
                        });
                        $.ajax({
                            async: false,
                            data: "nID=" + id + "&action=" + action,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                setTimeout(function () {
                                    location.reload();
                                },1000);
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                }
            });
        </script>
