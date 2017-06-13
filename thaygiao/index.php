<?php
	ob_start();
    session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
    $ip=$_SERVER['REMOTE_ADDR'];
	$now_nam=date("Y");
	$now_thang=date("m");
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
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            <?php if($_SESSION["mobile"]==0) { ?>
            * {padding:0;margin:0;font-family: "Roboto", Helvetica Neue, Helvetica, Arial, sans-serif;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;margin:auto;background:#D1DBBD;}#BODY {width:100%;height:100%;}#MAIN {width:320px;height:auto;margin:80px auto 0 auto;}#MAIN .main-i {background:#FFF;border:1px solid #dfe0e4;padding:30px 20px 30px 20px;}#MAIN table {width:100%;}#MAIN table tr td {padding-bottom:5px;}#MAIN table tr td span {color:#37474F;font-size:14px;}.input {padding:10px 2.5% 10px 2.5%;border:none;width:95%;font-size:18px;background:#ffffa5;color:#37474F;}.button {background:#EF5350;border:none;outline:none;padding:8px 10px 8px 10px;border-radius:2px;font-family:Arial, Helvetica, sans-serif;color:#FFF;font-size:14px;cursor:pointer;opacity:0.9;margin: 0 2.5px 0 2.5px;}.button:hover {opacity:1;}#logo {margin-bottom:20px;}#logo #logo-i {width:94px;height:94px;margin:auto;border-radius:94px;overflow:hidden;border:2px solid #37474F;}#logo #logo-i img {width:100%;height:auto;}#open_contact {cursor:pointer;opacity:0.8;}#open_contact:hover {opacity:1;}.popup {position:fixed;display:none;z-index:99;top:150px;border:1px solid #dfe0e4;cursor:pointer;background:#FFF;width:30%;left:35%;}.popup:hover {background:#dfe0e4;}.popup ul {margin:5%;}.popup ul li {font-size:14px;color:#37474F;padding-bottom:5px;}.popup ul li span {font-weight:600;}.popup ul p {margin-bottom:10px;font-size:14px;color:#37474F;}
            <?php } else { ?>
            * {padding:0;margin:0;font-family: "Roboto", Helvetica Neue, Helvetica, Arial, sans-serif;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;margin:auto;background:#D1DBBD;}#BODY {width:100%;height:100%;}#MAIN {width:90%;height:auto;margin:40px auto 0 auto;}#MAIN .main-i {background:#FFF;border:1px solid #dfe0e4;padding:30px 20px 30px 20px;}#MAIN table {width:100%;}#MAIN table tr td {padding-bottom:5px;}#MAIN table tr td span {color:#37474F;font-size:24px;}.input {padding:5% 2.5% 5% 2.5%;border:none;width:94%;font-size:24px;background:#ffffa5;color:#37474F;}.button {width:100%;background:#EF5350;border:none;outline:none;padding:5% 2.5% 5% 2.5%;border-radius:2px;font-family:Arial, Helvetica, sans-serif;color:#FFF;font-size:24px;cursor:pointer;opacity:0.9;}.button:hover {opacity:1;}#logo {margin-bottom:20px;}#logo #logo-i {width:200px;height:200px;margin:auto;border-radius:200px;overflow:hidden;border:2px solid #37474F;}#logo #logo-i img {width:100%;height:auto;}#open_contact {cursor:pointer;opacity:0.8;}#open_contact:hover {opacity:1;}.popup {position:fixed;display:none;z-index:99;top:150px;border:1px solid #dfe0e4;cursor:pointer;background:#FFF;width:30%;left:35%;}.popup:hover {background:#dfe0e4;}.popup ul {margin:5%;}.popup ul li {font-size:14px;color:#37474F;padding-bottom:5px;}.popup ul li span {font-weight:600;}.popup ul p {margin-bottom:10px;font-size:14px;color:#37474F;}#MAIN #logo {display: none;}
            <?php } ?>
        </style>
        
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery.min.js"></script>
        <script>
			$(document).ready(function() {
				
				$("#ok").click(function() {
					name = $("#name").val();
					pass = $("#pass").val();
					monID = $("#select-mon option:selected").val();
					
					if(name != "" && pass != "" && monID != 0 && $.isNumeric(monID)) {
						
						return true;
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
			});
		</script>
       
	</head>

    <body>
    
    	<?php
			$error="";
			$name=$password=$monID=NULL;
			$ok_code=false;
			if(isset($_POST["ok"])) {
			
				if(isset($_POST["name"])) {
					$name=addslashes($_POST["name"]);
				}
				
				if(isset($_POST["pass"])) {
					$password=md5(addslashes($_POST["pass"]));
				}
				
				if(isset($_POST["monID"])) {
					$monID=$_POST["monID"];
				}
				
				if($name && $password && $monID!=0 && is_numeric($monID)) {
					$result = login_thaygiao($name, $password, $monID);
					if(mysqli_num_rows($result) == 1) {
						session_start();
						session_destroy();
						session_start();
						session_regenerate_id();
                        $data=mysqli_fetch_assoc($result);
                        $_SESSION["username"]=$data["username"];
                        $_SESSION["ID"]=$data["ID"];
                        $lmID=get_lop_mon_main($monID);
                        $_SESSION["lmID"]=$lmID;
                        $_SESSION["mon"]=$monID;
                        $_SESSION["lop"]=get_lop_main();
                        $_SESSION["bieudo"]=0;
						$result2=get_mon_info($monID);
						$data2=mysqli_fetch_assoc($result2);
						$_SESSION["thang"]=$data2["thang"];
                        $_SESSION["mobile"]=0;

                        clean_ddos($ip,date("Y-m-d"));
                        add_log(0,"Thầy giáo ($name) - ($password) - ($monID) đăng nhập thành công, IP: $ip","login-thay");
						
						header("location:http://localhost/www/TDUONG/thaygiao/home/");
						exit();
					} else {
                        add_log(0,"Thầy giáo ($name) - ($password) - ($monID) đăng nhập không thành công, IP: $ip","login-thay");
						$error = "<div class='popup' id='popup-error' style='display:block;'>
							<ul style='text-align:center;'>
								<p style='font-weight:600;'>!!!</p>
								<p>Không tồn tại thông tin bạn cung cấp</p>
							</ul>
						</div>";
					}
				} else {
                    add_log(0,"Thầy giáo ($name) - ($password) - ($monID) đăng nhập không thành công (cú pháp), IP: $ip","login-thay");
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
                	<div id="logo-i"><a href="http://localhost/www/TDUONG/thaygiao/trang-chu/"><img src="http://localhost/www/TDUONG/thaygiao/images/logo.jpg" /></a></div>
                </div>
            	<div class="main-i">
                    <form action="http://localhost/www/TDUONG/thaygiao/trang-chu/" method="post">
                        <table>
                            <tr>
                                <td><span>Username</span></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="name" id="name" class="input" /></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td><span>Mật khẩu</span></td>
                            </tr>
                            <tr>
                                <td><input type="password" name="pass" id="pass" class="input" /></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td><span>Môn</span></td>
                           	</tr>
                            <tr>
                                <td>
                                    <select class="input" style="height:auto;width:100%;" id="select-mon" name="monID">
                                        <option value="0">Chọn môn</option>
                                    <?php
                                        $result=get_all_mon_admin();
                                        for($i=0;$i<count($result);$i++) {
                                            echo"<option value='".$result[$i]["monID"]."'>Môn ".$result[$i]["name"]."</option>";
                                        }
                                    ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align:right;"><input type="submit" name="ok" id="ok" class="button" value="Đăng nhập" /></td>
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