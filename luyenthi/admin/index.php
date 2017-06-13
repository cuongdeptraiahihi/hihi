
    <?php include_once "../include/top.php"; ?>

    <?php
        $me = md5("123456");
        $db = new De_Thi();
        $db3 = new Thong_Ke();
        $db2 = new Cau_Hoi();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Trang chủ</span> - Quản lý </h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "../include/sidebar.php"; ?>
            <?php
                $error = false;
                $error_msg = "";
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/trang-chu/" class="form-horizontal" method="get">
                <!-- Dashboard content -->
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Đề kiểm tra</h5>
                        </div>
                        <div class="panel panel-flat table-responsive">
                            <table class="table table-striped list-de" id="table-kiem-tra">
                                <thead>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Bài kiểm tra</th>
                                        <th class="text-center">SL đề</th>
                                        <th class="text-center">Hoàn thành / Chưa làm</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $dem = 1;
                                    $result = $db->getNhomDeNotHideByType("kiem-tra",$lmID,6);
                                    while($data = $result->fetch_assoc()) {
                                        $num = $db3->countHocSinhDoneLam($data["ID_N"], $lmID);
                                        $deID = $data["ID_DE"];
                                        if(isset($_SESSION["new_de"]) && $deID == $_SESSION["new_de"]) {
                                            echo"<tr style='background-color: #ffffb2;' data-nID='$data[ID_N]' data-loai='$data[loai]'>";
                                            $_SESSION["new_de"] = 0;
                                        } else {
                                            echo"<tr data-nID='$data[ID_N]' data-loai='$data[loai]'>";
                                        }
                                        echo"<td class='text-center'>$dem</td>";
//                                        <td class='text-center'><a class='btn btn-primary btn-xs bg-blue-400 sl-kiemtra' style='color:#FFF;' href='javascript:void(0)'>Tải</a></td>
                                        if($num != 0) {
                                            echo "<td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$deID/'>$data[mota]</a></td>";
                                        } else {
                                            echo "<td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$deID/'>$data[mota]</a></td>";
                                        }
                                        echo"<td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data[ID_N]/' target='_blank' title='Danh sách đề'>$data[dem]</a></td>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$data[ID_N]/' style='color:green;font-weight:600;'>".$num."</a> / <a class='btn btn-primary btn-xs bg-danger-400' style='color:#FFF;' href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/chua-lam/$data[ID_N]/'>Xem</a></td>
                                            <td class='text-center'>".formatStatus($data["public"]);
                                                if($data["public"]==1) {
                                                    echo"<a href='javascript:void(0)' class='lock-de' title='Ấn để khóa đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-unlocked2'></i></a>";
                                                } else {
                                                    echo"<a href='javascript:void(0)' class='open-de' title='Ấn để mở đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-lock5'></i></a>";
                                                }
                                            echo"</td>
                                            <td class='text-center'>
                                                <ul class='icons-list'>
                                                    <li class='dropdown'>
                                                        <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                            <i class='icon-menu9'></i>
                                                        </a>
                                                        
                                                        <ul class='dropdown-menu dropdown-menu-right'>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-de/".$data["ID_N"]."/' title='Sửa đề'><i class='icon-pencil'></i> Sửa đề</a></li>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thu-bai/".$data["ID_N"]."/' target='_blank' class='thu-bai' title='Ấn để khóa đề'><i class='icon-unlocked2'></i> Khóa và thu bài</a></li>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$deID/' target='_blank' title='Đề gốc'><i class='icon-file-text'></i> Đề gốc</a></li>
                                                            <li><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/$data[ID_N]/' title='In ấn'><i class='icon-printer'></i> In ấn</a></li>";
//                                                            if($data["is_sai"]==1) {
//                                                                echo"<li class='unerror-de'><a href='javascript:void(0)' data-nID='$data[ID_N]' class='bg-danger-600' style='color:#FFF;'> <i class='icon-bug2'></i>Đề có lỗi</a></li>";
//                                                            } else {
//                                                                echo"<li class='error-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-bug2'></i>Đề có lỗi</a></li>";
//                                                                echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
//                                                            }
                                                            echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
                                                        echo"</ul>
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
                        <div class="panel-heading">
                            <h5 class="panel-title">Các câu sai</h5>
                        </div>
                        <div class="panel panel-flat table-responsive">
                            <table class="table table-striped" id="cau-sai">
                                <thead>
                                    <tr>
                                        <th class="text-center">Mã</th>
                                        <th class="text-center">Bình luận</th>
                                        <th class="text-center">Gần nhất</th>
                                        <th class="text-center">Trạng thái</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $result = $db3->getCauHoiMostBinhLuan((new Mon_Hoc())->getMonOfLop($lmID));
                                    while($data = $result->fetch_assoc()) {
                                        echo"<tr>
                                            <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-cau-hoi/$data[ID_C]/' target='_blank'>$data[maso]</a></td>
                                            <td class='text-center'>$data[dem]</td>
                                            <td class='text-center'>".formatDateTime($data["datetime"])."</td>
                                            <td class='text-center'>".$db2->formatDone($data["done"])."</td>
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
                                                            echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$data[ID_C]/'><i class='icon-pencil3'></i> Sửa</a></li>
                                                            <li class='xoa-binh-luan'><a href='javascript:void(0)' data-cID='$data[ID_C]'><i class='icon-trash'></i> Xóa bình luận</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Yêu cầu kiểm tra lại bài</h5>
                        </div>
                        <div class="panel panel-flat table-responsive">
                            <table class="table table-striped" id="bao-sai">
                                <thead>
                                <tr>
                                    <th class="text-center">Đề thi</th>
                                    <th style="width: 50%;" class="text-center">Mô tả</th>
                                    <th class="text-center">Mã số</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $result = $db->getYeuCauXemLai("bao-de-sai", $lmID);
                                while($data = $result->fetch_assoc()) {
                                    echo"<tr>
                                        <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$data[ID_DE]/' target='_blank'>$data[mota]</a></td>
                                        <td class='text-center'>".nl2br($data["content"])."</td>
                                        <td class='text-center'><a href='" . formatFacebook($data["facebook"]) . "' target='_blank'>$data[cmt]</a></td>
                                        <td class='text-center'>
                                            <a href='http://localhost/www/TDUONG/luyenthi/admin/ket-qua-nhap-diem-hs/$data[ID_DE]/$data[ID_HS]/' target='_blank' style='color:#37474F'><i class='icon-copy'></i></a> |
                                            <a href='http://localhost/www/TDUONG/luyenthi/admin/ket-qua-lam-de-hs/$data[ID_DE]/$data[note]/' target='_blank' style='color:#37474F' data-oID='$data[ID_O]'><i class='icon-eye'></i></a> | 
                                            <a class='xoa-bao-sai' href='javascript:void(0)' style='color:#37474F' data-oID='$data[ID_O]'><i class='icon-trash'></i></a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
<!--                        <div class="panel panel-flat">-->
<!--                            <div class="panel-heading">-->
<!--                                <h5 class="panel-title">Đề chuyên đề</h5>-->
<!--                            </div>-->
<!--                            <table class="table datatable-basic datatable-show-all table-striped list-de" id="table-chuyen-de">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th class="text-center">STT</th>-->
<!--                                    <th class="text-center">Thời điểm mở</th>-->
<!--                                    <th class="text-center">Chuyên đề</th>-->
<!--                                    <th class="text-center">SL đề</th>-->
<!--                                    <th class="text-center">Hoàn thành / Chưa làm</th>-->
<!--                                    <th class="text-center">Còn lại</th>-->
<!--                                    <th class="text-center">Trạng thái</th>-->
<!--                                    <th class="text-center"></th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                --><?php
//                                $dem = 1;
//                                $result = $db->getNhomDeNotHideByType("chuyen-de",$lmID,3);
//                                while($data = $result->fetch_assoc()) {
//                                    $num = $db3->countHocSinhDoneLam($data["ID_N"],$lmID);
//                                    $deID = $data["ID_DE"];
//                                    if(isset($_SESSION["new_de"]) && $deID == $_SESSION["new_de"]) {
//                                        echo"<tr style='background-color: #ffffb2;' data-nID='$data[ID_N]' data-loai='0'>";
//                                        $_SESSION["new_de"] = 0;
//                                    } else {
//                                        echo"<tr data-nID='$data[ID_N]' data-loai='0'>";
//                                    }
//                                    echo"<td class='text-center'>$dem</td>";
//                                    if($data["date_open"] == "0000-00-00 00:00:00") {
//                                        echo"<td class='text-center'>Chưa mở</td>";
//                                    } else {
//                                        echo"<td class='text-center'>".formatDateFromTime($data["date_open"])."</td>";
//                                    }
////                                    <td class='text-center'><a class='btn btn-primary btn-xs bg-blue-400 sl-chuyende' style='color:#FFF;' href='javascript:void(0)'>Tải</a></td>
//                                    if($num != 0) {
//                                        echo "<td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/$deID/'>$data[mota]</a></td>";
//                                    } else {
//                                        echo "<td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$deID/'>$data[mota]</a></td>";
//                                    }
//                                    echo"<td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/danh-sach-de-thi/$data[ID_N]/' target='_blank' title='Danh sách đề'>$data[dem]</a></td>
//                                        <td class='text-center'><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/ket-thuc/$data[ID_N]/' style='color:green;font-weight:600;'>".$num."</a> / <a class='btn btn-primary btn-xs bg-blue-400' style='color:#FFF;' href='http://localhost/www/TDUONG/luyenthi/admin/thong-ke-de-thi/chua-lam/$data[ID_N]/'>Xem</a></td>
//                                        <td class='text-center'>".$db->getTimeConLai($data["date_open"],$data["date_close"],$data["public"])."</td>
//                                        <td class='text-center'>".formatStatus($data["public"]);
//                                            if($data["public"]==1) {
//                                                echo"<a href='javascript:void(0)' class='lock-de' title='Ấn để khóa đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-unlocked2'></i></a>";
//                                            } else {
//                                                echo"<a href='javascript:void(0)' class='open-de' title='Ấn để mở đề' data-nID='$data[ID_N]' style='color:#37474F;margin-left:10px;'><i class='icon-lock5'></i></a>";
//                                            }
//                                        echo"</td>
//                                        <td class='text-center'>
//                                            <ul class='icons-list'>
//                                                <li class='dropdown'>
//                                                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
//                                                        <i class='icon-menu9'></i>
//                                                    </a>
//
//                                                    <ul class='dropdown-menu dropdown-menu-right'>
//                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-de/".$data["ID_N"]."/' title='Sửa đề'><i class='icon-pencil'></i> Sửa đề</a></li>
//                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/thu-bai/".$data["ID_N"]."/' target='_blank' class='thu-bai' title='Ấn để khóa đề'><i class='icon-unlocked2'></i> Khóa và thu bài</a></li>
//                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-de/$deID/' target='_blank' title='Đề gốc'><i class='icon-file-text'></i> Đề gốc</a></li>
//                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/$data[ID_N]/' title='In ấn'><i class='icon-printer'></i> In ấn</a></li>";
////                                                        if($data["is_sai"]==1) {
////                                                            echo"<li class='unerror-de'><a href='javascript:void(0)' data-nID='$data[ID_N]' class='bg-danger-600' style='color:#FFF;'> <i class='icon-bug2'></i>Đề có lỗi</a></li>";
////                                                        } else {
////                                                            echo"<li class='error-de'><a href='javascript:void(0)' data-nID='$data[ID_N]'><i class='icon-bug2'></i>Đề có lỗi</a></li>";
////                                                            echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
////                                                        }
//                                                        echo"<li><a href='http://localhost/www/TDUONG/luyenthi/admin/thong-bao/$deID/' target='_blank' title='Thông báo'><i class='icon-bell3'></i> Thông báo</a></li>
//                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/sua-diem/".$data["ID_N"]."/' class='sua-diem' title='Sửa điểm'><i class='icon-warning'></i> Sửa điểm</a></li>";
//                                                    echo"</ul>
//                                                </li>
//                                            </ul>
//                                        </td>
//                                    </tr>";
//                                    $dem++;
//                                }
//                                ?>
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                        <div class="panel panel-flat">-->
<!--                            <div class="panel-heading">-->
<!--                                <h5 class="panel-title">Test hiển thị công thức</h5>-->
<!--                            </div>-->
<!--                            <div class="panel-body">-->
<!--                                <p class="content-group">-->
<!--                                    + Đầu vào là ngôn ngữ LaTex-->
<!--                                    <br />+ Nguồn trang test: <a href="https://www.mathjax.org/" target="_blank">Mathjax</a>-->
<!--                                </p>-->
<!--                                <fieldset class="content-group">-->
<!--                                    <div class="form-group">-->
<!--                                        <div class="col-lg-6">-->
<!--                                            <textarea rows="5" cols="5" id="content-cau-hoi" style="resize: vertical;" class="form-control" placeholder="Nội dung câu hỏi"></textarea>-->
<!--                                        </div>-->
<!--                                        <div class="col-lg-6" id="show-cau-hoi">-->
<!---->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </fieldset>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>
                <!-- /dashboard content -->
                </form>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#cau-sai tbody").delegate("tr td ul.dropdown-menu li.uncheck-cau-hoi","click",function() {
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
                                    location.reload();
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
                $("#cau-sai tbody").delegate("tr td ul.dropdown-menu li.check-cau-hoi","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "cID2=" + id + "&action=show",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#cau-sai tbody").delegate("tr td ul.dropdown-menu li.xoa-binh-luan","click",function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).find("> a").attr("data-cID");
                    if(valid_id(id)) {
                        $.ajax({
                            async: false,
                            data: "cID5=" + id + "&action=xoa-binh-luan",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                del_tr.fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $(".xoa-bao-sai").click(function() {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).attr("data-oID");
                    if(confirm("Bạn có chắc chắn?")) {
                        if(valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "oID=" + id + "&action=xoa-bao-sai",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                                success: function (result) {
                                    del_tr.fadeOut("fast");
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
                $("a.thu-bai").click(function () {
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

//                $("a.sl-kiemtra").click(function() {
//                    $(this).html("Đang tải");
//                    var me = $(this).closest("tr");
//                    var nID = me.attr("data-nID");
//                    var loai = me.attr("data-loai");
//                    $.ajax({
//                        async: true,
//                        data: "nID_count=" + nID + "&lmID=<?php //echo $lmID; ?>//&loai=" + loai,
//                        type: "post",
//                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
//                        success: function (result) {
//                            me.find("td:eq(3)").html(result);
//                        }
//                    });
//                });
//
//                $("a.sl-chuyende").click(function() {
//                    $(this).html("Đang tải");
//                    var me = $(this).closest("tr");
//                    var nID = me.attr("data-nID");
//                    $.ajax({
//                        async: true,
//                        data: "nIDcd_count=" + nID + "&lmID=<?php //echo $lmID; ?>//&numall=<?php //echo $db3->countHocSinhAll($lmID); ?>//",
//                        type: "post",
//                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
//                        success: function (result) {
//                            me.find("td:eq(3)").html(result);
//                        }
//                    });
//                });

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

                $("#content-cau-hoi").typeWatch({
                    captureLength: 3,
                    callback: function (me) {
                        me = me.replace(/\n/g,"<br />");
                        me = me.replace(/'/g,"1o1");
                        me = me.replace(/<\//g,"2o2");
                        me = me.replace(/</g,"3o3");
                        me = me.replace(/>/g,"4o4");
                        me = me.replace(/\+/g,"5o5");
                        me = me.replace(/&/g,"6o6");
                        $("#show-cau-hoi").html("<iframe class='embed-responsive-item' style='height: 90px;width: 100%;border:0;' src='http://localhost/www/TDUONG/luyenthi/admin/ajax/formula.php?formula=" + me + "'></iframe>");
                    }
                });

                $("a.sua-diem").click(function () {
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
                
                $("a.open-de").click(function () {
                    var nID = $(this).attr("data-nID");
                    if($.isNumeric(nID) && nID!=0) {
                        $.ajax({
                            async: false,
                            data: "nID=" + nID + "&action=public",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    }
                })

                $("a.lock-de").click(function () {
                    var nID = $(this).attr("data-nID");
                    if($.isNumeric(nID) && nID!=0) {
                        $.ajax({
                            async: false,
                            data: "nID=" + nID + "&action=lock",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-de/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    }
                })

                $("li.error-de").click(function() {
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

                $("li.unerror-de").click(function() {
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
            });
        </script>
