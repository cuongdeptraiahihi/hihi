<?php
    ob_start();
    //session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    session_start();require_once("access_hocsinh.php");
    require_once("include/is_mobile.php");
    $hsID=$_SESSION["ID_HS"];
    $monID=$_SESSION["mon"];
    $code=$_SESSION["code"];
    $lmID=$_SESSION["lmID"];

    if(isset($_GET["sttID"]) && is_numeric($_GET["sttID"])) {
        $sttID=addslashes($_GET["sttID"]);
    } else {
        header("location:http://localhost/www/TDUONG/mobile/nhom-phat-ve/");
        exit();
    }
    $mau="#FFF";
    $result0=get_hs_short_detail($hsID,$lmID);
    $data0=mysqli_fetch_assoc($result0);
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

        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/mobile/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://bgo.edu.vn/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/font-awesome.min.css">

        <style>
            <?php require_once("include/CSS.php"); ?>
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:14px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
            /*.hideme {margin-left:-150%;opacity:0;}*/
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
                $(".popup").click(function() {
                    $(this).fadeOut(250);
                    $("#BODY").css("opacity", "1");
                });
            });
        </script>
        <script>
            window.onload = function () {
                var myScroll = new IScroll('#main-table', {
                    scrollX: true,
                    scrollY: false,
                    mouseWheel: false,
                    startX: 0
                });
            }
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

    <div class="popup animated bounceIn" id="popup-loading">
        <p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
    </div>

    <div id="SIDEBACK"><div id="BODY">

        <div id="MAIN">

            <div class="main-div back animated bounceInUp" id="main-top">
                <div id="main-person">
                  <h1>Thông tin phát vé</h1>
                    <div class="clear"></div>
                </div>
                <div id="main-avata">
                    <img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                    <a href="http://localhost/www/TDUONG/mobile/ho-so/" title="Hồ sơ cá nhân">
                        <p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                        <i class="fa fa-pencil"></i>
                    </a>

                </div>
            </div>

            <div class="main-div animated bounceInUp">
                <?php
                $query6="SELECT h.*,t.name AS truong,s.fullname FROM hocvien_info AS h 
                INNER JOIN truong AS t ON t.ID_T=h.school 
                INNER JOIN hocsinh AS s ON s.ID_HS=h.ID_HS
                WHERE h.ID_STT='$sttID' AND h.ID_HS='$hsID'";
                $result6=mysqli_query($db,$query6);
                $data6=mysqli_fetch_assoc($result6);
                ?>
                <div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Quá trình mời</h3></div>
                <div id="main-table" class="main1-table" style="overflow: auto;">
                    <table class="table">
                        <?php
//                        if($data6["video"]) {
//                            ?>
<!--                            <tr class='tr-me back tr-fixed stt'>-->
<!--                                <td colspan="5"><iframe src="--><?php //echo "https://localhost/www/TDUONG/hocsinh/game/$data6[ID_N]/$data6[video]"; ?><!--" style="max-width: 300px;margin: auto;width: 100%;"></iframe></td>-->
<!--                            </tr>-->
<!--                            --><?php
//                        }
                        if($data6["anh"]) {
                            echo"<tr class='tr-me back tr-fixed stt'>
                                    <td colspan='5'>";
                            $temp=explode("|", $data6["anh"]);
                            for($i = 0; $i < count($temp); $i++) {
                                echo"<img src='https://localhost/www/TDUONG/hocsinh/game/$data6[ID_N]/$temp[$i]' style='max-width: 300px;margin: 10px;width: 100%;' />";

                            }
                            echo"</tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <?php require_once("include/MENU.php"); ?>

    </body>
    </html>

<?php
ob_end_flush();
require_once("../model/close_db.php");
?>