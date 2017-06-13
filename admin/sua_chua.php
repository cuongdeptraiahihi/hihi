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
        
        <title>SỬA CHỮA</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$(".fixed").click(function () {
				action = $(this).attr("data-action");
				if(confirm("Bạn có chắc chắn làm điều này?")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					$.ajax({
						async: true,
						data: "action=" + action,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-sua-chua/",
						success: function(result) {
							if(result=="ok") {
								alert("Đã sửa xong!");
							} else {
								alert(result);
							}
							$("#BODY").css("opacity","1");
							$("#popup-loading").fadeOut("fast");
						}
					});
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>Sửa chữa</h2>
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
<!--                                <tr>-->
<!--                                    <td><span>1</span></td>-->
<!--                                    <td><span class="fa fa-list-ol"></span></td>-->
<!--                                    <td><span>Dữ liệu các môn học</span></td>-->
<!--                                    <td><input class='submit fixed' type='submit' value='Sửa' data-action='mon-hoc' /></td>-->
<!--                                    <td><span>Sửa chữa dữ liệu các môn học, kiểm tra</span></td>-->
<!--                                </tr>-->
                                <tr>
                                    <td><span>1</span></td>
                                    <td><span class="fa fa-table"></span></td>
                                    <td><span>Dữ liệu đăng ký ca học</span></td>
                                    <td><input class='submit fixed' type='submit' value='Sửa' data-action='ca-hoc' /></td>
                                    <td><span>Sửa chữa dữ liệu đăng ký ca học của học sinh</span></td>
                                </tr>
                                <tr>
                                    <td><span>2</span></td>
                                    <td><span class="fa fa-mail-reply"></span></td>
                                    <td><span>Hủy toàn bộ ca TẠM</span></td>
                                    <td><input class='submit fixed' type='submit' value='Sửa' data-action='ca-tam' /></td>
                                    <td><span>Đưa toàn bộ ca tạm về ca cố định</span></td>
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