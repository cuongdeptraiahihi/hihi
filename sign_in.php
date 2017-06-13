<?php
	ob_start();
	////session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("model/is_mobile.php");
	$ip=$_SERVER['REMOTE_ADDR'];
	if(is_ddos($ip,date("Y-m-d"))) {
		header("location:https://localhost/www/TDUONG/ddos/");
		exit();
	}
	if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"]) && isset($_GET["sdt"]) && isset($_GET["lmID"]) && is_numeric($_GET["lmID"])) {
	    $ad_cmt=get_cmt_hs($_GET["hsID"]);
        $ad_sdt=$_GET["sdt"];
        $ad_lm=$_GET["lmID"];
        $_SESSION = array();
    } else {
        $ad_cmt="";
        $ad_sdt="";
        $ad_lm=0;
    }
    if(isset($_SESSION["ID_HS"]) || isset($_SESSION["fullname"])) {
        header("location:https://localhost/www/TDUONG/mon/");
        exit();
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>LOGIN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
       	<link rel="stylesheet" href="https://localhost/www/TDUONG/css/new.css" type="text/css" />
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        
        <style>
			* {padding:0;margin:0;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;height:100%;background:#D1DBBD;font-family: "FontLight";background:url(https://localhost/www/TDUONG/images/earth_moon2.jpg);background-repeat:no-repeat;background-size:cover;letter-spacing:0.5px;}#BODY {width:100%;height:100%;}#MAIN {width:340px;margin:60px 0 0 70%;border-radius:20px;padding:15px;}#MAIN .main-i {background:rgba(255,255,255,0.35);border-radius:10px;padding:15px;}#MAIN table {width:100%;border-collapse:collapse;}#MAIN table tr.padUnder > td {padding:5px 0 5px 0;}#MAIN table tr td {}#MAIN table tr td span {color:#000;font-size:22px;font-weight:600;display:block;text-align:center;}#MAIN table tr td span i {font-size:16px;}.input {padding:10px 5% 10px 5%;letter-spacing:0.5px;border:none;width:90%;font-size:18px;font-family:"FontLight";background:#FFF;color:#000;font-weight:600;}.button {font-family:"FontLight";letter-spacing:0.5px;background:#000;border:none;border-radius:6px;padding:10px 15px 10px 15px;color:#FFF;font-size:18px;font-weight:600;cursor:pointer;outline:none;}#logo {margin-bottom:20px;}#logo #logo-i {width:100%;height:190px;margin:auto;border-radius:20px;overflow:hidden;border:2px solid #FFFA03;background:#000;box-shadow: 0 0 0 3px #000, 6px 6px 6px #888;-webkit-box-shadow:0 0 0 3px #000, 6px 6px 6px #888;-moz-box-shadow:0 0 0 3px #000, 6px 6px 6px #888;text-align:center;}#logo #logo-i p {font-size:9.375em;line-height:190px;font-weight:100;color:#FFFA03;}#open_contact {cursor:pointer;opacity:0.8;}#open_contact:hover {opacity:1;}.popup {position:fixed;z-index:99;display:none;top:30%;background:#FFF;border-radius:10px;width:25%;text-align:center;cursor:pointer;left:35%;box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);padding:1.5% 2.5%;}.popup p {font-size:18px;color:#000;font-weight:600;line-height:22px;margin-bottom:10px;}.popup .popup-close {position:absolute;z-index:999;right:-15px;top:-15px;width:25px;height:25px;border-radius:25px;text-align:center;background:#FFF;}.popup .popup-close i {line-height:24px;color:#000;font-size:14px;}
            #SMS {  border-top-left-radius:10px;  border-top-right-radius:10px;  position:fixed;  z-index:999;  bottom:0;  left:33px;  background:#365899;  width:130px;  height:25px;  padding:5px 10px;  }  #SMS a {  display:block;  font-size:22px;  margin-left:5px;  }  #SMS a i {  line-height:26px;  color:#FFF;  }  #SMS a span {  font-weight:600;  color:#FFF;  font-size:14px;  margin-left:20px;  line-height:26px;}
            #fb-img {position: absolute;z-index: 9;top: 10%;left: 2%;border-radius: 1000px;width: auto;height: 35px;display: none;}
            #fb-login-box, #fb-show-box {width:100%;background: #4c69ba;display: none;}
            @media screen and (max-width: 300px) {
				#MAIN {width:90%;}
			}
        </style>
        
        <script src="https://localhost/www/TDUONG/js/jquery.min.js"></script>
        <script src="https://sdk.accountkit.com/en_EN/sdk.js"></script>
        <script>
			$(document).ready(function() {
//                AccountKit_OnInteractive = function(){
//                    AccountKit.init(
//                        {
//                            appId:967757889982912,
//                            state:"abcd",
//                            version:"v1.0"
//                        }
//                    );
//                };
//                function phone_btn_onclick() {
//                    // you can add countryCode and phoneNumber to set values
//                    AccountKit.login('PHONE', {}, // will use default values if this is not specified
//                        loginCallback);
//                }
//                // login callback
//                function loginCallback(response) {
//                    console.log(response);
//                    $(".popup").fadeOut("fast");
//                    $("#MAIN").css("opacity", "1");
//                    if (response.status === "PARTIALLY_AUTHENTICATED") {
//                        document.getElementById("ak-code").value = response.code;
////                        document.getElementById("csrf_nonce").value = response.state;
//                        $("#ok").removeClass("ok-confirm").css("background","#4c69ba").val("Xác thực thành công!");
////                        document.getElementById("my_form").submit();
//                    } else if (response.status === "NOT_AUTHENTICATED") {
//                        // handle authentication failure
//                        console.log("Authentication failure");
//                        $("#ok").css("background","red").val("Xác thực thất bại!");
//                    } else if (response.status === "BAD_PARAMS") {
//                        // handle bad parameters
//                        console.log("Bad parameters");
//                        $("#ok").css("background","red").val("Có lỗi xảy ra!");
//                    }
//                }
                function valid_maso(cmt) {
                    temp = cmt.split("-");
                    if(temp.length==2) {
                        if(!$.isNumeric(temp[0]) || !$.isNumeric(temp[1])) {
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        return false;
                    }
                }
				
				$("#ok").click(function() {
					cmt = $("#cmt").val();
					pass = $("#pass").val();
					if(cmt != "" && valid_maso(cmt) && pass != "") {
//					    if($(this).hasClass("ok-confirm")) {
//                            phone_btn_onclick();
//                            return false;
//                        } else {
                            return true;
//                        }
					} else {
						$("#MAIN").css("opacity","0.3");
						$("#popup-error").fadeIn(250);
						return false;
					}
				});
				$(".popup").click(function() {
					$(this).fadeOut(250);
					$("#MAIN").css("opacity", "1");
				});
				$("#open_contact").click(function() {
					$("#MAIN").css("opacity", "0.3");
					$("#popup-lienhe").fadeIn(250);
				});
			});
		</script>
       
	</head>

    <body>
        <script>
//            $(document).ready(function() {
//                $("#fb-login-box").click(function () {
//                    if($(this).hasClass("fb-login-new")) {
//                        FB.login(function(response){
//                            if (response.status === 'connected') {
//                                $("#fb-login-box").remove();
//                                window.location.href="https://localhost/www/TDUONG/dang-nhap-facebook/<?php //echo encode_data(0, md5("1241996")); ?>///";
//                            } else {
//                                $("#popup-tb p").html("Đăng nhập thất bại!");
//                                $("#popup-tb").fadeIn("fast");
//                            }
//                        }, {scope: 'public_profile,email'});
//                    } else if($(this).hasClass("fb-login-old")) {
//                        $("#fb-login-box, #fb-img").remove();
////                        $.ajax({
////                            async: true,
////                            data: "fb-app-id=" + $(this).attr("data-id"),
////                            type: "post",
////                            url: "https://localhost/www/TDUONG/xuly-mon/",
////                            success: function(result) {
////                                window.location.href="https://localhost/www/TDUONG/dang-nhap-facebook/" + result + "/";
////                            }
////                        });
//                    }
//                });
//            });
//            window.fbAsyncInit = function() {
//                FB.init({
//                    appId      : '967757889982912',
//                    cookie     : true,
//                    xfbml      : true,
//                    version    : 'v2.8'
//                });
//                FB.AppEvents.logPageView();
//
//                checkLoginState();
//            };
//
//            function statusChangeCallback(response) {
//                console.log('statusChangeCallback');
//                console.log(response);
//                if (response.status === 'connected') {
//                    $("#fb-login-box").addClass("fb-login-old").attr("data-id", response.authResponse.userID).fadeIn("fast");
//                    <?php //if(isset($_GET["hsID_face"])) { ?>
//                        FB.api('/me', function(response) {
//                            console.log(response);
//                            $("#fb-show-box").val(response.name).fadeIn("fast");
////                            $.ajax({
////                                async: true,
////                                data: "encode=" + response.id,
////                                type: "post",
////                                url: "https://localhost/www/TDUONG/xuly-mon/",
////                                success: function(result) {
////                                    $("#facebook-id").val(result);
////                                    $("#facebook-name").val(response.name);
////                                }
////                            });
//                        });
//                    <?php //} else { ?>
//                    $("#facebook-id").remove();
//                    <?php //} ?>
//                    FB.api('/' + response.authResponse.userID + '/picture?type=large', function(response) {
//                        $("#fb-img").attr("src", response.data.url).fadeIn("fast");
//                    });
//                } else {
//                    $("#fb-login-box").addClass("fb-login-new").fadeIn("fast");
//                    $("#facebook-id").remove();
//                }
//            }
//
//            function checkLoginState() {
//                FB.getLoginStatus(function(response) {
//                    statusChangeCallback(response);
//                });
//            }
//
//            function testAPI() {
//                console.log('Welcome!  Fetching your information.... ');
//                FB.api('/me', function(response) {
//                    console.log('Successful login for: ' + response.name + ', id: ' + response.id);
//                });
//            }
//
//            (function(d, s, id){
//                var js, fjs = d.getElementsByTagName(s)[0];
//                if (d.getElementById(id)) {return;}
//                js = d.createElement(s); js.id = id;
//                js.src = "//connect.facebook.net/en_US/sdk.js";
//                fjs.parentNode.insertBefore(js, fjs);
//            }(document, 'script', 'facebook-jssdk'));
        </script>
<!--        <div id="fb-root"></div>-->
    
    	<?php

            if($ad_cmt!="" && $ad_sdt!="" && valid_maso($ad_cmt) && $ad_lm!=0) {
                $result1=login_phuhuynh($ad_cmt, $ad_sdt);
                if(mysqli_num_rows($result1) == 1) {
                    $_SESSION = array();
                    session_destroy();
                    session_start();
                    session_regenerate_id();
                    $hsID=get_login($result1,2);

                    $lmID=$ad_lm;
                    $result=check_access_mon2(get_hs_id($ad_cmt),$lmID);
                    $_SESSION["lmID"]=$lmID;
                    $data=mysqli_fetch_assoc($result);
                    $_SESSION["de"]=$data["de"];
                    $monID=get_mon_of_lop($lmID);
                    $_SESSION["mon"]=$monID;
                    $result2=get_mon_info($monID);
                    $data2=mysqli_fetch_assoc($result2);
                    $_SESSION["thang"]=$data2["thang"];
                    $_SESSION["is_ct"]=$data2["ct"];

                    add_log($hsID,"($ad_cmt) - ($ad_sdt) đăng nhập thành công (admin), IP: $ip","login-error");
                    header("location:https://localhost/www/TDUONG/tong-quan/");
                    exit();
                } else {
                    add_log(0,"($ad_cmt) - ($ad_sdt) đăng nhập không thành công (admin), IP: $ip","login-error");
                    $error = "<div class='popup' id='popup-error' style='display:block;'>
                        <ul style='text-align:center;'>
                            <p style='font-weight:600;'>!!!</p>
                            <p>Không tồn tại thông tin bạn cung cấp</p>
                        </ul>
                    </div>";
                }
            }
			
			$error="";
			$cmt=$password=$sdt=$fbid=$fbname=$akcode=NULL;
			if(isset($_POST["ok"])) {

//			    if(isset($_POST["facebook-name"]) && !empty($_POST["facebook-name"])) {
//			        $fbname=addslashes(trim($_POST["facebook-name"]));
//                }

				if(isset($_POST["cmt"])) {
					$cmt=addslashes(trim($_POST["cmt"]));
				}
				
				if(isset($_POST["pass"])) {
					$sdt=addslashes($_POST["pass"]);
					$password=md5($sdt);
				}

				if(isset($_POST["facebook-id"])) {
//				    $fbid=addslashes(trim(decode_data($_POST["facebook-id"],md5("1241996"))));
                }

//                if(isset($_POST["ak-code"]) && !empty($_POST["ak-code"])) {
//                    $akcode=trim($_POST["ak-code"]);
//                }
				
				if($cmt && $password && $sdt && valid_maso($cmt)) {
					$result = login($cmt, $password);
					if(mysqli_num_rows($result) == 1) {
						//$data=mysqli_fetch_assoc($result);
                        $_SESSION = array();
                        session_destroy();
                        session_start();
                        session_regenerate_id();
						$hsID = get_login($result,1);
                        add_log($hsID,"($cmt) - ($sdt) đăng nhập thành công (học sinh), IP: $ip","login-error");
                        if(get_login_times($hsID) < 4) {
                            header("location:https://localhost/www/TDUONG/setup/");
                            exit();
                        } else {
                            header("location:https://localhost/www/TDUONG/mon/");
                            exit();
                        }
					} else {
						$result1=login_phuhuynh($cmt, $sdt);
						if(mysqli_num_rows($result1) == 1) {
                            $_SESSION = array();
                            session_destroy();
                            session_start();
                            session_regenerate_id();
							$hsID = get_login($result1,2);
                            add_log($hsID,"($cmt) - ($sdt) đăng nhập thành công (phụ huynh), IP: $ip","login-error");
							header("location:https://localhost/www/TDUONG/mon/");
							exit();
						} else {
							update_ddos($ip,"$cmt - $sdt đăng nhập không thành công!");
							$error = "<div class='popup' id='popup-error' style='display:block;'>
								<ul style='text-align:center;'>
									<p style='font-weight:600;'>!!!</p>
									<p>Không tồn tại thông tin bạn cung cấp</p>
								</ul>
							</div>";
						}
					}
				} else {
                    add_log(0,"($cmt) - ($sdt) đăng nhập không thành công (cú pháp), IP: $ip","login-error");
					$error = "<div class='popup' id='popup-error' style='display:block;'>
						<ul style='text-align:center;'>
							<p style='font-weight:600;'>!!!</p>
							<p>Vui lòng nhập đầy đủ thông tin đăng nhập</p>
						</ul>
					</div>";
				}
			}
		?>
                             
      	<div id="BODY">

            <?php
//            if(isset($_GET["hsID_face"])) {
//                $hsID_face = trim(decode_data($_GET["hsID_face"], md5("1241996")));
////                if(stripos($hsID_face,"xxx") != false) {
////                    $hsID_face = str_replace("x", "", $hsID_face);
////                    $error = "<div class='popup' id='popup-error' style='display:block;'>
////                        <ul style='text-align:center;'>
////                            <p style='font-weight:600;'>!!!</p>
////                            <p>Tài khoản Bgo này hiện đang kết nối đến <a href='https://www.facebook.com/$hsID_face' target='_blank'>tài khoản Facebook khác</a>. Liên hệ Admin để biết thêm thông tin.</p>
////                        </ul>
////                    </div>";
////                } else
//                echo $hsID_face;
//                if ($hsID_face == "xx") {
//                    $error = "<div class='popup' id='popup-error' style='display:block;'>
//                        <ul style='text-align:center;'>
//                            <p style='font-weight:600;'>!!!</p>
//                            <p>Số điện thoại của bạn nhập không khớp với số điện thoại học sinh bạn muốn kết nối!</p>
//                        </ul>
//                    </div>";
//                } else if($hsID_face == "xxx") {
//                    $error = "<div class='popup' id='popup-error' style='display:block;'>
//                        <ul style='text-align:center;'>
//                            <p style='font-weight:600;'>!!!</p>
//                            <p>Có lỗi xảy ra! Hãy liên hệ Admin</p>
//                        </ul>
//                    </div>";
//                } else {
//                    $_SESSION = array();
//                    session_destroy();
//                    session_start();
//                    session_regenerate_id();
//                    $result = login_face($hsID_face);
//                    if (mysqli_num_rows($result) != 0) {
//                        get_login($result, 1);
//                        add_log($hsID_face, "Đăng nhập thành công bằng Facebook (học sinh), IP: $ip", "login-error");
//
//                        header("location:https://localhost/www/TDUONG/mon/");
//                        exit();
//                    } else {
//                        $error = "<div class='popup' id='popup-error' style='display:block;'>
//                            <ul style='text-align:center;'>
//                                <p style='font-weight:600;'>!!!</p>
//                                <p>Hãy đăng nhập tài khoản Bgo của bạn để kết nối với tài khoản Facebook trong lần ĐẦU TIÊN!</p>
//                            </ul>
//                        </div>";
//                    }
//                }
//            }
            ?>

            <div class="popup animated bounceIn" id="popup-error">
                <ul style="text-align:center;">
                    <p style="font-weight:600;">!!!</p>
                    <p>Vui lòng nhập đầy đủ thông tin đăng nhập và chính xác!</p>
                </ul>
            </div>
        
        	<div class="popup animated bounceIn" id="popup-tb">
            	<ul style="text-align:center;">
                    <p></p>
               	</ul>
            </div>
            
            <?php echo $error; ?>
        
        	<div id="MAIN">
            	<div class="main-i">
                    <form action="<?php echo "https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post" id="my_form">
                        <table>
                            <tr>
                                <td colspan="3"><span style="text-align:left;">Đăng nhập</span></td>
                            </tr>
                            <?php if(isset($_GET["hsID_face"])) { ?>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="position: relative;"><img id="fb-img" src='' /><input type="button" disabled="disabled" class="button" id="fb-show-box" /></td>
                            </tr>
                            <?php } ?>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                            	<td style="width:15%;background:#FFF;border-bottom-left-radius: 6px;border-top-left-radius: 6px;"><span><i class="fa fa-user"></i></span></td>
                                <td style="width:85%;" colspan="2"><input type="text" name="cmt" id="cmt" autocomplete="off" class="input" placeholder="Mã số học sinh" style="border-bottom-right-radius: 6px;border-top-right-radius: 6px;" /><input type="hidden" name="facebook-id" id="facebook-id" style="display: none;" /><input type="hidden" name="facebook-name" id="facebook-name" style="display: none;" /><input type="hidden" name="ak-code" id="ak-code" style="display: none;" /></td>
                            </tr>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                            	<td style="background:#FFF;border-bottom-left-radius: 6px;border-top-left-radius: 6px;"><span><i class="fa fa-unlock-alt"></i></span></td>
                                <td colspan="2"><input type="password" name="pass" id="pass" autocomplete="off" class="input" placeholder="Mật khẩu" style="border-bottom-right-radius: 6px;border-top-right-radius: 6px;" /></td>
                            </tr>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <?php if(!isset($_GET["hsID_face"])) { ?>
                            <tr>
                                <td colspan="3"><input type="submit" name="ok" id="ok" class="button" value="Đăng nhập" style="width:100%;" /></td>
                            </tr>
<!--                            <tr class="padUnder">-->
<!--                                <td colspan="3"></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td colspan="3" style="position: relative;"><img id="fb-img" src='' /><input type="button" class="button" id="fb-login-box" value="Đăng nhập với Facebook" /></td>-->
<!--                            </tr>-->
                            <?php } else { ?>
                            <tr>
                                <td colspan="3"><input type="submit" name="ok" id="ok" class="button ok-confirm" value="Đăng nhập" style="width:100%;" /></td>
                            </tr>
                            <?php } ?>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php if(isset($_GET["hsID_face"])) { ?>
                                    <span style="float:left;font-size:12px;"><a href="https://localhost/www/TDUONG/dang-nhap/" style="color:#FFF;">< Quay lại</a></span>
                                    <?php } ?>
                                    <span style="float:right;font-size:12px;">@ <?php echo date("Y"); ?> Bgo.edu.vn</span>
                               	</td>
                            </tr>
                        </table>
                    </form>
               	</div>
            </div>

            <div id="SMS">
                <a href='http://m.me/Bgo.edu.vn' target="_blank" class='sms-new'><i class='fa fa-commenting'></i><span>Hỗ trợ</span></a>
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>