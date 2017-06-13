<?php
	ob_start();
	//session_start();

	require_once("../model/open_db.php");

	require_once("../model/model.php");
session_start();
	require_once("include/is_mobile.php");
	$color=array("#2C84BD","#69b42e","cyan","red","yellow");
	$shadow=array("#04344C","#246E2C","blue","brown","orange");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TRANG CHỦ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/hover.css" media="all" />
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/animate.css" media="all" />
       	<link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/ai.css" type="text/css" />
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/font-awesome.min.css">
        
        <style>
			#back-one {width:80%;height:auto;position:absolute;z-index:9;left:10%;top:10%;}#back-one > ul {width:350px;margin:auto;}#back-one > ul > li {width:80px;display:list-item;margin-right:135px;height:80px;position:relative;margin-bottom:60px;}#back-one > ul > li:last-child {margin-right:0;}#back-one > ul > li .back {width:100%;height:100%;border-radius:1000px;border:2px solid #dfe0e4;}#back-one > ul > li i.my-arrow {color:gray;opacity:0.7;font-size:2.5em;position:absolute;z-index:9;width:20%;top:35%;}#back-one > ul > li .back-con {position:absolute;z-index:9;top:15%;width:250%;left:260%;text-align:left;}#back-one > ul > li .back-con .h2-line {width:35px;margin:2px 0 5px 0;}#back-one > ul > li .back-con h2 {text-transform:uppercase;font-size:1em;line-height:28px;font-weight:600;}
			<?php
				for($i=1;$i<=5;$i++) {
					echo"#back$i {background:".$color[$i-1].";-moz-box-shadow:inset 15px 15px 50px ".$shadow[$i-1].";-webkit-box-shadow: inset 15px 15px 50px ".$shadow[$i-1].";box-shadow:inset 15px 15px 50px ".$shadow[$i-1].";}";
				}
			?>
			#back-one > ul > li .noi {width:100%;height:100%;border-radius:1000px;background:#FFF;position:absolute;z-index:9;-moz-box-shadow:5px 0px 5px gray;-webkit-box-shadow:5px 0px 5px gray;box-shadow:5px 0px 5px gray;border:2px solid #dfe0e4;top:0;text-align:center;left:0;}#back-one > ul > li .noi a {display:block;color:gray;}#back-one > ul > li .noi:hover {background:gray;transition-duration:0.5s;-webkit-transition-duration:0.5s;}#back-one > ul > li .noi:hover a {color:#FFF;transition-duration:0.5s;-webkit-transition-duration:0.5s;}#back-one > ul > li .noi i {font-size:2em;font-weight:lighter;line-height:80px;display:block;}
			
			@media screen and (max-width: 500px) {
				#back-one > ul {width:40% !important;}
				#back-one > ul > li {margin-bottom:160px;}
				#back-one > ul > li .back-con {position:absolute;z-index:9;width:170%;text-align:center;left:-10%;top:130%;}
				#back-one > ul > li .back-con .h2-line {width:35px;margin:3px auto 5px auto;}
				#back-one > ul > li i.my-arrow {display:none !important;}
			}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				var time = 500;
				var time2 = 150;
				$("#back-one > ul > li, .my-arrow, .back-con").hide();
				for(i=1;i<=5;i++) {
					$("#li-"+i).fadeIn("fast");
					$("#noi"+i).animate({left: "25%",top: "15%"},time);
				}
				setTimeout(function() {
					for(i=1;i<=5;i++) {
						$(".my-arrow-"+i+"-1").delay(time2).fadeIn(time2);
						$(".my-arrow-"+i+"-2").delay(time2*2).fadeIn(time2);
						$(".back-con-"+i).delay(time2*3).fadeIn(time2);
					}
				},time2);
			});
		</script>
       
	</head>

    <body>
                             
      	<div id="SIDEBACK"><div id="BODY">
            
            <div id="MAIN">
            	
                <div id="back-one">
                	
                    <ul>
                    <?php
						$icon=array("fa-sign-in","fa-camera","fa-map","fa-smile-o","fa-cloud");
						$title=array("Đăng nhập","Tài liệu","Video","Học sinh mới","Ngoại khóa");
						$link=array("sign_in.php","#","#","#","#");
						for($i=1;$i<=5;$i++) {
							echo"<li id='li-$i'>
								<div class='back' id='back$i'></div>
								<div class='noi hvr-pulse-grow' id='noi$i'>
									<a href='".$link[$i-1]."'><i class='fa ".$icon[$i-1]."'></i></a>
								</div>
								<i class='fa fa-angle-right my-arrow my-arrow-$i-1' style='left:175%;'></i>
								<i class='fa fa-angle-right my-arrow my-arrow-$i-2' style='left:200%;'></i>
								<div class='back-con back-con-$i'>
									<h2 style='color:".$shadow[$i-1]."'>".$title[$i-1]."</h2>
									<p class='h2-line' style='border-bottom:3px solid ".$shadow[$i-1]."'></p>
									<p>Đăng nhập để truy cập vào các nội dung của website</p>
								</div>
							</li>";
						}
					?>
                    </ul>
                    
                </div>
                
            </div>
        </div>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>