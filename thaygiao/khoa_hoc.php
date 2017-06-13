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
        
        <title>KHÓA HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}.input {text-align:center;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			$("input.edit_kg").datepicker({ dateFormat: 'dd/mm/yy' });
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit_ok", "click", function() {
				lopID = $(this).attr("data-lopID");
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				name = del_tr.find("td input.edit_name").val();
				count_time = del_tr.find("td input.edit_time").val();
				if(name!=") {
					$.ajax({
						async: true,
						data: "lopID=" + lopID + "&name=" + name + "&time=" + count_time,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-lop/",
						success: function(result) {
							del_tr.css("opacity","1");
						}
					});
				}
				return false;
			});
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
				lopID = $(this).attr("data-lopID");
				del_tr = $(this).closest("tr");
				if(confirm("Bạn có chắc chắn muốn xóa lớp không?")) {
					$.ajax({
						async: true,
						data: "lopID0=" + lopID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-lop/",
						success: function(result) {
							del_tr.fadeOut("fast");
						}
					});
				}
				return false;
			});
			
			$("#add_khoa").click(function() {
				$("#lop-add, #date-add").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
			});
			
			$(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});
			
			$("#popup-ok").click(function() {
				lop = $("#lop-add").val();
				if(lop!="" && date_in!="") {
					$.ajax({
						async: true,
						data: "lop=" + lop,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-lop/",
						success: function(result) {
							$("#popup-add").fadeOut("fast");
							$("#BODY").css("opacity","1");
							location.reload();
						}
					});
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm khóa học</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            <input id="lop-add" class="input" autocomplete="off" placeholder="Tên khóa" type="text" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>DANH SÁCH CÁC KHÓA HỌC</span></h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                    	<td colspan="4"><span></span></td>
                                        <td><input type="submit" class="submit" id="add_khoa" value="Thêm khóa mới" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                    	<th style="width:10%;"><span>ID</span></th>
                                        <th style="width:15%;"><span>Khóa</span></th>
                                        <th style="width:10%;"><span>Sĩ sô</span></th>
                                        <th style="width:20%;"><span>Đếm ngược<br />(Y-m-d H:i:s)</span></th>
                                        <th style="width:20%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_all_lop2();
										while($data=mysqli_fetch_assoc($result)) {
											$siso=count_hs_in_lop($data["ID_LOP"]);
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>$data[ID_LOP]</span></td>
												<td><input type='text' class='input edit_name' value='$data[name]' /></td>
												<td><span>$siso</span></td>
												<td><input type='text' class='input edit_time' value='".get_thi_daihoc($data["ID_LOP"])."' /></td>
												<td>
													<input type='submit' class='submit edit_ok' data-lopID='$data[ID_LOP]' value='Sửa' />";
                                                if($data["ID_LOP"]!=3) {
                                                    echo "<input type='submit' class='submit delete' data-lopID='$data[ID_LOP]' value='Xóa' />";
                                                }
												echo"</td>
											</tr>";
											$dem++;
										}
									?>
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