
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
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Cài đặt</span> - Mở khóa chuyên đề môn --><?php //echo $lop_mon_name; ?><!--</h4>-->
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
                            <h5 class="panel-title">Mở khóa chuyên đề môn <?php echo $lop_mon_name; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;" class="text-center">Mã</th>
                                        <th style="width: 30%;" class="text-center">Tên chuyên đề cha</th>
                                        <th style="width: 10%;" class="text-center"></th>
                                        <th class="text-center">Chuyên đề con</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $db2 = new Chuyen_De();
                                        $result2 = $db2->getChuyenDeDadByMon($monID);
                                        while($data2 = $result2->fetch_assoc()) {
                                            echo"<tr>
                                                <td class='text-center'>$data2[maso]</td>
                                                <td>$data2[name]</td>
                                                <td class='text-center'>
                                                    <ul class='icons-list'>
                                                        <li class='unlock-dad' data-dadID='$data2[ID_DAD]'><a href='javascript:void(0)' title='Mở chuyên đề'><i class='icon-check'></i></a></li>
                                                        <li> | </li>
                                                        <li class='lock-dad' data-dadID='$data2[ID_DAD]'><a href='javascript:void(0)' title='Khóa chuyên đề'><i class='icon-lock'></i></a></li>
                                                    </ul>
                                                </td>
                                                <td class='text-center'>
                                                    <table class='table list-chuyen-de'>
                                                        <tbody>";
                                                    $result3 = $db2->getChuyenDeConByDadCheck($data2["ID_DAD"],$lmID);
                                                    while($data3 = $result3->fetch_assoc()) {
                                                        echo"<tr>
                                                            <td style='width: 20%;' class='text-center'>$data3[maso]</td>
                                                            <td>$data3[name]</td>
                                                            <td style='width: 10%;' class='text-center'>
                                                                <div class='checkbox'>
                                                                    <label>";
                                                                        if(isset($data3["ID_STT"])) {
                                                                            echo "<input type='checkbox' checked='checked' class='control-primary lock-con' data-cdID='$data3[ID_CD]'>";
                                                                        } else {
                                                                            echo "<input type='checkbox' class='control-primary unlock-con' data-cdID='$data3[ID_CD]'>";
                                                                        }
                                                                    echo"</label>
                                                                </div>
                                                            </td>
                                                        </tr>";
                                                    }
                                                echo"</tbody>
                                                    </table>
                                                </td>
                                            </tr>";
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

                $("li.unlock-dad").click(function () {
                    var dadID = $(this).attr("data-dadID");
                    if(valid_id(dadID)) {
                        $.ajax({
                            async: false,
                            data: "dadID2=" + dadID + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Chuyên đề lớn',
                                    text: 'Đã được mở khóa cho <?php echo $lop_mon_name; ?>',
                                    icon: 'icon-menu6'
                                });
                                setTimeout(function () {
                                    location.reload();
                                },1000);
                            }
                        });
                    }
                });

                $("li.lock-dad").click(function () {
                    var dadID = $(this).attr("data-dadID");
                    if(valid_id(dadID)) {
                        $.ajax({
                            async: false,
                            data: "dadID3=" + dadID + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Chuyên đề lớn',
                                    text: 'Đã khóa cho <?php echo $lop_mon_name; ?>',
                                    icon: 'icon-menu6'
                                });
                                setTimeout(function () {
                                    location.reload();
                                },1000);
                            }
                        });
                    }
                });

                $("table.list-chuyen-de").delegate("input.unlock-con","click",function() {
                    me = $(this);
                    var cdID = $(this).attr("data-cdID");
                    if(valid_id(cdID)) {
                        $.ajax({
                            async: false,
                            data: "cdID2=" + cdID + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Chuyên đề con',
                                    text: 'Đã được mở khóa cho <?php echo $lop_mon_name; ?>',
                                    icon: 'icon-menu6'
                                });
                                me.removeClass("unlock-con").addClass("lock-con");
                            }
                        });
                    }
                });

                $("table.list-chuyen-de").delegate("input.lock-con","click",function() {
                    me = $(this);
                    var cdID = $(this).attr("data-cdID");
                    if(valid_id(cdID)) {
                        $.ajax({
                            async: false,
                            data: "cdID3=" + cdID + "&lmID=<?php echo $lmID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-chuyende/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Chuyên đề con',
                                    text: 'Đã khóa cho <?php echo $lop_mon_name; ?>',
                                    icon: 'icon-menu6'
                                });
                                me.removeClass("lock-con").addClass("unlock-con");
                            }
                        });
                    }
                });
            });
        </script>
