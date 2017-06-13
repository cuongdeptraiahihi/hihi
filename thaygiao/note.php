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
        
        <title>NOTE</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            #FIX {display: none;}
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
                	<h2>NOTE</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                    	<td colspan="4"></td>
                                        <td colspan="2"><input type="submit" class="submit add-note" value="Thêm note" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:5%;"><span>STT</span></th>
                                        <th style="width:20%;"><span>Tiêu đề</span></th>
                                        <th><span>Nội dung</span></th>
                                        <th style="width:10%;"><span>Tên</span></th>
                                        <th style="width:15%;"><span>Thời gian</span></th>
                                        <th style="width:10%;"><span></span></th>
                                    </tr>
                                    <?php
										$result=get_all_note();
										$dem=0;
										while($data=mysqli_fetch_assoc($result)) {
											if($data["status"]==1) {
												echo"<tr style='opacity:0.3;'>";
											} else {
												echo"<tr>";
											}
												echo"<td><span>".($dem+1)."</span></td>
												<td><span>$data[title]</span></td>
												<td style='text-align:left;padding-left:15px;'><span>".nl2br($data["content"])."</span></td>
												<td><span>$data[name]</span></td>
												<td><span>".format_datetime($data["datetime"])."</span></td>";
											if($data["status"]==1) {
												echo"<td><span><i class='fa fa-check-square-o' data-nID='$data[ID_N]'></i><i class='fa fa-trash' style='margin-left:10px;' data-nID='$data[ID_N]'></i></span></td>";
											} else {
												echo"<td><span><i class='fa fa-square-o' data-nID='$data[ID_N]'></i><i class='fa fa-trash' style='margin-left:10px;' data-nID='$data[ID_N]'></i></span></td>";
											}
											echo"</tr>";
											$dem++;
										}
										if($dem==0) {
											echo"<tr><td colspan='6'><span>Bạn đã hoàn thành hết các công việc!</span></td></tr>";
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