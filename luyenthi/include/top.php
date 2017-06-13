<?php
    ob_start();
    session_start();
    require_once("../model/model.php");
    if (isset($_SESSION["my_mon"]) && isset($_SESSION["my_id"]) && isset($_SESSION["is_ct"])) {
        global $lmID, $is_ct, $ID;
        $lmID = $_SESSION["my_mon"];
        $is_ct = $_SESSION["is_ct"];
        $ID = $_SESSION["my_id"];
        $isLogin = true;
//        $db = new Admin();
//        $result = $db->getCheckAdmin($ID,(new Mon_Hoc())->getMonOfLop($lmID));
//        if($result->num_rows != 0) {
//            $global = $result->fetch_assoc();
//            $isLogin = true;
//        } else {
//            $isLogin = false;
//            header("location:http://localhost/www/TDUONG/luyenthi/admin/dang-xuat/");
//            exit();
//        }
    } else {
        $isLogin = false;
        $url=$_SERVER['REQUEST_URI'];
        if(stripos($url,"/dang-nhap/")===false) {
            header("location:http://localhost/www/TDUONG/luyenthi/admin/dang-nhap/");
            exit();
        }
        $global["fullname"] = "Guest";
        $global["avata"] = "placeholder.jpg";
    }
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
    <link href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery-ui.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery-ui.multidatespicker.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/iscroll.js"></script>
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
        a.canvasjs-chart-credit {display: none;}
        table#list-bai-thi {table-layout: fixed;}
        /*table#list-bai-thi tr th, table#list-bai-thi tr td {font-family: 'FontMe';}*/
        #kietcc {position: fixed;bottom: 10px;right:10px;padding: 10px;text-align: center;}
        .icon-arrow-up16{font-size: 17px;color:#FFF;}
        @media only screen and (max-width: 640px) {
            table .bieu-do-hin {display: none;}
        }
        .mjx-mtd {text-align: left !important;}
        .btn-fixed {position: fixed;z-index: 99;left: 10px;bottom: 10px;}
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
        @media (max-width: 769px) {
            .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {padding: 0;}
        }
    </style>

    <!-- /theme JS files -->
    <script>
        function valid_json(json) {
            if (/^[\],:{}\s]*$/.test(json.replace(/\\["\\\/bfnrtu]/g, '@').
                replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').
                replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

                return true;

            }else{
                return false;
                //the json is not ok

            }
        };
        function valid_id(id) {
            if(id != 0 && $.isNumeric(id)) {
                return true;
            } else {
                return false;
            }
        };
        function confirm_popup(content) {
            var notice = new PNotify({
                title: 'Xác nhận',
                text: '<p>' + content + '</p>',
                hide: false,
                type: 'warning',
                confirm: {
                    confirm: true,
                    buttons: [
                        {
                            text: 'Có',
                            addClass: 'btn-sm'
                        },
                        {
                            text: 'Hủy',
                            addClass: 'btn-sm'
                        }
                    ]
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                }
            });
            return notice;
        };
        $(document).ready(function() {
            var myTop = $(".navbar.navbar-inverse").offset().top;
            $("body").css("margin-top",-myTop + "px");

            $("table#list-bai-thi tr td:last-child").css("overflow","auto");

            var mon = $("ul#mon-access li.active a").html();
            if(mon) {
                $("a#mon-show span strong").html(mon);
            } else {
                $("a#mon-show").hide();
            }

            $("ul#mon-access li a").click(function () {
                var lmID = $(this).attr("data-lmID");
                if(valid_id(lmID)) {
                    $.ajax({
                        async: false,
                        data: "lmID=" + lmID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-mon/",
                        success: function (result) {
                            location.reload();
                        }
                    });
                }
            });

            $("i.position-left").css("cursor","pointer");
            $("i.position-left").click(function() {
                history.go(-1);
            });

            $("#kietcc").click(function() {
                $("html,body").animate({scrollTop:0},250);
            });
            $(window).scroll(function(){
                if($(window).scrollTop() > 200){
                    $("#kietcc").fadeIn("fast");
                }
                else{
                    $("#kietcc").fadeOut("fast");
                }
            });
        });
    </script>
</head>
<body>

    <!-- Main navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="navbar-brand" href="http://localhost/www/TDUONG/luyenthi/admin/trang-chu/"><i class="icon-home4"></i> <span style="padding-left: 7px;">Bgo Education</span></a>

            <ul class="nav navbar-nav visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                <li><a class="sidebar-mobile-main-toggle sidebar-admin"><i class="icon-paragraph-justify3"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">
            <ul class="nav navbar-nav">
                <li><a class="sidebar-control sidebar-main-toggle hidden-xs sidebar-admin"><i class="icon-paragraph-justify3"></i></a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown dropdown-user bg-slate-800">
                    <a class="dropdown-toggle" data-toggle="dropdown" id="mon-show">
                        <span><strong></strong></span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right" id="mon-access">
                        <?php
                            if($isLogin) {
                                $db = new Mon_Hoc();
                                $result = $db->getAllLopMon();
                                while ($data = $result->fetch_assoc()) {
                                    if ($data["ID_LM"] == $lmID) {
                                        echo "<li class='active'><a href='javascript:void(0)' data-lmID='$data[ID_LM]'> Môn $data[name]</a></li>";
                                    } else {
                                        echo "<li><a href='javascript:void(0)' data-lmID='$data[ID_LM]'> Môn $data[name]</a></li>";
                                    }
                                }
                            }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->

    <!-- Popup -->

    <!-- /popup -->