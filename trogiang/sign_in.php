<?php
	ob_start();
    session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	$ip=$_SERVER['REMOTE_ADDR'];
	if(is_ddos($ip,date("Y-m-d"))) {
		header("location:https://localhost/www/TDUONG/ddos/");
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
       	<link rel="stylesheet" href="http://localhost/www/TDUONG/trogiang/css/new.css" type="text/css" />
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/trogiang/css/font-awesome.min.css">
        
        <style>
			* {padding:0;margin:0;}a {text-decoration:none;}ul, li {list-style-type:none;}body {width:100%;height:100%;background:#D1DBBD;font-family:Helvetica, Verdana, Arial, sans-serif;background:url(http://localhost/www/TDUONG/trogiang/images/earth_moon2.jpg);background-repeat:no-repeat;background-size:cover;letter-spacing:0.5px;}#BODY {width:100%;height:100%;}#MAIN {width:340px;margin:60px 0 0 70%;border-radius:20px;padding:15px;}#MAIN .main-i {background:rgba(255,255,255,0.35);border-radius:10px;padding:15px;}#MAIN table {width:100%;border-collapse:collapse;}#MAIN table tr.padUnder > td {padding:5px 0 5px 0;}#MAIN table tr td {}#MAIN table tr td span {color:#000;font-size:22px;font-weight:600;display:block;text-align:center;}#MAIN table tr td span i {font-size:16px;}.input {padding:10px 5% 10px 5%;letter-spacing:0.5px;border:none;width:90%;font-size:18px;font-family:Helvetica, Verdana, Arial, sans-serif;background:#FFF;color:#000;font-weight:600;}.button {font-family:Helvetica, Verdana, Arial, sans-serif;letter-spacing:0.5px;background:#000;border:none;border-radius:5px;padding:10px 15px 10px 15px;color:#FFF;font-size:18px;font-weight:600;cursor:pointer;outline:none;}#logo {margin-bottom:20px;}#logo #logo-i {width:100%;height:190px;margin:auto;border-radius:20px;overflow:hidden;border:2px solid #FFFA03;background:#000;box-shadow: 0 0 0 3px #000, 6px 6px 6px #888;-webkit-box-shadow:0 0 0 3px #000, 6px 6px 6px #888;-moz-box-shadow:0 0 0 3px #000, 6px 6px 6px #888;text-align:center;}#logo #logo-i p {font-size:9.375em;line-height:190px;font-weight:100;color:#FFFA03;}#open_contact {cursor:pointer;opacity:0.8;}#open_contact:hover {opacity:1;}.popup {position:fixed;z-index:99;display:none;top:30%;background:#FFF;border-radius:10px;width:25%;text-align:center;cursor:pointer;left:35%;box-shadow:0 2px 5px 0 rgba(0,0,0,.16),0 2px 10px 0 rgba(0,0,0,.12);padding:1.5% 2.5%;}.popup p {font-size:18px;color:#000;font-weight:600;line-height:22px;margin-bottom:10px;}.popup .popup-close {position:absolute;z-index:999;right:-15px;top:-15px;width:25px;height:25px;border-radius:25px;text-align:center;background:#FFF;}.popup .popup-close i {line-height:24px;color:#000;font-size:14px;}
            #SMS {  border-top-left-radius:10px;  border-top-right-radius:10px;  position:fixed;  z-index:999;  bottom:0;  left:33px;  background:#365899;  width:130px;  height:25px;  padding:5px 10px;  }  #SMS a {  display:block;  font-size:22px;  margin-left:5px;  }  #SMS a i {  line-height:26px;  color:#FFF;  }  #SMS a span {  font-weight:600;  color:#FFF;  font-size:14px;  margin-left:20px;  line-height:26px;}
            @media screen and (max-width: 300px) {
				#MAIN {width:90%;}
			}
        </style>

        <?php
        $error="";
        if(isset ($_POST["ok"])) {
            $name=addslashes($_POST["name"]);
            $pass=md5(trim(addslashes($_POST["pass"])));
            if($name != "" && $pass != "") {
                $query = "SELECT ID_TG FROM trogiang_info WHERE name='$name' AND pass='$pass'";
                $result=mysqli_query($db,$query);
                if(mysqli_num_rows($result) != 0) {
                    $data=mysqli_fetch_assoc($result);
                    $_SESSION['id']=$data['ID_TG'];
                    header("location: http://localhost/www/TDUONG/trogiang/tong-quan/");
                    exit();
                } else {
                    $error = "<div class='popup' id='popup-error' style='display:block;'>
						<ul style='text-align:center;'>
							<p style='font-weight:600;'>!!!</p>
							<p>Bạn chưa có tài khoản đăng nhập!</p>
						</ul>
					</div>";
                }
            } else {
                $error = "<div class='popup' id='popup-error' style='display:block;'>
                    <ul style='text-align:center;'>
                        <p style='font-weight:600;'>!!!</p>
                        <p>Vui lòng nhập đầy đủ thông tin đăng nhập!</p>
                    </ul>
                </div>";
            }
        }
        ?>
        <script src="http://localhost/www/TDUONG/trogiang/js/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $(".popup").click(function () {
                    $(this).fadeOut(250);
                    $("#MAIN").css("opacity", "1");
                });
            });
        </script>
    </head>
    <body>
                             
      	<div id="BODY">

            <?php echo $error; ?>
        
        	<div id="MAIN">
            	<div class="main-i">
                    <form action="http://localhost/www/TDUONG/trogiang/dang-nhap/" method="post">
                        <table>
                            <tr>
                                <td colspan="3"><span style="text-align:left;">Đăng nhập</span></td>
                            </tr>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr style="border:2px solid #dfe0e4;">
                            	<td style="width:15%;background:#D1DBBD;"><span><i class="fa fa-user"></i></span></td>
                                <td style="width:85%;" colspan="2"><input type="text" name="name" id="name" autocomplete="off" class="input" placeholder="Tên trợ giảng" /></td>
                            </tr>
                            <tr style="border:2px solid #dfe0e4;">
                            	<td style="background:#D1DBBD;"><span><i class="fa fa-unlock-alt"></i></span></td>
                                <td colspan="2"><input type="password" name="pass" id="pass" autocomplete="off" class="input" placeholder="Mật khẩu" /></td>
                            </tr>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3"><input type="submit" name="ok" id="ok" class="button" value="Đăng nhập" style="width:100%;" /></td>
                            </tr>
                            <tr class="padUnder">
                                <td colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="float:right;font-size:12px;">@ 2016 Bgo.edu.vn</span>
                               	</td>
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