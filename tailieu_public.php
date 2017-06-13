<?php
	ob_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	$mau="#FFF";
	if(!isset($_SESSION["mon"])) {
		$_SESSION["mon"]=0;
	}
	if(!isset($_SESSION["lop"])) {
		$_SESSION["lop"]=0;
	}
	if(!isset($_SESSION["ID_HS"])) {
		$_SESSION["ID_HS"]=0;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÀI LIỆU THAM KHẢO MIỄN PHÍ</title>
        
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
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.main-div .main-1 > ul {float:left;}.main-div .main-1 > ul.main-tabs {width:27.5%;}.main-div .main-1 > ul.main-in {width:67.5%;}.main-tabs > li.active {opacity:1 !important;height:60px !important;}.main-tabs > li.active a {line-height:60px !important;}.main-in > li.active {display:block !important;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				$(".main-in > li").css("min-height",$(".main-tabs").height());
				$(".main-tabs > li:first-child").css({"opacity":"1"}).addClass("active"), $(".main-in > li:first-child").addClass("active").show(), $(".main-tabs > li > a").click(function() {
					dmID = $(this).attr("data-dmID"), tab = $(".main-in > li#dm-" + dmID), "active" != tab.attr("class") && "active" != $(this).closest("li").attr("class") && ($(".main-tabs > li.active").css({"opacity":"0.15"}).removeClass("active"), $(".main-in > li.active").hide().removeClass("active"), tab.slideDown(500).addClass("active"), $(this).closest("li").css({
						"opacity":"1"
					}).addClass("active"))
				})
			});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Tài liệu thao khảo miễn phí</span></h1>
                        <div class="clear"></div>
                   	</div>
                    <a href="https://localhost/www/TDUONG/"><div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/images/loadingScreen.jpg" />
                   	</div></a>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back">
                    	<ul class="main-tabs">
                        <?php
							$result=get_danhmuc(0,0);
							while($data=mysqli_fetch_assoc($result)) {
								echo"<li id='tab-$data[ID_DM]'><a href='javascript:void(0)' data-dmID='$data[ID_DM]'>$data[title]</a></li>";
							}
						?>
                        </ul>
                        <ul class="main-in">
                        <?php
							$type=array("baiviet"=>"fa-newspaper-o","video"=>"fa-camera-retro","link"=>"fa-link","youtube"=>"fa-youtube");
							$result=get_danhmuc(0,0);
							while($data=mysqli_fetch_assoc($result)) {
						?>
                        	<li id="dm-<?php echo $data["ID_DM"]; ?>">
                                <?php
									$dem=0;
									$result2=get_tailieu_dm(0,$data["ID_DM"]);
									while($data2=mysqli_fetch_assoc($result2)) {
										if($data2["type"]=="link") {
											echo"<ol><a href='$data2[short_con]' target='_blank'>";
										} else {
											echo"<ol><a href='https://localhost/www/TDUONG/tai-lieu-cong-cong/$data2[titlestring]-$data2[ID_TL].html'>";
										}
										if($data2["pic"]!="none") {
											echo"<nav class='tl-icon'><img src='https://localhost/www/TDUONG/tailieu/$data2[pic]' style='width:150%;height:150%;margin-top:-10px;' /></nav>";
										} else {
											echo"<nav class='tl-icon'><span class='fa ".$type[$data2["type"]]."'></span></nav>";
										}
											echo"<nav class='tl-con'>
												<p class='tl-title'>$data2[name]</p>
												<p class='tl-intro'>$data2[intro]</p>
											</nav>
											<nav class='tl-date'><p>".format_datetime($data2["dateup"])."</p></nav>
											<div class='clear'></div>
										</a></ol>";
										$dem++;
									}
									if($dem==0) {
										echo"<ol><a href='javascript:void(0)'>
											<nav class='tl-date' style='width:100%;margin:0;text-align:center'><p>Nội dung đang được xây dựng</p></nav>
											<div class='clear'></div>
										</a></ol>";
									}
								?>
                            </li>
                            <div class="clear"></div>
                       	<?php } ?>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>	               
            </div>
        
        </div>
        
        <div id="myback"></div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>