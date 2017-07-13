<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>       
        
        <title>QUẢN LÝ TIỀN</title>
        
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
                	<h2>QUẢN LÝ TIỀN</h2>
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
                                        <td><span class="fa fa-money"></span></td>
                                        <td><span>Nhập - Nạp - Rút tiền</span></td>
                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-tien-hoc/'" /></td>
                                        <td class="hidden"><span>Các thao tác tiền với tài khoản học sinh</span></td>
                                    </tr>
<!--                                    <tr>-->
<!--                                    	<td class="hidden"><span>2</span></td>-->
<!--                                        <td><span class="fa fa-bank"></span></td>-->
<!--                                        <td><span>Thống kê tiền phạt</span></td>-->
<!--                                        <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/tai-khoan/--><?php //echo $lmID; ?>///'" /></td>
<!--//                                        <td class="hidden"><span>Thống kê tiền phạt, thưởng của học sinh</span></td>-->
<!--//                                    </tr>-->
                                    <tr>
                                    	<td class="hidden"><span>2</span></td>
                                        <td><span class="fa fa-dollar"></span></td>
                                        <td><span>Thống kê tiền học <b><?php echo get_lop_mon_name($lmID); ?></b></span></td>
                                        <td><input class='submit' type='submit' value='Xuất Excel (lâu)' onclick="location.href='http://localhost/www/TDUONG/thaygiao/kiem-tra-tien-hoc-mon/<?php echo $lmID; ?>/1/'" /></td>
                                        <td class="hidden"><span>Thống kê tiền đóng học các tháng của học sinh</span></td>
                                    </tr>
                                    <tr>
                                        <td class="hidden"><span>3</span></td>
                                        <td><span class="fa fa-eye"></span></td>
                                        <td><span>Kiểm tra tiền học</span></td>
                                        <td><input class='submit' type='submit' value='Kiểm tra' onclick="location.href='http://localhost/www/TDUONG/thaygiao/kiem-tra-tien/'" /></td>
                                        <td class="hidden"><span>Kiểm tra tiền học và tiền phạt nhập vào</span></td>
                                    </tr>
<!--                                    <tr>-->
<!--                                    	<td class="hidden"><span>4</span></td>-->
<!--                                        <td><span class="fa fa-search"></span></td>-->
<!--                                        <td><span>Tìm hóa đơn</span></td>-->
<!--                                        <td><input class='submit' type='submit' value='Tìm' onclick="location.href='http://localhost/www/TDUONG/thaygiao/tien-search/'" /></td>-->
<!--                                        <td class="hidden"><span>Tìm hóa đơn của học sinh theo ngày đóng</span></td>-->
<!--                                    </tr>-->
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