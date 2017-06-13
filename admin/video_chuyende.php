<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
	if(isset($_GET["chuyende"]) && is_numeric($_GET["chuyende"])) {
		$cdID=$_GET["chuyende"];
	} else {
		$cdID=0;
	}
	$result0=get_one_chuyende($cdID);
	$data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>VIDEO CHUYÊN ĐỀ <?php echo mb_strtoupper($data0["title"],"UTF-8");?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
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
                	<h2>DANH SÁCH CÁC VIDEO THUỘC CHUYÊN ĐỀ <span style="font-weight:600"><?php echo $data0["title"]; ?></span></h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                    	<td colspan="5"></td>
                                        <td><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/admin/up-video/'" value='Up video mới' /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                    	<th style="width:10%;"><span>STT</span></th>
                                        <th style="width:25%;"><span>Tên video</span></th>
                                        <th style="width:25%;"><span>Nội dung</span></th>
                                        <th style="width:10%;"><span>Giá</span></th>
                                        <th style="width:15%;"><span>Ngày up</span></th>
                                        <th style="width:15%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_video_same_cdadmin($cdID);
										while($data=mysqli_fetch_assoc($result)) {
											echo"<tr>
												<td><span>".($dem+1)."</span></td>
												<td><span>$data[title]</span></td>
												<td><span>";
												if(strpos($data["content"],"youtu")===false) {
													echo"<video width='100%' controls>
														<source src='https://localhost/www/TDUONG/video/$cdID/$data[content]' type='video/mp4' />
														Trình duyệt đã cũ, hãy nâng cấp!
													</video>";
												} else {
													echo"<iframe width='100%' height='' src='$data[content]' frameborder='0' allowfullscreen></iframe>";
												}
												echo"</span></td>
												<td><span>".format_price($data["price"])."</span></td>
												<td><span>".format_dateup($data["dateup"])."</span></td>
												<td>";
												?>
													<input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/video/<?php echo $data["ID_VIDEO"];?>/'" value="Xem" />
                                                    <input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/sua-video/<?php echo $data["ID_VIDEO"];?>/'" value="Sửa" />
												<?php
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