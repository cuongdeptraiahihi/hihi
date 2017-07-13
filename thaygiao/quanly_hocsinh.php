<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>       
        
        <title>QUẢN LÝ HỌC SINH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>QUẢN LÝ HỌC SINH</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:10%;" class="hidden"><span>STT</span></th>
                                        <th style="width:10%;"><span></span></th>
                                        <th style="width:35%;"><span>Nội dung</span></th>
                                        <th style="width:15%;"><span></span></th>
                                        <th style="width:30%;" class="hidden"><span>Ghi chú</span></th>
                                    </tr>
                                    <tr>
                                    	<td class="hidden"><span>1</span></td>
                                        <td><span class="fa fa-bar-chart"></span></td>
                                        <td><span>Dữ liệu thông tin học sinh</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/<?php echo $_SESSION["lmID"]."/".$_SESSION["mon"]; ?>/'" /></td>
                                        <td class="hidden"><span>Xem các thông tin về các học sinh trong lớp</span></td>
                                    </tr>
                                    <tr>
                                    	<td class="hidden"><span>2</span></td>
                                        <td><span class="fa fa-table"></span></td>
                                        <td><span>Lịch sử điểm danh</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-diem-danh/<?php echo $_SESSION["lmID"]."/".$_SESSION["mon"]; ?>/1/'" /></td>
                                        <td class="hidden"><span>Xem các lịch sử đi học các học sinh trong lớp</span></td>
                                    </tr>
                                    <tr>
                                        <td class="hidden"><span>3</span></td>
                                        <td><span class="fa fa-level-up"></span></td>
                                        <td><span>Quản lý level</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/level/'" /></td>
                                        <td class="hidden"><span>Quản lý dữ liệu về level</span></td>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <td class="hidden"><span>6</span></td>-->
<!--                                        <td><span class="fa fa-gift"></span></td>-->
<!--                                        <td><span>Thẻ miễn phạt</span></td>-->
<!--                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/level-thuong/'" /></td>-->
<!--                                        <td class="hidden"><span>Quản lý thẻ miễn phạt</span></td>-->
<!--                                    </tr>-->
                                    <tr>
                                    	<td class="hidden"><span>4</span></td>
                                        <td><span class="fa fa-facebook-official"></span></td>
                                        <td><span>Đăng ký group facebook</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/dang-ky-face/'" /></td>
                                        <td class="hidden"><span>Xét duyệt và accept các e học sinh vào group facebook</span></td>
                                    </tr>
                                    <tr>
                                        <td class="hidden"><span>5</span></td>
                                        <td><span class="fa fa-exclamation"></span></td>
                                        <td><span>Thông báo</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/thong-bao/'" /></td>
                                        <td class="hidden"><span>Thông báo tới học sinh và quản lý thông báo</span></td>
                                    </tr>
<!--                                    <tr>-->
<!--                                        <td class="hidden"><span>9</span></td>-->
<!--                                        <td><span class="fa fa-line-chart"></span></td>-->
<!--                                        <td><span>Thống kê lượt truy cập</span></td>-->
<!--                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/truy-cap/'" /></td>-->
<!--                                        <td class="hidden"><span>Biểu đồ thông kê lượt truy cập 1 tháng gần nhất</span></td>-->
<!--                                    </tr>-->
                                    <tr>
                                        <td class="hidden"><span>6</span></td>
                                        <td><span class="fa fa-sign-out"></span></td>
                                        <td><span>Học sinh nghỉ</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/nghi-hoc/<?php echo $_SESSION["lmID"]."/".$_SESSION["mon"]; ?>/'" /></td>
                                        <td class="hidden"><span>Danh sách và lý do các học sinh nghỉ</span></td>
                                    </tr>
                                    <!--<tr>
                                    	<td class="hidden"><span>8</span></td>
                                        <td><span class="fa fa-bug"></span></td>
                                        <td><span>Log</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/xem-log/'" /></td>
                                        <td class="hidden"><span>Quản lý hoạt động ghi log</span></td>
                                    </tr>
                                    <tr>
                                    	<td class="hidden"><span>7</span></td>
                                        <td><span class="fa fa-user-times"></span></td>
                                        <td><span>Nghỉ học</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/nghi-hoc/<?php echo $_SESSION["lmID"]."/".$_SESSION["mon"]; ?>/'" /></td>
                                        <td class="hidden"><span>Thêm, xóa danh sách học sinh nghỉ học</span></td>
                                    </tr>-->
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