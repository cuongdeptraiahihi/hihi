
    <?php include_once "../include/top.php"; ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cài đặt</span> - Loại đề</h4>-->
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
                            <h5 class="panel-title">Cài đặt loại đề</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <td></td>
                                        <td><input type='text' class='form-control' id="add-name" placeholder="Tên loại đề" /></td>
                                        <td><input type='text' class='form-control' id="add-mota" placeholder="Mô tả" /></td>
                                        <td class="text-center"><button type="button" id="add-ok" class="btn btn-primary btn-sm bg-brown-400">Thêm</button></td>
                                    </tr>
                                    <tr>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Đề</th>
                                        <th class="text-center">Mô tả</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $dem=1;
                                    $db = new Loai_De();
                                    $result = $db->getLoaiDe();
                                    while($data=$result->fetch_assoc()) {
                                        echo"<tr>
                                            <td class='text-center'>$dem</td>
                                            <td class='text-center' style='width: 20%;'><input type='text' class='form-control edit-name' value='$data[name]' /></td>
                                            <td class='text-center'><input type='text' class='form-control edit-mota' value='$data[mota]' /></td>
                                            <td class='text-center'>
                                                <ul class='icons-list'>
                                                    <li class='edit-ok' data-dID='$data[ID_D]'><a href='javascript:void(0)'><i class='icon-pencil3'></i></a></li>
                                                    <li> | </li>
                                                    <li class='del-ok' data-dID='$data[ID_D]'><a href='javascript:void(0)'><i class='icon-trash'></i></a></li>
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

                $("button#add-ok").click(function () {
                    var name = $("#add-name").val().trim();
                    var mota = $("#add-mota").val().trim();
                    if(name != "" && mota != "") {
                        $.ajax({
                            async: false,
                            data: "name=" + name + "&mota=" + mota,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-khac/",
                            success: function (result) {
                                location.reload();
                            }
                        });
                    } else {
                        new PNotify({
                            title: 'Thêm loại đề',
                            text: 'Vui lòng nhập Tên và Mô tả',
                            icon: 'icon-menu6'
                        });
                    }
                });

                $("li.edit-ok").click(function() {
                    var dID = $(this).attr("data-dID");
                    var name = $(this).closest("tr").find("td input.edit-name").val();
                    var mota = $(this).closest("tr").find("td input.edit-mota").val();
                    if(name != "" && mota != "" && valid_id(dID)) {
                        $.ajax({
                            async: false,
                            data: "name2=" + name + "&mota2=" + mota + "&dID2=" + dID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-khac/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Loại đề ' + name,
                                    text: 'Nội dung đã được cập nhật với tên: ' + name + ' và mô tả: ' + mota,
                                    icon: 'icon-menu6'
                                });
                            }
                        });
                    } else {
                        new PNotify({
                            title: 'Thêm loại đề',
                            text: 'Vui lòng nhập Tên và Mô tả',
                            icon: 'icon-menu6'
                        });
                    }
                });

                $("li.del-ok").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        del_tr = $(this).closest("tr");
                        var dID = $(this).attr("data-dID");
                        if (valid_id(dID)) {
                            $.ajax({
                                async: false,
                                data: "dID=" + dID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-khac/",
                                success: function (result) {
                                    del_tr.remove();
                                    new PNotify({
                                        title: 'Thêm loại đề',
                                        text: 'Đã xóa thành công!',
                                        icon: 'icon-menu6'
                                    });
                                }
                            });
                        }
                    }
                });
            });
        </script>
