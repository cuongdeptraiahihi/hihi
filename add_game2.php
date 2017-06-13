<?php
    ob_start();
    //session_start();
    require_once("model/open_db.php");
    require_once("model/model.php");
    session_start();require_once("access_hocsinh.php");
    require_once("model/is_mobile.php");
    $hsID=$_SESSION["ID_HS"];
    $monID=$_SESSION["mon"];
    $code=$_SESSION["code"];
    $lmID=$_SESSION["lmID"];

    if(isset($_GET["sttID"]) && is_numeric($_GET["sttID"])) {
        $sttID=addslashes($_GET["sttID"]);
    } else {
        $sttID=0;
    }

    if(isset($_GET["day"])) {
        $date=addslashes($_GET["day"]);
        $check=check_unlock_ca($date);
        if($check=="off") {
            header("location:https://localhost/www/TDUONG/nhom-phat-ve/");
            exit();
        }
    } else {
        header("location:https://localhost/www/TDUONG/nhom-phat-ve/");
        exit();
    }

    $result0=get_id_group_hs($hsID);
    if(mysqli_num_rows($result0)!=0) {
        $data0 = mysqli_fetch_assoc($result0);
        $level=$data0["level"];
        $nID=$data0["ID_N"];
    } else {
        header("location:https://localhost/www/TDUONG/nhom-phat-ve/");
        exit();
    }

    $mau="#FFF";
    $result0=get_hs_short_detail($hsID,$lmID);
    $data0=mysqli_fetch_assoc($result0);

    $now=date("Y-m-d");

    $query3="SELECT h.*,t.name AS truong_name FROM hocvien_info AS h 
    INNER JOIN truong AS t ON t.ID_T=h.school
    WHERE h.ID_STT='$sttID'";
    $result3=mysqli_query($db,$query3);
    $data3=mysqli_fetch_assoc($result3);
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>GAME</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://bgo.edu.vn/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">

        <style>
            <?php require_once("include/CSS.php"); ?>
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:14px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
            /*.hideme {margin-left:-150%;opacity:0;}*/
            .img-hin {display: none;}
            .rotated {
                transform: rotate(90deg);
                -ms-transform: rotate(90deg); /* IE 9 */
                -moz-transform: rotate(90deg); /* Firefox */
                -webkit-transform: rotate(90deg); /* Safari and Chrome */
                -o-transform: rotate(90deg); /* Opera */
                max-height: auto;
            }
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {

                $(".btn-exit, .popup2, .popup .popup-close").click(function() {
                    $(".popup").fadeOut(250);
                    $("#BODY").css("opacity", "1");
                });

                <?php if($check=="on") { ?>
                $(".search-div2 ul").delegate("li a", "click", function() {
                    truong = $(this).attr("data-truong");
                    name_truong = $(this).html();
                    $("#add-truong").val(name_truong).attr("data-truong",truong);
                    $("#add-truong2").val(truong);
                    $(".search-div2").fadeOut("fast");
                });

                $("#add-truong").keyup(function() {
                    value = $(this).val();
                    if(value!="" && value!=" " && value!="%" && value!="_") {
                        $.ajax({
                            async: true,
                            data: "search_truong=" + value,
                            type: "get",
                            url: "https://localhost/www/TDUONG/xuly-game/",
                            success: function(a) {
                                $("#search-truong > ul").html(a), $("#search-truong").fadeIn("fast");
                            }
                        });
                    } else {
                        $("#search-truong").fadeOut("fast");
                    }
                });
                <?php } ?>

                $(".btn-img-left").click(function () {
                    var stt = parseInt($("img.img-show").attr("data-stt"));
                    if(stt > 0 && $("img.img-" + (stt-1)).length > 0) {
                        $("img.img-show").removeClass("img-show").addClass("img-hin").hide();
                        $("img.img-" + (stt-1)).addClass("img-show").removeClass("img-hin").show();
                    }
                });

                $(".btn-img-right").click(function () {
                    var stt = parseInt($("img.img-show").attr("data-stt"));
                    if($("img.img-" + (stt+1)).length > 0) {
                        $("img.img-show").removeClass("img-show").addClass("img-hin").hide();
                        $("img.img-" + (stt+1)).addClass("img-show").removeClass("img-hin").show();
                    }
                });

                $(".btn-rotate").click(function () {
                    var me = $("img.img-show").closest("div");
                    if(!me.hasClass("rotated")) {
                        me.css({"height": $("#popup-anh").width() + "px"}).addClass("rotated");
                        $("#popup-anh img").each(function (index, element) {
                            $(element).css("margin-top", (($("#popup-anh").width() - $(element).height()) / 2 - 10) + "px");
                        });
                    } else {
                        me.css({"height":"auto"}).removeClass("rotated");
                        $("#popup-anh img").css("margin-top","0px");
                    }
                });

                $(".btn-rotate2").click(function () {
                    var me = $("img.img-show").closest("div");
                    if(!me.hasClass("rotated")) {
                        me.css({"height": $("#popup-anh-done").width() + "px"}).addClass("rotated");
                        $("#popup-anh-done img").each(function (index, element) {
                            $(element).css("margin-top", (($("#popup-anh-done").width() - $(element).height()) / 2 - 10) + "px");
                        });
                    } else {
                        me.css({"height":"auto"}).removeClass("rotated");
                        $("#popup-anh-done img").css("margin-top","0px");
                    }
                });

                $("#xem-anh").click(function () {
                    $("#popup-anh").fadeIn("fast");
                });

                $("#xem-anh-done").click(function () {
                    $("#popup-anh-done").fadeIn("fast");
                });

                $("#feedback-ok").click(function() {
                    $(".popup").fadeOut(250);
                    $("#popup-feedback").fadeIn("fast");
                    $("#BODY").css("opacity", "1");
                });

                $(".btn-next1").click(function() {
                    $(".popup").fadeOut(250);
                    $("#popup-feedback2").fadeIn("fast");
                    $("#BODY").css("opacity", "1");
                });

                $(".btn-next2").click(function() {
                    $(".popup").fadeOut(250);
                    $("#popup-feedback3").fadeIn("fast");
                    $("#BODY").css("opacity", "1");
                });
            });
        </script>

    </head>

    <body>

    <div class="popup animated bounceIn" id="popup-view">
        <div class="popup-close"><i class="fa fa-close"></i></div>
        <div>
            <p class="title"></p>
            <div>
                <button class="submit2 btn-exit"><i class="fa fa-check"></i></button>
            </div>
        </div>
    </div>

    <div class="popup animated bounceIn" id="popup-anh" style="width: 50%;left: 20%;top: 15%;">
        <div class="popup-close"><i class="fa fa-close"></i></div>
        <div style="position: relative;">
            <div style="max-height:400px;overflow: auto;">
                <div>
                    <?php
                    if($data3["anh"]) {
                        $class="class='img-show img-0'";
                        $temp=explode("|", $data3["anh"]);
                        for($i = 0; $i < count($temp); $i++) {
                            echo"<img $class src='https://localhost/www/TDUONG/hocsinh/game/$data3[ID_N]/$temp[$i]' data-stt='$i' style='width: 100%;' />";
                            $class="class='img-hin img-".($i+1)."'";
                        }
                    }
                    ?>
                </div>
            </div>
            <i class="fa fa-angle-left btn-img-left" style="position: absolute;left: -25px;top:0;font-size: 50px;"></i>
            <i class="fa fa-angle-right btn-img-right" style="position: absolute;right: -25px;top:0;font-size: 50px;"></i>
            <div style="margin-top: 10px;">
                <button class="submit2 btn-exit"><i class="fa fa-check"></i></button>
                <button class="submit2 btn-rotate"><i class="fa fa-refresh"></i></button>
            </div>
        </div>
    </div>

    <div class="popup animated bounceIn" id="popup-anh-done" style="width: 50%;left: 20%;top: 15%;">
        <div class="popup-close"><i class="fa fa-close"></i></div>
        <div style="position: relative;">
            <div style="max-height:400px;overflow: auto;">
                <div>
                    <?php
                    if($data3["anh_done"]) {
                        $class="class='img-show img-0'";
                        $temp=explode("|", $data3["anh_done"]);
                        for($i = 0; $i < count($temp); $i++) {
                            echo"<img $class src='https://localhost/www/TDUONG/hocsinh/game/done/$data3[ID_N]/$temp[$i]' data-stt='$i' style='width: 100%;' />";
                            $class="class='img-hin img-".($i+1)."'";
                        }
                    }
                    ?>
                </div>
            </div>
            <i class="fa fa-angle-left btn-img-left" style="position: absolute;left: -25px;top:0;font-size: 50px;"></i>
            <i class="fa fa-angle-right btn-img-right" style="position: absolute;right: -25px;top:0;font-size: 50px;"></i>
            <div style="margin-top: 10px;">
                <button class="submit2 btn-exit"><i class="fa fa-check"></i></button>
                <button class="submit2 btn-rotate2"><i class="fa fa-refresh"></i></button>
            </div>
        </div>
    </div>

    <div class="popup animated bounceIn" id="popup-loading">
        <p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
    </div>

    <div id="BODY">

        <div id="MAIN">

            <div class="main-div back animated bounceInUp" id="main-top">
                <div id="main-person">
                  <h1 style='line-height:98px;'>Phát vé ngày <?php echo format_dateup($date); ?></h1>
                    <div class="clear"></div>
                </div>
                <div id="main-avata">
                    <img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                    <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        <p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                        <i class="fa fa-pencil"></i>
                    </a>

                </div>
            </div>

            <?php
                $error="";
                if(isset($_POST["done-co"])) {
                    if(isset($_POST["done-ca1"]) && isset($_POST["done-ca2"]) && isset($_POST["done-ca3"])) {
                        $ca1=addslashes($_POST["done-ca1"]);
                        $ca2=addslashes($_POST["done-ca2"]);
                        $ca3=addslashes($_POST["done-ca3"]);

                        $check2 = true;
                        $total = count($_FILES["fileToUpload-imagedone"]["name"]);
                        for ($f = 0; $f < $total; $f++) {
                            if ($_FILES["fileToUpload-imagedone"]["type"][$f] == "image/jpeg" || $_FILES["fileToUpload-imagedone"]["type"][$f] == "image/png" || $_FILES["fileToUpload-imagedone"]["type"][$f] == "image/jpg") {
                                $check2 = true;
                            } else {
                                $check2 = false;
                                break;
                            }
                        }
                        if ($total > 30) {
                            $check2 = false;
                        }
                        if ($check2) {
                            if (!is_dir("hocsinh/game/done/$nID")) {
                                mkdir("hocsinh/game/done/$nID");
                            }
                            $anh = array();
                            for ($f = 0; $f < $total; $f++) {
                                move_uploaded_file($_FILES["fileToUpload-imagedone"]["tmp_name"][$f], "hocsinh/game/done/$nID/" . $_FILES["fileToUpload-imagedone"]["name"][$f]);
                                $pre = explode(".", $_FILES["fileToUpload-imagedone"]["name"][$f]);
                                $temp = strtolower(end($pre));
                                if ($temp == "jpg" || $temp == "png" || $temp == "jpeg") {
                                    $filename = $nID . "-" . $_FILES["fileToUpload-imagedone"]["size"][$f] . "-" . $f . "-" . $hsID . "." . end($pre);
                                    rename("hocsinh/game/done/$nID/" . $_FILES["fileToUpload-imagedone"]["name"][$f], "hocsinh/game/done/$nID/$filename");
                                    $anh[] = $filename;
                                }
                            }
                            if (count($anh) != 0) {
                                $anh = implode("|", $anh);
                            } else {
                                $anh = "";
                            }
                            if ($anh != "" && $total != 0) {
                                $query1 = "UPDATE hocvien_info SET anh_done='$anh' WHERE ID_STT='$sttID'";
                                mysqli_query($db, $query1);
                            }
                        }

                        if(check_ca_phat_ve($ca1)!="none" && check_ca_phat_ve($ca2)!="none" && check_ca_phat_ve($ca3)!="none") {
                            $query1 = "UPDATE hocvien_info SET cahoc='".$ca1."|".$ca2."|".$ca3."' WHERE ID_STT='$sttID'";
                            mysqli_query($db, $query1);
                        }
//                        header("location:https://localhost/www/TDUONG/them-phat-ve/$sttID/$date/");
                        header("location:https://localhost/www/TDUONG/phat-ve/$date/");
                        exit();
                    } else {
                        $error = "<div class='popup popup2' style='display:block'>
                            <p>Vui lòng nhập đầy đủ thông tin</p>
                        </div>";
                    }
                }
                if(isset($_POST["ok"])) {
                    if($check == "on") {
                        if (isset($_POST["name"]) && isset($_POST["class"]) && isset($_POST["school2"])) {
                            $check = false;
                            $anh = false;
                            $video = false;
                            $status = "";
                            $the = fill_zero(addslashes($_POST["the"]),7);
                            $name = addslashes($_POST["name"]);
                            $class = addslashes($_POST["class"]);
                            $school = addslashes($_POST["school2"]);
                            $number = addslashes($_POST["number"]);
                            $number2 = addslashes($_POST["number-parent"]);
                            $linkfb = addslashes($_POST["link-fb"]);
                            $ca = $date;
                            //                                if (!($_FILES["fileToUpload-video"]["error"] > 0)) {
                            //                                    $check = true;
                            //                                }
                            $total = count($_FILES["fileToUpload-image"]["name"]);
                            //                                if ($total > 0) {
                            //                                    $check = true;
                            //                                }
                            //                                if($sttID!=0) {
                            //                                    $check=true;
                            //                                }
                            $check = true;
                            if ($check) {
                                $check2 = true;
                                for ($f = 0; $f < $total; $f++) {
                                    if ($_FILES["fileToUpload-image"]["type"][$f] == "image/jpeg" || $_FILES["fileToUpload-image"]["type"][$f] == "image/png" || $_FILES["fileToUpload-image"]["type"][$f] == "image/jpg") {
                                        $check2 = true;
                                    } else {
                                        $check2 = false;
                                        break;
                                    }
                                }
                                if ($total > 30) {
                                    $check2 = false;
                                }
                                if ($sttID != 0) {
                                    $check2 = true;
                                }
                                if ($check2) {
                                    if (!is_dir("hocsinh/game/$nID")) {
                                        mkdir("hocsinh/game/$nID");
                                    }
                                    $anh = array();
                                    for ($f = 0; $f < $total; $f++) {
                                        move_uploaded_file($_FILES["fileToUpload-image"]["tmp_name"][$f], "hocsinh/game/$nID/" . $_FILES["fileToUpload-image"]["name"][$f]);
                                        $pre = explode(".", $_FILES["fileToUpload-image"]["name"][$f]);
                                        $temp = strtolower(end($pre));
                                        if ($temp == "jpg" || $temp == "png" || $temp == "jpeg") {
                                            $filename = $nID . "-" . $_FILES["fileToUpload-image"]["size"][$f] . "-" . $f . "-" . $hsID . "." . end($pre);
                                            rename("hocsinh/game/$nID/" . $_FILES["fileToUpload-image"]["name"][$f], "hocsinh/game/$nID/$filename");
                                            $anh[] = $filename;
                                        }
                                    }
                                    if (count($anh) != 0) {
                                        $anh = implode("|", $anh);
                                    } else {
                                        $anh = "";
                                    }
                                    //                                        if (!($_FILES["fileToUpload-video"]["error"] > 0)) {
                                    //                                            if ($_FILES["fileToUpload-video"]["size"] < 10485760) {
                                    //                                                move_uploaded_file($_FILES["fileToUpload-video"]["tmp_name"], "hocsinh/game/$nID/" . $_FILES["fileToUpload-video"]["name"]);
                                    //                                                $pre = explode(".", $_FILES["fileToUpload-video"]["name"]);
                                    //                                                $filename = $nID . "-" . $_FILES["fileToUpload-video"]["size"] . "." . end($pre);
                                    //                                                rename("hocsinh/game/$nID/" . $_FILES["fileToUpload-video"]["name"], "hocsinh/game/$nID/$filename");
                                    //                                                if($sttID!=0) {
                                    //                                                    if($anh != "") {
                                    //                                                        $query1 = "UPDATE hocvien_info SET the='$the',name='$name',class='$class',school='$school',sdt='$number',sdt_phuhuynh='$number2',anh='$anh',video='$filename',facebook='$linkfb',ca='$ca' WHERE ID_STT='$sttID'";
                                    //                                                    } else {
                                    //                                                        $query1 = "UPDATE hocvien_info SET the='$the',name='$name',class='$class',school='$school',sdt='$number',sdt_phuhuynh='$number2',video='$filename',facebook='$linkfb',ca='$ca' WHERE ID_STT='$sttID'";
                                    //                                                    }
                                    //                                                    mysqli_query($db, $query1);
                                    //                                                } else {
                                    //                                                    $query1 = "INSERT INTO hocvien_info (the,ID_N,ID_HS,name,class,school,sdt,sdt_phuhuynh,anh,video,facebook,ca)
                                    //                                                                                VALUES ('$the','$nID','$hsID','$name','$class','$school','$number','$number2','$anh','$filename','$linkfb','$ca')";
                                    //                                                    mysqli_query($db, $query1);
                                    //                                                    $sttID=mysqli_insert_id($db);
                                    //                                                }
                                    //                                                header("location:https://localhost/www/TDUONG/them-phat-ve/$sttID/");
                                    //                                                exit();
                                    //
                                    //                                            } else {
                                    //                                                $error = "<div class='popup' style='display:block'>
                                    //                                                    <p>Bạn chỉ được đăng video dưới 10MB</p>
                                    //                                                </div>";
                                    //                                            }
                                    //                                        } else {
                                    if ($sttID != 0) {
                                        if ($anh != "" && $total != 0) {
                                            $query1 = "UPDATE hocvien_info SET the='$the',name='$name',school='$school',sdt='$number',sdt_phuhuynh='$number2',anh='$anh',facebook='$linkfb' WHERE ID_STT='$sttID'";
                                        } else {
                                            $query1 = "UPDATE hocvien_info SET the='$the',name='$name',school='$school',sdt='$number',sdt_phuhuynh='$number2',facebook='$linkfb' WHERE ID_STT='$sttID'";
                                        }
                                        mysqli_query($db, $query1);
//                                        header("location:https://localhost/www/TDUONG/them-phat-ve/$sttID/$date/");
                                        header("location:https://localhost/www/TDUONG/phat-ve/$date/");
                                        exit();
                                    } else {
                                        $query2 = "SELECT COUNT(ID_STT) AS dem FROM hocvien_info WHERE ca='$ca'";
                                        $result2 = mysqli_query($db, $query2);
                                        $data2 = mysqli_fetch_assoc($result2);
                                        if ($data2["dem"] < get_sl_ca_phat_ve($ca)) {
                                            if (!check_hs_tuyen_fb($linkfb) && !check_hs_tuyen_sdt($number)) {
                                                $query1 = "INSERT INTO hocvien_info (the,ID_N,ID_HS,name,class,school,sdt,sdt_phuhuynh,anh,video,facebook,ca)
                                                                            VALUES ('$the','$nID','$hsID','$name','$class','$school','$number','$number2','$anh','','$linkfb','$ca')";
                                                mysqli_query($db, $query1);
                                                $sttID = mysqli_insert_id($db);
                                                header("location:https://localhost/www/TDUONG/phat-ve/$date/");
//                                                header("location:https://localhost/www/TDUONG/them-phat-ve/$sttID/$date/");
                                                exit();
                                            } else {
                                                $error = "<div class='popup popup2' style='display:block'>
                                                            <p>Học sinh này đã được mời!</p>
                                                        </div>";
                                            }
                                        } else {
                                            $error = "<div class='popup popup2' style='display:block'>
                                                        <p>Đã đủ 200 người, bạn không thể thêm</p>
                                                    </div>";
                                        }
                                    }
                                    //                                        }
                                } else {
                                    $error = "<div class='popup popup2' style='display:block'>
                                            <p>Bạn chỉ được đăng ảnh định dạng png, jpeg, jpg và tối đa 30 ảnh!</p>
                                        </div>";
                                }
                            } else {
                                $error = "<div class='popup popup2' style='display:block'>
                                        <p>Vui lòng đăng ảnh hoặc video</p>
                                    </div>";
                            }
                        } else {
                            $error = "<div class='popup popup2' style='display:block'>
                                <p>Vui lòng nhập đầy đủ thông tin</p>
                            </div>";
                        }
                    } else {
                        $error = "<div class='popup' style='display:block'>
                            <p>Hiện đã khóa sửa thông tin!</p>
                        </div>";
                    }
                }
                echo $error;
            ?>

            <form action="https://localhost/www/TDUONG/them-phat-ve/<?php echo $sttID; ?>/<?php echo $date; ?>/" method="post" enctype="multipart/form-data">
                <div class="main-div animated bounceInUp">
                    <ul>
                        <li id="chart-li1">
                            <div class="main-2 back"><h3>Thông tin người nhận</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div style="width:100%;margin-top:0;text-align:center;"><p>Điền đầy đủ thông tin của người nhận</p></div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>ID vé</p></div>
                                    <div style="width:78%;"><input type="number" name="the" id="the" value="<?php echo $data3["the"] ?>" placeholder="Mã vẽ" autocomplete="off" style="width:96%;padding:2%" class="input" /></div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>Họ và tên</p></div>
                                    <div style="width:78%;"><input type="text" name="name" id="name" placeholder="Họ tên" value="<?php echo $data3["name"]; ?>" autocomplete="off" style="width:96%;padding:2%" class="input" /></div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>Trường</p></div>
                                    <div style="width:78%;"><input type="text" name="school" id="add-truong" value="<?php echo $data3["truong_name"]; ?>" data-truong="<?php echo $data3["school"] ?>" placeholder="Trường" autocomplete="off" style="width:96%;padding:2%" class="input" /><input type="hidden" name="school2" id="add-truong2" value="<?php echo $data3["school"] ?>" data-truong="0"/></div>
                                    <nav id="search-truong" class="search-div2" style="float: right;top:auto;margin-top:2px;width: 80%;">
                                        <ul>
                                        </ul>
                                    </nav>
                                </li>
                                <div class="clear"></div>
                                <li>
                                    <div style="width: 20%;"><p>Lớp</p></div>
                                    <div style="width:58%;">
                                        <?php
                                        $query1="SELECT note2 FROM options WHERE content='$date' AND type='buoi-phat-ve'";
                                        $result1=mysqli_query($db, $query1);
                                        $data1=mysqli_fetch_assoc($result1);
                                        if($data1["note2"]=="none" || $data1["note2"]=="") {
                                            echo "<p><input type='radio' name='class' class='check' value='$data1[note2]' checked='checked' /><span> Lớp ".str_replace("-"," lên ",$data1["note2"])."</span></p>";
                                        } else {
                                            echo "<p><input type='radio' name='class' class='check' value='10-11' ";if($data3["class"]=="10-11"){echo"checked='checked'";} echo" /><span> Lớp 10 lên 11</span> ";
                                            echo " <input type='radio' name='class' class='check' value='11-12' ";if($data3["class"]=="11-12"){echo"checked='checked'";} echo" /><span> Lớp 11 lên 12</span></p>";
                                        }
                                        ?>
                                    </div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>SĐT cá nhân</p></div>
                                    <div style="width:78%;"><input type="number" name="number" id="number" value="<?php echo $data3["sdt"] ?>" placeholder="Số điện thoại" autocomplete="off" style="width:96%;padding:2%" class="input" /></div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>SĐT phụ huynh</p></div>
                                    <div style="width:78%;"><input type="number" name="number-parent" id="number-parent" value="<?php echo $data3["sdt_phuhuynh"] ?>" placeholder="Số điện thoại phụ huynh" autocomplete="off" style="width:96%;padding:2%" class="input" /></div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>Link facebook</p></div>
                                    <div style="width:78%;"><input type="text" name="link-fb" id="link-fb" value="<?php echo $data3["facebook"] ?>" placeholder="Link facebook" autocomplete="off" style="width:96%;padding:2%" class="input" /></div>
                                </li>
    <!--                                    <li>-->
    <!--                                        <div style="width:100%;">-->
    <!--                                            <select class="input" name="ca-thi" style="width: 100%;">-->
    <!--                                                <option>Chọn buổi diễn thuyết</option>-->
    <!--                                                <option value="2017-04-23">23/04/2017</option>-->
    <!--                                                <option value="2017-04-30">30/04/2017</option>-->
    <!--                                                <option value="2017-05-07">07/05/2017</option>-->
    <!--                                                <option value="2017-05-14">14/05/2017</option>-->
    <!--                                            </select>-->
    <!--                                        </div>-->
    <!--                                    </li>-->
                                <li>
                                    <div style="width: 20%;"><p>Quá trình mời</p></div>
                                    <div style="width:78%;">
                                        <input type="file" name="fileToUpload-image[]" multiple="multiple" style="width: 20%;" class="submit" id="fileToUpload"/>
                                        <?php if($sttID!=0) { ?>
                                        <button class="submit" type="button" id="xem-anh">Xem ảnh</button>
                                        <?php } ?>
                                    </div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>Ghi chú của thầy</p></div>
                                    <div style="width:78%;">
                                        <p><?php echo $data3["note"]; ?></p>
                                    </div>
                                </li>
    <!--                                    <li>-->
    <!--                                        <div style="width: 20%;"><p>Video</p></div>-->
    <!--                                        <div style="width:78%;"><input type="file" name="fileToUpload-video" class="submit" style="width: 20%" id="fileToUpload"/></div>-->
    <!--                                    </li>-->
                                <li>
                                    <div style="width:100%;margin-top:0;text-align:right;"><button class="submit" name="ok" id="ok">Nhập</button></div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>

                <div class="main-div animated bounceInUp">
                    <ul>
                        <li id="chart-li1">
                            <div class="main-2 back"><h3>Phản hồi</h3></div>
                            <ul style="margin-top:3px;">
<!--                                --><?php //if($data3["anh_done"] && $data3["anh_done"]!="") { ?>
                                <?php if($data3["anh_done"]) { ?>
                                    <li>
                                        <div style="width:100%;margin-top:0;text-align:center;"><p>Đã có phản hồi</p></div>
                                    </li>
                                <?php } ?>
                                <li>
                                    <div style="width: 20%;"><p>Ảnh phản hồi</p></div>
                                    <div style="width:78%;">
                                        <input type="file" name="fileToUpload-imagedone[]" multiple="multiple" style="width: 20%;" class="submit" />
                                        <?php if($sttID!=0 && $data3["anh_done"]) { ?>
                                            <button class="submit" type="button" id="xem-anh-done">Xem ảnh</button>
                                        <?php } ?>
                                    </div>
                                </li>
                                <li>
                                    <div style="width: 20%;"><p>Ca học thử</p></div>
                                    <div style="width:78%;">
                                        <?php
                                            $temp=explode("|",$data3["cahoc"]);
                                            if(count($temp)==3) {
                                                $ca1 = $temp[0];
                                                $ca2 = $temp[1];
                                                $ca3 = $temp[2];
                                            } else {
                                                $ca1=$ca2=$ca3=NULL;
                                            }
                                        ?>
                                        <select class="submit" name="done-ca1" style="margin-top: 10px;">
                                            <option>Cụm 1</option>
                                            <option value="2-4h-5h30" <?php if($ca1=="2-4h-5h30") echo"selected='selected'"; ?>>Thứ 2, 4h - 5h30 chiều</option>
                                            <option value="2-8h-9h30" <?php if($ca1=="2-8h-9h30") echo"selected='selected'"; ?>>Thứ 2, 8h - 9h30 tối</option>
                                            <option value="3-8h-9h30" <?php if($ca1=="3-8h-9h30") echo"selected='selected'"; ?>>Thứ 3, 8h - 9h30 tối</option>
                                        </select>
                                        <select class="submit" name="done-ca2" style="margin-top: 10px;">
                                            <option>Cụm 2</option>
                                            <option value="4-4h-5h30" <?php if($ca2=="4-4h-5h30") echo"selected='selected'"; ?>>Thứ 4, 4h - 5h30 chiều</option>
                                            <option value="4-8h-9h30" <?php if($ca2=="4-8h-9h30") echo"selected='selected'"; ?>>Thứ 4, 8h - 9h30 tối</option>
                                            <option value="5-8h-9h30" <?php if($ca2=="5-8h-9h30") echo"selected='selected'"; ?>>Thứ 5, 8h - 9h30 tối</option>
                                        </select>
                                        <select class="submit" name="done-ca3" style="margin-top: 10px;">
                                            <option>Cụm 3</option>
                                            <option value="6-4h-5h30" <?php if($ca3=="6-4h-5h30") echo"selected='selected'"; ?>>Thứ 6, 4h - 5h30 chiều</option>
                                            <option value="6-8h-9h30" <?php if($ca3=="6-8h-9h30") echo"selected='selected'"; ?>>Thứ 6, 8h - 9h30 tối</option>
                                            <option value="7-8h-9h30" <?php if($ca3=="7-8h-9h30") echo"selected='selected'"; ?>>Thứ 7, 8h - 9h30 tối</option>
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div style="width:100%;margin-top:0;text-align:right;"><button class="submit" name="done-co">Nhập phản hồi</button></div>
                                </li>
<!--                                --><?php //} else { ?>
<!--                                <li>-->
<!--                                    <div style="width: 20%;"><p>Tiến hành phản hồi</p></div>-->
<!--                                    <div style="width:78%;">-->
<!--                                        <button class="submit" type="button" id="feedback-ok">Phản hồi</button>-->
<!--                                    </div>-->
<!--                                </li>-->
<!--                                --><?php //} ?>
                            </ul>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            </form>

            <?php require_once("include/IN.php"); ?>
        </div>

    </div>

    <?php require_once("include/MENU.php"); ?>

    </body>
    </html>

<?php
ob_end_flush();
require_once("model/close_db.php");
?>