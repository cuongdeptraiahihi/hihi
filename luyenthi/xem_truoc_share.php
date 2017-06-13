<?php
    ob_start();
    session_start();
    unset($_SESSION);
    session_destroy();
    require_once("model/model.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUYỆN THI TRẮC NGHIỆM</title>
    <link href="http://localhost/www/TDUONG/luyenthi/assets/favicon.ico" rel="shortcut icon" type="image/x-icon"/>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/bootstrap.min.js"></script>
<!--    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>-->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/iscroll.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/uniform.min.js"></script>

    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/switchery.min.js"></script>
<!--    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/ui/moment/moment.min.js"></script>-->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/uploaders/fileinput.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/tables/datatables/datatables.min.js"></script>

    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/app.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/form_inputs.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/form_checkboxes_radios.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery.typewatch.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/jquery.countdown.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery.countTo.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/components_notifications_pnotify.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/datatables_advanced.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/canvasjs.min.js"></script>

    <style type="text/css">
        a.canvasjs-chart-credit {display: none;}
        table#list-bai-thi {table-layout: fixed;}
        /*table#list-bai-thi tr th, table#list-bai-thi tr td {font-family: 'FontMe';}*/
        button#btn-support {position: fixed;z-index: 99;right: 0;top: 50px;}
        button#btn-support a {color:#FFF;}
        .mjx-mtd {text-align: left !important;}
        i {cursor: pointer;}
        @media (max-width: 769px) {
            .sidebar.sidebar-main {
                position: fixed;
                left: -80%;
                top: 0;
                width: 79%;
                height: 100%;
                z-index: 99;
            }
            .sidebar-content {
                height: 100%;
            }
            .navbar-header .navbar-nav {
                float: left;
                display: none !important;
                margin-top: 2px;
            }
            .navbar-brand {
                float: none;
                display: block;
                width: 200px;
                margin: auto;
            }
            .navbar-header {
                text-align: center;
            }
            .page-container, .panel-body {
                padding: 10px;
            }
            .panel-heading {
                padding-top: 0;
            }
            #my-dock {
                position: fixed;
                z-index: 99;
                bottom: 0;
                margin-bottom: 0;
                left: 0;
                width: 100%;
                text-align: center;
            }
            #my-dock > .panel-body {
                padding: 5px 0 5px 0;
            }
            th.small-hidden, td.small-hidden {
                display: none;
            }
            .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {padding: 0;}
        }
    </style>

    <!-- /theme JS files -->
    <script>
        function valid_id(id) {
            if(id != 0 && $.isNumeric(id)) {
                return true;
            } else {
                return false;
            }
        };
        function valid_json(json) {
            if (/^[\],:{}\s]*$/.test(json.replace(/\\["\\\/bfnrtu]/g, '@').
                replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                return true;

            }else{
                return false;
                //the json is not ok

            }
        }
        $(document).ready(function() {
            $("table#list-bai-thi tr td:last-child").css("overflow","auto");
            $("i.position-left").css("cursor","pointer");
            $("i.position-left").click(function() {
                history.go(-1);
            });

            var mon = $("ul#mon-access li.active a").html();
            if(mon) {
                $("a#mon-show span strong").html(mon);
            } else {
                $("a#mon-show").hide();
            }
        });
    </script>
</head>
<body style="margin-top: 10px !important;">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="http://localhost/www/TDUONG/luyenthi/trang-chu/">Bgo Education</a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-bubble2"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">

<!--        --><?php //$link = "https://www.messenger.com/t/hotroloptoan"; ?>

        <ul class="nav navbar-nav navbar-right">
<!--            <li class="dropdown dropdown-user">-->
<!--                <a class="dropdown-toggle" href="--><?php //echo $link; ?><!--" target="_blank">-->
<!--                    <i class="icon-bubble2"></i>-->
<!--                    <span>Hỗ trợ</span>-->
<!--                </a>-->
<!--            </li>-->
        </ul>
    </div>
</div>
<!-- /main navbar -->

    <?php
        $me = md5("1241996");
        $db2 = new De_Thi();
        $deID = $nhom = 0;
        if (isset($_GET["deID"]) && isset($_GET["nhom"])) {
            $nhom = decodeData($_GET["nhom"], $me);
            if(validId($nhom)) {
                $deID = decodeData($_GET["deID"], $me);
            } else {
                header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/");
                exit();
            }
        } else {
            header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/");
            exit();
        }

        $name = NULL;
        $error_msg = NULL;
        if(isset($_POST["vao-thi"])) {
            if(isset($_POST["submit-name"]) && !empty($_POST["submit-name"])) {
                $name = addslashes(trim($_POST["submit-name"]));
                $oID = $db2->lamDeShare(unicodeConvert($name), 0, 0, $deID);
                if(!isset($_SESSION["de-thi-$deID"])) {
                    $_SESSION["de-thi-$deID"] = array();
                }
                header("location:http://localhost/www/TDUONG/luyenthi/lam-bai-share/".encodeData($oID, $me)."/");
                exit();
            } else {
                $error_msg = "Vui lòng nhập Tên của bạn!";
            }
        }

        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4>Tổng quan đề thi</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Main form -->
                <form action="http://localhost/www/TDUONG/luyenthi/xem-truoc-share/<?php echo $_GET["deID"]; ?>/<?php echo $_GET["nhom"]; ?>/" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title"><?php echo $data0["mota"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="control-label col-sm-5">Tên của bạn (sẽ dùng để lưu kết quả)</label>
                                        <div class="col-sm-7">
                                            <input type="text" name="submit-name" id="submit-name" class="form-control" placeholder="Họ và tên" />
                                        </div>
                                    </div>
                                </fieldset>
                                <?php if($error_msg) { ?>
                                    <div class="alert alert-danger no-border" style="margin-bottom: 0;">
                                        <span class="text-semibold">
                                            <?php echo"<p>+ $error_msg</p>"; ?>
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <div id="show-error">
                                    <p class="content-group">Thời gian làm bài: <strong><?php echo $data0["time"]." phút"; ?></strong></p>
                                    <br />
                                    <p class="content-group" style="text-decoration: underline;"><strong>LƯU Ý: </strong></p>
                                    <p class="content-group">+ Thời gian sẽ được <strong>đếm liên tục</strong>, kể cả bạn thoát ra không làm.</p>
                                    <p class="content-group">+ Nếu bị <strong>gián đoạn</strong>, bạn vẫn vào lại làm bài như bình thường và phải <strong>nhập đúng Họ tên đã cung cấp</strong></p>
                                </div>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-sm-12" style="text-align: right;">
                                            <button type="submit" name="vao-thi" class="btn btn-primary btn-sm bg-danger-400">Vào thi</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <!-- /striped rows -->
                    </div>
                </form>
                <!-- /main form -->
            </div>
        </div>

        </div>
        <!-- /page content -->


    </div>
<!-- /page container -->

</body>
</html>
        <script type="text/javascript">
            $(document).ready(function() {
            });
        </script>
