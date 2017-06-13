<?php
    ob_start();
    session_start();
    require_once("../model/model.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRẮC NGHIỆM</title>
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/favicon.ico" rel="shortcut icon" type="image/x-icon"/>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/uniform.min.js"></script>

    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/switch.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/uploaders/fileinput.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/notifications/pnotify.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>

    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/app.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/form_inputs.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/uploader_bootstrap.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/form_checkboxes_radios.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery.typewatch.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/components_notifications_pnotify.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/datatables_advanced.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/canvasjs.min.js"></script>

    <style type="text/css">
        #kietcc {display: none;}
    </style>
</head>
<body>

    <?php
        $ip=$_SERVER['REMOTE_ADDR'];
        if(isset($_GET["ID"]) && isset($_GET["code"]) && isset($_GET["lmID"])) {
            $ID = decodeData($_GET["ID"],$_GET["code"]);
            if(validId($ID) && validId($_GET["lmID"])) {
                $lmID = $_GET["lmID"];
                $db = new Admin();
                $result = $db->getCheckAdmin($ID,(new Mon_Hoc())->getMonOfLop($lmID));
                if($result->num_rows != 0) {
                    $data = $result->fetch_assoc();
                    session_destroy();
                    session_start();
                    $code = randPass(16);
                    $_SESSION["my_mon"] = $lmID;
                    $db2 = new Mon_Hoc();
                    $_SESSION["is_ct"] = $db2->getUseCt($db2->getMonOfLop($lmID));
                    $_SESSION["my_id"] = $ID;
                    $_SESSION["my_code"] = $code;
                    (new Log())->ghiLog(0,"ID $ID đăng nhập từ trang admin và ip: ".$_SERVER['REMOTE_ADDR']. " - OK","login");
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/trang-chu/");
                    exit();
                } else {
                    (new Log())->ghiLog(0,"ID $ID đăng nhập từ trang admin và ip: ".$_SERVER['REMOTE_ADDR']. " - SAI","login");
                    header("location:http://localhost/www/TDUONG/luyenthi/admin/dang-nhap/");
                    exit();
                }
            } else {
                (new Log())->ghiLog(0,"ID $ID đăng nhập từ trang admin và ip: ".$_SERVER['REMOTE_ADDR']. " - SAI","login");
                header("location:http://localhost/www/TDUONG/luyenthi/admin/dang-nhap/");
                exit();
            }
        }

    $error="";
    $cmt=$password=$sdt=NULL;
    if(isset($_POST["sign-ok"])) {

        if(isset($_POST["username"])) {
            $cmt=trim(addslashes($_POST["username"]));
        }

        if(isset($_POST["password"])) {
            $sdt=addslashes($_POST["password"]);
            $password=md5($sdt);
        }

        if($cmt && $password && $sdt) {
            $db = new Admin();
            $result = $db->getCheckAdminPass($cmt,$password);
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                $code = randPass(16);
                session_destroy();
                session_start();
                $lmID = $db->getMonAdmin($data["ID"]);
                $_SESSION["my_mon"] = $lmID;
                $db2 = new Mon_Hoc();
                $_SESSION["is_ct"] = $db2->getUseCt($db2->getMonOfLop($lmID));
                $_SESSION["my_id"] = $data["ID"];
                $_SESSION["my_code"] = $code;
                (new Log())->ghiLog($data["ID"],"Tài khoản $cmt đăng nhập với pass: $sdt và ip: ".$_SERVER['REMOTE_ADDR']. " - OK","login");
                header("location:http://localhost/www/TDUONG/luyenthi/admin/trang-chu/");
                exit();
            } else {
                (new Log())->ghiLog(0,"Tài khoản $cmt đăng nhập với pass: $sdt và ip: ".$_SERVER['REMOTE_ADDR']. " - SAI","login");
                $error = "<div class='text-center bg-danger-400' style='padding:5px;margin-bottom: 20px;'>
                    <h5><small class='display-block' style='color:#FFF;line-height:20px;'>Thông tin không chính xác!</small></h5>
                </div>";
            }
        } else {
            (new Log())->ghiLog(0,"Tài khoản $cmt đăng nhập với pass: $sdt và ip: ".$_SERVER['REMOTE_ADDR']. " - SAI","login");
            $error = "<div class='text-center bg-danger-400' style='padding:5px;margin-bottom: 20px;'>
                <h5><small class='display-block' style='color:#FFF;line-height:20px;'>Thông tin không chính xác!</small></h5>
            </div>";
        }
    }
    ?>

    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="http://localhost/www/TDUONG/luyenthi/trang-chu/">Bgo Education</a>
        </div>
    </div>
    <!-- /main navbar -->

    <!-- Page container -->
    <div class="page-container login-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Simple login form -->
                <form action="http://localhost/www/TDUONG/luyenthi/admin/dang-nhap/" method="post">
                    <div class="panel panel-body login-form">
                        <div class="text-center">
                            <h5 class="content-group">Quản lý trắc nghiệm</h5>
                        </div>

                        <?php echo $error; ?>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Tên đăng nhập">
                            <div class="form-control-feedback">
                                <i class="icon-user text-muted" style="line-height: 36px;"></i>
                            </div>
                        </div>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="password" name="password" class="form-control" autocomplete="off" placeholder="Mật khẩu">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted" style="line-height: 36px;"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" name="sign-ok" class="btn btn-primary btn-block">Đăng nhập <i class="icon-circle-right2 position-right"></i></button>
                        </div>
                    </div>
                </form>
                <!-- /simple login form -->
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                //document.oncontextmenu = new Function("return false");
            });
        </script>
