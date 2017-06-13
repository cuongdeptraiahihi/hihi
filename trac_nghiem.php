<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
    $code=$_SESSION["code"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);
	$cmt=$data0["cmt"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TRẮC NGHIỆM</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:14px;line-height: 22px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
			
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
				$("#vao-thi").click(function() {
					if(confirm("Bạn có chắc chắn không?")) {
					    window.location.href='http://localhost/www/TDUONG/luyenthi/dang-nhap/<?php echo encode_data($hsID,$code)."/".$code."/".$lmID; ?>/';
                    }
				});
                $(".popup").click(function() {
                    $(this).fadeOut(250);
                    $("#BODY").css("opacity", "1");
                });
			});
		</script>
       
	</head>

    <body>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>

        <?php
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
                    default:
                        $msg = "Unknow Error!";
                        break;
                }
                echo"<div class='popup animated bounceIn' style='display:block;'>
                    <div>
                        <p class='title'>$msg</p>
                    </div>
                </div>";
            }
        ?>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Luyện thi trắc nghiệm</h1>
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
                
                <div class="main-div animated bounceInUp">
                	<ul>
                    	<li id="chart-li1" class="li-3" style="width:48.75%">
                        	<div class="main-2 back"><h3>Một số lưu ý</h3></div>
                            <ul style="margin-top:3px;" id="msg-succ">
                                <li>
                                    <div style="width:100%;margin-top:0;"><p>- Bạn phải có ít nhất 50k trong tài khoản</p></div>
                                </li>
<!--                            	<li>-->
<!--                                    <div style="width:100%;margin-top:0;"><p>- Mỗi bạn chỉ được thi 1 lần trong 1 ngày</p></div>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <div style="width:100%;margin-top:0;"><p>- Khi đã vào thi, nếu có gián đoạn, kết quả sẽ không được công nhận</p></div>-->
<!--                                </li>-->
                                <li>
                                    <div style="width:100%;margin-top:0;"><p>- Nếu không làm đủ số câu trong 1 tuần, bạn sẽ bị khóa chức năng Trắc nghiệm</p></div>
                                </li>
                                <li>
                                    <div style="width:100%;margin-top:0;"><p>!!! Chúc các bạn làm bài tốt !!!</p></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li1" class="li-3" style="width:48.75%">
                        	<div class="main-2 back"><h3>Vào thi</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                <?php
                                    $date=getdate(date("U"));
                                    $current=$date["wday"]+1;
                                    //                                    if($current!=1) {
                                        if (!check_done_options("", "khong-lam-du-cau", $hsID, $monID)) {
                                            if (true) {
                                                echo "<div style='width:100%;text-align:center;'><input type='submit' class='submit' value='Vào thi' id='vao-thi' /></div>
";
                                            } else {
                                                echo "<div style='width:100%;text-align:center;margin-top:0;'><p>Bạn không có đủ 50k trong tài khoản!</p></div>";
                                            }
                                        } else {
                                            echo "<div style='width:100%;text-align:center;margin-top:0;'><p>Bạn đã bị khóa vĩnh viễn do không làm đủ số câu trắc nghiệm trong tuần. Liên hệ Admin để mở khóa!</p></div>";
                                        }
//                                    } else {
//                                        echo "<div style='width:100%;text-align:center;margin-top:0;'><p>Chủ nhật không được làm</p></div>";
//                                    }
                                ?>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                
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