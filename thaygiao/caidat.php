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
        
        <title>CÀI ĐẶT</title>
        
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
			$("#testing").click(function() {
				if(confirm("Bạn có chắc chắn làm điều này?")) {
					$.ajax({
						async: true,
						data: "action=testing",
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-test/",
						success: function(result) {
							location.reload();
						}
					});
				}
			});

            $("#game").click(function() {
                $.ajax({
                    async: true,
                    data: "action=game",
                    type: "post",
                    url: "http://localhost/www/TDUONG/thaygiao/xuly-test/",
                    success: function(result) {
                        location.reload();
                    }
                });
            });

			$("#nhayde").click(function() {
				if(confirm("Bạn có chắc chắn làm điều này?")) {
					$.ajax({
						async: true,
						data: "action=nhayde",
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-test/",
						success: function(result) {
							location.reload();
						}
					});
				}
			});
			$("#show_tien").click(function() {
				if(confirm("Bạn có chắc chắn làm điều này?")) {
					$.ajax({
						async: true,
						data: "action=show_tien",
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-test/",
						success: function(result) {
							location.reload();
						}
					});
				}
			});
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
                	<h2>CÀI ĐẶT</h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                                <tr style="background:#3E606F;">
                                    <th class="hidden" style="width:10%;"><span>STT</span></th>
                                    <th style="width:10%;"><span></span></th>
                                    <th style="width:35%;"><span>Nội dung</span></th>
                                    <th style="width:15%;"><span></span></th>
                                    <th class="hidden" style="width:30%;"><span>Ghi chú</span></th>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>1</span></td>
                                    <td><span class="fa fa-list-ol"></span></td>
                                    <td><span>Danh sách các chuyên đề</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/thaygiao/chuyen-de/<?php echo $_SESSION["lmID"]; ?>/'" /></td>
                                    <td class="hidden"><span>Cấu hình, thêm sửa xóa các chuyên đề của các môn</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>2</span></td>
                                    <td><span class="fa fa-file"></span></td>
                                    <td><span>Tài liệu</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/thaygiao/pre-tai-lieu/'" /></td>
                                    <td class="hidden"><span>Tài liệu của môn học</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>3</span></td>
                                    <td><span class="fa fa-facebook-official"></span></td>
                                    <td><span>Đăng ký group facebook</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/dang-ky-face/'" /></td>
                                    <td class="hidden"><span>Xét duyệt và accept các e học sinh vào group facebook</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>4</span></td>
                                    <td><span class="fa fa-sign-out"></span></td>
                                    <td><span>Học sinh nghỉ</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/nghi-hoc/<?php echo $_SESSION["lmID"]."/".$_SESSION["mon"]; ?>/'" /></td>
                                    <td class="hidden"><span>Danh sách và lý do các học sinh nghỉ</span></td>
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