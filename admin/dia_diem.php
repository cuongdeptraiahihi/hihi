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
        
        <title>ĐỊA ĐIỂM</title>
        
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
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
				$("#add-diadiem").click(function() {
					$("#name-add").val("");
					$("#mota-add").val("");
					$("#popup-add").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
				});
				
				$(".popup-close").click(function() {
					$("#popup-add").fadeOut("fast");
					$("#BODY").css("opacity","1");
				});
				
				$("#popup-ok").click(function() {
					name = $("#name-add").val();
					mota = $("#mota-add").val();
					if(name!="" && mota!="") {
						$("#popup-add").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "name=" + name + "&mota=" + mota,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-diadiem/",
							success: function(result) {
								location.reload();
								$("#popup-add").fadeOut("fast");
								$("#BODY").css("opacity","1");
								
							}
						});
					} else {
						alert("Vui lòng nhập đầy đủ thông tin!");
					}
				});
				
				$("#MAIN #main-mid .status table tr td input.delete").click(function() {
					del_tr = $(this).closest("tr");
					ddID = $(this).attr("data-ddID");
					if(confirm("Bạn có chắc chắn xóa địa điểm này?")) {
						if($.isNumeric(ddID)) {
							$("#popup-loading").fadeIn("fast");
							$("#BODY").css("opacity","0.3");
							$.ajax({
								async: true,
								data: "ddID=" + ddID,
								type: "post",
								url: "http://localhost/www/TDUONG/admin/xuly-diadiem/",
								success: function(result) {
									del_tr.fadeOut("fast");
									$("#popup-loading").fadeOut("fast");
									$("#BODY").css("opacity","1");
								}
							});
						}
					}
				});
				
				$("#MAIN #main-mid .status table tr td input.edit").click(function() {
					del_tr = $(this).closest("tr");
					del_tr.css("opacity","0.3");
					ddID = $(this).attr("data-ddID");
					name = del_tr.find("td input.name").val();
					mota = del_tr.find("td input.mota").val();
					if($.isNumeric(ddID) && name!="" && mota!="") {
						$.ajax({
							async: true,
							data: "ddID1=" + ddID + "&name1=" + name + "&mota1=" + mota,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-diadiem/",
							success: function(result) {
								del_tr.css("opacity","1");
							}
						});
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
        
        <div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm địa điểm</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<input id="name-add" class="input" autocomplete="off" placeholder="Tên địa điểm" />
                <input id="mota-add" class="input" style="margin-top:10px;" autocomplete="off" placeholder="Mô tả địa điểm, địa chỉ,.." />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                <div id="main-mid">
                	<h2>Địa điểm học</h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                            	<tr>
                                	<td colspan="4"></td>
                                    <td><input type="submit" id="add-diadiem" class="submit" value="Thêm địa điểm" /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>STT</span></th>
                                    <th style="width:25%;"><span>Địa điểm</span></th>
                                    <th style="width:35%;"><span>Mô tả</span></th>
                                    <th style="width:10%;"><span>Số ca</span></th>
                                    <th style="width:20%;"><span></span></th>
                                </tr>
                                <?php
                                    $dem=0;
                                    $result=get_all_dia_diem();
                                    while($data=mysqli_fetch_assoc($result)) {
                                        if($dem%2!=0) {
                                            echo"<tr style='background:#D1DBBD'>";
                                        } else {
                                            echo"<tr>";
                                        }
                                        echo"<td><span>".($dem+1)."</span></td>
                                            <td><input type='text' class='input name' value='$data[name]' /></td>
                                            <td><input type='text' class='input mota' value='$data[mota]' /></td>
                                            <td><span>".count_ca_dia_diem($data["ID_DD"])."</span></td>
                                            <td>
                                                <input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/admin/dia-diem-ca/$data[ID_DD]/'\" value='Xem' />
												<input type='submit' class='submit edit' data-ddID='$data[ID_DD]' value='Sửa' />
												<input type='submit' class='submit delete' data-ddID='$data[ID_DD]' value='Xóa' />
                                            </td>
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