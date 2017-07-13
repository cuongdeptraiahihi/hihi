<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>       
        
        <title>QUẢN LÝ TÀI LIỆU</title>
        
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
                	<h2>QUẢN LÝ TÀI LIỆU</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:10%;"><span>STT</span></th>
                                        <th style="width:10%;"><span></span></th>
                                        <th style="width:35%;"><span>Nội dung</span></th>
                                        <th style="width:15%;"><span></span></th>
                                        <th style="width:30%;"><span>Ghi chú</span></th>
                                    </tr>
                                    <!--<tr>
                                    	<td><span>1</span></td>
                                        <td><span class="fa fa-video-camera"></span></td>
                                        <td><span>Videos</span></td>
                                        <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/thaygiao/videos/'" /></td>
                                        <td><span>Up video, sửa, xóa, unlock,...</span></td>
                                    </tr>
                                    <tr>
                                    	<td><span>2</span></td>
                                        <td><span class="fa fa-unlock-alt"></span></td>
                                        <td><span>Mở video cho từng học sinh</span></td>
                                        <td><input class='submit' type='submit' value='Mở' onclick="location.href='http://localhost/www/TDUONG/thaygiao/mo-video-hoc-sinh/'" /></td>
                                        <td><span>Mở video cho từng học sinh cụ thể</span></td>
                                    </tr>
                                    <tr>
                                    	<td><span>3</span></td>
                                        <td><span class="fa fa-unlock"></span></td>
                                        <td><span>Mở video hàng loạt</span></td>
                                        <td><input class='submit' type='submit' value='Mở' onclick="location.href='http://localhost/www/TDUONG/thaygiao/pre-mo-video-hang-loat/'" /></td>
                                        <td><span>Mở video hàng loạt cho nhiều học sinh</span></td>
                                    </tr>-->
                                    <tr>
                                    	<td><span>1</span></td>
                                        <td><span class="fa fa-folder"></span></td>
                                        <td><span>Tài liệu</span></td>
                                        <td><input class='submit' type='submit' value='Mở' onclick="location.href='http://localhost/www/TDUONG/thaygiao/pre-tai-lieu/'" /></td>
                                        <td><span>Up và chỉnh sửa tài liệu</span></td>
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