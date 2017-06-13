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
        
        <title>KHÓA THÁCH ĐẤU, NGÔI SAO</title>
        
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
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				$("#MAIN #main-mid .status table tr td span.fa-toggle-on").click(function() {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.1");
					oID = $(this).attr("data-oID");
					if(oID!=0 && $.isNumeric(oID)) {
						$.ajax({
							url: "http://localhost/www/TDUONG/thaygiao/xuly-sua-chua/",
							async: true,
							data: "khoa0=" + oID,
							type: "post",
							success: function(result) {
								location.reload();
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
					}
				});
				
				$("#MAIN #main-mid .status table tr td span.fa-toggle-off").click(function() {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.1");
					oID = $(this).attr("data-oID");
					if(oID!=0 && $.isNumeric(oID)) {
						$.ajax({
							url: "http://localhost/www/TDUONG/thaygiao/xuly-sua-chua/",
							async: true,
							data: "khoa1=" + oID,
							type: "post",
							success: function(result) {
								location.reload();
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
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
                	<h2>Khóa thách đấu, ngôi sao hy vọng</h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                            	<tr style="background:#3E606F;">
                                	<th style="width:15%;"><span>STT</span></th>
                                    <th style="width:30%;"><span>Môn</span></th>
                                    <th><span>Trạng thái</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <?php
									$dem=0;$check=true;
									$result=get_all_lop_mon();
									while($data=mysqli_fetch_assoc($result)) {
                                        $result2=get_khoa($data["ID_LM"]);
                                        if(mysqli_num_rows($result2)==0) {
                                            fix_khoa($data["ID_LM"]);
                                            $check=false;
                                        }
                                        $data2=mysqli_fetch_assoc($result2);
                                        if($dem%2!=0) {
                                            echo"<tr style='background:#D1DBBD'>";
                                        } else {
                                            echo"<tr>";
                                        }
                                            echo"<td><span>".($dem+1)."</span></td>
                                            <td><span>$data[name]</span></td>";
                                        if($data2["content"]==1) {
                                            echo"<td><span style='font-weight:600;'>Đang khóa</span></td>
                                            <td><span class='fa fa-toggle-on' style='cursor:pointer' data-oID='$data2[ID_O]' /></td>";
                                        } else {
                                            echo"<td><span>Đang mở</span></td>
                                            <td><span class='fa fa-toggle-off' style='cursor:pointer' data-oID='$data2[ID_O]' /></td>";
                                        }
                                        echo"</tr>";
                                        $dem++;
									}
									if(!$check) {
										header("location:http://localhost/www/TDUONG/thaygiao/khoa/");
										exit();
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