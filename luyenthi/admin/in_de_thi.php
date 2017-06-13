
    <?php include_once "../include/top.php"; ?>
    <?php
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
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Đề thi</span> - In ấn</h4>-->
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
                $luuy = $goc = 0;
                $start = $end = 1;
                if(isset($_POST["in-de-word"])) {
                    if(isset($_POST["submit-luuy"])) {
                        $luuy = 1;
                    }
                    if(isset($_POST["submit-goc"])) {
                        $goc = 1;
                    }
                    if(isset($_POST["submit-start"]) && !empty($_POST["submit-start"])) {
                        $start = $_POST["submit-start"];
                    }
                    if(isset($_POST["submit-end"]) && !empty($_POST["submit-end"])) {
                        $end = $_POST["submit-end"];
                    }
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/word/$nhom/$luuy/$goc/$start/$end/");
                    exit();
                }
                if(isset($_POST["in-de-web"])) {
                    if(isset($_POST["submit-luuy"])) {
                        $luuy = 1;
                    }
                    if(isset($_POST["submit-goc"])) {
                        $goc = 1;
                    }
                    if(isset($_POST["submit-start"]) && !empty($_POST["submit-start"])) {
                        $start = $_POST["submit-start"];
                    }
                    if(isset($_POST["submit-end"]) && !empty($_POST["submit-end"])) {
                        $end = $_POST["submit-end"];
                    }
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/web/$nhom/$luuy/$goc/$start/$end/");
                    exit();
                }
                if(isset($_POST["in-chuyen-de"])) {
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/chuyen-de/$nhom/");
                    exit();
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/<?php echo $nhom; ?>/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                <div class="form-cau-hoi">
                    <div class="col-lg-8">
                        <div class="panel-heading">
                            <h5 class="panel-title">In ấn</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <p class="content-group">Phần lưu ý sẽ được hiển thị ở đầu mỗi đề thi</p>
<!--                                <form class="form-horizontal" action="#">-->
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <textarea rows="7" cols="5" disabled="disabled" style="resize: vertical;" class="form-control" placeholder="Nội dung phần lưu ý"><?php echo (new Loai_De())->getMotaDe($data0["loai"], $lmID); ?></textarea>
                                            <a href="http://localhost/www/TDUONG/luyenthi/admin/mo-ta-de/">Chỉnh sửa</a>
                                        </div>
                                    </div>
                                </fieldset>
<!--                                </form>-->
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">Link in đề thi nhanh</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <?php
                                $num = $db->countDeThiByNhom($nhom);
                                $total = 10;
                                for($i = 1;$i <= $num; $i+=$total) {
                                    echo"<p class='content-group'><a href='http://localhost/www/TDUONG/luyenthi/admin/in-de-thi/web/".$nhom."/1/0/$i/".($i+$total-1)."/' target='_blank'>Từ $i -> ".($i+$total-1)."</a></p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-heading">
                            <h5 class="panel-title">In đề thi</h5>
                        </div>
                        <div class="panel panel-flat">

                            <div class="panel-body">
<!--                                <form class="form-horizontal" action="#">-->
                                    <fieldset class="content-group">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="control-primary" checked="checked" name="submit-luuy">
                                                        In trang lưu ý đầu
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" class="control-primary" name="submit-goc">
                                                        Chỉ in đề gốc
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="display: none;">
                                            <div class="col-lg-6">
                                                <label>
                                                    <input type="text" name="submit-start" class="form-control" placeholder="STT đầu" />
                                                </label>
                                            </div>
                                            <div class="col-lg-6">
                                                <label>
                                                    <input type="text" name="submit-end" class="form-control" placeholder="STT cuối" />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12" style="text-align: right;">
<!--                                                <button type="submit" name="in-de-word" class="btn btn-primary btn-sm bg-blue-400">In WORD</button>-->
                                                <button type="submit" name="in-de-web" class="btn btn-primary btn-sm bg-blue-400">In WEB</button>
                                            </div>
                                        </div>
                                    </fieldset>
<!--                                </form>-->
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h5 class="panel-title">In chuyên đề</h5>
                        </div>
                        <div class="panel panel-flat">

                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12" style="text-align: right;">
                                            <button type="submit" name="in-chuyen-de" class="btn btn-primary btn-sm bg-danger-400">In Chuyên đề</button>
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
                $("span.hidden-xs").remove();
            });
        </script>
