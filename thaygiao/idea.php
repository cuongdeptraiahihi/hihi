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
        
        <title>IDEA</title>
        
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
                	<h2>Ý tưởng</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                        <td colspan="5"><input type="submit" class="submit add-idea" value="Thêm ý tưởng" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th class="hidden" style="width:5%;"><span>STT</span></th>
                                        <th style="width:15%;"><span>Tiêu đề</span></th>
                                        <th><span>Nội dung</span></th>
                                        <th style="width:10%;"><span>Tên</span></th>
                                        <th style="width:10%;"><span></span></th>
                                    </tr>
                                    <?php
										$result=get_options_all("idea",50);
										$dem=0;
										while($data=mysqli_fetch_assoc($result)) {
										    echo"<tr>
                                                <td class='hidden'><span>".($dem+1)."</span></td>
                                                <td><span>$data[note]</span></td>
												<td style='text-align:left;padding-left:15px;'><span>".nl2br($data["content"])."</span></td>
												<td><span>$data[note2]</span></td>
												<td><span><i class='fa fa-close' data-oID='$data[ID_O]'></i></span></td>
                                            </tr>";
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