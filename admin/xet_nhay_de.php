<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $mon_lop_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÍNH ĐIỂM TB THÁNG VÀ NHẢY ĐỀ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			
			$("#xem").click(function() {
				thang = $("#select-thang").val();
				thongbao = 0;
				if($("#check-tb").is(":checked")) {
					thongbao = 1;
				} 
				nhayde = 0;
				if($("#check-nhayde").is(":checked")) {
					nhayde = 1;
				} 
				if(thang!="" && (thongbao==0 || thongbao==1) && (nhayde==0 || nhayde==1)) {
					if(confirm("Bạn có chắc chắn muốn tính điểm TB và xét nhảy đề của tháng " + thang + " ?")) {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity", "0.3");
						$.ajax({
							async: true,
							data: "lmID=" + <?php echo $lmID; ?> + "&thang=" + thang + "&thongbao=" + thongbao + "&nhayde=" + nhayde,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-diem/",
							success: function(result) {
								if(result=="ok") {
									$(".status table.table").append("<tr><td colspan='2'><a href='http://localhost/www/TDUONG/admin/moi-chuyen-de/" + <?php echo $lmID; ?> + "/G/'>Click vào đây để xem danh sách học sinh lên đề G</a></td></tr>");
									$("#xem").val("Xong").css("background","blue");
								} else {
									alert("Dữ liệu không chính xác!" + result);
									$("#xem").val("Lỗi").css("background","red");
								}
								$("#BODY").css("opacity", "1");
								$("#popup-loading").fadeOut("fast");
							}
						});
					}
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
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
                	<h2>Tính điểm TB tháng và xét nhảy đề <span style="font-weight:600;">môn <?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                                <tr>
                                    <td style="width:50%;"><span>Tháng để tính</span></td>
                                    <td style="width:50%;">
                                        <select class="input" style="height:auto;width:100%;" id="select-thang" name="thang">
                                        <?php
                                            $now_nam=date("Y");
                                            $now_thang=date("m");
                                            $i=4;
                                            while($i>=0) {
                                                echo"<option value='$now_nam-$now_thang'>$now_thang/$now_nam</option>";
                                                $now_nam=get_last_year($now_thang,$now_nam);
                                                $now_thang=get_last_month($now_thang);
                                                $i--;
                                            }
                                        ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span>Chuyển đề dựa theo điểm TB tháng</span></td>
                                    <td><input type="checkbox" class="check" id="check-nhayde" name="check-nhayde" /></td>
                                </tr>
                                <tr>
                                    <td><span>Thông báo tới học sinh</span></td>
                                    <td><input type="checkbox" class="check" id="check-tb" name="check-tb" checked="checked" /></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><span>Khi được Click, hệ thống sẽ tính điểm TB của tháng được chọn, sau đó cập nhật nếu đã có điểm TB tháng đó hoặc ghi mới nếu chưa có! Sau đó đưa ra danh sách những người lên đề hoặc xuống đề</span></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Tính" /></td>
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