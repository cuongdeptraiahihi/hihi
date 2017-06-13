<?php
	ob_start();
	//session_start();

	require_once("../model/open_db.php");

	require_once("../model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("include/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
    $de=$_SESSION["de"];
	$mau="#FFF";
	$result1=get_hs_short_detail($hsID, $lmID);
	$data1=mysqli_fetch_assoc($result1);

    $check0=check_khoa($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>LEVEL</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/mobile/css/tongquan.css"/> 
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			/*#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:12px;padding:10px;margin:3px 0 3px 0;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;}#chart-li1 ul {padding:5px 0 5px 0;border-radius:10px;margin-top:3px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:49%;}#chart-li1 ul li > div p {margin-left:10px;letter-spacing:0.5px;color:#FFF;font-size:12px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:12px;background:rgba(0,0,0,0.15);padding:5px;display:block;}.win {color:red !important;}.lose {color:yellow !important;}.draw {color:cyan !important;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
			/*.td-action {left:50%;}*/
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:12px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:65%;color:yellow;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:15px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:120px;color:yellow;}#chart-li1 ul li > div#main-star p {color:yellow;font-size:100px;left:0 !important;;}#chart-li1 ul li > div p {color:#FFF;font-size:12px;line-height: 22px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
            #thongbao-menu>ul>li {background: #000;border: 2px solid #000;}#thongbao-menu>ul>a, #thongbao-menu>ul>li>a, #thongbao-menu>ul>li ol p, #MAIN .main-div #main-table table th span, #MAIN .main-div #main-table table td span, #thongbao-menu>ul>li ol.tb-con p, #thongbao-menu>ul>li ol>a, #MAIN #main-top #main-avata a i, .popup p {color:yellow !important;font-weight: 400 !important;}#NAVBAR>ul>li{background:#000 !important;opacity: 1 !important;}#NAVBAR>ul>li>a{color:yellow;}.back, #NAVBAR>ul>li {background: yellow;}#MAIN .main-div .main-1 h3, #MAIN .main-div .main-2 h3 {color:#000;font-weight: 600;}#MAIN .main-div #main-table table tr.tr-big {background: #000;}
            .ask .sub-ask, .popup, #menu-chon-xo {background: #000 !important;}.ask .sub-ask ul li span, #NAVBAR ul li ol a {color:yellow;}.fixed-action-btn ul li p {color:yellow;background: #000;}.btn-floating .my-btn-big, #SMS, #MAIN #main-top #main-avata:hover {background: yellow !important;}.my-btn i, #SMS a span, #SMS a i {color:#000;}#MAIN #main-top #main-avata {border:3px solid #000;}#main-tb ul li .td-content img {border: 2px solid yellow;}.submit2, .submit {border: 1px solid yellow;border-bottom: 2px solid yellow;background: #000;color:yellow;}
            .td-action {left:50%;display:none;}#NAVBAR ul li ol {border-top:1px solid rgba(255,255,0,0.15) !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
                <?php //if($_SESSION["test"]==0) { ?>
                $("#MAIN .main-div #main-table table tr td button.thachdau").click(function(){if(del_td=$(this).closest("td"),maso=$(this).attr("data-maso"),chap=0,$.isNumeric(chap)&&chap>=0&&chap<=5&&""!=maso){if("<?php echo base64_encode($_SESSION["cmt"]);?>"==maso)alert("Bạn không thể thách đấu chính mình!");else if(confirm("Nếu bạn mang bài về nhà làm thì sẽ thua luôn! Bạn có chắc chắn gửi lời thách đấu này?"))return $("#BODY").css("opacity","0.3"),$.ajax({url:"http://localhost/www/TDUONG/mobile/xuly-thachdau/",async:!1,data:"maso="+maso+"&chap="+chap,type:"post",success:function(a){$("#BODY").css("opacity","1"),alert(a),location.reload()}}),!1}else alert("Vui lòng cung cấp đầy đủ thông tin và chính xác!")});
                $("#MAIN .main-div #main-table table tr.friend").swipe({swipeLeft:function(t,i,e,n,d){me=$(this),me.find("td.td-old").animate({left:"-100%"},200,function(){$(this).hide(),me.find("td.td-action").show().animate({left:"0"},200)})},swipeRight:function(t,i,e,n,d){me=$(this),me.find("td.td-action").animate({left:"50%"},200,function(){$(this).hide(),me.find("td.td-old").show().animate({left:"0"},200)})},threshold:75,triggerOnTouchEnd:!1});

                <?php //} ?>
			});
		</script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/mobile/js/canvasjs.min.js"></script>
       
	</head>

    <body>
                             
      	<div id="SIDEBACK"><div id="BODY">
            
            <div id="MAIN">

                <?php
                    //if($data1["level"]%5==0 && $data1["level"]!=0) {
                    echo"<div class='main-div back animated bounceInUp' id='main-top'>
                        <div id='main-person'>
                            <h1><img src='https://localhost/www/TDUONG/images/level_up.png' style='height: 98px;' /></h1>
                            <div class='clear'></div>
                        </div>
                        <div class='clear'></div>
                    </div>";
                    /*} else {
                    echo"<div class='main-div back animated bounceInUp' id='main-top'>
                        <div id='main-person'>
                            <h1 style='color:#000;font-weight: 600;'>Level</h1>
                            <div class='clear'></div>
                        </div>
                    </div>";
                    }*/
                ?>
                
                <div class="main-div animated bounceInUp">
                	<ul>
                    	<li id="chart-li1">
                            <ul style="background: #000;">
                                <li>
                                    <div id="main-star" class="hvr-buzz">
                                        <i class="fa fa-level-up" style="display:inline-block;"></i>
                                        <p style="display:inline-block;"><?php echo $data1["level"]; ?></p>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li id="main-tb" style="margin-top: 15px;">
                            <div class="main-2 back"><h3>Thưởng lớn</h3></div>
                            <ul style="margin-top:3px;background: #000;">
                                <?php
                                $dem=0;
                                $result=get_log_hs($hsID,"len-level-big-$monID");
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<li>
                                        <div class='td-content'>
                                            <img src='https://localhost/www/TDUONG/hocsinh/avata/$data1[avata]' />
                                            <p>$data[content] (".format_datetime($data["datetime"]).")</p>
                                            <div class='clear'></div>
                                        </div>
                                    </li>";
                                    $dem++;
                                }
                                if($dem==0) {
                                    echo"<li>
                                        <div class='td-content'>
                                            <img src='https://localhost/www/TDUONG/hocsinh/avata/$data1[avata]' />
                                            <p>Hãy thách đấu thật nhiều để nhận những phần thưởng hấp dẫn và nâng level của mình!!!</p>
                                            <div class='clear'></div>
                                        </div>
                                    </li>";
                                }
                                ?>
                            </ul>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                
                <div class="main-div animated bounceInUp">
                    <div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Thưởng nhỏ</h3></div>
                    <div id="main-table">
                        <table>
                            <tr id="table-head" class="back tr-big">
                                <th style="width:30%;"><span>Thời gian</span></th>
                                <th style="width:70%;"><span>Nội dung</span></th>
                            </tr>
                            <?php
                            $result=get_log_hs($hsID,"len-level-$monID");
                            while($data=mysqli_fetch_assoc($result)) {
                                echo"<tr style='background: #000;'>
										<td><span>".format_datetime($data["datetime"])."</span></td>
										<td><span>$data[content]</span></td>
									</tr>";
                                $dem++;
                            }
                            if($dem==0) {
                                echo"<tr style='background: #000;'>
                                    <td colspan='2'><span>Ở đây trống trải quá! :((</span></td>
                                </tr>";
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