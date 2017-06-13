
    <?php include_once "../include/top.php"; ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - Mô tả đề</h4>-->
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
                $db = new Loai_De();
                if(isset($_POST["submit-edit-ok"])) {
                    $result = $db->getMotaWithLoaiDe($lmID);
                    while($data = $result->fetch_assoc()) {
                        if(isset($_POST["submit-content-".$data["ID_D"]]) && !empty($_POST["submit-content-".$data["ID_D"]])) {
                            $content = $_POST["submit-content-".$data["ID_D"]];
                            $db->addMotaDe($content,$data["ID_D"],$lmID);
                        }
                    }
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/mo-ta-de/");
                    exit();
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/mo-ta-de/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                <div class="form-cau-hoi">
                    <div class="col-lg-8">
                        <?php
                            $result = $db->getMotaWithLoaiDe($lmID);
                            while($data = $result->fetch_assoc()) {
                        ?>
                        <div class="panel-heading">
                            <h5 class="panel-title"><?php echo "Đề ".$data["name"]." - ".$data["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <textarea rows="7" cols="5" name="submit-content-<?php echo $data["ID_D"]; ?>" style="resize: vertical;" class="form-control chon-dang-text" placeholder="Nội dung phần lưu ý"><?php echo $data["content"]; ?></textarea>
                                        </div>
                                    </div>
                                </fieldset>
<!--                                </form>-->
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-heading">
                            <h5 class="panel-title">Mô tả</h5>
                        </div>
                        <!-- Sales stats -->
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <p class="content-group">+ Môn <?php echo (new Mon_Hoc())->getNameMonLop($lmID); ?></p>
                                <p class="content-group">+ Mỗi lưu ý bắt đầu bằng dấu "+"</p>
                                <p class="content-group">+ Từ nào bôi đậm thì cho vào trong <code>&lt;b&gt;&lt;/b&gt;</code> như: <code>&lt;b&gt;bị phạt&lt;/b&gt;</code></p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-xs-12" style="text-align: right;">
                                            <button type="submit" name="submit-edit-ok" class="btn btn-primary btn-sm bg-blue-400 edit-ok">Lưu</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /main form -->
                </form>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
            });
        </script>
