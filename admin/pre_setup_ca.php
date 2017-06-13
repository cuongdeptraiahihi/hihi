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
        
        <title>CÁC CA HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			/*$("#lich-su-doi-ca").click(function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity", "0.3");
				$.ajax({
					async: true,
					data: "action=lichsu",
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-ca/",
					success: function(result) {
						alert(result);
						$("#BODY").css("opacity", "1");
						$("#popup-loading").fadeOut("fast");
					}
				});
			});*/
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
                	<h2>CÁC CA HỌC</h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                            	<tr>
                                    <td style="width:33%;"><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-ca-all/<?php echo $_SESSION["lmID"]; ?>/'" value="Đổi ca hàng loạt" /></td>
                                    <td style="width:33%;"><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-khoa-ca/<?php echo $_SESSION["lmID"]; ?>/'" value="Mở khóa ca" /></td>
                                    <td style="width:33%;"><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/dia-diem/'" value="Địa điểm" /></td>
                                </tr>
                            </table>
                        </div>
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