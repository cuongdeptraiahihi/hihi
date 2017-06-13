<?php
    ob_start();
    session_start();
    require_once("model/model.php");
    $date=getdate(date("U"));
    $current=$date["wday"]+1;
	$start_count = microtime(true);
//    if($current == 1) {
//        header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/4/");
//        exit();
//    }
    $db = new Hoc_Sinh();
    if (isset($_SESSION["my_mon"]) && isset($_SESSION["my_monbig"]) && isset($_SESSION["my_id"]) && isset($_SESSION["is_ct"])) {
        global $lmID, $is_ct, $hsID, $code;
        $lmID = $_SESSION["my_mon"];
        $is_ct = $_SESSION["is_ct"];
        $hsID = $_SESSION["my_id"];
        $code = $_SESSION["my_code"];
        $monID = $_SESSION["my_monbig"];
        if(!isset($_SESSION["global_fullname"]) || !isset($_SESSION["global_avata"]) || !isset($_SESSION["global_cmt"])) {
            $result = $db->getHocSinhDetail($hsID);
            $global = $result->fetch_assoc();
            $_SESSION["global_fullname"] = $global["fullname"];
            $_SESSION["global_avata"] = $global["avata"];
            $_SESSION["global_cmt"] = $global["cmt"];
        } else {
            $global = array();
            $global["fullname"] = $_SESSION["global_fullname"];
            $global["cmt"] = $_SESSION["global_cmt"];
            $global["avata"] = $_SESSION["global_avata"];
        }
    } else if (isset($_COOKIE["my_mon"]) && isset($_COOKIE["my_monbig"]) && isset($_COOKIE["my_id"]) && isset($_COOKIE["is_ct"])) {
        global $lmID, $is_ct, $hsID, $code;
        $lmID = $_COOKIE["my_mon"];
        $is_ct = $_COOKIE["is_ct"];
        $hsID = $_COOKIE["my_id"];
        $code = $_COOKIE["my_code"];
        $monID = $_COOKIE["my_monbig"];
        $_SESSION["my_mon"] = $lmID;
        $_SESSION["is_ct"] = $is_ct;
        $_SESSION["my_id"] = $hsID;
        $_SESSION["my_code"] = $code;
        $_SESSION["my_monbig"] = $monID;
        if(!isset($_SESSION["global_fullname"]) || !isset($_SESSION["global_avata"]) || !isset($_SESSION["global_cmt"])) {
            $result = $db->getHocSinhDetail($hsID);
            $global = $result->fetch_assoc();
            $_SESSION["global_fullname"] = $global["fullname"];
            $_SESSION["global_avata"] = $global["avata"];
            $_SESSION["global_cmt"] = $global["cmt"];
        } else {
            $global = array();
            $global["fullname"] = $_SESSION["global_fullname"];
            $global["cmt"] = $_SESSION["global_cmt"];
            $global["avata"] = $_SESSION["global_avata"];
        }
    } else {
        $url=$_SERVER['REQUEST_URI'];
        if(stripos($url,"/dang-nhap/")===false) {
            header("location:http://localhost/www/TDUONG/luyenthi/dang-nhap/");
            exit();
        }
        $global["fullname"] = "Guest";
        $global["avata"] = "placeholder.jpg";
        $global["cmt"] = "xxx";
        $lmID = $is_ct = $hsID = $monID = 0;
    }
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
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript" src="http://localhost/www/TDUONG/luyenthi/assets/js/jquery.form.js"></script>

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
            .page-container, .navbar-inverse {
                -webkit-box-shadow: -3px 0px 5px 0px rgba(0,0,0,0.65);
                -moz-box-shadow: -3px 0px 5px 0px rgba(0,0,0,0.65);
                box-shadow: -3px 0px 5px 0px rgba(0,0,0,0.65);
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
        var Base64 = {


            _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


            encode: function(input) {
                var output = "";
                var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
                var i = 0;

                input = Base64._utf8_encode(input);

                while (i < input.length) {

                    chr1 = input.charCodeAt(i++);
                    chr2 = input.charCodeAt(i++);
                    chr3 = input.charCodeAt(i++);

                    enc1 = chr1 >> 2;
                    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                    enc4 = chr3 & 63;

                    if (isNaN(chr2)) {
                        enc3 = enc4 = 64;
                    } else if (isNaN(chr3)) {
                        enc4 = 64;
                    }

                    output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

                }

                return output;
            },


            decode: function(input) {
                var output = "";
                var chr1, chr2, chr3;
                var enc1, enc2, enc3, enc4;
                var i = 0;

                input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

                while (i < input.length) {

                    enc1 = this._keyStr.indexOf(input.charAt(i++));
                    enc2 = this._keyStr.indexOf(input.charAt(i++));
                    enc3 = this._keyStr.indexOf(input.charAt(i++));
                    enc4 = this._keyStr.indexOf(input.charAt(i++));

                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;

                    output = output + String.fromCharCode(chr1);

                    if (enc3 != 64) {
                        output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 != 64) {
                        output = output + String.fromCharCode(chr3);
                    }

                }

                output = Base64._utf8_decode(output);

                return output;

            },

            _utf8_encode: function(string) {
                string = string.replace(/\r\n/g, "\n");
                var utftext = "";

                for (var n = 0; n < string.length; n++) {

                    var c = string.charCodeAt(n);

                    if (c < 128) {
                        utftext += String.fromCharCode(c);
                    }
                    else if ((c > 127) && (c < 2048)) {
                        utftext += String.fromCharCode((c >> 6) | 192);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }
                    else {
                        utftext += String.fromCharCode((c >> 12) | 224);
                        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }

                }

                return utftext;
            },

            _utf8_decode: function(utftext) {
                var string = "";
                var i = 0;
                var c = c1 = c2 = 0;

                while (i < utftext.length) {

                    c = utftext.charCodeAt(i);

                    if (c < 128) {
                        string += String.fromCharCode(c);
                        i++;
                    }
                    else if ((c > 191) && (c < 224)) {
                        c2 = utftext.charCodeAt(i + 1);
                        string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                        i += 2;
                    }
                    else {
                        c2 = utftext.charCodeAt(i + 1);
                        c3 = utftext.charCodeAt(i + 2);
                        string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                        i += 3;
                    }

                }

                return string;
            }

        }

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
//            var myTop = $(".navbar.navbar-inverse").offset().top;
//            $("body").css("margin-top",-myTop + "px");

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

            $("ul#mon-access li a").click(function () {
                var lmID = $(this).attr("data-lmID");
                if(valid_id(lmID)) {
                    $.ajax({
                        async: false,
                        data: "lmID=" + lmID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/luyenthi/xuly-mon/",
                        success: function (result) {
                            location.reload();
                        }
                    });
                }
            });

            var window_width = $(window).width();
            if(window_width <= 769) {
                var len = $("#my-dock a.btn").length;
                $("#my-dock a.btn").each(function(index, element) {
                    $(element).css("width", ((window_width/len) - 10) + "px");
                });
                var sidebar = $("#my-sidebar");
                sidebar.css({"max-width":window_width*0.79 + "px","height":$(window).height() + "px"});
                sidebar.click(function (e) {
                    e.stopPropagation();
                });
//                var is_first = false;
//                <?php //if(isset($_SESSION["is_first"]) && $_SESSION["is_first"]) { ?>
//                $("body").addClass("sidebar-mobile-main");
//                sidebar.animate({left: "0px"}, "fast", "linear", function() {
//                    $(".content-wrapper, .navbar").css("opacity","0.3");
////                    $("body").removeClass("sidebar-on");
//                });
//                is_first = true;
//                <?php
//                        $_SESSION["is_first"] = false;
//                    }
//                ?>
                var stack_bottom_right = {"dir1": "up", "dir2": "left", "firstpos1": 25, "firstpos2": 25};
                $(".page-container").click(function (e) {
                    if ($("body").hasClass("sidebar-mobile-main")) {
                        sidebar.animate({left: "-80%"}, "fast", "linear", function () {
                            $("body").removeClass("sidebar-mobile-main");
                            //     $(".content-wrapper, .navbar").css("opacity", "1");
//                            if(is_first) {
//                                new PNotify({
//                                    text: 'Bấm vào khoảng trắng để đóng Menu',
//                                    addclass: 'stack-bottom-right bg-primary',
//                                    stack: stack_bottom_right
//                                });
//                                is_first = false;
//                            }
                        });
                        $(".page-container, .navbar").animate({marginLeft: "0",marginRight: "0"}, "fast", "linear");
//                        sidebar.animate({left: "-80%"}, "fast", "linear", function () {
//                            $("body").removeClass("sidebar-mobile-main");
//                            $(".content-wrapper, .navbar").css("opacity","1");
//                            if(is_first) {
//                                new PNotify({
//                                    text: 'Bấm vào khoảng trắng để đóng Menu',
//                                    addclass: 'stack-bottom-right bg-primary',
//                                    stack: stack_bottom_right
//                                });
//                                is_first = false;
//                            }
//                        });
                    }
                });
//                $("body").swipe({
//                    swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
////                        console.log(direction + " - " + distance + " - " + duration + " - " + fingerCount);
//                        if (direction == "right" && distance != 1) {
//                            if (!$(this).hasClass("sidebar-mobile-main")) {
//                                $(this).addClass("sidebar-mobile-main sidebar-on");
//                                $(".content-wrapper, .navbar").css("opacity","0.3");
//                                sidebar.animate({left: "0px"}, "fast", "linear", function() {
//                                    $("body").removeClass("sidebar-on");
//                                });
//                            }
//                        } else if (direction == "left" && distance != 1) {
//                            if ($(this).hasClass("sidebar-mobile-main")) {
//                                sidebar.animate({left: "-80%"}, "fast", "linear", function () {
//                                    $("body").removeClass("sidebar-mobile-main").removeClass("sidebar-on");
//                                    $(".content-wrapper, .navbar").css("opacity","1");
//                                });
//                            }
//                        }
//                    },
//                    threshold: 0,
//                    fingers: 'all',
//                    allowPageScroll: 'auto',
//                    excludedElements: '.noSwipe'
//                });
            }
        });
    </script>
</head>
<body style="overflow-x: hidden;">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <ul class="nav navbar-nav visible-xs-block">
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
            <!--            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-map5"></i></a></li>-->
        </ul>

        <a class="navbar-brand" href="http://localhost/www/TDUONG/luyenthi/trang-chu/"><span>Bgo Education</span></a>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
<!--            <li class="dropdown dropdown-user">-->
<!--                <a class="dropdown-toggle" data-toggle="dropdown" id="mon-show">-->
<!--                    <span><strong></strong></span>-->
<!--                    <i class="caret"></i>-->
<!--                </a>-->
<!---->
<!--                <ul class="dropdown-menu dropdown-menu-right" id="mon-access">-->
<!--                    --><?php
//                    $db = new Mon_Hoc();
//                    $result = $db->getAllLopMonHs($hsID);
//                    while ($data = $result->fetch_assoc()) {
//                        if ($data["ID_LM"] == $lmID) {
//                            echo "<li class='active'><a href='javascript:void(0)' data-lmID='$data[ID_LM]'> Môn $data[name]</a></li>";
//                        } else {
//                            echo "<li><a href='javascript:void(0)' data-lmID='$data[ID_LM]'> Môn $data[name]</a></li>";
//                        }
//                    }
//                    ?>
<!--                </ul>-->
<!--            </li>-->
        </ul>

<!--        <ul class="nav navbar-nav navbar-right">-->
<!--            <li class="dropdown dropdown-user">-->
<!--                <a class="dropdown-toggle" data-toggle="dropdown">-->
<!--                    <img src="https://localhost/www/TDUONG/hocsinh/avata/--><?php //echo $global["avata"]; ?><!--" alt=""/>-->
<!--                    <span>--><?php //echo $global["fullname"] ?><!--</span>-->
<!--                    <i class="caret"></i>-->
<!--                </a>-->
<!---->
<!--                --><?php //if(!$_SESSION["is_app"]) { ?>
<!--                <ul class="dropdown-menu dropdown-menu-right">-->
<!--                    <li><a href="#"><i class="icon-user-plus"></i> Hồ sơ</a></li>-->
<!--                    <li class="divider"></li>-->
<!--                    <li><a href="http://localhost/www/TDUONG/luyenthi/dang-xuat/"><i class="icon-switch2"></i> Đăng xuất</a></li>-->
<!--                </ul>-->
<!--                --><?php //} ?>
<!--            </li>-->
<!--        </ul>-->
    </div>
</div>
<!-- /main navbar -->
