<?php
ob_start();
session_start();
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
        .btn-fixed {position: fixed;z-index: 99;right: 10px;top: 7px;}
        i {cursor: pointer;}
        .mjx-chtml {
            display: inline !important;
        }
        .border-up {
            border-color: yellow !important;
            border-top: 4px solid yellow !important;
            border-left: 4px solid yellow !important;
            border-right: 4px solid yellow !important;
        }
        .border-down {
            border-color: yellow !important;
            border-bottom: 4px solid yellow !important;
            border-left: 4px solid yellow !important;
            border-right: 4px solid yellow !important;
        }
        #navbar-mobile {position: absolute;z-index: 99;top: 3px;}
        @media (min-width: 770px) {
            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
                font-size: 15px;
            }
        }
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
        <a class="navbar-brand" href="http://localhost/www/TDUONG/luyenthi/trang-chu/"><span>Bgo Education</span></a>

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
        $me = md5("123456");
        $db2 = new De_Thi();
        $db = new Vao_Thi();
        $db3 = new Cau_Hoi();
        $hsID = $deID = $oID = 0;
        $name = "";
        if(isset($_GET["oID"])) {
            $oID = decodeData($_GET["oID"], md5("1241996"));
            if(validId($oID)) {
                $result = (new Options())->getOptions($oID);
                $data = $result->fetch_assoc();
                $temp = explode("-",$data["type"]);
                $name = $data["content"];
                $deID = end($temp);
                $hsID = "-".abs($oID);
            } else {
                header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/");
                exit();
            }
        } else {
            header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/");
            exit();
        }
        if(isset($_POST["super-nop-bai"]) && isset($_POST["super-de"])) {
            header("location:http://localhost/www/TDUONG/luyenthi/ket-qua-lam-share/".$_GET["oID"]."/");
            exit();
        }
        $deID = $deID + 1 - 1;

        $result0 = $db2->getDeThiById($deID);
        $data0 = $result0->fetch_assoc();

        $deID = $db->addHocSinhInDe($hsID, $deID, $data0["nhom"], "in");

        $deID_en = encodeData($deID,$me);
        //        $hsID_en = encodeData($hsID,$me);
        $hsID_en = $hsID;

        $check_done = false;
        if($db2->checkHocSinhDoneLam($hsID, $data0["nhom"])) {
            $check_done = true;
            header("location:http://localhost/www/TDUONG/luyenthi/ket-qua-lam-share/".$_GET["oID"]."/");
            exit();
        }

//        if(!isset($_SESSION["de-thi-$deID"])) {
//            $_SESSION["de-thi-$deID"] = array();
//        }
//        $cau_lam = array();
//        $se_need = "de-thi-$deID";
//        foreach ($_SESSION as $se_key => $se_value) {
//            if(is_array($se_value) && stripos($se_key, "-") != false) {
//                $temp = explode("-", $se_key);
//                if(count($temp) == 3) {
//                    $temp[2] = (int) $temp[2];
//                    if($temp[0] == "de" && $temp[1] == "thi" && $temp[2] == $deID) {
//                        //                        echo $temp[0] . " - " . $temp[1] . " - " . $temp[2] . "<br />";
//                        if(count($se_value) > 0) {
//                            $cau_lam = $se_value;
//                            break;
//                        }
//                    }
//                }
//            }
//        }

        $vao_thi = $db->getTimeHocSinhInDe($hsID,$deID,$data0["nhom"]);
        $in_time = strtotime($vao_thi);

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
        $time = $data0["time"]*60;
        $now = time();

        $start_time = $now - $in_time;

        $url = mt_rand(1,5);
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title text-center" style="padding-left: 36px;">-->
<!--                <h4>--><?php //echo $data0["mota"]."<br />Mã: ".$data0["maso"]; ?><!--</h4>-->
<!--                <code id="count-time" style="font-size: 26px;"></code>-->
<!--                <span id="count-time-progress" style="display: none;"></span>-->
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
                <form action="http://localhost/www/TDUONG/luyenthi/lam-bai-share/<?php echo $_GET["oID"]; ?>/" class="form-horizontal" method="post">
                    <input type="hidden" value="<?php echo $deID_en; ?>" name="super-de" />
                    <button type="submit" class="hidden" id="super-nop-bai" name="super-nop-bai">Nộp bài</button>
                </form>
                    <div class="col-lg-12" style="padding: 0;">
                        <div class="panel panel-flat">
                            <table class="table" id="list-bai-thi">
                                <colgroup>
                                    <col style="width: 60px;">
                                    <col>
                                </colgroup>
                                <tbody>
                                <?php
                                    $dapan = array();
                                    $dem = 0;
                                    if(!($start_time > $time) || $check_done) {
                                        $dapan_hs = array();
                                        $result = $db2->getHocSinhDapAnByDe($hsID, $deID);
                                        while ($data = $result->fetch_assoc()) {
//                                            if(isset($cau_lam["cau-hoi-$data[ID_C]"]) && (date_create($cau_lam["cau-hoi-$data[ID_C]"]["datetime"]) > date_create($data["datetime"]) || $data["ID_DA"] != $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"])) {
//                                                $dapan_hs[$data["ID_C"]] = array(
//                                                    "daID" => $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"],
//                                                    "note" => $cau_lam["cau-hoi-$data[ID_C]"]["note"],
//                                                    "time" => $cau_lam["cau-hoi-$data[ID_C]"]["time"],
//                                                    "stamp" => $cau_lam["cau-hoi-$data[ID_C]"]["datetime"]
//                                                );
//                                                if($data["ID_DA"] != $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"]) {
//                                                    $db->updateCauLam($hsID, $data["ID_C"], $deID, $cau_lam["cau-hoi-$data[ID_C]"]["time"], $cau_lam["cau-hoi-$data[ID_C]"]["ID_DA"], $cau_lam["cau-hoi-$data[ID_C]"]["note"]);
//                                                }
//                                            } else {
                                                $dapan_hs[$data["ID_C"]] = array(
                                                    "daID" => $data["ID_DA"],
                                                    "note" => $data["note"],
                                                    "time" => $data["time"],
                                                    "stamp" => $data["datetime"]
                                                );
//                                            }
                                        }

                                        $dapan_all = array();
                                        $result = $db2->getDapAnNganByDeAll($deID);
                                        while ($data = $result->fetch_assoc()) {
                                            $dapan_all[$data["ID_C"]][] = array(
                                                "ID_DA" => $data["ID_DA"],
                                                "main" => $data["main"],
                                                "type" => $data["type"],
                                                "content" => $data["content"]
                                            );
                                        }

                                        $dem = 1;
                                        $result = $db2->getCauHoiByDeDangLam($deID);
                                        $num = mysqli_num_rows($result);
                                        while ($data = $result->fetch_assoc()) {
                                            if (isset($dapan_hs[$data["ID_C"]])) {
                                                $data["ID_DA"] = $dapan_hs[$data["ID_C"]]["daID"];
                                                $data["khac"] = $dapan_hs[$data["ID_C"]]["note"];
                                                $data["time"] = $dapan_hs[$data["ID_C"]]["time"];
                                            } else {
                                                $data["ID_DA"] = NULL;
                                                $data["time"] = 0;
                                                $data["khac"] = "";
                                            }
                                            $daID_hs = $data["ID_DA"];
                                            $pre_time = 0;
                                            if (isset($daID_hs) && is_numeric($daID_hs)) {
                                                echo "<tr class='de-bai-cau-big de-bai-cau-$dem cau-edited' data-cID='$data[ID_C]' data-cau='$dem' style='display: none;background:#D1DBBD;'>";
                                                if (isset($data["time"]) && is_numeric($data["time"])) {
                                                    $pre_time = $data["time"];
                                                }
                                            } else {
                                                $daID_hs = 0;
                                                echo "<tr class='de-bai-cau-big de-bai-cau-$dem' data-cID='$data[ID_C]' data-cau='$dem' style='display: none;background:#D1DBBD;'>";
                                            }
                                            echo "<td colspan='2' style='line-height: 30px;'>";
                                            if ($dem == $num) {
                                                echo "<span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>Câu $data[sort] (CÂU CUỐI):</span> ";
                                            } else {
                                                echo "<span class='label bg-brown-600' style='font-size:14px;margin-right: 10px;'>Câu $data[sort]:</span> ";
                                            }
                                            if ($data["content"] != "none") {
                                                echo imageToImg($data["ID_MON"], $data["content"], 250);
                                            }
                                            if ($data["anh"] != "none") {
                                                echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/" . $db3->getUrlDe($data["ID_MON"], $data["anh"]) . "' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                            }
                                            echo "<span class='count-time-$dem' style='display: none;' data-pre='$pre_time'></span></td></tr>";
                                            $dem2 = 0;
                                            for ($i = 0; $i < count($dapan_all[$data["ID_C"]]); $i++) {
                                                if ($daID_hs == $dapan_all[$data["ID_C"]][$i]["ID_DA"]) {
                                                    echo "<tr style='background-color: #ffffb2;display: none;' class='dap-an-con dap-an-cau-$dem dapan-chon'>";
                                                    $dapan[$dem] = $da_arr[$dem2];
                                                } else {
                                                    echo "<tr class='dap-an-con dap-an-cau-$dem' style='display: none;'>";
                                                }
                                                echo "<td><div class='radio'>
                                                    <label>
                                                        <input type='radio' name='radio-dap-an-$dem' ";
                                                if ($dapan_all[$data["ID_C"]][$i]["ID_DA"] == $daID_hs) {
                                                    echo "checked='checked'";
                                                }
                                                echo " class='control-primary radio-dap-an' data-temp='" . $dapan_all[$data["ID_C"]][$i]["ID_DA"] . "' data-cau='$dem' data-stt='$dem2'/>
                                                        " . $da_arr[$dem2] . ".
                                                    </label>
                                                </div></td>
                                                <td>";
                                                $khac = false;
                                                if ($dapan_all[$data["ID_C"]][$i]["type"] == "text") {
                                                    $dapan_temp = imageToImgDapan($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"], 250);
//                                                        if(stripos(unicodeConvert($dapan_temp),"dap-an-khac") === false) {} else {$khac=true;}
                                                    echo $dapan_temp;
                                                } else {
                                                    echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;'><img src='http://localhost/www/TDUONG/luyenthi/" . $db3->getUrlDapAn($data["ID_MON"], $dapan_all[$data["ID_C"]][$i]["content"]) . "' style='max-height:250px;' class='img-thumbnail img-responsive' /></span>";
                                                }
                                                echo "</td>
                                            </tr>";
                                                $dem2++;
                                            }
                                            $dem++;
                                        }
                                        $dem--;
                                        if ($dem == 0) {
                                            echo "<tr>
                                                <th colspan='2'>Đề trống rỗng! :(</th>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                            <th colspan='2'>Hết thời gian làm bài!</th>
                                        </tr>";
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /striped rows -->
                    </div>
                <!-- /main form -->
            </div>
            <!-- /main content -->

        </div>

        <div style="height: 120px;"></div>
        <button type="button" class="btn btn-primary btn-xs bg-danger-400 btn-fixed" id="submit-nop-bai">NỘP BÀI</button>

        <div class="container-fluid" style="position: fixed;bottom:-20px;left:0;z-index:99;width: 100%;padding: 0;margin: 0;">
            <div class="panel panel-flat">
                <div class="panel-heading" style="padding: 5px;text-align: center;">
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: left;" id="submit-cau-left"><span class="icon-chevron-left"></span></button>
                    <button type="button" class="btn btn-primary btn-xs bg-brown-400" style="float: right;" id="submit-cau-right"><span class="icon-chevron-right"></span></button>
                    <div style="clear: both;"></div>
                </div>
                <div class="panel-body" style="overflow-x: auto;padding: 0;">
                    <table class="table table-xs" id="list-tom-tat">
                        <thead>
                            <tr class="bg-brown-400">
                            <?php
                                $tr_dapan = "";
                                for($i=1;$i<=$dem;$i++) {
                                    echo"<td style='min-width: 60px;cursor: pointer;' class='text-center dap-an-eye'>$i</td>";
                                    if(isset($dapan[$i])) {
                                        $tr_dapan .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>".$dapan[$i]."</td>";
                                    } else {
                                        $tr_dapan .= "<td class='text-center tom-tat-$i dap-an-eye' style='cursor: pointer;'>_</td>";
                                    }
                                }
                            ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><?php echo $tr_dapan; ?></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /page content -->

    </div>
<!-- /page container -->

</body>
</html>
        <script type="text/javascript">
//            var myEvent = window.attachEvent || window.addEventListener;
//            var chkevent = window.attachEvent ? 'onbeforeunload' : 'beforeunload'; /// make IE7, IE8 compatable

//            myEvent(chkevent, function(e) { // For >=IE7, Chrome, Firefox
//                var confirmationMessage = ' ';  // a space
//                (e || window.event).returnValue = confirmationMessage;
//                return confirmationMessage;
//            });

            $(document).ready(function() {
                var window_width = $(window).width();
                $(".navbar-brand span").html("<code id='count-time' style='font-size: 26px;'></code><span id='count-time-progress' style='display: none;'>");
                var border_min = parseInt(((window_width/$("table#list-tom-tat tr td").length)/2));
                var border_max = $("table#list-tom-tat tr td").length - border_min;
//                console.log("border-len: " + border_max);
                var border = "4px solid yellow";
                bo_khung(1, "none");
                var td_width = $("table#list-tom-tat").width() / <?php echo $dem; ?>;
                td_width = td_width > 60 ? td_width : 60;
                function bo_khung(dem, type) {
                    $("table#list-tom-tat tr td").removeClass("border-up").removeClass("border-down").removeClass("border-full");
                    $("table#list-tom-tat tr:first td:eq(" + (dem - 1) + ")").addClass("border-up");
                    $("table#list-tom-tat tr:last td:eq(" + (dem - 1) + ")").addClass("border-down");
                    if(dem > border_min && type == "right") {
                        $("table#list-tom-tat").closest("div").animate({scrollLeft:'+=' + td_width},250);
                    } else if(dem < border_max && type == "left") {
                        $("table#list-tom-tat").closest("div").animate({scrollLeft:'-=' + td_width},250);
                    }
                }
                <?php if($start_time > $time) { ?>
                    var str_thongbao = "Bạn vào thi từ thời điểm <?php echo formatDateTime($vao_thi); ?>, đến nay <?php echo date("H:i:s d/m/Y"); ?> đã hết thời gian làm bài <?php echo $data0["time"]; ?> phút nên sẽ tiến hành thu bài!";
//                    alert(str_thongbao);
                    new PNotify({
                        title: 'Nộp bài',
                        text: str_thongbao,
                        icon: 'icon-reload-alt'
                    });
                    $("#super-nop-bai").click();
                <?php } ?>
                $("#count-time-progress").countTo({
                    from: <?php echo $start_time*1000; ?>,
                    to: <?php echo $time*1000; ?>,
                    speed: <?php echo $time*1000; ?>,
                    refreshInterval: 1 * 1000,
                    onUpdate: function (value) {
                        var seconds = (<?php echo $time*1000; ?> - value)/1000;
                        var days    = Math.floor(seconds / 86400);
                        var hours   = Math.floor((seconds - (days * 86400)) / 3600);
                        var minutes = Math.floor((seconds - (days * 86400) - (hours * 3600))/60);
                        seconds = Math.floor((seconds - (days * 86400) - (hours * 3600) - (minutes*60)));
                        $("#count-time").html("<strong></strong>");
                        $("#count-time").find("strong").html(formatZero(hours) + ":" + formatZero(minutes) + ":" + formatZero(seconds));
                    },
                    onComplete: function (value) {
                        $("button#submit-nop-bai").removeClass("disabled").show();
                        $("table#list-bai-thi").html("<tr><th>Đã hết thời gian làm bài!</th></tr>");
//                        setTimeout(function() {
                            nop_bai();
//                        },1000);
                    }
                });
                function formatZero(number) {
                    if(number < 10) {
                        return "0" + number;
                    } else {
                        return number;
                    }
                }
                var time_cau = [];
                var da_arr = ["A","B","C","D","E","F","G","H","I","K"];
//                var chosen = [];
                for(i = 1; i <= <?php echo $dem; ?>; i++) {
//                    chosen["" + i + ""] = 0;
                    time_cau["" + i + ""] = 0;
                    $("span.count-time-" + i).countTo({
                        from: parseInt($("span.count-time-" + i).attr("data-pre")),
                        to: 30 * 60 * 1000,
                        speed: 30 * 60 * 1000,
                        refreshInterval: 2 * 1000
                    });
                    if(i > 1) {
                        $("span.count-time-" + i).countTo("stop");
                    }
                }
                <?php
//                    for($i = 1; $i <= $dem; $i++) {
//                        if(isset($dapan[$i])) {
//                            echo"chosen[$i] = 1;\n";
//                        }
//                    }
                ?>
                // Mở chỗ xem kết quả
//                if(chosen.indexOf(0) == -1) {
//                    $("button#submit-nop-bai").removeClass("disabled").show();
//                }
                var dem_first = $("table#list-bai-thi tr.de-bai-cau-big:first").attr("data-cau");
                $("table#list-bai-thi tr.de-bai-cau-big:first").addClass("cau-active").show();
                $("table#list-bai-thi tr.dap-an-cau-" + dem_first).show();
                $("button#submit-cau-left").css("opacity","0");
                $("button#submit-cau-left").click(function() {
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    if(my_stt > 1) {
                        $("span.count-time-" + my_stt).countTo("stop");
                        var time = parseInt($("span.count-time-" + my_stt).html());
                        time_cau["" + my_stt + ""] = time;
                        cur_cau.removeClass("cau-active").hide();
                        $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                        var now_stt = my_stt - 1;
                        $("span.count-time-" + now_stt).countTo("start");
                        $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                        $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                        $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
//                        $("html,body").animate({scrollTop:170},250);
//                        $("table#list-tom-tat").closest("div").animate({scrollLeft:(now_stt-1)*td_width},250);
//                        update_time_lam(my_stt,time);
                        $("button#submit-cau-right").css("opacity","1");
                        if(now_stt == 1) {
                            $("button#submit-cau-left").css("opacity","0");
                        }
                        bo_khung(now_stt, "left");
                    }
                });
                $("button#submit-cau-right").click(function() {
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    if(my_stt < <?php echo $dem; ?>) {
                        $("span.count-time-" + my_stt).countTo("stop");
                        var time = parseInt($("span.count-time-" + my_stt).html());
                        time_cau["" + my_stt + ""] = time;
                        cur_cau.removeClass("cau-active").hide();
                        $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                        var now_stt = my_stt + 1;
                        $("span.count-time-" + now_stt).countTo("start");
                        $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                        $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                        $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
//                        $("html,body").animate({scrollTop:170},250);
//                        $("table#list-tom-tat").closest("div").animate({scrollLeft:(now_stt-1)*td_width},250);
//                        update_time_lam(my_stt,time);
                        $("button#submit-cau-left").css("opacity","1");
                        if(now_stt == <?php echo $dem; ?>) {
                            $("button#submit-cau-right").css("opacity","0");
                        }
                        bo_khung(now_stt, "right");
                    }
                });
                $("body").addClass("sidebar-xs");
                $("table#list-bai-thi tr.dap-an-con").click(function () {
                    temp = $(this).find("input.radio-dap-an");
                    click_dap_an(temp);
                });
//                $("input.radio-dap-an").click(function() {
//                    click_dap_an($(this));
//                });
                function click_dap_an(div) {
                    // Lấy dữ liệu
                    var dapan_cau = div.closest("tr");
                    var temp = div.attr("data-temp");
                    var dem = div.attr("data-cau");
                    var dem2 = div.attr("data-stt");
                    var main_cau = $("table#list-bai-thi tr.de-bai-cau-" + dem);
                    var cID = main_cau.attr("data-cID");

//                    chosen["" + dem + ""] = 1;
//                    time_cau["" + my_stt + ""] = parseInt($("span.count-time-" + dem).html());

                    // Dữ liệu gửi đi
                    //var da = parseInt(temp);
                    var time = parseInt($("span.count-time-" + dem).html());

                    if (main_cau.hasClass("cau-edited") && !dapan_cau.hasClass("cau-in") && !dapan_cau.hasClass("dapan-chon")) {
                        if (valid_id(dem) && $.isNumeric(time) && valid_id(temp) && valid_id(cID)) {
                            dapan_cau.addClass("cau-in");
                            update_view(dem, dapan_cau, dem2, null, div);
                            $.ajax({
                                async: true,
                                data: "da0=" + temp + "&time0=" + time + "&cID=" + cID + "&hsID=<?php echo $hsID_en; ?>" + "&deID=<?php echo $deID; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                                success: function (result) {
                                    result = result.trim();
                                    main_cau.addClass("cau-edited");
                                    $("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").removeClass("dapan-chon");
                                    dapan_cau.removeClass("cau-in").addClass("dapan-chon");
                                    if (result == "none") {
                                        new PNotify({
                                            text: 'Dữ liệu không chính xác!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "out") {
                                        new PNotify({
                                            text: 'Bạn đã hết phiên đăng nhập do xuất hiện gián đoạn Hãy refresh!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "fuck") {
                                        new PNotify({
                                            text: 'Không thể lưu đáp án!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else {
                                        main_cau.removeClass("cau-error");
//                                        new PNotify({
//                                            title: 'Làm bài',
//                                            text: 'Đáp án của bạn đã được ghi nhận!',
//                                            icon: 'icon-checkmark'
//                                        });
                                    }
                                    console.log("Sửa: --" + result);
                                },
                                error: function(xhr, ajaxOpionns, thrownError) {
                                    new PNotify({
                                        text: 'Đã có lỗi xảy ra: ' + thrownError,
                                        addclass: 'bg-danger'
                                    });
                                    dapan_cau.removeClass("cau-in");
                                    main_cau.addClass("cau-error");
                                }
                            });
                        } else {
                            console.log("Dữ liệu lỗi: " + dem + " - " + temp + " - " + time);
                        }
                    } else if(!dapan_cau.hasClass("cau-in") && !dapan_cau.hasClass("dapan-chon")) {
                        if (valid_id(dem) && $.isNumeric(time) && valid_id(temp) && valid_id(cID)) {
                            dapan_cau.addClass("cau-in");
                            update_view(dem, dapan_cau, dem2, null, div);
                            $.ajax({
                                async: true,
                                data: "da=" + temp + "&time=" + time + "&cID=" + cID + "&hsID=<?php echo $hsID_en; ?>" + "&deID=<?php echo $deID; ?>",
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                                success: function (result) {
                                    result = result.trim();
                                    main_cau.addClass("cau-edited");
                                    $("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").removeClass("dapan-chon");
                                    dapan_cau.removeClass("cau-in").addClass("dapan-chon");
                                    if (result == "none") {
                                        new PNotify({
                                            text: 'Dữ liệu không chính xác!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "out") {
                                        new PNotify({
                                            text: 'Bạn đã hết phiên đăng nhập do xuất hiện gián đoạn Hãy refresh!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else if (result == "fuck") {
                                        new PNotify({
                                            text: 'Không thể lưu đáp án!',
                                            addclass: 'bg-danger'
                                        });
                                        main_cau.addClass("cau-error");
                                    } else {
                                        main_cau.removeClass("cau-error");
//                                        new PNotify({
//                                            title: 'Làm bài',
//                                            text: 'Đáp án của bạn đã được ghi nhận!',
//                                            icon: 'icon-checkmark'
//                                        });
                                    }
                                    console.log("Thêm: --" + result);
                                },
                                error: function(xhr, ajaxOpionns, thrownError) {
                                    new PNotify({
                                        text: 'Đã có lỗi xảy ra: ' + thrownError,
                                        addclass: 'bg-danger'
                                    });
                                    dapan_cau.removeClass("cau-in");
                                    main_cau.addClass("cau-error");
                                }
                            });
                        } else {
                            console.log("Dữ liệu lỗi: " + dem + " - " + temp + " - " + time);
                        }
                    }
                }

                function update_view(dem, dapan_cau, dem2, chosen, div) {
                    // Đổi màu
                    $("table#list-bai-thi tr.dap-an-cau-" + dem).removeAttr("style").removeClass("dapan-chon");
                    dapan_cau.css("background-color","#ffffb2");
                    dapan_cau.addClass("dapan-chon");
                    $("table#list-bai-thi tr.dap-an-cau-" + dem).find("td > div > label > div > span input.radio-dap-an").removeAttr("checked");
                    $("table#list-bai-thi tr.dap-an-cau-" + dem).find("td > div > label > div > span").removeClass("checked");
                    div.attr("checked","checked");
                    div.closest("span").addClass("checked");

                    // Hiển thị đáp án đã chọn và đếm số câu đã làm
                    $("table#list-tom-tat tr td.tom-tat-" + dem).html(da_arr[dem2]);
//                    var stt = -1;
//                    $("#list-tom-tat tr").each(function(index, element) {
//                        if($(element).find("td:eq(1)").html() != "_" && da_arr[dem2]) {
//                            stt++;
//                        }
//                    });
//                    $("#dap-an-progress").html("Đáp án của bạn: " + stt + "/<?php //echo $dem; ?>//");

                    // Mở chỗ xem kết quả
//                    if(chosen.indexOf(0) == -1) {
//                        $("button#submit-nop-bai").removeClass("disabled").show();
//                    }
                }

                function update_time_lam(dem, time) {
                    var cID = $("table#list-bai-thi tr.de-bai-cau-" + dem).attr("data-cID");
                    if(cID != "" && $.isNumeric(time)) {
                        $.ajax({
                            async: true,
                            data: "&time_new=" + time + "&cID0=" + cID + "&hsID0=<?php echo $hsID_en; ?>" + "&deID0=<?php echo $deID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau/",
                            success: function (result) {
                                console.log("Update: --" + cID + " - : " + time);
                            }
                        });
                    } else {
                        console.log("Dữ liệu lỗi: " + cID + " - " + time);
                    }
                }

                $("table#list-tom-tat tr .dap-an-eye").click(function() {
                    var now_stt = $(this).index() + 1;
                    var cur_cau = $("table#list-bai-thi tr.de-bai-cau-big.cau-active");
                    var my_stt = parseInt(cur_cau.attr("data-cau"));
                    $("span.count-time-" + my_stt).countTo("stop");
                    $("span.count-time-" + now_stt).countTo("start");
                    var time = parseInt($("span.count-time-" + my_stt).html());
                    time_cau["" + my_stt + ""] = time;
                    cur_cau.removeClass("cau-active").hide();
                    $("table#list-bai-thi tr.dap-an-cau-" + my_stt).hide();
                    $("table#list-bai-thi tr.de-bai-cau-" + now_stt).addClass("cau-active").show();
                    $("table#list-bai-thi tr.dap-an-cau-" + now_stt).show();
                    $("#submit-bao-sai").attr("data-target","#modal_default_" + now_stt);
//                    $("html,body").animate({scrollTop:$("table#list-bai-thi").offset().top},250);
//                    update_time_lam(my_stt, time);
                    if(now_stt == 1) {
                        $("button#submit-cau-left").css("opacity","0");
                    } else if(now_stt == <?php echo $dem; ?>) {
                        $("button#submit-cau-right").css("opacity","0");
                    } else {
                        $("button#submit-cau-left, button#submit-cau-right").css("opacity","1");
                    }
                    bo_khung(now_stt, "none");
                });
                $("#submit-nop-bai").click(function () {
                     if(!$(this).hasClass("disabled")) {
                         if(confirm("Bạn có chắc chắn muốn nộp bài không?")) {
                             nop_bai();
                         }
                     }
                });
                function nop_bai() {
                    $("#count-time-progress").countTo("stop");
                    new PNotify({
                        title: 'Nộp bài',
                        text: 'Đang tiến hành nộp bài...',
                        icon: 'icon-reload-alt'
                    });
                    var ajax_cau = "[";
                    $("table#list-bai-thi tr.de-bai-cau-big.cau-error").each(function(index, element) {
                        var dem = $(element).attr("data-cau");
                        var da = $("table#list-bai-thi tr.dap-an-cau-" + dem + ".dapan-chon").find("input.radio-dap-an").attr("data-temp");
                        var cID = $(element).attr("data-cID");
                        var time = parseInt($("span.count-time-" + dem).html());
                        ajax_cau += '{"cID":"' + cID + '","daID":"' + da + '","time":"' + time + '"},';
                    });
                    console.log("Ajax: " + ajax_cau);
                    if(ajax_cau != "[") {
                        ajax_cau += '{"hsID":"<?php echo $hsID_en ?>","deID":"<?php echo $deID; ?>"}';
                        ajax_cau += "]";
                        $.ajax({
                            async: true,
                            data: "ajax_cau=" + ajax_cau,
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/xuly-luyen-thi-cau<?php echo $url; ?>/",
                            success: function (result) {
                                new PNotify({
                                    title: 'Nộp bài',
                                    text: 'Đã nộp bài thành công',
                                    icon: 'icon-checkmark'
                                });
                                console.log("Nộp bài: " + result);
                                $("#super-nop-bai").click();
                            }
                        });
                    } else {
                        $("#super-nop-bai").click();
                    }
                }
                document.oncontextmenu = new Function("return false");
            });
        </script>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML-full"></script>
        <script>
            MathJax.Hub.Config({
                showProcessingMessages: false,
                messageStyle: "none",
                tex2jax: {
                    inlineMath: [["$", "$"], ["\\(", "\\)"], ["\\[", "\\]"]],
                    processEscapes: false
                },
                showMathMenu: false,
                displayAlign: "left",
                jax: ["input/TeX","output/NativeMML"],
                "fast-preview": {disabled: true},
                NativeMML: { linebreaks: { automatic: true }, minScaleAdjust: 110, scale: 110},
                TeX: { noErrors: { disabled: true } },
            });
        </script>
