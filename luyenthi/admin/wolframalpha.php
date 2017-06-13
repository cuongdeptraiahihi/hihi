
    <?php include_once "../include/top.php"; ?>
    <?php include_once "../../wolframalpha/WolframAlphaEngine.php"; ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tiện ích</span> - WolframAlpha</h4>-->
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
                $ct = $response = NULL;
                $appID = "EJR373-9X94RRJ966";
                if(isset($_POST["tinh-ct"])) {
                    if(isset($_POST["input-ct"]) && !empty($_POST["input-ct"])) {
                        $ct = $_POST["input-ct"];

                        $qArgs = array();
                        $engine = new WolframAlphaEngine( $appID );
                        $response = $engine->getResults($ct, $qArgs);
                        if ( $response->isError() ) {
                            $error = true;
                            $error_msg = "Không có dữ liệu trả về!";
                        }
                    } else {
                        $error = true;
                        $error_msg = "Vui lòng nhập công thức!";
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/wolframalpha/" class="form-horizontal" method="post">
                <!-- Dashboard content -->
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">WolframAlpha.com</h5>
                        </div>
                        <div class="panel panel-flat">

                            <div class="panel-body">
                                <p class="content-group">
                                    + Nhập vào công thức bạn muốn tra cứu
                                    <br />+ Ví dụ: 3x^2+5x+6, pi,...
                                </p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <input type="text" name="input-ct" id="input-ct" class="form-control" value="<?php echo $ct; ?>" placeholder="Nhập công thức" />
                                            <button type="submit" name="tinh-ct" class="btn btn-primary btn-sm bg-brown-400" style="float:right;margin-top: 10px;" id="tinh-ct">Tính toán</button>
                                        </div>
                                        <?php
                                            if(!$error && $ct) {
                                                if (count($response->getPods()) > 0) {
                                                    echo"<div class='col-md-9' id='result-ct'>
                                                        <table class='table table-framed'>";
                                                            foreach ( $response->getPods() as $pod ) {
                                                                echo"<tr class='bg-blue'><th>".$pod->attributes['title']."</th></tr>";
                                                                foreach ( $pod->getSubpods() as $subpod ) {
                                                                   echo "<tr><td><img src='".$subpod->image->attributes['src']."'></td></tr>";
                                                                }
                                                            }
                                                        echo"</table>
                                                    </div>";
                                                }
                                            } else {
                                                echo"<div class='col-md-9'>$error_msg</div>";
                                            }
                                        ?>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
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

            });
        </script>
