<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
		$lmID=$_GET["lm"];
        $monID=$_GET["mon"];
	} else {
		$lmID=0;
        $MonID=0;
	}
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>UP TÀI LIỆU</title>
        
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

            $("#danhmuc").change(function() {
                if($(this).find("option:selected").val() != 0) {
                    $("#danhmuc2 option:first-child").prop("selected",true);
                }
            });

            $("#danhmuc2").change(function() {
                if($(this).find("option:selected").val() != 0) {
                    $("#danhmuc option:first-child").prop("selected",true);
                }
            });
			
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
						$pic="none";
					}
					if($loai=="video") {
						if(!is_dir("../tailieu/$danhmuc")){
							mkdir("../tailieu/$danhmuc");
						}
						move_uploaded_file($_FILES["con-video"]["tmp_name"],"../tailieu/$danhmuc/".$_FILES["con-video"]["name"]);	
						up_tailieu_short($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where);
					} else if($loai=="baiviet") {
						up_tailieu_full($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where);
					} else {
						up_tailieu_short($title, $intro, $content, $loai, $pic, $price, $danhmuc, $where);
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
                	<h2>UP TÀI LIỆU <span style="font-weight:600;">Môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/up-tai-lieu/<?php echo $lmID."/".$monID; ?>/" method="post" enctype="multipart/form-data">
                            	<table class="table">
                                	<tr>
                                    	<td><span>Tiêu đề *</span></td>
                                        <td colspan="3"><input name="title" id="title" class="input" type="text" autocomplete="off" /></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Giới thiệu</span></td>
                                        <td colspan="3"><textarea name="intro" id="intro" style="height:50px;" class="input"></textarea></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Giá tiền</span></td>
                                        <td><input type="number" class="input" min="0" name="price" placeholder="30000" id="price" /></td>
                                    	<td colspan="2"><span>Không cần nhập nếu FREE</span></td>
                                    </tr>
                                    <tr>
                                    	<td><span>Ảnh đại diện</span></td>
                                        <td colspan="2"><input type="file" class="input" name="pic" id="pic" /></td>
                                    	<td><span>Ảnh vuông</span></td>
                                    </tr>
                                    <tr>
                                    	<td style="width:25%;"><span>Chuyên đề *</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="danhmuc" name="danhmuc">
                                            	<option value="0">Chọn chuyên đề</option>
                                                <?php
													$result=get_all_chuyende_all($lmID);
													while($data=mysqli_fetch_assoc($result)) {
														echo"<option value='$data[ID_CD]'>$data[name] - $data[title]</option>";
													}
												?>
                                            </select>
                                            <span style="display: block;margin: 5px 0 5px 0;">Hoặc</span>
                                            <select class="input" style="height:auto;" id="danhmuc2" name="danhmuc2">
                                                <option value="0">Chọn danh mục ngoài chuyên đề</option>
                                                <?php
                                                $result=get_all_danhmuc($monID);
                                                while($data=mysqli_fetch_assoc($result)) {
                                                    echo"<option value='$data[ID_DM]'>$data[name] - $data[title]</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td style="width:25%;"><span>Loại *</span></td>
                                        <td style="width:25%;">
                                        	<select class="input" style="height:auto;" id="loai" name="loai">
                                            	<option value="none">Chọn loại</option>
                                                <option value="baiviet">Bài viết</option>
                                                <option value="link">Link ngoài</option>
                                                <option value="video">Video trực tiếp</option>
                                                <option value="youtube">Link youtube</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="baiviet" class="con">
                                    	<td colspan="4"><textarea name="content" id="content" style="height:auto;"></textarea></td>
                                    </tr>
                                    <tr id="link" class="con">
                                    	<td><span>Link *</span></td>
                                    	<td colspan="3"><input class="input" name="con-link" id="con-link" type="text" placeholder="Link trang cần chia sẻ..." /></td>
                                    </tr>
                                    <tr id="video" class="con">
                                    	<td><span>Up video *</span></td>
                                        <td colspan="3"><input class="input" name="con-video" id="con-video" type="file" /></td>
                                    </tr>
                                    <tr id="youtube" class="con">
                                    	<td><span>Youtube *</span></td>
                                    	<td colspan="3"><input class="input" name="con-you" id="con-you" type="text" placeholder="https://www.youtube.com/embed/KbPigceQhbI" /></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><button class="submit" style="width:50%;font-size:1.375em;" id="up" name="up">Up tài liệu</button></td>
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