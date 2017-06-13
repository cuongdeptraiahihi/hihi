<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	if(isset($_GET["tlID"]) && is_numeric($_GET["tlID"])) {
		$tlID=$_GET["tlID"];
	} else {
		$tlID=0;
	}
	if(!check_tailieu_mon($tlID,$lmID) && !check_tailieu_mon3($tlID,$lmID)) {
		header("location:https://localhost/www/TDUONG/tai-lieu/");
		exit();
	}
	$mon_name=get_mon_name($monID);
	$mau="#FFF";
	$result1=get_hs_short_detail($hsID,$lmID);
	$data1=mysqli_fetch_assoc($result1);
	
	$result0=get_one_tailieu($tlID);
	$data0=mysqli_fetch_assoc($result0);
	
	$title=unicode_convert($data0["title"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title><?php echo mb_strtoupper($data0["title"],"UTF-8"); ?></title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.main-cmt {padding-bottom:0 !important;}.main-cmt li {height:50px;padding:10px;border-radius:10px;}.main-cmt li > div {float:left;}.main-cmt li div.cmt-img {width:7%;overflow:hidden;border-radius:1000px;height:auto;}.main-cmt li div.cmt-img img {width:100%;height:auto;}.main-cmt li div.cmt-con {width:75.5%;margin-left:2.5%;}.main-cmt li div.cmt-send {width:15%;text-align:right;}.main-cmt li div.cmt-send span.fa {color:#FFF;font-size:22px;line-height:50px;cursor:pointer;}.main-cmt li div.cmt-send span.fa:hover {color:#000;}.main-cmt li div.cmt-send span.cmt-time {font-size:12px;color:#FFF;line-height:64px;opacity:0.7;}.btn-like-ar {background:red;padding:3px 10px 3px 10px;font-size:14px;}.main-cmt li div.cmt-con p {color:#FFF;margin-top:5px;font-size:14px;}.btn-action {cursor:pointer;}.btn-action:hover {color:#000;}.btn-gone {display:none;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:10px;margin:3px 0 3px 0;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;}#chart-li1 ul {border-radius:10px;margin-top:3px;padding:5px 0px 5px 0px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {padding:10px;}#chart-li1 ul li > div.main-sub {margin-left:20px;}#chart-li1 ul li > div.main-sub ol {padding:5px 0 5px 0;}#chart-li1 ul li > div.main-sub ol a {font-size:14px;}#chart-li1 ul li > div a {letter-spacing:0.5px;color:#FFF;font-size:14px;display:block;}.main-content ol, .main-content ol li {float:none !important;width:100% !important;color:#FFF;font-size:14px;list-style-type:disc;list-style-position:inside;margin:5px 0 5px 0;}.main-content p {color:#FFF;font-size:14px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				/*var my_width = $("#main-table > nav > iframe").width();
				$("#main-table > nav > iframe").attr("height",(my_width*9/16)+"px");
				
				$("#main-table > nav p").has("img").css("text-align","center");*/
				var my_width = $(".main-video > ul > iframe").width();
				$(".main-video > ul > iframe").attr("height",(my_width*9/16)+"px");
				
				$(".main-video > ul p").has("img").css({"text-align":"center","margin":"10px 0 10px 0"});
				$(".main-video > ul p img").css({"width":"80%","height":"auto"});
				
				$(".btn-action").click(function() {
					$.ajax({
						url: "https://localhost/www/TDUONG/xuly-tailieu/",
						async: false,
						data: "tlID0=" + "<?php echo base64_encode($tlID); ?>",
						type: "post",
						success: function(result) {
							$("#like-show").html(result);
							$(".btn-action").removeClass("btn-action");
						}
					});
				});
				
				$(".main-cmt li div.cmt-send > button").click(function() {
					me = $(this);
					text = $(this).closest("li").find("div.cmt-con .input").val();
					if(text!="") {
						/*$.ajax({
							url: "https://localhost/www/TDUONG/xuly-tailieu/",
							async: false,
							data: "tlID=" + "<?php echo base64_encode($tlID); ?>" + "&text=" + text,
							type: "post",
							success: function(result) {
								if(result=="fuck") {
									alert("Đoạn tin nhắn của bạn chứa kí tự không được cho phép!");
								} else if(result=="none") {
									alert("Có lỗi đã xảy ra!");
								} else {
									$(".main-cmt > ul").append(result);
								}
								me.closest("li").find("div.cmt-con .input").val("");
							}
						});*/
						return true;
					} else {
						me.closest("li").find("div.cmt-con .input").attr("placeholder","Vui lòng nhập ít nhất 1 kí tự!!!");
						return false;
					}
				});
				
				$("#chart-li1 ul li > div").hover(function() {
					$("#chart-li1 ul li > div").addClass("inactive");
					$(this).removeClass("inactive");
					$("#chart-li1 ul li > div.inactive").stop(true, false).delay(250).animate({opacity:"0.15"},250);
				},function() {
					$("#chart-li1 ul li > div.inactive").stop(true, false).animate({opacity:"1"},250);
				});
			});
		</script>
       
	</head>

    <body>
    
    	<?php
			$cmt=$error=NULL;
			if(isset($_POST["cmt-send"])) {
				if(isset($_POST["cmt-con"])) {
					$cmt=addslashes(htmlspecialchars($_POST["cmt-con"]));
				}
				if($cmt) {
					if(valid_text($cmt)) {
						add_tailieu_cmt($hsID, $tlID, $cmt);
						header("location:https://localhost/www/TDUONG/tai-lieu/$title-$tlID.html");
						exit();
					} else {
						$error="<div class='popup' style='display:block'>
							<p>Dữ liệu bạn nhập không thỏa mãn!</p>
						</div>";
					}
				} else {
					$error="<div class='popup' style='display:block'>
						<p>Vui lòng nhập bình luận!</p>
					</div>";
				}
			}
		?>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top" style="min-height:100px;">
                	<div id="main-person">
                		<h1 style="line-height:50px;"><span><?php echo $data0["title"]; ?></span></h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data1["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data1["cmt"];?> (<?php echo $data1["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<ul>
                    	<li style="width:30%;float:left;" id="chart-li1">
                        	<div class="main-2 back" style="overflow:hidden;"><h3><?php echo get_chuyende($data0["ID_DM"]); ?></h3></div>
                            <ul>
                            <?php
								$result3=get_tailieu_dm($data0["ID_DM"],$data0["ID_DM2"]);
								while($data3=mysqli_fetch_assoc($result3)) {
									echo"<li>
										<div><a href='https://localhost/www/TDUONG/tai-lieu/$data3[titlestring]-$data3[ID_TL].html'>$data3[name]<br /><span style='font-size:10px;float:left;margin-top:5px;'>".format_dateup($data3["dateup"])."</span></a><div class='clear'></div></div>
									</li>";
								}
							?>
                            </ul>
                        </li>
                        <li style="margin-left:2%;width:68%;float:left;" class="main-video">
                        	<div class="main-2 back"><h3>Ngày đăng: <span><?php echo format_datetime($data0["dateup"]); ?></span></h3></div>
                            <ul class="main-content">
							<?php
                                echo"<p style='color:#FFF;font-size:14px;margin-bottom:15px;margin-top:5px;'>$data0[intro]</p>";
                                if($data0["type"]=="baiviet") {
                                    echo $data0["full_con"];
                                } else if($data0["type"]=="video") {
                                    echo"<video width='100%' controls>
                                        <source src='https://localhost/www/TDUONG/tailieu/$data0[ID_DM]/$data0[short_con]' type='video/mp4' />
                                        Trình duyệt đã cũ, hãy nâng cấp!
                                    </video>";
                                } else if($data0["type"]=="youtube") {
                                    echo"<iframe width='100%' height='' src='$data0[short_con]' frameborder='0' allowfullscreen></iframe>";
                                }
                            ?>
                            </ul>
                            <ul class="main-cmt">
                            	<p style="color:#FFF;font-size:22px;text-transform:uppercase;text-align:center;margin-bottom:15px;margin-top:5px;">Bình luận và góp ý</p>
                                <p style="color:#FFF;text-align:center;"><span class="btn-like-ar <?php if(!check_tailieu_like($hsID,$tlID)){echo"btn-action";}else{echo"btn-gone";} ?>">Like<i class="fa fa-caret-right" style="margin-left:5px;"></i></span><span class="fa fa-thumbs-o-up <?php if(!check_tailieu_like($hsID,$tlID)){echo"btn-action";} ?>" style="margin-right:5px;margin-left:15px;"></span><span id="like-show"><?php echo count_tailieu_like($tlID); ?></span><span class="fa fa-comment-o" style="margin-right:5px;margin-left:25px;"></span><span id="cmt-show"><?php echo count_tailieu_cmt($tlID); ?></span></p>
                            	<div class="clear" style="height:15px;"></div>
                                <form action="https://localhost/www/TDUONG/tai-lieu/<?php echo"$title-$tlID"; ?>.html" method="post">
                                    <li style="margin-bottom:10px;">
                                        <div class="cmt-img"><img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo get_avata_hs($hsID); ?>"/></div>
                                        <div class="cmt-con" style="width:80.5%;"><input class="input" style="width:95%;padding:10px 2.5% 10px 2.5%;resize:vertical;" placeholder="Thêm bình luận..." name="cmt-con"/></div>
                                        <div class="cmt-send" style="width:10%;text-align:center;"><button type="submit" style="background:none;outline:none;border:none;" name="cmt-send"><span class="fa fa-send" title="Gửi bình luận"></span></button></div>
                                    </li>
                                </form>
                                <div class="clear"></div>
                                <?php
									if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
										$position=$_GET["begin"];
									} else {
										$position=0;
									}
									$stt=1;$display=20;
									$result=get_tailieu_cmt($tlID,$position,$display);
									while($data=mysqli_fetch_assoc($result)) {
										echo"<li>
											<div class='cmt-img'><img src='https://localhost/www/TDUONG/hocsinh/avata/".get_avata_hs($data["ID_HS"])."'/></div>
											<div class='cmt-con'><p>$data[content]</p></div>
											<div class='cmt-send'><span class='cmt-time'>".get_past_time($data["datetime"])."</span></div>
										</li>
										<div class='clear'></div>";
									}
								?>
                            </ul>
                            <ul style="background:none;padding:0;">
                            	<?php
									$result2=get_all_tailieu_cmt($tlID);
									$sum=mysqli_num_rows($result2);
									$sum_page=ceil($sum/$display);
									if(2>1) {
										$current=($position/$display)+1;
								?>
								<div class="page-number back" style="margin-top:0;">
									<ul>
									<?php
										if($current!=1) {
											$prev=$position-$display;
											echo"<li><a href='https://localhost/www/TDUONG/tai-lieu/$title-$tlID.html/page-$prev/'><</a></li>";
										}
										for($page=1;$page<=$sum_page;$page++) {
											$begin=($page-1)*$display;
											if($page==$current) {
												echo"<li style='background:rgba(0,0,0,0.35);'><a href='https://localhost/www/TDUONG/tai-lieu/$title-$tlID.html/page-$begin/' style='font-weight:600;'>$page</a></li>";
											} else {
												echo"<li><a href='https://localhost/www/TDUONG/tai-lieu/$title-$tlID.html/page-$begin/'>$page</a></li>";
											}
										}
										if($current!=$sum_page) {
											$next=$position+$display;
											echo"<li><a href='https://localhost/www/TDUONG/tai-lieu/$title-$tlID.html/page-$next/'>></a></li>";
										}
									?>
									</ul>
								</div>
								<?php
									}
								?>
                                <div class="clear"></div>
                            </ul>
                        </li>
                    </ul>
                </div>
                
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