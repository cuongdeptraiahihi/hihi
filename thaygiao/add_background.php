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
        
        <title>THÊM BACKGROUND</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function(){$(".popup").click(function(){$(this).fadeOut("fast")}),$("#up").click(function(){return image=$("#image").val(),""!=image?!0:(alert("Vui lòng chọn ảnh để up lên!"),!1)})});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			$error = "";
			$image=NULL;
			if(isset($_POST["up"])) {
				if($_FILES["image"]["error"]>0) {
				} else {
					$image=$_FILES["image"]["name"];
				}
				
				if($image) {
					
					echo"<div class='popup' id='popup-loading' style='display:block'>
						<p><img src='images/ajax-loader.gif' /></p>
					</div>";
					move_uploaded_file($_FILES["image"]["tmp_name"],"../images/".$_FILES["image"]["name"]);	
					up_background($image);
					
					header("location:http://localhost/www/TDUONG/thaygiao/background/");
					exit();
				} else {
					$error="<div class='popup' style='display:block;width:30%;left:35%;'>
						<p>Bạn vui lòng chọn ảnh để up lên!</p>
					</div>";
				}
			}
		?>
        
        <?php echo $error; ?>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>THÊM BACKGROUND</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/add-background/" method="post" enctype="multipart/form-data">
                            	<table class="table">
                                	<tr>
                                    	<td><span>Up ảnh nền</span></td>
                                        <td><input type="file" name="image" class="input" id="image" /></td>
                                   	</tr>
                                    <tr>
                                        <td colspan="2"><button class="submit" style="width:50%;font-size:1.375em;" id="up" name="up">Up ảnh</button></td>
                                    </tr>
                                </table>
                            </form>
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