<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>       
        
        <title>ĐĂNG KÝ VÀO GROUP</title>
        
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
			$("#MAIN #main-mid .status table tr td input.done").click(function() {
				if(confirm("Bạn có chắc chắn hoàn thành yêu cầu này? Yêu cầu này sẽ đc xóa và link facebook của học sinh sẽ được cập nhật!")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					oID = $(this).attr("data-oID");
					del_tr = $(this).closest("tr");
					$.ajax({
						async: true,
						data: "oID2=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-facebook/",
						success: function(result) {
							del_tr.fadeOut("fast");
							$(".popup").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				}
			});
			
			$("#MAIN #main-mid .status table tr td input.delete").click(function() {
				if(confirm("Bạn có chắc chắn xóa yêu cầu này? Hành động không thể hoàn tác!")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					oID = $(this).attr("data-oID");
					del_tr = $(this).closest("tr");
					$.ajax({
						async: true,
						data: "oID3=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-facebook/",
						success: function(result) {
							del_tr.fadeOut("fast");
							$(".popup").fadeOut("fast");
							$("#BODY").css("opacity","1");
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
                	<h2>Danh sách học sinh đăng ký vào group facebook <span style="font-weight: 600;"><?php echo "môn $lop_mon_name"; ?></span></h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;" class="hidden"><span>STT</span></th>
                                    <th style="width:25%;"><span>Tên</span></th>
                                    <th style="width:15%;"><span>CMT</span></th>
                                    <th style="width:15%;"><span>Link</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <?php
                                    $dem=0;
                                    $query="SELECT o.*,h.cmt FROM options AS o 
                                    INNER JOIN hocsinh AS h ON h.ID_HS=o.note2 AND h.cmt LIKE '".substr($lop_mon_name,strlen($lop_mon_name)-2,2)."-%'
                                    WHERE o.type='dangky-face' ORDER BY o.ID_O DESC";
                                    $result=mysqli_query($db,$query);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td class='hidden'><span>".($dem+1)."</span></td>
                                            <td><span>$data[note]</span></td>
                                            <td><span>$data[cmt]</span></td>
                                            <td><a href='$data[content]' target='_blank'>Link</a></td>
                                            <td>
                                                <input type='submit' value='Xong' class='submit done' data-oID='$data[ID_O]' />
                                                <input type='submit' value='Xóa' class='submit delete' data-oID='$data[ID_O]' />
                                            </td>
                                        </tr>";
                                        $dem++;
                                    }
                                ?>
                                <tr style="background:#3E606F;">
                                    <th colspan="5"><span>Cách thức cũ</span></th>
                                </tr>
                                <?php
									$dem=0;
									$result=get_all_dangky_face($lmID);
									while($data=mysqli_fetch_assoc($result)) {
										echo"<tr>
											<td class='hidden'><span>".($dem+1)."</span></td>
											<td><span>$data[fullname]</span></td>
											<td><span>$data[cmt]</span></td>
											<td><a href='$data[content]' target='_blank'>Link</a></td>
											<td>
												<input type='submit' value='Xong' class='submit done' data-oID='$data[ID_O]' />
												<input type='submit' value='Xóa' class='submit delete' data-oID='$data[ID_O]' />
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