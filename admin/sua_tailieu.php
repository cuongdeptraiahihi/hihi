<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	$monID=$_SESSION["mon"];
	if(isset($_GET["tlID"]) && is_numeric($_GET["tlID"])) {
		$tlID=$_GET["tlID"];
	} else {
		$tlID=0;
	}
	$result0=get_one_tailieu($tlID);
	$data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SỬA TÀI LIỆU</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.con {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			var editor = CKEDITOR.replace('content');
			
			$(".popup").click(function() {
				$(this).fadeOut("fast");
			});
			
			$("#loai").change(function() {
				$(".con").hide();
				loai = $(this).find("option:selected").val();
				$("#MAIN #main-mid .status table tr#"+loai).fadeIn("fast");
			});
			
			$("#up").click(function() {
				title = $("#title").val();
				danhmuc = $("#danhmuc option:selected").val();
                if(danhmuc==0) {
                    danhmuc = $("#danhmuc2 option:selected").val();
                }
				loai = $("#loai option:selected").val();
				content = "";
				switch(loai) {
					case "baiviet":
						content = editor.getData();
						break;
					case "link":
						content = $("#con-link").val();
						break;
					case "video":
						content = $("#con-video").val();
						break;
					case "youtube":
						content = $("#con-you").val();
						break;
				}
				if(title!="" && $.isNumeric(danhmuc) && danhmuc!=0 && loai!="none" && content!="") {
					return true;
				} else {
					alert("Vui lòng nhập đầy đủ các trường và chính xác!");
					return false;
				}
			});
			
			var my_width = $(".status .table tr td iframe").width();
			$(".status .table tr td iframe").attr("height",(my_width*9/16)+"px");
		});
		</script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/admin/include/ckeditor/ckeditor.js"></script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			$error = "";
			$title=$intro=$content=$danhmuc=$where=$loai=NULL;
			$price=0;
			$pic="none";
			if(isset($_POST["up"])) {
			 	if(isset($_POST["title"])) {
					$title=$_POST["title"];
				}
				
				if(isset($_POST["intro"])) {
					$intro=$_POST["intro"];
				}

                if(isset($_POST["danhmuc"]) && $_POST["danhmuc"]!=0) {
                    $danhmuc=$_POST["danhmuc"];
                    $where="chuyende";
                }

                if(isset($_POST["danhmuc2"]) && $_POST["danhmuc2"]!=0) {
                    $danhmuc=$_POST["danhmuc2"];
                    $where="danhmuc";
                }
				
				if(isset($_POST["price"]) && $_POST["price"]>=0) {
					$price=$_POST["price"];
				}
				
				if($_FILES["pic"]["error"]>0) {
					$pic="none";
				} else {
					$pic=$_FILES["pic"]["name"];
				}
				
				if(isset($_POST["loai"]) && $_POST["loai"]!="none") {
					$loai=$_POST["loai"];
					switch($loai) {
						case "baiviet":
							if(isset($_POST["content"])) {
								$content=$_POST["content"];
							}
							break;
						case "link":
							if(isset($_POST["con-link"])) {
								$content=$_POST["con-link"];
							}
							break;
						case "video":
							if($_FILES["con-video"]["error"]>0) {
								$content="none";
							} else {
								$content=$_FILES["con-video"]["name"];
							}
							break;
						case "youtube":
							if(isset($_POST["con-you"])) {
								$content=$_POST["con-you"];
							}
							break;
					}
				}  
				
				if($title && $content && is_numeric($danhmuc) && $danhmuc!=0 && $loai && $pic && $where) {
					
					echo"<div class='popup' id='popup-loading' style='display:block;'>
						<p><img src='http://localhost/www/TDUONG/admin/images/ajax-loader.gif' /></p>
					</div>";
					if($pic!="none" && valid_image($pic)) {
						move_uploaded_file($_FILES["pic"]["tmp_name"],"../tailieu/".$_FILES["pic"]["name"]);
					} else {
						$pic=$data0["pic"];
					}
					if($loai=="video" && $content!="none") {
						if(!is_dir("../tailieu/$danhmuc")){
							mkdir("../tailieu/$danhmuc");
						}
						move_uploaded_file($_FILES["con-video"]["tmp_name"],"../tailieu/$danhmuc/".$_FILES["con-video"]["name"]);	
						edit_tailieu_short($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where, $tlID);
					} else if($loai=="baiviet") {
						edit_tailieu_full($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where, $tlID);
					} else {
						edit_tailieu_short($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where, $tlID);
					}

                    if($where=="chuyende") {
                        header("location:http://localhost/www/TDUONG/admin/tai-lieu-chuyen-de/$danhmuc/");
                        exit();
                    } else {
                        header("location:http://localhost/www/TDUONG/admin/tai-lieu-danh-muc/$danhmuc/");
                        exit();
                    }
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
                	<h2>SỬA TÀI LIỆU</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/sua-tai-lieu/<?php echo $tlID; ?>/" method="post" enctype="multipart/form-data">
                            	<table class="table">
                                	<tr>
                                    	<td><span>Tiêu đề *</span></td>
                                        <td colspan="3" style="width:75%;"><input name="title" id="title" class="input" type="text" value="<?php echo $data0["title"]; ?>" autocomplete="off" /></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Giới thiệu</span></td>
                                        <td colspan="3"><textarea name="intro" id="intro" style="height:50px;" class="input"><?php echo $data0["intro"]; ?></textarea></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Giá tiền</span></td>
                                        <td><input type="number" class="input" min="0" name="price" value="<?php echo $data0["price"]; ?>" placeholder="30000" id="price" /></td>
                                    	<td colspan="2"><span>Không cần nhập nếu FREE</span></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Ảnh đại diện</span></td>
                                        <td><input type="file" class="input" name="pic" id="pic" /></td>
                                    	<td><span>Ảnh vuông</span></td>
                                        <td><img src="https://localhost/www/TDUONG/tailieu/<?php echo $data0["pic"]; ?>" style="width:100%;" /></td>
                                    </tr>
                                    <tr>
                                    	<td style="width:25%;"><span>Chuyên đề *</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="danhmuc" name="danhmuc">
                                            	<option value="0">Chọn chuyên đề</option>
                                                <?php
													$result=get_all_chuyende_all($monID);
													while($data=mysqli_fetch_assoc($result)) {
														echo"<option value='$data[ID_CD]' ";if($data0["ID_DM"]==$data["ID_CD"]){echo"selected='selected'";}echo">$data[name] - $data[title]</option>";
													}
												?>
                                            </select>
                                            <span style="display: block;margin: 5px 0 5px 0;">Hoặc</span>
                                            <select class="input" style="height:auto;" id="danhmuc2" name="danhmuc2">
                                                <option value="0">Chọn danh mục ngoài chuyên đề</option>
                                                <?php
                                                $result=get_all_danhmuc($monID);
                                                while($data=mysqli_fetch_assoc($result)) {
                                                    echo"<option value='$data[ID_DM]' ";if($data0["ID_DM2"]==$data["ID_DM"]){echo"selected='selected'";}echo">$data[name] - $data[title]</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td style="width:25%;"><span>Loại *</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="loai" name="loai">
                                            	<option value="none">Chọn loại</option>
                                                <option value="baiviet" <?php if($data0["type"]=="baiviet"){echo"selected='selected'";} ?>>Bài viết</option>
                                                <option value="link" <?php if($data0["type"]=="link"){echo"selected='selected'";} ?>>Link ngoài</option>
                                                <option value="video" <?php if($data0["type"]=="video"){echo"selected='selected'";} ?>>Video trực tiếp</option>
                                                <option value="youtube" <?php if($data0["type"]=="youtube"){echo"selected='selected'";} ?>>Link youtube</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="baiviet" <?php if($data0["type"]=="baiviet"){echo"style='display:table-row'";} ?> class="con">
                                    	<td colspan="4"><textarea name="content" id="content" style="height:auto;"><?php echo $data0["full_con"]; ?></textarea></td>
                                    </tr>
                                    <tr id="link" <?php if($data0["type"]=="link"){echo"style='display:table-row'";} ?> class="con">
                                    	<td><span>Link *</span></td>
                                    	<td colspan="3"><input class="input" name="con-link" id="con-link" type="text" value="<?php echo $data0["short_con"]; ?>" placeholder="Link trang cần chia sẻ..." /></td>
                                    </tr>
                                    <tr id="video" <?php if($data0["type"]=="video"){echo"style='display:table-row'";} ?> class="con">
                                    	<td><span>Up video *</span></td>
                                        <td colspan="3"><input class="input" name="con-video" id="con-video" type="file" /></td>
                                    </tr>
                                    <tr id="youtube" <?php if($data0["type"]=="youtube"){echo"style='display:table-row'";} ?> class="con">
                                    	<td><span>Youtube *</span></td>
                                    	<td colspan="3"><input class="input" name="con-you" id="con-you" value="<?php echo $data0["short_con"]; ?>" type="text" placeholder="https://www.youtube.com/embed/KbPigceQhbI" /></td>
                                    </tr>
                                    <?php
										if($data0["type"]=="video") {
											echo"<tr>
												<td><span>Video cũ</span></td>
												<td colspan='3'>
													<video width='100%' controls>
														<source src='https://localhost/www/TDUONG/tailieu/$data0[ID_DM]/$data0[short_con]' type='video/mp4' />
														Trình duyệt đã cũ, hãy nâng cấp!
													</video>
												</td>
											</tr>";
										}
										if($data0["type"]=="youtube") {
											echo"<tr>
												<td><span>Video cũ</span></td>
												<td colspan='3'>
													<iframe width='100%' height='' src='$data0[short_con]' frameborder='0' allowfullscreen></iframe>
												</td>
											</tr>";
										}
									?>
                                    <tr>
                                        <td colspan="4"><button class="submit" style="width:50%;font-size:1.375em;" id="up" name="up">Sửa tài liệu</button></td>
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