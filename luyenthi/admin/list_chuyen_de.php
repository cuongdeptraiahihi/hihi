
    <?php include_once "../include/top.php"; ?>

    <?php
        $db = new Mon_Hoc();
        $lop_mon_name = $db->getNameMonLop($lmID);
        $monID = $db->getMonOfLop($lmID);
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cài đặt</span> - Chuyên đề môn --><?php //echo $lop_mon_name; ?><!--</h4>-->
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

                <!-- Dashboard content -->
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Chuyên đề môn <?php echo $lop_mon_name; ?></h5>
                        </div>
                        <div class="panel panel-flat table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td><input type='text' class='form-control' id="add-maso" placeholder="Mã số" /></td>
                                        <td colspan="2"><input type='text' class='form-control' id="add-name" placeholder="Tên chuyên đề cha" /></td>
                                        <td class="text-center" style="text-align: left;"><button type="button" id="add-ok" class="btn btn-primary btn-sm bg-brown-400">Thêm</button></td>
                                    </tr>
                                    <tr>
                                        <th style="width: 15%;" class="text-center">Mã</th>
                                        <th colspan="3" class="text-center">Chuyên đề</th>
                                        <th style="width: 10%;" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $db2 = new Chuyen_De();
                                        $result2 = $db2->getChuyenDeDadByMon($monID);
                                        while($data2 = $result2->fetch_assoc()) {
                                            echo"<tr>
                                                <td><input type='text' class='form-control edit-maso' value='$data2[maso]' /></td>
                                                <td colspan='3'><input type='text' class='form-control edit-name' value='$data2[name]' /></td>
                                                <td class='text-center'>
                                                    <ul class='icons-list'>
                                                        <li class='add-con' data-dad='$data2[ID_DAD]'><a href='javascript:void(0)'><i class='icon-plus3'></i></a></li>
                                                        <li> | </li>
                                                        <li class='dropdown'>
                                                            <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                                <i class='icon-menu9'></i>
                                                            </a>
                                                            
                                                            <ul class='dropdown-menu dropdown-menu-right'>
                                                                <li class='edit-dad' data-dad='$data2[ID_DAD]'><a href='javascript:void(0)'><i class='icon-pencil3'></i> Sửa</a></li>
                                                                <li class='del-dad' data-dad='$data2[ID_DAD]'><a href='javascript:void(0)'><i class='icon-trash'></i> Xóa</a></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr style='background-color: #fcfcfc;display: none;' class='tr-them-con-$data2[ID_DAD]'>
                                                <td></td>
                                                <td><input type='text' class='form-control add-con-maso' placeholder='Mã số' /></td>
                                                <td colspan='2'><input type='text' class='form-control add-con-name' placeholder='Tên chuyên đề con' /></td>
                                                <td class='text-center'><button type='button' class='btn btn-primary btn-sm bg-brown-400 add-con-ok' data-dadID='$data2[ID_DAD]' data-dadID2='0'>+</button></td>
                                            </tr>";
                                            $result3 = $db2->getChuyenDeConByDad($data2["ID_DAD"]);
                                            while($data3 = $result3->fetch_assoc()) {
                                                echo"<tr>
                                                    <td></td>
                                                    <td style='width: 15%;'><input type='text' class='form-control edit-maso' value='$data3[maso]' /></td>
                                                    <td colspan='2'><input type='text' class='form-control edit-name' value='$data3[name]' /></td>
                                                    <td class='text-center'>
                                                        <ul class='icons-list'>
                                                            <li class='add-con-more' data-cdID='$data3[ID_CD]'><a href='javascript:void(0)'><i class='icon-plus3'></i></a></li>
                                                            <li> | </li>
                                                            <li class='dropdown'>
                                                                <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                                    <i class='icon-menu9'></i>
                                                                </a>
                                                                
                                                                <ul class='dropdown-menu dropdown-menu-right'>
                                                                    <li><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-chuyen-de/$data3[ID_CD]/'><i class='icon-eye'></i> Xem danh sách</a></li>
                                                                    
                                                                    <li class='edit-con-ok' data-cdID='$data3[ID_CD]'><a href='javascript:void(0)'><i class='icon-pencil3'></i> Sửa</a></li>
                                                                    <li class='del-con-ok' data-cdID='$data3[ID_CD]'><a href='javascript:void(0)'><i class='icon-trash'></i> Xóa</a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr style='background-color: #fcfcfc;display: none;' class='tr-them-con2-$data3[ID_CD]'>
                                                    <td colspan='2'></td>
                                                    <td><input type='text' class='form-control add-con-maso' placeholder='Mã số' /></td>
                                                    <td><input type='text' class='form-control add-con-name' placeholder='Tên chuyên đề con' /></td>
                                                    <td class='text-center'><button type='button' class='btn btn-primary btn-sm bg-brown-400 add-con-ok' data-dadID='0' data-dadID2='$data3[ID_CD]'>+</button></td>
                                                </tr>";
                                                $result4 = $db2->getChuyenDeConByDad2($data3["ID_CD"]);
                                                while($data4 = $result4->fetch_assoc()) {
                                                    echo"<tr>
                                                        <td colspan='2'></td>
                                                        <td style='width: 15%;'><input type='text' class='form-control edit-maso' value='$data4[maso]' /></td>
                                                        <td><input type='text' class='form-control edit-name' value='$data4[name]' /></td>
                                                        <td class='text-center'>
                                                            <ul class='icons-list'>
                                                                <li class='dropdown'>
                                                                    <a href='#' class='dropdown-toggle' data-toggle='dropdown'>
                                                                        <i class='icon-menu9'></i>
                                                                    </a>
                                                                    
                                                                    <ul class='dropdown-menu dropdown-menu-right'>
                                                                        <li><a href='http://localhost/www/TDUONG/luyenthi/admin/xem-chuyen-de/$data4[ID_CD]/'><i class='icon-eye'></i> Xem danh sách</a></li>
                                                                        <li class='edit-con-ok' data-cdID='$data4[ID_CD]'><a href='javascript:void(0)'><i class='icon-pencil3'></i> Sửa</a></li>
                                                                        <li class='del-con-ok' data-cdID='$data4[ID_CD]'><a href='javascript:void(0)'><i class='icon-trash'></i> Xóa</a></li>
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>";
                                                }
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <!-- /dashboard content -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {

                $("button#add-ok").click(function () {
                    var maso = $("#add-maso").val().trim();
                    var name = $("#add-name").val().trim();
                    if(maso != "" && name != "") {
                        $.ajax({
                            async: false,
                            data: "maso=" + maso + "&name=" + name + "&monID=<?php echo $monID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                result = result.trim();
                                if(result == "ok") {
                                    location.reload();
                                } else {
                                    alert("Mã số đã tồn tại!");
                                }
                            }
                        });
                    } else {
                        new PNotify({
                            title: 'Thêm chuyên đề',
                            text: 'Vui lòng nhập Mã số và tên Chuyên đề',
                            icon: 'icon-menu6'
                        });
                    }
                });

                $("button.add-con-ok").click(function () {
                    del_tr = $(this).closest("tr");
                    var dadID = $(this).attr("data-dadID");
                    var dadID2 = $(this).attr("data-dadID2");
                    var maso = del_tr.find("td input.add-con-maso").val().trim();
                    var name = del_tr.find("td input.add-con-name").val().trim();
                    if(maso != "" && name != "" && $.isNumeric(dadID) && $.isNumeric(dadID2)) {
                        $.ajax({
                            async: false,
                            data: "maso=" + maso + "&name=" + name + "&dadID=" + dadID + "&dadID2=" + dadID2,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                result = result.trim();
                                if(result == "ok") {
                                    location.reload();
                                } else {
                                    alert("Mã số đã tồn tại!");
                                }
                            }
                        });
                    } else {
                        new PNotify({
                            title: 'Thêm chuyên đề',
                            text: 'Vui lòng nhập Mã số và tên Chuyên đề',
                            icon: 'icon-menu6'
                        });
                    }
                });

                $("li.edit-dad").click(function() {
                    var dadID = $(this).attr("data-dad");
                    var maso = $(this).closest("tr").find("td input.edit-maso").val();
                    var name = $(this).closest("tr").find("td input.edit-name").val();
                    if(maso != "" && name != "" && valid_id(dadID)) {
                        $.ajax({
                            async: false,
                            data: "maso0=" + maso + "&name0=" + name + "&dadID0=" + dadID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                result = result.trim();
                                if(result == "ok") {
                                    new PNotify({
                                        title: 'Chuyên đề lớn ' + maso,
                                        text: 'Nội dung đã được cập nhật với tên: ' + name,
                                        icon: 'icon-menu6'
                                    });
                                } else {
                                    alert("Mã số đã tồn tại!");
                                }
                            }
                        });
                    } else {
                        new PNotify({
                            title: 'Thêm chuyên đề',
                            text: 'Vui lòng nhập Mã số và tên Chuyên đề',
                            icon: 'icon-menu6'
                        });
                    }
                });

                $("li.add-con").click(function () {
                    $(this).closest("table").find("tr.tr-them-con-" + $(this).attr("data-dad")).toggle();
                });

                $("li.add-con-more").click(function () {
                    $(this).closest("table").find("tr.tr-them-con2-" + $(this).attr("data-cdID")).toggle();
                });

                $("li.del-dad").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        del_tr = $(this).closest("tr");
                        var dadID = $(this).attr("data-dad");
                        if (valid_id(dadID)) {
                            $.ajax({
                                async: false,
                                data: "dadID1=" + dadID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                                success: function (result) {
                                    result = result.trim();
                                    if (result == "ok") {
                                        del_tr.remove();
                                        new PNotify({
                                            title: 'Chuyên đề lớn',
                                            text: 'Đã xóa thành công!',
                                            icon: 'icon-menu6'
                                        });
                                    } else {
                                        new PNotify({
                                            title: 'Chuyên đề lớn',
                                            text: 'Không thể xóa chuyên đề này do chuyên đề này chứa các chuyên đề con',
                                            icon: 'icon-menu6'
                                        });
                                    }
                                }
                            });
                        }
                    }
                });

                $("li.edit-con-ok").click(function() {
                    var cdID = $(this).attr("data-cdID");
                    var maso = $(this).closest("tr").find("td input.edit-maso").val();
                    var name = $(this).closest("tr").find("td input.edit-name").val();
                    if(maso != "" && name != "" && valid_id(cdID)) {
                        $.ajax({
                            async: false,
                            data: "maso0=" + maso + "&name0=" + name + "&cdID0=" + cdID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                result = result.trim();
                                if(result == "ok") {
                                    new PNotify({
                                        title: 'Chuyên đề con ' + maso,
                                        text: 'Nội dung đã được cập nhật với tên: ' + name,
                                        icon: 'icon-menu6'
                                    });
                                } else {
                                    alert("Mã số đã tồn tại!");
                                }
                            }
                        });
                    } else {
                        new PNotify({
                            title: 'Thêm chuyên đề',
                            text: 'Vui lòng nhập Mã số và tên Chuyên đề',
                            icon: 'icon-menu6'
                        });
                    }
                });

                $("li.del-con-ok").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        del_tr = $(this).closest("tr");
                        var cdID = $(this).attr("data-cdID");
                        if (valid_id(cdID)) {
                            $.ajax({
                                async: false,
                                data: "cdID1=" + cdID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                                success: function (result) {
                                    result = result.trim();
                                    if (result == "ok") {
                                        del_tr.remove();
                                        new PNotify({
                                            title: 'Chuyên đề con',
                                            text: 'Đã xóa thành công!',
                                            icon: 'icon-menu6'
                                        });
                                    }
                                }
                            });
                        }
                    }
                });
            });
        </script>
