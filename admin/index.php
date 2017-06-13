<?php
	ob_start();
    session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
    require_once("include/mobile.php");
    $ip=$_SERVER['REMOTE_ADDR'];
	$now_nam=date("Y");
	$now_thang=date("m");
    $me=md5("123456");
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
               
        <link href="images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
            <?php if($_SESSION["mobile"]==0) { ?>
			* {padding:0;margin:0;font-family: "Roboto", Helvetica Neue, Helvetica, Arial, sans-serif;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;margin:auto;background:#D1DBBD;}#BODY {width:100%;height:100%;}#MAIN {width:320px;height:auto;margin:80px auto 0 auto;}#MAIN .main-i {background:#FFF;border:1px solid #dfe0e4;padding:30px 20px 30px 20px;}#MAIN table {width:100%;}#MAIN table tr td {padding-bottom:5px;}#MAIN table tr td span {color:#3E606F;font-size:14px;}.input {padding:10px 2.5% 10px 2.5%;border:none;width:95%;font-size:18px;background:#ffffa5;color:#3E606F;}.button {background:#EF5350;border:none;outline:none;padding:8px 10px 8px 10px;border-radius:2px;font-family:Arial, Helvetica, sans-serif;color:#FFF;font-size:14px;cursor:pointer;opacity:0.9;margin: 0 2.5px 0 2.5px;}.button:hover {opacity:1;}#logo {margin-bottom:20px;}#logo #logo-i {width:94px;height:94px;margin:auto;border-radius:94px;overflow:hidden;border:2px solid #3E606F;}#logo #logo-i img {width:100%;height:auto;}#open_contact {cursor:pointer;opacity:0.8;}#open_contact:hover {opacity:1;}.popup {position:fixed;display:none;z-index:99;top:150px;border:1px solid #dfe0e4;cursor:pointer;background:#FFF;width:30%;left:35%;}.popup:hover {background:#dfe0e4;}.popup ul {margin:5%;}.popup ul li {font-size:14px;color:#3E606F;padding-bottom:5px;}.popup ul li span {font-weight:600;}.popup ul p {margin-bottom:10px;font-size:14px;color:#3E606F;}
            <?php } else { ?>
            * {padding:0;margin:0;font-family: "Roboto", Helvetica Neue, Helvetica, Arial, sans-serif;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;margin:auto;background:#D1DBBD;}#BODY {width:100%;height:100%;}#MAIN {width:90%;height:auto;margin:40px auto 0 auto;}#MAIN .main-i {background:#FFF;border:1px solid #dfe0e4;padding:30px 20px 30px 20px;}#MAIN table {width:100%;}#MAIN table tr td {padding-bottom:5px;}#MAIN table tr td span {color:#3E606F;font-size:24px;}.input {padding:5% 2.5% 5% 2.5%;border:none;width:94%;font-size:24px;background:#ffffa5;color:#3E606F;}.button {width:100%;background:#EF5350;border:none;outline:none;padding:5% 2.5% 5% 2.5%;border-radius:2px;font-family:Arial, Helvetica, sans-serif;color:#FFF;font-size:24px;cursor:pointer;opacity:0.9;}.button:hover {opacity:1;}#logo {margin-bottom:20px;}#logo #logo-i {width:200px;height:200px;margin:auto;border-radius:200px;overflow:hidden;border:2px solid #3E606F;}#logo #logo-i img {width:100%;height:auto;}#open_contact {cursor:pointer;opacity:0.8;}#open_contact:hover {opacity:1;}.popup {position:fixed;display:none;z-index:99;top:150px;border:1px solid #dfe0e4;cursor:pointer;background:#FFF;width:30%;left:35%;}.popup:hover {background:#dfe0e4;}.popup ul {margin:5%;}.popup ul li {font-size:14px;color:#3E606F;padding-bottom:5px;}.popup ul li span {font-weight:600;}.popup ul p {margin-bottom:10px;font-size:14px;color:#3E606F;}#MAIN #logo {display: none;}
            <?php } ?>
        </style>
        
        <script src="http://localhost/www/TDUONG/admin/js/jquery.min.js"></script>
        <script>
			$(document).ready(function() {

                $("#ok").focus();
				
				$("#ok").click(function() {
					name = $("#name").val();
					pass = $("#pass").val();
					code = $("#code").val();
					
					if(name != "" && pass != "" && code != "") {
						
						return true;
					} else {
						
						$("#MAIN").css("opacity","0.3");
						$("#popup-error").fadeIn(250);
						return false;
					}
				});
				$("#again").click(function() {
					name = $("#name").val();
					pass = $("#pass").val();
					
					if(name != "" && pass != "") {
						if(confirm("Bạn có chắc chắn muốn gửi lại mã bảo mật?")) {
							return true;
						} else {
							return false;
						}
					} else {
						alert("Bạn hãy điền đúng tên đăng nhập và mật khẩu để được gửi lại mã bảo mật!");
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
    
    	<?php
			$error="";
			$name=$password=NULL;
			$ok_code=false;
			if(isset($_POST["again"])) {
				
				if(isset($_POST["name"])) {
					$name=addslashes($_POST["name"]);
				}
				
				if(isset($_POST["pass"])) {
					$password=md5(addslashes($_POST["pass"]));
				}
				
				if($name && $password) {
					$result = login_admin($name, $password);
					if(mysqli_num_rows($result) == 1) {
						$ok_code=true;
					}
				}
				
				if($ok_code) {
					if(count_time_code($name)<3) {
						$code=rand_pass(8);
						
						$sended=send_email("no-reply@bgo.edu.vn","mactavish124!@","dinh_van_kiet@yahoo.com.vn","BGO.EDU.VN: Tạo lại mã bảo mật","<p>Bạn đã gửi yêu cầu tạo lại mã bảo mật mới. Đây là mã an toàn mới của bạn: <span style='font-weight:600'>$code</span>.<br /><br />BGO.EDU.VN Support</p>",false);
						 
						if($sended!="ok") {
						   echo $sended;
						} else {
							$error = "<div class='popup' id='popup-error' style='display:block;'>
								<ul style='text-align:center;'>
									<p style='font-weight:600;'>OK</p>
									<p>Đã gửi thành công!</p>
								</ul>
							</div>";
							update_code_ad(md5($code));
							insert_time_code($name);
                            add_log(0,"Admin ($name) - ($password) gửi mã thành công, IP: $ip","login-admin");
						}
					} else {
                        add_log(0,"Admin ($name) - ($password) gửi mã quá nhiều, IP: $ip","login-admin");
						$error = "<div class='popup' id='popup-error' style='display:block;'>
							<ul style='text-align:center;'>
								<p style='font-weight:600;'>!!!</p>
								<p>Không thể gửi quá nhiều thư 1 ngày!</p>
							</ul>
						</div>";
					}
				} else {
                    add_log(0,"Admin ($name) - ($password) gửi mã không thành công (cú pháp), IP: $ip","login-admin");
					$error = "<div class='popup' id='popup-error' style='display:block;'>
						<ul style='text-align:center;'>
							<p style='font-weight:600;'>!!!</p>
							<p>Không tồn tại thông tin bạn cung cấp</p>
						</ul>
					</div>";
				}
			}
			$error="";
			$name=$password=$code=NULL;
			$ok_code=false;
			if(isset($_POST["ok"]) || (isset($_COOKIE["name"]) && isset($_COOKIE["pass"]) && isset($_COOKIE["code"]))) {
			
				if(isset($_POST["name"])) {
					$name=addslashes($_POST["name"]);
				}
				
				if(isset($_POST["pass"])) {
					$password=md5(addslashes($_POST["pass"]));
				}
				
				if(isset($_POST["code"])) {
					$code=md5($_POST["code"]);
					if(check_correct_ma($code,"$now_nam-$now_thang")) {
						$ok_code=true;
					}
					/*$query="SELECT ID_O FROM options WHERE content='$code' AND type='code' AND note='$now_nam-$now_thang' ORDER BY ID_O DESC LIMIT 1";
					$result=mysqli_query($db,$query);
					if(mysqli_num_rows($result)!=0) {
						$ok_code=true;
					}*/
				}

				if(isset($_COOKIE["name"]) && $name==NULL) {
                    $name=decode_data($_COOKIE["name"],$me);
                }

                if(isset($_COOKIE["pass"]) && $password==NULL) {
                    $password=addslashes(decode_data($_COOKIE["pass"],$me));
                }

                if(isset($_COOKIE["code"]) && $code==NULL) {
                    $code=addslashes(decode_data($_COOKIE["code"],$me));
                    if(check_correct_ma($code,"$now_nam-$now_thang")) {
                        $ok_code=true;
                    }
                }
				
				if($name && $password && $code && $ok_code) {
					$result = login_admin($name, $password);
					if(mysqli_num_rows($result) == 1) {
                        setcookie("name", "", time() - 3600, "/");
                        setcookie("pass", "", time() - 3600, "/");
                        setcookie("code", "", time() - 3600, "/");
						session_destroy();
						session_start();
						session_regenerate_id();
						$data=mysqli_fetch_assoc($result);
						$_SESSION["username"]=$data["username"];
						$_SESSION["ID"]=$data["ID"];
						$lmID=get_mon_main();
						$_SESSION["lmID"]=$lmID;
                        $monID=get_mon_of_lop($lmID);
                        $_SESSION["mon"]=$monID;
						$_SESSION["lop"]=get_lop_main();
                        $_SESSION["bieudo"]=0;
						$result2=get_mon_info($monID);
						$data2=mysqli_fetch_assoc($result2);
						$_SESSION["thang"]=$data2["thang"];

                        setcookie("name",encode_data($name,$me),time() + 86400,"/");
                        setcookie("pass",encode_data($password,$me),time() + 86400,"/");
                        setcookie("code",encode_data($code,$me),time() + 86400,"/");

                        clean_ddos($ip,date("Y-m-d"));
                        add_log(0,"Admin ($name) - ($password) - ($code) đăng nhập thành công, IP: $ip","login-admin");

						header("location:http://localhost/www/TDUONG/admin/home/");
						exit();
					} else {
                        setcookie("name", "", time() - 3600);
                        setcookie("pass", "", time() - 3600);
                        setcookie("code", "", time() - 3600);
                        add_log(0,"Admin ($name) - ($password) - ($code) đăng nhập không thành công, IP: $ip","login-admin");
						$error = "<div class='popup' id='popup-error' style='display:block;'>
							<ul style='text-align:center;'>
								<p style='font-weight:600;'>!!!</p>
								<p>Không tồn tại thông tin bạn cung cấp</p>
							</ul>
						</div>";
					}
				} else {
                    setcookie("name", "", time() - 3600);
                    setcookie("pass", "", time() - 3600);
                    setcookie("code", "", time() - 3600);
                    add_log(0,"Admin ($name) - ($password) - ($code) đăng nhập không thành công (cú pháp), IP: $ip","login-admin");
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
        
        	<div class="popup" id="popup-error">
            	<ul style="text-align:center;">
                    <p style="font-weight:600;">!!!</p>
                    <p>Vui lòng nhập đầy đủ thông tin đăng nhập</p>
               	</ul>
            </div>
            
            <?php echo $error; ?>
        
        	<div id="MAIN">
            	<div id="logo">
                	<div id="logo-i"><a href="http://localhost/www/TDUONG/admin/trang-chu/"><img src="http://localhost/www/TDUONG/admin/images/logo.jpg" /></a></div>
                </div>
            	<div class="main-i">
                    <form action="http://localhost/www/TDUONG/admin/trang-chu/" method="post">
                        <table>
                            <tr>
                                <td colspan="2"><span>Username</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" name="name" id="name" class="input" /></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span>Mật khẩu</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="password" name="pass" id="pass" class="input" /></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><span>Mã bảo mật</span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="password" name="code" id="code" class="input" /></td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right;"><input type="submit" name="ok" id="ok" class="button" value="Đăng nhập" /><input type="submit" name="again" id="again" class="button" value="Tạo mã" /></td>
                            </tr>
                        </table>
                    </form>
               	</div>
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>