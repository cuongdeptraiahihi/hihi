<?php
	ob_start();
	//session_start();

	require_once("../model/open_db.php");

	require_once("../model/model.php");
session_start();
	require_once("include/is_mobile.php");
	$color=array("#2C84BD","#69b42e","blue","red","yellow");
	$shadow=array("#04344C","#246E2C","blue","brown","orange");
	header("location:http://localhost/www/TDUONG/mobile/dang-nhap/");
	exit();
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
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        
        <style>
			*{margin:0;padding:0}body{background:url(https://localhost/www/TDUONG/images/earth_moon2.jpg);font-family:Tahoma,Geneva,sans-serif;letter-spacing:.5px;width:100%;height:100%}a{text-decoration:none}.my-form{position:absolute;z-index:9;width:87%;border:2px solid #12C8F0;padding:1.5%;right:5%;}.my-form .my-in{padding:0 10px;height:80px;background:rgba(18,200,240,.3);text-align:center}.my-form .my-in:hover a{color:#FFF}.my-form .my-in a{color:#12C8F0;font-size:14px;text-transform:uppercase;font-weight:600;line-height:80px;display:block}.my-conner{background-color:transparent;width:20px;height:20px;position:absolute;z-index:99}.conner1{top:-4px;left:-4px;border-left:6px solid #12C8F0;border-top:6px solid #12C8F0}#world{left:0;top:0;position:absolute}.conner2{bottom:-4px;right:-4px;border-right:6px solid #12C8F0;border-bottom:6px solid #12C8F0}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
			});
		</script>
       
	</head>

    <body>
                             
      	<div id="SIDEBACK"><div id="BODY">
            
                    <?php
						if (!isset($_SESSION["ID_HS"]) || !isset($_SESSION["fullname"])) {
							$icon=array("fa-sign-in","fa-smile-o","fa-users");
							$title=array("Đăng nhập","Học sinh mới","Ngoại khóa");
							$link=array("dang-nhap","#","#");
						} else {
							$icon=array("fa-sign-in","fa-smile-o","fa-users");
							$title=array("Trang cá nhân","Học sinh mới","Ngoại khóa");
							$link=array("mon","#","#");
						}
						$top=array("10","35","60");
						$content=array("Truy cập vào trang cá nhân của bạn","Thông tin hữu ích dành cho các bạn mới","Hoạt động ngoại khóa thường niên");
						for($i=1;$i<=3;$i++) {
							echo"<div class='my-form' style='top:".($top[$i-1])."%;'>
								<div class='my-conner conner1'></div>
								<div class='my-in hvr-shutter-out-horizontal'>
									<a href='http://localhost/www/TDUONG/mobile/".$link[$i-1]."/'>".$title[$i-1]."</a>
								</div>
								<div class='my-conner conner2'></div>
							</div>";
							/*echo"<li id='li-$i'>
								<div class='back-info'><a href='".$link[$i-1]."'>
									<h2 style='color:".$color[$i-1]."'>".$title[$i-1]."</h2>
									<p class='h2-line' style='border-bottom:0.5vh solid ".$color[$i-1].";'></p>
									<p>".$content[$i-1]."</p>
								</a></div>
							</li>";*/
						}
					?>
        </div>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>