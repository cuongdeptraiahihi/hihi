<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SMS VÀ THÔNG BÁO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#FIX {display: none;}#MAIN > #main-mid {width:100%;margin-right:0;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
            $temp=get_next_time(date("Y"),date("m"));
            $temp=explode("-",$temp);
            $thang_toi=$temp[1]+1-1;
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>SMS và Thông báo</h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                                <tr>
                                    <td><span><i class="fa fa-newspaper-o"></i></span></td>
                                    <td style="text-align: left;padding-left: 15px;" colspan="2"><a href="http://localhost/www/TDUONG/thaygiao/sms/">Thông báo tiền học tháng <?php echo $thang_toi; ?> (Excel)</a></td>
                                </tr>
                                <tr>
                                    <td><span><i class="fa fa-newspaper-o"></i></span></td>
                                    <td style="text-align: left;padding-left: 15px;" colspan="2"><a href="http://localhost/www/TDUONG/thaygiao/sms-tuy-chon/">Nội dung tùy chọn</a></td>
                                </tr>
                                <tr>
                                    <td><span><i class="fa fa-newspaper-o"></i></span></td>
                                    <td style="text-align: left;padding-left: 15px;" colspan="2"><a href="http://localhost/www/TDUONG/thaygiao/sms-tu-dong/">SMS đơn lẻ</a></td>
                                </tr>
                                <tr style="background: #3E606F;">
                                    <th colspan="3"><span>Danh sách các biến</span></th>
                                </tr>
                                <tr>
                                    <td style="width: 10%;"><span>1</span></td>
                                    <td><span>Họ tên học sinh</span></td>
                                    <td><span>{ho_ten_hoc_sinh}</span></td>
                                </tr>
                                <tr>
                                    <td><span>2</span></td>
                                    <td><span>Mã số học sinh</span></td>
                                    <td><span>{maso_hoc_sinh}</span></td>
                                </tr>
                                <tr>
                                    <td><span>3</span></td>
                                    <td><span>Số điện thoại phụ huynh</span></td>
                                    <td><span>{sdt_phu_huynh}</span></td>
                                </tr>
                            </table>
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