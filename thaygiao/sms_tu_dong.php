<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 2000);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SMS MỜI HỌP PHỤ HUYNH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		    $("#xuat-ok").click(function() {
		        if(confirm("Bạn có chắc chắn? Vui lòng đợi...")) {
		            return true;
                } else {
                    return false;
                }
            });
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
                $error = "";
                $username=$password=$mobile=$type=$content=$brandname=NULL;
                if(isset($_POST["send-ok"])) {
                    if(isset($_POST["username"])) {
                        $username=$_POST["username"];
                    }
                    if(isset($_POST["password"])) {
                        $password=$_POST["password"];
                    }
                    if(isset($_POST["mobile"])) {
                        $mobile=$_POST["mobile"];
                    }
                    if(isset($_POST["type"])) {
                        $type=$_POST["type"];
                    }
                    if(isset($_POST["content"])) {
                        $content=$_POST["content"];
                    }
                    if(isset($_POST["brandname"])) {
                        $brandname=$_POST["brandname"];
                    }
                    if($username && $password && $mobile && $type && $content) {
                        if($content != "") {
                            $content = addslashes($content);
                            $content=trim($content);
                            $pre_con="";
                            $temp=explode("\n",$content);
                            for($i=0;$i<count($temp);$i++) {
                                $me=trim($temp[$i]);
                                $me = str_replace(" ", "%20", $me);
                                $pre_con .= $me . "%0A";
                            }
                            $content=$pre_con;
                            $url = "http://g3g4.vn:8008/smsws/api/sendSms.jsp";
                            $ch = curl_init();
                            if ($brandname != NULL) {
                                curl_setopt($ch, CURLOPT_URL, $url . "?username=" . $username . "&password=" . $password . "&mobile=" . $mobile . "&content=" . $content . "&type=" . $type . "&brandname=" . $brandname . "&target=abc123xyz");
                            } else {
                                curl_setopt($ch, CURLOPT_URL, $url . "?username=" . $username . "&password=" . $password . "&mobile=" . $mobile . "&content=" . $content . "&type=" . $type . "&target=abc123xyz");
                            }
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_HEADER, false);
                            $output = curl_exec($ch);
                            echo $url . "?username=" . $username . "&password=" . $password . "&mobile=" . $mobile . "&content=" . $content . "&type=" . $type . "&target=abc123xyz";
                            //echo $pre_con;
                            curl_close($ch);
                            $error = $output;
                            echo $error;
                        }
                    }
                }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Cấu hình gửi SMS tự động</h2>
                	<div>
                    	<div class="status">
                            <form action="http://localhost/www/TDUONG/thaygiao/sms-tu-dong/" method="post">
                                <table class="table">
                                    <tr style="background: #3E606F;">
                                        <th colspan="2"><span>Thông tin bắt buộc</span></th>
                                    </tr>
                                    <tr>
                                        <td style="width: 25%;"><span>Tên truy cập *</span></td>
                                        <td><input type="text" class="input" name="username" value="phanduong" /></td>
                                    </tr>
                                    <tr>
                                        <td><span>Mật khẩu *</span></td>
                                        <td><input type="password" class="input" name="password" value="123456" /></td>
                                    </tr>
                                    <tr>
                                        <td><span>Số điện thoại *</span></td>
                                        <td><textarea style="resize: vertical;height:100px;" name="mobile" class="input" placeholder="Chọn ở trên để tự động điền. Nhiều số điện thoại cách nhau bằng dấu phẩy"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><span>Type *</span></td>
                                        <td>
                                            <select name="type" class="input" style="height: auto;width: 100%;">
                                                <option value="1">Gửi CSKH từ đầu số 1900xxxx</option>
                                                <option value="2">Gửi CSKH bằng BRANDNAME</option>
                                                <option value="4">Gửi QC từ đầu số bất kỳ</option>
                                                <option value="8">Gửi QC bằng BRANDNAME</option>
                                                <option value="10">Gửi QC từ đầu số cố định</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Nội dung *</span></td>
                                        <td><textarea class="input" name="content" style="resize: vertical;padding: 2.5%;" rows="7" placeholder="Nhap noi dung khong dau"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><span>BRANDNAME</span></td>
                                        <td><input type="text" class="input" name="brandname" placeholder="Điền brandname" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input type="submit" class="submit" value="Gửi" id="xuat-ok" name="send-ok"/></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
               	</div>
            
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>