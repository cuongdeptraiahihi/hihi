<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
	if(is_numeric($_GET["videoID"])) {
		$videoID=$_GET["videoID"];
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
        
        <title>EDIT VIDEO</title>
        
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
		$(document).ready(function(){$(".popup").click(function(){$(this).fadeOut("fast")}),$("#mon").change(function(){monID=$(this).val(),$.ajax({async: true,data:"monID3="+monID,type:"post",url:"http://localhost/www/TDUONG/admin/xuly-chuyende/",success:function(e){$("#chuyende").html(e)}})}),$("#mon, #chuyende").change(function(){monID=$("#mon").val(),0==monID?$("#mon").addClass("new-change"):$("#mon").removeClass("new-change"),cdID=$("#chuyende").val(),0==cdID?$("#chuyende").addClass("new-change"):$("#chuyende").removeClass("new-change")}),$("#MAIN #main-left ul li a#delete_video").click(function(){confirm("Bạn có chắc chắn không?")&&(videoID=$(this).attr("data-videoID"),$.ajax({async: true,data:"videoID="+videoID,type:"post",url:"http://localhost/www/TDUONG/admin/xuly-video/",success:function(e){alert("Video đã bị xóa!")}}))}),$("#select-video").change(function(){$("#add-link").is(":selected")?($("#video-mp4").hide(),$("#video-link").fadeIn("fast")):($("#video-link").hide(),$("#video-mp4").fadeIn("fast"))});var e=$(".status .table tr td iframe").width();$(".status .table tr td iframe").attr("height",9*e/16+"px")});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			$error = "";
			$title=$video=$mon=$chuyende=$price=$video_on=NULL;
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
						} else {
							$video="none";
						}
					}
					if($video_on==2) {
						if($_FILES["video-mp4"]["error"]>0) {
							$video="none";
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
				
				if($title && $mon && $video && $chuyende && is_numeric($price) && $video_on && $videoID) {
					
					echo"<div class='popup' id='popup-loading' style='display:block'>
						<p><img src='http://localhost/www/TDUONG/admin/images/ajax-loader.gif' /></p>
					</div>";
					if($video != "none" && $video_on==2) {
						if(!is_dir("https://localhost/www/TDUONG/video/$chuyende")){
							mkdir("https://localhost/www/TDUONG/video/$chuyende");
						}
						move_uploaded_file($_FILES["video"]["tmp_name"],"https://localhost/www/TDUONG/video/$chuyende/".$_FILES["video"]["name"]);
					}
					edit_video($title, $mon, $video, $chuyende, $price, $videoID);
					
					header("location:http://localhost/www/TDUONG/admin/sua-video/$videoID/");
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
            
            	<div id="main-left">
                
                	<?php require_once("include/LEFT.php"); ?>
                    
                    <div>
                    	<h3>Menu</h3>
                        <ul>
                        	<li><a href="javascript:void(0)"><i class="fa fa-calendar"></i><?php echo format_dateup($data["dateup"]);?></a></li>
                            <li class="action"><a href="javascript:void(0)" id="delete_video" data-videoID="<?php echo $videoID;?>"><i class="fa fa-trash"></i>Xóa video</a></li>
                        </ul>
                    </div>
                </div>
                
                <div id="main-mid">
                	<h2>EDIT VIDEO</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/sua-video/<?php echo $videoID;?>/" method="post" enctype="multipart/form-data">
                            	<table class="table">
                                	<tr>
                                    	<td style="width:25%:"><span>Tiêu đề</span></td>
                                        <td colspan="3"><input name="title" id="title" class="input" type="text" value="<?php echo $data["title"];?>" /></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Môn</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="mon" name="mon">
                                            	<option value="0">Chọn môn</option>
                                            	<?php
													$result=get_all_mon_admin();
													for($i=0;$i<count($result);$i++) {
														echo"<option value='".$result[$i]["monID"]."'";if($data["ID_MON"]==$result[$i]["monID"]) {echo"selected='selected'";}echo">Môn ".$result[$i]["name"]."</option>";
													}
												?>
                                            </select>
                                        </td>
                                        <td><span>Chuyên đề</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="chuyende" name="chuyende">
                                            	<option value="0">Chọn chuyên đề</option>
                                             	<?php
													$result2=get_all_chuyende_all($data["ID_MON"]);
													while($data2=mysqli_fetch_assoc($result2)) {
														echo"<option value='$data2[ID_CD]'";if($data2["ID_CD"]==$data["ID_CD"]) {echo"selected='selected'";}echo">$data2[name] - $data2[maso] - $data2[title]</option>";
													}
												?>
                                        	</select>
                                        </td>
                                   	</tr>
                                    <tr>
                                    <?php
										$you=true;
										if(strpos($data["content"],"youtu")===false) {
											$you=false;
										}
									?>
                                    	<td><span>Video cũ</span></td>
                                    	<td colspan="3" style="width:75%">
                                        <?php
											if(!$you) {
												echo"<video width='100%' controls>
													<source src='https://localhost/www/TDUONG/video/$data[ID_CD]/$data[content]' type='video/mp4' />
													Trình duyệt đã cũ, hãy nâng cấp!
												</video>";
											} else {
												echo"<iframe width='100%' height='' src='$data[content]' frameborder='0' allowfullscreen></iframe>";
											}
										?>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span>Giá xem (VNĐ)</span></td>
                                        <td><input name="price" id="price" class="input" type="number" min="0" placeholder="50000" value="<?php echo $data["price"];?>" /></td>
                                        <td colspan="2"></td>
                                   	</tr>
                                    <tr>
                                    	<td><span>Video</span></td>
                                        <td>
                                        	<select class="input" style="height:auto;" id="select-video" name="select-video">
                                            	<option id="add-link" value="1" <?php if($you){echo"selected='selected'";} ?>>Up link youtube</option>
                                                <option id="add-mp4" value="2" <?php if(!$you){echo"selected='selected'";} ?>>Up video trực tiếp</option>
                                            </select>
                                        </td>
                                        <td colspan="2"><input type="text" name="video-link" class="input" id="video-link" /><input type="file" name="video-mp4" class="input" id="video-mp4" style="display:none;" /></td>
                                   	</tr>
                                    <tr>
                                        <td colspan="4"><button class="submit" style="width:50%;font-size:1.375em;" name="up">Edit video</button></td>
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