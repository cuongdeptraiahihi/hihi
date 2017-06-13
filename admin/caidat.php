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
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
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
						url: "http://localhost/www/TDUONG/admin/xuly-test/",
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
                    url: "http://localhost/www/TDUONG/admin/xuly-test/",
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
						url: "http://localhost/www/TDUONG/admin/xuly-test/",
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
						url: "http://localhost/www/TDUONG/admin/xuly-test/",
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
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>CÀI ĐẶT</h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                            <?php if(check_testing()) { ?>
                                <tr>
                                    <td colspan="2"><span style="font-weight:600;">Bật/Tắt chế độ TEST (đang bật)</span></td>
                                    <td><span id="testing" class="fa fa-toggle-on" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2"><span>Bật/Tắt chế độ TEST (đang tắt)</span></td>
                                    <td><span id="testing" class="fa fa-toggle-off" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } ?>
                            <?php if(check_nhayde()) { ?>
                                <tr>
                                    <td colspan="2"><span style="font-weight:600;">Bật/Tắt nhảy đề (đang bật)</span></td>
                                    <td><span id="nhayde" class="fa fa-toggle-on" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2"><span>Bật/Tắt nhảy đề (đang tắt)</span></td>
                                    <td><span id="nhayde" class="fa fa-toggle-off" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } ?>
                            <?php if(check_show_tien()) { ?>
                                <tr>
                                    <td colspan="2"><span style="font-weight:600;">Bật/Tắt hiển thị tiền (đang bật)</span></td>
                                    <td><span id="show_tien" class="fa fa-toggle-on" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2"><span>Bật/Tắt hiển thị tiền (đang tắt)</span></td>
                                    <td><span id="show_tien" class="fa fa-toggle-off" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } ?>
                            <?php if(check_game()) { ?>
                                <tr>
                                    <td colspan="2"><span style="font-weight:600;">Bật/Tắt khóa game (đang mở)</span></td>
                                    <td><span id="game" class="fa fa-toggle-on" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2"><span>Bật/Tắt khóa game (đang khóa)</span></td>
                                    <td><span id="game" class="fa fa-toggle-off" style="cursor:pointer;"></span></td>
                                </tr>
                            <?php } ?>
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
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/chuyen-de/<?php echo $_SESSION["lmID"]; ?>/'" /></td>
                                    <td class="hidden"><span>Cấu hình, thêm sửa xóa các chuyên đề của các môn</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>2</span></td>
                                    <td><span class="fa fa-usd"></span></td>
                                    <td><span>Định mức tiền</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/cai-dat-tien/'" /></td>
                                    <td class="hidden"><span>Chỉnh sửa tiền học, tiền thưởng và phạt của học sinh</span></td>
                                </tr>
                                <!--<tr>
                                    <td><span>3</span></td>
                                    <td><span class="fa fa-hourglass-half"></span></td>
                                    <td><span>Lịch sử code chạy tự động</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/admin/dong-bo/'" /></td>
                                    <td><span>Kiểm tra lịch sử các chức năng chạy tự động: nhảy đề, kết quả thách đấu, ngôi sao hy vọng,...</span></td>
                                </tr>-->
                                <tr>
                                    <td class="hidden"><span>3</span></td>
                                    <td><span class="fa fa-exclamation-triangle"></span></td>
                                    <td><span>Các lý do hủy bài</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/li-do/'" /></td>
                                    <td class="hidden"><span>Cấu hình các lí do hủy bài kiểm tra</span></td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td><span>4</span></td>-->
<!--                                    <td><span class="fa fa-university"></span></td>-->
<!--                                    <td><span>Trường cấp 3</span></td>-->
<!--                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/pre-truong-hoc/'" /></td>-->
<!--                                    <td><span>Quản lý danh sách các trường học và học sinh của các trường</span></td>-->
<!--                                </tr>-->
                                <tr>
                                    <td class="hidden"><span>4</span></td>
                                    <td><span class="fa fa-picture-o"></span></td>
                                    <td><span>Hình nền</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/background/'" /></td>
                                    <td class="hidden"><span>Thay đổi nền background</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>5</span></td>
                                    <td><span class="fa fa-cubes"></span></td>
                                    <td><span>Môn học</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/mon-hoc/'" /></td>
                                    <td class="hidden"><span>Cấu hình thông tin môn học</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>6</span></td>
                                    <td><span class="fa fa-cube"></span></td>
                                    <td><span>Khóa học</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/khoa-hoc/'" /></td>
                                    <td class="hidden"><span>Thêm và chỉnh sửa thông tin khóa học</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>7</span></td>
                                    <td><span class="fa fa-facebook-official"></span></td>
                                    <td><span>Group Facebook</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/facebook/'" /></td>
                                    <td class="hidden"><span>Cài đặt group facebook cho các khóa, môn</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>8</span></td>
                                    <td><span class="fa fa-lock"></span></td>
                                    <td><span>Khóa thách đấu, ngôi sao hy vọng</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/khoa/'" /></td>
                                    <td class="hidden"><span>Khóa để học sinh không thể thách đấu vào những buổi không kiểm tra</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>9</span></td>
                                    <td><span class="fa fa-clock-o"></span></td>
                                    <td><span>Thời gian đổi ca Miễn Phí</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/ca-time/'" /></td>
                                    <td class="hidden"><span>Cài đặt thời gian đổi ca ko mất tiền</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>10</span></td>
                                    <td><span class="fa fa-users"></span></td>
                                    <td><span>Trợ giảng</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/tro-giang/'" /></td>
                                    <td class="hidden"><span>Cài đặt mã trợ giảng, tên trợ giảng và thông tin</span></td>
                                </tr>
<!--                                <tr>-->
<!--                                    <td class="hidden"><span>11</span></td>-->
<!--                                    <td><span class="fa fa-cog"></span></td>-->
<!--                                    <td><span>Sửa chữa</span></td>-->
<!--                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/sua-chua/'" /></td>-->
<!--                                    <td class="hidden"><span>Sửa chữa và cài đặt CSDL</span></td>-->
<!--                                </tr>-->
                                <tr>
                                    <td class="hidden"><span>11</span></td>
                                    <td><span class="fa fa-table"></span></td>
                                    <td><span>Mở tháng đóng tiền học</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/kiem-tra-tien-hoc-mon/<?php echo $_SESSION["lmID"]; ?>/0/'" /></td>
                                    <td class="hidden"><span>Mở tháng để dự kiến tiền học</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>12</span></td>
                                    <td><span class="fa fa-file"></span></td>
                                    <td><span>Tài liệu</span></td>
                                    <td><input class='submit' type='submit' value='Cài đặt' onclick="location.href='http://localhost/www/TDUONG/admin/pre-tai-lieu/'" /></td>
                                    <td class="hidden"><span>Tài liệu của môn học</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>13</span></td>
                                    <td><span class="fa fa-level-up"></span></td>
                                    <td><span>Quản lý level</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/admin/level/'" /></td>
                                    <td class="hidden"><span>Quản lý dữ liệu về level</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>14</span></td>
                                    <td><span class="fa fa-facebook-official"></span></td>
                                    <td><span>Đăng ký group facebook</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/admin/dang-ky-face/'" /></td>
                                    <td class="hidden"><span>Xét duyệt và accept các e học sinh vào group facebook</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>15</span></td>
                                    <td><span class="fa fa-exclamation"></span></td>
                                    <td><span>Thông báo</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/admin/thong-bao/'" /></td>
                                    <td class="hidden"><span>Thông báo tới học sinh và quản lý thông báo</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>16</span></td>
                                    <td><span class="fa fa-sign-out"></span></td>
                                    <td><span>Học sinh nghỉ</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/admin/nghi-hoc/<?php echo $_SESSION["lmID"]."/".$_SESSION["mon"]; ?>/'" /></td>
                                    <td class="hidden"><span>Danh sách và lý do các học sinh nghỉ</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>17</span></td>
                                    <td><span class="fa fa-calendar"></span></td>
                                    <td><span>Điểm danh trợ giảng</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/admin/diem-danh-tro-giang/'" /></td>
                                    <td class="hidden"><span>Điểm danh lịch làm việc của trợ giảng</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
               	</div>

                <div id="main-mid">
                    <h2>CÁC CA HỌC</h2>
                    <div>
                        <div class="status">
                            <table class="table">
                                <tr>
                                    <th style="width:33%;"><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-ca-all/<?php echo $_SESSION["lmID"]; ?>/'" value="Đổi ca hàng loạt" /></th>
                                    <th style="width:33%;"><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-khoa-ca/<?php echo $_SESSION["lmID"]; ?>/'" value="Mở khóa ca" /></th>
                                    <th style="width:33%;"><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/dia-diem/'" value="Địa điểm" /></th>
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