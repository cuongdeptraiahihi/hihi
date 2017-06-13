<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$ip=$_SERVER['REMOTE_ADDR'];
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	
	$error=0;
	if(isset($_GET["error"]) && is_numeric($_GET["error"])) {
		$error=$_GET["error"];
	}
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);
	$cmt=$data0["cmt"];
	
	if($data0["facebook"]=="X" || $data0["facebook"]=="") {
		$face="#";
	} else {
		$face=$data0["facebook"];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>HỒ SƠ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/imgareaselect-animated.css" />
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
				$("#avata-ok").click(function() {
					avata = $("#avata").val();
					if(avata!="") {
						return true;
					} else {
						alert("Bạn vui lòng chọn 1 ảnh?");
						return false;
					}
				});
				
				$("#change-gender").change(function() {
					gender = $(this).find("option:selected").val();
					if($.isNumeric(gender) && (gender==0 || gender==1)) {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "gender=" + gender,
							type: "post",
							url: "https://localhost/www/TDUONG/xuly-tongquan/",
							success: function(result) {
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					}
				});	
				
				$("#change-sdt").typeWatch({
					captureLength: 5,
					callback: function(value) {
						if($.isNumeric(value) && value>0) {
							$("#popup-loading").fadeIn("fast");
							$("#BODY").css("opacity","0.3");
							$.ajax({
								async: true,
								data: "sdt=" + value,
								type: "post",
								url: "https://localhost/www/TDUONG/xuly-tongquan/",
								success: function(result) {
									$("#popup-loading").fadeOut("fast");
									$("#BODY").css("opacity","1");
								}
							});
						}
					}
				});
				
				$("#change").click(function() {
					mk1 = $("#mk1").val();
					mk2 = $("#mk2").val();
					mk3 = $("#mk3").val();
					if(mk1!="" && mk2!="" && mk3!="") {
						if(mk2!=mk3) {
							alert("Bạn đã nhập lại sai mật khẩu mới!");
							return false;
						} else {
							return true;
						}
					} else {
						alert("Vui lòng nhập đầy đủ thông tin!");
						return false;
					}
				});	
				
				$(".popup").click(function() {
					if(!$(this).hasClass("popup-img")) {
						/*$(this).fadeOut(250);
						$("#submit-up").fadeOut();
						$("#BODY").css("opacity", "1");*/
						window.location.href="https://localhost/www/TDUONG/ho-so/";
					}
				});
				
				$(".popup-close").click(function() {
					me = $(this);
					if(me.find("i").hasClass("fa-close")) {
						me.closest(".popup").fadeOut(250);
						$("#submit-up").fadeOut();
						$("#BODY").css("opacity", "1");
					}
				});

                $("#setup").click(function () {
                    if(confirm("Bạn có chắc chắn không?")) {
                        return true;
                    } else {
                        return false;
                    }
                });
			});
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/jquery.imgareaselect.pack.js"></script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/script.js"></script>
       
	</head>

    <body>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			
			if($error!=0) {
				switch($error) {
					case 1:
						$con="Thành công!";
						break;
					case 2:
						$con="Có lỗi xảy ra!";
						break;
					case 3:
						$con="Ảnh quá to hoặc quá nhỏ!";
						break;
					case 4:
						$con="Chưa chọn ảnh!";
						break;
					case 5:
						$con="Lỗi dữ liệu";
						break;
                    default:
                        $con="Unknown Error";
				}
				echo"<div class='popup' style='display:block'>
					<p>$con</p>
				</div>";
			}

			if(isset($_POST["setup"])) {
			    update_login_times($hsID, 0);
                header("location:https://localhost/www/TDUONG/setup/");
                exit();
            }
		
			$error="";
			$mk1=$mk2=$mk3=NULL;
			if(isset($_POST["change"])) {
				if(isset($_POST["mk1"])) {
					$mk1=addslashes($_POST["mk1"]);
				}
				if(isset($_POST["mk2"])) {
					$mk2=addslashes($_POST["mk2"]);
				}
				if(isset($_POST["mk3"])) {
					$mk3=addslashes($_POST["mk3"]);
				}
				if($mk1 && $mk2 && $mk3 && $mk1!="" && $mk2!="" && $mk3!="") {
					if($mk2==$mk3) {
						if(valid_text($mk1) && valid_text($mk2) && valid_text($mk3) && valid_pass($mk2)) {
							$cmt=get_cmt_hs($hsID);
							$result=login($cmt,md5($mk1));
							if(mysqli_num_rows($result)==1) {
								change_mk_hs($cmt,md5($mk2));
								add_log($hsID,"Đổi mật khẩu thành $mk2","doi-mat-khau");
								$error="<div class='popup' style='display:block'>
									<p>Đổi mật khẩu thành công!</p>
								</div>";
							} else {
								update_ddos($ip,"$cmt đổi mật khẩu với mật khẩu cũ sai!");
								$error="<div class='popup' style='display:block'>
									<p>Mật khẩu cũ không chính xác!</p>
								</div>";
							}
						} else {
							$error="<div class='popup' style='display:block'>
								<p>Mật khẩu chứa ít nhất 6 kí tự và bắt đầu bằng chữ cái</p>
							</div>";
						}
					} else {
						$error="<div class='popup' style='display:block'>
							<p>Bạn đã nhập lại sai mật khẩu!</p>
						</div>";
					}
				} else {
					$error="<div class='popup' style='display:block'>
						<p>Vui lòng nhập đầy đủ thông tin và chính xác!</p>
					</div>";
				}
			}
			
			echo $error;
		?>
        
        <?php
			/*$error="";
			$ava=NULL;
			if(isset($_POST["avata-ok"])) {
				if($_FILES["avata"]["error"]>0) {
				} else {
					$ava=$_FILES["avata"]["name"];
				}
				
				if($ava && valid_image($ava)) {
					move_uploaded_file($_FILES["avata"]["tmp_name"],"hocsinh/avata/".$_FILES["avata"]["name"]);	
					update_avata_hs($hsID,$ava);
				} else {
					$error="<div class='popup' style='display:block'>
						<p>Vui lòng up ảnh có định dạng .png, .jpg, .gif!</p>
					</div>";
				}
			}
			
			echo $error;*/
		?>
        
        <div class="popup popup-img" style="display:none;padding:25px;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<!--<div class="popup-close" style="width:50px;height:50px;border-radius:1000px;background:#000;right:15px;top:15px;"><i class="fa fa-check" style="line-height:50px;color:#FFF;font-size:22px;"></i></div>-->
        	<img id="uploadPreview" />
        </div>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                    <div id="main-person">
                        <h1><?php echo $data0["fullname"];?></h1>
                        <?php
                        $lich_hoc=get_hs_lich_hoc2($hsID,$lmID,$monID);
                        $lich_thi=get_hs_lich_thi($hsID,$monID);
                        $lich_tc=get_hs_tang_cuong($hsID,$lmID,$monID);
                        echo"<p style='margin-bottom:5px;margin-top:5px;width:83%'><a href='https://localhost/www/TDUONG/lich-hoc/' style='font-weight:600;font-size:14px;color:#FFF;'>Lịch học cố định:</a> $lich_hoc</p>
                        	<p><a href='https://localhost/www/TDUONG/lich-hoc/' style='font-weight:600;font-size:14px;color:#FFF;'>Lịch thi:</a> $lich_thi</p>";
                        if($lich_tc!="") {
                            echo"<p><a href='https://localhost/www/TDUONG/lich-hoc/' style='font-weight:600;font-size:14px;color:#FFF;'>Lịch tăng cường:</a> $lich_tc</p>";
                        }
                        ?>
                        <div class="clear"></div>
                    </div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>
                
                <div class="main-div animated bounceInUp">
                	<ul>
                    	<li id="chart-li1" class="li-3">
                        	<div class="main-2 back"><h3>Thông tin</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                	<div><p><i class="fa fa-credit-card" style="width:25px;"></i>Mã số</p></div>
                                    <div style="width:58%;"><input type="text" disabled="disabled" value="<?php echo $data0["cmt"];?>" class="input" /></div>
                                </li>
                                <li>
                                	<div><p><i class="fa fa-venus-mars" style="width:25px;"></i>Giới tính</p></div>
                                    <div style="width:58%;">
                                    	<select class="input" style="width:95%;" id="change-gender">
                                        	<option value="1" <?php if($data0["gender"]==1){echo"selected='selected'";} ?>>Nam</option>
                                            <option value="0" <?php if($data0["gender"]==0){echo"selected='selected'";} ?>>Nữ</option>
                                        </select>
                                    	<!--<input type="text" value="<?php echo get_gender($data0["gender"]);?>" class="input" />-->
                                    </div>
                                </li>
                                <li>
                                	<div><p><i class="fa fa-child" style="width:25px;"></i>Ngày sinh</p></div>
                                    <div style="width:58%;"><input type="text" disabled="disabled" value="<?php echo format_dateup($data0["birth"]);?>" class="input" /></div>
                                </li>
                                <li>
                                	<div><p><i class="fa fa-phone" style="width:25px;"></i>SĐT</p></div>
                                    <div style="width:58%;"><input type="number" min="0" id="change-sdt" value="<?php echo $data0["sdt"] ;?>" class="input" /></div>
                                </li>
                                <li>
                                	<div><p><i class="fa fa-mobile" style="width:25px;"></i>SĐT Bố</p></div>
                                    <div style="width:58%;"><input type="text" disabled="disabled" value="<?php echo $data0["sdt_bo"] ;?>" class="input" /></div>
                                </li>
                                <li>
                                	<div><p><i class="fa fa-mobile" style="width:25px;"></i>SĐT Mẹ</p></div>
                                    <div style="width:58%;"><input type="text" disabled="disabled" value="<?php echo $data0["sdt_me"] ;?>" class="input" /></div>
                                </li>
                                <li>
                                	<div><p><i class="fa fa-university" style="width:25px;"></i>Trường</p></div>
                                    <div style="width:58%;"><input type="text" disabled="disabled" value="<?php echo get_truong_hs($data0["truong"]);?>" class="input" /></div>
                                </li>
                                <li style="padding: 10px;">
                                	<div><p><i class="fa fa-facebook-official" style="width:25px;"></i>Facebook</p></div>
                                    <div style="width:58%;"><a href="<?php echo $face; ?>" style="color:#FFF;font-size:14px;text-decoration:underline;">Link</a></div>
                                </li>
                                <li style="padding: 10px;">
                                    <div><p><i class="fa fa-level-up" style="width:25px;"></i>LEVEL</p></div>
                                    <div style="width:58%;"><a href="javascript:void(0)" style="color:#FFF;font-size:14px;"><?php echo $data0["level"]; ?></a></div>
                                </li>
                                <?php if($_SESSION["show_tien"]==1) { ?>
                                <li style="border-bottom:none">
                                	<div><p><i class="fa fa-dollar" style="width:25px;"></i>Tài khoản</p></div>
                                    <div style="width:58%;"><input type="text" disabled="disabled" value="<?php echo format_price($data0["taikhoan"]); ?>" class="input" /></div>
                                </li>
                                <?php } ?>
                            </ul>
                            
                        </li>
                        <li id="chart-li1" class="li-3">
                        	<div class="main-2 back"><h3>Đổi mật khẩu</h3></div>
                            <ul style="margin-top:3px;">
                            	<form action="https://localhost/www/TDUONG/ho-so/" method="post">
                                    <li>
                                    	<div style="width:100%;margin-top:0;text-align:center;"><p>Mật khẩu chứa ít nhất 6 kí tự và bắt đầu bằng chữ cái</p></div>
                                    </li>
                                    <?php if($_SESSION["test"]==0) { ?>
                                    <li>
                                        <div style="width:100%;"><input type="password" name="mk1" id="mk1" placeholder="Mật khẩu cũ" autocomplete="off" style="width:90%;padding:5%" class="input" /></div>
                                    </li>
                                    <li>
                                        <div style="width:100%;"><input type="password" name="mk2" id="mk2" placeholder="Mật khẩu mới" autocomplete="off" style="width:90%;padding:5%" class="input" /></div>
                                    </li>
				                    <li>
                                        <div style="width:100%;"><input type="password" name="mk3" id="mk3" placeholder="Nhập lại mật khẩu mới" autocomplete="off" style="width:90%;padding:5%" class="input" /></div>
                                    </li>
                                    <li>
                                        <div style="width:100%;margin-top:0;text-align:right;"><button class="submit" name="change" id="change">Thay đổi</button></div>
                                    </li>
                                    <?php } ?>
                               	</form>
                            </ul>
                            <div class="main-2 back" style="margin-top: 15px;"><h3>Setup tài khoản</h3></div>
                            <ul style="margin-top:3px;">
                                <form action="https://localhost/www/TDUONG/ho-so/" method="post">
                                    <li>
                                        <div style="width:100%;margin-top:0;text-align:right;"><button class="submit" name="setup" id="setup">Setup</button></div>
                                    </li>
                                </form>
                            </ul>
                        </li>
                        <li id="main-tb" class="li-3">
                            <div class="main-2 back"><h3>Đổi ảnh cá nhân</h3></div>
                            <ul style="margin-top:3px;">
                            <form action="https://localhost/www/TDUONG/avata-upload/" method="post" enctype="multipart/form-data">
                            	<li><div style="width:100%;text-align:center;"><p>Hãy chọn 1 ảnh vuông (.png, .jpg, .gif)</p></div></li>
                                <li style="padding-top:5px;padding-bottom:5px;" id="imageCon">
                                	<input type="file" class="submit" accept="image/jpeg" name="avata" id="avata" style="width: 92%;" />
                                </li>
                                <!--<li style="padding-top:5px;text-align:right;"><input type="submit" class="submit" name="avata-ok" value="Thay đổi" id="avata-ok" /></li>-->
                                <input type="submit" id="submit-up" class="submit" value="Thay đổi" />
                                <input type="hidden" id="x" name="x" />
                                <input type="hidden" id="y" name="y" />
                                <input type="hidden" id="w" name="w" />
                                <input type="hidden" id="h" name="h" />
                           	</form>
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