<?php
	ob_start();
	////session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$color=array("#2C84BD","#69b42e","cyan","red","yellow");
	$shadow=array("#04344C","#246E2C","blue","brown","orange");
	$hsID=$_SESSION["ID_HS"];
    $code=$_SESSION["code"];
	if(!isset($_SESSION["mon"])) {
		$_SESSION["mon"]=0;
	}
	if(!isset($_SESSION["lop"])) {
		$_SESSION["lop"]=0;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>CHỌN MÔN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/hover.css" media="all" />
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/animate.css" media="all" />
       	<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/>
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#back-one {width:100%;height:100%;}#back-one > ul {width:500px;margin:auto;height:100%;}#back-one > ul > nav {}#back-one > ul > nav li {margin:auto;width:130px;display:list-item;height:130px;position:relative;margin-bottom:70px;}#back-one > ul #back-one > ul > nav li:last-child {margin-right:0;}#back-one > ul > nav li i.my-arrow {color:gray;opacity:0.7;font-size:2.75em;position:absolute;left:50%;z-index:9;width:20%;}#back-one > ul > nav li .back-con {position:absolute;z-index:9;width:170%;text-align:center;left:-20%;}#back-one > ul > nav li .back-con .h2-line {width:35px;margin:3px auto 5px auto;}#back-one > ul > nav li .back-con h2 {text-transform:uppercase;font-size:1.25em;line-height:28px;font-weight:600;}
			#back-one > ul > nav li .noi {width:100%;height:100%;position:absolute;z-index:9;top:0;text-align:center;left:0;box-shadow:0 5px 10px 0 rgba(<?php echo $backall; ?>,0.16),0 5px 15px 0 rgba(<?php echo $backall; ?>,0.12);-moz-box-shadow:0 5px 10px 0 rgba(<?php echo $backall; ?>,0.16),0 5px 15px 0 rgba(<?php echo $backall; ?>,0.12);-ms-box-shadow:0 5px 10px 0 rgba(<?php echo $backall; ?>,0.16),0 5px 15px 0 rgba(<?php echo $backall; ?>,0.12);-o-moz-box-shadow:0 5px 10px 0 rgba(<?php echo $backall; ?>,0.16),0 5px 15px 0 rgba(<?php echo $backall; ?>,0.12);-webkit-moz-box-shadow:0 5px 10px 0 rgba(<?php echo $backall; ?>,0.16),0 5px 15px 0 rgba(<?php echo $backall; ?>,0.12);border-radius:10px;}
			#back-one > ul > nav li .noi button {display:block;color:#FFF;background:none;border:none;outline:none;font-size:32px;line-height:38px;width:90%;margin:auto;font-family: 'FontLight';cursor: pointer;}#back-one > ul > nav li .noi:hover {background:rgba(<?php echo $backall; ?>,0.35);transition-duration:0.5s;-webkit-transition-duration:0.5s;}#back-one > ul > nav li .noi:hover a {transition-duration:0.5s;-webkit-transition-duration:0.5s;}
            #SMS {  border-top-left-radius:10px;  border-top-right-radius:10px;  position:fixed;  z-index:999;  bottom:0;  left:33px;  background:#365899;  width:130px;  height:25px;  padding:5px 10px;  }  #SMS a {  display:block;  font-size:22px;  margin-left:5px;  }  #SMS a i {  line-height:26px;  color:#FFF;  }  #SMS a span {  font-weight:600;  color:#FFF;  font-size:14px;  margin-left:20px;  line-height:26px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
				
				$(".popup .popup-close, button.btn_ok").click(function() {
					$(".popup").fadeOut("fast");
					$("#BODY").css("opacity","1");
				});

//                $("#inbox-done").click(function() {
//                    $.ajax({
//                        async: false,
//                        data: "inbox=done",
//                        type: "post",
//                        url: "https://localhost/www/TDUONG/xuly-mon/",
//                        success: function(result) {
//                            if(result == "ok") {
////                                console.log("Ok");
//                            } else {
////                                console.log("ERROR");
//                                alert("Có lỗi đã xảy ra! Bạn phải gửi mã số học sinh của bạn cho Fanpage tại đây và nhận được thông báo thành công!");
//                            }
//                        }
//                    });
//                });

                $("li.mon-none").click(function() {
                    $(".popup").fadeOut("fast");
                    $("#baoloi").fadeIn("fast");
                    $("#BODY").css("opacity","0.1");
                    return false;
                });

                $("li.mon-nghi").click(function() {
                    ngay = $(this).attr("data-date");
                    $("#nghihoc .title").html("Bạn đã nghỉ học hẳn từ ngày "+ngay+", để đăng ký học lại vui lòng liên hệ thầy Dương 09765.82.764");
                    $(".popup").fadeOut("fast");
                    $("#nghihoc").fadeIn("fast");
                    $("#BODY").css("opacity","0.1");
                    return false;
                });
			});
		</script>
       
	</head>

    <body>

        <?php
            $lmID=NULL;
            $error="";
            if(isset($_POST["mon-id"])) {
                $lmID = base64_decode($_POST["mon-id"]);
                if(valid_id($lmID)) {
                    $result=check_access_mon2($hsID,$lmID);
                    if($result!=false && (!check_hs_nghi($hsID,$lmID) || $_SESSION["who"]==2)) {
                        $_SESSION["lmID"]=$lmID;
                        $data=mysqli_fetch_assoc($result);
                        $_SESSION["de"]=$data["de"];
                        $monID=get_mon_of_lop($lmID);
                        $_SESSION["mon"]=$monID;
                        $result2=get_mon_info($monID);
                        $data2=mysqli_fetch_assoc($result2);
                        $_SESSION["thang"]=$data2["thang"];
                        $_SESSION["is_ct"]=$data2["ct"];
                        header("location:https://localhost/www/TDUONG/tong-quan/");
                        exit();
                    } else {
                        header("location:https://localhost/www/TDUONG/mon/");
                        exit();
                    }
                } else {
                    header("location:https://localhost/www/TDUONG/mon/");
                    exit();
                }
            }
//            if($login_times <= 0 && $_SESSION["test"]==0) {
//                echo"<div class='popup animated bounceIn' id='baoloi' style='display: block;top: 15%;'>
//                    <div>
//                        <p class='title'>Bạn hãy inbox <a href='http://m.me/Bgo.edu.vn' target='_blank'>Fanpage</a> mã số của bạn tại đây trong lần ĐẦU TIỀN! VD: 99-0156<br />Sau khi nhận được tin nhắn thành công, bạn chọn OK!</p>
//                        <div class='fb-page' data-href='https://www.facebook.com/Bgo.edu.vn/' data-tabs='messages' data-height='250' data-small-header='true' data-adapt-container-width='true' data-hide-cover='false' data-show-facepile='true'></div>
//                        <div>
//                            <button class='submit2 btn_ok' id='inbox-done'>Tiếp tục</button>
//                        </div>
//                    </div>
//                </div>";

//                <div class='fb-send-to-messenger'
//                            messenger_app_id='967757889982912'
//                            page_id='624951394326436'
//                            data-ref='DATA_NEWHS_".encode_data($hsID, md5("1241996"))."'
//                            color='blue'
//                            size='xlarge'>
//                        </div>
//                        <div style='margin-top:10px;'>
//                            <button class='submit2' id='inbox-done'>OK</button>
//                        </div>
//            }
        ?>


    
    	<div class="popup animated bounceIn" id="baoloi">
        	<div>
            	<p class="title">Bạn không tham gia môn học này, nếu muốn đăng kí học, bạn vui lòng liên hệ thầy Dương theo số 09765.82.764 để đăng kí</p>
                <div>
                    <button class="submit2 btn_ok">OK</button>
                </div>
            </div>
        </div>
        
        <div class="popup animated bounceIn" id="nghihoc">
        	<div>
            	<p class="title"></p>
                <div>
                    <button class="submit2 btn_ok">OK</button>
                </div>
            </div>
        </div>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
                <?php
                    $dem=0;
                    $contentL=$contentR="";
					$result=get_all_lop_mon();
					while($data=mysqli_fetch_assoc($result)) {
					    if(check_access_mon($hsID,$data["ID_LM"])) {
					        if(check_hs_nghi($hsID,$data["ID_LM"])) {
                                $access = 2;
                                continue;
                            } else {
                                $access = 1;
                            }
                            if($_SESSION["who"]==2) {
                                $access = 1;
                            }
                        } else {
                            $access = 0;
                            continue;
                        }
                        if($dem%2==0) {
                            if($access==0) {
                                $contentL .= "<li id='li-$dem' style='opacity: 0.3 !important;' class='animated slideInLeft mon-none'>";
                            } else if($access==2) {
                                $contentL .= "<li id='li-$dem' class='animated slideInLeft mon-nghi' data-date='".format_dateup(get_date_nghi($hsID,$data["ID_LM"]))."'>";
                            } else {
                                $contentL .= "<li id='li-$dem' class='animated slideInLeft mon-ok'>";
                            }
                            $contentL .= "<form action='https://localhost/www/TDUONG/mon/' method='post'>
                                        <div class='noi noi-mon hvr-pulse-grow'>
                                            <button name='mon-id' value='".base64_encode($data["ID_LM"])."'>$data[name]</button>
                                        </div>
                                    </form>
                                </li>";
                        } else {
                            if($access==0) {
                                $contentR .= "<li id='li-$dem' style='opacity: 0.3 !important;' class='animated slideInRight mon-none'>";
                            } else if($access==2) {
                                $contentR .= "<li id='li-$dem' class='animated slideInLeft mon-nghi' data-date='".format_dateup(get_date_nghi($hsID,$data["ID_LM"]))."'>";
                            } else {
                                $contentR .= "<li id='li-$dem' class='animated slideInRight mon-ok'>";
                            }
                            $contentR .= "<form action='https://localhost/www/TDUONG/mon/' method='post'>
                                        <div class='noi noi-mon hvr-pulse-grow'>
                                            <button name='mon-id' value='".base64_encode($data["ID_LM"])."'>$data[name]</button>
                                        </div>
                                    </form>
                                </li>";
                        }
                        $dem++;
					}
				?>
                
                <div id="back-one">
                	<ul>
                    	<nav style="float:left;">
						<?php
                            echo $contentL;
                        ?>
                    	</nav>
                        <nav style="float:right;">
                        <?php
                            echo $contentR;
                        ?>
                        </nav>
                        <div class="clear"></div>
                    </ul>
                </div>
                <div id="SMS">
                    <a href='http://m.me/Bgo.edu.vn' target="_blank" class='sms-new'><i class='fa fa-commenting'></i><span>Hỗ trợ</span></a>
                </div>
            </div>
        </div>
        <?php require_once("include/MENUshort.php"); ?>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>