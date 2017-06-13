<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
	if(is_numeric($_GET["video"])) {
		$videoID=$_GET["video"];
	} else {
		$videoID=0;
	}
	$result=get_video_detail($videoID);
	$cdID=get_chuyende_id($videoID);
	$data=mysqli_fetch_assoc($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>VIDEO</title>
        
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
		$(document).ready(function() {
			$("#MAIN #main-left ul li a#delete_video").click(function() {
				if(confirm("Bạn có chắc chắn không?")) {
					videoID = $(this).attr("data-videoID");
					$.ajax({
						async: true,
						data: "videoID=" + videoID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-video/",
						success: function(result) {
							alert("Video đã bị xóa!");
						}
					});
				}
			});
			
			var my_width = $(".video-main > iframe").width();
			$(".video-main > iframe").attr("height",(my_width*9/16)+"px");
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	<div id="main-left">
                
                	<?php require_once("include/LEFT.php"); ?>
                    
                    <div>
                    	<h3>Menu</h3>
                        <ul>
                        	<li><a href="javascript:void(0)"><i class="fa fa-calendar"></i><?php echo format_dateup($data["dateup"]);?></a></li>
                            <li><a href="javascript:void(0)"><span class="video-price2"><?php if($data["price"]!=0){echo format_price($data["price"]);}else{echo"FREE";} ?></span></a></li>
                        	<li class="action"><a href="http://localhost/www/TDUONG/thaygiao/sua-video/<?php echo $videoID;?>/"><i class="fa fa-edit"></i>Sửa video</a></li>
                            <li class="action"><a href="javascript:void(0)" id="delete_video" data-videoID="<?php echo $videoID;?>"><i class="fa fa-trash"></i>Xóa video</a></li>
                        </ul>
                    </div>
                </div>
                
                <div id="main-mid">
                	<h2><?php echo $data["title"];?></h2>
                	<div>
                    	<div class="status">
                        	<nav class="video-main">
                            <?php
                            if(strpos($data["content"],"youtu")===false) {
                            	echo"<video width='100%' controls>
                                	<source src='https://localhost/www/TDUONG/video/$cdID/$data[content]' type='video/mp4' />
                                    Trình duyệt đã cũ, hãy nâng cấp!
                                </video>";
                          	} else {
                            	echo"<iframe width='100%' height='' src='$data[content]' frameborder='0' allowfullscreen></iframe>";
                            }
							?>
                            </nav>
                        </div>
                        
                        <div class="status list-more">
                        	<h4>Cùng chuyên đề</h4>
                            <ul>
                            <?php
								$result0=get_video_same_cdadmin($cdID);
								while($data0=mysqli_fetch_assoc($result0)) {
							?>
                            	<li><a href="http://localhost/www/TDUONG/thaygiao/video/<?php echo $data0["ID_VIDEO"]; ?>/">+ <?php echo $data0["title"]; ?><?php if($data0["ID_VIDEO"]==$videoID) {echo"<span>Đang xem</span>";} ?></a></li>
							<?php	
                                }
							?>
                            </ul>
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