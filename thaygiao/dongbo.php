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
        
        <title>ĐỒNG BỘ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>Lịch sử đồng bộ</h2>
                	<div>
                    	<div class="status">	
                        	<form>
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:60%;"><span>Nội dung</span></th>
                                        <th style="width:25%;"><span>Thời gian</span></th>
                                    </tr>
                                    <?php
										if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
											$position=$_GET["begin"];
										} else {
											$position=0;
										}
										$dem=0;$display=30;
										$result=get_all_sync_sort($position, $display);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo ($dem+1);?></span></td>
                                        <td style="text-align:left;padding-left:10px;"><span><?php echo $data["note"];?></span></td>
                                        <td><span><?php echo $data["datetime"]; ?></span></td>
                                    </tr>
									<?php 
											$dem++;
										}
									?>
                                </table>
                            </form>
                        </div>
                        <?php
							$result2=get_all_sync();
							$sum=mysqli_num_rows($result2);
							$sum_page=ceil($sum/$display);
							if($sum_page>1) {
								$current=($position/$display)+1;
						?>
                        <div class="page-number">
                        	<ul>
                            <?php
								if($current!=1) {
									$prev=$position-$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/dong-bo/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/dong-bo/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/dong-bo/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/dong-bo/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
						?>
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