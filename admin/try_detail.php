<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["mon"]) && is_numeric($_GET["mon"]) && isset($_GET["lop"]) && is_numeric($_GET["lop"]) && isset($_GET["chuyende"]) && is_numeric($_GET["chuyende"])) {
		$monID=$_GET["mon"];
		$lopID=$_GET["lop"];
		$cdID=$_GET["chuyende"];
	} else {
		$monID=0;
		$lopID=0;
		$cdID=0;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>UNLOCK VIDEO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN #main-mid .status form table tr td span i {font-size:1.125em;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#MAIN #main-mid .table").delegate("tr td.con-action > span i.fa-check-square-o", "click", function() {
				video = $(this);
				videoID = video.attr("data-videoID");
				hsID = video.attr("data-hsID");
				if(videoID!=0 && hsID!=0 && $.isNumeric(videoID) && $.isNumeric(hsID)) {
					$.ajax({
						async: true,
						data: "videoID=" + videoID + "&hsID=" + hsID + "&action=off",
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							if(result) {
								video.attr("class","fa fa-square-o");
							}
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
				}
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.con-action > span i.fa-square-o", "click", function() {
				video = $(this);
				videoID = video.attr("data-videoID");
				hsID = video.attr("data-hsID");
				if(videoID!=0 && hsID!=0 && $.isNumeric(videoID) && $.isNumeric(hsID)) {
					$.ajax({
						async: true,
						data: "videoID=" + videoID + "&hsID=" + hsID + "&action=on",
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							if(result) {
								video.attr("class","fa fa-check-square-o");
							}
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
				}
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.dad-action > span i.fa-square-o", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				video = $(this);
				videoID = video.attr("data-videoID");
				lopID = <?php echo $lopID;?>;
				monID = <?php echo $monID;?>;
				if(videoID!=0 && monID!=0 && $.isNumeric(videoID) && $.isNumeric(monID)) {
					$.ajax({
						async: true,
						data: "videoID_all=" + videoID + "&monID=" + monID + "&lopID=" + lopID + "&action=on",
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							video.attr("class","fa fa-check-square-o");
							$("#MAIN #main-mid .table tr td.videoID_"+videoID+" span i").attr("class","fa fa-check-square-o");
							$("#BODY").css("opacity","1");
							$("#popup-loading").fadeOut("fast");
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
				}
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.dad-action > span i.fa-check-square-o", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				video = $(this);
				videoID = video.attr("data-videoID");
				lopID = <?php echo $lopID;?>;
				monID = <?php echo $monID;?>;
				if(videoID!=0 && monID!=0 && $.isNumeric(videoID) && $.isNumeric(monID)) {
					$.ajax({
						async: true,
						data: "videoID_all=" + videoID + "&monID=" + monID + "&lopID=" + lopID + "&action=off",
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							video.attr("class","fa fa-square-o");
							$("#MAIN #main-mid .table tr td.videoID_"+videoID+" span i").attr("class","fa fa-square-o");
							$("#BODY").css("opacity","1");
							$("#popup-loading").fadeOut("fast");
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
				}
			});
			
			$("#MAIN #main-mid .table tr td.dad-action").each(function(index, element) {
                unlock = $("#unlock_"+index).val();
				if(unlock=="all") {
					$(element).find("span i").attr("class","fa fa-check-square-o");
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
                	<h2>CHUYÊN ĐỀ "<?php echo get_chuyende($cdID);?>"</h2>
                	<div>
                    	<div class="status" style="width:auto;overflow-x:scroll;">
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                    	<th style="max-width:160px;min-width:160px;"><span></span></th>
                                    <?php
										$videoID=array();
										$result0=get_all_video_cd($cdID);
										while($data0=mysqli_fetch_assoc($result0)) {
									?>	
                                        <th style="max-width:200px;min-width:200px;"><span><?php echo $data0["title"]?></span><br /><span class="dateup">(<?php echo format_dateup($data0["dateup"]); ?>)</span></th>
                                   	<?php
											$videoID[]=$data0["ID_VIDEO"];
										}
									?>
                                    </tr>
                                    <tr style="background:#D1DBBD">
                                    	<td><span>Tất cả</span></td>
                                        <?php
											$open_arr=array();
											for($i=0;$i<count($videoID);$i++) {
												echo"<td class='dad-action'><span><i class='fa fa-square-o' data-videoID='$videoID[$i]'></i></span></td>";
												$open_arr[$videoID[$i]]=0;
											}
										?>
                                    </tr>
                                    <?php
										$dem=0;
										$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' WHERE hocsinh.lop='$lopID' ORDER BY hocsinh.cmt ASC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>$data[fullname]<br /><span>($data[cmt])</span></span></td>";
											for($i=0;$i<count($videoID);$i++) {
												echo"<td class='con-action videoID_$videoID[$i]'><span>";
												if(check_video_base_hs($data["ID_HS"], $videoID[$i])) {
													echo"<i class='fa fa-check-square-o' data-videoID='$videoID[$i]' data-hsID='$data[ID_HS]'></i>";
													$open_arr[$videoID[$i]]++;
												} else {
													echo"<i class='fa fa-square-o' data-videoID='$videoID[$i]' data-hsID='$data[ID_HS]'></i>";
												}
												echo"</span></td>";
											}
											echo"</tr>";
											$dem++;
										}
									?>
                                </table>
                                
                                <?php
									for($i=0;$i<count($videoID);$i++) {
										if($open_arr[$videoID[$i]]==$dem) {
											echo"<input type='hidden' value='all' id='unlock_$i' />";
										} else {
											echo"<input type='hidden' value='none' id='unlock_$i' />";
										}
									}
								?>
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