<?php
    ob_start();
    session_start();
    require_once("model/model.php");
    if (isset($_SESSION["my_mon"]) && isset($_SESSION["my_monbig"]) && isset($_SESSION["my_id"]) && isset($_SESSION["is_ct"]) && isset($_SESSION["is_app"])) {
        header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/");
        exit();
    }
    $me = md5("123456");
    $temp_code=md5("1241996");
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="en" style="margin-top: 0 !important;">
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
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>
        <!-- /core JS files -->

        <!-- Theme JS files -->
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/uniform.min.js"></script>

        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/switchery.min.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/ui/moment/moment.min.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/forms/styling/switch.min.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/uploaders/fileinput.min.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>

        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/app.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/form_inputs.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/form_checkboxes_radios.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/pages/jquery.countdown.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery.countTo.js"></script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/canvasjs.min.js"></script>

    </head>
    <body style="margin-top: 0 !important;">

    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="http://localhost/www/TDUONG/luyenthi/trang-chu/">Bgo Education</a>
        </div>
    </div>
    <!-- /main navbar -->

    <?php
    $isApp = false;
    $ip=$_SERVER['REMOTE_ADDR'];

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

        if($cmt && $password && $sdt && validMaso($cmt)) {
            $db = new Hoc_Sinh();
            $result = $db->checkHocSinhDetail($cmt,$password);
            if($result->num_rows != 0) {
                $data = $result->fetch_assoc();
                $db->login($data);
                header("location:http://localhost/www/TDUONG/luyenthi/trang-chu/");
                exit();
            } else {
                if($db->checkSdtPhuHuynh($sdt,$cmt)) {
                    $error = "<div class='text-center bg-danger-400' style='padding:5px;margin-bottom: 20px;'>
                        <h5><small class='display-block' style='color:#FFF;line-height:20px;'>Bạn không thể đăng nhập bằng số điện thoại của phụ huynh!</small></h5>
                    </div>";
                } else {
                    $error = "<div class='text-center bg-danger-400' style='padding:5px;margin-bottom: 20px;'>
                        <h5><small class='display-block' style='color:#FFF;line-height:20px;'>Không tồn tại thông tin đăng nhập!</small></h5>
                    </div>";
                }
            }
        } else {
            $error = "<div class='text-center bg-danger-400' style='padding:5px;margin-bottom: 20px;'>
                <h5><small class='display-block' style='color:#FFF;line-height:20px;'>Thông tin không chính xác!</small></h5>
            </div>";
        }
    }
    if(isset($_GET["error"]) && is_numeric($_GET["error"])) {
        $error=$_GET["error"];
        switch ($error) {
            case 0:
                $msg = "Dữ liệu không chính xác!";
                break;
            case 1:
                $msg = "Bạn không đủ điều kiện tham gia môn học này!";
                break;
            case 2:
                $msg = "Tài khoản của bạn không đủ 50k!";
                break;
            case 3:
                $msg = "Bạn đã làm bài thi này trong ngày! Mỗi ngày chỉ được làm 1 lần!";
                break;
            case 4:
                $msg = "Chủ nhật không được làm bài!";
                break;
            default:
                $msg = "Unknow Error!";
                break;
        }
        $error = "<div class='text-center bg-danger-400' style='padding:5px;margin-bottom: 20px;'>
                <h5><small class='display-block' style='color:#FFF;line-height:20px;'>$msg</small></h5>
            </div>";
    }
    ?>

    <!-- Page container -->
    <div class="page-container login-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">
                <!-- Simple login form -->
                <form action="http://localhost/www/TDUONG/luyenthi/dang-nhap/" method="post">
                    <div class="panel panel-body login-form">
                        <div class="text-center">
                            <h5 class="content-group">Luyện thi trắc nghiệm</h5>
                        </div>

                        <?php echo $error; ?>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Mã số">
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

        <?php include_once "include/bottom_hoc_sinh.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                //document.oncontextmenu = new Function("return false");
            });
        </script>
