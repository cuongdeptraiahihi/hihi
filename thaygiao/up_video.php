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
        
        <title>UP VIDEO</title>
        
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
			$(".popup").click(function() {
				$(this).fadeOut("fast");
			});
			
			$("#mon").change(function() {
				monID = $(this).val();
				$.ajax({
					async: true,
					data: "monID3=" + monID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
					success: function(result) {
						$("#chuyende").html(result);
					}
				});
			});
			
			$("#up").click(function() {
				monID = $("#mon").val();
				cdID = $("#chuyende").val();
				title = $("#title").val();
				price = $("#price").val();
				video = $("#video").val();
				if($.isNumeric(monID) && $.isNumeric(cdID) && monID!=0 && cdID!=0 && title!="" && $.isNumeric(price) && video!="") {
					return true;
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
					return false;
				}
			});
			
			$("#mon, #chuyende").change(function() {
				monID = $("#mon").val();
				if(monID==0) {
					$("#mon").addClass("new-change");
				} else {
					$("#mon").removeClass("new-change");
				}
				cdID = $("#chuyende").val();
				if(cdID==0) {
					$("#chuyende").addClass("new-change");
				} else {
					$("#chuyende").removeClass("new-change");
				}
			});
			
			$("#select-video").change(function() {
				if($("#add-link").is(":selected")) {
					$("#video-mp4").hide();
					$("#video-link").fadeIn("fast");
				} else {
					$("#video-link").hide();
					$("#video-mp4").fadeIn("fast");
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			$error = "";
			$title=$mon=$video=$chuyende=$price=$video_on=NULL;
			if(isset($_POST["up"])) {
			 	if(isset($_POST["title"])) {
					$title=$_POST["title"];
				}
				
				if(isset($_POST["mon"])) {
					$mon=$_POST["mon"];
				}
				
				if(isset($_POST["select-video"])) {
					$video_on=$_POST["select-video"];
					if($video_on==1) {
						if(isset($_POST["video-link"])) {
							$video=$_POST["video-link"];
						}
					}
					if($video_on==2) {
						if($_FILES["video-mp4"]["error"]>0) {
						} else {
							$video=$_FILES["video-mp4"]["name"];
						}
					}
				}
				
				if(isset($_POST["chuyende"])) {
					$chuyende=$_POST["chuyende"];
				}
				
				if(isset($_POST["price"])) {
					if($_POST["price"]!=0 && is_numeric($_POST["price"])) {
						$price=abs($_POST["price"]);
					} else {
						$price=0;
					}
				}
				
				if($title && $mon && $video && $chuyende && is_numeric($price) && $video_on) {
					
					echo"<div class='popup' id='popup-loading' style='display:block'>
						<p><img src='http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif' /></p>
					</div>";
					if($video_on==2) {
						if(!is_dir("https://localhost/www/TDUONG/video/$chuyende")){
							mkdir("https://localhost/www/TDUONG/video/$chuyende");
						}
						move_uploaded_file($_FILES["video"]["tmp_name"],"https://localhost/www/TDUONG/video/$chuyende/".$_FILES["video"]["name"]);
					}
					up_video($title, $mon, $video, $chuyende, $price);
					
					header("location:http://localhost/www/TDUONG/thaygiao/videos/");
					exit();
				} else {
					$error="<div class='popup' style='display:block;width:30%;left:35%;'>
						<p>Bạn vui lòng nhập đủ các trường!</p>
					</div>";
				}
			}
		?>
        
        <?php echo $error; ?>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>UP VIDEO</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/up-video/" method="post" enctype="multipart/form-data">
                            	<table class="table">
                                	<tr>
                                    	<td style="width:25%:"><span>Tiêu đề</span></td>
                                        <td colspan="3"><input name="title" id="title" class="input" type="text" autocomplete="off" /></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Môn</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="mon" name="mon">
                                            	<option value="0">Chọn môn</option>
                                            	<?php
													$result=get_all_mon_admin();
													for($i=0;$i<count($result);$i++) {
														echo"<option value='".$result[$i]["monID"]."'>Môn ".$result[$i]["name"]."</option>";
													}
												?>
                                            </select>
                                        </td>
                                        <td><span>Chuyên đề</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="chuyende" name="chuyende">
                                            	<option value="0">Chọn chuyên đề</option>
                                        	</select>
                                        </td>
                                   	</tr>
                                    <tr>
                                    	<td><span>Giá xem (VNĐ)</span></td>
                                        <td><input name="price" id="price" class="input" type="number" min="0" placeholder="50000" /></td>
                                        <td colspan="2"></td>
                                   	</tr>
                                    <tr>
                                        <td><span>Video</span></td>
                                        <td>
                                        	<select class="input" style="height:auto;" id="select-video" name="select-video">
                                            	<option id="add-link" value="1">Up link youtube</option>
                                                <option id="add-mp4" value="2">Up video trực tiếp</option>
                                            </select>
                                        </td>
                                        <td colspan="2"><input type="text" name="video-link" class="input" id="video-link" /><input type="file" name="video-mp4" class="input" id="video-mp4" style="display:none;" /></td>
                                   	</tr>
                                    <tr>
                                        <td colspan="4"><button class="submit" style="width:50%;font-size:1.375em;" id="up" name="up">Up video</button></td>
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