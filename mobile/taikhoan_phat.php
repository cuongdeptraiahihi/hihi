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
	$de=$_SESSION["de"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$mau="#FFF";
	
	if($_SESSION["show_tien"]==0) {
		header("location:http://localhost/www/TDUONG/mobile/tong-quan/");
		exit();
	}
	
	$result1=get_hs_short_detail($hsID, $lmID);
	$data1=mysqli_fetch_assoc($result1);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÀI KHOẢN</title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height: 22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
       
	</head>

    <body>
                             
      	<div id="SIDEBACK"><div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1>Lịch sử trừ tiền</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata"><img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data1["avata"]; ?>" /></div>
                    <div id="main-code"><h2><?php echo $data1["cmt"];?></h2></div>
                    <div class="clear"></div>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Tổng cộng: -<?php echo format_price(get_phat_hs($hsID)); ?></h3></div>
                    <div id="main-table">
                    	<table>
                        	<tr id="table-head" class="back tr-big">
                                <th style="width:15%;"><span>Ngày</span></th>
                                <th style="width:65%;"><span>Nội dung</span></th>
                                <th style="width:20%;"><span class="fa fa-money"></span></th>
                      		</tr>
                            <?php
								if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
									$position=$_GET["begin"];
								} else {
									$position=0;
								}
								$stt=1;$display=30;
								$result=get_phat_hocsinh_sort($hsID,$position,$display);
								while($data=mysqli_fetch_assoc($result)) {
									echo"<tr class='back'>
										<td><span>".format_date($data["date"])."</span></td>
										<td><span>$data[note]</span></td>
										<td><span>-".format_money_vnd($data["price"])."</span></td>";
									echo"</tr>";
									$stt++;
								}
							?>
                        </table>
                    </div>
                    <?php
						$result2=get_phat_hocsinh($hsID);
						$sum=mysqli_num_rows($result2);
						$sum_page=ceil($sum/$display);
						if($sum_page>1) {
							$current=($position/$display)+1;
					?>
					<div class="page-number back">
						<ul>
						<?php
							if($current!=1) {
								$prev=$position-$display;
								echo"<li><a href='http://localhost/www/TDUONG/mobile/tai-khoan-phat/page-$prev/'><</a></li>";
							}
							for($page=1;$page<=$sum_page;$page++) {
								$begin=($page-1)*$display;
								if($page==$current) {
									echo"<li style='background:rgba(0,0,0,0.35);'><a href='http://localhost/www/TDUONG/mobile/tai-khoan-phat/page-$begin/' style='font-weight:600;'>$page</a></li>";
								} else {
									echo"<li><a href='http://localhost/www/TDUONG/mobile/tai-khoan-phat/page-$begin/'>$page</a></li>";
								}
							}
							if($current!=$sum_page) {
								$next=$position+$display;
								echo"<li><a href='http://localhost/www/TDUONG/mobile/tai-khoan-phat/page-$next/'>></a></li>";
							}
						?>
						</ul>
					</div>
					<?php
						}
					?>
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